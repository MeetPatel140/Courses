<?php
// Start session for admin authentication and preferences
// Example: $_SESSION['admin_id'] = 1, $_SESSION['admin_name'] = 'Admin User'
session_start();

// Database connection configuration
// Example: Host = localhost, Database = admin_system
$dbConfig = [
    'host' => 'localhost',
    'dbname' => 'admin_system',
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

// Function to get dashboard statistics
// Example output: ['total_users' => 100, 'total_products' => 50, 'total_orders' => 25]
function getDashboardStats($db) {
    try {
        // Get total users
        $stmt = $db->query('SELECT COUNT(*) FROM users');
        $totalUsers = $stmt->fetchColumn();
        
        // Get total products
        $stmt = $db->query('SELECT COUNT(*) FROM products');
        $totalProducts = $stmt->fetchColumn();
        
        // Get total orders
        $stmt = $db->query('SELECT COUNT(*) FROM orders');
        $totalOrders = $stmt->fetchColumn();
        
        // Get revenue
        $stmt = $db->query('SELECT SUM(total_amount) FROM orders WHERE status = "completed"');
        $totalRevenue = $stmt->fetchColumn() ?: 0;
        
        return [
            'total_users' => $totalUsers,
            'total_products' => $totalProducts,
            'total_orders' => $totalOrders,
            'total_revenue' => $totalRevenue
        ];
    } catch (PDOException $e) {
        return [
            'total_users' => 0,
            'total_products' => 0,
            'total_orders' => 0,
            'total_revenue' => 0
        ];
    }
}

// Function to get recent orders
// Example output: [['id' => 1, 'user' => 'John', 'total' => 100.00, 'date' => '2024-01-01'], ...]
function getRecentOrders($db, $limit = 5) {
    try {
        $stmt = $db->prepare(
            'SELECT o.id, u.name as user, o.total_amount, o.created_at 
             FROM orders o 
             JOIN users u ON o.user_id = u.id 
             ORDER BY o.created_at DESC 
             LIMIT ?'
        );
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

// Connect to database
$db = connectDB($dbConfig);

// Get dashboard data
$stats = $db ? getDashboardStats($db) : [];
$recentOrders = $db ? getRecentOrders($db) : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        /* Dashboard styling */
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
        
        /* Stats cards styling */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .stat-card h3 {
            color: var(--text-color);
            margin-bottom: 10px;
        }
        
        .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: var(--primary-color);
        }
        
        /* Recent orders table styling */
        .orders-table {
            width: 100%;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .orders-table th,
        .orders-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        .orders-table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }
        
        .orders-table tr:last-child td {
            border-bottom: none;
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
            <h1>Dashboard Overview</h1>
            <div class="stats-grid">
                <!-- Total Users Stats -->
                <div class="stat-card">
                    <h3>Total Users</h3>
                    <div class="stat-value"><?php echo number_format($stats['total_users']); ?></div>
                </div>
                
                <!-- Total Products Stats -->
                <div class="stat-card">
                    <h3>Total Products</h3>
                    <div class="stat-value"><?php echo number_format($stats['total_products']); ?></div>
                </div>
                
                <!-- Total Orders Stats -->
                <div class="stat-card">
                    <h3>Total Orders</h3>
                    <div class="stat-value"><?php echo number_format($stats['total_orders']); ?></div>
                </div>
                
                <!-- Total Revenue Stats -->
                <div class="stat-card">
                    <h3>Total Revenue</h3>
                    <div class="stat-value">$<?php echo number_format($stats['total_revenue'], 2); ?></div>
                </div>
            </div>
            
            <!-- Recent Orders Section -->
            <h2>Recent Orders</h2>
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentOrders as $order): ?>
                        <tr>
                            <td>#<?php echo $order['id']; ?></td>
                            <td><?php echo htmlspecialchars($order['user']); ?></td>
                            <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                            <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($recentOrders)): ?>
                        <tr>
                            <td colspan="4">No recent orders found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>