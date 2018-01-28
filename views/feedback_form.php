<div id = "feedback-headline">Please input the following details...</div>

<form id = "feedback-form" action="feedback.php" method="post"> 
    <div class="form-group" id = "feedback-name">
        <div class = "question"> Your name: </div>
        <input autocomplete="off" autofocus class="form-control" name="name" type="text"/>
    </div>

    <div class="form-group" id = "feedback-email">
        <div class = "question" id = "email-question"> Your email address: </div>
        <input autocomplete="off" autofocus class="form-control" name="email" type="text"/>
    </div>
    
    <div class="form-group" id = "feedback-comment">
        <div class = "question"> Your comments: </div>
        <textarea autocomplete="off" autofocus class="form-control" name="comment" type="text"> </textarea>
    </div>
    
    <div class ="form-group"> 
        <button class="btn btn-default" id ="preference-button" type="submit">Submit</button>
    </div>    
</form>

