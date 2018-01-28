<?php
    include_once("fonoapi-v1.php");
    require("page_functions.php"); 
    require("data_functions.php");
    set_time_limit(600);

    
    session_start();
    
    //set up connection to myphpadmin database

    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', 'mysql');
    define('DB_DATABASE', 'mobilecomp');
    $db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
    
    //set up connection to fonoapi API
    $apiKey = "ad7ff361126a4b061764479a834007d9468d0e568661e5f4";
    $fonoapi = fonoApi::init($apiKey);

?>