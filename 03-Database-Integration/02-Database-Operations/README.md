# Database Operations with MySQL

This section demonstrates common database operations (CRUD) using MySQL and PHP PDO. We'll cover:

1. Creating records (INSERT)
2. Reading records (SELECT)
3. Updating records (UPDATE)
4. Deleting records (DELETE)

## Prerequisites

- Completed MySQL Setup section
- Running XAMPP server
- Created database and tables

## Files in this Section

- `user_operations.php`: User management CRUD operations
- `product_operations.php`: Product catalog management
- `order_operations.php`: Order processing and management

## Features Demonstrated

1. User Management
   - User registration
   - Profile updates
   - User deletion
   - Password hashing

2. Product Management
   - Add new products
   - Update inventory
   - Product search
   - Delete products

3. Order Processing
   - Create new orders
   - Update order status
   - View order history
   - Order cancellation

## Security Practices

- Prepared statements for SQL injection prevention
- Password hashing for user security
- Input validation and sanitization
- Error handling and logging

## Expected Results

- Successful CRUD operations on users table
- Proper product inventory management
- Accurate order processing and tracking
- Secure data handling

## Usage Examples

Each file contains detailed examples with comments showing:
- Expected input values
- SQL queries used
- Expected output
- Error handling scenarios