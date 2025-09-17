# Point of Sale (POS) System

A comprehensive Point of Sale system built with a custom PHP MVC framework, featuring real-time product management, receipt generation, SMS notifications, and role-based access control.

## 🏗️ Architecture

This project implements a **Custom MVC Framework** with the following characteristics:

- **Custom-built PHP framework** (not using Laravel, CodeIgniter, or other established frameworks)
- **MVC Pattern** with clear separation of concerns
- **Simple URL-based routing** system
- **Active Record** database abstraction layer
- **View templating** system with PHP includes
- **Session-based authentication** with role-based access control
- **RESTful API** endpoints
- **Composer** dependency management

## 🚀 Features

### Core POS Features
- **Real-time Product Search** - Search by name, barcode, or category
- **Shopping Cart Management** - Add, remove, and modify items
- **Receipt Generation** - PDF receipts with company branding
- **SMS Notifications** - Automatic SMS receipts via Arduino GSM module
- **Barcode Scanning** - Support for barcode scanner integration
- **Multi-category Products** - Organized product catalog (Cakes, Bread, Fast Food, Drinks, etc.)

### User Management
- **Role-based Access Control** - Admin, Supervisor, Cashier, Accountant, User roles
- **User Authentication** - Secure login with password hashing
- **Profile Management** - User profile editing and settings
- **Session Management** - Secure session handling

### Admin Features
- **Dashboard Analytics** - Sales reports and statistics
- **Product Management** - CRUD operations for products
- **Sales Reporting** - Daily, monthly, yearly sales analysis
- **User Management** - Create and manage user accounts
- **Inventory Tracking** - Product view counts and sales tracking

### Technical Features
- **Responsive Design** - Mobile-friendly interface
- **Real-time Updates** - AJAX-powered dynamic content
- **Image Processing** - Automatic image cropping and optimization
- **Database Logging** - Comprehensive transaction and SMS logging
- **Error Handling** - Robust error management and logging

## 📁 Project Structure

```
final-project/
├── app/
│   ├── controllers/          # MVC Controllers
│   │   ├── home.php         # POS interface
│   │   ├── login.php        # Authentication
│   │   ├── admin.php        # Admin panel
│   │   └── ajax.php         # AJAX endpoints
│   ├── models/              # MVC Models
│   │   ├── User.php         # User management
│   │   ├── Product.php      # Product catalog
│   │   ├── Sale.php         # Sales transactions
│   │   └── Auth.php         # Authentication
│   ├── views/               # MVC Views
│   │   ├── auth/            # Authentication views
│   │   ├── admin/           # Admin panel views
│   │   ├── products/        # Product management views
│   │   └── partials/        # Reusable components
│   └── core/                # Framework core
│       ├── init.php         # Application initialization
│       ├── database.php     # Database abstraction
│       ├── model.php        # Base model class
│       └── functions.php    # Helper functions
├── public/
│   ├── index.php            # Main entry point
│   ├── assets/              # Static assets
│   │   ├── css/            # Stylesheets
│   │   ├── js/             # JavaScript files
│   │   └── images/         # Images and icons
│   └── api/                 # RESTful API endpoints
└── database/
    └── sms_logs.sql         # Database schema
```

## 🔄 System Flow

### Customer Purchase Flow
1. **Cashier Login** → Authentication and role verification
2. **Product Selection** → Search, browse, or scan products
3. **Cart Management** → Add items and calculate totals
4. **Checkout Process** → Payment validation and change calculation
5. **Sale Processing** → Database transaction recording
6. **Receipt Generation** → PDF creation and printing
7. **SMS Notification** → Automatic SMS via Arduino GSM module
8. **Transaction Complete** → Cart cleared, ready for next customer

### Admin Management Flow
1. **Admin Login** → Role-based access verification
2. **Dashboard Access** → Sales analytics and reports
3. **Product Management** → Add, edit, delete products
4. **User Management** → Create and manage user accounts
5. **Sales Reporting** → View and export sales data

## 🛠️ Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- Composer (for dependency management)

### Setup Steps
1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd final-project
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Database setup**
   - Create MySQL database named `ink_pos`
   - Import the database schema from `ink_pos.sql`
   - Update database credentials in `app/core/database.php`

4. **Configure SMS (Optional)**
   - Set up Arduino GSM module on COM12 port
   - Configure Twilio credentials in `app/core/config.php`

5. **Web server configuration**
   - Point document root to `public/` directory
   - Ensure mod_rewrite is enabled (for clean URLs)

## 🔧 Configuration

### Database Configuration
Update `app/core/database.php` with your database credentials:
```php
$DBHOST = "localhost";
$DBNAME = "ink_pos";
$DBUSER = "your_username";
$DBPASS = "your_password";
```

### SMS Configuration
Configure Twilio settings in `app/core/config.php`:
```php
define("TWILIO_SID", "your_twilio_account_sid_here");
define("TWILIO_AUTH_TOKEN", "your_twilio_auth_token_here");
define("TWILIO_PHONE_NUMBER", "your_twilio_phone_number_here");
```

## 👥 User Roles

- **Admin**: Full system access, user management, all features
- **Supervisor**: Sales management, reporting, product management
- **Cashier**: POS operations, basic product viewing
- **Accountant**: Financial reporting and analysis
- **User**: Basic system access

## 📱 API Endpoints

- `POST /api/login` - User authentication
- `GET /api/logout` - User logout
- `GET /api/users` - User management
- `GET /api/products` - Product operations
- `GET /api/sales` - Sales transactions

## 🔒 Security Features

- **Password Hashing** - Secure password storage
- **SQL Injection Prevention** - PDO prepared statements
- **XSS Protection** - Input sanitization and output escaping
- **Session Security** - Secure session management
- **Role-based Access** - Granular permission system
- **CSRF Protection** - Cross-site request forgery prevention

## 📊 Technologies Used

- **Backend**: PHP 7.4+, Custom MVC Framework
- **Database**: MySQL with PDO
- **Frontend**: HTML5, CSS3, JavaScript (ES6), Bootstrap 5
- **PDF Generation**: mPDF library
- **SMS Integration**: Arduino GSM module
- **Dependency Management**: Composer
- **Version Control**: Git

## 📈 Features in Detail

### Real-time POS Interface
- Dynamic product search with instant results
- Category-based filtering
- Shopping cart with real-time calculations
- Barcode scanner integration
- Mobile-responsive design

### Receipt System
- Professional PDF receipt generation
- Company branding and logo
- Detailed itemized receipts
- Automatic receipt numbering
- Print and digital receipt options

### SMS Integration
- Automatic SMS notifications
- Arduino GSM module integration
- SMS delivery logging
- Customizable message templates

### Reporting & Analytics
- Daily, monthly, yearly sales reports
- Product performance analytics
- Cashier performance tracking
- Export capabilities for data analysis

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## 📄 License

This project is developed as a final year project. Please contact the development team for licensing information.

## 👨‍💻 Development Team

- **Framework**: Custom PHP MVC
- **Database Design**: MySQL with optimized queries
- **Frontend**: Responsive Bootstrap interface
- **Integration**: Arduino GSM for SMS functionality

## 📞 Support

For technical support or questions about this POS system, please contact the development team or create an issue in the repository.

---

**Note**: This is a comprehensive Point of Sale system designed for retail environments with features like real-time inventory management, receipt generation, and customer communication via SMS.
