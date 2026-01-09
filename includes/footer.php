<footer class="contact-footer" id="contact">
  <div class="container">

    <!-- Foreground Heading -->
    <!-- <h2 class="footer-title">Contact <span>Us</span></h2> -->

    <!-- 🔹 Office Locations -->
    <div class="footer-grid">
      <div class="footer-box">
        &emsp;&emsp;<i class="bi bi-geo-alt-fill icon"></i>
        <h4>Lucknow Office</h4>
        <p>C-37 Alkapuri Colony, Aliganj, Lucknow - 226024, Uttar Pradesh</p>
        <p><i class="fas fa-phone-alt"></i> <a href="tel:+918004752804" class="call-link">+91 80047 52804</a></p>
      </div>

      <div class="footer-box">
        &emsp;&emsp;<i class="bi bi-geo-alt-fill icon"></i>
        <h4>Kanpur Office</h4>
        <p>1188 Vakil Nagar, Kalyanpur, Kanpur Nagar, Uttar Pradesh</p>
        <p><i class="fas fa-phone-alt"></i> <a href="tel:+916394365653" class="call-link">+91 63943 65653</a></p>
      </div>

      <div class="footer-box">
        &emsp;&emsp;<i class="bi bi-geo-alt-fill icon"></i>
        <h4>Gorakhpur Office</h4>
        <p>Betiyahata South, Near Falmandi No-31, Infront of Indian Oil Petrol Pump, Gorakhpur, Uttar Pradesh</p>
        <p><i class="fas fa-phone-alt"></i> <a href="tel:+919511150618" class="call-link">+91 95111 50618</a></p>
      </div>
    </div>

    <!-- 🔹 Quick Links, Products & Contact Info -->
    <div class="footer-grid">
      <div class="footer-box">
        <h4>Quick Menus</h4>
        <ul>
          <li><a href="index.php"> Home</a></li>
          <li><a href="about_us.php"> About Us</a></li>
          <li><a href="products.php"> Products</a></li>
          <li><a href="rental.php"> Services</a></li>
          <li><a href="contact_us.php"> Contact</a></li>
        </ul>
      </div>

      <div class="footer-box">
        <h4>Our Products</h4>
        <ul>
          
		  
		  
		  <?php
                 include('./RBMG_admin/config/db.php');
                      $sel = "SELECT * FROM categories WHERE status='1' order by id DESC limit 0,5";
                      $res = mysqli_query($conn,$sel);
                      while($product=mysqli_fetch_array($res,MYSQLI_BOTH))
                      {
                        
                ?>
			
			<li><a href="product_all.php?cat_id=<?php echo $product['id'] ?>" ><?php echo $product['cat_name'] ?></a></li>
          
                <?php
                      }
                      ?>
			
		  </ul>
		  
      </div>

      <div class="footer-box">
        <h4>Contact Information</h4>
        <p><i class="fas fa-phone-alt"></i>&nbsp; <a href="tel:+919511150618" class="call-link">+91 95111 50618</a></p>
        <p><i class="bi bi-envelope"></i> &nbsp; <a href="mailto:raebiomedglobal@gmail.com" class="mail-link">raebiomedglobal@gmail.com</a></p>
        <p><i class="bi bi-globe"></i>&nbsp; <a href="https://raebiomedglobal.com" target="_blank">raebiomedglobal.com</a></p>

        <div class="social-icons">
          <a href="#" class="social-icon"><i class="bi bi-facebook"></i></a>
          <a href="#" class="social-icon"><i class="bi bi-linkedin"></i></a>
          <a href="#" class="social-icon"><i class="bi bi-instagram"></i></a>
          <a href="#" class="social-icon"><i class="bi bi-youtube"></i></a>
        </div>
      </div>
    </div>

    <!-- 🔹 Footer Bottom -->
    <!-- <div class="footer-bottom">
      <div class="footer-left">
        <p><strong>Mail:</strong> info@raebiomedglobal.com</p>
        <p><strong>Website:</strong> www.raebiomedglobal.com</p>
        <p><strong>24 Hours Toll Free:</strong> 1800-8917-466</p>
      </div>
      <div class="footer-right">
        <img src="images/qr-code.png" alt="QR Code" class="qr-code">
      </div>
    </div> -->

    <div class="footer-copy">
      <p>&copy; <?= date('Y'); ?> RaeBioMed Global  | Developed & Designed by <a href="https://digicoders.in/" target="_blank">#Team DigiCoders</a></p>
    </div>
  </div>
</footer>



<!-- Floating WhatsApp Button -->
<a href="#" class="whatsapp-float" onclick="openWhatsApp(event)"><i class="bi bi-whatsapp"></i></a>

<a href="tel:+918004752804" class="phone-float" ><i class="bi bi-telephone"></i></a>

<!-- Back to Top Button -->
<button class="back-to-top" id="backToTop">&uarr;</button>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script src="js/script.js"></script>
<!-- Swiper JS CDN -->
<script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>


</body>

</html>