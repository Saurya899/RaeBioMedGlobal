<?php
include("../includes/session.php");
include("../config/db.php");

// Get carousel counts
$active_count = 0;
$inactive_count = 0;
$total_count = 0;

$count_query = "SELECT status, COUNT(*) as count FROM carousel GROUP BY status";
$count_result = mysqli_query($conn, $count_query);
while ($row = mysqli_fetch_assoc($count_result)) {
    if ($row['status'] == 'active') {
        $active_count = $row['count'];
    } else if ($row['status'] == 'inactive') {
        $inactive_count = $row['count'];
    }
    $total_count += $row['count'];
}

// Handle actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $carousel_id = intval($_GET['id']);

    if ($_GET['action'] == 'delete') {
        // First get the image path to delete the file
        $select_query = "SELECT image FROM carousel WHERE id = $carousel_id";
        $select_result = mysqli_query($conn, $select_query);
        $carousel = mysqli_fetch_assoc($select_result);
        
        // Delete the image file
        if ($carousel['image'] && file_exists("../assets/images/carousel/" . $carousel['image'])) {
            unlink("../assets/images/carousel/" . $carousel['image']);
        }
        
        $delete_query = "DELETE FROM carousel WHERE id = $carousel_id";
        mysqli_query($conn, $delete_query);
        $_SESSION['success'] = "Carousel item deleted successfully";
        header("Location: carousel.php");
        exit();
    } elseif ($_GET['action'] == 'toggle_status') {
        $status_query = "SELECT status FROM carousel WHERE id = $carousel_id";
        $status_result = mysqli_query($conn, $status_query);
        $carousel = mysqli_fetch_assoc($status_result);
        
        $new_status = ($carousel['status'] == 'active') ? 'inactive' : 'active';
        $update_query = "UPDATE carousel SET status = '$new_status' WHERE id = $carousel_id";
        mysqli_query($conn, $update_query);
        $_SESSION['success'] = "Carousel item status updated";
        header("Location: carousel.php");
        exit();
    }
}

// Handle form submission for adding/editing carousel
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $heading1 = mysqli_real_escape_string($conn, $_POST['heading1']);
    $heading2 = mysqli_real_escape_string($conn, $_POST['heading2']);
    $button_name = mysqli_real_escape_string($conn, $_POST['button_name']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    // Handle image upload
    $image_name = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        
        if (in_array($file_extension, $allowed_extensions)) {
            $image_name = time() . '_' . uniqid() . '.' . $file_extension;
            $upload_path = "../assets/images/carousel/" . $image_name;
            
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                $image_name = '';
                $_SESSION['error'] = "Failed to upload image";
            }
        } else {
            $_SESSION['error'] = "Invalid file type. Only JPG, JPEG, PNG, GIF, and WEBP are allowed.";
        }
    }
    
    if (isset($_POST['carousel_id']) && !empty($_POST['carousel_id'])) {
        // Update existing carousel item
        $carousel_id = intval($_POST['carousel_id']);
        
        if ($image_name) {
            // Delete old image
            $old_image_query = "SELECT image FROM carousel WHERE id = $carousel_id";
            $old_image_result = mysqli_query($conn, $old_image_query);
            $old_carousel = mysqli_fetch_assoc($old_image_result);
            
            if ($old_carousel['image'] && file_exists("../assets/images/carousel/" . $old_carousel['image'])) {
                unlink("../assets/images/carousel/" . $old_carousel['image']);
            }
            
            $update_query = "UPDATE carousel SET heading1='$heading1', heading2='$heading2', button_name='$button_name', image='$image_name', status='$status' WHERE id=$carousel_id";
        } else {
            $update_query = "UPDATE carousel SET heading1='$heading1', heading2='$heading2', button_name='$button_name', status='$status' WHERE id=$carousel_id";
        }
        
        mysqli_query($conn, $update_query);
        $_SESSION['success'] = "Carousel item updated successfully";
    } else {
        // Add new carousel item
        $insert_query = "INSERT INTO carousel (heading1, heading2, button_name, image, status) VALUES ('$heading1', '$heading2', '$button_name', '$image_name', '$status')";
        mysqli_query($conn, $insert_query);
        $_SESSION['success'] = "Carousel item added successfully";
    }
    
    header("Location: carousel.php");
    exit();
}

// Get filter
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

// Build query based on filter
$query = "SELECT * FROM carousel";
if ($filter == 'active') {
    $query .= " WHERE status = 'active'";
} elseif ($filter == 'inactive') {
    $query .= " WHERE status = 'inactive'";
}
$query .= " ORDER BY created_at DESC";

$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RaeBioMedGlobal - Carousel Management</title>
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

        .page-title {
            font-size: 1.875rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        /* Stats Cards */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--card-bg);
            padding: 1.5rem;
            border-radius: 0.75rem;
            border: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .stat-icon.active {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .stat-icon.inactive {
            background: linear-gradient(135deg, #6b7280, #4b5563);
        }

        .stat-icon.total {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
        }

        .stat-info h3 {
            font-size: 1.75rem;
            font-weight: 700;
            margin: 0;
            color: var(--text-primary);
        }

        .stat-info p {
            margin: 0;
            color: var(--text-secondary);
            font-size: 0.875rem;
        }

        /* Table Card */
        .table-card {
            background: var(--card-bg);
            border-radius: 1rem;
            box-shadow: var(--shadow);
            overflow: hidden;
            border: 1px solid var(--border-color);
        }

        .table-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .table-header h3 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .filter-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .filter-btn {
            padding: 0.5rem 1rem;
            border: 1px solid var(--border-color);
            background: var(--bg-color);
            color: var(--text-secondary);
            border-radius: 0.375rem;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .filter-btn:hover,
        .filter-btn.active {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
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

        .status-badge {
            padding: 0.375rem 0.75rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            display: inline-block;
        }

        .status-badge.active {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }

        .status-badge.inactive {
            background: rgba(107, 114, 128, 0.1);
            color: #6b7280;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .btn-view,
        .btn-edit,
        .btn-delete,
        .btn-status {
            padding: 0.5rem 0.75rem;
            border: none;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            text-decoration: none;
        }

        .btn-view {
            background: #06b6d4;
            color: white;
        }

        .btn-view:hover {
            background: #0891b2;
        }

        .btn-edit {
            background:  #3b82f6;
            color: white;
        }

        .btn-edit:hover {
            background:  #2563eb;
        }

        .btn-delete {
            background: #ef4444;
            color: white;
        }

        .btn-delete:hover {
            background: #dc2626;
        }

        .btn-status {
            background: #10b981;
            color: white;
        }

        .btn-status.inactive {
            background: #6b7280;
        }

        .btn-status:hover {
            opacity: 0.9;
        }

        /* Image Preview */
        .carousel-image {
            width: 100px;
            height: 60px;
            object-fit: cover;
            border-radius: 0.375rem;
            border: 1px solid var(--border-color);
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
            border: 1px solid var(--border-color);
            display: none;
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

        /* Mobile Carousel Cards */
        .mobile-carousel-card {
            background: var(--card-bg);
            border-radius: 0.75rem;
            padding: 1.25rem;
            margin-bottom: 1rem;
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow);
        }

        .mobile-carousel-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .mobile-carousel-info h4 {
            font-size: 1.125rem;
            margin-bottom: 0.25rem;
            color: var(--text-primary);
        }

        .mobile-carousel-info p {
            color: var(--text-secondary);
            margin-bottom: 0.25rem;
            font-size: 0.875rem;
        }

        .mobile-carousel-status {
            padding: 0.375rem 0.75rem;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .mobile-carousel-status.active {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }

        .mobile-carousel-status.inactive {
            background: rgba(107, 114, 128, 0.1);
            color: #6b7280;
        }

        .mobile-carousel-image {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            border: 1px solid var(--border-color);
        }

        /* Tablet Carousel Grid - 2 cards per row */
        .tablet-carousel-grid {
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

            .stats-row {
                grid-template-columns: repeat(2, 1fr);
            }

            /* Tablet View - Show 2 cards per row */
            .custom-table {
                display: none;
            }

            .mobile-carousel-cards {
                display: block;
            }

            .tablet-carousel-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 1.25rem;
            }

            .mobile-carousel-card {
                margin-bottom: 0;
                height: 100%;
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

            .stats-row {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }

            .stat-card {
                padding: 1.25rem;
            }

            .custom-table {
                display: none;
            }

            .mobile-carousel-cards {
                display: block;
            }

            /* Mobile View - 1 card per row */
            .tablet-carousel-grid {
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

            .btn-view,
            .btn-edit,
            .btn-delete,
            .btn-status {
                padding: 0.6rem;
                font-size: 0.8rem;
                justify-content: center;
            }

            .filter-buttons {
                width: 100%;
            }

            .filter-btn {
                flex: 1;
                text-align: center;
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

            .stats-row {
                gap: 0.5rem;
            }

            .stat-card {
                padding: 1rem;
            }

            .stat-icon {
                width: 40px;
                height: 40px;
                font-size: 1.25rem;
            }

            .stat-info h3 {
                font-size: 1.5rem;
            }

            .mobile-carousel-header {
                flex-direction: column;
                gap: 0.5rem;
            }

            .mobile-carousel-status {
                align-self: flex-start;
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
            .mobile-carousel-cards {
                display: none;
            }

            .tablet-carousel-grid {
                display: none;
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
                    <h2 class="page-title">Carousel Management</h2>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCarouselModal">
                        <i class="fas fa-plus me-2"></i>Add Carousel Item
                    </button>
                </div>

                <!-- Stats Cards -->
                <div class="stats-row">
                    <div class="stat-card">
                        <div class="stat-icon active">
                            <i class="fas fa-play-circle"></i>
                        </div>
                        <div class="stat-info">
                            <h3 id="activeCount"><?php echo $active_count; ?></h3>
                            <p>Active Items</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon inactive">
                            <i class="fas fa-pause-circle"></i>
                        </div>
                        <div class="stat-info">
                            <h3 id="inactiveCount"><?php echo $inactive_count; ?></h3>
                            <p>Inactive Items</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon total">
                            <i class="fas fa-images"></i>
                        </div>
                        <div class="stat-info">
                            <h3 id="totalCount"><?php echo $total_count; ?></h3>
                            <p>Total Items</p>
                        </div>
                    </div>
                </div>

                <!-- Carousel Table (Desktop) -->
                <div class="table-card">
                    <div class="table-header">
                        <h3><i class="fas fa-images me-2"></i>Carousel Items</h3>
                        <div class="filter-buttons">
                            <a href="?filter=all" class="filter-btn <?php echo $filter == 'all' ? 'active' : ''; ?>">All</a>
                            <a href="?filter=active" class="filter-btn <?php echo $filter == 'active' ? 'active' : ''; ?>">Active</a>
                            <a href="?filter=inactive" class="filter-btn <?php echo $filter == 'inactive' ? 'active' : ''; ?>">Inactive</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="custom-table" id="myTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Heading 1</th>
                                    <th>Heading 2</th>
                                    <th>Button Name</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="carouselTableBody">
                                <?php
                                $counter = 1;
                                if (mysqli_num_rows($result) > 0) {
                                    while ($carousel = mysqli_fetch_assoc($result)) {
                                ?>
                                        <tr>
                                            <td><?php echo $counter++; ?></td>
                                            <td>
                                                <?php if ($carousel['image']): ?>
                                                    <img src="../assets/images/carousel/<?php echo $carousel['image']; ?>" class="carousel-image" alt="Carousel Image">
                                                <?php else: ?>
                                                    <span class="text-muted">No Image</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($carousel['heading1']); ?></td>
                                            <td><?php echo htmlspecialchars($carousel['heading2']); ?></td>
                                            <td><?php echo htmlspecialchars($carousel['button_name']); ?></td>
                                            <td>
                                                <span class="status-badge <?php echo $carousel['status']; ?>">
                                                    <?php echo strtoupper($carousel['status']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('M j, Y', strtotime($carousel['created_at'])); ?></td>
                                            <td>
                                                <div class="action-buttons">
                                                    <button class="btn-edit" onclick="editCarousel(<?php echo $carousel['id']; ?>)">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </button>
                                                    <button class="btn-status <?php echo $carousel['status']; ?>" onclick="toggleStatus(<?php echo $carousel['id']; ?>)">
                                                        <?php if ($carousel['status'] == 'active'): ?>
                                                            <i class="fas fa-pause"></i> Deactivate
                                                        <?php else: ?>
                                                            <i class="fas fa-play"></i> Activate
                                                        <?php endif; ?>
                                                    </button>
                                                    <button class="btn-delete" onclick="confirmDelete(<?php echo $carousel['id']; ?>)">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            No carousel items found.
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                                <script>
                                    let table = new DataTable('#myTable');
                                </script>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tablet & Mobile Carousel Cards -->
                <div class="mobile-carousel-cards">
                    <div class="tablet-carousel-grid" id="tabletCarouselGrid">
                        <?php
                        mysqli_data_seek($result, 0); // Reset result pointer
                        if (mysqli_num_rows($result) > 0) {
                            while ($carousel = mysqli_fetch_assoc($result)) {
                        ?>
                                <div class="mobile-carousel-card">
                                    <div class="mobile-carousel-header">
                                        <div class="mobile-carousel-info">
                                            <h4><?php echo htmlspecialchars($carousel['heading1']); ?></h4>
                                            <p><?php echo htmlspecialchars($carousel['heading2']); ?></p>
                                            <p><?php echo date('M j, Y', strtotime($carousel['created_at'])); ?></p>
                                        </div>
                                        <span class="mobile-carousel-status <?php echo $carousel['status']; ?>">
                                            <?php echo strtoupper($carousel['status']); ?>
                                        </span>
                                    </div>
                                    <?php if ($carousel['image']): ?>
                                        <img src="../assets/images/carousel/<?php echo $carousel['image']; ?>" class="mobile-carousel-image" alt="Carousel Image">
                                    <?php else: ?>
                                        <div class="mobile-carousel-image bg-light d-flex align-items-center justify-content-center">
                                            <span class="text-muted">No Image</span>
                                        </div>
                                    <?php endif; ?>
                                    <div class="mb-2">
                                        <strong>Button Text:</strong> <?php echo htmlspecialchars($carousel['button_name']); ?>
                                    </div>
                                    <div class="action-buttons">
                                        <button class="btn-edit" onclick="editCarousel(<?php echo $carousel['id']; ?>)">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <button class="btn-status <?php echo $carousel['status']; ?>" onclick="toggleStatus(<?php echo $carousel['id']; ?>)">
                                            <?php if ($carousel['status'] == 'active'): ?>
                                                <i class="fas fa-pause"></i> Deactivate
                                            <?php else: ?>
                                                <i class="fas fa-play"></i> Activate
                                            <?php endif; ?>
                                        </button>
                                        <button class="btn-delete" onclick="confirmDelete(<?php echo $carousel['id']; ?>)">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </div>
                                </div>
                        <?php
                            }
                        } else {
                            echo '<div class="col-12 text-center py-4 text-muted">No carousel items found.</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Add/Edit Carousel Modal -->
    <div class="modal fade" id="addCarouselModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">
                        <i class="fas fa-plus me-2"></i>Add Carousel Item
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="carouselForm" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="carousel_id" id="carouselId">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="heading1" class="form-label">Heading 1</label>
                                <input type="text" class="form-control" id="heading1" name="heading1" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="heading2" class="form-label">Heading 2</label>
                                <input type="text" class="form-control" id="heading2" name="heading2" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="button_name" class="form-label">Button Name</label>
                            <input type="text" class="form-control" id="button_name" name="button_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <small class="text-muted">Recommended size: 1920x1080px. Max file size: 2MB.</small>
                            <img id="imagePreview" class="image-preview" src="" alt="Image Preview">
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Carousel Item</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Profile Modal -->
    <?php include("../includes/profile.php") ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/script.js"></script>
    <script>
        // Image preview
        document.getElementById('image').addEventListener('change', function(e) {
            const preview = document.getElementById('imagePreview');
            const file = e.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
                
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
            }
        });

        // Confirm Delete
        function confirmDelete(id) {
            Swal.fire({
                title: "Are you sure you want to delete this carousel item?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it",
                cancelButtonText: "Cancel",
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "carousel.php?action=delete&id=" + id;
                }
            });
        }

        // Toggle Status
        function toggleStatus(id) {
            Swal.fire({
                title: "Are you sure you want to change the status?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Yes, change it",
                cancelButtonText: "Cancel",
                confirmButtonColor: "#10b981",
                cancelButtonColor: "#6b7280"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "carousel.php?action=toggle_status&id=" + id;
                }
            });
        }

        // Edit Carousel
        function editCarousel(id) {
            // Fetch carousel data via AJAX
            fetch('../codes/get_carousel.php?id=' + id)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('carouselId').value = data.carousel.id;
                        document.getElementById('heading1').value = data.carousel.heading1;
                        document.getElementById('heading2').value = data.carousel.heading2;
                        document.getElementById('button_name').value = data.carousel.button_name;
                        document.getElementById('status').value = data.carousel.status;
                        
                        // Show image preview if exists
                        if (data.carousel.image) {
                            document.getElementById('imagePreview').src = '../assets/images/carousel/' + data.carousel.image;
                            document.getElementById('imagePreview').style.display = 'block';
                        }
                        
                        // Update modal title
                        document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit me-2"></i>Edit Carousel Item';
                        
                        // Show modal
                        const modal = new bootstrap.Modal(document.getElementById('addCarouselModal'));
                        modal.show();
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to load carousel data'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load carousel data'
                    });
                });
        }

        // Reset form when modal is closed
        document.getElementById('addCarouselModal').addEventListener('hidden.bs.modal', function() {
            document.getElementById('carouselForm').reset();
            document.getElementById('carouselId').value = '';
            document.getElementById('imagePreview').style.display = 'none';
            document.getElementById('modalTitle').innerHTML = '<i class="fas fa-plus me-2"></i>Add Carousel Item';
        });

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