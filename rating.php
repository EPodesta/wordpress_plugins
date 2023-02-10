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
add_filter( 'comment_form_submit_button', 'star_rating' );

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
		<input type="radio" id="star5" name="rate" value="5" />
		<label for="star5" title="text">5 stars</label>
		<input type="radio" id="star4" name="rate" value="4" />
		<label for="star4" title="text">4 stars</label>
		<input type="radio" id="star3" name="rate" value="3" />
		<label for="star3" title="text">3 stars</label>
		<input type="radio" id="star2" name="rate" value="2" />
		<label for="star2" title="text">2 stars</label>
		<input type="radio" id="star1" name="rate" value="1" />
		<label for="star1" title="text">1 star</label>
	  </div>
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
		float: left;
		height: 46px;
		padding: 0 10px;
	}
	.rate:not(:checked) > input {
		position:absolute;
		top:-9999px;
	}
	.rate:not(:checked) > label {
		float:right;
		width:1em;
		overflow:hidden;
		white-space:nowrap;
		cursor:pointer;
		font-size:30px;
		color:#ccc;
	}
	.rate:not(:checked) > label:before {
		content: '★ ';
	}
	.rate > input:checked ~ label {
		color: #ffc700;
	}
	.rate:not(:checked) > label:hover,
	.rate:not(:checked) > label:hover ~ label {
		color: #deb217;
	}
	.rate > input:checked + label:hover,
	.rate > input:checked + label:hover ~ label,
	.rate > input:checked ~ label:hover,
	.rate > input:checked ~ label:hover ~ label,
	.rate > label:hover ~ input:checked ~ label {
		color: #c59b08;
	}
	</style>
<?php
}

function star_rating($button)
{
	/* star_definition(); */
	star_css();
	return "Rating: " . <<<END
	  <div class="rate">
		<input type="radio" id="star5" name="rate" value="5" />
		<label for="star5" title="text">5 stars</label>
		<input type="radio" id="star4" name="rate" value="4" />
		<label for="star4" title="text">4 stars</label>
		<input type="radio" id="star3" name="rate" value="3" />
		<label for="star3" title="text">3 stars</label>
		<input type="radio" id="star2" name="rate" value="2" />
		<label for="star2" title="text">2 stars</label>
		<input type="radio" id="star1" name="rate" value="1" />
		<label for="star1" title="text">1 star</label>
	  </div>
	END . $button;
}
