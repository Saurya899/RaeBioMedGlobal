<?php
include('./RBMG_admin/config/db.php');

// Get category ID
$cat_id = isset($_GET['cat_id']) ? $_GET['cat_id'] : 0;

// Fetch category name
$cat_query = mysqli_query($conn, "SELECT cat_name FROM categories WHERE id='$cat_id'");
$cat_row = mysqli_fetch_assoc($cat_query);
$category_name = $cat_row['cat_name'] ?? "All Products";

// Fetch products for selected category
$product_query = mysqli_query($conn, "SELECT * FROM products WHERE category_id='$cat_id'");
//count product 
$product_count = mysqli_num_rows($product_query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Products | RaeBioMedGlobal Healthtech - Leading Medical Equipment Supplier in India</title>

    <?php include("includes/header.php") ?>
    <!-- Breadcrumb -->

    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="product-details-breadcrumb breadcrumb">
                <li class="product-details-breadcrumb-item breadcrumb-item"><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
                <li class="product-details-breadcrumb-item breadcrumb-item acive" aria-current="page">Products</li>

            </ol>
        </nav>

        <a href="products.php" class="product-details-back-button">
            <i class="fas fa-arrow-left"></i> Back to Products
        </a>
    </div>
    <div class="container my-4">

        <!-- Search Box -->
        <div class="products-search-container">
            <div class="products-search-box">
                <input type="text" class="products-search-input" id="productsSearchInput" placeholder="Search by product name or price...">
                <button class="products-search-button" id="productsSearchButton">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="row">
            <div class="col-12">
                <!-- Top Controls -->
                <div class="products-top-controls">
                    <div class="products-results-info" id="productsResultsInfo">
                        <i class="fas fa-th"></i> Showing 1-<?php echo $product_count  ?> of <?php echo $product_count  ?> results
                    </div>

                </div>

                <!-- Product Grid -->
                <div class="products-grid" id="productsGrid">
                    <!-- Product  -->
                    <?php

                    while ($product = mysqli_fetch_assoc($product_query)) {
                    ?>
					
                        <div onclick="window.location.href='product_details.php?product_id=<?php echo $product["id"] ?>' " class="products-card" data-name="<?php echo $product['name'] ?>" data-price="<?php echo $product['price'] ?>">
                            <div class="products-image">
                                <img src="RBMG_admin/assets/images/products/<?php echo $product['image'] ?>" alt="<?php echo $product['name'] ?>">

                            </div>
                            <div class="products-info">
                                <h3 class="products-title"><?php echo $product['name'] ?></h3>
                                <div class="d-none products-price">&#8377;<?php echo $product['price'] ?></div>
                                <a href="product_details.php?product_id=<?php echo $product['id'] ?>" class="products-detail-btn" style="text-decoration: none;">View Details</a>
                            </div>
                        </div>
                    <?php
                    }
                    ?>

                </div>


            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // ======== Product Search Functionality ========
        const productsSearchInput = document.getElementById('productsSearchInput');
        const productsSearchButton = document.getElementById('productsSearchButton');
        const productsGrid = document.getElementById('productsGrid');
        const productsResultsInfo = document.getElementById('productsResultsInfo');
        const productsCards = document.querySelectorAll('.products-card');

        function performSearch() {
            const searchTerm = productsSearchInput.value.toLowerCase().trim();
            let visibleCount = 0;

            // Restore original grid content if cleared
            if (searchTerm === '') {
                productsCards.forEach(card => card.style.display = 'block');
                productsResultsInfo.innerHTML = `<i class="fas fa-th"></i> Showing ${productsCards.length} results`;
                return;
            }

            productsCards.forEach(card => {
                const productName = (card.getAttribute('data-name') || '').toLowerCase();
                const productPrice = (card.getAttribute('data-price') || '').toLowerCase();

                // Match name or price
                if (productName.includes(searchTerm) || productPrice.includes(searchTerm.replace(/,/g, ''))) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Update results info
            productsResultsInfo.innerHTML = `<i class="fas fa-th"></i> Showing ${visibleCount} of ${productsCards.length} results`;

            // Show "No results" message
            if (visibleCount === 0) {
                productsGrid.innerHTML = `
                <div class="products-no-results text-center" style="padding:50px;">
                    <i class="fas fa-search fa-3x mb-3 text-muted"></i>
                    <h3>No products found</h3>
                    <p>Try adjusting your search terms</p>
                </div>
            `;
            }
        }

        // 🔍 Real-time filtering as user types
        productsSearchInput.addEventListener('input', performSearch);

        // 🔘 Search button click
        productsSearchButton.addEventListener('click', performSearch);

        // ⌨️ Enter key triggers search too
        productsSearchInput.addEventListener('keyup', event => {
            if (event.key === 'Enter') performSearch();
        });
    </script>

    <?php include("includes/footer.php") ?>