<div class="product-item">
    <a href="">
    <div class="product-tags">
        <?php 
        if (!empty($promo) && !empty($promo_price)) { 
            echo '
            <div class="product-tag product-tag-promotion pull-left">';
                $this->language->get('PROMOTION');
            echo '
            </div>';
        }
        ?>
        <?php
        if ($bestseller == 1) { 
            echo '
            <div class="product-tag product-tag-bestseller pull-left">';
                $this->language->get('BESTSELLER');
            echo '
            </div>';
        }
        ?>
        <?php
        if ($new == 1) { 
            echo '
            <div class="product-tag product-tag-new pull-left">';
                $this->language->get('NEW');
            echo '
            </div>';
        }
        ?>
    </div>
    <div class="product-image">
        <img src="<?php echo BASE_URL.'media/products/'.$images[0]['url'] ?>" alt="" width="100%">
    </div>
    <div class="product-name"><?php echo $name ?></div>
    <div class="product-brand"><?php echo $brand_name ?></div>
    <div class="row">
        <div class="col-sm-6">
            <div class="product-price pull-left <?php echo (empty($promo_price) ? 'current-price' : 'old-price'); ?>">
                <?php echo 'R$ '.number_format($price, 2, ',', '.'); ?>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="product-promo-price current-price pull-right">
                <?php echo (!empty($promo_price) ? 'R$ '.number_format($promo_price, 2, ',', '.') : ''); ?>
            </div>
        </div>
    </div>
    </a>
</div>
