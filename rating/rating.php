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

function star_rating( $fields ) {
    $commenter = wp_get_current_commenter();

    //unset fields
    $comment_field = $fields['comment'];
    $author_field = $fields['author'];
    $email_field = $fields['email'];
    $url_field = $fields['url'];
    $cookies_field = $fields['cookies'];
    unset( $fields['comment'] );
    unset( $fields['author'] );
    unset( $fields['email'] );
    unset( $fields['url'] );
    unset( $fields['cookies'] );

    // reorder fields pre new field
    $fields['author'] = $author_field;
    $fields['email'] = $email_field;
    $fields['url'] = $url_field;
    $fields['comment'] = $comment_field;

    // add new field
    $fields[ 'Rate this post' ] .=
        '<link rel="stylesheet" href="https://cdn.korzh.com/metroui/v4/css/metro-all.min.css">'.
        '<div class="rate">
            <div class="rate_name">
                Rating
            </div>
        </div>'.
        '<div class="current_post" id="post_id" data-post_id=' . get_the_ID() . '></div>'.
        '<div class="current_comment" id="comment_id" data-comment_id=' . get_comment_ID() . '></div>
        <input class="star_rating_field"
            data-role="rating"
            id="star_rating_field"
            data-star-color="orange"
            data-stared-color="orange"
            data-on-star-click="
                <span class="comment_rating"><input type="radio" name="rating" id="rating" value=arguments[0]/>
            "
            data-show-score="false"
        >
        <script src="https://cdn.korzh.com/metroui/v4/js/metro.min.js"></script>';

    // add remaining fields
    $fields['cookies'] = $cookies_field;

    return $fields;
}

// Add fields after default fields above the comment box, always visible

add_action( 'comment_form_logged_in_after', 'additional_fields' );
add_action( 'comment_form_after_fields', 'additional_fields' );

function additional_fields () {
  echo '<p class="comment-form-title">'.
  '<label for="title">' . __( 'Comment Title' ) . '</label>'.
  '<input id="title" name="title" type="text" size="30"  tabindex="5" /></p>';

  echo '<p class="comment-form-rating">'.
  '<label for="rating">'. __('Rating') . '<span class="required">*</span></label>
  <span class="commentratingbox">';

    //Current rating scale is 1 to 5. If you want the scale to be 1 to 10, then set the value of $i to 10.
    for( $i=1; $i <= 5; $i++ )
    echo '<span class="commentrating"><input type="radio" name="rating" id="rating" value="'. $i .'"/>'. $i .'</span>';

  echo'</span></p>';

}

function add_meta_field($post) {
	add_post_meta($post, 'rating', -1);
	add_comment_meta($comment, 'rating', -1);
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

add_filter( 'comment_form_fields', 'star_rating');
add_action( 'comment_post', 'save_comment_meta_data' );
add_action('the_post', 'show_rating_field');
