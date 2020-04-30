<?php  
foreach ($listWidgets as $widget) {
    // $defaultPriceClass = (empty($widget['promo_price']) ? 'current-price' : 'old-price');
    
    // $price = 'R$ '.number_format($widget['price'], 2, ',', '.');
    // $promoPrice = ($defaultPriceClass == 'old-price' ? 'R$ '.number_format($widget['promo_price'], 2, ',', '.') : ''); 
   
    echo '
        <div class="restaurant-item-small">
            <a href="'.BASE_URL.'restaurant/open/'.$widget['id_restaurant'].'">
                <div class="widget-image col-md-5">
                    <img src="'.BASE_URL.'media/restaurants/'.$widget['image'].'" alt="" width="80">
                </div>
                
                <div class="widget-info col">
                    <div class="widget-restaurant-name">'.$widget['name'].'</div>
                    <div class="widget-restaurant-rating">
                        <div class="rating-restaurant-widget-small rating-read-only float-left"></div>
                        <span class="restaurant-rating-widget float-right">4.1</span>
                    </div>
                </div>
            </a>
        </div>
    ';
}
?>