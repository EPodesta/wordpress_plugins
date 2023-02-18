<?php
/**
 * @package Rating
 * @version 1.0
 */
/*
Plugin Name: Rating
Description: This plugin is just a rating one. Its sole purpose is for study
only.
Author: Emmanuel Podestá Junior
Version: 1.0
*/

define( 'WP_DEBUG', true );
define( 'SAVEQUERIES', true );

/**
 * Add our field to the comment form
 */
/* add_action( 'comment_form_logged_in_after', 'star_rating' ); */
/* add_action( 'comment_form_after_fields', 'star_rating' ); */
/* add_action( 'comment_form', 'star_rating' ); */

function star_definition() {
?>
<html>
	<head>
	  <meta charset="UTF-8">
	  <link rel="stylesheet" type="text/css" href="style.css">
	  <title>Star rating using pure CSS</title>
	</head>

	<body>
	<div class="rate">
		<div class="rate_name">
			Rating
		</div>
		<div class="current_post" id="post_id" data-post_id=<?php echo get_the_ID() ?>>
		<div class="stars">
			<input type="radio" id="star5" name="stars" value="5" />
			<label for="star5" title="text">5 stars</label>
			<input type="radio" id="star4" name="stars" value="4" />
			<label for="star4" title="text">4 stars</label>
			<input type="radio" id="star3" name="stars" value="3" />
			<label for="star3" title="text">3 stars</label>
			<input type="radio" id="star2" name="stars" value="2" />
			<label for="star2" title="text">2 stars</label>
			<input type="radio" id="star1" name="stars" value="1" />
			<label for="star1" title="text">1 star</label>
		</div>
	</div>
	<br>
	<br>
	</body>
</html>
<?php
}

function star_css() {
?>
	<style type="text/css">
	*{
		margin: 0;
		padding: 0;
	}
	.rate {
		display: inline;
		padding: 0px 0px 0px 0px;
	}
	.stars {
		float: left;
		height: 46px;
		padding: 0px 0px 0px 0px;
	}
	/* .stars::after { */
	/* 	content: "\a"; */
	/* 	white-space: pre; */
	/* } */
	.stars:not(:checked) > input {
		position:absolute;
		top:-9999px;
	}
	.stars:not(:checked) > label {
		float:right;
		width:1em;
		overflow:hidden;
		white-space:nowrap;
		cursor:pointer;
		font-size:30px;
		color:#ccc;
	}
	.stars:not(:checked) > label:before {
		content: '★ ';
	}
	.stars > input:checked ~ label {
		color: #ffc700;
	}
	.stars:not(:checked) > label:hover,
	.stars:not(:checked) > label:hover ~ label {
		color: #deb217;
	}
	.stars > input:checked + label:hover,
	.stars > input:checked + label:hover ~ label,
	.stars > input:checked ~ label:hover,
	.stars > input:checked ~ label:hover ~ label,
	.stars > label:hover ~ input:checked ~ label {
		color: #c59b08;
	}
	</style>
<?php
}


function star_rating()
{
	star_definition();
	star_css();
}

function add_meta_field($post) {
	add_post_meta($post, 'rating', -1);
	register_meta('post', 'rating', [
		'type' => 'integer',
		'description'  => 'Meta key associated with post rating.',
		'single'       => true,
		'show_in_rest' => true
	]);
}

function get_rating_field($post) {
	$post_id = $post->ID;
	$post_rating = get_post_meta($post_id, 'rating', true);
	echo $post_rating;
}

function post_rating( $req ) {
	$rating = $req['rating'];
	$post_id = $req['post_id'];
	$res = new WP_REST_Response($rating);
	$res->set_status(200);
	add_post_meta($post_id, 'rating', $rating, true);
	return $res;
}
function load_scripts() {
	wp_enqueue_script( 'rating', '/wp-content/plugins/rating.js', array('wp-api-fetch'), '1.0.0', true );
}

add_action( 'rest_api_init', function () {
  register_rest_route( 'rating/v1', '/post_rating', array(
    'methods' => 'POST',
    'callback' => 'post_rating',
  ) );
} );

add_action('wp_enqueue_scripts', 'load_scripts');
/* add_action( 'the_post', 'add_meta_field'); */
add_action( 'comment_form_before_fields', 'star_rating' );
add_action('the_post', 'get_rating_field');
