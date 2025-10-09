<?php
$ppage = 2;
require('base.php');

// Get property type counts for filter display
function getPropertyTypeCounts() {
    $counts = array();
    try {
        $query = new SqlIt("
            SELECT pt.pr_type_id, pt.type_desc, COUNT(p.property_id) as count
            FROM property_types pt 
            LEFT JOIN property_list p ON pt.pr_type_id = p.pr_type_id 
                AND p.pr_status = 'active' 
                AND p.is_visible = 1
            GROUP BY pt.pr_type_id, pt.type_desc
            ORDER BY pt.type_desc", "select", array());
        
        if($query->NumResults > 0) {
            foreach($query->Response as $type) {
                $counts[$type->pr_type_id] = array(
                    'name' => $type->type_desc,
                    'count' => $type->count
                );
            }
        }
    } catch (Exception $e) {
        // Return empty array on error
    }
    return $counts;
}

$property_type_counts = getPropertyTypeCounts();

// Get current search parameters
$search_params = array(
    'search_type' => $_GET['search_type'] ?? 'basic',
    'adv_property_types' => $_GET['adv_property_types'] ?? array(),
    'price_min' => $_GET['price_min'] ?? 0,
    'price_max' => $_GET['price_max'] ?? 999999999,
    'min_beds' => $_GET['min_beds'] ?? 0,
    'min_baths' => $_GET['min_baths'] ?? 0,
    'page' => $_GET['page'] ?? 1
);

// Get properties based on search parameters
$properties = array();
$total_count = 0;
try {
    $where_conditions = array("p.pr_status = 'active'", "p.is_visible = 1");
    $params = array();
    
    // Property type filter
    if(!empty($search_params['adv_property_types'])) {
        $type_placeholders = implode(',', array_fill(0, count($search_params['adv_property_types']), '?'));
        $where_conditions[] = "p.pr_type_id IN ($type_placeholders)";
        $params = array_merge($params, $search_params['adv_property_types']);
    }
    
    // Price range filter
    if($search_params['price_min'] > 0 || $search_params['price_max'] < 999999999) {
        $where_conditions[] = "EXISTS (SELECT 1 FROM property_pricing pp WHERE pp.property_id = p.property_id AND pp.price_amt BETWEEN ? AND ?)";
        $params[] = $search_params['price_min'];
        $params[] = $search_params['price_max'];
    }
    
    // Beds filter
    if($search_params['min_beds'] > 0) {
        $where_conditions[] = "p.bedrooms >= ?";
        $params[] = $search_params['min_beds'];
    }
    
    // Baths filter
    if($search_params['min_baths'] > 0) {
        $where_conditions[] = "p.bathrooms >= ?";
        $params[] = $search_params['min_baths'];
    }
    
    $where_clause = implode(' AND ', $where_conditions);
    
    // Get total count
    $count_query = new SqlIt("SELECT COUNT(*) as total FROM property_list p WHERE $where_clause", "select", $params);
    if($count_query->NumResults > 0) {
        $total_count = $count_query->Response[0]->total;
    }
    
    // Get properties with pagination
    $limit = 12;
    $offset = ($search_params['page'] - 1) * $limit;
    
    $properties_query = new SqlIt("
        SELECT p.property_id, p.property_title, p.address, p.bedrooms, p.bathrooms, p.is_featured,
               pt.type_desc, c.location as city_name, p.mls_num,
               (SELECT MIN(price_amt) FROM property_pricing WHERE property_id = p.property_id) as min_price,
               (SELECT MAX(price_amt) FROM property_pricing WHERE property_id = p.property_id) as max_price,
               (SELECT img_folder || '/' || img_name FROM property_photos WHERE property_id = p.property_id ORDER BY img_order ASC LIMIT 1) as main_image
        FROM property_list p 
        LEFT JOIN property_types pt ON p.pr_type_id = pt.pr_type_id
        LEFT JOIN locations_cities c ON p.city = c.city_id
        WHERE $where_clause 
        ORDER BY p.is_featured DESC, p.property_id DESC 
        LIMIT $limit OFFSET $offset", "select", $params);
    
    if($properties_query->NumResults > 0) {
        $properties = $properties_query->Response;
    }
} catch (Exception $e) {
    // Handle error silently
}

// Determine current search description
$search_description = "All Properties";
if(!empty($search_params['adv_property_types'])) {
    $type_names = array();
    foreach($search_params['adv_property_types'] as $type_id) {
        if(isset($property_type_counts[$type_id])) {
            $type_names[] = $property_type_counts[$type_id]['name'];
        }
    }
    if(!empty($type_names)) {
        $search_description = implode(', ', $type_names);
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <base href="<?= $base_href ?>">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $search_description ?> - Casa Novara Properties</title>
    <meta name="robots" content="index" />
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="dist/css/cng_base.css" rel="stylesheet">
    <link href="dist/css/cng.css" rel="stylesheet">

    <style>
        .listings-filter {
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            padding: 1rem 0;
        }
        
        .filter-ribbon {
            display: flex;
            gap: 1rem;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .filter-item {
            position: relative;
        }
        
        .btn-filter {
            border-radius: 20px;
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
            border: 1px solid #dee2e6;
            background: white;
            color: #495057;
        }
        
        .btn-filter:hover, .btn-filter.active {
            background: #007bff;
            color: white;
            border-color: #007bff;
        }
        
        .filter-dropdown {
            position: relative;
        }
        
        .dropdown-panel {
            position: absolute;
            top: 100%;
            left: 0;
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            z-index: 1000;
            min-width: 200px;
            display: none;
            padding: 1rem;
        }
        
        .property-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .property-card {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .property-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 16px rgba(0,0,0,0.15);
        }
        
        .property-image {
            height: 200px;
            background: #e9ecef;
            position: relative;
            background-size: cover;
            background-position: center;
        }
        
        .property-featured {
            position: absolute;
            top: 8px;
            right: 8px;
            background: #ffc107;
            color: #000;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        
        .property-details {
            padding: 1.5rem;
        }
        
        .property-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #333;
        }
        
        .property-type {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        
        .property-features {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            color: #6c757d;
        }
        
        .property-price {
            font-size: 1.2rem;
            font-weight: 600;
            color: #007bff;
            margin-bottom: 1rem;
        }
        
        .property-actions {
            border-top: 1px solid #e9ecef;
            padding-top: 1rem;
        }
        
        .results-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 2rem 0 1rem 0;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e9ecef;
        }
        
        .no-results {
            text-align: center;
            padding: 4rem 2rem;
            color: #6c757d;
        }
        
        .type-counts {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }
        
        .type-count-item {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 20px;
            padding: 0.5rem 1rem;
            text-decoration: none;
            color: #495057;
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }
        
        .type-count-item:hover {
            background: #007bff;
            color: white;
            border-color: #007bff;
            text-decoration: none;
        }
        
        .type-count-item.active {
            background: #007bff;
            color: white;
            border-color: #007bff;
        }
    </style>
</head>
<body class="listings-page">

<?php require 'dist/inc/nav-inner.php'; ?>

<!-- Listings filter ribbon -->
<section class="listings-filter">
    <div class="container-fluid">
        <div class="filter-ribbon">
            <!-- Property Type Counts -->
            <div class="type-counts">
                <a href="listings" class="type-count-item <?= empty($search_params['adv_property_types']) ? 'active' : '' ?>">
                    All Properties (<?= array_sum(array_column($property_type_counts, 'count')) ?>)
                </a>
                <?php foreach($property_type_counts as $type_id => $type_data): ?>
                    <?php if($type_data['count'] > 0): ?>
                        <a href="?adv_property_types[]=<?= $type_id ?>" 
                           class="type-count-item <?= in_array($type_id, $search_params['adv_property_types']) ? 'active' : '' ?>">
                            <?= htmlspecialchars($type_data['name']) ?> (<?= $type_data['count'] ?>)
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<!-- Main content -->
<div class="container-fluid">
    <!-- Results header -->
    <div class="results-header">
        <div>
            <h1><?= htmlspecialchars($search_description) ?></h1>
            <p class="text-muted mb-0"><?= number_format($total_count) ?> properties found</p>
        </div>
        <div>
            <?php if(!empty($_GET)): ?>
                <a href="listings" class="btn btn-outline-secondary btn-sm">Clear Filters</a>
            <?php endif; ?>
        </div>
    </div>

    <?php if(empty($properties)): ?>
        <!-- No results -->
        <div class="no-results">
            <i class="bi bi-house-door" style="font-size: 3rem; color: #dee2e6;"></i>
            <h3>No properties found</h3>
            <p>Try adjusting your search criteria or <a href="contact-us">contact us</a> for personalized assistance.</p>
            <a href="listings" class="btn btn-primary">View All Properties</a>
        </div>
    <?php else: ?>
        <!-- Property grid -->
        <div class="property-grid">
            <?php foreach($properties as $property): ?>
                <div class="property-card">
                    <div class="property-image" 
                         <?php if($property->main_image): ?>
                         style="background-image: url('images/<?= htmlspecialchars($property->main_image) ?>');"
                         <?php endif; ?>>
                        <?php if($property->is_featured): ?>
                            <div class="property-featured">Featured</div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="property-details">
                        <h3 class="property-title"><?= htmlspecialchars($property->property_title) ?></h3>
                        
                        <div class="property-type"><?= htmlspecialchars($property->type_desc) ?></div>
                        
                        <?php if($property->address || $property->city_name): ?>
                            <div class="text-muted mb-2">
                                <i class="bi bi-geo-alt"></i>
                                <?= htmlspecialchars($property->address) ?>
                                <?php if($property->city_name): ?>
                                    <?= $property->address ? ', ' : '' ?><?= htmlspecialchars($property->city_name) ?>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="property-features">
                            <?php if($property->bedrooms > 0): ?>
                                <span><i class="bi bi-house"></i> <?= $property->bedrooms ?> bed<?= $property->bedrooms > 1 ? 's' : '' ?></span>
                            <?php endif; ?>
                            <?php if($property->bathrooms > 0): ?>
                                <span><i class="bi bi-droplet"></i> <?= $property->bathrooms ?> bath<?= $property->bathrooms > 1 ? 's' : '' ?></span>
                            <?php endif; ?>
                        </div>
                        
                        <?php if($property->min_price > 0): ?>
                            <div class="property-price">
                                <?php if($property->min_price == $property->max_price): ?>
                                    $<?= number_format($property->min_price) ?>
                                <?php else: ?>
                                    $<?= number_format($property->min_price) ?> - $<?= number_format($property->max_price) ?>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if($property->mls_num): ?>
                            <div class="text-muted small">MLS: <?= htmlspecialchars($property->mls_num) ?></div>
                        <?php endif; ?>
                        
                        <div class="property-actions">
                            <a href="contact-us?property=<?= $property->property_id ?>" class="btn btn-primary btn-sm w-100">
                                Contact About Property
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php 
        $total_pages = ceil($total_count / 12);
        if($total_pages > 1): 
        ?>
            <nav class="mt-4" aria-label="Property listings pagination">
                <ul class="pagination justify-content-center">
                    <?php for($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= $i == $search_params['page'] ? 'active' : '' ?>">
                            <a class="page-link" href="?<?= http_build_query(array_merge($_GET, array('page' => $i))) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php require 'dist/inc/foot.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>