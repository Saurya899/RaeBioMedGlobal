// ==================== TOGGLE MAIN MENU ====================
function toggleMenu() {
  const navMenu = document.getElementById("navMenu");
  navMenu.classList.toggle("active");
}

// ==================== TOGGLE SUBMENU ====================
function toggleSubmenu(event) {
  event.preventDefault();
  const submenu = event.target.nextElementSibling;

  // Close other open submenus (optional)
  document.querySelectorAll(".submenu.active").forEach(el => {
    if (el !== submenu) el.classList.remove("active");
  });

  submenu.classList.toggle("active");
  event.stopPropagation(); // prevent outside click close
}

// ==================== CLOSE MENU ON OUTSIDE CLICK ====================
document.addEventListener("click", function (event) {
  const navMenu = document.getElementById("navMenu");
  const mobileToggle = document.querySelector(".mobile-toggle");

  if (
    navMenu &&
    mobileToggle &&
    !navMenu.contains(event.target) &&
    !mobileToggle.contains(event.target)
  ) {
    navMenu.classList.remove("active");

    // also close submenus
    document.querySelectorAll(".submenu").forEach(sub => sub.classList.remove("active"));
  }
});

// ==================== SMOOTH SCROLL ====================
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener("click", function (e) {
    e.preventDefault();
    const target = document.querySelector(this.getAttribute("href"));
    if (target) {
      target.scrollIntoView({ behavior: "smooth", block: "start" });
      const navMenu = document.getElementById("navMenu");
      if (navMenu) navMenu.classList.remove("active");
    }
  });
});

// ==================== NAVBAR SCROLL EFFECT ====================
const navbar = document.getElementById("navbar");
window.addEventListener("scroll", function () {
  navbar.classList.toggle("scrolled", window.pageYOffset > 100);
});

// ==================== BACK TO TOP BUTTON ====================
const backToTopButton = document.getElementById("backToTop");
window.addEventListener("scroll", function () {
  backToTopButton.classList.toggle("visible", window.pageYOffset > 400);
});
backToTopButton.addEventListener("click", () => {
  window.scrollTo({ top: 0, behavior: "smooth" });
});

// ==================== WHATSAPP INTEGRATION ====================
function openWhatsApp(e) {
  e.preventDefault();
  const phoneNumber = "+918004752804";
  const message =
    "Hello RaeBioMedGlobal! I would like to inquire about your medical equipment.";
  window.open(`https://wa.me/${phoneNumber}?text=${encodeURIComponent(message)}`, "_blank");
}

// ==================== PRODUCT CARD ANIMATION ====================
const productObserver = new IntersectionObserver(
  function (entries) {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = "1";
        entry.target.style.transform = "translateY(0)";
      }
    });
  },
  { threshold: 0.1 }
);

document.querySelectorAll(".product-card").forEach(card => {
  card.style.opacity = "0";
  card.style.transform = "translateY(30px)";
  card.style.transition = "opacity 0.6s ease, transform 0.6s ease";
  productObserver.observe(card);
});

// ==================== CONSOLE MESSAGE ====================
console.log(
  "%c Welcome to RaeBioMedGlobal Healthtech! ",
  "background: linear-gradient(135deg, #0099cc, #006699); color: white; font-size: 20px; padding: 10px;"
);

// ==================== SWIPER SLIDER ====================
document.addEventListener("DOMContentLoaded", function () {
  new Swiper(".elementor-image-carousel-wrapper.swiper", {
    slidesPerView: 4,
    spaceBetween: 20,
    loop: true,
    autoplay: { delay: 3000, disableOnInteraction: false },
    speed: 500,
    pauseOnMouseEnter: true,
    grabCursor: true,
    breakpoints: {
      320: { slidesPerView: 1 },
      576: { slidesPerView: 2 },
      768: { slidesPerView: 3 },
      1024: { slidesPerView: 4 }
    }
  });
});

// ==================== FILE UPLOAD HANDLER ====================
document.getElementById("fileInput")?.addEventListener("change", function (e) {
  const fileName =
    e.target.files.length > 0 ? `${e.target.files.length} file(s) selected` : "No file chosen";
  this.parentElement.querySelector("p").textContent = `📁 ${fileName}`;
});

// ==================== FORM SUBMISSION HANDLER ====================
document.getElementById("sellEquipmentForm")?.addEventListener("submit", function (e) {
  e.preventDefault();
  alert("Thank you for your submission! We will contact you within 24 business hours.");
  this.reset();
  document.querySelector(".file-upload p").textContent = "📁 Choose File - No file chosen";
});

// ==================== SCROLL REVEAL ANIMATION ====================
const observerOptions = { threshold: 0.1, rootMargin: "0px 0px -100px 0px" };
const observer = new IntersectionObserver(entries => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.style.opacity = "1";
      entry.target.style.transform = "translateY(0)";
    }
  });
}, observerOptions);

document.querySelectorAll(".form-group").forEach(el => {
  el.style.opacity = "0";
  el.style.transform = "translateY(20px)";
  el.style.transition = "all 0.6s ease";
  observer.observe(el);
});

// ==================== FEATURE SECTION ANIMATIONS ====================
const statsObservers = new IntersectionObserver(entries => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      const el = entry.target;
      if (el.classList.contains("amc-animate-left")) {
        el.style.animation = "amc-fadeInLeft 1s ease forwards";
      } else if (el.classList.contains("amc-animate-right")) {
        el.style.animation = "amc-fadeInRight 1s ease forwards";
      }
    }
  });
}, observerOptions);

document.querySelectorAll(".amc-feature-text, .amc-feature-image").forEach(el => {
  observer.observe(el);
});

// ==================== STATS COUNTER ANIMATION ====================
document.addEventListener("DOMContentLoaded", function () {
  const statItems = document.querySelectorAll(".amc-stat-item");
  const statsObserver = new IntersectionObserver(
    function (entries) {
      entries.forEach(entry => {
        if (entry.isIntersecting && !entry.target.classList.contains("amc-counted")) {
          const numberEl = entry.target.querySelector(".amc-stat-number");
          const text = numberEl.textContent.trim();
          const numericValue = parseInt(text.replace(/[^0-9]/g, ""));
          const suffix = text.replace(/[0-9]/g, "");
          let current = 0;
          const duration = 1500;
          const stepTime = 20;
          const increment = numericValue / (duration / stepTime);

          const timer = setInterval(() => {
            current += increment;
            if (current >= numericValue) {
              numberEl.textContent = numericValue + suffix;
              clearInterval(timer);
            } else {
              numberEl.textContent = Math.floor(current) + suffix;
            }
          }, stepTime);

          entry.target.classList.add("amc-counted");
          entry.target.style.opacity = "1";
          entry.target.style.transform = "translateY(0)";
        }
      });
    },
    { threshold: 0.5 }
  );

  statItems.forEach(item => {
    item.style.opacity = "0";
    item.style.transform = "translateY(30px)";
    item.style.transition = "all 0.6s ease";
    statsObserver.observe(item);
  });
});

// ==================== PARALLAX HERO SECTION ====================
window.addEventListener("scroll", function () {
  const scrolled = window.pageYOffset;
  const hero = document.querySelector(".calibration-hero-section");
  if (hero && scrolled < hero.offsetHeight) {
    hero.style.transform = `translateY(${scrolled * 0.5}px)`;
  }
});


 // Simple animation on scroll
        document.addEventListener('DOMContentLoaded', function() {
            const benefitCards = document.querySelectorAll('.amc-benefit-card');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.animationPlayState = 'running';
                    }
                });
            }, { threshold: 0.1 });
            
            benefitCards.forEach(card => {
                card.style.animationPlayState = 'paused';
                observer.observe(card);
            });
        });