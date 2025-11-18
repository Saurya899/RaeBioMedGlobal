 <?php
    include("../includes/session.php");

    include('../config/db.php'); // apna correct DB config path lagao

    // Total Categories
    $q1 = mysqli_query($conn, "SELECT COUNT(*) AS total_categories FROM categories");
    $r1 = mysqli_fetch_assoc($q1);
    $total_categories = $r1['total_categories'];

    // Total Products
    $q2 = mysqli_query($conn, "SELECT COUNT(*) AS total_products FROM products");
    $r2 = mysqli_fetch_assoc($q2);
    $total_products = $r2['total_products'];

    // Total Messages
    $q3 = mysqli_query($conn, "SELECT COUNT(*) AS total_messages FROM messages");
    $r3 = mysqli_fetch_assoc($q3);
    $total_messages = $r3['total_messages'];

    // Total Sellers
    $q4 = mysqli_query($conn, "SELECT COUNT(*) AS total_sellers FROM sellers");
    $r4 = mysqli_fetch_assoc($q4);
    $total_sellers = $r4['total_sellers'];
    ?>


 <!DOCTYPE html>
 <html lang="en">

 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>RaeBioMedGlobal - Admin Dashboard</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
     <link rel="stylesheet" href="../assets/css/style.css">
     <!-- Favicons -->
     <link rel="icon" type="image/png" href="../../assets/favicon/favicon-96x96.png" sizes="96x96" />
     <link rel="icon" type="image/svg+xml" href="../../assets/favicon/favicon.svg" />
     <link rel="shortcut icon" href="../../assets/favicon/favicon.ico" />
     <link rel="apple-touch-icon" sizes="180x180" href="../../assets/favicon/apple-touch-icon.png" />
     <meta name="apple-mobile-web-app-title" content="MyWebSite" />
     <link rel="manifest" href="../../assets/favicon/site.webmanifest" />
     <style>
         .page-title {
             margin-bottom: 1.5rem;
         }

         /* Stats Cards */
         .stats-grid {
             display: grid;
             grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
             gap: 1.5rem;
             margin-bottom: 2rem;
         }

         html {
             overflow-y: scroll
         }

         body.modal-open {
             overflow: hidden;
             padding-right: 0 !important;
         }

         .stat-card {
             background: var(--card-bg);
             border-radius: 1rem;
             padding: 1.5rem;
             box-shadow: var(--shadow);
             transition: all 0.3s;
             border: 1px solid var(--border-color);
         }

         .stat-card:hover {
             transform: translateY(-5px);
             box-shadow: var(--shadow-lg);
         }

         .stat-header {
             display: flex;
             justify-content: space-between;
             align-items: flex-start;
             margin-bottom: 1rem;
         }

         .stat-icon {
             width: 60px;
             height: 60px;
             border-radius: 1rem;
             display: flex;
             align-items: center;
             justify-content: center;
             font-size: 1.75rem;
             color: white;
         }

         .stat-icon.blue {
             background: linear-gradient(135deg, #3b82f6, #2563eb);
         }

         .stat-icon.green {
             background: linear-gradient(135deg, #10b981, #059669);
         }

         .stat-icon.orange {
             background: linear-gradient(135deg, #f59e0b, #d97706);
         }

         .stat-icon.purple {
             background: linear-gradient(135deg, #8b5cf6, #7c3aed);
         }

         .stat-content {
             flex: 1;
         }

         .stat-title {
             color: var(--text-secondary);
             font-size: 0.875rem;
             font-weight: 500;
             margin-bottom: 0.5rem;
         }

         .stat-value {
             font-size: 2rem;
             font-weight: 700;
             color: var(--text-primary);
             line-height: 1;
         }

         .stat-trend {
             display: flex;
             align-items: center;
             gap: 0.5rem;
             margin-top: 0.75rem;
             font-size: 0.875rem;
         }

         .trend-up {
             color: var(--success-color);
         }

         .trend-down {
             color: var(--danger-color);
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

         .close-modal {
             background: none;
             border: none;
             font-size: 1.5rem;
             color: var(--text-secondary);
             cursor: pointer;
             padding: 0.25rem;
         }

         @media (max-width: 768px) {
             .stats-grid {
                 grid-template-columns: 1fr;
             }
         }
     </style>
 </head>

 <body>
     <div class="dashboard-container">
         <!-- Sidebar -->
         <?php include("../includes/sidebar.php") ?>

         <!-- Sidebar Overlay for Mobile -->
         <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

         <!-- Main Content -->
         <main class="main-content" id="mainContent">
             <!-- header  -->
             <?php include("../includes/header.php") ?>

             <!-- Content -->
             <div class="content">
                 <h2 class="page-title">Dashboard Overview</h2>

                 <!-- Stats Cards -->
                 <div class="stats-grid">
                     <div class="stat-card">
                         <div class="stat-header">
                             <div class="stat-content">
                                 <div class="stat-title">Total Categories</div>
                                 <div class="stat-value"><?php echo $total_categories; ?></div>
                             </div>
                             <div class="stat-icon blue">
                                 <i class="fas fa-layer-group"></i>
                             </div>
                         </div>
                         <p><a href="categories.php" style="text-decoration: none;" >View Details</a></p>
                     </div>

                     <div class="stat-card">
                         <div class="stat-header">
                             <div class="stat-content">
                                 <div class="stat-title">Total Products</div>
                                 <div class="stat-value"><?php echo $total_products; ?></div>
                                   
                             </div>
                             <div class="stat-icon green">
                                 <i class="fas fa-box"></i>
                             </div>
                         </div>
                         <p><a href="products.php" style="text-decoration: none;">View Details</a></p>
                     </div>

                     <div class="stat-card">
                         <div class="stat-header">
                             <div class="stat-content">
                                 <div class="stat-title">Total Messages</div>
                                 <div class="stat-value"><?php echo $total_messages; ?></div>
                                   
                             </div>
                             <div class="stat-icon orange">
                                 <i class="fas fa-envelope"></i>
                             </div>
                         </div>
                         <p><a href="messages.php" style="text-decoration: none;">View Details</a></p>
                     </div>
                           
                     <div class="stat-card">
                         <div class="stat-header">
                             <div class="stat-content">
                                 <div class="stat-title">Total Sellers</div>
                                 <div class="stat-value"><?php echo $total_sellers; ?></div>
                                  
                             </div>
                             <div class="stat-icon purple">
                                 <i class="fas fa-users"></i>
                             </div>
                         </div>
                          <p><a href="sellers.php" style="text-decoration: none;">View Details</a></p>
                     </div>
                 </div>
             </div>
         </main>
     </div>

     <!-- Profile Modal -->

     <?php include("../includes/profile.php") ?>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
     <!-- SweetAlert -->
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
     <script src="../js/script.js"> </script>
 </body>

 </html>