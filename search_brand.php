<?php

    require("includes/config.php");

    //obtain the mobile specs data from fonoapi
    try {
        $result = $fonoapi::getDevice($_GET["device"]);
    } catch (Exception $e) {
        apologize($e->getMessage());
    };
    
    $output = [];
    
    //look into each result from fonoapi and see if brand of phone matches what was input
    foreach ($result as $result_object) {
        $test = true;
        $result_brand = $result_object->Brand;
        foreach ($output as $output_object) {
            if ($output_object["Brand"] == $result_brand) {
                $test = false;
            }
        }
        if ($test) {
            $output[] = ["Brand" => $result_brand];
        }
    }
    
    // output places as JSON (pretty-printed for debugging convenience)
    header("Content-type: application/json");
    print(json_encode($output, JSON_PRETTY_PRINT));


?>