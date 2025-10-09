<?php
$ppage = 2;
require('base.php');

// Simple debug page to test URL routing
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <base href="<?= $base_href ?>" >
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Property Listings - Casa Novara</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="dist/css/cng_base.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="dist/css/cng.css" />
    <style>
        .debug-container {
            padding: 60px 0;
            min-height: 60vh;
        }
        .debug-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 2rem;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <?php require 'dist/inc/nav-inner.php'; ?>
    
    <div class="debug-container">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="debug-info">
                        <h1>Property Listings</h1>
                        <p class="text-muted">We're setting up the property listings system for Casa Novara.</p>
                        
                        <div class="alert alert-info">
                            <h4><i class="bi bi-info-circle"></i> Property Search Active</h4>
                            <p class="mb-0">
                                <?php 
                                if(isset($_GET['adv_property_types']) && $_GET['adv_property_types'][0] == '20') {
                                    echo "Searching for <strong>Residential Properties</strong>";
                                } elseif(isset($_GET['adv_property_types']) && $_GET['adv_property_types'][0] == '3') {
                                    echo "Searching for <strong>Commercial Properties</strong>";
                                } elseif(isset($_GET['adv_property_types']) && $_GET['adv_property_types'][0] == '21') {
                                    echo "Searching for <strong>Lots & Land</strong>";
                                } else {
                                    echo "Searching for <strong>All Properties</strong>";
                                }
                                
                                if(isset($_GET['price_min']) && isset($_GET['price_max'])) {
                                    $min = number_format($_GET['price_min']);
                                    $max = $_GET['price_max'] >= 1000000000 ? 'No limit' : '$' . number_format($_GET['price_max']);
                                    echo " in price range $" . $min . " - " . $max;
                                }
                                ?>
                            </p>
                            
                            <?php
                            // Try to get property count from database
                            try {
                                $property_type_condition = "";
                                $params = array();
                                
                                if(isset($_GET['adv_property_types']) && !empty($_GET['adv_property_types'][0])) {
                                    $property_type_condition = "AND p.pr_type_id = ?";
                                    $params[] = $_GET['adv_property_types'][0];
                                }
                                
                                $count_query = new SqlIt("SELECT COUNT(*) as property_count FROM property_list p WHERE p.pr_status = 'active' AND p.is_visible = 1 $property_type_condition", "select", $params);
                                
                                if($count_query->NumResults > 0) {
                                    $count = $count_query->Response[0]->property_count;
                                    if($count > 0) {
                                        echo "<div class='alert alert-success mt-2'>";
                                        echo "<strong>Found $count matching properties!</strong> The listings system is being configured to display them.";
                                        echo "</div>";
                                    } else {
                                        echo "<div class='alert alert-warning mt-2'>";
                                        echo "<strong>No matching properties found.</strong> Please try a different search or contact us directly.";
                                        echo "</div>";
                                    }
                                }
                            } catch (Exception $e) {
                                echo "<div class='alert alert-warning mt-2'>";
                                echo "<strong>Database connection issue.</strong> Please <a href='contact-us'>contact us directly</a> for property inquiries.";
                                echo "</div>";
                            }
                            ?>
                        </div>
                        
                        <?php
                        // Try to display actual properties if they exist
                        try {
                            $property_type_condition = "";
                            $params = array();
                            
                            if(isset($_GET['adv_property_types']) && !empty($_GET['adv_property_types'][0])) {
                                $property_type_condition = "AND p.pr_type_id = ?";
                                $params[] = $_GET['adv_property_types'][0];
                            }
                            
                            $properties_query = new SqlIt("
                                SELECT p.property_id, p.property_title, p.address, p.bedrooms, p.bathrooms, 
                                       pt.type_desc, c.location as city_name,
                                       (SELECT price_amt FROM property_pricing WHERE property_id = p.property_id ORDER BY price_amt ASC LIMIT 1) as min_price
                                FROM property_list p 
                                LEFT JOIN property_types pt ON p.pr_type_id = pt.pr_type_id
                                LEFT JOIN locations_cities c ON p.city = c.city_id
                                WHERE p.pr_status = 'active' AND p.is_visible = 1 $property_type_condition 
                                ORDER BY p.property_id DESC 
                                LIMIT 6", "select", $params);
                            
                            if($properties_query->NumResults > 0) {
                                echo "<h3 class='mt-4'>Available Properties</h3>";
                                echo "<div class='row'>";
                                
                                foreach($properties_query->Response as $property) {
                                    echo "<div class='col-md-4 mb-4'>";
                                    echo "<div class='card h-100'>";
                                    echo "<div class='card-body'>";
                                    echo "<h5 class='card-title'>" . htmlspecialchars($property->property_title) . "</h5>";
                                    echo "<p class='card-text'>";
                                    echo "<strong>Type:</strong> " . htmlspecialchars($property->type_desc) . "<br>";
                                    if($property->address) echo "<strong>Location:</strong> " . htmlspecialchars($property->address) . "<br>";
                                    if($property->city_name) echo "<strong>City:</strong> " . htmlspecialchars($property->city_name) . "<br>";
                                    if($property->bedrooms > 0) echo "<strong>Bedrooms:</strong> " . $property->bedrooms . "<br>";
                                    if($property->bathrooms > 0) echo "<strong>Bathrooms:</strong> " . $property->bathrooms . "<br>";
                                    if($property->min_price > 0) echo "<strong>Starting at:</strong> $" . number_format($property->min_price) . "<br>";
                                    echo "</p>";
                                    echo "<a href='contact-us' class='btn btn-primary'>Inquire About This Property</a>";
                                    echo "</div>";
                                    echo "</div>";
                                    echo "</div>";
                                }
                                
                                echo "</div>";
                                
                                if($properties_query->NumResults >= 6) {
                                    echo "<div class='text-center mt-3'>";
                                    echo "<p><em>Showing first 6 properties. Contact us to see all available properties.</em></p>";
                                    echo "</div>";
                                }
                            }
                        } catch (Exception $e) {
                            // Silently fail - the alert above will handle the error message
                        }
                        ?>
                        
                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h5>🏠 Residential</h5>
                                        <p>Beautiful homes and condominiums</p>
                                        <a href="contact-us" class="btn btn-primary btn-sm">Inquire Now</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h5>🏢 Commercial</h5>
                                        <p>Investment and business properties</p>
                                        <a href="contact-us" class="btn btn-primary btn-sm">Inquire Now</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <h5>🏞️ Lots & Land</h5>
                                        <p>Development opportunities</p>
                                        <a href="contact-us" class="btn btn-primary btn-sm">Inquire Now</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <?php if($_SERVER['QUERY_STRING']): ?>
                        <div class="mt-4">
                            <h5>Technical Information (Debug)</h5>
                            <div class="bg-light p-3 rounded">
                                <p><strong>Requested URL:</strong> <?= $_SERVER['REQUEST_URI'] ?></p>
                                <p><strong>Query Parameters:</strong> <?= $_SERVER['QUERY_STRING'] ?></p>
                                <?php if(!empty($_GET)): ?>
                                    <pre><?= print_r($_GET, true) ?></pre>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php require 'dist/inc/foot.php'; ?>
</body>
</html>