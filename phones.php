<?php

    require("includes/config.php");

    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // else render form
        render("mobiles_form.php", ["title" => "Mobile Selection"]);
    }
    
    // else if user reached page via POST (as by submitting a form via POST)
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if (empty($_POST["phone1"]) || empty($_POST["phone2"])) {
            apologize("Please select two phones.");
        };
        try {
            //get the device specs data
            $results = $fonoapi::getDevice($_POST["phone1"]);
            foreach ($results as $result) {
                if ($result->DeviceName == $_POST["phone1"]) {
                    $list[0] = $result;
                    break;
                };
            };
            
            $results = $fonoapi::getDevice($_POST["phone2"]);
            foreach ($results as $result) {
                if ($result->DeviceName == $_POST["phone2"]) {
                    $list[1] = $result;
                    break;
                };
            };
            
        } catch (Exception $e) {
            echo "ERROR : " . $e->getMessage();
        };

        $_SESSION["DeviceName"] = [$list[0]->DeviceName,$list[1]->DeviceName];
        $_SESSION["Price"]["data"] = comparePrice($list);
        $_SESSION["Camera"]["data"] = compareCamera($list);
        $_SESSION["Performance"]["data"] = comparePerformance($list);
        $_SESSION["Modernity"]["data"] = compareAge($list);
        $_SESSION["Battery"]["data"] = compareBattery($list);
        $_SESSION["Size_&_Weight"]["data"] = compareSizeWeight($list);
        $_SESSION["Resolution"]["data"] = compareResolution($list);
        $_SESSION["Storage"]["data"] = compareStorage($list);
        $_SESSION["Internet_Speed"]["data"] = compareInternet($list);
        $_SESSION["Brand"]["data"] = compareBrand($list,$_SESSION["Brand1"]["preference"]);

        echo '<script>var msg = '.json_encode($_SESSION).';</script>'; 

        //move from session to javascript
        render("result_form.php", ["title" => "Result"]);

    }
?>