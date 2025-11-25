<?php
// Contact Form Processing for Property Inquiries
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Start session and include necessary files
session_start();

// Include base configuration
$base_path = '../../../';
require_once($base_path . 'base.php');

// Initialize response array
$response = [
    'success' => false,
    'message' => 'An error occurred while processing your request.',
    'errors' => []
];

// Check if form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Invalid request method.';
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Sanitize and validate input data
$full_name = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$message = trim($_POST['message'] ?? '');
$prop_id = trim($_POST['prop_id'] ?? '');

// Validation
if (empty($full_name)) {
    $response['errors']['full_name'] = 'Full name is required.';
}

if (empty($email)) {
    $response['errors']['email'] = 'Email address is required.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $response['errors']['email'] = 'Please enter a valid email address.';
}

if (!empty($phone) && !preg_match('/^[\+]?[0-9\s\-\(\)]{10,}$/', $phone)) {
    $response['errors']['phone'] = 'Please enter a valid phone number.';
}

// If there are validation errors, return them
if (!empty($response['errors'])) {
    $response['message'] = 'Please correct the errors below.';
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Prepare data for database insertion
$inquiry_data = [
    'full_name' => htmlspecialchars($full_name, ENT_QUOTES, 'UTF-8'),
    'email' => $email,
    'phone' => htmlspecialchars($phone, ENT_QUOTES, 'UTF-8'),
    'message' => htmlspecialchars($message, ENT_QUOTES, 'UTF-8'),
    'property_id' => $prop_id,
    'inquiry_type' => 'property_viewing',
    'source' => 'listing_page',
    'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
    'created_at' => date('Y-m-d H:i:s')
];

try {
    // Insert into property_requests table
    $sql = "INSERT INTO property_requests (
        full_name, 
        email, 
        phone, 
        message, 
        property_id, 
        inquiry_type, 
        source, 
        ip_address, 
        user_agent, 
        created_at
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $insert_inquiry = new SqlIt($sql, "insert", [
        $inquiry_data['full_name'],
        $inquiry_data['email'], 
        $inquiry_data['phone'],
        $inquiry_data['message'],
        $inquiry_data['property_id'],
        $inquiry_data['inquiry_type'],
        $inquiry_data['source'],
        $inquiry_data['ip_address'],
        $inquiry_data['user_agent'],
        $inquiry_data['created_at']
    ]);

    // Prepare email content
    $email_subject = "New Property Inquiry - " . ($prop_id ? "Property #$prop_id" : "General Inquiry");
    
    $email_body = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #007bff; color: white; padding: 20px; text-align: center; }
            .content { padding: 20px; background: #f8f9fa; }
            .property-info { background: white; padding: 15px; margin: 10px 0; border-left: 4px solid #007bff; }
            .contact-info { background: white; padding: 15px; margin: 10px 0; }
            .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>New Property Inquiry</h2>
                <p>Casa Novara Real Estate</p>
            </div>
            
            <div class='content'>
                <div class='contact-info'>
                    <h3>Contact Information</h3>
                    <p><strong>Name:</strong> {$inquiry_data['full_name']}</p>
                    <p><strong>Email:</strong> {$inquiry_data['email']}</p>
                    <p><strong>Phone:</strong> " . ($inquiry_data['phone'] ?: 'Not provided') . "</p>
                </div>
                
                " . ($prop_id ? "
                <div class='property-info'>
                    <h3>Property Interest</h3>
                    <p><strong>Property ID:</strong> $prop_id</p>
                    <p><strong>Inquiry Type:</strong> Private Tour Request</p>
                </div>
                " : "") . "
                
                <div class='contact-info'>
                    <h3>Message</h3>
                    <p>" . ($inquiry_data['message'] ?: 'No specific message provided.') . "</p>
                </div>
                
                <div class='contact-info'>
                    <h3>Technical Details</h3>
                    <p><strong>Submitted:</strong> {$inquiry_data['created_at']}</p>
                    <p><strong>Source:</strong> Property Listing Page</p>
                    <p><strong>IP Address:</strong> {$inquiry_data['ip_address']}</p>
                </div>
            </div>
            
            <div class='footer'>
                <p>This inquiry was submitted through the Casa Novara website.</p>
                <p>Please respond within 24 hours for optimal customer service.</p>
            </div>
        </div>
    </body>
    </html>
    ";

    // Get admin email from site configuration or use default
    $admin_email = 'info@casanovaragroup.com'; // Default fallback
    
    // Try to get admin email from site_contact if available
    if (isset($site_contact->ContactInfo['office_info'])) {
        foreach ($site_contact->ContactInfo['office_info'] as $contact) {
            if ($contact['type'] == 'email') {
                $admin_email = $contact['val'];
                break;
            }
        }
    }

    // Send email notification
    $headers = [
        'MIME-Version: 1.0',
        'Content-type: text/html; charset=UTF-8',
        'From: Casa Novara Website <noreply@casanovaragroup.com>',
        'Reply-To: ' . $inquiry_data['email'],
        'X-Mailer: PHP/' . phpversion()
    ];

    $mail_sent = mail($admin_email, $email_subject, $email_body, implode("\r\n", $headers));

    // Send confirmation email to customer
    $customer_subject = "Thank You for Your Interest - Casa Novara";
    $customer_body = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #007bff; color: white; padding: 20px; text-align: center; }
            .content { padding: 20px; }
            .footer { text-align: center; padding: 20px; color: #666; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>Thank You for Your Interest!</h2>
                <p>Casa Novara Real Estate</p>
            </div>
            
            <div class='content'>
                <p>Dear {$inquiry_data['full_name']},</p>
                
                <p>Thank you for your interest in our property" . ($prop_id ? " (ID: $prop_id)" : "") . ". We have received your inquiry and our expert team will get back to you within 24 hours.</p>
                
                <p>Your inquiry details:</p>
                <ul>
                    <li><strong>Submitted:</strong> {$inquiry_data['created_at']}</li>
                    <li><strong>Contact Email:</strong> {$inquiry_data['email']}</li>
                    " . ($inquiry_data['phone'] ? "<li><strong>Phone:</strong> {$inquiry_data['phone']}</li>" : "") . "
                </ul>
                
                <p>Our luxury property specialists are standing by to provide you with detailed information and arrange a personalized viewing at your convenience.</p>
                
                <p>If you have any immediate questions, please don't hesitate to contact us directly:</p>
                <p><strong>Phone:</strong> +52 322 123-4567<br>
                <strong>Email:</strong> info@casanovaragroup.com</p>
                
                <p>Best regards,<br>
                Casa Novara Real Estate Team</p>
            </div>
            
            <div class='footer'>
                <p>Casa Novara - Your Premier Real Estate Partner</p>
            </div>
        </div>
    </body>
    </html>
    ";

    $customer_headers = [
        'MIME-Version: 1.0',
        'Content-type: text/html; charset=UTF-8',
        'From: Casa Novara <info@casanovaragroup.com>',
        'X-Mailer: PHP/' . phpversion()
    ];

    mail($inquiry_data['email'], $customer_subject, $customer_body, implode("\r\n", $customer_headers));

    // Success response
    $response['success'] = true;
    $response['message'] = 'Thank you for your inquiry! We will contact you within 24 hours.';
    
} catch (Exception $e) {
    error_log("Contact form error: " . $e->getMessage());
    $response['message'] = 'Sorry, there was an error processing your request. Please try again or contact us directly.';
}

// Return JSON response for AJAX requests, otherwise redirect
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // For regular form submission, redirect back with status
    $redirect_url = $_SERVER['HTTP_REFERER'] ?? '/';
    $status = $response['success'] ? 'success' : 'error';
    $message = urlencode($response['message']);
    
    if (strpos($redirect_url, '?') !== false) {
        $redirect_url .= "&contact_status=$status&contact_message=$message";
    } else {
        $redirect_url .= "?contact_status=$status&contact_message=$message";
    }
    
    header("Location: $redirect_url");
}

exit;
?>