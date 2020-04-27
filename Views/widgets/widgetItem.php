<?php  
foreach ($listWidgets as $widget) {
    // $defaultPriceClass = (empty($widget['promo_price']) ? 'current-price' : 'old-price');
    
    // $price = 'R$ '.number_format($widget['price'], 2, ',', '.');
    // $promoPrice = ($defaultPriceClass == 'old-price' ? 'R$ '.number_format($widget['promo_price'], 2, ',', '.') : ''); 
   
    echo '
        <div class="widget-item">
            <a href="'.BASE_URL.'restaurant/open/'.$widget['id_restaurant'].'">
                <div class="widget-info">
                    <div class="widget-restaurant-name">'.$widget['name'].'</div>
                    <div class="widget-restaurant-rating">
                        <div class="col-md-7 pl-0 pr-1">
                            <div class="rating-page-home-widget rating-read-only float-left"></div>
                            <span class="restaurant-rating-widget float-right">4.1</span>
                        </div>
                    </div>
                </div>
                <div class="widget-image">
                    <img src="'.BASE_URL.'media/restaurants/'.$widget['image'].'" alt="" width="80">
                </div>
                <div class="clear"></div>
            </a>
        </div>
    ';
}
?>