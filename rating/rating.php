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

function star_rating() {
?>
<html lang="en">
    <head>
        <link rel="stylesheet" href="https://cdn.korzh.com/metroui/v4/css/metro-all.min.css">
    </head>
    <body>
        <div class="rate">
            <div class="rate_name">
                Rating
            </div>
        </div>
        <div class="current_post" id="post_id" data-post_id=<?php echo get_the_ID() ?>></div>
        <div class="current_comment" id="comment_id" data-comment_id=<?php echo get_comment_ID() ?>></div>
        <input class="star_rating_field"
            data-role="rating"
            id="star_rating_field"
            data-star-color="orange"
            data-stared-color="orange"
            data-on-star-click="wordpress_rating_plugin"
            data-show-score="false"
        >
        <script src="https://cdn.korzh.com/metroui/v4/js/metro.min.js"></script>
    </body>
</html>
<?php
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

function post_rating( $req ) {
    $rating = $req['rating'];
    $post_id = $req['post_id'];
    $comment_id = $req['comment_id'];
    $res = new WP_REST_Response($comment_id);
    $res->set_status(200);
    add_post_meta($post_id, 'rating', $rating, true);
    return $res;
}

function load_scripts() {
    wp_enqueue_style('star_style', plugin_dir_url(__FILE__) . '/theme/style.css');
    wp_enqueue_script( 'rating', plugin_dir_url(__FILE__) . '/rating.js', array('wp-api-fetch'), '1.0.0', true );
}

function show_rating_field($post) {
    $post_id = $post->ID;
    $post_rating = get_post_meta($post_id, 'rating', true);
?>
<html lang="en">
    <body>
        <input class="post_rating"
            id="post_rating"
            data-role="rating"
            data-stared-color="orange"
            data-static="true"
            data-value="<?php echo $post_rating?>"
        >
    </body>
</html>
<?php
}

add_action( 'rest_api_init', function () {
  register_rest_route( 'rating/v1', '/post_rating', array(
    'methods' => 'POST',
    'callback' => 'post_rating',
  ) );
} );

add_action('wp_enqueue_scripts', 'load_scripts');
add_action('comment_form_before_fields', 'star_rating');
add_action('the_post', 'show_rating_field');
