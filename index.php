<?php
    require 'operations.php';
    $productsFile = fopen('products.txt', 'r');

    while (!feof($productsFile)) {
        $productsContent = fgets($productsFile);
        $productsArray = explode(";", $productsContent);
        if (empty($productsArray[4])) {
            echo "Txt file doesnt contain enough data please check the file and try again" . PHP_EOL;
            continue;
        }
        list($identifier, $name, $quantity, $price, $currency) = $productsArray;
        createProduct($identifier, $name, $quantity, $price, $currency);
    }
?>
