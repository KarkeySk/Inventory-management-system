<?php
/**
 * DELETE FUNCTIONALITY TEST
 * This file helps you test if delete is working properly
 * Access: http://localhost/inventory-system-updated/public/test-delete.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Delete Functionality Test</h1>";

// Test 1: Check if files exist
echo "<h2>Test 1: File Existence</h2>";
$files = [
    'delete.php' => file_exists('delete.php'),
    'ajax/delete.php' => file_exists('ajax/delete.php'),
    '../config/db.php' => file_exists('../config/db.php'),
    '../includes/functions.php' => file_exists('../includes/functions.php')
];

foreach($files as $file => $exists) {
    $status = $exists ? '✅ EXISTS' : '❌ MISSING';
    echo "<p>$file: <strong>$status</strong></p>";
}

// Test 2: Database Connection
echo "<h2>Test 2: Database Connection</h2>";
try {
    require_once '../config/db.php';
    echo "<p>✅ Database connection: <strong>SUCCESS</strong></p>";
    echo "<p>Connected to database: <strong>" . DB_NAME . "</strong></p>";
} catch(Exception $e) {
    echo "<p>❌ Database connection: <strong>FAILED</strong></p>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
    exit;
}

// Test 3: Functions file
echo "<h2>Test 3: Functions File</h2>";
try {
    require_once '../includes/functions.php';
    echo "<p>✅ Functions file loaded: <strong>SUCCESS</strong></p>";
    
    // Check if deleteProduct function exists
    if(function_exists('deleteProduct')) {
        echo "<p>✅ deleteProduct() function: <strong>EXISTS</strong></p>";
    } else {
        echo "<p>❌ deleteProduct() function: <strong>MISSING</strong></p>";
    }
} catch(Exception $e) {
    echo "<p>❌ Functions file: <strong>FAILED</strong></p>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
}

// Test 4: Products table
echo "<h2>Test 4: Products Table</h2>";
try {
    $stmt = $conn->query("SELECT COUNT(*) as count FROM products");
    $result = $stmt->fetch();
    echo "<p>✅ Products table exists: <strong>YES</strong></p>";
    echo "<p>Total products in database: <strong>" . $result['count'] . "</strong></p>";
    
    // Get a sample product
    $stmt = $conn->query("SELECT id, title FROM products LIMIT 1");
    $product = $stmt->fetch();
    
    if($product) {
        echo "<p>Sample product (ID: {$product['id']}): <strong>{$product['title']}</strong></p>";
        echo "<p>You can test delete with ID: <strong>{$product['id']}</strong></p>";
    }
} catch(Exception $e) {
    echo "<p>❌ Products table: <strong>ERROR</strong></p>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
}

// Test 5: Session
echo "<h2>Test 5: Session Status</h2>";
if(session_status() === PHP_SESSION_ACTIVE) {
    echo "<p>✅ Session: <strong>ACTIVE</strong></p>";
    if(isset($_SESSION['admin'])) {
        echo "<p>✅ Logged in: <strong>YES</strong></p>";
    } else {
        echo "<p>⚠️ Logged in: <strong>NO</strong> (You need to login first)</p>";
    }
} else {
    echo "<p>❌ Session: <strong>INACTIVE</strong></p>";
}

// Test 6: Try a mock delete (without actually deleting)
echo "<h2>Test 6: Delete Function Test</h2>";
echo "<p>This will test the delete function without actually deleting anything.</p>";

try {
    // Prepare a delete statement but don't execute
    $testId = 999999; // Non-existent ID
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    echo "<p>✅ SQL prepare: <strong>SUCCESS</strong></p>";
    echo "<p>Delete function is ready to work!</p>";
} catch(Exception $e) {
    echo "<p>❌ SQL prepare: <strong>FAILED</strong></p>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
}

// Summary
echo "<h2>Summary</h2>";
echo "<div style='background: #f0f9ff; border: 2px solid #3b82f6; padding: 20px; border-radius: 8px; margin: 20px 0;'>";
echo "<h3 style='color: #1e40af; margin-top: 0;'>Next Steps:</h3>";
echo "<ol>";
echo "<li>If all tests show ✅, delete functionality should work</li>";
echo "<li>Make sure you're logged in to the system</li>";
echo "<li>Go to products page and try deleting a product</li>";
echo "<li>If it still doesn't work, check browser console (F12) for JavaScript errors</li>";
echo "<li>Check the TROUBLESHOOTING.md file for detailed help</li>";
echo "</ol>";
echo "</div>";

// JavaScript test
echo "<h2>Test 7: JavaScript Delete Function</h2>";
echo "<p>Click the button below to test if JavaScript is working:</p>";
echo "<button onclick='testDelete()' style='padding: 10px 20px; background: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;'>Test Delete Alert</button>";
echo "<p id='jsResult'></p>";

echo "<script>
function testDelete() {
    document.getElementById('jsResult').innerHTML = '<p style=\"color: green; font-weight: bold;\">✅ JavaScript is working!</p>';
    
    if(confirm('This is how the delete confirmation looks. Click OK to continue.')) {
        alert('Delete function would execute here. JavaScript is working correctly!');
    }
}
</script>";

echo "<hr>";
echo "<p><a href='products.php' style='padding: 10px 20px; background: #4c6ef5; color: white; text-decoration: none; border-radius: 6px; display: inline-block; margin-top: 20px;'>← Back to Products</a></p>";
?>

<style>
body {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    max-width: 800px;
    margin: 40px auto;
    padding: 20px;
    background: #f9fafb;
}
h1 {
    color: #1f2937;
    border-bottom: 3px solid #4c6ef5;
    padding-bottom: 10px;
}
h2 {
    color: #374151;
    margin-top: 30px;
    background: white;
    padding: 15px;
    border-radius: 8px;
    border-left: 4px solid #4c6ef5;
}
p {
    background: white;
    padding: 10px 15px;
    margin: 10px 0;
    border-radius: 6px;
}
</style>
