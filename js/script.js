 // Toggle Mobile Menu
        function toggleMenu() {
            const navMenu = document.getElementById('navMenu');
            navMenu.classList.toggle('active');
        }

        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            const navMenu = document.getElementById('navMenu');
            const mobileToggle = document.querySelector('.mobile-toggle');
            
            if (navMenu && mobileToggle && !navMenu.contains(event.target) && !mobileToggle.contains(event.target)) {
                navMenu.classList.remove('active');
            }
        });

        // Smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                    const navMenu = document.getElementById('navMenu');
                    if (navMenu) navMenu.classList.remove('active');
                }
            });
        });

        // Navbar scroll effect
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 100) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Back to Top Button
        const backToTopButton = document.getElementById('backToTop');
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 400) {
                backToTopButton.classList.add('visible');
            } else {
                backToTopButton.classList.remove('visible');
            }
        });

        backToTopButton.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // WhatsApp integration
        function openWhatsApp(e) {
            e.preventDefault();
            const phoneNumber = '919990000000';
            const message = 'Hello RaeBioMedGlobal Healthtech! I would like to inquire about your medical equipment.';
            window.open(`https://wa.me/${phoneNumber}?text=${encodeURIComponent(message)}`, '_blank');
        }

        // Product card animation
        const productObserver = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.product-card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            productObserver.observe(card);
        });

        // Console message
        console.log('%c Welcome to RaeBioMedGlobal Healthtech! ', 'background: linear-gradient(135deg, #0099cc, #006699); color: white; font-size: 20px; padding: 10px;');
    // for slider 
    document.addEventListener("DOMContentLoaded", function () {
    new Swiper('.elementor-image-carousel-wrapper.swiper', {
        slidesPerView: 4,            // ek baar me 4 image dikhaye
        spaceBetween: 20,            // images ke beech gap
        loop: true,                  // infinite loop
        autoplay: {
            delay: 3000,             // har 3 sec me slide change
            disableOnInteraction: false,
        },
        speed: 500,                  // slide transition speed (ms)
        pauseOnMouseEnter: true,     // hover par pause ho jaye
        grabCursor: true,            // mouse pointer hand ban jaye
        breakpoints: {               // responsive view
            320: { slidesPerView: 1 },
            576: { slidesPerView: 2 },
            768: { slidesPerView: 3 },
            1024: { slidesPerView: 4 }
        }
    });
});