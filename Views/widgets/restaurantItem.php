<div class="restaurant-item">
    <a href="<?php echo BASE_URL.'restaurant/open/'.$id_restaurant ?>">
    <div class="restaurant-tags">
        <?php 
        if (!empty($promo) && !empty($promo_price)) { 
            echo '
            <div class="restaurant-tag restaurant-tag-promotion float-left">';
                $this->language->get('PROMOTION');
            echo '
            </div>';
        }
        ?>
        <?php
        if ($bestseller == 1) { 
            echo '
            <div class="restaurant-tag restaurant-tag-bestseller float-left">';
                $this->language->get('BESTSELLER');
            echo '
            </div>';
        }
        ?>
        <?php
        if ($new == 1) { 
            echo '
            <div class="restaurant-tag restaurant-tag-new float-left">';
                $this->language->get('NEW');
            echo '
            </div>';
        }
        ?>
    </div>
    <div class="restaurant-image">
        <img src="<?php echo BASE_URL.'media/restaurants/'.$images[0]['url'] ?>" alt="" width="100%">
    </div>
    <div class="restaurant-name"><?php echo $name ?></div>
    <div class="restaurant-brand"><?php echo $brand_name ?></div>
    <div class="row">
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
    </div>
    </a>
</div>
