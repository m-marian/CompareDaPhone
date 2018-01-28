
<form id="mobile_form" name="mobile_form" action="phones.php" method="post">
    <label class = "phone_label">Now select the first phone</label>

    <div class="typeahead__container">
        <div class="typeahead__field">

            <span class="typeahead__query">
                <input class="js-typeahead" id = "phone1" name="phone1" type="search" placeholder="Search" autocomplete="off" size="20%">
            </span>

        </div>
    </div>
    
    <label class = "phone_label"> ... and the second phone: </label>

    <div class="typeahead__container">

        <div class="typeahead__field">
            <span class="typeahead__query">
                <input class="js-typeahead" id = "phone2" name="phone2" type="search" placeholder="Search" autocomplete="off" size="20%">
            </span>

        </div>
    </div>
    
      <div class ="form-group"> 
            <button class="btn btn-default" id="phoneButton" type="submit">See Results</button>
        </div>    
    
</form>

<script> configureSelection(); </script>

    