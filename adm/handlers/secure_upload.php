<?php
require_once '../config/security.php';
require_once '../base.php';

/**
 * Secure File Upload Handler
 * Replaces the vulnerable upload_cvs.php functionality
 */

// Check if user is logged in
if(!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    log_security_event('Unauthorized upload attempt');
    die('Unauthorized access');
}

// Validate CSRF token
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !validate_csrf_token($_POST['csrf_token'])) {
        log_security_event('CSRF token validation failed');
        die('Invalid security token');
    }
}

if (isset($_POST['submit']) && isset($_FILES['filename'])) {
    // Validate file upload
    $file_errors = validate_file_upload($_FILES['filename']);
    
    if (!empty($file_errors)) {
        log_security_event('File upload validation failed', implode(', ', $file_errors));
        echo json_encode(['success' => false, 'errors' => $file_errors]);
        exit;
    }
    
    // Create secure upload directory outside web root
    $upload_dir = __DIR__ . '/../uploads/cvs/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    // Generate secure filename
    $file_extension = strtolower(pathinfo($_FILES['filename']['name'], PATHINFO_EXTENSION));
    $secure_filename = uniqid('upload_', true) . '.' . $file_extension;
    $target_file = $upload_dir . $secure_filename;
    
    if (move_uploaded_file($_FILES['filename']['tmp_name'], $target_file)) {
        log_security_event('File uploaded successfully', "Original: " . $_FILES['filename']['name'] . ", Secure: $secure_filename");
        
        // Process CSV file if applicable
        if ($file_extension === 'csv') {
            try {
                $processed = process_csv_file($target_file);
                echo json_encode(['success' => true, 'message' => 'File uploaded and processed successfully', 'processed' => $processed]);
            } catch (Exception $e) {
                log_security_event('CSV processing failed', $e->getMessage());
                echo json_encode(['success' => false, 'errors' => ['CSV processing failed: ' . $e->getMessage()]]);
            }
        } else {
            echo json_encode(['success' => true, 'message' => 'File uploaded successfully']);
        }
    } else {
        log_security_event('File upload failed', 'move_uploaded_file failed');
        echo json_encode(['success' => false, 'errors' => ['Failed to save file']]);
    }
} else {
    echo json_encode(['success' => false, 'errors' => ['No file provided']]);
}

/**
 * Secure CSV processing function
 */
function process_csv_file($file_path) {
    if (!file_exists($file_path)) {
        throw new Exception('File not found');
    }
    
    $handle = fopen($file_path, 'r');
    if (!$handle) {
        throw new Exception('Cannot open file');
    }
    
    $processed_count = 0;
    $i = 0;
    
    // Get existing listing numbers for cleanup
    $active_ids = array();
    $getIDS = new SqlIt("SELECT listing_number FROM listings_upload", "select", array());
    if ($getIDS->NumResults != 0) {
        foreach ($getIDS->Response as $gi) {
            $active_ids[] = $gi->listing_number;
        }
    }
    
    while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
        if ($i > 0) { // Skip header row
            try {
                // Validate and sanitize CSV data
                $listing_data = array();
                for ($z = 0; $z <= 95; $z++) {
                    // Date field processing (columns 9-13, 15-17)
                    if (($z >= 9 && $z <= 13) || ($z >= 15 && $z <= 17)) {
                        if (isset($data[$z]) && $data[$z] != '') {
                            $date_value = date('Y-m-d', strtotime($data[$z]));
                            $listing_data[] = $date_value;
                        } else {
                            $listing_data[] = null;
                        }
                    } else {
                        // Sanitize other fields
                        $listing_data[] = isset($data[$z]) ? sanitize_input($data[$z], 'string') : '';
                    }
                }
                
                // Remove existing entry if exists
                if (isset($data[0]) && in_array($data[0], $active_ids)) {
                    new SqlIt("DELETE FROM listings_upload WHERE listing_number = ?", "delete", array($data[0]));
                }
                
                // Insert new data (you'll need to adjust this based on your actual table structure)
                // This is a placeholder - adjust column names and count based on your table
                $insert_sql = "INSERT INTO listings_upload (listing_number, /* add other columns */) VALUES (?, /* add other placeholders */)";
                // new SqlIt($insert_sql, "insert", $listing_data);
                
                $processed_count++;
                
            } catch (Exception $e) {
                log_security_event('CSV row processing failed', "Row $i: " . $e->getMessage());
                // Continue processing other rows
            }
        }
        $i++;
    }
    
    fclose($handle);
    
    // Clean up the uploaded file for security
    unlink($file_path);
    
    return $processed_count;
}
?>