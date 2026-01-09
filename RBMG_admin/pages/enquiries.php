<?php
include("../includes/session.php");
include("../config/db.php");

// Get enquiry counts
$unread_count = 0;
$read_count = 0;
$total_count = 0;

$count_query = "SELECT status, COUNT(*) as count FROM enquiries GROUP BY status";
$count_result = mysqli_query($conn, $count_query);
while ($row = mysqli_fetch_assoc($count_result)) {
    if ($row['status'] == 'unread') {
        $unread_count = $row['count'];
    } else if ($row['status'] == 'read') {
        $read_count = $row['count'];
    }
    $total_count += $row['count'];
}

// Handle actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $enquiry_id = intval($_GET['id']);

    if ($_GET['action'] == 'view') {
        // Mark as read when viewing
        $update_query = "UPDATE enquiries SET status = 'read' WHERE id = $enquiry_id";
        mysqli_query($conn, $update_query);
    } elseif ($_GET['action'] == 'delete') {
        $delete_query = "DELETE FROM enquiries WHERE id = $enquiry_id";
        mysqli_query($conn, $delete_query);
        $_SESSION['success'] = "Enquiry deleted successfully";
        header("Location: enquiries.php");
        exit();
    }
}

// Get filter
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

// Build query based on filter
$query = "SELECT * FROM enquiries";
if ($filter == 'unread') {
    $query .= " WHERE status = 'unread'";
} elseif ($filter == 'read') {
    $query .= " WHERE status = 'read'";
}
$query .= " ORDER BY created_at DESC";

$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RaeBioMedGlobal - Enquiries</title>
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

        .stat-icon.unread {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        .stat-icon.read {
            background: linear-gradient(135deg, #10b981, #059669);
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

        .custom-table tbody tr.unread {
            background: rgba(37, 99, 235, 0.05);
        }

        .status-badge {
            padding: 0.375rem 0.75rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            display: inline-block;
        }

        .status-badge.unread {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        .status-badge.read {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .btn-view,
        .btn-contact,
        .btn-delete {
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

        .btn-contact {
            background: #10b981;
            color: white;
        }

        .btn-contact:hover {
            background: #059669;
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

        .profile-img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #0d6efd;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
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

        .enquiry-details {
            background: var(--bg-color);
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
        }

        .enquiry-details .detail-row {
            display: flex;
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--border-color);
        }

        .enquiry-details .detail-row:last-child {
            border-bottom: none;
        }

        .enquiry-details .detail-label {
            font-weight: 600;
            width: 120px;
            color: var(--text-primary);
        }

        .enquiry-details .detail-value {
            flex: 1;
            color: var(--text-secondary);
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

        /* Mobile Enquiry Cards */
        .mobile-enquiry-card {
            background: var(--card-bg);
            border-radius: 0.75rem;
            padding: 1.25rem;
            margin-bottom: 1rem;
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow);
        }

        .mobile-enquiry-card.unread {
            background: rgba(37, 99, 235, 0.05);
        }

        .mobile-enquiry-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .mobile-enquiry-info h4 {
            font-size: 1.125rem;
            margin-bottom: 0.25rem;
            color: var(--text-primary);
        }

        .mobile-enquiry-info p {
            color: var(--text-secondary);
            margin-bottom: 0.25rem;
            font-size: 0.875rem;
        }

        .mobile-enquiry-status {
            padding: 0.375rem 0.75rem;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .mobile-enquiry-status.unread {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        .mobile-enquiry-status.read {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }

        .mobile-enquiry-subject {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .mobile-enquiry-preview {
            color: var(--text-secondary);
            font-size: 0.875rem;
            margin-bottom: 1rem;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Tablet Enquiry Grid - 2 cards per row */
        .tablet-enquiry-grid {
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

            .mobile-enquiry-cards {
                display: block;
            }

            .tablet-enquiry-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 1.25rem;
            }

            .mobile-enquiry-card {
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

            .mobile-enquiry-cards {
                display: block;
            }

            /* Mobile View - 1 card per row */
            .tablet-enquiry-grid {
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
            .btn-contact,
            .btn-delete {
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

            .enquiry-details .detail-row {
                flex-direction: column;
                padding: 0.75rem 0;
            }

            .enquiry-details .detail-label {
                width: 100%;
                margin-bottom: 0.25rem;
            }

            .enquiry-details .detail-value {
                width: 100%;
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

            .mobile-enquiry-header {
                flex-direction: column;
                gap: 0.5rem;
            }

            .mobile-enquiry-status {
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
            .mobile-enquiry-cards {
                display: none;
            }

            .tablet-enquiry-grid {
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
                    <h2 class="page-title">Customer Enquiries</h2>
                </div>

                <!-- Stats Cards -->
                <div class="stats-row">
                    <div class="stat-card">
                        <div class="stat-icon unread">
                            <i class="fas fa-question-circle"></i>
                        </div>
                        <div class="stat-info">
                            <h3 id="unreadCount"><?php echo $unread_count; ?></h3>
                            <p>Unread Enquiries</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon read">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-info">
                            <h3 id="readCount"><?php echo $read_count; ?></h3>
                            <p>Read Enquiries</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon total">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <div class="stat-info">
                            <h3 id="totalCount"><?php echo $total_count; ?></h3>
                            <p>Total Enquiries</p>
                        </div>
                    </div>
                </div>

                <!-- Enquiries Table (Desktop) -->
                <div class="table-card">
                    <div class="table-header">
                        <h3><i class="fas fa-clipboard-list me-2"></i>All Enquiries</h3>
                        <div class="filter-buttons">
                            <a href="?filter=all" class="filter-btn <?php echo $filter == 'all' ? 'active' : ''; ?>">All</a>
                            <a href="?filter=unread" class="filter-btn <?php echo $filter == 'unread' ? 'active' : ''; ?>">Unread</a>
                            <a href="?filter=read" class="filter-btn <?php echo $filter == 'read' ? 'active' : ''; ?>">Read</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="custom-table" id="myTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                  
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="enquiriesTableBody">
                                <?php
                                $counter = 1;
                                if (mysqli_num_rows($result) > 0) {
                                    while ($enquiry = mysqli_fetch_assoc($result)) {
                                        $row_class = $enquiry['status'] == 'unread' ? 'unread' : '';
                                ?>
                                        <tr class="<?php echo $row_class; ?>">
                                            <td><?php echo $counter++; ?></td>
                                            <td><strong><?php echo htmlspecialchars($enquiry['name']); ?></strong></td>
                                            <td><?php echo htmlspecialchars($enquiry['email']); ?></td>
                                            <td><?php echo htmlspecialchars($enquiry['phone']); ?></td>
                                           
                                            <td><?php echo date('M j, Y', strtotime($enquiry['created_at'])); ?></td>
                                            <td>
                                                <span class="status-badge <?php echo $enquiry['status']; ?>">
                                                    <?php echo strtoupper($enquiry['status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="action-buttons">
                                                    <a href="?action=view&id=<?php echo $enquiry['id']; ?>" class="btn-view">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                    <a href="mailto:<?php echo $enquiry['email']; ?>?subject=Re: Your Enquiry About <?php echo urlencode($enquiry['subject']); ?>" class="btn-contact">
                                                        <i class="fas fa-envelope"></i> Contact
                                                    </a>
                                                    <button class="btn-delete" onclick="confirmDelete(<?php echo $enquiry['id']; ?>)">
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
                                        <td colspan="8" class="text-center py-4 ">
                                            No enquiries found.
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

                <!-- Tablet & Mobile Enquiry Cards -->
                <div class="mobile-enquiry-cards">
                    <div class="tablet-enquiry-grid" id="tabletEnquiryGrid">
                        <?php
                        mysqli_data_seek($result, 0); // Reset result pointer
                        if (mysqli_num_rows($result) > 0) {
                            while ($enquiry = mysqli_fetch_assoc($result)) {
                                $card_class = $enquiry['status'] == 'unread' ? 'unread' : '';
                        ?>
                                <div class="mobile-enquiry-card <?php echo $card_class; ?>">
                                    <div class="mobile-enquiry-header">
                                        <div class="mobile-enquiry-info">
                                            <h4><?php echo htmlspecialchars($enquiry['name']); ?></h4>
                                            <p><?php echo htmlspecialchars($enquiry['email']); ?></p>
                                            <p><?php echo htmlspecialchars($enquiry['phone']); ?></p>
                                            <p><?php echo date('M j, Y', strtotime($enquiry['created_at'])); ?></p>
                                        </div>
                                        <span class="mobile-enquiry-status <?php echo $enquiry['status']; ?>">
                                            <?php echo strtoupper($enquiry['status']); ?>
                                        </span>
                                    </div>
                                    <div class="mobile-enquiry-subject">Product/Service: <?php echo htmlspecialchars($enquiry['subject']); ?></div>
                                    <div class="mobile-enquiry-preview"><?php echo substr(htmlspecialchars($enquiry['message']), 0, 120); ?>...</div>
                                    <div class="action-buttons">
                                        <a href="?action=view&id=<?php echo $enquiry['id']; ?>" class="btn-view">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a href="mailto:<?php echo $enquiry['email']; ?>?subject=Re: Your Enquiry About <?php echo urlencode($enquiry['subject']); ?>" class="btn-contact">
                                            <i class="fas fa-envelope"></i> Contact
                                        </a>
                                        <button class="btn-delete" onclick="confirmDelete(<?php echo $enquiry['id']; ?>)">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </div>
                                </div>
                        <?php
                            }
                        } else {
                            echo '<div class="col-12 text-center py-4 text-light">No enquiries found.</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- View Enquiry Modal -->
    <?php
    if (isset($_GET['action']) && $_GET['action'] == 'view' && isset($_GET['id'])) {
        $enquiry_id = intval($_GET['id']);
        $enquiry_query = "SELECT * FROM enquiries WHERE id = $enquiry_id";
        $enquiry_result = mysqli_query($conn, $enquiry_query);
        $enquiry_data = mysqli_fetch_assoc($enquiry_result);

        if ($enquiry_data) {
    ?>
            <div class="modal fade show" id="viewEnquiryModal" tabindex="-1" style="display: block; padding-right: 17px;">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-clipboard-list me-2"></i>Enquiry Details
                            </h5>
                            <a href="enquiries.php" class="btn-close"></a>
                        </div>
                        <div class="modal-body">
                            <div class="enquiry-details">
                                <div class="detail-row">
                                    <div class="detail-label">Name:</div>
                                    <div class="detail-value" id="viewEnquiryName"><?php echo htmlspecialchars($enquiry_data['name']); ?></div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Email:</div>
                                    <div class="detail-value" id="viewEnquiryEmail"><?php echo htmlspecialchars($enquiry_data['email']); ?></div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Phone:</div>
                                    <div class="detail-value" id="viewEnquiryPhone"><?php echo htmlspecialchars($enquiry_data['phone']); ?></div>
                                </div>
                                <div class="detail-row">
                                    <div class="detail-label">Subject:</div>
                                    <div class="detail-value" id="viewEnquiryPhone"><?php echo htmlspecialchars($enquiry_data['subject']); ?></div>
                                </div>
                               
                                <div class="detail-row">
                                    <div class="detail-label">Date:</div>
                                    <div class="detail-value" id="viewEnquiryDate"><?php echo date('F j, Y g:i A', strtotime($enquiry_data['created_at'])); ?></div>
                                </div>
                            </div>
                            <div>
                                <label class="form-label">Message:</label>
                                <div class="form-control" id="viewEnquiryContent" style="min-height: 150px; white-space: pre-wrap;"><?php echo htmlspecialchars($enquiry_data['message']); ?></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <a href="enquiries.php" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Close
                            </a>
                            <a href="mailto:<?php echo $enquiry_data['email']; ?>?subject=Re: Your Enquiry About <?php echo urlencode($enquiry_data['subject']); ?>" class="btn btn-success">
                                <i class="fas fa-envelope me-2"></i>Contact
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-backdrop fade show"></div>
    <?php
        }
    }
    ?>

    <!-- Profile Modal -->
    <?php include("../includes/profile.php") ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/script.js"></script>
    <script>
        // Confirm Delete
        function confirmDelete(id) {
            Swal.fire({
                title: "Are you sure you want to delete this enquiry?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it",
                cancelButtonText: "Cancel",
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "enquiries.php?action=delete&id=" + id;
                }
            });
        }

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