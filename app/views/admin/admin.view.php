<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --bg-sidebar: #ffffff;
            --text-color: #334155;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --hover-bg: #f1f5f9;
            --active-bg: #e2e8f0;
        }

        body {
            font-family: system-ui, -apple-system, sans-serif;
            background-color: #f8fafc;
            color: var(--text-color);
        }

        .admin-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-color);
            padding: 1.5rem 0;
        }

        .sidebar-container {
            background: var(--bg-sidebar);
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
            transition: all 0.3s ease;
        }

        .sidebar-container:hover {
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: var(--text-color);
            border-radius: 0.5rem;
            margin: 0.25rem 0;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .nav-link:hover {
            background-color: var(--hover-bg);
        }

        .nav-link.active {
            background-color: var(--active-bg);
            font-weight: 500;
        }

        .nav-link i {
            width: 1.5rem;
            margin-right: 0.75rem;
        }

        /* Icon Colors */
        .nav-link i.fa-th-large {
            color: #3b82f6;  /* Blue */
        }

        .nav-link i.fa-users {
            color: #8b5cf6;  /* Purple */
        }

        .nav-link i.fa-hamburger {
            color: #f59e0b;  /* Amber */
        }

        .nav-link i.fa-money-bill-wave {
            color: #10b981;  /* Emerald */
        }

        .nav-link i.fa-sign-out-alt {
            color: #ef4444;  /* Red */
        }

        .main-content {
            background: white;
            border-radius: 0.75rem;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
            padding: 1.5rem;
        }

        .content-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 1.5rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        @media (max-width: 768px) {
            .sidebar-container {
                margin-bottom: 1rem;
            }
        }
    </style>
</head>
<body>

<?php require views_path('partials/header');?>

<div class="container-fluid">
    <div class="text-center admin-title">
        <i class="fa fa-user-shield"></i> Admin Dashboard
    </div>

    <div class="row">
        <!-- Sidebar -->
        <div class="col-12 col-sm-4 col-md-3 col-lg-2 mb-4">
            <div class="sidebar-container" style="position: sticky; top: 0; height: 100vh; overflow-y: auto;">
                <div class="p-3">
                    <a href="index.php?pg=admin&tab=dashboard" 
                       class="nav-link <?=!$tab || $tab == 'dashboard' ? 'active' : ''?>">
                        <i class="fa fa-th-large"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="index.php?pg=admin&tab=users" 
                       class="nav-link <?=$tab == 'users' ? 'active' : ''?>">
                        <i class="fa fa-users"></i>
                        <span>Users</span>
                    </a>
                    <a href="index.php?pg=admin&tab=products" 
                       class="nav-link <?=$tab == 'products' ? 'active' : ''?>">
                        <i class="fa fa-hamburger"></i>
                        <span>Products</span>
                    </a>
                    <a href="index.php?pg=admin&tab=sales" 
                       class="nav-link <?=$tab == 'sales' ? 'active' : ''?>">
                        <i class="fa fa-money-bill-wave"></i>
                        <span>Sales</span>
                    </a>
                    <a href="index.php?pg=logout" class="nav-link">
                        <i class="fa fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col">
            <div class="main-content">
                <h4 class="content-title"><?=$tab ? ucfirst($tab) : 'Dashboard'?></h4>

                <?php
                switch ($tab) {
                    case 'products':
                        require views_path('admin/products');
                        break;
                    case 'users':
                        require views_path('admin/users');
                        break;
                    case 'sales':
                        require views_path('admin/sales');
                        break;
                    default:
                        require views_path('admin/dashboard');
                        break;
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php require views_path('partials/footer');?>

</body>
</html>