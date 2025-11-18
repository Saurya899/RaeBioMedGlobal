 <!-- Header  -->
 <header class="header">
     <div class="header-left">
         <button class="menu-toggle" onclick="toggleSidebar()">
             <i class="fas fa-bars"></i>
         </button>
         <h1 style="font-size: 1.25rem; font-weight: 600;">Dashboard</h1>
     </div>
     <div class="header-right">
         <button class="theme-toggle" onclick="toggleTheme()">
             <i class="fas fa-moon" id="themeIcon"></i>
         </button>
         <div class="profile-dropdown">
             <button class="profile-btn" onclick="toggleDropdown()">
                 <div class="profile-avatar">A</div>
                 <div class="profile-info">
                     <div style="font-weight: 600; font-size: 0.875rem;">Admin</div>
                 </div>
                 <i class="fas fa-chevron-down" style="font-size: 0.75rem;"></i>
             </button>
             <div class="dropdown-menu" id="dropdownMenu">
                 <!-- <a href="#" class="dropdown-item" onclick="showProfile()">
                                <i class="fas fa-user"></i>
                                <span>My Profile</span>
                            </a> -->
                 <a href="#" class="dropdown-item" onclick="showChangePassword()">
                     <i class="fas fa-key"></i>
                     <span>Change Password</span>
                 </a>
                 <!-- Change Email Button -->
                 <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#emailModal">
                     <i class="fas fa-envelope"></i> Change Email
                 </button>
                 <div class="dropdown-divider"></div>
                 <a href="../logout.php" class="dropdown-item" onclick="return confirm('Are you sure you want to logout?')">
                     <i class="fas fa-sign-out-alt"></i>
                     <span>Logout</span>
                 </a>
             </div>
         </div>
     </div>
 </header>