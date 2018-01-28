<!DOCTYPE html>

<html>

    <head>

        <!-- https://jquery.com/ -->
        <script src="js/jquery-3.2.1.min.js"></script>
        
        <!-- http://getbootstrap.com/ -->
        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <script src="js/bootstrap.min.js"></script>        

        <!-- bootstrap slider -->
        <script src="js/bootstrap-slider.min.js"></script>
        <link href="css/bootstrap-slider.min.css" rel="stylesheet" type="text/css"/>
        
        <!-- typeahead -->
        <script src="js/jquery.typeahead.min.js"></script>
        <link href="css/jquery.typeahead.min.css" rel="stylesheet" type="text/css"/>
                
        <!--custom scripts & styles -->
        <script src="js/scripts.js"></script>
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>       
        
        <!--HTML5 shiv -->
        <script src="js/html5shiv-printshiv.js"> </script>
        
        <title>CompareDaPhone</title>
        
    </head>

    <body>

        <div class="container">

            <div id="top">
                <div id = "subtop1">
                    <a><img alt="CompareDaPhone" src="img/logo.png"/></a>
                </div>
                <div id = "subtop2">  
                    <ul class="nav nav-pills">
                    	<?php 
                    	   if (!empty($_SESSION["Price"])) {
                    	       echo '<li><a href="preferences.php">Select Preferences</a></li>';
                    	   };
                    	   if (!empty($_SESSION["DeviceName"])) {
                    	       echo '<li><a href="phones.php">Select Mobile Devices</a></li>';                    	       
                    	   };
                    	?>
                        <li><a href="feedback.php">Provide Feedback</a></li>
                    </ul>
                </div>
            </div>

            <div id="middle">
