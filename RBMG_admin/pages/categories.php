<?php
include("../includes/session.php");
include("../config/db.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories | RaeBioMedGlobal - Categories</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- Favicons -->
    <link rel="icon" type="image/png" href="../../assets/favicon/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="../../assets/favicon/favicon.svg" />
    <link rel="shortcut icon" href="../../assets/favicon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="../../assets/favicon/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="MyWebSite" />
    <link rel="manifest" href="../../assets/favicon/site.webmanifest" />
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

        .category-img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 0.5rem;
        }

        .status-badge {
            padding: 0.375rem 0.75rem;
            border-radius: 0.375rem;
            font-weight: 600;
            display: inline-block;
        }

        .status-active {
            background: linear-gradient(135deg, var(--success-color), #059669);
            color: white;
        }

        .status-inactive {
            background: #827e7eff;
            color: white;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .btn-edit,
        .btn-delete,
        .btn-enable,
        .btn-disable {
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

        .btn-enable {
            background: #10b981;
            color: white;
        }

        .btn-enable:hover {
            background: #059669;
        }

        .btn-disable {
            background: #827e7eff;
            color: white;
        }

        .btn-disable:hover {
            background: #565050ff;
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

        /* Mobile Category Cards */
        .mobile-category-card {
            background: var(--card-bg);
            border-radius: 0.75rem;
            padding: 1.25rem;
            margin-bottom: 1rem;
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow);
            height: 100%;
        }

        .mobile-category-header {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .mobile-category-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 0.5rem;
            flex-shrink: 0;
        }

        .mobile-category-info {
            flex: 1;
        }

        .mobile-category-info h4 {
            font-size: 1.125rem;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }

        .mobile-category-description {
            color: var(--text-secondary);
            font-size: 0.875rem;
            line-height: 1.4;
            margin-bottom: 1rem;
        }

        /* Tablet Category Grid - 2 cards per row */
        .tablet-category-grid {
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

            .mobile-categories-cards {
                display: block;
            }

            .tablet-category-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 1.25rem;
            }

            .mobile-category-card {
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

            /* Mobile View - 1 card per row */
            .tablet-category-grid {
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
            .btn-enable,
            .btn-disable {
                padding: 0.6rem;
                font-size: 0.8rem;
                justify-content: center;
            }

            .mobile-category-header {
                flex-direction: column;
                text-align: center;
            }

            .mobile-category-img {
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

            .mobile-category-img {
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
            .mobile-categories-cards {
                display: none;
            }

            .tablet-category-grid {
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
                    <h2 class="page-title">Manage Categories</h2>
                    <button class="btn-add" data-bs-toggle="modal" data-bs-target="#categoryModal" onclick="resetForm()">
                        <i class="fas fa-plus"></i>
                        Add Category
                    </button>
                </div>

                <!-- Categories Table (Desktop) -->
                <div class="table-card">
                    <div class="table-responsive">
                        <table class="custom-table" id="myTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Category Name</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="categoriesTableBody">
                                <?php
                                $query = mysqli_query($conn, "SELECT * FROM categories");
                                $i = 1;
                                while ($fetch = mysqli_fetch_assoc($query)) {
                                ?>
                                    <tr>
                                        <td><?= $i++; ?></td>
                                        <td>
                                            <img src="../assets/images/categories/<?= $fetch['image']; ?>" width="60" height="60" class="rounded category-img">
                                        </td>
                                        <td><strong><?= $fetch['cat_name']; ?></strong></td>
                                        <td><?= substr($fetch['description'], 0, 50) . '...'; ?></td>
                                        <!-- <td>
                                            <span class="status-badge <?= $fetch['status'] == 1 ? 'status-active' : 'status-inactive'; ?>">
                                                <?= $fetch['status'] == 1 ? 'Active' : 'Inactive'; ?>
                                            </span>
                                        </td> -->
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn-edit" data-bs-toggle="modal" data-bs-target="#editCategoryModal<?= $fetch['id']; ?>">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                                <button class="btn-delete" onclick="confirmDelete(<?= $fetch['id']; ?>)">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                                <?php if ($fetch['status'] == 1): ?>
                                                    <button class="btn-disable" onclick="toggleStatus(<?= $fetch['id']; ?>, 0)">
                                                        <i class="fas fa-times-circle"></i> Disable
                                                    </button>
                                                <?php else: ?>
                                                    <button class="btn-enable" onclick="toggleStatus(<?= $fetch['id']; ?>, 1)">
                                                        <i class="fas fa-check-circle"></i> Enable
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Edit Category Modal -->
                                    <div class="modal fade" id="editCategoryModal<?= $fetch['id']; ?>" tabindex="-1">
                                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <form action="../codes/update_category.php" method="POST" enctype="multipart/form-data">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">
                                                            <i class="fas fa-edit me-2"></i>Edit Category
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                                                        <input type="hidden" name="cat_id" value="<?= $fetch['id']; ?>">
                                                        <input type="hidden" name="old_image" value="<?= $fetch['image']; ?>">

                                                        <div class="row">
                                                            <div class="col-md-12 mb-3">
                                                                <label for="editCategoryName<?= $fetch['id']; ?>" class="form-label">
                                                                    <i class="fas fa-tag me-2"></i>Category Name
                                                                </label>
                                                                <input type="text" class="form-control" name="cat_name" id="editCategoryName<?= $fetch['id']; ?>" value="<?= $fetch['cat_name']; ?>" placeholder="Enter category name" required>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-md-6 mb-3">
                                                                <label for="editCategoryStatus<?= $fetch['id']; ?>" class="form-label">
                                                                    <i class="fas fa-toggle-on me-2"></i>Status
                                                                </label>
                                                                <select class="form-select" name="status" id="editCategoryStatus<?= $fetch['id']; ?>" required>
                                                                    <option value="1" <?= $fetch['status'] == 1 ? 'selected' : ''; ?>>Active</option>
                                                                    <option value="0" <?= $fetch['status'] == 0 ? 'selected' : ''; ?>>Inactive</option>
                                                                </select>
                                                            </div>

                                                            <div class="col-md-6 mb-3">
                                                                <label for="editCategoryImage<?= $fetch['id']; ?>" class="form-label">
                                                                    <i class="fas fa-image me-2"></i>Category Image
                                                                </label>
                                                                <input type="file" class="form-control" name="cat_image" id="editCategoryImage<?= $fetch['id']; ?>" accept="image/*" onchange="previewEditImage(event, <?= $fetch['id']; ?>)">
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
                                                                        <img src="../assets/images/categories/<?= $fetch['image']; ?>" alt="Current Image" class="img-fluid rounded shadow" style="max-width: 200px; height: 100px;">
                                                                        <p class="mt-2 mb-0 text-muted">Current category image</p>
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
                                                            <label for="editCategoryDescription<?= $fetch['id']; ?>" class="form-label">
                                                                <i class="fas fa-align-left me-2"></i>Description
                                                            </label>
                                                            <textarea class="form-control" name="cat_des" id="editCategoryDescription<?= $fetch['id']; ?>" rows="4" placeholder="Enter detailed category description" required><?= $fetch['description']; ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                            <i class="fas fa-times me-2"></i>Cancel
                                                        </button>
                                                        <button type="submit" name="cat_update" class="btn btn-primary">
                                                            <i class="fas fa-save me-2"></i>Update Category
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

                <!-- Tablet & Mobile Category Cards -->
                <div class="mobile-categories-cards">
                    <div class="tablet-category-grid" id="tabletCategoryGrid">
                        <?php
                        $query = "SELECT * FROM categories ORDER BY id DESC";
                        $result = mysqli_query($conn, $query);

                        if (mysqli_num_rows($result) > 0) {
                            while ($category = mysqli_fetch_assoc($result)) {
                        ?>
                                <div class="mobile-category-card">
                                    <div class="mobile-category-header">
                                        <img src="../assets/images/categories/<?php echo htmlspecialchars($category['image']); ?>"
                                            alt="<?php echo htmlspecialchars($category['cat_name']); ?>"
                                            class="mobile-category-img">
                                        <div class="mobile-category-info">
                                            <h4><?php echo htmlspecialchars($category['cat_name']); ?></h4>
                                            <span class="status-badge <?php echo $category['status'] == 1 ? 'status-active' : 'status-inactive'; ?>">
                                                <?php echo $category['status'] == 1 ? 'Active' : 'Inactive'; ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="mobile-category-description">
                                        <?php echo htmlspecialchars(substr($category['description'], 0, 100) . '...'); ?>
                                    </div>
                                    <div class="action-buttons">
                                        <button class="btn-edit" data-bs-toggle="modal" data-bs-target="#editCategoryModal<?php echo $category['id']; ?>">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <button class="btn-delete" onclick="confirmDelete(<?php echo $category['id']; ?>)">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                        <?php if ($category['status'] == 1): ?>
                                            <button class="btn-disable" onclick="toggleStatus(<?php echo $category['id']; ?>, 0)">
                                                <i class="fas fa-times-circle"></i> Disable
                                            </button>
                                        <?php else: ?>
                                            <button class="btn-enable" onclick="toggleStatus(<?php echo $category['id']; ?>, 1)">
                                                <i class="fas fa-check-circle"></i> Enable
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                        <?php
                            }
                        } else {
                            echo "<p style='text-align:center; color:#666; width: 100%;'>No categories found.</p>";
                        }
                        ?>
                        
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Add Category Modal -->
    <div class="modal fade" id="categoryModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <form action="../codes/add_category.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-plus-circle me-2"></i>Add New Category
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="categoryName" class="form-label">
                                    <i class="fas fa-tag me-2"></i>Category Name
                                </label>
                                <input type="text" class="form-control" name="cat_name" id="categoryName" placeholder="Enter category name" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="categoryStatus" class="form-label">
                                    <i class="fas fa-toggle-on me-2"></i>Status
                                </label>
                                <select class="form-select" name="status" id="categoryStatus" required>
                                    <option value="1" selected>Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="categoryImage" class="form-label">
                                    <i class="fas fa-image me-2"></i>Category Image <span class="text-danger">*</span>
                                </label>
                                <input type="file" class="form-control" name="cat_image" id="categoryImage" accept="image/*" onchange="previewAddImage(event)" required>
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
                            <label for="categoryDescription" class="form-label">
                                <i class="fas fa-align-left me-2"></i>Description
                            </label>
                            <textarea class="form-control" name="cat_des" id="categoryDescription" rows="4" placeholder="Enter detailed category description" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Cancel
                        </button>
                        <button type="submit" name="cat_save" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Save Category
                        </button>
                    </div>
                </form>
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
        function previewEditImage(event, categoryId) {
            const input = event.target;
            const preview = document.getElementById('editImagePreview' + categoryId);
            const container = document.getElementById('editImagePreviewContainer' + categoryId);

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
        function removeEditImage(categoryId) {
            const input = document.getElementById('editCategoryImage' + categoryId);
            const preview = document.getElementById('editImagePreview' + categoryId);
            const container = document.getElementById('editImagePreviewContainer' + categoryId);

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
            const fileInput = document.getElementById('categoryImage');

            preview.src = '';
            fileInput.value = '';
            container.style.display = 'none';
        }

        // Reset form when modal closes
        function resetForm() {
            document.querySelector('#categoryModal form').reset();
            removeAddImage();
        }

        // Toggle Status Function
        function toggleStatus(id, newStatus) {
            const actionText = newStatus == 1 ? "enable" : "disable";

            Swal.fire({
                title: `Are you sure you want to ${actionText} this category?`,
                text: "You can change this anytime!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: `Yes, ${actionText} it!`,
                cancelButtonText: "Cancel",
                confirmButtonColor: newStatus == 1 ? "#10b981" : "#ef4444",
                cancelButtonColor: "#6c757d"
            }).then((result) => {
                if (result.isConfirmed) {
                    // AJAX call to update status
                    const formData = new FormData();
                    formData.append('id', id);
                    formData.append('status', newStatus);

                    fetch('../codes/category_status.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: data.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    // Reload page to reflect changes
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: data.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            }
                        })
                        .catch(err => {
                            console.error('Error:', err);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Something went wrong!',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        });
                }
            });
        }

        // Confirm Delete
        function confirmDelete(id) {
            Swal.fire({
                title: "Are you sure you want to delete this category?",
                text: "This action cannot be undone!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "Cancel",
                confirmButtonColor: "#e74c3c",
                cancelButtonColor: "#6c757d"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "../codes/delete_category.php?id=" + id;
                }
            });
        }

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