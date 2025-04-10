<?php
// Start session for user preferences and filters
// Example: $_SESSION['category_filter'] = 'electronics'
session_start();

// Database connection configuration
// Example: Host = localhost, Database = product_system
$dbConfig = [
    'host' => 'localhost',
    'dbname' => 'product_system',
    'username' => 'root',
    'password' => ''
];

// Function to establish database connection
// Example output: PDO connection object or false on failure
function connectDB($config) {
    try {
        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4";
        $pdo = new PDO($dsn, $config['username'], $config['password']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        return false;
    }
}

// Function to get product categories
// Example output: [['id' => 1, 'name' => 'Electronics'], ...]
function getCategories($db) {
    try {
        $stmt = $db->query('SELECT id, name FROM categories ORDER BY name');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

// Function to get products with pagination and filtering
// Example input: getProducts($db, 1, 10, 'electronics', 'laptop')
// Example output: ['products' => [...], 'total' => 100]
function getProducts($db, $page = 1, $perPage = 12, $category = null, $search = null) {
    try {
        $offset = ($page - 1) * $perPage;
        $params = [];
        
        // Build query with filters
        $query = 'SELECT SQL_CALC_FOUND_ROWS p.*, c.name as category_name 
                 FROM products p 
                 LEFT JOIN categories c ON p.category_id = c.id 
                 WHERE 1';
        
        if ($category) {
            $query .= ' AND c.name = ?';
            $params[] = $category;
        }
        
        if ($search) {
            $query .= ' AND (p.name LIKE ? OR p.description LIKE ?)';
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        $query .= ' ORDER BY p.name LIMIT ? OFFSET ?';
        $params[] = $perPage;
        $params[] = $offset;
        
        // Execute query
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get total count
        $total = $db->query('SELECT FOUND_ROWS()')->fetchColumn();
        
        return [
            'products' => $products,
            'total' => $total
        ];
    } catch (PDOException $e) {
        return ['products' => [], 'total' => 0];
    }
}

// Connect to database
$db = connectDB($dbConfig);

// Get current page and filters
// Example: page=1, category='electronics', search='laptop'
$page = max(1, intval($_GET['page'] ?? 1));
$category = $_GET['category'] ?? null;
$search = $_GET['search'] ?? null;

// Get categories and products
$categories = $categories = getCategories($db);
$result = getProducts($db, $page, 12, $category, $search);
$products = $result['products'];
$total = $result['total'];
$totalPages = ceil($total / 12);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Catalog</title>
    <style>
        /* Basic styling for product catalog */
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
        }
        .filters {
            margin-bottom: 20px;
            padding: 15px;
            background: #f5f5f5;
            border-radius: 5px;
        }
        .search-box {
            margin-bottom: 15px;
        }
        .search-box input {
            padding: 8px;
            width: 200px;
        }
        .category-filter select {
            padding: 8px;
            width: 200px;
        }
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }
        .product-card {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
        }
        .product-card img {
            width: 100%;
            height: auto;
            margin-bottom: 10px;
        }
        .pagination {
            margin-top: 20px;
            text-align: center;
        }
        .pagination a {
            padding: 5px 10px;
            margin: 0 5px;
            border: 1px solid #ddd;
            text-decoration: none;
            color: #333;
        }
        .pagination a.active {
            background: #4CAF50;
            color: white;
            border-color: #4CAF50;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Product Catalog</h2>
        
        <!-- Filters -->
        <div class="filters">
            <form method="GET" class="search-box">
                <input type="text" name="search" placeholder="Search products..." 
                       value="<?php echo htmlspecialchars($search ?? ''); ?>">
                
                <select name="category" class="category-filter">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?php echo htmlspecialchars($cat['name']); ?>" 
                                <?php echo $category === $cat['name'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                
                <button type="submit">Apply Filters</button>
            </form>
        </div>
        
        <!-- Products Grid -->
        <div class="products-grid">
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                         alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p><?php echo htmlspecialchars($product['description']); ?></p>
                    <p>Category: <?php echo htmlspecialchars($product['category_name']); ?></p>
                    <p>Price: $<?php echo number_format($product['price'], 2); ?></p>
                    <button onclick="addToCart(<?php echo $product['id']; ?>)">Add to Cart</button>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>&category=<?php echo urlencode($category ?? ''); ?>&search=<?php echo urlencode($search ?? ''); ?>" 
                       class="<?php echo $page === $i ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Function to add product to cart
        // Example: Add product with ID 1 to cart
        function addToCart(productId) {
            fetch('add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert('Product added to cart!');
                } else {
                    alert('Failed to add product to cart.');
                }
            })
            .catch(error => {
                alert('An error occurred. Please try again.');
            });
        }
    </script>
</body>
</html>