<?php
// Quick database test for contact info enhancements
require_once('../classes/sql.class.php');

// Test if the new columns exist
$test = new SqlIt("SHOW COLUMNS FROM site_contact","select",array());

echo "<h3>Current site_contact table structure:</h3>";
echo "<table border='1'>";
echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";

if($test->NumResults > 0){
    foreach($test->Response as $column){
        echo "<tr>";
        echo "<td>" . $column->Field . "</td>";
        echo "<td>" . $column->Type . "</td>";
        echo "<td>" . $column->Null . "</td>";
        echo "<td>" . $column->Key . "</td>";
        echo "<td>" . $column->Default . "</td>";
        echo "</tr>";
    }
}
echo "</table>";

// Check if we need to run the enhancements
$hasNewColumns = false;
if($test->NumResults > 0){
    foreach($test->Response as $column){
        if($column->Field == 'contact_title' || $column->Field == 'contact_description' || $column->Field == 'is_whatsapp' || $column->Field == 'contact_meta') {
            $hasNewColumns = true;
            break;
        }
    }
}

if(!$hasNewColumns) {
    echo "<h3 style='color: red;'>⚠️ Database needs to be updated!</h3>";
    echo "<p>Please run the SQL commands from <code>DB/contact_info_enhancements.sql</code></p>";
} else {
    echo "<h3 style='color: green;'>✅ Database is ready!</h3>";
    echo "<p>All required columns are present.</p>";
}
?>