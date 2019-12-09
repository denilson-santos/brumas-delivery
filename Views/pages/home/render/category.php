<h1>Resultados encontrados para "<?php echo $categoryName; ?>":</h1>
<div class="row">
<?php
$count = 0;

foreach ($products as $product) {
    echo '
    <div class="col-sm-4">';
        $this->loadView('widgets/productItem', $product);
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

<?php
for ($p=1; $p <= $numberPages; $p++) { 
    if ($currentPage == $p) {
        $active = 'pagination-active';
    } else {
        $active = '';
    }
    echo "
        <div class='pagination pagination-item $active'><a href='".BASE_URL."category/enter/$categoryId/?p=$p"."'>$p</a></div>
    ";
}

    
