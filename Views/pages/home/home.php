<h1>Resultados encontrados para "<?php echo (!empty($categoryName) ? $categoryName : 'Todas as categorias'); ?>":</h1>
<div class="row restaurants">
<?php

foreach ($restaurants as $restaurant) {
    $mainCategories = explode(',', $restaurant['main_categories']);

    foreach ($mainCategories as $mainCategory) {
        $restaurant['main_categories_name'][] =  $categories[$mainCategory-1]['name'];
    }

    echo "
    <div class='col-sm-4 px-1 pb-2 restaurant-item'>";
        $this->loadView('widgets/restaurantItem', $restaurant);
    echo " 
    </div>";
}

?>

</div>

<nav aria-label="pagination">
  <ul class="pagination">
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

        echo "
        <li class='page-item $active'><a class='page-link' href='".BASE_URL."?$url"."'>$p</a></li>
        ";
    }
    ?>
    </ul>
</nav>
    