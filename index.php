<?php

    require("includes/config.php");

    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // else render form
        render("welcome_form.php", ["title" => "Welcome"]);
    }
    
    // else if user reached page via POST (as by submitting a form via POST)
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $client_ip = get_ip_address();
        $myip = mysqli_real_escape_string($db,$client_ip);
        $sql = "INSERT INTO hitlist (ip) VALUES ('$myip')";
        $result = mysqli_query($db,$sql);
        if ($result <> 0) {
            redirect("preferences.php");
        } else {
            apologize("Something went wrong with the website, sorry!");   
        }
    }
?>