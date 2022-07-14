import Swiper from 'swiper';

jQuery(function($) {
    "use strict";

    console.log(Swiper);

    var results_divs = $('.klyp-developer-test__results');

    results_divs.each(function(){
        // Variables
        var colour = $(this).data('colour');
        var loading = true;
        var loadingImage = $(this).find('.loading-image');
        var currentDiv = $(this);
        
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
            // Parse JSON so we can work with it more easily in JS.
            var response = JSON.parse(response)

            // Stop if no movies found. 
            if (response.totalResults === 0){
                return        
            } 

            var movies = response.Search;

            movies.forEach(function(movie){
                // Check if movie title begins with one of our colours.
                if (movie.Title.toLowerCase().startsWith(colour)){
                    console.log(movie);

                    var moviesHtml = `
                    <div class="klyp-developer-test__results__col">
                        <img src="${movie.Poster}" title="${movie.Title}" width="300" height="445" />

                        <h3>
                            ${movie.Title}
                        </h3>
                        
                        <p>
                            ${movie.Year}
                        </p>

                        <a href="https://www.imdb.com/title/${movie.imdbID}" target="_blank">
                            IMDB Page
                        </a>
                    </div>
                    `;

                    currentDiv.append(moviesHtml)
                }
            })
        })
        .fail(function(error){
            console.error(error)
        })
        .always(function(){
            // Set loading to false and remove the loading gif.
            loading = false;
            if (loading === false) loadingImage.hide();

            // Just so we know the request has been finished. 
            console.log('Request completed.')
        })
    })
});