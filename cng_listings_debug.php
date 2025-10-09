<?php
$ppage = 2;
require('base.php');

// Simple debug page to test URL routing
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Listings Debug - Casa Novara</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
                        <h1>Listings Debug Page</h1>
                        <p class="text-muted">This is a temporary debug page to test the listings functionality.</p>
                        
                        <h3>Request Information:</h3>
                        <ul>
                            <li><strong>URL:</strong> <?= $_SERVER['REQUEST_URI'] ?></li>
                            <li><strong>Query String:</strong> <?= $_SERVER['QUERY_STRING'] ?></li>
                            <li><strong>Method:</strong> <?= $_SERVER['REQUEST_METHOD'] ?></li>
                        </ul>
                        
                        <h3>GET Parameters:</h3>
                        <?php if(!empty($_GET)): ?>
                            <pre><?= print_r($_GET, true) ?></pre>
                        <?php else: ?>
                            <p>No GET parameters</p>
                        <?php endif; ?>
                        
                        <h3>Database Connection:</h3>
                        <?php
                        try {
                            $test_query = new SqlIt("SELECT COUNT(*) as property_count FROM property_list WHERE pr_status = 'active'", "select", array());
                            if($test_query->NumResults > 0) {
                                echo "<p class='text-success'>✅ Database connected successfully</p>";
                                echo "<p>Total active properties: " . $test_query->Response[0]->property_count . "</p>";
                            }
                        } catch (Exception $e) {
                            echo "<p class='text-danger'>❌ Database error: " . $e->getMessage() . "</p>";
                        }
                        ?>
                        
                        <h3>Property Types:</h3>
                        <?php
                        try {
                            $types_query = new SqlIt("SELECT * FROM property_types", "select", array());
                            if($types_query->NumResults > 0) {
                                echo "<ul>";
                                foreach($types_query->Response as $type) {
                                    echo "<li>ID: {$type->pr_type_id} - {$type->type_desc}</li>";
                                }
                                echo "</ul>";
                            } else {
                                echo "<p>No property types found</p>";
                            }
                        } catch (Exception $e) {
                            echo "<p class='text-danger'>Error getting property types: " . $e->getMessage() . "</p>";
                        }
                        ?>
                        
                        <div class="alert alert-info mt-4">
                            <h4>Next Steps:</h4>
                            <ol>
                                <li>Add some test properties to the database</li>
                                <li>Create/fix the missing search classes</li>
                                <li>Restore the full listings functionality</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php require 'dist/inc/foot.php'; ?>
</body>
</html>