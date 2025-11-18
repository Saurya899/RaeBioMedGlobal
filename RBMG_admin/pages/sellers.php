<?php
include("../includes/session.php");
include("../config/db.php");

// Get all sellers
$query = "SELECT * FROM sellers ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RaeBioMedGlobal - Sellers</title>
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
            overflow-y: scroll;
        }

        body.modal-open {
            overflow: hidden;
            padding-right: 0 !important;
        }

        .dashboard-container {
            display: flex;
            min-height: 100vh;
        }

        .profile-img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #0d6efd;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
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

        .seller-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid var(--border-color);
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .btn-view,
        .btn-edit,
        .btn-delete {
            padding: 0.4rem 0.6rem;
            border: none;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            text-decoration: none;
            white-space: nowrap;
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

        .seller-details {
            background: var(--bg-color);
            padding: 1.5rem;
            border-radius: 0.75rem;
            margin-bottom: 1rem;
        }

        .detail-section {
            margin-bottom: 1.5rem;
        }

        .detail-section:last-child {
            margin-bottom: 0;
        }

        .detail-section h6 {
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 0.75rem;
            border-bottom: 2px solid var(--border-color);
            padding-bottom: 0.5rem;
        }

        .detail-row {
            display: flex;
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--border-color);
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            width: 150px;
            color: var(--text-primary);
        }

        .detail-value {
            flex: 1;
            color: var(--text-secondary);
        }

        .seller-profile-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 0.75rem;
            border: 3px solid var(--border-color);
            margin-bottom: 1rem;
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

        /* Mobile Cards for Sellers */
        .mobile-seller-cards {
            display: none;
        }

        .tablet-seller-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.25rem;
        }

        .mobile-seller-card {
            background: var(--card-bg);
            border-radius: 0.75rem;
            padding: 1.25rem;
            margin-bottom: 1rem;
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow);
            height: 100%;
        }

        .mobile-seller-header {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .mobile-seller-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid var(--border-color);
            flex-shrink: 0;
        }

        .mobile-seller-info {
            flex: 1;
        }

        .mobile-seller-info h4 {
            font-size: 1.125rem;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }

        .mobile-seller-info p {
            color: var(--text-secondary);
            margin-bottom: 0.25rem;
            font-size: 0.875rem;
        }

        .mobile-seller-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .mobile-detail-item {
            display: flex;
            flex-direction: column;
        }

        .mobile-detail-label {
            font-size: 0.75rem;
            color: var(--text-secondary);
            margin-bottom: 0.25rem;
        }

        .mobile-detail-value {
            font-size: 0.875rem;
            color: var(--text-primary);
            font-weight: 500;
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

            .seller-profile-img {
                width: 120px;
                height: 120px;
            }

            .detail-label {
                width: 130px;
            }

            /* Tablet View - Show cards and hide table */
            .custom-table {
                display: none;
            }

            .mobile-seller-cards {
                display: block;
            }

            .tablet-seller-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 1.25rem;
            }

            .mobile-seller-card {
                margin-bottom: 0;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
                z-index: 1100; /* Higher than header */
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
            .tablet-seller-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn-view,
            .btn-edit,
            .btn-delete {
                padding: 0.6rem;
                font-size: 0.8rem;
                justify-content: center;
            }

            .detail-label {
                width: 120px;
            }

            .seller-profile-img {
                width: 100px;
                height: 100px;
            }

            .detail-row {
                flex-direction: column;
                padding: 0.75rem 0;
            }

            .detail-label {
                width: 100%;
                margin-bottom: 0.25rem;
            }

            .detail-value {
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

            .mobile-seller-details {
                grid-template-columns: 1fr;
            }

            .mobile-seller-header {
                flex-direction: column;
                text-align: center;
            }

            .seller-profile-img {
                width: 80px;
                height: 80px;
            }

            .detail-label {
                width: 100%;
            }

            .seller-details {
                padding: 1rem;
            }

            .modal-dialog {
                margin: 0.25rem;
            }

            .modal-header,
            .modal-body,
            .modal-footer {
                padding: 0.75rem;
            }

            .page-header {
                flex-direction: column;
                align-items: stretch;
            }

            .action-buttons {
                flex-direction: column;
                gap: 0.25rem;
            }

            .btn-view,
            .btn-edit,
            .btn-delete {
                width: 100%;
                justify-content: center;
                font-size: 0.7rem;
                padding: 0.35rem 0.5rem;
            }

            .seller-img {
                width: 30px;
                height: 30px;
            }
        }

        @media (min-width: 993px) {
            .mobile-seller-cards {
                display: none;
            }

            .tablet-seller-grid {
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

        /* Responsive modal adjustments */
        @media (max-width: 768px) {
            .modal-body {
                max-height: 60vh !important;
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
                    <h2 class="page-title">Manage Sellers</h2>
                </div>

                <!-- Sellers Table (Desktop) -->
                <div class="table-card">
                    <div class="table-responsive">
                        <table class="custom-table" id="sellersTable">
                            <thead>
                                <tr>
                                    <th style="width: 5%">#</th>
                                    <th style="width: 10%">Image</th>
                                    <th style="width: 20%">Name</th>
                                    <th style="width: 20%">Email</th>
                                    <th style="width: 10%">Phone</th>
                                    <th style="width: 15%">City</th>
                                    <th style="width: 10%">State</th>
                                    <th style="width: 20%">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="sellersTableBody">
                                <?php
                                $counter = 1;
                                if (mysqli_num_rows($result) > 0) {
                                    while ($seller = mysqli_fetch_assoc($result)) {
                                ?>
                                        <tr>
                                            <td><?php echo $counter++; ?></td>
                                            <td>
                                                <img src="../assets/images/sellers/<?php echo htmlspecialchars($seller['image']); ?>"
                                                    alt="<?php echo htmlspecialchars($seller['first_name'] . ' ' . $seller['last_name']); ?>"
                                                    class="seller-img">
                                            </td>
                                            <td><strong><?php echo htmlspecialchars($seller['first_name'] . ' ' . $seller['last_name']); ?></strong></td>
                                            <td><?php echo htmlspecialchars($seller['email']); ?></td>
                                            <td><?php echo htmlspecialchars($seller['phone']); ?></td>
                                            <td><?php echo htmlspecialchars($seller['city']); ?></td>
                                            <td><?php echo htmlspecialchars($seller['state']); ?></td>
                                            <td>
                                                <div class="action-buttons">
                                                    <a href="?action=view&id=<?php echo $seller['id']; ?>" class="btn-view">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                    <a href="?action=edit&id=<?php echo $seller['id']; ?>" class="btn-edit">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <button class="btn-delete" onclick="confirmDelete(<?php echo $seller['id']; ?>)">
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
                                            No sellers found.
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tablet & Mobile Seller Cards -->
                <div class="mobile-seller-cards">
                    <div class="tablet-seller-grid" id="tabletSellerGrid">
                        <?php
                        mysqli_data_seek($result, 0); // Reset result pointer
                        if (mysqli_num_rows($result) > 0) {
                            while ($seller = mysqli_fetch_assoc($result)) {
                        ?>
                                <div class="mobile-seller-card">
                                    <div class="mobile-seller-header">
                                        <img src="../assets/images/sellers/<?php echo htmlspecialchars($seller['image']); ?>"
                                            alt="<?php echo htmlspecialchars($seller['first_name'] . ' ' . $seller['last_name']); ?>"
                                            class="mobile-seller-img">
                                        <div class="mobile-seller-info">
                                            <h4><?php echo htmlspecialchars($seller['first_name'] . ' ' . $seller['last_name']); ?></h4>
                                            <p><?php echo htmlspecialchars($seller['email']); ?></p>
                                            <p><?php echo htmlspecialchars($seller['phone']); ?></p>
                                        </div>
                                    </div>
                                    <div class="mobile-seller-details">
                                        <div class="mobile-detail-item">
                                            <span class="mobile-detail-label">City</span>
                                            <span class="mobile-detail-value"><?php echo htmlspecialchars($seller['city']); ?></span>
                                        </div>
                                        <div class="mobile-detail-item">
                                            <span class="mobile-detail-label">State</span>
                                            <span class="mobile-detail-value"><?php echo htmlspecialchars($seller['state']); ?></span>
                                        </div>
                                    </div>
                                    <div class="action-buttons">
                                        <a href="?action=view&id=<?php echo $seller['id']; ?>" class="btn-view">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a href="?action=edit&id=<?php echo $seller['id']; ?>" class="btn-edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <button class="btn-delete" onclick="confirmDelete(<?php echo $seller['id']; ?>)">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </div>
                                </div>
                        <?php
                            }
                        } else {
                            echo '<div class="col-12 text-center py-4 text-muted">No sellers found.</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- View Seller Modal -->
    <?php
    if (isset($_GET['action']) && $_GET['action'] == 'view' && isset($_GET['id'])) {
        $seller_id = intval($_GET['id']);
        $seller_query = "SELECT * FROM sellers WHERE id = $seller_id";
        $seller_result = mysqli_query($conn, $seller_query);
        $seller_data = mysqli_fetch_assoc($seller_result);

        if ($seller_data) {
    ?>
            <div class="modal fade show" id="viewSellerModal" tabindex="-1" style="display: block; padding-right: 17px;">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-user-circle me-2"></i>Seller Details
                            </h5>
                            <a href="sellers.php" class="btn-close"></a>
                        </div>
                        <div class="modal-body">
                            <div class="text-center mb-4">
                                <img src="../assets/images/sellers/<?php echo htmlspecialchars($seller_data['image']); ?>"
                                    alt="<?php echo htmlspecialchars($seller_data['first_name'] . ' ' . $seller_data['last_name']); ?>"
                                    class="seller-profile-img">
                            </div>
                            <div class="seller-details">
                                <div class="detail-section">
                                    <h6><i class="fas fa-user me-2"></i>Personal Information</h6>
                                    <div class="detail-row">
                                        <div class="detail-label">First Name:</div>
                                        <div class="detail-value"><?php echo htmlspecialchars($seller_data['first_name']); ?></div>
                                    </div>
                                    <div class="detail-row">
                                        <div class="detail-label">Last Name:</div>
                                        <div class="detail-value"><?php echo htmlspecialchars($seller_data['last_name']); ?></div>
                                    </div>
                                    <div class="detail-row">
                                        <div class="detail-label">Email:</div>
                                        <div class="detail-value"><?php echo htmlspecialchars($seller_data['email']); ?></div>
                                    </div>
                                    <div class="detail-row">
                                        <div class="detail-label">Phone:</div>
                                        <div class="detail-value"><?php echo htmlspecialchars($seller_data['phone']); ?></div>
                                    </div>
                                </div>

                                <div class="detail-section">
                                    <h6><i class="fas fa-map-marker-alt me-2"></i>Address Details</h6>
                                    <div class="detail-row">
                                        <div class="detail-label">Address:</div>
                                        <div class="detail-value"><?php echo htmlspecialchars($seller_data['address']); ?></div>
                                    </div>
                                    <div class="detail-row">
                                        <div class="detail-label">City:</div>
                                        <div class="detail-value"><?php echo htmlspecialchars($seller_data['city']); ?></div>
                                    </div>
                                    <div class="detail-row">
                                        <div class="detail-label">State:</div>
                                        <div class="detail-value"><?php echo htmlspecialchars($seller_data['state']); ?></div>
                                    </div>
                                    <div class="detail-row">
                                        <div class="detail-label">Pincode:</div>
                                        <div class="detail-value"><?php echo htmlspecialchars($seller_data['pincode']); ?></div>
                                    </div>
                                </div>

                                <div class="detail-section">
                                    <h6><i class="fas fa-info-circle me-2"></i>Business Information</h6>
                                    <div class="detail-row">
                                        <div class="detail-label">About Us:</div>
                                        <div class="detail-value"><?php echo htmlspecialchars($seller_data['about_us']); ?></div>
                                    </div>
                                    <div class="detail-row">
                                        <div class="detail-label">About Products:</div>
                                        <div class="detail-value"><?php echo htmlspecialchars($seller_data['about_product']); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <a href="sellers.php" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Close
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

    <!-- Edit Seller Modal -->
    <?php
    if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
        $seller_id = intval($_GET['id']);
        $seller_query = "SELECT * FROM sellers WHERE id = $seller_id";
        $seller_result = mysqli_query($conn, $seller_query);
        $seller_data = mysqli_fetch_assoc($seller_result);

        if ($seller_data) {
    ?>
            <div class="modal fade show" id="editSellerModal" tabindex="-1" style="display: block; padding-right: 17px;">
                <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-edit me-2"></i>Edit Seller
                            </h5>
                            <a href="sellers.php" class="btn-close"></a>
                        </div>
                        <div class="modal-body">
                            <form action="../codes/update_seller.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="seller_id" value="<?php echo $seller_data['id']; ?>">
                                <input type="hidden" name="old_image" value="<?php echo $seller_data['image']; ?>">

                                <h6 class="mb-3" style="color: var(--primary-color); border-bottom: 2px solid var(--border-color); padding-bottom: 0.5rem;">
                                    <i class="fas fa-user me-2"></i>Personal Information
                                </h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="editFirstName" class="form-label">First Name</label>
                                        <input type="text" class="form-control" name="first_name" id="editFirstName" value="<?php echo htmlspecialchars($seller_data['first_name']); ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="editLastName" class="form-label">Last Name</label>
                                        <input type="text" class="form-control" name="last_name" id="editLastName" value="<?php echo htmlspecialchars($seller_data['last_name']); ?>" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="editEmail" class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email" id="editEmail" value="<?php echo htmlspecialchars($seller_data['email']); ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="editPhone" class="form-label">Phone</label>
                                        <input type="tel" class="form-control" name="phone" id="editPhone" value="<?php echo htmlspecialchars($seller_data['phone']); ?>" required>
                                    </div>
                                </div>

                                <h6 class="mb-3 mt-4" style="color: var(--primary-color); border-bottom: 2px solid var(--border-color); padding-bottom: 0.5rem;">
                                    <i class="fas fa-map-marker-alt me-2"></i>Address Details
                                </h6>
                                <div class="mb-3">
                                    <label for="editAddress" class="form-label">Address</label>
                                    <textarea class="form-control" name="address" id="editAddress" rows="2" required><?php echo htmlspecialchars($seller_data['address']); ?></textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="editCity" class="form-label">City</label>
                                        <input type="text" class="form-control" name="city" id="editCity" value="<?php echo htmlspecialchars($seller_data['city']); ?>" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="editState" class="form-label">State</label>
                                        <input type="text" class="form-control" name="state" id="editState" value="<?php echo htmlspecialchars($seller_data['state']); ?>" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="editPincode" class="form-label">Pincode</label>
                                        <input type="text" class="form-control" name="pincode" id="editPincode" value="<?php echo htmlspecialchars($seller_data['pincode']); ?>" required>
                                    </div>
                                </div>

                                <h6 class="mb-3 mt-4" style="color: var(--primary-color); border-bottom: 2px solid var(--border-color); padding-bottom: 0.5rem;">
                                    <i class="fas fa-info-circle me-2"></i>Business Information
                                </h6>
                                <div class="mb-3">
                                    <label for="editAboutUs" class="form-label">About Us</label>
                                    <textarea class="form-control" name="about_us" id="editAboutUs" rows="3" required><?php echo htmlspecialchars($seller_data['about_us']); ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="editAboutProduct" class="form-label">About Your Products</label>
                                    <textarea class="form-control" name="about_product" id="editAboutProduct" rows="3" required><?php echo htmlspecialchars($seller_data['about_product']); ?></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="editImage" class="form-label">Profile Image</label>
                                    <input type="file" class="form-control" name="image" id="editImage" accept="image/*">
                                    <small class="text-muted">Leave empty to keep current image</small>
                                </div>

                                <div class="text-center">
                                    <button type="submit" name="update_seller" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>Update Seller
                                    </button>
                                </div>
                            </form>
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

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

   <script>
    // Initialize DataTable
    $(document).ready(function() {
        $('#sellersTable').DataTable({
            responsive: true,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search sellers..."
            }
        });
    });

    // Header and Sidebar Toggle Function
    function toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        
        sidebar.classList.toggle('show');
        sidebarOverlay.classList.toggle('show');
        
        // Prevent body scroll when sidebar is open
        if (sidebar.classList.contains('show')) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = 'auto';
        }
    }

    // Close sidebar when clicking on overlay
    document.getElementById('sidebarOverlay').addEventListener('click', function() {
        toggleSidebar();
    });

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        const sidebar = document.querySelector('.sidebar');
        const menuToggle = document.querySelector('.menu-toggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        
        // Check if sidebar is open and click is outside
        if (sidebar.classList.contains('show') && 
            !sidebar.contains(event.target) && 
            !menuToggle.contains(event.target) &&
            window.innerWidth <= 768) {
            toggleSidebar();
        }
    });

    // Close sidebar on window resize if it becomes desktop view
    window.addEventListener('resize', function() {
        const sidebar = document.querySelector('.sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        
        if (window.innerWidth > 768 && sidebar.classList.contains('show')) {
            sidebar.classList.remove('show');
            sidebarOverlay.classList.remove('show');
            document.body.style.overflow = 'auto';
        }
    });

    // Confirm Delete
    function confirmDelete(id) {
        Swal.fire({
            title: "Are you sure you want to delete this seller?",
            text: "This action cannot be undone!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it",
            cancelButtonText: "Cancel",
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "../codes/delete_seller.php?action=delete&id=" + id;
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

    // Fix for modal scroll issue on mobile
    document.addEventListener('DOMContentLoaded', function() {
        var modals = document.querySelectorAll('.modal');
        modals.forEach(function(modal) {
            modal.addEventListener('show.bs.modal', function() {
                document.body.style.overflow = 'hidden';
                document.body.style.paddingRight = '0';
            });
            modal.addEventListener('hidden.bs.modal', function() {
                document.body.style.overflow = 'auto';
            });
        });
    });
</script>
</body>

</html>