jQuery(function($) {
    "use strict";

    var results_divs = $('.klyp-developer-test__results');

    results_divs.each(function(){
        var colour = $(this).data('colour');
        var loading = true;
        var loadingImage = $(this).find('.loading-image');
        
        $.ajax({
            method: 'POST',
            url: klyp_ajax.admin_url,
            dataType: 'json',
            data: {
                action: 'fetch_movies',
                colour: colour
            }
        })
        .done(function(response){
            var response = JSON.parse(response)

            // Stop if no movies found. 
            if (response.totalResults == 0){
                return        
            } 

            var movies = response.Search;

            movies.forEach(function(movie){
                // Check if movie title begins with one of our colours.
                if (movie.Title.toLowerCase().startsWith(colour)){
                    console.log(movie);
                }
            })
        })
        .fail(function(error){
            console.error(error)
        })
        .always(function(){
            loading = false;
            if (loading === false) loadingImage.hide();
            console.log('Request completed.')
        })
    })
});