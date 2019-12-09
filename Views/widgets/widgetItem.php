<?php  
foreach ($listWidgets as $widget) {
    $defaultPriceClass = (empty($widget['promo_price']) ? 'current-price' : 'old-price');
    
    $price = 'R$ '.number_format($widget['price'], 2, ',', '.');
    $promoPrice = ($defaultPriceClass == 'old-price' ? 'R$ '.number_format($widget['promo_price'], 2, ',', '.') : ''); 
   
    echo '
        <div class="widget-item">
            <a href="'.BASE_URL.'plate/open/'.$widget['id_plate'].'">
                <div class="widget-info">
                    <div class="widget-plate-name">'.$widget['name'].'</div>
                    <div class="widget-plate-price"><span class="'.$defaultPriceClass.'">'.$price.'</span><span class="current-price">'.$promoPrice.'</span></div>
                </div>
                <div class="widget-image">
                    <img src="'.BASE_URL.'media/plates/'.$widget['images'][0]['url'].'" alt="" width="80">
                </div>
                <div class="clear"></div>
            </a>
        </div>
    ';
}
?>