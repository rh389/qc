$(document).ready(function() {
    //listen for the form beeing submitted
    $(".modal-ajax-form").each(function(index){
        var modal = $(this),
            form = $('form', modal);

        $.ajaxSetup({"accept":"application/json"});

        $(form).submit(function(){
            //get the url for the form
            var url=$(form).attr("action");

            modal.modal('hide');
            //start send the post request
            $.post(url,form.serialize(),function(data, a, request){
                //the response is in the data variable

                if(request.status==200 ){
                    if(form.attr('data-success-location')){
                        document.location.href = form.attr('data-success-location');
                    }
                    if(form.attr('data-success-callback')){
                        var callbackFunc = window[form.attr('data-success-callback')];
                        if(typeof callbackFunc === 'function') {
                            callbackFunc(data.data);
                        }
                    }

                    //$('#output').html(data.greeting);
                    //$('#output').css("color","red");
                }
                else if(request.status==400){//bad request
                   // $('#output').html(data.greeting);
                    //$('#output').css("color","red");
                }
                else{
                    //if we got to this point we know that the controller
                    //did not return a json_encoded array. We can assume that
                    //an unexpected PHP error occured
                    //alert("An unexpeded error occured.");

                    //if you want to print the error:
                    //$('#output').html(data);
                }
            });//It is silly. But you should not write 'json' or any thing as the fourth parameter. It should be undefined. I'll explain it futher down

            //we dont what the browser to submit the form
            return false;
        });
    });
});