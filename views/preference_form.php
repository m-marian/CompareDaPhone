
<div id = "preference-form"> <b>Select how important each of the ten features below are to you. </b></div>

<template id ="preference-template">
    <div class ="form-group">
        <div class = "question">
            <a class = "hint" data-toggle="tooltip" data-placement="right" animation = "true" title="" ></a>
        </div>
        <input class="preference" type="text" style="width:50%;"
            data-provide="slider"
            data-slider-ticks="[1, 2, 3]"
            data-slider-ticks-labels='["Not Important", "Somewhat Important", "Very Important"]'
            data-slider-min="1"
            data-slider-max="3"
            data-slider-step="1"
            data-slider-value="3"
            data-slider-tooltip="hide" 
        />
    </div>
</template>

<form id = "questionsList" action="preferences.php" method="post"> 
    <div class="form-group">
        <div class = "question" id = "brand-question" style = "margin-bottom: 100px"> Brand Name 
            <div class="typeahead__container">
                <div class="typeahead__field">

                    <span class="typeahead__query">
                        <input class="js-typeahead" id = "Brand1" name="Brand1" type="search" placeholder="Search" autocomplete="off" size="30%">
                    </span>

                </div>
            </div>
        </div>
    </div>
        
    <div class ="form-group"> 
        <button class="btn btn-default" id ="preference-button" name="mobile" type="submit">Select Mobile Devices</button>

        <?php
            $result_button = '<button class="btn btn-default" id = "result-button" name= "result" type="submit">See Results</button>';
            if (!empty($_SESSION["DeviceName"])) {
                echo $result_button;
            }
        ?>
        
    </div> 
        
</form>

<?php 
    echo '<script>var msg = '.json_encode($_SESSION).';</script>'; 
?>

<script> loadPrefQuestions(); </script>


    