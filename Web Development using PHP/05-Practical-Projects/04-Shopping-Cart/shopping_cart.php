<?php
// Start a new or resume existing session for shopping cart management
// Sessions allow us to persist cart data across multiple page requests
// The cart data structure in session:
// $_SESSION['cart'] = [
//     ['id' => 1, 'quantity' => 2],  // Product ID 1, Quantity 2
//     ['id' => 3, 'quantity' => 1]   // Product ID 3, Quantity 1
// ]
session_start();

// Initialize empty shopping cart if it doesn't exist in session
// This ensures we always have a valid cart array to work with
// Example: First-time visitor gets an empty cart array
// Example: Returning visitor keeps their existing cart items
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Initialize empty cart array
}

// Database connection configuration for product system
// These settings define how to connect to the MySQL database
// IMPORTANT: In production environment:
// - Use environment variables for credentials
// - Enable SSL/TLS for secure connections
// - Use a dedicated database user with limited permissions
$dbConfig = [
    'host' => 'localhost',     // Database server (usually localhost for development)
    'dbname' => 'product_system', // Name of the database containing product data
    'username' => 'root',      // Database user (default for local development)
    'password' => ''           // Database password (empty for local development)
];

// Function to establish database connection
// Example output: PDO connection object or false on failure
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

// Function to retrieve cart items with their associated product details from the database
// Parameters:
//   - $db: PDO database connection object
// Returns:
//   - On success: Array of cart items with product details and calculated totals
//   - On failure or empty cart: Empty array
// Example output: [['id' => 1, 'name' => 'Product', 'price' => 10.00, 'quantity' => 2], ...]
function getCartItems($db) {
    // Return empty array if cart is empty
    // This prevents unnecessary database queries
    if (empty($_SESSION['cart'])) {
        return [];
    }
    
    try {
        // Extract product IDs from cart session data
        // array_column gets all 'id' values from cart items
        $productIds = array_column($_SESSION['cart'], 'id');
        
        // Create SQL placeholders for IN clause
        // Example: For 3 products, generates "?,?,?"
        $placeholders = str_repeat('?,', count($productIds) - 1) . '?';
        
        // Prepare and execute query to get product details
        // Uses IN clause to fetch multiple products at once
        $stmt = $db->prepare("SELECT id, name, price FROM products WHERE id IN ($placeholders)");
        $stmt->execute($productIds);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Combine product details with cart quantities
        // This creates complete cart items with prices and totals
        $cartItems = [];
        foreach ($products as $product) {
            // Find matching cart item for current product
            // Uses array_filter to find item with matching ID
            $cartItem = array_filter($_SESSION['cart'], function($item) use ($product) {
                return $item['id'] === $product['id'];
            });
            $cartItem = reset($cartItem); // Get first (and only) matching item
            
            // Create cart item with all necessary information
            // Includes product details, quantity, and calculated total
            $cartItems[] = [
                'id' => $product['id'],          // Product ID
                'name' => $product['name'],       // Product name
                'price' => $product['price'],     // Unit price
                'quantity' => $cartItem['quantity'], // Quantity from cart
                'total' => $product['price'] * $cartItem['quantity'] // Line total
            ];
        }
        
        return $cartItems; // Return complete cart items array
    } catch (PDOException $e) {
        // Return empty array on database error
        // In production, log the error: $e->getMessage()
        return [];
    }
}

// Handle AJAX requests for cart operations
// This section processes asynchronous requests from the frontend
// Example input: POST request with action and product data
// Example output: JSON response with status and updated cart

// Check if this is an AJAX POST request
// $_SERVER['REQUEST_METHOD'] checks the HTTP method
// $_SERVER['HTTP_X_REQUESTED_WITH'] verifies it's an AJAX call
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    // Initialize response array with empty values
    // This structure will be converted to JSON and sent back to client
    $response = [
        'status' => '',     // Success or error status
        'message' => '',    // Human-readable message
        'cart' => []        // Updated cart items
    ];
    
    // Get and parse the JSON request data
    // file_get_contents('php://input') reads raw POST data
    // json_decode converts JSON string to PHP array
    $data = json_decode(file_get_contents('php://input'), true);
    
    // Extract and sanitize request parameters
    // ?? operator provides default values if keys don't exist
    $action = $data['action'] ?? '';              // Cart action (add/update/remove)
    $productId = intval($data['product_id'] ?? 0); // Product ID (converted to integer)
    $quantity = intval($data['quantity'] ?? 0);    // Product quantity (converted to integer)
    
    // Process different cart actions
    switch ($action) {
        case 'add':
            // Add new item or increment quantity of existing item
            // Example: Add product with ID 1, quantity 1
            
            // Check if product already exists in cart
            $existingItem = array_filter($_SESSION['cart'], function($item) use ($productId) {
                return $item['id'] === $productId;
            });
            
            if (empty($existingItem)) {
                // Product not in cart - add new item
                $_SESSION['cart'][] = ['id' => $productId, 'quantity' => 1];
            } else {
                // Product exists - increment quantity
                $key = key($existingItem);
                $_SESSION['cart'][$key]['quantity']++;
            }
            
            // Set success response
            $response['status'] = 'success';
            $response['message'] = 'Item added to cart';
            break;
            
        case 'update':
            // Update quantity of existing cart item
            // Example: Update product with ID 1 to quantity 3
            
            // Find product index in cart array
            $key = array_search($productId, array_column($_SESSION['cart'], 'id'));
            
            if ($key !== false) {
                if ($quantity > 0) {
                    // Update quantity if greater than zero
                    $_SESSION['cart'][$key]['quantity'] = $quantity;
                    $response['status'] = 'success';
                    $response['message'] = 'Quantity updated';
                } else {
                    // Remove item if quantity is zero or negative
                    unset($_SESSION['cart'][$key]);
                    $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex array
                    $response['status'] = 'success';
                    $response['message'] = 'Item removed from cart';
                }
            } else {
                // Product not found in cart
                $response['status'] = 'error';
                $response['message'] = 'Item not found in cart';
            }
            break;
            
        case 'remove':
            // Remove item from cart completely
            // Example: Remove product with ID 1
            
            // Find product index in cart array
            $key = array_search($productId, array_column($_SESSION['cart'], 'id'));
            
            if ($key !== false) {
                // Remove item and reindex array
                unset($_SESSION['cart'][$key]);
                $_SESSION['cart'] = array_values($_SESSION['cart']);
                $response['status'] = 'success';
                $response['message'] = 'Item removed from cart';
            } else {
                // Product not found in cart
                $response['status'] = 'error';
                $response['message'] = 'Item not found in cart';
            }
            break;
            
        default:
            // Invalid or missing action parameter
            $response['status'] = 'error';
            $response['message'] = 'Invalid action';
    }
    
    // Get updated cart items
    $db = connectDB($dbConfig);
    if ($db) {
        $response['cart'] = getCartItems($db);
    }
    
    echo json_encode($response);
    exit;
}

// Get cart items for display
$db = connectDB($dbConfig);
$cartItems = $db ? getCartItems($db) : [];
$total = array_sum(array_column($cartItems, 'total'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Basic meta tags for character encoding and responsive design -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <style>
        /* Container styles */
        /* Centers content and provides reasonable max-width for readability */
        .container {
            max-width: 800px;      /* Limit width for better readability */
            margin: 30px auto;     /* Center container with top/bottom margin */
            padding: 20px;         /* Inner spacing for content */
        }
        
        /* Table styles for cart items display */
        /* Creates a clean, bordered table layout for cart items */
        .cart-table {
            width: 100%;           /* Full width of container */
            border-collapse: collapse;  /* Remove space between cells */
            margin-bottom: 20px;   /* Space below table */
        }
        
        /* Table cell styles */
        /* Consistent padding and borders for table cells */
        .cart-table th,
        .cart-table td {
            padding: 10px;         /* Space inside cells */
            border: 1px solid #ddd; /* Light gray borders */
            text-align: left;      /* Left-align content */
        }
        
        /* Quantity input field styles */
        /* Compact input field for quantity adjustments */
        .quantity-input {
            width: 60px;           /* Fixed width for number input */
            padding: 5px;          /* Space inside input */
        }
        
        /* Button styles */
        /* Modern, flat button design with hover effects */
        .btn {
            background: #4CAF50;    /* Green background */
            color: white;           /* White text */
            padding: 5px 10px;      /* Comfortable padding */
            border: none;           /* Remove default border */
            border-radius: 4px;     /* Rounded corners */
            cursor: pointer;        /* Hand cursor on hover */
            margin: 2px;           /* Space between buttons */
        }
        
        /* Button hover effect */
        .btn:hover {
            background: #45a049;    /* Darker green on hover */
        }
        
        /* Remove button variant */
        /* Red button for destructive actions */
        .btn.remove {
            background: #f44336;    /* Red background */
        }
        
        /* Remove button hover effect */
        .btn.remove:hover {
            background: #da190b;    /* Darker red on hover */
        }
        
        /* Total amount display */
        /* Right-aligned, prominent text for cart total */
        .total {
            text-align: right;      /* Align to right side */
            font-size: 1.2em;       /* Larger text size */
            margin-top: 20px;       /* Space above total */
        }
    </style>
</head>
<body>
    <!-- Main container for shopping cart content -->
    <!-- Uses max-width and auto margins for centered, responsive layout -->
    <div class="container">
        <!-- Shopping cart header -->
        <h2>Shopping Cart</h2>
        
        <!-- Conditional display based on cart status -->
        <!-- Shows empty message if no items, otherwise displays cart table -->
        <?php if (empty($cartItems)): ?>
            <!-- Empty cart message shown when $cartItems array is empty -->
            <p>Your cart is empty.</p>
        <?php else: ?>
            <!-- Cart items table with product details and actions -->
            <!-- Structured layout for cart items with consistent styling -->
            <table class="cart-table">
                <!-- Table header row defining column structure -->
                <thead>
                    <tr>
                        <th>Product</th>      <!-- Product name column -->
                        <th>Price</th>        <!-- Unit price column -->
                        <th>Quantity</th>     <!-- Quantity input column -->
                        <th>Total</th>        <!-- Line total column -->
                        <th>Actions</th>      <!-- Item actions column -->
                    </tr>
                </thead>
                <!-- Table body containing cart items -->
                <!-- Iterates through $cartItems array from getCartItems() function -->
                <tbody>
                    <?php foreach ($cartItems as $item): ?>
                        <!-- Cart item row with data attribute for JavaScript interaction -->
                        <!-- data-product-id used by JS functions to identify items -->
                        <tr data-product-id="<?php echo $item['id']; ?>">
                            <!-- Product name cell with XSS protection -->
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <!-- Price cell formatted with 2 decimal places -->
                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                            <!-- Quantity input cell with update functionality -->
                            <td>
                                <!-- Number input for quantity with min value and change event -->
                                <!-- Triggers updateQuantity() function on value change -->
                                <input type="number" class="quantity-input" 
                                       value="<?php echo $item['quantity']; ?>" 
                                       min="1" onchange="updateQuantity(<?php echo $item['id']; ?>, this.value)">
                            </td>
                            <!-- Total price cell (quantity × unit price) -->
                            <td>$<?php echo number_format($item['total'], 2); ?></td>
                            <!-- Actions cell with remove button -->
                            <td>
                                <!-- Remove button triggers removeItem() function -->
                                <button class="btn remove" onclick="removeItem(<?php echo $item['id']; ?>)">Remove</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <!-- Cart total section showing sum of all item totals -->
            <!-- Right-aligned with larger font for emphasis -->
            <div class="total">
                Total: $<?php echo number_format($total, 2); ?>
            </div>
            
            <!-- Checkout button to proceed with purchase -->
            <!-- Triggers checkout() function when clicked -->
            <button class="btn" onclick="checkout()">Proceed to Checkout</button>
        <?php endif; ?>
    </div>

    <script>
        // Function to update item quantity in the cart
        // Parameters:
        //   - productId: ID of the product to update
        //   - quantity: New quantity value
        // Example: updateQuantity(1, 3) updates product ID 1 to quantity 3
        async function updateQuantity(productId, quantity) {
            try {
                // Send AJAX request to server
                // Uses fetch API with POST method and JSON data
                const response = await fetch('shopping_cart.php', {
                    method: 'POST',                     // HTTP POST request
                    headers: {
                        'Content-Type': 'application/json',  // Send JSON data
                        'X-Requested-With': 'XMLHttpRequest' // Mark as AJAX request
                    },
                    body: JSON.stringify({              // Convert data to JSON string
                        action: 'update',               // Action to perform
                        product_id: productId,          // Product to update
                        quantity: parseInt(quantity)     // New quantity (as integer)
                    })
                });
                
                // Parse JSON response from server
                const data = await response.json();
                
                // Handle response based on status
                if (data.status === 'success') {
                    updateCartDisplay(data.cart);      // Update UI with new cart data
                } else {
                    alert(data.message);              // Show error message
                }
            } catch (error) {
                // Handle network or other errors
                alert('An error occurred. Please try again.');
            }
        }
        
        // Function to remove an item from the cart
        // Parameters:
        //   - productId: ID of the product to remove
        // Example: removeItem(1) removes product ID 1 from cart
        async function removeItem(productId) {
            try {
                // Send AJAX request to remove item
                const response = await fetch('shopping_cart.php', {
                    method: 'POST',                     // HTTP POST request
                    headers: {
                        'Content-Type': 'application/json',  // Send JSON data
                        'X-Requested-With': 'XMLHttpRequest' // Mark as AJAX request
                    },
                    body: JSON.stringify({              // Convert data to JSON string
                        action: 'remove',               // Action to perform
                        product_id: productId           // Product to remove
                    })
                });
                
                // Parse and handle server response
                const data = await response.json();
                if (data.status === 'success') {
                    updateCartDisplay(data.cart);      // Update UI with new cart data
                } else {
                    alert(data.message);              // Show error message
                }
            } catch (error) {
                // Handle network or other errors
                alert('An error occurred. Please try again.');
            }
        }
        
        // Function to update the cart display in the UI
        // Parameters:
        //   - cartItems: Array of updated cart items from server
        // Note: This is a simple implementation that reloads the page
        // In production, you would update the DOM elements directly
        function updateCartDisplay(cartItems) {
            location.reload(); // Reload page to show updated cart
            // TODO: Implement direct DOM updates for better UX
            // This would involve updating quantities, totals, and removing items
            // without a full page reload
        }
        
        // Function to handle the checkout process
        // Redirects user to the checkout page
        // This is a simple implementation - in production:
        // - Validate cart is not empty
        // - Check user is logged in
        // - Save cart state
        function checkout() {
            window.location.href = 'checkout.php'; // Redirect to checkout
        }
    </script>
</body>
</html>