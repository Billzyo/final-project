THE UNIVERSITY OF [INSTITUTION NAME]
SCHOOL OF [SCHOOL/FACULTY NAME]
DEPARTMENT OF [DEPARTMENT NAME]

POINT OF SALE (POS) MANAGEMENT SYSTEM
BY
[YOUR FULL NAME]
CSC 4004 FINAL YEAR COURSE PROJECT
A THESIS SUBMITTED IN PARTIAL FULFILMENT OF THE REQUIREMENT FOR THE AWARD OF A BACHELOR’S DEGREE OF COMPUTER SCIENCE
SUPERVISOR: [SUPERVISOR NAME]

ABSTRACT
Numerous small and medium retail businesses continue to rely on manual or fragmented processes to handle product catalogs, sales transactions, receipts, inventory, and customer communication. These practices introduce inefficiencies, reduce data accuracy, and limit operational insight. To address these challenges, this project proposes and implements a comprehensive Point of Sale (POS) Management System built on a custom PHP MVC framework with a MySQL database, focusing on real-time product management, receipt generation, and SMS notifications. The system features role-based access control, a responsive user interface, robust API endpoints, and a modular architecture that facilitates maintainability and scalability.

The POS system supports real-time product search, barcode scanning, cart management, and secure checkout with automatic receipt generation (PDF) and optional SMS notifications via an Arduino GSM module. Administrative capabilities include sales analytics, product and user management, and detailed reporting. The system’s design emphasizes security through password hashing, prepared statements, and session-hardening techniques, while the modular MVC architecture supports a clean separation of concerns and testability.

Evaluation through structured test cases demonstrates that the system meets core functional and non-functional requirements across authentication, sales processing, inventory management, receipt generation, and SMS notification logging. The results indicate practical feasibility for deployment in retail environments, with observed strengths in usability, extensibility, and operational accuracy. Limitations include optional dependencies on external hardware for SMS and the need for further analytics and inventory reconciliation features. Future work includes expanding integrations (payment gateways, accounting systems), advanced inventory forecasting, and customer loyalty features.

DECLARATION
I, the undersigned, hereby declare that the Point of Sale (POS) Management System is my own work. It has not been submitted for any degree or examination in any other university to my knowledge, and all sources I have used or quoted have been indicated and acknowledged by complete references.

Author:
[YOUR FULL NAME]
Student ID: [YOUR STUDENT ID]
Signature: ___________________________

Supervisor:
[SUPERVISOR NAME]
Signature: ___________________________

DEDICATION
To my family and friends for unwavering support, encouragement, and belief in my journey, and to the educators who fostered my curiosity and discipline. This work is dedicated to everyone who values craftsmanship, perseverance, and continuous learning.

ACKNOWLEDGEMENT
I would like to express sincere gratitude to my supervisor, [SUPERVISOR NAME], for insightful guidance, feedback, and encouragement throughout the project. I also appreciate the contributions from peers and testers who provided valuable suggestions and helped refine the system’s usability, performance, and robustness.

Contents
1. INTRODUCTION
  1.1 Background
  1.2 Problem Statement
  1.3 Research Questions
  1.4 Aims
  1.5 Objectives
2. LITERATURE REVIEW
  2.1 Introduction
  2.2 Review of Similar Systems
  2.3 Summary
3. REQUIREMENTS ANALYSIS
  3.1 Software Development Methodology
    3.1.1 Development Method
    3.1.2 Required Resources
  3.2 Requirements
    3.2.1 Approach
    3.2.2 Functional Requirements
    3.2.3 Non-Functional Requirements
4. SYSTEM DESIGN
  4.1 Proposed System
  4.2 System and Algorithm Flowcharts
  4.3 System Structure
  4.4 User Interface Design
  4.5 Entity-Relationship (ER) Model
5. SYSTEM IMPLEMENTATION
  5.1 Description of the Developed System
  5.2 Technical Details
  5.3 Screens and Interfaces
6. TESTING AND VERIFICATION
  6.1 Testing Methodology
  6.2 Test Cases
  6.3 Analysis and Test Results
7. CONCLUSION
  7.1 Results and Achievements
  7.2 Limitations and Problems Faced
  7.3 Future Work and Recommendations
8. APPENDICES
  8.1 Appendix 1 – Domain Terms
  8.2 Appendix 2 – Installation
  8.3 Appendix 3 – Selected Source Code
9. REFERENCES

1. INTRODUCTION
1.1 Background
Retail environments demand efficient, reliable, and secure systems for processing transactions, managing product information, generating receipts, tracking inventory, and communicating with customers. Historically, many small and medium enterprises (SMEs) have employed manual cash registers, spreadsheets, and ad-hoc tools that fragment operational data. This leads to inconsistent records, limited analytics, and poor customer experiences.

With the maturity of web technologies and affordable open-source stacks, integrated POS solutions have become accessible. Still, many solutions are either overly generic, too costly for SMEs, or locked behind proprietary ecosystems. This project delivers a custom, extensible POS Management System built on a custom PHP MVC framework tailored for retail operations, providing real-time product management, robust reporting, receipt generation (PDF), and SMS notifications for digital proof-of-purchase.

1.2 Problem Statement
Existing workflows in SME retail are often fragmented: product catalogs, sales logs, and customer notifications live in separate tools or are managed manually. This fragmentation leads to errors during checkout, inconsistent inventory reconciliation, lack of real-time analytics, and limited traceability (e.g., missing receipt copies, lost transaction details). Additionally, pricing and feature barriers in commercial platforms reduce adoption in price-sensitive contexts.

1.3 Research Questions
- RQ1: Can a custom PHP MVC-based POS with REST-style endpoints provide a cost-effective and maintainable alternative for SMEs?
- RQ2: Does integrating PDF and SMS-based receipts improve operational transparency and customer satisfaction?
- RQ3: Can role-based access controls and prepared-statement database access improve security and data integrity in typical SME deployments?

1.4 Aims
To design, implement, and evaluate a secure, modular, and extensible POS Management System that streamlines retail transactions, supports real-time product management, and automates receipt generation and notifications.

1.5 Objectives
- Implement session-based authentication with role-based access control.
- Provide real-time product search, barcode scanning support, and shopping cart management.
- Implement reliable sale processing with database transactions and audit logging.
- Generate branded PDF receipts and optionally deliver SMS receipts via GSM hardware.
- Provide administrative dashboards, product CRUD, user management, and reporting.
- Expose REST-style API endpoints for integration and future mobile/IoT clients.
- Ensure security best practices (password hashing, prepared statements, XSS/CSRF mitigations).

2. LITERATURE REVIEW
2.1 Introduction
The literature review surveys existing POS solutions and frameworks, extracting best practices and identifying gaps relevant to SMEs. It emphasizes architecture patterns, usability, extensibility, and cost.

2.2 Review of Similar Systems
- Square POS (Cloud POS): Comprehensive retail solution with payments, inventory, and analytics. Strengths include seamless hardware integration and payment gateways; limitations include subscription costs and vendor lock-in.
- Lightspeed Retail: Feature-rich POS with advanced inventory and multi-store support. Offers deep analytics and integrations at higher cost tiers.
- Vend by Lightspeed: Cloud-based POS focusing on usability and inventory. Well-supported ecosystem; less control over hosting and data locality for SMEs desiring on-premise.
- Odoo POS (Open Source): Modular ERP with POS module. Extensible and self-hostable; complexity and learning curve can be high for small teams.
- Open Source POS (OSPOS): PHP-based POS with barcode and inventory. Good baseline, but theming, security posture, and extensibility vary with forks.

Observations
- Modern POS emphasizes responsive web UIs, modular architecture, and APIs.
- SMEs benefit from self-hosted deployments for data control and cost, but need sane defaults.
- Security and compliance are non-negotiable: password hashing, prepared statements, and session hardening are standard.

2.3 Summary
A custom, self-hosted POS tailored for SME needs can bridge the gap between cost, control, and functionality. Leveraging a custom MVC framework in PHP with MySQL enables maintainability, portability, and clear separation of concerns.

3. REQUIREMENTS ANALYSIS
3.1 Software Development Methodology
3.1.1 Development Method
An iterative and incremental approach was adopted. The system was decomposed into modules: authentication, products, cart/checkout, sales processing, receipt generation, notifications, reporting, and admin management. Each iteration encompassed requirements capture, design, implementation, and testing of one or two modules, enabling rapid feedback and continuous improvement.

3.1.2 Required Resources
- Hardware: Development machine; optional Arduino GSM module for SMS; barcode scanner for cashier station; thermal printer for receipts.
- Software: PHP 7.4+, MySQL 5.7+, Apache/Nginx; Composer; mPDF; Windows (XAMPP) or Linux (LAMP) stack for local development.
- Tooling: VS Code, Git, Postman for API testing, Browser devtools.

3.2 Requirements
3.2.1 Approach
Requirements were derived from stakeholder interviews, analysis of SME retail workflows, and benchmarking against comparable systems. Iterative demonstrations informed refinements, particularly in checkout UX and reporting.

3.2.2 Functional Requirements
- Authentication & Roles: Secure login; roles (Admin, Supervisor, Cashier, Accountant, User).
- Product Catalog: CRUD, categories, images, barcode support, search and filters.
- Cart & Checkout: Add/update/remove items; totals calculation; discounts/taxes; payment confirmation.
- Sales Processing: Transaction recording, receipt numbering, refunds/voids, audit logging.
- Receipt Generation: PDF receipts with branding; print and digital options; SMS notification support.
- User Management: Create/update users, role assignment, profile management.
- Reporting & Analytics: Daily/monthly/yearly sales; product performance; cashier KPIs; export.
- API Endpoints: Login/logout; list/search products; create/read sales; list users (role-restricted).

3.2.3 Non-Functional Requirements
- Security: Password hashing, prepared statements, XSS/CSRF mitigations, session hardening.
- Performance: Real-time search and responsive UI under typical SME loads.
- Usability: Intuitive POS interface optimized for fast cashier workflows.
- Maintainability: Modular MVC design, clear conventions.
- Scalability: Support growth in product catalog and concurrent sessions.
- Reliability: Consistent receipt generation, logging, and error handling.

4. SYSTEM DESIGN
4.1 Proposed System
A web-based POS application backed by a custom PHP MVC framework with MySQL persistence and a REST-style API. The public/ directory exposes entry points and assets, while app/ contains controllers, models, views, and core framework files (init, database, base model, helpers).

4.2 System and Algorithm Flowcharts
Login Flow (Role-based)
- User submits credentials → Auth verifies via hashed passwords → Session created with role → Route to role dashboard (Admin, Supervisor, Cashier, Accountant, User).

Checkout Flow
- Search/select product(s) → Add to cart → Compute totals → Confirm payment → Persist sale and items in DB (transaction) → Generate PDF → Optionally send SMS → Clear cart.

Admin Management Flow
- Admin login → Access dashboard → Manage products, users, prices → View reports → Export data.

4.3 System Structure
- app/core/
  - init.php: Bootstraps application, autoloads classes, starts session, loads config.
  - database.php: PDO-based database layer using prepared statements.
  - model.php: Base model with Active Record-like helpers.
  - functions.php: Common helpers (redirects, validation, formatting).
- app/controllers/: Route handlers (home.php POS UI; admin.php dashboard; login/logout; ajax.php for dynamic operations; product/sale/user CRUD controllers).
- app/models/: Business models (User.php, Product.php, Sale.php, Category.php, Pager.php, Graph.php, Auth.php).
- app/views/: View templates (admin, auth, products, sales, partials header/nav/footer, home POS page, print/receipt templates).
- public/: Front controller (index.php), assets (css/js/images), API endpoints (login, logout, users, products, sales), uploads/ for images.
- database/: SQL artifacts (e.g., sms_logs.sql), and ink_pos.sql (schema for main DB).

4.4 User Interface Design
- Responsive Bootstrap-based UI with role-specific dashboards.
- POS interface optimized for fast entry, barcode scanning, and immediate feedback.
- Admin views for products, users, sales, reports, with navigational partials (header/nav/footer) and pagination.

4.5 Entity-Relationship (ER) Model
Core entities and relationships (typical schema):
- users(id, name, email, password_hash, role, phone, created_at, updated_at)
- categories(id, name)
- products(id, name, category_id, barcode, price, cost_price, image_path, stock_qty, created_at, updated_at)
- sales(id, user_id, total, paid_amount, change_amount, receipt_number, created_at)
- sale_items(id, sale_id, product_id, qty, unit_price, line_total)
- sms_logs(id, phone, message, status, created_at)
- receipts(id, sale_id, pdf_path, created_at)
Relationships: users 1–* sales; sales 1–* sale_items; categories 1–* products; products 1–* sale_items; sales 1–1 receipts.

5. SYSTEM IMPLEMENTATION
5.1 Description of the Developed System
The system is implemented as a custom PHP MVC web application with REST-style API endpoints. It provides real-time product search, barcode scanning support, cart and checkout workflows, robust receipt generation (mPDF), and optional SMS notifications via Arduino GSM. Role-based dashboards expose administrative features, reports, and user management.

5.2 Technical Details
- Backend: PHP 7.4+, custom MVC, Composer. PDO with prepared statements for DB access. Session-based authentication with role checks. Error handling and logging.
- Frontend: HTML5/CSS3/JS with Bootstrap. AJAX for dynamic operations and real-time search. Responsive layout for desktop POS terminals and tablets.
- Database: MySQL with normalized schema and indices for search performance. Transactional integrity for sales and sale items.
- PDF Receipts: mPDF for branded, itemized PDF receipts with logos and auto-numbering.
- SMS Notifications: Arduino GSM module for sending SMS receipts; message logging in database. Optional Twilio configuration abstraction for alternative providers.
- APIs: public/api endpoints such as login.php, logout.php, users.php, products.php, sales.php, product-create.php, user-create.php.
- Assets & Uploads: public/assets for css/js/images; public/uploads for product images with image optimization and cropping.

5.3 Screens and Interfaces
- Authentication: Login, logout, access-denied views.
- POS Home: Product search, category filters, cart management, totals, checkout.
- Admin Dashboard: KPIs (sales summaries), users, products, sales, analytics.
- Products: List, create, edit, delete, image upload.
- Sales: List sales, print receipts, export data.
- Users: Create accounts, assign roles, edit profiles.

6. TESTING AND VERIFICATION
6.1 Testing Methodology
Quality dimensions considered include correctness, functionality, structure, usability, navigability, performance, compatibility, and security. Methods included unit-level verifications on models and helpers, integration tests of checkout flow, and manual trials of UI flows across roles. API endpoints were validated with Postman using both valid and invalid inputs.

6.2 Test Cases (Representative)
1. Unregistered users cannot access protected routes (login enforced).
   Approach: Human Trial + Endpoint tests. Pass: redirect to login.
2. Registered users can authenticate and receive role-appropriate dashboards.
   Approach: Human Trial. Pass: correct role landing pages.
3. Real-time product search returns correct matches by name/barcode/category.
   Approach: Human Trial + seeded data. Pass: correct results and latency.
4. Cart operations (add/update/remove) compute totals correctly.
   Approach: Human Trial with known prices/qty. Pass: exact totals.
5. Checkout persists sale and items transactionally.
   Approach: Inspect DB; rollback on error. Pass: consistent data.
6. Receipt generation produces branded PDF with accurate line items and totals.
   Approach: Open PDF; verify fields. Pass: correct details and numbering.
7. SMS notifications are logged and sent (when hardware connected).
   Approach: Human Trial with GSM. Pass: message sent and logged.
8. Product CRUD enforces validation and reflects in search and POS.
   Approach: Human Trial. Pass: visible updates.
9. Role-based access prevents unauthorized operations (e.g., cashier editing users).
   Approach: Attempt restricted actions. Pass: access denied.
10. Basic security checks (SQL injection attempts, XSS payloads) are mitigated.
    Approach: Crafted inputs. Pass: sanitized and safe outputs.

6.3 Analysis and Test Results
All core scenarios passed. Real-time search remained responsive under typical SME datasets. PDF generation was reliable with consistent formatting. SMS delivery success depended on GSM module connectivity; logs accurately reflected outcomes. Security controls (hashed passwords, prepared statements, input/output sanitization) mitigated common attack vectors. The modular MVC structure eased targeted testing and reduced regression risk across iterations.

7. CONCLUSION
7.1 Results and Achievements
The POS Management System met its objectives: secure authentication and role management; fast, usable POS workflows; reliable sales persistence; branded PDF receipts; optional SMS notifications; and admin dashboards with reporting. The custom MVC framework provided a clean separation of concerns and a foundation for extensibility.

7.2 Limitations and Problems Faced
- Optional SMS delivery depends on external hardware (Arduino GSM) or third-party services; reliability varies with connectivity and configuration.
- Inventory reconciliation and advanced analytics (e.g., forecasting) are basic in the current scope.
- Multi-branch and offline-first capabilities are outside the MVP and would require synchronization logic.

7.3 Future Work and Recommendations
- Integrate payment gateways (mobile money, cards) and accounting systems.
- Implement inventory forecasting, low-stock alerts, and purchase orders.
- Add loyalty programs, customer profiles, and targeted promotions.
- Expand reporting with drill-down analytics and export formats.
- Support offline-first mode with background sync and conflict resolution.
- Containerize deployment and add CI/CD pipelines with automated tests.

8. APPENDICES
8.1 Appendix 1 – Domain Terms
- POS (Point of Sale): The place and system where retail transactions occur.
- SKU/Barcode: Identifiers used to uniquely identify products for scanning/search.
- Receipt: Proof-of-purchase issued to customer; may be printed or digital (PDF/SMS).
- Role-Based Access Control (RBAC): Authorization model assigning permissions by role.

8.2 Appendix 2 – Installation
Prerequisites
- PHP 7.4+; MySQL 5.7+; Apache/Nginx; Composer installed.
- Optional: Arduino GSM module for SMS.

Setup Steps
1. Clone the repository
   git clone <repository-url>
   cd final-project
2. Install dependencies
   composer install
3. Database setup
   - Create database ink_pos
   - Import schema from ink_pos.sql
   - Update credentials in app/core/database.php
4. Configure SMS (optional)
   - Connect Arduino GSM on configured COM port
   - Alternatively set provider credentials in app/core/config.php
5. Web server configuration
   - Point document root to public/
   - Ensure mod_rewrite is enabled

8.3 Appendix 3 – Selected Source Code
Note: File paths relative to project root (final-project/).
- app/core/database.php: PDO database connection and helper methods.
- app/core/model.php: Base model class (Active Record-style helpers).
- app/controllers/home.php: POS interface controller.
- app/controllers/admin.php: Admin dashboard and management actions.
- public/api/products.php, public/api/sales.php: REST-style endpoints.

9. REFERENCES
- MySQL. https://www.mysql.com
- PHP Manual: PDO. https://www.php.net/manual/en/book.pdo.php
- mPDF Library. https://mpdf.github.io/
- OWASP Cheat Sheet Series (Authentication, Injection Prevention, XSS, CSRF). https://cheatsheetseries.owasp.org/
- Square POS (Product docs). https://squareup.com/
- Odoo POS. https://www.odoo.com/
- Open Source POS. https://github.com/opensourcepos/opensourcepos
