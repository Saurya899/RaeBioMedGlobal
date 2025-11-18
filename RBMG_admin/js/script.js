// ==================== THEME TOGGLE ====================
function toggleTheme() {
  const html = document.documentElement;
  const icon = document.getElementById("themeIcon");
  const currentTheme = html.getAttribute("data-theme");

  if (currentTheme === "dark") {
    html.setAttribute("data-theme", "light");
    icon.className = "fas fa-moon";
    localStorage.setItem("theme", "light");
  } else {
    html.setAttribute("data-theme", "dark");
    icon.className = "fas fa-sun";
    localStorage.setItem("theme", "dark");
  }
}

// ==================== SIDEBAR TOGGLE ====================
function toggleSidebar() {
  const sidebar = document.getElementById("sidebar");
  const overlay = document.getElementById("sidebarOverlay");
  sidebar.classList.toggle("show");
  overlay.classList.toggle("show");
}

// ==================== DROPDOWN TOGGLE ====================
function toggleDropdown() {
  const dropdown = document.getElementById("dropdownMenu");
  dropdown.classList.toggle("show");
}

// ==================== CLOSE DROPDOWN ON OUTSIDE CLICK ====================
document.addEventListener("click", (e) => {
  const dropdown = document.getElementById("dropdownMenu");
  const profileBtn = document.querySelector(".profile-btn");
  if (
    dropdown &&
    profileBtn &&
    !profileBtn.contains(e.target) &&
    !dropdown.contains(e.target)
  ) {
    dropdown.classList.remove("show");
  }
});
// Close dropdown when clicking outside
document.addEventListener("click", (e) => {
  const dropdown = document.getElementById("dropdownMenu");
  const profileBtn = document.querySelector(".profile-btn");
  if (!profileBtn.contains(e.target) && !dropdown.contains(e.target)) {
    dropdown.classList.remove("show");
  }
});
// save theme
window.addEventListener("DOMContentLoaded", () => {
  const savedTheme = localStorage.getItem("theme") || "light";
  const icon = document.getElementById("themeIcon");
  document.documentElement.setAttribute("data-theme", savedTheme);
  icon.className = savedTheme === "dark" ? "fas fa-sun" : "fas fa-moon";
});
// ==================== PROFILE MODALS ====================
function showProfile() {
  new bootstrap.Modal(document.getElementById("profileModal")).show();
  document.getElementById("dropdownMenu").classList.remove("show");
}

function showChangePassword() {
  new bootstrap.Modal(document.getElementById("passwordModal")).show();
  document.getElementById("dropdownMenu").classList.remove("show");
}
// ==================== PAGE NAVIGATION ====================
function showPage(page) {
  document.querySelectorAll(".nav-item").forEach((item) => {
    item.classList.remove("active");
  });
  event.target.closest(".nav-item").classList.add("active");

  if (window.innerWidth <= 768) {
    toggleSidebar();
  }
}

//   edit profile

document
  .getElementById("updateProfileForm")
  .addEventListener("submit", function (e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch("../codes/update_profile.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.text())
      .then((data) => {
        if (data.trim() === "success") {
          alert("Profile updated successfully!");
          location.reload();
        } else {
          alert("Error updating profile.");
        }
      });
  });

//   change password

document.addEventListener("DOMContentLoaded", function () {
  const passwordForm = document.getElementById("passwordForm");

  if (passwordForm) {
    passwordForm.addEventListener("submit", function (e) {
      e.preventDefault();

      let current = document.getElementById("currentPassword").value.trim();
      let newPass = document.getElementById("newPassword").value.trim();
      let confirm = document.getElementById("confirmPassword").value.trim();

      let formData = new FormData();
      formData.append("current", current);
      formData.append("new", newPass);
      formData.append("confirm", confirm);

      fetch("../codes/change_password.php", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.status === "success") {
            Swal.fire({
              icon: "success",
              title: "Password Updated!",
              text: data.message,
              timer: 2000,
              showConfirmButton: false,
            });

            // Close modal + Reset form + Redirect after short delay
            setTimeout(() => {
              let modalElement = document.getElementById("passwordModal");
              let modalInstance = bootstrap.Modal.getInstance(modalElement);
              if (modalInstance) modalInstance.hide();
              passwordForm.reset();

              // ✅ Redirect to login page
              if (data.redirect) {
                window.location.href = data.redirect;
              } else {
                window.location.href = "../RBMG_admin/index.php";
              }
            }, 2000);
          } else {
            Swal.fire({
              icon: "error",
              title: "Error!",
              text: data.message,
              timer: 2000,
              showConfirmButton: false,
            });
          }
        })
        .catch((err) => {
          Swal.fire({
            icon: "error",
            title: "Oops!",
            text: "Something went wrong!",
            timer: 2000,
            showConfirmButton: false,
          });
        });
    });
  }
});
// / change email
document.addEventListener("DOMContentLoaded", function () {
  const emailForm = document.getElementById("emailForm");

  if (emailForm) {
    emailForm.addEventListener("submit", function (e) {
      e.preventDefault();

      let current = document.getElementById("currentEmail").value.trim();
      let newEmail = document.getElementById("newEmail").value.trim();
      let confirm = document.getElementById("confirmEmail").value.trim();

      let formData = new FormData();
      formData.append("current", current);
      formData.append("new", newEmail);
      formData.append("confirm", confirm);

      fetch("../codes/change_email.php", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.status === "success") {
            Swal.fire({
              icon: "success",
              title: "Email Updated!",
              text: data.message,
              timer: 2000,
              showConfirmButton: false,
            });

            // Close modal + Reset + Redirect
            setTimeout(() => {
              let modalElement = document.getElementById("emailModal");
              let modalInstance = bootstrap.Modal.getInstance(modalElement);
              if (modalInstance) modalInstance.hide();
              emailForm.reset();

              if (data.redirect) {
                window.location.href = data.redirect;
              } else {
                window.location.href = "../RBMG_admin/index.php";
              }
            }, 2000);
          } else {
            Swal.fire({
              icon: "error",
              title: "Error!",
              text: data.message,
              timer: 2000,
              showConfirmButton: false,
            });
          }
        })
        .catch(() => {
          Swal.fire({
            icon: "error",
            title: "Oops!",
            text: "Something went wrong!",
            timer: 2000,
            showConfirmButton: false,
          });
        });
    });
  }
});

//          // Theme Toggle
//         function toggleTheme() {
//             const html = document.documentElement;
//             const icon = document.getElementById('themeIcon');
//             const currentTheme = html.getAttribute('data-theme');

//             if (currentTheme === 'dark') {
//                 html.setAttribute('data-theme', 'light');
//                 icon.className = 'fas fa-moon';
//                 localStorage.setItem('theme', 'light');
//             } else {
//                 html.setAttribute('data-theme', 'dark');
//                 icon.className = 'fas fa-sun';
//                 localStorage.setItem('theme', 'dark');
//             }
//         }

//         // Sidebar Toggle
//         function toggleSidebar() {
//             const sidebar = document.getElementById('sidebar');
//             const overlay = document.getElementById('sidebarOverlay');
//             sidebar.classList.toggle('show');
//             overlay.classList.toggle('show');
//         }

//         // Dropdown Toggle
//         function toggleDropdown() {
//             const dropdown = document.getElementById('dropdownMenu');
//             dropdown.classList.toggle('show');
//         }

//         // Confirm Delete
//         function confirmDelete(id) {
//             Swal.fire({
//                 title: "Are you sure you want to delete this seller?",
//                 text: "This action cannot be undone!",
//                 icon: "warning",
//                 showCancelButton: true,
//                 confirmButtonText: "Yes, delete it",
//                 cancelButtonText: "Cancel",
//                 confirmButtonColor: "#d33",
//                 cancelButtonColor: "#3085d6"
//             }).then((result) => {
//                 if (result.isConfirmed) {
//                     window.location.href = "sellers.php?action=delete&id=" + id;
//                 }
//             });
//         }

//         // Close dropdown when clicking outside
//         document.addEventListener('click', (e) => {
//             const dropdown = document.getElementById('dropdownMenu');
//             const profileBtn = document.querySelector('.profile-btn');
//             if (!profileBtn.contains(e.target) && !dropdown.contains(e.target)) {
//                 dropdown.classList.remove('show');
//             }
//         });

//         // Load saved theme
//         window.addEventListener('DOMContentLoaded', () => {
//             const savedTheme = localStorage.getItem('theme') || 'light';
//             const icon = document.getElementById('themeIcon');
//             document.documentElement.setAttribute('data-theme', savedTheme);
//             icon.className = savedTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
//         });

//         // ==================== THEME TOGGLE ====================
// function toggleTheme() {
//     const html = document.documentElement;
//     const icon = document.getElementById('themeIcon');
//     const currentTheme = html.getAttribute('data-theme');

//     if (currentTheme === 'dark') {
//         html.setAttribute('data-theme', 'light');
//         icon.className = 'fas fa-moon';
//         localStorage.setItem('theme', 'light');
//     } else {
//         html.setAttribute('data-theme', 'dark');
//         icon.className = 'fas fa-sun';
//         localStorage.setItem('theme', 'dark');
//     }
// }

// // ==================== SIDEBAR TOGGLE ====================
// function toggleSidebar() {
//     const sidebar = document.getElementById('sidebar');
//     const overlay = document.getElementById('sidebarOverlay');
//     sidebar.classList.toggle('show');
//     overlay.classList.toggle('show');
// }

// // ==================== DROPDOWN TOGGLE ====================
// function toggleDropdown() {
//     const dropdown = document.getElementById('dropdownMenu');
//     dropdown.classList.toggle('show');
// }

// // ==================== PROFILE MODALS ====================
// function showProfile() {
//     new bootstrap.Modal(document.getElementById('profileModal')).show();
//     document.getElementById('dropdownMenu').classList.remove('show');
// }

// function showChangePassword() {
//     new bootstrap.Modal(document.getElementById('passwordModal')).show();
//     document.getElementById('dropdownMenu').classList.remove('show');
// }

// function changePassword(e) {
//     e.preventDefault();
//     alert('Password changed successfully!');
//     bootstrap.Modal.getInstance(document.getElementById('passwordModal')).hide();
// }

// // ==================== LOGOUT ====================
// function logout() {
//     if (confirm('Are you sure you want to logout?')) {
//         alert('Logged out successfully!');
//     }
// }

// // ==================== PAGE NAVIGATION ====================
// function showPage(page) {
//     document.querySelectorAll('.nav-item').forEach(item => {
//         item.classList.remove('active');
//     });
//     event.target.closest('.nav-item').classList.add('active');

//     if (window.innerWidth <= 768) {
//         toggleSidebar();
//     }
// }

// // ==================== IMAGE PREVIEW FUNCTIONS ====================
// function previewAddImage(event, previewId = 'addImagePreview', containerId = 'addImagePreviewContainer') {
//     const file = event.target.files[0];
//     const preview = document.getElementById(previewId);
//     const container = document.getElementById(containerId);

//     if (file) {
//         // Validate file size (5MB)
//         if (file.size > 5000000) {
//             Swal.fire({
//                 icon: 'error',
//                 title: 'File Too Large',
//                 text: 'Image size should not exceed 5MB!',
//                 confirmButtonColor: '#ef4444'
//             });
//             event.target.value = '';
//             container.style.display = 'none';
//             container.classList?.remove('show');
//             return;
//         }

//         // Validate file type
//         const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
//         if (!allowedTypes.includes(file.type)) {
//             Swal.fire({
//                 icon: 'error',
//                 title: 'Invalid File Type',
//                 text: 'Only JPG, JPEG, PNG, GIF, and WEBP images are allowed!',
//                 confirmButtonColor: '#ef4444'
//             });
//             event.target.value = '';
//             container.style.display = 'none';
//             container.classList?.remove('show');
//             return;
//         }

//         const reader = new FileReader();
//         reader.onload = function(e) {
//             preview.src = e.target.result;
//             container.style.display = 'block';
//             container.classList?.add('show');

//             // Smooth scroll to preview
//             setTimeout(() => {
//                 container.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
//             }, 100);
//         }
//         reader.readAsDataURL(file);
//     }
// }

// function previewEditImage(event, itemId, type = 'product') {
//     const preview = document.getElementById(`editImagePreview${itemId}`);
//     const container = document.getElementById(`editImagePreviewContainer${itemId}`);

//     if (event.target.files && event.target.files[0]) {
//         const file = event.target.files[0];

//         // Validate file size (5MB)
//         if (file.size > 5000000) {
//             Swal.fire({
//                 icon: 'error',
//                 title: 'File Too Large',
//                 text: 'Image size should not exceed 5MB!',
//                 confirmButtonColor: '#ef4444'
//             });
//             event.target.value = '';
//             container.style.display = 'none';
//             container.classList?.remove('show');
//             return;
//         }

//         // Validate file type
//         const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
//         if (!allowedTypes.includes(file.type)) {
//             Swal.fire({
//                 icon: 'error',
//                 title: 'Invalid File Type',
//                 text: 'Only JPG, JPEG, PNG, GIF, and WEBP images are allowed!',
//                 confirmButtonColor: '#ef4444'
//             });
//             event.target.value = '';
//             container.style.display = 'none';
//             container.classList?.remove('show');
//             return;
//         }

//         const reader = new FileReader();
//         reader.onload = function(e) {
//             preview.src = e.target.result;
//             container.style.display = 'block';
//             container.classList?.add('show');
//         }
//         reader.readAsDataURL(file);
//     }
// }

// // ==================== REMOVE IMAGE FUNCTIONS ====================
// function removeAddImage(fileInputId = 'categoryImage', previewId = 'addImagePreview', containerId = 'addImagePreviewContainer') {
//     const fileInput = document.getElementById(fileInputId);
//     const preview = document.getElementById(previewId);
//     const container = document.getElementById(containerId);

//     fileInput.value = '';
//     preview.src = '';
//     container.style.display = 'none';
//     container.classList?.remove('show');
// }

// function removeEditImage(itemId, fileInputSelector = 'input[type="file"]') {
//     const fileInput = document.querySelector(`#editCategoryModal${itemId} ${fileInputSelector}`);
//     const preview = document.getElementById(`editImagePreview${itemId}`);
//     const container = document.getElementById(`editImagePreviewContainer${itemId}`);

//     fileInput.value = '';
//     preview.src = '';
//     container.style.display = 'none';
//     container.classList?.remove('show');
// }

// // ==================== RESET FORM FUNCTIONS ====================
// function resetAddForm(formId = 'categoryForm') {
//     document.getElementById(formId).reset();
//     removeAddImage();
// }

// // ==================== DELETE CONFIRMATION ====================
// function confirmDelete(id, type = 'category', customUrl = null) {
//     const messages = {
//         category: {
//             title: "Are you sure you want to delete this category?",
//             text: "This action cannot be undone!",
//             url: `../codes/delete_category.php?id=${id}`
//         },
//         product: {
//             title: "Are you sure you want to delete this product?",
//             text: "",
//             url: `../codes/delete_product.php?id=${id}`
//         },
//         message: {
//             title: "Are you sure you want to delete this message?",
//             text: "",
//             url: `messages.php?action=delete&id=${id}`
//         },
//         seller: {
//             title: "Are you sure you want to delete this seller?",
//             text: "This action cannot be undone!",
//             url: `sellers.php?action=delete&id=${id}`
//         }
//     };

//     const config = messages[type] || {
//         title: "Are you sure you want to delete this item?",
//         text: "",
//         url: customUrl
//     };

//     Swal.fire({
//         title: config.title,
//         text: config.text,
//         icon: "warning",
//         showCancelButton: true,
//         confirmButtonText: "Yes, delete it",
//         cancelButtonText: "Cancel",
//         confirmButtonColor: "#d33",
//         cancelButtonColor: "#3085d6"
//     }).then((result) => {
//         if (result.isConfirmed) {
//             window.location.href = config.url;
//         }
//     });
// }

// // ==================== PRODUCT VIEW FUNCTION ====================
// function viewProduct(id) {
//     fetch(`../codes/get_product.php?id=${id}`)
//         .then(response => response.json())
//         .then(data => {
//             if(data.success) {
//                 const product = data.product;

//                 document.getElementById('viewProductImage').src = `../assets/images/products/${product.image}`;
//                 document.getElementById('viewProductName').textContent = product.name;
//                 document.getElementById('viewProductCategory').textContent = product.cat_name || 'Uncategorized';
//                 document.getElementById('viewProductPrice').textContent = `₹${parseFloat(product.price).toLocaleString()}`;
//                 document.getElementById('viewProductDescription').textContent = product.description;

//                 // Specifications
//                 const specsDiv = document.getElementById('viewProductSpecs');
//                 specsDiv.innerHTML = '';
//                 const specs = product.specification.split('\n');
//                 specs.forEach(spec => {
//                     if(spec.trim()) {
//                         specsDiv.innerHTML += `<div class="spec-item">${spec}</div>`;
//                     }
//                 });

//                 // Show modal
//                 const modal = new bootstrap.Modal(document.getElementById('viewProductModal'));
//                 modal.show();
//             } else {
//                 alert('Product not found!');
//             }
//         })
//         .catch(error => {
//             console.error('Error:', error);
//             alert('Error loading product details!');
//         });
// }

// // ==================== EVENT LISTENERS ====================
// document.addEventListener('click', (e) => {
//     const dropdown = document.getElementById('dropdownMenu');
//     const profileBtn = document.querySelector('.profile-btn');
//     if (dropdown && profileBtn && !profileBtn.contains(e.target) && !dropdown.contains(e.target)) {
//         dropdown.classList.remove('show');
//     }
// });

// // Reset product form when modal closes
// const productModal = document.getElementById('productModal');
// if (productModal) {
//     productModal.addEventListener('hidden.bs.modal', function () {
//         const form = document.querySelector('#productModal form');
//         if (form) {
//             form.reset();
//             removeAddImage('addProductImage', 'addImagePreview', 'addImagePreviewContainer');
//         }
//     });
// }

// // ==================== INITIALIZATION ====================
// window.addEventListener('DOMContentLoaded', () => {
//     // Set theme
//     const savedTheme = localStorage.getItem('theme') || 'light';
//     const icon = document.getElementById('themeIcon');
//     document.documentElement.setAttribute('data-theme', savedTheme);
//     if (icon) {
//         icon.className = savedTheme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
//     }

// });
