# MySQL Setup with XAMPP

This section demonstrates how to set up and connect to MySQL using XAMPP. We'll cover:

1. Installing and configuring XAMPP
2. Starting MySQL server
3. Creating a database and tables
4. Establishing PHP-MySQL connection

## Prerequisites

- XAMPP installed on your system
- Basic understanding of SQL commands
- PHP development environment

## Installation Steps

1. Download XAMPP from the official website
2. Install XAMPP with MySQL and Apache components
3. Start MySQL and Apache services
4. Access phpMyAdmin through http://localhost/phpmyadmin

## Files in this Section

- `config.php`: Database connection configuration
- `connect.php`: Example of establishing MySQL connection
- `create_database.php`: Script to create a sample database

## Usage

1. Start XAMPP Control Panel
2. Start MySQL and Apache services
3. Run the example scripts in order
4. Check phpMyAdmin to verify database creation

## Expected Results

- Successful database connection
- Creation of sample database
- Basic database operations working

## Common Issues and Solutions

1. Connection refused
   - Verify MySQL service is running
   - Check credentials in config.php

2. Access denied
   - Verify user permissions
   - Check username and password

3. Port conflicts
   - Change MySQL port in XAMPP settings
   - Update connection settings accordingly