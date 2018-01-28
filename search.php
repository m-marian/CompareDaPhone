<?php

    require("includes/config.php");

    try {
        $result = $fonoapi::getDevice($_GET["device"]);
    } catch (Exception $e) {
        apologize($e->getMessage());
    };

    // output places as JSON (pretty-printed for debugging convenience)
    header("Content-type: application/json");
    print(json_encode($result, JSON_PRETTY_PRINT));


?>