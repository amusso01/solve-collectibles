/**
 * AJAX Load (Lazy Load) events
 */

 //--   Settings

// How many do you want to load each button click?
var postsPerPage = 12;

const $ = jQuery;

//--    /Settings

// How many posts there's total
var totalPosts = parseInt( jQuery( '#found-posts' ).text() );

// if( totalPosts == postOffset ) {
//  jQuery( '#load-more' ).fadeOut();
// }

$('#loadMore').click( function(e){
    e.preventDefault();

    // Get current category
    var cat_id  =   $(this).data('product-category');


ajax_next_posts( cat_id );
    $('body').addClass('ajaxLoading');
});

var ajaxLock = false; // ajaxLock is just a flag to prevent double clicks and spamming

if( !ajaxLock ) {

    function ajax_next_posts( cat_id ) {

        ajaxLock = true;

        // How many have been loaded
      
        var data = {
			'action': 'ajax_next_posts',
            '_ajax_nonce' : fd_load_more_global.nonce,
			'max_page': fd_load_more_global.max_page, // that's how we get params from wp_localize_script() function
			'current_page' : fd_load_more_global.current_page ,
            'post_offset' : jQuery( 'li.product' ).length
		};

        // Ajax call itself
        $.ajax({
            method: 'POST',
            url: fd_load_more_global.ajax_url,
            data: data,
            dataType: 'json'
        })
        .done( function( response ) { // Ajax call is successful

            // Add new posts
            jQuery( '.products' ).append( response[0] );

            // Update Post Offset
            data.post_offset = jQuery( 'li.product' ).length;
            fd_load_more_global.current_page++

            console.log(data.max_page);
            console.log(data.post_offset);
            console.log(data.current_page);
     
        


            ajaxLock = false;

            console.log( 'Success' );

            $('body').removeClass('ajaxLoading');

            

            // How many have been loaded
            var postOffset = jQuery( 'li.product' ).length
            console.log( "Posts on Page: " + postOffset );

            // Hide button if all posts are loaded
            if( ( data.current_page - data.max_page ) === 0 ) {
                jQuery( '#loadMore' ).fadeOut();
            }

        })
        // .fail( function() {
        .fail( function(jqXHR, textStatus, errorThrown) { // Ajax call is not successful, still remove lock in order to try again

            ajaxLock = false;

            console.log(XMLHttpRequest);
            console.log(textStatus);
            console.log(errorThrown);

            console.log( 'Failed' );

        });
    }
}

// const button = document.getElementById('loadMore');

// button.addEventListener('click', function(e){
//     e.preventDefault();
//     fetch('/wp-admin/admin-ajax.php', {
//         method: 'POST',

//       }).then(response => {
  
//         return response.json();
  
//       }).then(jsonResponse => {
  
//         console.log({ jsonResponse });
  
//       });
// })