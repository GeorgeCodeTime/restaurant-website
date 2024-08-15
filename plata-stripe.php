<?php

if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] === 'http://localhost/restaurant/checkout.php') {
    include('sessionstart.php');
    require 'vendor/autoload.php';

    $stripe_secret_key = "";

    \Stripe\Stripe::setApiKey($stripe_secret_key);

    $select_cantitate_client = "SELECT * FROM comenzi_temporare where idcont = '$idCont'";
    $result_select_cantitate_client = mysqli_query($conn, $select_cantitate_client);

    if (mysqli_num_rows($result_select_cantitate_client) > 0) {
        if ($result_select_cantitate_client) {
            while ($row = mysqli_fetch_assoc($result_select_cantitate_client)) {

                $total_price_temporary = $row["total_comanda"];
                $checkout_session = \Stripe\Checkout\Session::create([
                    "mode" => "payment",
                    "success_url" => "http://localhost/restaurant/finalizare-comanda-card.php",
                    "cancel_url" => "http://localhost/restaurant/checkout.php",
                    "payment_method_types" => ['card'],
                    "line_items" => [
                        [
                            "quantity" => 1,
                            "price_data" => [
                                "currency" => "ron",
                                "unit_amount" => $total_price_temporary * 100,
                                "product_data" => [
                                    "name" => "Total plata",
                                    "images" => ['https://millstick.ro/media/catalog/product/cache/1/image/9df78eab33525d08d6e5fb8d27136e95/-/w/-wall-sticker-pofta-buna-wbb022.jpg']
                                ]
                            ]
                        ]
                    ]
                ]);
            }
            http_response_code(303);
            header("Location: " . $checkout_session->url);
        }
    }
} else{
    header('Location: checkout.php');
}
?>