<?php

    function getProduct(){
        return json_decode(file_get_contents(__DIR__.'/products.json'), true);
    }

    function createProduct($identifier, $name, $quantity, $price, $currency){
        $products = getProduct();
        $data['identifier'] = $identifier;
        $data['name'] = $name;
        $data['quantity'] = $quantity;
        $data['price'] = $price;
        $data['currency'] = $currency;
        $data['currency'] = str_replace("\n", "", $data['currency']);
        $data['currency'] = strtoupper($data['currency']);
        if ($data['currency'] == 'USD') {
            $data['price'] = $data['price'] / 1.14;
            $data['currency'] = 'EUR';
        }
        if ($data['currency'] == 'GBP') {
            $data['price'] = $data['price'] / 0.88;
            $data['currency'] = 'EUR';
        }
        

        //identifier validation
        if (!preg_match("#^[a-zA-Z0-9]+$#", $data['identifier'])){
            echo "Identifier can only contain letters or numbers". PHP_EOL;
            return;
        }
        //identifier validation end


        //quantity validation
        if (!is_numeric($data['quantity'])){
            echo "Quantity can only contain negative or positive numbers". PHP_EOL;
            return;
        }
        //quantity validation end


        //price validations
        if ($data['price'] < 0) {
            echo "Price input cant be negative" . PHP_EOL;
            return;
        }
        if (!is_numeric($data['price'])) {
            echo "Price input is invalid" . PHP_EOL;
            return;
        }
        //price validations end


        //currency validations
        if ($data['currency'] != 'USD' && $data['currency'] != 'GBP' && 
            $data['currency'] != 'EUR') {
            echo "currency input is invalid supported currencies: EUR, USD, GBP" . PHP_EOL;
            return;
        }
        //currency validations end


        //update
        if (!empty($products)) {
            foreach ($products as $key => $product) {
                if ($data['identifier'] == $product['identifier']) {

                    //remove
                    if ($data['quantity'] < 0) {
                        array_splice($products, $key, 1);
                        cartsTotal($products);
                        file_put_contents(__DIR__.'/products.json', json_encode($products));
                        return;
                    }
                    //end of remove

                    $data['quantity'] += $product['quantity'];
                    $products[$key] = array_merge($product, $data);
                    cartsTotal($products);
                    file_put_contents(__DIR__.'/products.json', json_encode($products));
                    return;
                }
            }
        }
        //end of update


        //clear unnecessary data
        if ($data['quantity'] < 0) {
            array_splice($data, 1);
            cartsTotal($products);
            return;
        }
        //end of clearing


        $products[] = $data;
        cartsTotal($products);
        file_put_contents(__DIR__.'/products.json', json_encode($products));
        return;
    }

    function cartsTotal($products){
        $total = 0;
        if (!empty($products)) {
            foreach ($products as $product) {
                $total += $product['quantity'] * $product['price'];
            }
        }
        echo "Total cart price: ". number_format($total, 2, ',', ' ') . ' EUR' . PHP_EOL;
        return;
    }

?>
