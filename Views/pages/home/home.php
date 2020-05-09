<h1>Resultados encontrados para "<?php echo (!empty($categoryName) ? $categoryName : 'Todas as categorias'); ?>":</h1>
<div class="row restaurants">
<?php

foreach ($restaurants as $key => $restaurant) {
    $mainCategories = explode(',', $restaurant['main_categories']);

    foreach ($mainCategories as $mainCategory) {
        if ($mainCategory == $categories[$mainCategory-1]['id_category']) {
            $restaurant['main_categories_name'][] = $categories[$mainCategory-1]['name'];
        }
    }

    foreach ($restaurantsOpen as $restaurantOpen) {
        if ($restaurantOpen['restaurant_id'] == $restaurant['id_restaurant']) {
            $restaurant['status'] = 1;
        }
    }

    foreach ($restaurantsClosed as $restaurantClosed) {
        if ($restaurantClosed['restaurant_id'] == $restaurant['id_restaurant']) {
            $restaurant['status'] = 0;
        }
    }

    $restaurant['onsale'] = 0;
    foreach ($restaurantsInPromotion as $restaurantInPromotion) {
        if ($restaurantInPromotion['id_restaurant'] == $restaurant['id_restaurant']) {
            $restaurant['onsale'] = 1;
        }
    }

    // print_r($restaurant);

    echo "
    <div class='col-sm-4 px-2 pb-3 restaurant-item'>";
        $this->loadView('widgets/restaurantItem', $restaurant);
    echo " 
    </div>";
}
?>

</div>

<nav aria-label="pagination">
  <ul class="pagination ml-1">
    <?php
    for ($p=1; $p <= $numberPages; $p++) { 
        if ($currentPage == $p) {
            $active = 'active';
        } else {
            $active = '';
        }

        $url = $_GET;
        $url['p'] = $p;
        $url = http_build_query($url);

        $colorPagination = empty($active) ? 'text-dark' : ''; 

        echo "
        <li class='page-item $active'><a class='page-link $colorPagination' href='".BASE_URL."?$url"."'>$p</a></li>
        ";
    }
    ?>
    </ul>
</nav>
    