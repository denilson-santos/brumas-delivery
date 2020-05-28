<!-- <div class="restaurant-item"> -->
    <a href="<?php echo BASE_URL.'restaurant/open/'.$id_restaurant ?>">
    <div class="restaurant-tags">
        
        <?php
        if ($featured == 1) { 
            echo '
            <div class="restaurant-tag restaurant-tag-featured float-left">';
                $this->language->get('FEATURED');
            echo '
            </div>';
        }
 
        if ($new == 1) { 
            echo '
            <div class="restaurant-tag restaurant-tag-new float-left">';
                $this->language->get('NEW');
            echo '
            </div>';
        }
        
        if ($onsale == 1) { 
            echo '
            <div class="restaurant-tag restaurant-tag-onsale float-left">';
                $this->language->get('ONSALE');
            echo '
            </div>';
        }

        if ($top_rated == 1) { 
            echo '
            <div class="restaurant-tag restaurant-tag-top-rated float-left">';
                $this->language->get('TOPRATEDS');
            echo '
            </div>';
        }
        ?>
    </div> 
    <!-- <div class="restaurant-image">
        <img src="<?php echo BASE_URL.'media/restaurants/'.$images[0]['url'] ?>" alt="" width="100%">
    </div> -->
    <!-- <div class="restaurant-name"><?php echo $name ?></div> -->
    <!-- <div class="restaurant-brand"><?php echo $brand_name ?></div> -->
    <!-- <div class="row">
        <div class="col-sm-6">
            <div class="restaurant-price float-left <?php echo (empty($promo_price) ? 'current-price' : 'old-price'); ?>">
                <?php echo 'R$ '.number_format($price, 2, ',', '.'); ?>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="restaurant-promo-price current-price float-right">
                <?php echo (!empty($promo_price) ? 'R$ '.number_format($promo_price, 2, ',', '.') : ''); ?>
            </div>
        </div>
    </div> -->
    
    <div class="card">
        <img class="card-img-top" src="<?php echo BASE_URL.'media/restaurants/'.$image ?>" alt="Restaurant image">
        
        <div class="separator">
            <img class="restaurant-separator" src="<?php echo BASE_URL.'assets/images/restaurant-separator-white.png'?>" alt="">
        </div>

        <div class="card-body">
            <h5 class="card-title restaurant-name text-cc"><?php echo strlen($name) <= 22 ? $name : substr($name, 0, 21).'...' ?></h5>
            <h6 class="card-subtitle restaurant-main-categories"><?php echo implode(' & ', $main_categories_name) ?></h6>

            <div class="rating-restaurant-widget rating-restaurant-widget-<?php echo $id_restaurant ?> row justify-content-between align-items-center">
                <div class="col-md-7 pl-0 pr-1">
                    <div class="rating-read-only float-left"></div>
                    <span class="restaurant-rating restaurant-rating-<?php echo $id_restaurant ?> float-right"><?php echo number_format($rating, 1) ?></span>
                    <input type="hidden" class="id-restaurant" value="<?php echo $id_restaurant ?>">
                </div>

                <div class="col px-0 text-right">
                    <?php
                        if ($status == 1) {
                            echo '<div class="badge badge-pill badge-cc-green">Aberto</div>';
                        } else {
                            echo '<div class="badge badge-pill badge-cc-gray text-white">Fechado</div>';
                        }
                    ?>                    
                </div>
            </div>
        </div>
    </div>
    </a>
<!-- </div> -->
