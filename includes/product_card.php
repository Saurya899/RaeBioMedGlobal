 <div class="product-grid">
                <?php
                 include('./RBMG_admin/config/db.php');
                      $sel = "SELECT * FROM categories WHERE status='1' order by id DESC";
                      $res = mysqli_query($conn,$sel);
                      while($product=mysqli_fetch_array($res,MYSQLI_BOTH))
                      {
                        
                ?>
                <div class="product-card">
                    <a href="product_all.php?cat_id=<?php echo $product['id'] ?>"><img src="./RBMG_admin/assets/images/categories/<?php echo $product['image'] ?>" alt="<?php echo $product['cat_name'] ?>"></a>
                    <a href="product_all.php?cat_id=<?php echo $product['id'] ?>"><h3><?php echo $product['cat_name'] ?></h3></a>
                </div>
                <?php
                      }
                      ?>
                
            </div>