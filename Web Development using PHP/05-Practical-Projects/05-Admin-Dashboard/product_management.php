<?php
// Start a new or resume an existing session for admin authentication
// Sessions are used to persist user data across multiple page requests
// Example: When admin logs in, their ID is stored in $_SESSION['admin_id'] = 1
// This allows us to track the admin's authentication status throughout the application
session_start();

// Database connection configuration array containing essential connection parameters
// These settings define how to connect to the MySQL database server
// Example configuration:
// - host: The database server location (localhost for local development)
// - dbname: The specific database to connect to (admin_system)
// - username: MySQL user account (root is default for local development)
// - password: MySQL user password (empty string for default local setup)
// IMPORTANT: In production, use secure credentials and environment variables
$dbConfig = [
    'host' => 'localhost',      // Database server hostname
    'dbname' => 'admin_system', // Name of the database to use
    'username' => 'root',       // Database user account
    'password' => ''            // Database user password
];

// Function to establish a secure database connection using PDO (PHP Data Objects)
// Parameters:
//   - $config: Array containing database connection parameters (host, dbname, username, password)
// Returns:
//   - On success: PDO connection object for database operations
//   - On failure: false (indicates connection error)
// Example usage: $db = connectDB($dbConfig);
function connectDB($config) {
    try {
        // Create the DSN (Data Source Name) string for PDO connection
        // Format: mysql:host=localhost;dbname=database_name;charset=utf8mb4
        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4";
        
        // Create new PDO instance with connection details
        // This establishes the actual connection to the database
        $pdo = new PDO($dsn, $config['username'], $config['password']);
        
        // Configure PDO to throw exceptions on errors
        // This helps in debugging and error handling
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        return $pdo; // Return successful connection
    } catch (PDOException $e) {
        // If connection fails, return false
        // In production, you might want to log the error message: $e->getMessage()
        return false;
    }
}

// Function to retrieve products with pagination and search functionality
// Parameters:
//   - $db: PDO database connection object
//   - $page: Current page number (default: 1)
//   - $limit: Number of products per page (default: 10)
//   - $search: Search term to filter products (default: empty string)
// Returns:
//   - On success: Array with 'products' array and 'total' count
//   - On failure: Empty products array and zero total
// Example input: getProducts($db, 1, 10, 'laptop')
// Example output: ['products' => [['id' => 1, 'name' => 'Gaming Laptop', ...]], 'total' => 100]
function getProducts($db, $page = 1, $limit = 10, $search = '') {
    try {
        // Calculate the SQL OFFSET based on current page and limit
        // Formula: offset = (page_number - 1) * items_per_page
        // Example: Page 2 with 10 items per page: (2-1) * 10 = 10
        $offset = ($page - 1) * $limit;
        
        // Construct base query with JOIN to get category names
        // LEFT JOIN ensures we get products even if they have no category
        $query = 'SELECT p.*, c.name as category_name 
                 FROM products p 
                 LEFT JOIN categories c ON p.category_id = c.id';
        $params = []; // Initialize parameters array for prepared statement
        
        // Add search conditions if search term is provided
        // Uses LIKE operator with wildcards for partial matches
        if ($search) {
            $query .= ' WHERE p.name LIKE ? OR p.description LIKE ?';
            $searchTerm = "%$search%"; // Add wildcards for partial matching
            $params = [$searchTerm, $searchTerm]; // Same term for name and description
        }
        
        // Get total number of matching products for pagination
        // Replace SELECT columns with COUNT(*) for efficiency
        $countStmt = $db->prepare(str_replace('p.*, c.name as category_name', 'COUNT(*)', $query));
        $countStmt->execute($params);
        $total = $countStmt->fetchColumn(); // Get total count as single value
        
        // Add sorting and pagination to main query
        // Sort by newest products first (created_at DESC)
        // LIMIT ? OFFSET ? for pagination
        $query .= ' ORDER BY p.created_at DESC LIMIT ? OFFSET ?';
        $params[] = $limit;  // Number of items per page
        $params[] = $offset; // Starting position
        
        // Prepare and execute the final query
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC); // Get all products as associative array
        
        // Return both the products array and total count
        return ['products' => $products, 'total' => $total];
    } catch (PDOException $e) {
        // Return empty result set on database error
        // In production, log the error: $e->getMessage()
        return ['products' => [], 'total' => 0];
    }
}

// Function to get all product categories from the database
// Parameters:
//   - $db: PDO database connection object
// Returns:
//   - On success: Array of category records with id and name
//   - On failure: Empty array
// Example output: [['id' => 1, 'name' => 'Electronics'], ...]
// Used for populating category dropdowns and filtering products
function getCategories($db) {
    try {
        // Query to get all categories sorted alphabetically by name
        // Simple query without parameters, safe to use query() directly
        $stmt = $db->query('SELECT id, name FROM categories ORDER BY name');
        
        // Fetch all categories as an associative array
        // Each element will have 'id' and 'name' keys
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Return empty array on database error
        // In production, log the error: $e->getMessage()
        return [];
    }
}

// Function to create a new product in the database
// Parameters:
//   - $db: PDO database connection object
//   - $productData: Array containing product information (name, description, price, etc.)
// Returns:
//   - On success: Array with success=true and success message
//   - On failure: Array with success=false and error message
// Example input: createProduct($db, ['name' => 'Laptop', 'price' => 999.99, ...])
// Example output: ['success' => true, 'message' => 'Product created successfully']
function createProduct($db, $productData) {
    try {
        // Prepare SQL statement for inserting new product
        // Uses named placeholders for better readability and security
        $stmt = $db->prepare(
            'INSERT INTO products (name, description, price, stock, category_id, status) 
             VALUES (?, ?, ?, ?, ?, ?)'
        );
        
        // Execute the prepared statement with product data
        // Status is set to 'active' by default for new products
        $stmt->execute([
            $productData['name'],        // Product name
            $productData['description'], // Product description
            $productData['price'],       // Product price
            $productData['stock'],       // Initial stock quantity
            $productData['category_id'], // Category ID
            'active'                     // Default status
        ]);
        
        // Return success response
        return ['success' => true, 'message' => 'Product created successfully'];
    } catch (PDOException $e) {
        // Return error response on database error
        // In production, log the error: $e->getMessage()
        return ['success' => false, 'message' => 'Failed to create product'];
    }
}

// Function to update an existing product in the database
// Parameters:
//   - $db: PDO database connection object
//   - $productId: ID of the product to update
//   - $productData: Associative array containing fields to update and their new values
// Returns:
//   - On success: Array with success=true and success message
//   - On failure: Array with success=false and error message
// Example input: updateProduct($db, 1, ['name' => 'Updated Laptop', 'price' => 1299.99])
// Example output: ['success' => true, 'message' => 'Product updated successfully']
function updateProduct($db, $productId, $productData) {
    try {
        // Initialize arrays for building dynamic UPDATE query
        $updates = [];  // Will store field=? pairs for SET clause
        $params = [];   // Will store values for prepared statement
        
        // Build update query dynamically based on provided fields
        // This allows updating only the fields that were provided
        foreach ($productData as $field => $value) {
            // Skip 'id' field as it's used in WHERE clause
            if ($field !== 'id') {
                $updates[] = "$field = ?";  // Add field=? to SET clause
                $params[] = $value;         // Add value to parameters array
            }
        }
        
        // Add product ID as last parameter for WHERE clause
        $params[] = $productId;
        
        // Create comma-separated list of field updates
        // Example: "name = ?, price = ?, stock = ?"
        $updateStr = implode(', ', $updates);
        
        // Prepare and execute the UPDATE statement
        // Example: "UPDATE products SET name = ?, price = ? WHERE id = ?"
        $stmt = $db->prepare("UPDATE products SET $updateStr WHERE id = ?");
        $stmt->execute($params);
        
        // Return success response if update was successful
        return ['success' => true, 'message' => 'Product updated successfully'];
    } catch (PDOException $e) {
        // Return error response on database error
        // In production, log the error: $e->getMessage()
        return ['success' => false, 'message' => 'Failed to update product'];
    }
}

// Connect to database
$db = connectDB($dbConfig);

// Handle AJAX requests
// Example input: POST request with action and product data
// Example output: JSON response with status and message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    header('Content-Type: application/json');
    $response = ['success' => false, 'message' => 'Invalid action'];
    
    $data = json_decode(file_get_contents('php://input'), true);
    $action = $data['action'] ?? '';
    
    switch ($action) {
        case 'create':
            $response = createProduct($db, $data['product']);
            break;
            
        case 'update':
            $response = updateProduct($db, $data['product']['id'], $data['product']);
            break;
    }
    
    echo json_encode($response);
    exit;
}

// Get search term
$search = $_GET['search'] ?? '';

// Get products for current page
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$productData = $db ? getProducts($db, $page, 10, $search) : ['products' => [], 'total' => 0];
$totalPages = ceil($productData['total'] / 10);

// Get categories for form
$categories = $db ? getCategories($db) : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management - Admin Dashboard</title>
    <style>
        /* Inherit dashboard styles */
        :root {
            --primary-color: #4CAF50;
            --secondary-color: #2196F3;
            --danger-color: #f44336;
            --success-color: #4CAF50;
            --warning-color: #ff9800;
            --text-color: #333;
            --bg-color: #f5f5f5;
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: var(--bg-color);
            color: var(--text-color);
        }
        
        .container {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar styling */
        .sidebar {
            width: 250px;
            background-color: #333;
            color: white;
            padding: 20px;
        }
        
        .sidebar h2 {
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #555;
        }
        
        .nav-item {
            padding: 10px;
            margin: 5px 0;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        
        .nav-item:hover {
            background-color: #555;
        }
        
        /* Main content styling */
        .main-content {
            flex: 1;
            padding: 20px;
        }
        
        /* Search bar styling */
        .search-bar {
            margin-bottom: 20px;
        }
        
        .search-bar input {
            width: 300px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
        }
        
        /* Product form styling */
        .product-form {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .form-group textarea {
            height: 100px;
            resize: vertical;
        }
        
        /* Product table styling */
        .product-table {
            width: 100%;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .product-table th,
        .product-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        .product-table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }
        
        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            color: white;
            background-color: var(--primary-color);
        }
        
        .btn:hover {
            opacity: 0.9;
        }
        
        /* Pagination styling */
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            gap: 10px;
        }
        
        .pagination a {
            padding: 8px 12px;
            background: white;
            border-radius: 4px;
            text-decoration: none;
            color: var(--text-color);
        }
        
        .pagination a.active {
            background: var(--primary-color);
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar navigation -->
        <div class="sidebar">
            <h2>Admin Panel</h2>
            <div class="nav-item" onclick="location.href='admin_dashboard.php'">Dashboard</div>
            <div class="nav-item" onclick="location.href='user_management.php'">Users</div>
            <div class="nav-item" onclick="location.href='product_management.php'">Products</div>
            <div class="nav-item" onclick="location.href='order_management.php'">Orders</div>
            <div class="nav-item" onclick="location.href='analytics.php'">Analytics</div>
        </div>
        
        <!-- Main content area -->
        <div class="main-content">
            <h1>Product Management</h1>
            
            <!-- Search bar -->
            <div class="search-bar">
                <form action="" method="GET">
                    <input type="text" name="search" placeholder="Search products..." 
                           value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit" class="btn">Search</button>
                </form>
            </div>
            
            <!-- Product creation form -->
            <div class="product-form">
                <h2>Create New Product</h2>
                <form id="createProductForm" onsubmit="return handleSubmit(event)">
                    <div class="form-group">
                        <label for="name">Product Name</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="number" id="price" name="price" step="0.01" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="stock">Stock</label>
                        <input type="number" id="stock" name="stock" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="category_id">Category</label>
                        <select id="category_id" name="category_id" required>
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>">
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn">Create Product</button>
                </form>
            </div>
            
            <!-- Products table -->
            <table class="product-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productData['products'] as $product): ?>
                        <tr>
                            <td><?php echo $product['id']; ?></td>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td><?php echo htmlspecialchars($product['category_name']); ?></td>
                            <td>$<?php echo number_format($product['price'], 2); ?></td>
                            <td><?php echo $product['stock']; ?></td>
                            <td><?php echo htmlspecialchars($product['status']); ?></td>
                            <td>
                                <button class="btn" onclick="editProduct(<?php echo $product['id']; ?>)">
                                    Edit
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($productData['products'])): ?>
                        <tr>
                            <td colspan="7">No products found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            
            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div class="pagination">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>" 
                           class="<?php echo $page === $i ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Function to handle form submission
        // Example: Submit form data via AJAX
        async function handleSubmit(event) {
            event.preventDefault();
            
            const form = event.target;
            const formData = new FormData(form);
            const productData = {};
            
            formData.forEach((value, key) => {
                productData[key] = value;
            });
            
            try {
                const response = await fetch('product_management.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        action: 'create',
                        product: productData
                    })
                });
                
                const data = await response.json();
                if (data.success) {
                    alert(data.message);
                    form.reset();
                    location.reload();
                } else {
                    alert(data.message);
                }
            } catch (error) {
                alert('An error occurred. Please try again.');
            }
            
            return false;
        }
        
        // Function to edit product
        // Example: Open edit form for product with ID 1
        function editProduct(productId) {
            // Implement edit functionality
            alert('Edit product ' + productId);
        }
    </script>
</body>
</html>