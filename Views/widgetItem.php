<?php  
foreach ($listWidgets as $widget) {
    $defaultPrice = (empty($widget['promo_price']) ? 'widget-current-price' : 'old-price');
    
    $price = 'R$ '.number_format($widget['price'], 2, ',', '.');
    $promoPrice = ($defaultPrice == 'old-price' ? 'R$ '.number_format($widget['promo_price'], 2, ',', '.') : ''); 
   
    echo '
        <div class="widget-item">
            <a href="#">
                <div class="widget-info">
                    <div class="widget-product-name">'.$widget['name'].'</div>
                    <div class="widget-product-price"><span class="'.$defaultPrice.'">'.$price.'</span><span class="widget-current-price">'.$promoPrice.'</span></div>
                </div>
                <div class="widget-image">
                    <img src="'.BASE_URL.'media/products/'.$widget['images'][0]['url'].'" alt="" width="80">
                </div>
                <div class="clear"></div>
            </a>
        </div>
    ';
}
?>