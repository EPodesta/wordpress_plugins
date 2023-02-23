function wordpress_rating_plugin(value){
	var post_id = document.getElementById("post_id");
	var comment_id = document.getElementById("comment_id");
	// POST
	window.wp.apiFetch( {
		path: '/rating/v1/post_rating',
		method: 'POST',
        data: { rating: value, comment_id: comment_id.dataset.comment_id, post_id: post_id.dataset.post_id },
	} ).then( ( res ) => {
		console.log( res );
	} );

}
