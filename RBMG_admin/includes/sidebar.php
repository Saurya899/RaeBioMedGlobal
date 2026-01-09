  <!-- sidebar  -->
  <aside class="sidebar" id="sidebar">
      <div class="logo">
          <img src="../assets/images/logo.png" alt="logo" style="height:50px; width:50px; border-radius:30%;">
          <h2><strong>RaeBioMedGlobal</strong></h2>
      </div>
      <nav class="nav-menu">
          <a href="dashboard.php" class="nav-item" onclick="showPage('dashboard')">
              <i class="fas fa-th-large"></i>
              <span>Dashboard</span>
          </a>
          <a href="categories.php" class="nav-item" onclick="showPage('categories')">
              <i class="fas fa-layer-group"></i>
              <span>Categories</span>
          </a>
          <a href="products.php" class="nav-item" onclick="showPage('products')">
              <i class="fas fa-box"></i>
              <span>Products</span>
          </a><a href="sellers.php" class="nav-item" onclick="showPage('sellers')">
              <i class="fas fa-users"></i>
              <span>Sellers</span>
          </a>
          <a href="enquiries.php" class="nav-item" onclick="showPage('enquiries')">
              <i class="fas fa-envelope-open-text"></i>
              <span>Enquiries</span>
          </a>
          <a href="messages.php" class="nav-item" onclick="showPage('messages')">
              <i class="fas fa-envelope"></i>
              <span>Messages</span>
          </a>
           <a href="carousel.php" class="nav-item" onclick="showPage('carousel')">
              <i class="fas fa-sliders"></i>
              <span>Carousel</span>
          </a>
          <a href="../logout.php" class="nav-item" onclick="return confirm('Are you sure you want to logout?')">
              <i class="fas fa-sign-out-alt"></i>
              <span>Logout</span>
          </a>
      </nav>
  </aside>