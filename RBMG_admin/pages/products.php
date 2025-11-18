<?php
include("../includes/session.php");
include("../config/db.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RaeBioMedGlobal - Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Favicons -->
    <link rel="icon" type="image/png" href="../../assets/favicon/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="../../assets/favicon/favicon.svg" />
    <link rel="shortcut icon" href="../../assets/favicon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="../../assets/favicon/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="MyWebSite" />
    <link rel="manifest" href="../../assets/favicon/site.webmanifest" />
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
     <!-- jQuery (DataTable dependency) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
     <!-- data tables css  -->
      <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">
     
    <!-- data tables js  -->
     <script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #2563eb;
            --secondary-color: #1e40af;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #06b6d4;
            --bg-color: #f8fafc;
            --sidebar-bg: #ffffff;
            --card-bg: #ffffff;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --border-color: #e2e8f0;
            --shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        [data-theme="dark"] {
            --bg-color: #0f172a;
            --sidebar-bg: #1e293b;
            --card-bg: #1e293b;
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --border-color: #334155;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-primary);
            transition: all 0.3s ease;
        }

        html {
            overflow-y: scroll
        }

        body.modal-open {
            overflow: hidden;
            padding-right: 0 !important;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 260px;
            background: var(--sidebar-bg);
            border-right: 1px solid var(--border-color);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            transition: transform 0.3s ease;
            z-index: 1000;
        }

        .sidebar.collapsed {
            transform: translateX(-100%);
        }

        .logo {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .logo i {
            font-size: 1.75rem;
            color: var(--primary-color);
        }

        .logo h2 {
            font-size: 1.25rem;
            color: var(--text-primary);
        }

        .nav-menu {
            padding: 1rem 0;
        }

        .nav-item {
            padding: 0.875rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            color: var(--text-secondary);
            text-decoration: none;
            transition: all 0.2s;
            cursor: pointer;
            border-left: 3px solid transparent;
        }

        .nav-item:hover {
            background: var(--bg-color);
            color: var(--primary-color);
        }

        .nav-item.active {
            background: var(--bg-color);
            color: var(--primary-color);
            border-left-color: var(--primary-color);
        }

        .nav-item i {
            font-size: 1.125rem;
            width: 20px;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 260px;
            transition: margin-left 0.3s ease;
        }

        .main-content.expanded {
            margin-left: 0;
        }

        /* Header */
        .header {
            background: var(--card-bg);
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--text-primary);
            cursor: pointer;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .theme-toggle {
            background: none;
            border: none;
            font-size: 1.25rem;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.5rem;
            transition: all 0.2s;
        }

        .theme-toggle:hover {
            background: var(--bg-color);
            color: var(--primary-color);
        }

        .profile-dropdown {
            position: relative;
        }

        .profile-btn {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.2s;
            background: none;
            border: none;
            color: var(--text-primary);
        }

        .profile-btn:hover {
            background: var(--bg-color);
        }

        .profile-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 0.5rem;
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            box-shadow: var(--shadow-lg);
            min-width: 200px;
            display: none;
            z-index: 1000;
        }

        .dropdown-menu.show {
            display: block;
        }

        .dropdown-item {
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--text-primary);
            text-decoration: none;
            transition: all 0.2s;
            cursor: pointer;
        }

        .dropdown-item:hover {
            background: var(--bg-color);
        }

        .dropdown-divider {
            height: 1px;
            background: var(--border-color);
            margin: 0.5rem 0;
        }

        /* Content Area */
        .content {
            padding: 2rem 1.5rem;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .profile-img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #0d6efd;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .page-title {
            font-size: 1.875rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .btn-add {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s;
        }

        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.3);
        }

        /* Table Card */
        .table-card {
            background: var(--card-bg);
            border-radius: 1rem;
            box-shadow: var(--shadow);
            overflow: hidden;
            border: 1px solid var(--border-color);
        }

        .table-responsive {
            overflow-x: auto;
        }

        .custom-table {
            width: 100%;
            margin: 0;
        }

        .custom-table thead {
            background: var(--bg-color);
        }

        .custom-table th {
            padding: 1rem;
            font-weight: 600;
            color: var(--text-primary);
            border-bottom: 2px solid var(--border-color);
            white-space: nowrap;
        }

        .custom-table td {
            padding: 1rem;
            color: var(--text-secondary);
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }

        .custom-table tbody tr {
            transition: all 0.2s;
        }

        .custom-table tbody tr:hover {
            background: var(--bg-color);
        }

        .product-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 0.5rem;
        }

        .price-badge {
            background: linear-gradient(135deg, var(--success-color), #059669);
            color: white;
            padding: 0.375rem 0.75rem;
            border-radius: 0.375rem;
            font-weight: 600;
            display: inline-block;
        }

        .category-badge {
            background: var(--bg-color);
            color: var(--primary-color);
            padding: 0.375rem 0.75rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            display: inline-block;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .btn-edit,
        .btn-delete,
        .btn-view {
            padding: 0.5rem 0.75rem;
            border: none;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .btn-view {
            background: #06b6d4;
            color: white;
        }

        .btn-view:hover {
            background: #0891b2;
        }

        .btn-edit {
            background: #3b82f6;
            color: white;
        }

        .btn-edit:hover {
            background: #2563eb;
        }

        .btn-delete {
            background: #ef4444;
            color: white;
        }

        .btn-delete:hover {
            background: #dc2626;
        }

        /* Modal Customization */
        .modal-content {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 1rem 1rem 0 0;
            border-bottom: none;
        }

        .modal-title {
            font-weight: 700;
        }

        .btn-close {
            filter: brightness(0) invert(1);
        }

        .form-label {
            color: var(--text-primary);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .form-control,
        .form-select {
            background: var(--bg-color);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            padding: 0.75rem;
            border-radius: 0.5rem;
        }

        .form-control:focus,
        .form-select:focus {
            background: var(--bg-color);
            border-color: var(--primary-color);
            color: var(--text-primary);
            box-shadow: 0 0 0 0.25rem rgba(37, 99, 235, 0.1);
        }

        .image-preview {
            width: 100%;
            max-height: 200px;
            object-fit: cover;
            border-radius: 0.5rem;
            margin-top: 0.5rem;
            display: none;
        }

        .image-preview.show {
            display: block;
        }

        /* Specifications Display */
        .spec-item {
            background: var(--bg-color);
            padding: 0.5rem 0.75rem;
            border-radius: 0.375rem;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
        }

        /* Sidebar Overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .sidebar-overlay.show {
            display: block;
        }

        /* Mobile Product Cards */
        .mobile-product-card {
            background: var(--card-bg);
            border-radius: 0.75rem;
            padding: 1.25rem;
            margin-bottom: 1rem;
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow);
            height: 100%;
        }

        .mobile-product-header {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .mobile-product-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 0.5rem;
            flex-shrink: 0;
        }

        .mobile-product-info {
            flex: 1;
        }

        .mobile-product-info h4 {
            font-size: 1.125rem;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }

        .mobile-product-category {
            background: var(--bg-color);
            color: var(--primary-color);
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-block;
            margin-bottom: 0.5rem;
        }

        .mobile-product-price {
            background: linear-gradient(135deg, var(--success-color), #059669);
            color: white;
            padding: 0.375rem 0.75rem;
            border-radius: 0.375rem;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 0.5rem;
        }

        .mobile-product-description {
            color: var(--text-secondary);
            font-size: 0.875rem;
            line-height: 1.4;
            margin-bottom: 1rem;
        }

        /* Tablet Product Grid - 2 cards per row */
        .tablet-product-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.25rem;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .content {
                padding: 1.5rem;
            }

            .page-title {
                font-size: 1.75rem;
            }
        }

        @media (max-width: 992px) {
            .content {
                padding: 1.25rem;
            }

            .page-title {
                font-size: 1.5rem;
            }

            /* Tablet View - Show 2 cards per row */
            .custom-table {
                display: none;
            }

            .mobile-product-cards {
                display: block;
            }

            .tablet-product-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 1.25rem;
            }

            .mobile-product-card {
                margin-bottom: 0;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .menu-toggle {
                display: block;
            }

            .profile-info {
                display: none;
            }

            .content {
                padding: 1rem;
            }

            .page-title {
                font-size: 1.375rem;
            }

            .page-header {
                margin-bottom: 1.5rem;
            }

            .custom-table {
                display: none;
            }

            /* .mobile-product-cards {
                display: block;
            } */

            /* Mobile View - 1 card per row */
            .tablet-product-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .custom-table th,
            .custom-table td {
                padding: 0.75rem 0.5rem;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-edit,
            .btn-delete,
            .btn-view {
                padding: 0.6rem;
                font-size: 0.8rem;
                justify-content: center;
            }

            .mobile-product-header {
                flex-direction: column;
                text-align: center;
            }

            .mobile-product-img {
                width: 120px;
                height: 120px;
                margin: 0 auto;
            }

            .modal-dialog {
                margin: 0.5rem;
            }

            .modal-header {
                padding: 1rem;
            }

            .modal-body {
                padding: 1rem;
            }

            .modal-footer {
                padding: 1rem;
            }
        }

        @media (max-width: 576px) {
            .content {
                padding: 0.75rem;
            }

            .page-title {
                font-size: 1.25rem;
            }

            .header {
                padding: 0.75rem 1rem;
            }

            .btn-add {
                padding: 0.6rem 1.2rem;
                font-size: 0.875rem;
            }

            .mobile-product-img {
                width: 100px;
                height: 100px;
            }

            .modal-dialog {
                margin: 0.25rem;
            }

            .modal-header,
            .modal-body,
            .modal-footer {
                padding: 0.75rem;
            }
        }

        @media (min-width: 993px) {
            .mobile-product-cards {
                display: none;
            }

            .tablet-product-grid {
                display: none;
            }
        }

        /* Smooth scrolling for modal body */
        .modal-body {
            scrollbar-width: thin;
            scrollbar-color: var(--primary-color) var(--bg-color);
        }

        .modal-body::-webkit-scrollbar {
            width: 8px;
        }

        .modal-body::-webkit-scrollbar-track {
            background: var(--bg-color);
            border-radius: 10px;
        }

        .modal-body::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 10px;
        }

        .modal-body::-webkit-scrollbar-thumb:hover {
            background: var(--secondary-color);
        }

        /* Image preview card styling */
        .card-body {
            scrollbar-width: thin;
            scrollbar-color: var(--primary-color) var(--bg-color);
        }

        .card-body::-webkit-scrollbar {
            width: 6px;
        }

        .card-body::-webkit-scrollbar-track {
            background: var(--bg-color);
            border-radius: 10px;
        }

        .card-body::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 10px;
        }

        /* Responsive modal adjustments */
        @media (max-width: 768px) {
            .modal-body {
                max-height: 60vh !important;
            }

            .card-body {
                max-height: 200px !important;
            }
        }

        @media (max-width: 576px) {
            .modal-dialog {
                margin: 0.5rem;
            }

            .modal-body {
                max-height: 55vh !important;
                padding: 1rem;
            }

            .card-body {
                max-height: 180px !important;
            }
        }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <?php include("../includes/sidebar.php") ?>

        <!-- Sidebar Overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

        <!-- Main Content -->
        <main class="main-content" id="mainContent">
            <!-- Header -->
            <?php include("../includes/header.php") ?>

            <!-- Content -->
            <div class="content">
                <div class="page-header">
                    <h2 class="page-title">Manage Products</h2>
                    <button class="btn-add" data-bs-toggle="modal" data-bs-target="#productModal" onclick="resetForm()">
                        <i class="fas fa-plus"></i>
                        Add Product
                    </button>
                </div>

                <!-- Products Table (Desktop) -->
                <div class="table-card">
                    <div class="table-responsive">
                        <table class="custom-table" id="myTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Category</th>
                                    <th>Product Name</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Specification</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="productsTableBody">
                                <!-- Products will be populated here -->
                                <?php
                                $query = mysqli_query($conn, "SELECT p.*, c.cat_name FROM products p LEFT JOIN categories c ON p.category_id = c.id");
                                $i = 1;
                                while ($fetch = mysqli_fetch_assoc($query)) {
                                ?>
                                    <tr>
                                        <td><?= $i++; ?></td>
                                        <td>
                                            <img src="../assets/images/products/<?= $fetch['image']; ?>" width="60" height="60" class="rounded product-img">
                                        </td>
                                        <td><span class="category-badge"><?= $fetch['cat_name']; ?></span></td>
                                        <td><strong><?= $fetch['name']; ?></strong></td>
                                        <td><?= substr($fetch['description'], 0, 50) . '...'; ?></td>
                                        <td><span class="price-badge">₹<?= $fetch['price']; ?></span></td>
                                        <td><?= substr($fetch['specification'], 0, 50) . '...'; ?></td>

                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn-view" onclick="viewProduct(<?= $fetch['id']; ?>)">
                                                    <i class="fas fa-eye"></i> View
                                                </button>
                                                <button class="btn-edit" data-bs-toggle="modal" data-bs-target="#editProductModal<?= $fetch['id']; ?>">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                                <button class="btn-delete" onclick="confirmDelete(<?= $fetch['id']; ?>)">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Edit Product Modal -->
                                    <div class="modal fade" id="editProductModal<?= $fetch['id']; ?>" tabindex="-1">
                                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <form action="../codes/update_product.php" method="POST" enctype="multipart/form-data">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">
                                                            <i class="fas fa-edit me-2"></i>Edit Product
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                                                        <input type="hidden" name="prod_id" value="<?= $fetch['id']; ?>">
                                                        <input type="hidden" name="old_image" value="<?= $fetch['image']; ?>">

                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label for="editCategoryId<?= $fetch['id']; ?>" class="form-label">
                                                                    <i class="fas fa-layer-group me-2"></i>Category
                                                                </label>
                                                                <select class="form-select" name="category_id" id="editCategoryId<?= $fetch['id']; ?>" required>
                                                                    <option value="">Select Category</option>
                                                                    <?php
                                                                    $cat_query = mysqli_query($conn, "SELECT * FROM categories");
                                                                    while ($c = mysqli_fetch_assoc($cat_query)) {
                                                                        $sel = ($c['id'] == $fetch['category_id']) ? "selected" : "";
                                                                        echo "<option value='{$c['id']}' $sel>{$c['cat_name']}</option>";
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>

                                                            <div class="col-md-6 mb-3">
                                                                <label for="editProductName<?= $fetch['id']; ?>" class="form-label">
                                                                    <i class="fas fa-box me-2"></i>Product Name
                                                                </label>
                                                                <input type="text" class="form-control" name="name" id="editProductName<?= $fetch['id']; ?>" value="<?= $fetch['name']; ?>" placeholder="Enter product name" required>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label for="editProductPrice<?= $fetch['id']; ?>" class="form-label">
                                                                    <i class="fas fa-rupee-sign me-2"></i>Price (₹)
                                                                </label>
                                                                <input type="number" class="form-control" name="price" id="editProductPrice<?= $fetch['id']; ?>" value="<?= $fetch['price']; ?>" placeholder="Enter price" min="0" step="0.01" required>
                                                            </div>

                                                            <div class="col-md-6 mb-3">
                                                                <label for="editProductImage<?= $fetch['id']; ?>" class="form-label">
                                                                    <i class="fas fa-image me-2"></i>Product Image
                                                                </label>
                                                                <input type="file" class="form-control" name="image" id="editProductImage<?= $fetch['id']; ?>" accept="image/*" onchange="previewEditImage(event, <?= $fetch['id']; ?>)">
                                                                <small class="text-muted">Max size: 5MB | Formats: JPG, PNG, GIF, WEBP</small>
                                                            </div>
                                                        </div>

                                                        <!-- Current Image Display -->
                                                        <div class="row mb-3">
                                                            <div class="col-12">
                                                                <div class="card border-secondary">
                                                                    <div class="card-header bg-secondary text-white">
                                                                        <i class="fas fa-image me-2"></i>Current Image
                                                                    </div>
                                                                    <div class="card-body text-center">
                                                                        <img src="../assets/images/products/<?= $fetch['image']; ?>" alt="Current Image" class="img-fluid rounded shadow" style="max-width: 200px; height: 100px;">
                                                                        <p class="mt-2 mb-0 text-muted">Current product image</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Image Preview Section (Scrollable) -->
                                                        <div class="row" id="editImagePreviewContainer<?= $fetch['id']; ?>" style="display: none;">
                                                            <div class="col-12 mb-3">
                                                                <div class="card border-primary">
                                                                    <div class="card-header bg-primary text-white">
                                                                        <i class="fas fa-eye me-2"></i>New Image Preview
                                                                    </div>
                                                                    <div class="card-body text-center" style="max-height: 300px; overflow-y: auto;">
                                                                        <img id="editImagePreview<?= $fetch['id']; ?>" src="" alt="Preview" class="img-fluid rounded shadow" style="max-width: 100%; height: auto;">
                                                                        <button type="button" class="btn btn-sm btn-danger mt-2" onclick="removeEditImage(<?= $fetch['id']; ?>)">
                                                                            <i class="fas fa-trash me-1"></i>Remove New Image
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="editProductDescription<?= $fetch['id']; ?>" class="form-label">
                                                                <i class="fas fa-align-left me-2"></i>Description
                                                            </label>
                                                            <textarea class="form-control" name="description" id="editProductDescription<?= $fetch['id']; ?>" rows="4" placeholder="Enter detailed product description" required><?= $fetch['description']; ?></textarea>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="editProductSpec<?= $fetch['id']; ?>" class="form-label">
                                                                <i class="fas fa-list-ul me-2"></i>Specifications
                                                            </label>
                                                            <textarea class="form-control" name="specification" id="editProductSpec<?= $fetch['id']; ?>" rows="5" placeholder="Enter specifications (one per line)&#10;Example:&#10;Material: Stainless Steel&#10;Weight: 2kg&#10;Dimensions: 30x20x10 cm&#10;Warranty: 1 Year" required><?= $fetch['specification']; ?></textarea>
                                                            <small class="text-muted">Enter each specification on a new line</small>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                            <i class="fas fa-times me-2"></i>Cancel
                                                        </button>
                                                        <button type="submit" name="update_product" class="btn btn-primary">
                                                            <i class="fas fa-save me-2"></i>Update Product
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                <?php } ?>
                                <script>
                                    let table = new DataTable('#myTable');
                                   </script> 
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tablet & Mobile Product Cards -->
                <div class="mobile-product-cards">
                    <div class="tablet-product-grid" id="tabletProductGrid">
                        <?php
                        $query = "SELECT p.id, p.name, p.description, p.price, p.image, p.specification, c.cat_name AS category 
                                FROM products p 
                                LEFT JOIN categories c ON p.category_id = c.id
                                ORDER BY p.id DESC";
                        $result = mysqli_query($conn, $query);

                        if (mysqli_num_rows($result) > 0) {
                            while ($product = mysqli_fetch_assoc($result)) {
                        ?>
                                <div class="mobile-product-card">
                                    <div class="mobile-product-header">
                                        <img src="../assets/images/products/<?php echo htmlspecialchars($product['image']); ?>"
                                            alt="<?php echo htmlspecialchars($product['name']); ?>"
                                            class="mobile-product-img">
                                        <div class="mobile-product-info">
                                            <h4><?php echo htmlspecialchars($product['name']); ?></h4>
                                            <span class="mobile-product-category">
                                                <?php echo htmlspecialchars($product['category'] ?? 'Uncategorized'); ?>
                                            </span>
                                            <div class="mobile-product-price">
                                                ₹<?php echo number_format($product['price']); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mobile-product-description">
                                        <?php echo htmlspecialchars(substr($product['description'], 0, 100) . '...'); ?>
                                    </div>
                                    <div class="action-buttons">
                                        <button class="btn-view" onclick="viewProduct(<?php echo $product['id']; ?>)">
                                            <i class="fas fa-eye"></i> View
                                        </button>
                                        <button class="btn-edit" data-bs-toggle="modal" data-bs-target="#editProductModal<?php echo $product['id']; ?>">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <button class="btn-delete" onclick="confirmDelete(<?php echo $product['id']; ?>)">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </div>
                                </div>
                        <?php
                            }
                        } else {
                            echo "<p style='text-align:center; color:#666; width: 100%;'>No products found.</p>";
                        }
                        ?>
                        <script>
                                    let table1 = new DataTable('#myTable');
                                   </script> 
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="productModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <form action="../codes/add_product.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-plus-circle me-2"></i>Add New Product
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="productCategory" class="form-label">
                                    <i class="fas fa-layer-group me-2"></i>Category
                                </label>
                                <select class="form-select" name="category_id" id="addCategoryId" required>
                                    <option value="">Select Category</option>
                                    <?php
                                    $cat_query = mysqli_query($conn, "SELECT * FROM categories ORDER BY cat_name ASC");
                                    while ($cat = mysqli_fetch_assoc($cat_query)) {
                                        echo "<option value='{$cat['id']}'>{$cat['cat_name']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="productName" class="form-label">
                                    <i class="fas fa-box me-2"></i>Product Name
                                </label>
                                <input type="text" class="form-control" name="name" id="addProductName" placeholder="Enter product name" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="productPrice" class="form-label">
                                    <i class="fas fa-rupee-sign me-2"></i>Price (₹)
                                </label>
                                <input type="number" class="form-control" name="price" id="addProductPrice" placeholder="Enter price" min="0" step="0.01" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="productImage" class="form-label">
                                    <i class="fas fa-image me-2"></i>Product Image <span class="text-danger">*</span>
                                </label>
                                <input type="file" class="form-control" name="image" id="addProductImage" accept="image/*" onchange="previewAddImage(event)" required>
                                <small class="text-muted">Max size: 5MB | Formats: JPG, PNG, GIF, WEBP</small>
                            </div>
                        </div>

                        <!-- Image Preview Section (Scrollable) -->
                        <div class="row" id="addImagePreviewContainer" style="display: none;">
                            <div class="col-12 mb-3">
                                <div class="card border-primary">
                                    <div class="card-header bg-primary text-white">
                                        <i class="fas fa-eye me-2"></i>Image Preview
                                    </div>
                                    <div class="card-body text-center" style="max-height: 300px; overflow-y: auto;">
                                        <img id="addImagePreview" src="" alt="Preview" class="img-fluid rounded shadow" style="max-width: 100%; height: auto;">
                                        <button type="button" class="btn btn-sm btn-danger mt-2" onclick="removeAddImage()">
                                            <i class="fas fa-trash me-1"></i>Remove Image
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="productDescription" class="form-label">
                                <i class="fas fa-align-left me-2"></i>Description
                            </label>
                            <textarea class="form-control" name="description" id="addProductDescription" rows="4" placeholder="Enter detailed product description" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="productSpecifications" class="form-label">
                                <i class="fas fa-list-ul me-2"></i>Specifications
                            </label>
                            <textarea class="form-control" name="specification" id="addProductSpec" rows="5" placeholder="Enter specifications (one per line)&#10;Example:&#10;Material: Stainless Steel&#10;Weight: 2kg&#10;Dimensions: 30x20x10 cm&#10;Warranty: 1 Year" required></textarea>
                            <small class="text-muted">Enter each specification on a new line</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Cancel
                        </button>
                        <button type="submit" name="add_product" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Save Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Product Modal -->
    <div class="modal fade" id="viewProductModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-info-circle me-2"></i>Product Details
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-5 col-md-6 mb-3 text-center">
                            <img id="viewProductImage" src="" alt="Product" class="img-fluid rounded shadow-sm" style="max-height: 400px; width: 100%; object-fit: contain;">
                        </div>
                        <div class="col-lg-7 col-md-6">
                            <h3 id="viewProductName" class="mb-3 text-primary"></h3>
                            <div class="mb-3">
                                <strong>Category:</strong>
                                <span id="viewProductCategory" class="category-badge ms-2"></span>
                            </div>
                            <div class="mb-3">
                                <strong>Price:</strong>
                                <span id="viewProductPrice" class="price-badge ms-2"></span>
                            </div>
                            <hr>
                            <h6 class="mb-2 text-primary"><strong><i class="fas fa-align-left me-2"></i>Description:</strong></h6>
                            <p id="viewProductDescription" style="text-align: justify;"></p>
                            <hr>
                            <h6 class="mb-2 text-primary"><strong><i class="fas fa-list-ul me-2"></i>Specifications:</strong></h6>
                            <div id="viewProductSpecs"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Modal -->

    <?php include("../includes/profile.php") ?>
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/script.js"> </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Function to preview image for edit modal
        function previewEditImage(event, productId) {
            const input = event.target;
            const preview = document.getElementById('editImagePreview' + productId);
            const container = document.getElementById('editImagePreviewContainer' + productId);

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    container.style.display = 'block';
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        // Function to remove the selected image for edit modal
        function removeEditImage(productId) {
            const input = document.getElementById('editProductImage' + productId);
            const preview = document.getElementById('editImagePreview' + productId);
            const container = document.getElementById('editImagePreviewContainer' + productId);

            input.value = '';
            preview.src = '';
            container.style.display = 'none';
        }

        // Preview image on add
        function previewAddImage(event) {
            const preview = document.getElementById('addImagePreview');
            const container = document.getElementById('addImagePreviewContainer');
            const file = event.target.files[0];

            if (file) {
                // Validate file size (5MB)
                if (file.size > 5000000) {
                    Swal.fire({
                        icon: 'error',
                        title: 'File Too Large',
                        text: 'Image size should not exceed 5MB!',
                        confirmButtonColor: '#ef4444'
                    });
                    event.target.value = '';
                    container.style.display = 'none';
                    return;
                }

                // Validate file type
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                if (!allowedTypes.includes(file.type)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid File Type',
                        text: 'Only JPG, JPEG, PNG, GIF, and WEBP images are allowed!',
                        confirmButtonColor: '#ef4444'
                    });
                    event.target.value = '';
                    container.style.display = 'none';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    container.style.display = 'block';

                    // Smooth scroll to preview
                    setTimeout(() => {
                        container.scrollIntoView({
                            behavior: 'smooth',
                            block: 'nearest'
                        });
                    }, 100);
                }
                reader.readAsDataURL(file);
            }
        }

        // Remove image preview
        function removeAddImage() {
            const preview = document.getElementById('addImagePreview');
            const container = document.getElementById('addImagePreviewContainer');
            const fileInput = document.getElementById('addProductImage');

            preview.src = '';
            fileInput.value = '';
            container.style.display = 'none';
        }

        // View Product Details
        function viewProduct(id) {
            fetch(`../codes/get_product.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const product = data.product;

                        document.getElementById('viewProductImage').src = `../assets/images/products/${product.image}`;
                        document.getElementById('viewProductName').textContent = product.name;
                        document.getElementById('viewProductCategory').textContent = product.cat_name || 'Uncategorized';
                        document.getElementById('viewProductPrice').textContent = `₹${parseFloat(product.price).toLocaleString()}`;
                        document.getElementById('viewProductDescription').textContent = product.description;

                        // Specifications
                        const specsDiv = document.getElementById('viewProductSpecs');
                        specsDiv.innerHTML = '';
                        const specs = product.specification.split('\n');
                        specs.forEach(spec => {
                            if (spec.trim()) {
                                specsDiv.innerHTML += `<div class="spec-item">${spec}</div>`;
                            }
                        });

                        // Show modal
                        const modal = new bootstrap.Modal(document.getElementById('viewProductModal'));
                        modal.show();
                    } else {
                        alert('Product not found!');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading product details!');
                });
        }

        // Confirm Delete
        function confirmDelete(id) {
            Swal.fire({
                title: "Are you sure you want to delete this product?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it",
                cancelButtonText: "Cancel",
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "../codes/delete_product.php?id=" + id;
                }
            });
        }

        // Reset form when modal closes
        document.getElementById('productModal').addEventListener('hidden.bs.modal', function() {
            document.querySelector('#productModal form').reset();
            removeAddImage();
        });



        // SweetAlert for success/error messages
        <?php if (isset($_SESSION['success'])): ?>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '<?php echo $_SESSION['success']; ?>',
                timer: 3000,
                showConfirmButton: false
            });
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '<?php echo $_SESSION['error']; ?>',
                timer: 3000,
                showConfirmButton: false
            });
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
    </script>

</body>

</html>