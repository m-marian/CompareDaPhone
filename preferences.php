<?php

    require("includes/config.php");

    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // else render form
        render("preference_form.php", ["title" => "Preference Form"]);
    }
    
    // else if user reached page via POST (as by submitting a form via POST)
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $list = ["Price" => "price", "Camera" => "camera", "Performance" => "performance", "Modernity" => "age","Internet_Speed" => "internet", "Battery" => "battery", "Resolution" => "resolution", "Size_&_Weight" => "sizeweight", "Storage" => "storage", "Brand" => "brand", "Brand1" => "brand1"];
        
        //reduce value in preferences input by 1 to get (0,1,2) values instead of (1,2,3)
        foreach ($_POST as $key=>$value)
        {
            if ($key <> "Brand1") {
                $_SESSION[$key]["preference"] = $value - 1;
            } else $_SESSION[$key]["preference"] = $value;
        }

        
        if (isset($_POST['mobile'])) {
            redirect("phones.php");
        } else {
            
            //change the brand comparison as there has been a change in input
            $brand1 = $_SESSION["Brand"]["data"][1][0];
            $brand2 = $_SESSION["Brand"]["data"][1][1];
            
            //create array of two objects
            $list = array();
            $list[0] = new stdClass();
            $list[1] = new stdClass();

            
            //update new brand
            $list[0]->Brand = $brand1;                    
            $list[1]->Brand = $brand2;
            
            $_SESSION["Brand"]["data"] = compareBrand($list,$_SESSION["Brand1"]["preference"]);
            
            echo '<script>var msg = '.json_encode($_SESSION).';</script>'; 
            //move from session to javascript
            render("result_form.php", ["title" => "Result"]);
        }
    }