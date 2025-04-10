<?php

// Include database configuration
require_once '../01-MySQL-Setup/config.php';

class ProductOperations {
    private $pdo;
    
    public function __construct() {
        // Initialize database connection
        // Example output: "Database connection established"
        $this->pdo = getDbConnection();
    }
    
    // Create new product
    // Example input: createProduct('Gaming Mouse', 'High-DPI gaming mouse', 79.99, 50)
    // Example output: "Product created successfully with ID: 1"
    public function createProduct($name, $description, $price, $stock) {
        try {
            // Validate price and stock
            if ($price <= 0 || $stock < 0) {
                throw new Exception("Invalid price or stock value");
            }
            
            // Prepare SQL statement
            $sql = "INSERT INTO products (name, description, price, stock) VALUES (?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$name, $description, $price, $stock]);
            
            return "Product created successfully with ID: " . $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Create Product Error: " . $e->getMessage());
            throw new Exception("Failed to create product");
        }
    }
    
    // Get product information
    // Example input: getProduct(1)
    // Example output: Array with product data
    public function getProduct($productId) {
        try {
            $sql = "SELECT * FROM products WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$productId]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Get Product Error: " . $e->getMessage());
            throw new Exception("Failed to retrieve product");
        }
    }
    
    // Search products by name or description
    // Example input: searchProducts('laptop')
    // Example output: Array of matching products
    public function searchProducts($keyword) {
        try {
            $keyword = "%$keyword%";
            $sql = "SELECT * FROM products WHERE name LIKE ? OR description LIKE ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$keyword, $keyword]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Search Products Error: " . $e->getMessage());
            throw new Exception("Failed to search products");
        }
    }
    
    // Update product information
    // Example input: updateProduct(1, 'Updated Mouse', 'Better description', 89.99, 45)
    // Example output: "Product updated successfully"
    public function updateProduct($productId, $name, $description, $price, $stock) {
        try {
            // Validate price and stock
            if ($price <= 0 || $stock < 0) {
                throw new Exception("Invalid price or stock value");
            }
            
            $sql = "UPDATE products SET name = ?, description = ?, price = ?, stock = ? WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$name, $description, $price, $stock, $productId]);
            
            return "Product updated successfully";
        } catch (PDOException $e) {
            error_log("Update Product Error: " . $e->getMessage());
            throw new Exception("Failed to update product");
        }
    }
    
    // Update product stock
    // Example input: updateStock(1, 5, 'add')
    // Example output: "Stock updated successfully. New stock: 55"
    public function updateStock($productId, $quantity, $operation = 'add') {
        try {
            // Start transaction for stock update
            $this->pdo->beginTransaction();
            
            // Get current stock
            $sql = "SELECT stock FROM products WHERE id = ? FOR UPDATE";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$productId]);
            $currentStock = $stmt->fetchColumn();
            
            // Calculate new stock
            $newStock = $operation === 'add' ? 
                       $currentStock + $quantity : 
                       $currentStock - $quantity;
            
            // Validate new stock value
            if ($newStock < 0) {
                throw new Exception("Insufficient stock");
            }
            
            // Update stock
            $sql = "UPDATE products SET stock = ? WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$newStock, $productId]);
            
            // Commit transaction
            $this->pdo->commit();
            
            return "Stock updated successfully. New stock: $newStock";
        } catch (Exception $e) {
            // Rollback on error
            $this->pdo->rollBack();
            error_log("Update Stock Error: " . $e->getMessage());
            throw new Exception("Failed to update stock");
        }
    }
    
    // Delete product
    // Example input: deleteProduct(1)
    // Example output: "Product deleted successfully"
    public function deleteProduct($productId) {
        try {
            $sql = "DELETE FROM products WHERE id = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$productId]);
            
            return "Product deleted successfully";
        } catch (PDOException $e) {
            error_log("Delete Product Error: " . $e->getMessage());
            throw new Exception("Failed to delete product");
        }
    }
}

// Example usage:
/*
try {
    $productOps = new ProductOperations();
    
    // Create new product
    echo $productOps->createProduct('Gaming Mouse', 'High-DPI gaming mouse', 79.99, 50);
    
    // Search products
    $products = $productOps->searchProducts('mouse');
    print_r($products);
    
    // Update stock
    echo $productOps->updateStock(1, 5, 'add');
    
    // Delete product
    echo $productOps->deleteProduct(1);
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
*/