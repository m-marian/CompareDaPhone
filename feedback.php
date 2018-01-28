<?php

    require("includes/config.php");

    // if user reached page via GET (as by clicking a link or via redirect)
    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        // else render form
        render("feedback_form.php", ["title" => "Feedback"]);
    }
    
    // else if user reached page via POST (as by submitting a form via POST)
    else if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        if (empty($_POST["name"]))
        {
            apologize("You must provide your name.");
        }
        else if (empty($_POST["email"]))
        {
            apologize("You must provide your email.");
        }
        else if (empty($_POST["comment"]))
        {
            apologize("You must write some feedback.");
        };

        $myname = mysqli_real_escape_string($db,$_POST["name"]);
        $myemail = mysqli_real_escape_string($db,$_POST["email"]);
        $mycomment = mysqli_real_escape_string($db,$_POST["comment"]);
        $myphone1 = mysqli_real_escape_string($db,$_SESSION["DeviceName"][0]);
        $myphone2 = mysqli_real_escape_string($db,$_SESSION["DeviceName"][1]);
        
        $sql_part = "('$myname', '$myemail', '$mycomment', '$myphone1', '$myphone2')";
        
        $sql = "INSERT INTO feedback (name, email, comment, phone1, phone2) VALUES ".$sql_part;
        $result = mysqli_query($db,$sql);
        
        if ($result <> 0) {
            render("feedback_output.php", ["title" => "Feedback Output"]);
        } else {
            apologize("Something went wrong with data insertion!");        }
    };
    
?>