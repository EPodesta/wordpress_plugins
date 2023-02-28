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
        '<p class="comment-form-rating">'.
        '<label for="rating">'.__('Rate this post').'<span class="required"> *</span></label>'.
        '<span class="comment_ratingbox"><br>'.
        '<div class="stars">'.
            '<input type="radio" id="star5" name="stars" value="5" />'.
            '<label for="star5" title="text" class="fas fa-star">5 stars</label>'.
            '<input type="radio" id="star4" name="stars" value="4" />'.
            '<label for="star4" title="text" class="fas fa-star">4 stars</label>'.
            '<input type="radio" id="star3" name="stars" value="3" />'.
            '<label for="star3" title="text" class="fas fa-star">3 stars</label>'.
            '<input type="radio" id="star2" name="stars" value="2" />'.
            '<label for="star2" title="text" class="fas fa-star">2 stars</label>'.
            '<input type="radio" id="star1" name="stars" value="1" />'.
            '<label for="star1" title="text" class="fas fa-star">1 star</label>'.
        '</div><br><br>'.
        '</span></p>';


    // add remaining fields
    $fields['cookies'] = $cookies_field;

    return $fields;
}

function save_comment_meta_data( $comment_id ) {
    if ((isset($_POST['stars'])))
        $rating = $_POST['stars'];
    add_comment_meta($comment_id, 'rating', $rating);
}

function insert_comment_rating($comment) {
    $comment_id = $comment->comment_ID;
    $comment_rating = get_comment_meta($comment_id,'rating',true);
    $comment->comment_content .=
    '<br><input class="comment_rating"
        id="comment_rating"
        data-role="rating"
        data-stared-color="orange"
        data-static="true"
        data-value="'.$comment_rating.'"'.
    '>';
    return $comment;
}

function insert_post_rating($post_content) {
    $post_id = get_the_ID();

    $args = array(
        'post_id' => $post_id   // Use post_id, not post_ID
    );
    $comments = get_comments( $args );
    $sum_comments = 0.0;
    $quantity_comments = 0.0;
    foreach ( $comments as $comment ) {
        $comment_rate = (float)get_comment_meta($comment->comment_ID,'rating',true);
        $sum_comments += $comment_rate;
        $quantity_comments += 1.0;
    }
    $mean_comments = $sum_comments/$quantity_comments;
    return $post_content .
'<link rel="stylesheet" href="https://cdn.korzh.com/metroui/v4/css/metro-all.min.css">'.
    '<input class="post_rating"
        id="post_rating"
        data-role="rating"
        data-stared-color="orange"
        data-static="true"
        data-value="'.$mean_comments.'"'.
    '>'.
'<script src="https://cdn.korzh.com/metroui/v4/js/metro.min.js"></script>';
}

function load_scripts() {
    wp_enqueue_style('star_style', plugin_dir_url(__FILE__) . '/theme/style.css');
}

add_action('wp_enqueue_scripts', 'load_scripts');
add_filter( 'comment_form_fields', 'star_rating');
add_action( 'comment_post', 'save_comment_meta_data' );
add_filter('get_comment', 'insert_comment_rating');
add_filter('the_content', 'insert_post_rating');
