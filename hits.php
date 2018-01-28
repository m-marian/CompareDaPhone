<?php
    require("includes/config.php");

    if ($_SERVER["REQUEST_METHOD"] == "GET")
    {
        $sql = "SELECT * FROM hitlist";
        $result = mysqli_query($db,$sql);
        print($result->num_rows);
    }
?>

