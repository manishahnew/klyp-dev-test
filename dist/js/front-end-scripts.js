jQuery(function($) {
    "use strict";

    $.ajax({
        url: klyp_ajax.admin_url,
        dataType: 'json',
        data: {
            action: 'fetch_movies'
        }
    })
    .done(function(response){
        console.log(response);
        console.log('Success');
    })
    .fail(function(error){
        console.error(error)
    })
    .always(function(){
        console.log('Request completed.')
    })
});