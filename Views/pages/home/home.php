<h1>Resultados encontrados para "<?php echo (!empty($categoryName) ? $categoryName : 'Todas as categorias'); ?>":</h1>
<div class="row">
<?php
$count = 0;

foreach ($restaurants as $restaurant) {
    echo '
    <div class="col-sm-4">';
        $this->loadView('widgets/restaurantItem', $restaurant);
    echo ' 
    </div>';

    if ($count >= 2) {
        $count = 0;
        echo '</div><div class="row">';
    } else {
        $count++;
    }
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
    