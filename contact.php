<?php
$ppage = 5;
require('base.php');

$current_link['en'] = $link_contact['en'];
$current_link['es'] = $link_contact['es'];

// Helper function to format contact information
function getContactDisplay($contactInfo, $type) {
    $result = array();
    if(!empty($contactInfo[$type])) {
        foreach($contactInfo[$type] as $contact) {
            $result[] = $contact;
        }
    }
    return $result;
}

// Get contact information
$contact_phones = getContactDisplay($site_contact->ContactInfo, 'contact_page');
$office_info = getContactDisplay($site_contact->ContactInfo, 'office_info');

// Separate office info by type
$office_phones = array();
$office_location = array();
$office_availability = array();

// Extract coordinates for map
$map_coordinates = array();
$has_map_data = false;

foreach($office_info as $info) {
    switch($info['type']) {
        case 'phone':
            $office_phones[] = $info;
            break;
        case 'location':
            $office_location[] = $info;
            // Extract coordinates from location data
            if(!empty($info['contact_meta'])) {
                $meta = json_decode($info['contact_meta'], true);
                if(isset($meta['latitude']) && isset($meta['longitude']) && 
                   !empty($meta['latitude']) && !empty($meta['longitude'])) {
                    $map_coordinates[] = array(
                        'lat' => floatval($meta['latitude']),
                        'lng' => floatval($meta['longitude']),
                        'title' => !empty($info['title']) ? $info['title'] : 'Office Location',
                        'address' => $info['val']
                    );
                    $has_map_data = true;
                }
            }
            break;
        case 'availability':
            $office_availability[] = $info;
            break;
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <base href="<?= $base_href ?>" >
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?= $meta['contact']['title'] ?> - Casa Novara</title>
    <meta name="robots" content="index" />
    <link rel="canonical" href="https://casanovaragroup.com/<?= $link_contact[$lang] ?>">
    <meta name="description" content="<?= $meta['contact']['desc'] ?>">

    <meta property="og:title" content="<?= $meta['contact']['title'] ?> - Casa Novara">
    <meta property="og:description" content="<?= $meta['contact']['desc'] ?>">
    <meta property="og:image" content="https://casanovaragroup.com/dist/img/social.jpg">
    <meta property="og:url" content="https://casanovaragroup.com/<?= $link_contact[$lang] ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta property="og:site_name" content="Casa Novara">
    <meta name="twitter:image:alt" content="<?= $meta['contact']['title'] ?>">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>

    <!-- Custom CSS -->
    <link href="<?= $assets_prefix ?>/dist/css/cng_base.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?= $assets_prefix ?>/dist/css/cng.css" />
    <script src="https://cdn.jsdelivr.net/npm/less@4.1.3/dist/less.min.js"></script>
    
    <!-- Contact Page Specific Styles -->
    <style>
        /* Ensure all container-fluid elements have consistent padding */
        .breadcrumb-section .container-fluid,
        .contact-hero .container-fluid,
        .contact-content .container-fluid {
            padding-left: 15px;
            padding-right: 15px;
        }
        
        .contact-hero{
           background-size: 100%;
        }
        @media (min-width: 576px) {
            .breadcrumb-section .container-fluid,
            .contact-hero .container-fluid,
            .contact-content .container-fluid {
                padding-left: 20px;
                padding-right: 20px;
            }
            .contact-hero{
              background-size: cover;
            }
        }
        
        @media (min-width: 768px) {
            .breadcrumb-section .container-fluid,
            .contact-hero .container-fluid,
            .contact-content .container-fluid {
                padding-left: 24px;
                padding-right: 24px;
            }
            .contact-hero{
              background-size: cover;
            }
        }

        .contact-hero {
            background: linear-gradient(135deg, #1a365d 0%, #2d5a87 100%);
            background: url('dist/img/beach.jpg') no-repeat;
           
            color: white;
            padding: 80px 0 60px;
            position: relative;
            z-index: 0;
        }
        .contact-hero > .container-fluid{
          position:relative;
          z-index: 10;
        }

        .contact-hero h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            font-family: 'Inter', sans-serif;
            font-weight: 300;
        }

        .contact-hero p {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-bottom: 0;
        }

        .contact-info-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            border: 1px solid #e2e8f0;
        }

        .contact-info-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .contact-info-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .contact-info-icon {
            background: #048384;
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1.2rem;
        }
        .contact-info-icon a{
          color: #1f8c9b;
        }

        .contact-form-card {
            background: white;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border: 1px solid #e2e8f0;
        }

        .form-control {
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .btn-contact {
            background: #3b82f6;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .btn-contact:hover {
            background: #2563eb;
            transform: translateY(-2px);
        }

        .map-container {
            height: 400px;
            border-radius: 12px;
            overflow: hidden;
            margin-top: 2rem;
        }

        .office-hours {
            background: #f8fafc;
            border-radius: 8px;
            padding: 1.5rem;
            margin-top: 1.5rem;
        }

        .office-hours h5 {
            color: #1e293b;
            margin-bottom: 1rem;
        }

        .hours-item {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .hours-item:last-child {
            border-bottom: none;
        }
        section.contact-hero:after {
            content: '';
            position: absolute;
            background: #00000052;
            width: 100%;
            height: 100%;
            top: 0;
            z-index: 1;
        }
        section.contact-content {
    position: relative;
}
    </style>

    <?php include 'dist/inc/favicon.php'; ?>
</head>
<body class="cng-contact-page">
    <?php require 'dist/inc/nav-inner.php';?>

    <!-- Contact Hero Section -->
    <section class="contact-hero">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-8 col-md-10 mx-auto text-center">
                    <h1><?= $lan['con']['h1'] ?? 'Contact Casa Novara' ?></h1>
                    <p><?= $lan['con']['subtitle'] ?? 'Get in touch with our luxury real estate experts. We\'re here to help you find your dream property in Puerto Vallarta and the Riviera Maya.' ?></p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Content Section -->
    <section class="contact-content">
        <div class="container-fluid">
            <div class="row g-4" style="margin-top: -40px;">
                <!-- Contact Information -->
                <div class="col-lg-4 col-md-6">
                    <div class="contact-info-card">
                        <h3 style="margin-bottom: 2rem; color: #1e293b;">Get In Touch</h3>
                        
                        <!-- Office Location -->
                        <?php if(!empty($office_location)): ?>
                            <?php foreach($office_location as $location): ?>
                                <div class="contact-info-item">
                                    <div class="contact-info-icon">
                                        <i class="bi bi-geo-alt"></i>
                                    </div>
                                    <div>
                                        <h5 style="margin-bottom: 0.5rem; color: #1e293b;">
                                            <?= !empty($location['title']) ? htmlspecialchars($location['title']) : 'Office Location' ?>
                                        </h5>
                                        <p style="margin: 0; color: #64748b;">
                                            <?= nl2br(htmlspecialchars($location['val'])) ?>
                                        </p>
                                        <?php if(!empty($location['description'])): ?>
                                            <small style="color: #64748b;"><?= htmlspecialchars($location['description']) ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <!-- Fallback static location -->
                            <div class="contact-info-item">
                                <div class="contact-info-icon">
                                    <i class="bi bi-geo-alt"></i>
                                </div>
                                <div>
                                    <h5 style="margin-bottom: 0.5rem; color: #1e293b;">Office Location</h5>
                                    <p style="margin: 0; color: #64748b;">
                                        Marina Vallarta<br>
                                        Puerto Vallarta, Jalisco<br>
                                        Mexico 48354
                                    </p>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Phone Numbers -->
                        <?php 
                        // Collect all phone numbers from both sources
                        $all_phones = array();
                        if(!empty($office_phones)) {
                            $all_phones = array_merge($all_phones, $office_phones);
                        }
                        if(!empty($contact_phones)) {
                            foreach($contact_phones as $contact) {
                                if($contact['type'] == 'phone') {
                                    $all_phones[] = $contact;
                                }
                            }
                        }
                        
                        if(!empty($all_phones)): ?>
                            <div class="contact-info-item">
                                <div class="contact-info-icon">
                                    <i class="bi bi-telephone"></i>
                                </div>
                                <div>
                                    <h5 style="margin-bottom: 1rem; color: #1e293b;">Phone Numbers</h5>
                                    <?php foreach($all_phones as $index => $phone): ?>
                                        <div style="<?= $index > 0 ? 'margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #f1f5f9;' : '' ?>">
                                            <p style="margin: 0; font-weight: 500;">
                                                <?php if($phone['is_whatsapp'] == 1): ?>
                                                    <i class="bi bi-whatsapp" style="color: #25d366; margin-right: 5px;"></i>
                                                    <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $phone['val']) ?>" target="_blank" style="color: #3b82f6; text-decoration: none;">
                                                        <?= htmlspecialchars($phone['val']) ?>
                                                    </a>
                                                    <?php if(!empty($phone['title'])): ?>
                                                        <span style="color: #64748b;"> - <?= htmlspecialchars($phone['title']) ?></span>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <a href="tel:<?= preg_replace('/[^0-9+]/', '', $phone['val']) ?>" style="color: #3b82f6; text-decoration: none;">
                                                        <?= htmlspecialchars($phone['val']) ?>
                                                    </a>
                                                    <?php if(!empty($phone['title'])): ?>
                                                        <span style="color: #64748b;"> - <?= htmlspecialchars($phone['title']) ?></span>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </p>
                                            <?php if(!empty($phone['description'])): ?>
                                                <small style="color: #64748b; display: block; margin-top: 0.25rem;"><?= htmlspecialchars($phone['description']) ?></small>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Email Addresses -->
                        <?php 
                        // Collect all emails from contact_phones
                        $all_emails = array();
                        if(!empty($contact_phones)) {
                            foreach($contact_phones as $contact) {
                                if($contact['type'] == 'email') {
                                    $all_emails[] = $contact;
                                }
                            }
                        }
                        
                        if(!empty($all_emails)): ?>
                            <div class="contact-info-item">
                                <div class="contact-info-icon">
                                    <i class="bi bi-envelope"></i>
                                </div>
                                <div>
                                    <h5 style="margin-bottom: 1rem; color: #1e293b;">Email Addresses</h5>
                                    <?php foreach($all_emails as $index => $email): ?>
                                        <div style="<?= $index > 0 ? 'padding-top: 1rem;' : '' ?>">
                                            <p style="margin: 0;">
                                                <a href="mailto:<?= htmlspecialchars($email['val']) ?>" style="color: #3b82f6; text-decoration: none;">
                                                    <?= htmlspecialchars($email['val']) ?>
                                                </a>
                                                <?php if(!empty($email['title'])): ?>
                                                    <span style="color: #64748b;"> - <?= htmlspecialchars($email['title']) ?></span>
                                                <?php endif; ?>
                                            </p>
                                            <?php if(!empty($email['description'])): ?>
                                                <small style="color: #64748b; display: block; margin-top: 0.25rem;"><?= htmlspecialchars($email['description']) ?></small>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Office Hours -->
                        <?php if(!empty($office_availability)): ?>
                            <div class="office-hours">
                                <h5>Office Hours</h5>
                                <?php foreach($office_availability as $hours): ?>
                                    <div class="hours-item">
                                        <span style="font-weight: 500;">
                                            <?= !empty($hours['title']) ? htmlspecialchars($hours['title']) : 'Business Hours' ?>
                                        </span>
                                        <span style="color: #64748b;"><?= htmlspecialchars($hours['val']) ?></span>
                                    </div>
                                    <?php if(!empty($hours['description'])): ?>
                                        <div class="hours-description" style="margin-left: 0; color: #64748b; font-size: 0.85em;">
                                            <?= htmlspecialchars($hours['description']) ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <!-- Fallback static hours -->
                            <div class="office-hours">
                                <h5>Office Hours</h5>
                                <div class="hours-item">
                                    <span style="font-weight: 500;">Monday - Friday</span>
                                    <span style="color: #64748b;">9:00 AM - 6:00 PM</span>
                                </div>
                                <div class="hours-item">
                                    <span style="font-weight: 500;">Saturday</span>
                                    <span style="color: #64748b;">10:00 AM - 4:00 PM</span>
                                </div>
                                <div class="hours-item">
                                    <span style="font-weight: 500;">Sunday</span>
                                    <span style="color: #64748b;">By Appointment</span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="col-lg-8 col-md-6">
                    <div class="contact-form-card">
                        <h3 style="margin-bottom: 1.5rem; color: #1e293b;">Send Us a Message</h3>
                        <p style="color: #64748b; margin-bottom: 2rem;">Ready to find your dream property? Fill out the form below and our expert team will get back to you within 24 hours.</p>
                        
                        <form method="post" action="dist/inc/process/contact_submit.php" id="contact_form">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="full_name" class="form-label" style="font-weight: 500; color: #374151;">Full Name *</label>
                                    <input type="text" name="full_name" id="full_name" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label" style="font-weight: 500; color: #374151;">Email Address *</label>
                                    <input type="email" name="email" id="email" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="form-label" style="font-weight: 500; color: #374151;">Phone Number</label>
                                    <input type="tel" name="phone" id="phone" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label for="subject" class="form-label" style="font-weight: 500; color: #374151;">Subject</label>
                                    <select name="subject" id="subject" class="form-control">
                                        <option value="">Select a topic</option>
                                        <option value="property_inquiry">Property Inquiry</option>
                                        <option value="buying">Buying a Property</option>
                                        <option value="selling">Selling a Property</option>
                                        <option value="investment">Investment Opportunities</option>
                                        <option value="general">General Information</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="message" class="form-label" style="font-weight: 500; color: #374151;">Message *</label>
                                    <textarea name="message" id="message" class="form-control" rows="5" placeholder="Tell us about your property needs, preferred locations, budget range, or any specific questions you have..." required></textarea>
                                </div>
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="newsletter" id="newsletter">
                                        <label class="form-check-label" for="newsletter" style="color: #64748b;">
                                            I would like to receive updates about new properties and market insights
                                        </label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary btn-contact">
                                        <i class="bi bi-send"></i>
                                        Send Message
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Map Section - Only show if we have coordinates -->
                    <?php if($has_map_data): ?>
                        <div id="contact-map" class="map-container"></div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Additional Information Section -->
            <div class="row g-4" style="margin-top: 3rem;">
                <div class="col-12">
                    <div class="contact-info-card">
                        <div class="row g-4">
                            <div class="col-lg-4 text-center">
                                <div class="contact-info-icon mx-auto" style="margin-bottom: 1rem;">
                                    <i class="bi bi-award"></i>
                                </div>
                                <h5 style="color: #1e293b;">Expert Knowledge</h5>
                                <p style="color: #64748b; margin: 0;">Over 10 years of experience in Puerto Vallarta and Riviera Maya real estate markets.</p>
                            </div>
                            <div class="col-lg-4 text-center">
                                <div class="contact-info-icon mx-auto" style="margin-bottom: 1rem;">
                                    <i class="bi bi-shield-check"></i>
                                </div>
                                <h5 style="color: #1e293b;">Trusted Service</h5>
                                <p style="color: #64748b; margin: 0;">Licensed professionals committed to providing transparent and ethical real estate services.</p>
                            </div>
                            <div class="col-lg-4 text-center">
                                <div class="contact-info-icon mx-auto" style="margin-bottom: 1rem;">
                                    <i class="bi bi-globe"></i>
                                </div>
                                <h5 style="color: #1e293b;">Global Reach</h5>
                                <p style="color: #64748b; margin: 0;">Serving international clients with multilingual support and comprehensive relocation assistance.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php require 'dist/inc/foot.php'; ?>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
     integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
     crossorigin=""></script>
    
    <script>
        <?php if($has_map_data): ?>
        // Initialize Leaflet Map for office location(s)
        $(document).ready(function() {
            // Get coordinates from PHP
            var locations = <?= json_encode($map_coordinates) ?>;
            
            if(locations.length > 0) {
                // Use first location as center, or calculate center if multiple locations
                var centerLat = locations.length === 1 ? locations[0].lat : 
                    locations.reduce((sum, loc) => sum + loc.lat, 0) / locations.length;
                var centerLng = locations.length === 1 ? locations[0].lng : 
                    locations.reduce((sum, loc) => sum + loc.lng, 0) / locations.length;
                
                // Initialize map
                var map = L.map('contact-map').setView([centerLat, centerLng], 15);
                
                // Add OpenStreetMap tiles
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                    maxZoom: 19
                }).addTo(map);
                
                // Add markers for each location
                locations.forEach(function(location) {
                    var marker = L.marker([location.lat, location.lng]).addTo(map);
                    
                    var popupContent = '<div style="text-align: center;">' +
                        '<h6 style="margin-bottom: 8px; color: #1e293b;">' + location.title + '</h6>' +
                        '<p style="margin: 0; color: #64748b; font-size: 0.9em;">' + 
                        location.address.replace(/\n/g, '<br>') + '</p>' +
                        '</div>';
                    
                    marker.bindPopup(popupContent);
                });
                
                // If multiple locations, fit map to show all markers
                if(locations.length > 1) {
                    var group = new L.featureGroup(map._layers);
                    map.fitBounds(group.getBounds().pad(0.1));
                }
            }
        });
        <?php endif; ?>

        // Contact form submission
        $('#contact_form').on('submit', function(e) {
            e.preventDefault();
            
            // Add loading state
            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.html();
            submitBtn.html('<i class="bi bi-hourglass-split"></i> Sending...').prop('disabled', true);
            
            // Here you would typically submit the form via AJAX
            // For now, we'll just show a success message after 2 seconds
            setTimeout(() => {
                alert('Thank you for your message! We will get back to you within 24 hours.');
                this.reset();
                submitBtn.html(originalText).prop('disabled', false);
            }, 2000);
        });
    </script>

</body>
</html>

