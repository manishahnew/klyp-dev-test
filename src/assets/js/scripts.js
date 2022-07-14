jQuery(function($) {
    "use strict";

    // Fetch and display movies for each colour.
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
                action: 'fetch_movies_per_colour',
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
                    var moviesHtml = `
                    <div class="klyp-developer-test__results__col">
                        <a class="klyp-developer-test__results__poster" href="https://www.imdb.com/title/${movie.imdbID}" target="_blank">
                            <img src="${movie.Poster}" title="${movie.Title}" width="300" height="445" />
                        </a>

                        <h3>
                            ${movie.Title}
                        </h3>
                        
                        <p>
                            Year: ${movie.Year}
                        </p>

                        <a class="klyp-developer-test__results__link" href="https://www.imdb.com/title/${movie.imdbID}" target="_blank">
                            IMDB Page
                        </a>
                    </div>
                    `;

                    currentDiv.append(moviesHtml)
                }
            })
        })
        .fail(function(error){
            console.error(error);
            alert('Something has gone wrong.');
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