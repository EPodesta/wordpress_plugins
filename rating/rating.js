var stars = document.getElementsByName("stars");
stars.forEach(r => r.addEventListener('change', function (e) {
	var post_id = document.getElementById("post_id");
	// POST
	window.wp.apiFetch( {
		path: '/rating/v1/post_rating',
		method: 'POST',
		data: { rating: e.currentTarget.value, post_id: post_id.dataset.post_id },
	} ).then( ( res ) => {
		console.log( res );
	} );

}))
