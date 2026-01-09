<!DOCTYPE html>
<html lang="en">

<head>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Primary Meta Tags -->
        <title>RaeBioMedGlobal  - Leading Medical Equipment Supplier in India</title>
        <?php include("includes/header.php") ?>

        <!-- Banner Section -->
        <section id="home" class="banner-section">
            <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-sride="carousel" data-bs-interval="3000">
                <div class="carousel-inner">

                   <?php
        include('./RBMG_admin/config/db.php');
        $carousel_query = "SELECT * FROM carousel WHERE status = 'active' ORDER BY id ASC";
        $carousel_result = mysqli_query($conn, $carousel_query);
         while ($carousel = mysqli_fetch_assoc($carousel_result)) {
        ?>
                    <!-- Slide 1 -->
                    <div class="carousel-item active">
                        <img src="./RBMG_admin/assets/images/carousel/<?php echo $carousel['image'] ?>" class="d-block w-100 banner-img" alt="Healthcare Products">
                        <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                            <h1 class="display-5 fw-bold text-white"><?php echo $carousel['heading1'] ?></h1>
                            <p class="lead"><?php echo $carousel['heading2'] ?></p>
                            <a href="products.php" class="btn btn-outline-light btn-lg rounded-pill px-4 mt-3"><?php echo $carousel['button_name'] ?></a>
                        </div>
                    </div>
                    <?php } ?>

                  

                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        </section>


        <!-- About Section -->
        <section class="about" id="about">
            <div class="container-custom">
                <div class="about-content">
                    <div class="about-image-wrapper">
                        <img src="assets/Images/about-us.jpg" alt="Medical Equipment" class="about-image">
                    </div>
                    <div class="about-text">
                        <h2>About Us</h2>
                        <p>RaeBioMedGlobal, headquartered in Lucknow, Uttar Pradesh, working with a vision to revolutionize the healthcare industry. As an independent Company, we have emerged as a leading contributor and partner in the private hospital setup in India, with a comprehensive range of Hospital Furniture.</p>
                        <p>Our company is committed to delivering the highest quality of products, adhering to international standards that exceed the highest industry standards. Our dedication to maintaining premium quality is reflected in every piece of equipment we manufacture. This unwavering commitment to excellence has enabled us to earn the trust and confidence of healthcare professionals and institutions across the country.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Products Section -->
        <section class="products" id="products">
            <div class="container-custom">
                <h2>Our Products</h2>

                <!-- product card  -->
                <?php include("./includes/product_card.php") ?>

            </div>
        </section>

        <!-- Partners Section -->
        <section class="partners">
            <div class="elementor-element elementor-element-d3ef69b e-flex e-con-boxed e-con e-parent" data-id="d3ef69b" data-element_type="container">
                <div class="e-con-inner">
                    <div class="elementor-element elementor-element-ac15ae1 elementor-widget elementor-widget-image-carousel" data-id="ac15ae1" data-element_type="widget" data-settings="{&quot;slides_to_show&quot;:&quot;4&quot;,&quot;navigation&quot;:&quot;none&quot;,&quot;autoplay_speed&quot;:3000,&quot;autoplay&quot;:&quot;yes&quot;,&quot;pause_on_hover&quot;:&quot;yes&quot;,&quot;pause_on_interaction&quot;:&quot;yes&quot;,&quot;infinite&quot;:&quot;yes&quot;,&quot;speed&quot;:500}" data-widget_type="image-carousel.default">
                        <div class="elementor-widget-container">
                            <div class="elementor-image-carousel-wrapper swiper" role="region" aria-roledescription="carousel" aria-label="Image Carousel" dir="ltr">
                                <div class="elementor-image-carousel swiper-wrapper" aria-live="off">
                                    <div class="swiper-slide" role="group" aria-roledescription="slide" aria-label="1 of 25">
                                        <figure class="swiper-slide-inner">
                                            <img decoding="async" class="swiper-slide-image" src="assets/Images/MAQUET.jpg" alt="MAQUET" />
                                        </figure>
                                    </div>
                                    <div class="swiper-slide" role="group" aria-roledescription="slide" aria-label="2 of 25">
                                        <figure class="swiper-slide-inner">
                                            <img decoding="async" class="swiper-slide-image" src="assets/Images/STOKERT.jpg" alt="STOKERT" />
                                        </figure>
                                    </div>
                                    <div class="swiper-slide" role="group" aria-roledescription="slide" aria-label="3 of 25">
                                        <figure class="swiper-slide-inner">
                                            <img decoding="async" class="swiper-slide-image" src="assets/Images/DATASCOPE.jpg" alt="DATASCOPE" />
                                        </figure>
                                    </div>
                                    <div class="swiper-slide" role="group" aria-roledescription="slide" aria-label="4 of 25">
                                        <figure class="swiper-slide-inner">
                                            <img decoding="async" class="swiper-slide-image" src="assets/Images/PHILIPS.jpg" alt="PHILIPS" />
                                        </figure>
                                    </div>
                                    <div class="swiper-slide" role="group" aria-roledescription="slide" aria-label="5 of 25">
                                        <figure class="swiper-slide-inner">
                                            <img decoding="async" class="swiper-slide-image" src="assets/Images/PHILIPS-1.jpg" alt="PHILIPS (1)" />
                                        </figure>
                                    </div>
                                    <div class="swiper-slide" role="group" aria-roledescription="slide" aria-label="6 of 25">
                                        <figure class="swiper-slide-inner">
                                            <img decoding="async" class="swiper-slide-image" src="assets/Images/MEDTRONIC.jpg" alt="MEDTRONIC" />
                                        </figure>
                                    </div>
                                    <div class="swiper-slide" role="group" aria-roledescription="slide" aria-label="7 of 25">
                                        <figure class="swiper-slide-inner">
                                            <img decoding="async" class="swiper-slide-image" src="assets/Images/SORIN.jpg" alt="SORIN" />
                                        </figure>
                                    </div>
                                    <div class="swiper-slide" role="group" aria-roledescription="slide" aria-label="8 of 25">
                                        <figure class="swiper-slide-inner">
                                            <img decoding="async" class="swiper-slide-image" src="assets/Images/HEMOTHERM.jpg" alt="HEMOTHERM" />
                                        </figure>
                                    </div>
                                    <div class="swiper-slide" role="group" aria-roledescription="slide" aria-label="9 of 25">
                                        <figure class="swiper-slide-inner">
                                            <img decoding="async" class="swiper-slide-image" src="assets/Images/HEMOTHERM-1.jpg" alt="HEMOTHERM (1)" />
                                        </figure>
                                    </div>
                                    <div class="swiper-slide" role="group" aria-roledescription="slide" aria-label="10 of 25">
                                        <figure class="swiper-slide-inner">
                                            <img decoding="async" class="swiper-slide-image" src="assets/Images/ERBE.jpg" alt="ERBE" />
                                        </figure>
                                    </div>
                                    <div class="swiper-slide" role="group" aria-roledescription="slide" aria-label="11 of 25">
                                        <figure class="swiper-slide-inner">
                                            <img decoding="async" class="swiper-slide-image" src="assets/Images/COVIDIEN.jpg" alt="COVIDIEN" />
                                        </figure>
                                    </div>
                                    <div class="swiper-slide" role="group" aria-roledescription="slide" aria-label="12 of 25">
                                        <figure class="swiper-slide-inner">
                                            <img decoding="async" class="swiper-slide-image" src="assets/Images/Johnson-Johnson.jpg" alt="Johnson &amp; Johnson" />
                                        </figure>
                                    </div>
                                    <div class="swiper-slide" role="group" aria-roledescription="slide" aria-label="13 of 25">
                                        <figure class="swiper-slide-inner">
                                            <img decoding="async" class="swiper-slide-image" src="assets/Images/DRAGER.jpg" alt="DRAGER" />
                                        </figure>
                                    </div>
                                    <div class="swiper-slide" role="group" aria-roledescription="slide" aria-label="14 of 25">
                                        <figure class="swiper-slide-inner">
                                            <img decoding="async" class="swiper-slide-image" src="assets/Images/VIAYSIS.jpg" alt="VIAYSIS" />
                                        </figure>
                                    </div>
                                    <div class="swiper-slide" role="group" aria-roledescription="slide" aria-label="15 of 25">
                                        <figure class="swiper-slide-inner">
                                            <img decoding="async" class="swiper-slide-image" src="assets/Images/CAREFUSION.jpg" alt="CAREFUSION" />
                                        </figure>
                                    </div>
                                    <div class="swiper-slide" role="group" aria-roledescription="slide" aria-label="16 of 25">
                                        <figure class="swiper-slide-inner">
                                            <img decoding="async" class="swiper-slide-image" src="assets/Images/ASSEMBLED.jpg" alt="ASSEMBLED" />
                                        </figure>
                                    </div>
                                    <div class="swiper-slide" role="group" aria-roledescription="slide" aria-label="17 of 25">
                                        <figure class="swiper-slide-inner">
                                            <img decoding="async" class="swiper-slide-image" src="assets/Images/BYOND.jpg" alt="BYOND" />
                                        </figure>
                                    </div>
                                    <div class="swiper-slide" role="group" aria-roledescription="slide" aria-label="18 of 25">
                                        <figure class="swiper-slide-inner">
                                            <img decoding="async" class="swiper-slide-image" src="assets/Images/ZONCARE.jpg" alt="ZONCARE" />
                                        </figure>
                                    </div>
                                    <div class="swiper-slide" role="group" aria-roledescription="slide" aria-label="19 of 25">
                                        <figure class="swiper-slide-inner">
                                            <img decoding="async" class="swiper-slide-image" src="assets/Images/MICOME.jpg" alt="MICOME" />
                                        </figure>
                                    </div>
                                    <div class="swiper-slide" role="group" aria-roledescription="slide" aria-label="20 of 25">
                                        <figure class="swiper-slide-inner">
                                            <img decoding="async" class="swiper-slide-image" src="assets/Images/FP-ASSEMBLED.jpg" alt="F&amp;P ASSEMBLED" />
                                        </figure>
                                    </div>
                                    <div class="swiper-slide" role="group" aria-roledescription="slide" aria-label="21 of 25">
                                        <figure class="swiper-slide-inner">
                                            <img decoding="async" class="swiper-slide-image" src="assets/Images/STRYKER.jpg" alt="STRYKER" />
                                        </figure>
                                    </div>
                                    <div class="swiper-slide" role="group" aria-roledescription="slide" aria-label="22 of 25">
                                        <figure class="swiper-slide-inner">
                                            <img decoding="async" class="swiper-slide-image" src="assets/Images/OLYMPUS.jpg" alt="OLYMPUS" />
                                        </figure>
                                    </div>
                                    <div class="swiper-slide" role="group" aria-roledescription="slide" aria-label="23 of 25">
                                        <figure class="swiper-slide-inner">
                                            <img decoding="async" class="swiper-slide-image" src="assets/Images/KARL-STORZ.jpg" alt="KARL STORZ" />
                                        </figure>
                                    </div>
                                    <div class="swiper-slide" role="group" aria-roledescription="slide" aria-label="24 of 25">
                                        <figure class="swiper-slide-inner">
                                            <img decoding="async" class="swiper-slide-image" src="assets/Images/FUJINON.jpg" alt="FUJINON" />
                                        </figure>
                                    </div>
                                    <div class="swiper-slide" role="group" aria-roledescription="slide" aria-label="25 of 25">
                                        <figure class="swiper-slide-inner">
                                            <img decoding="async" class="swiper-slide-image" src="assets/Images/TERUMO.jpg" alt="TERUMO" />
                                        </figure>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>

        <!-- =================== FOOTER SECTION =================== -->
        <?php include("includes/footer.php") ?>