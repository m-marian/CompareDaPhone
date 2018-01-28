function loadPrefQuestions () {
    var brand_node = 0 ;
    var questions = [["Price", "Duuh"], ["Camera", "Kind of obvious"], ["Performance", "How fast the phone works"], ["Modernity", "How new the phone is"],["Internet Speed", "Duuh"], ["Battery", "Another obvious one"], ["Resolution", "Duuh"], ["Size & Weight", "The larger the worse"], ["Storage", "Duuh"], ["Brand", "I hope I don't need to explain"]];
         
    var questionsList = document.getElementById('questionsList');
    
    //Tester for whether browser is IE
    var checkIE = (window.navigator.userAgent.indexOf("MSIE ") >  0 || !!navigator.userAgent.match(/Trident.*rv\:11\./) );
    
    for (var i=0; i<questions.length;i++) {
        var question = questions[i][0];

        var tmpl;
        
        //IE doesn't recognize the #document-fragment intermediate node

        if (checkIE) {
            tmpl = document.getElementById('preference-template').childNodes[1].cloneNode(true);
        } else {
            tmpl = document.getElementById('preference-template').content.cloneNode(true);
        }
                
        var subForm = tmpl.querySelector('.preference');
        subForm.name = question;
                
        var parent = $(subForm).parent();

                
        // remembering previous preferences input
        if (typeof msg !== 'undefined' && Object.keys(msg).length > 0 && msg.constructor === Object) {
            var section = question;
            if (section == "Size & Weight") {
                section = "Size_&_Weight";
            } else if (section == "Internet Speed") {
                section = "Internet_Speed";
            };
            // set previous preferences as tick marks
            $(subForm).slider();            
            $(subForm).slider('setValue',(msg[section]["preference"]+1));
            
            // set styling for question div as when I call the above alone it won't work
            
            parent.find('.slider.slider-horizontal').css("margin-bottom", "24px");
            parent.find('.slider-tick-label-container').css({"margin-left":"-25%"});            
            parent.find('.slider-tick-label').css("width","40%");
            

        };
        
        // set up tooltip
        var hint = parent.find('[data-toggle="tooltip"]');
        hint.tooltip({title: questions[i][1]});
        hint.text((i+1).toString() + ". " + question);

        questionsList.insertBefore(tmpl,questionsList.children[brand_node]);
        brand_node++;
    };
    
    $.typeahead({
        input: '#Brand1',
        minLength: 1,
        order: "asc",
        dynamic: true,
        delay: 400,
        source: {
            result: {
                display: "Brand",
                ajax: function (query) {
                    return {
                        type: "GET",
                        url: "search_brand.php",
                        data: {
                            device: "{{query}}"
                        }
                    };
                }
            }
        },
    });
}

function configureSelection () {
    $.typeahead({
        input: '#phone1',
        minLength: 1,
        order: "asc",
        dynamic: true,
        delay: 500,
        source: {
            result: {
                display: "DeviceName",
                ajax: function (query) {
                    return {
                        type: "GET",
                        url: "search.php",
                        data: {
                            device: "{{query}}"
                        }
                    }
                }
            }
        },
    }); 
    
    $.typeahead({
        input: '#phone2',
        minLength: 1,
        order: "asc",
        dynamic: true,
        delay: 500,
        source: {
            result: {
                display: "DeviceName",
                ajax: function (query) {
                    return {
                        type: "GET",
                        url: "search.php",
                        data: {
                            device: "{{query}}"
                        }
                    }
                }
            }
        },
    }); 
}
function configureTable() {
        
    var total = 0;
    var features = ["Price", "Camera", "Performance", "Modernity","Internet_Speed", "Battery", "Resolution", "Size_&_Weight", "Storage", "Brand"];
    
    var phone1 = msg["DeviceName"][0].fontcolor("red");
    var phone2 = msg["DeviceName"][1].fontcolor("blue");
    
    //create first/header row
    var header = document.getElementById("phone1");
    header.innerHTML = phone1;
    header.class = "phone1_column"
    var header = document.getElementById("phone2");
    header.innerHTML = phone2;
    header.class = "phone2_column";
    
    for (var i = 0; i < features.length; i++) {
        
        //total calculation
        feature = features[i];
        
        total += msg[feature]["data"][0][0]*msg[feature]["preference"];
        
        //adding the data to HTML table
        var table = document.getElementById("table-body");
        var row = table.insertRow(i);
        
        
        //1st column containing feature names
        var th = document.createElement('th');
        if (feature == "Size_&_Weight") {
            th.innerHTML = "Size & Weight";
        } else if (feature === "Internet_Speed") {
            th.innerHTML = "Internet Speed";
        } else th.innerHTML = feature;
        row.appendChild(th);
        
        
        //creating 2nd,3rd,4th and 5th columns
        var td1 = document.createElement('td');
        var td2 = document.createElement('td');
        var td3 = document.createElement('td');
        var td4 = document.createElement('td');
        
        //2nd and 3rd column containing phone details
        var phone1_detail = msg[feature]["data"][1][0];
        var phone2_detail = msg[feature]["data"][1][1];
        
        td1.innerHTML = phone1_detail;
        row.appendChild(td1);
        td2.innerHTML = phone2_detail;
        row.appendChild(td2);
        
        //4th column containing result of comparison
        var output;
        
        if (msg[feature]["data"][0][0] > 0) {
            output = phone1.fontcolor("red");
        } else if (msg[feature]["data"][0][0] < 0) {
            output = phone2.fontcolor("blue");
        } else if (msg[feature]["data"][0][1] == 1) {
            output = "It's a draw";
        } else {
            output = "Unable to assess";
        }
        
        td3.innerHTML = output;
        row.appendChild(td3);
        
        //5th column showing importance of feature
        var importance;
        
        switch (msg[feature]["preference"]) {
            case 2:
                importance = "Very important";
                break;
            case 1:
                importance = "Somewhat important";
                break;
            case 0:
                importance = "Not important";
        };
        
        td4.innerHTML = importance;
        row.appendChild(td4);
    }
    
    var result;
    if (total > 0) {
        result = phone1;
    } else if (total < 0) {
        result = phone2;
    } else {
        result = "neither!";
    };
    
    var headline = document.getElementById("result");
    headline.innerHTML = result;
} 

function getCounter() {
    $.ajax({
        method: "GET",
        url: "hits.php",
    })
        .done(function(data) {
            $('#counter').text(data);
        });
}
