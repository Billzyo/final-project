# WARP.md

This file provides guidance to WARP (warp.dev) when working with code in this repository.

## Project Overview

This is a **Point of Sale (POS) Management System** built with a custom PHP MVC framework. The system handles retail transactions, product management, user authentication with role-based access, PDF receipt generation, and SMS notifications via Arduino GSM module integration.

## Common Commands

### Development Setup
```bash
# Install PHP dependencies
composer install

# Start local development server (XAMPP/WAMP)
# Ensure Apache and MySQL services are running in XAMPP control panel

# Database setup
# 1. Create database 'ink_pos' in phpMyAdmin
# 2. Import ink_pos.sql file
# 3. Update database credentials in app/core/database.php
```

### Development Workflow
```bash
# Access the application
# Navigate to: http://localhost/final-project/public/

# Access admin panel
# URL: http://localhost/final-project/public/?pg=admin

# API endpoints testing
# Base URL: http://localhost/final-project/public/api/
# Available endpoints: login.php, logout.php, products.php, sales.php, users.php
```

### File Management
```bash
# Product image uploads go to
public/uploads/

# Log files are stored in
app/logs/

# PDF receipts are generated in
public/uploads/receipts/
```

## Architecture Overview

### Custom MVC Framework Structure
The system implements a **custom-built PHP MVC framework** with the following core components:

#### Front Controller Pattern
- **Entry Point**: `public/index.php` - All requests flow through this single entry point
- **Routing**: Simple URL parameter-based routing (`?pg=controller_name`)
- **Controller Loading**: Dynamic controller loading based on `$_GET['pg']` parameter

#### Core Framework (`app/core/`)
- **`init.php`**: Application bootstrap - loads config, functions, database, models, and autoloader
- **`database.php`**: PDO-based database abstraction with prepared statements for security
- **`model.php`**: Base Active Record-style model class for database operations
- **`functions.php`**: Helper functions (views, authentication, image processing, receipts)
- **`config.php`**: Configuration constants (Twilio settings, database credentials)

#### MVC Components

**Controllers (`app/controllers/`)**:
- Follow simple routing: `?pg=home` loads `home.php`
- Controllers handle authentication checks before loading views
- Key controllers: `home.php` (POS interface), `admin.php` (admin panel), `ajax.php` (API endpoints)

**Models (`app/models/`)**:
- Extend base `Model` class for database operations
- Key models: `User.php`, `Product.php`, `Sale.php`, `Category.php`, `Auth.php`
- Follow Active Record pattern with query methods

**Views (`app/views/`)**:
- PHP template files with `.view.php` extension
- Loaded via `views_path()` helper function
- Organized by feature: `admin/`, `auth/`, `products/`, `partials/`

### Database Layer
- **PDO with prepared statements** for SQL injection prevention
- **Transaction support** for sale operations (sale + sale_items)
- **Database class** provides `query()` method for all database operations
- **Connection management** with error handling and attribute configuration

### API Architecture (`public/api/`)
- **REST-style endpoints** for external integration
- **JSON responses** with consistent error handling
- **Session-based authentication** carried over from web interface
- **Role-based access control** applied to API endpoints

### Authentication & Authorization
- **Session-based authentication** with `$_SESSION['USER']`
- **Role hierarchy**: Admin > Supervisor > Cashier > Accountant > User  
- **Access control**: `Auth::access('role')` checks throughout controllers
- **Password hashing** for secure storage

### Key Business Flows

#### Sales Transaction Flow
1. **Product Search**: Real-time AJAX search by name/barcode/category
2. **Cart Management**: Session-based cart with add/remove/update operations
3. **Checkout Processing**: Calculate totals, validate payment, create transaction
4. **Database Transaction**: Insert to `sales` and `sale_items` tables atomically
5. **Receipt Generation**: Create PDF via mPDF library with branding
6. **SMS Notification**: Optional SMS via Arduino GSM module (`send_receipt.php`)

#### User Management Flow  
- **Role-based dashboards** redirect users to appropriate interfaces
- **Profile management** with image upload and cropping
- **User CRUD operations** restricted to admin/supervisor roles

#### Product Management Flow
- **Category-based organization** for easier browsing
- **Image upload with automatic cropping** to standard dimensions
- **Barcode support** for scanner integration
- **Real-time search indexing** for POS interface

## Development Guidelines

### File Modifications
- **Controllers**: Add new features by creating new controller files in `app/controllers/`
- **Models**: Extend base `Model` class for new entities
- **Views**: Follow `.view.php` naming convention
- **API Endpoints**: Create in `public/api/` directory with consistent JSON responses

### Database Changes
- Update `app/core/database.php` for connection settings
- Use PDO prepared statements for all database operations
- Follow existing transaction patterns for multi-table operations

### Security Practices
- **Input Sanitization**: Use `esc()` function for output escaping
- **SQL Injection Prevention**: Always use prepared statements via `Database::query()`
- **Authentication**: Check `Auth::access()` before sensitive operations
- **File Upload Security**: Validate file types and use `crop()` function for images

### Integration Points
- **SMS Module**: Arduino GSM on COM12 port or Twilio API configuration
- **PDF Generation**: mPDF library for receipt formatting
- **Image Processing**: GD library for product image cropping
- **Session Management**: PHP sessions for authentication and cart state

### Role-Based Features
Understanding user roles is crucial for feature development:
- **Admin**: Full system access, user management, all CRUD operations
- **Supervisor**: Sales reports, product management, user viewing
- **Cashier**: POS operations, basic product search, sales processing
- **Accountant**: Financial reports and analytics access
- **User**: Basic read-only system access

### Common Patterns
- **View Loading**: `require views_path('path/to/view')`
- **Database Queries**: `$model->query("SELECT ...", $params)`
- **Redirects**: `redirect('pg=controller&param=value')`
- **Authentication**: `if(Auth::access('role')) { ... }`
- **Error Handling**: Database class returns `false` on failure