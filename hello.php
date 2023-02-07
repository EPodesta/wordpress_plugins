<?php
/**
 * @package Hello_Dolly
 * @version 1.7.2
 */
/*
Plugin Name: Hello Dolly
Plugin URI: http://wordpress.org/plugins/hello-dolly/
Description: This is not just a plugin, it symbolizes the hope and enthusiasm of an entire generation summed up in two words sung most famously by Louis Armstrong: Hello, Dolly. When activated you will randomly see a lyric from <cite>Hello, Dolly</cite> in the upper right of your admin screen on every page.
Author: Matt Mullenweg
Version: 1.7.2
Author URI: http://ma.tt/
*/

function hello_dolly_get_lyric() {
	/** These are the lyrics to Hello Dolly */
	$lyrics = "Hello, Dolly
Well, hello, Dolly
It's so nice to have you back where you belong
You're lookin' swell, Dolly
I can tell, Dolly
You're still glowin', you're still crowin'
You're still goin' strong
I feel the room swayin'
While the band's playin'
One of our old favorite songs from way back when
So, take her wrap, fellas
Dolly, never go away again
Hello, Dolly
Well, hello, Dolly
It's so nice to have you back where you belong
You're lookin' swell, Dolly
I can tell, Dolly
You're still glowin', you're still crowin'
You're still goin' strong
I feel the room swayin'
While the band's playin'
One of our old favorite songs from way back when
So, golly, gee, fellas
Have a little faith in me, fellas
Dolly, never go away
Promise, you'll never go away
Dolly'll never go away again";

	// Here we split it into lines.
	$lyrics = explode( "\n", $lyrics );

	// And then randomly choose a line.
	return wptexturize( $lyrics[ mt_rand( 0, count( $lyrics ) - 1 ) ] );
}

// This just echoes the chosen line, we'll position it later.
function hello_dolly() {
	$chosen = hello_dolly_get_lyric();
	$lang   = '';
	if ( 'en_' !== substr( get_user_locale(), 0, 3 ) ) {
		$lang = ' lang="en"';
	}

	printf(
		'<p id="dolly"><span class="screen-reader-text">%s </span><span dir="ltr"%s>%s</span></p>',
		__( 'Quote from Hello Dolly song, by Jerry Herman:' ),
		$lang,
		$chosen
	);
}

// Now we set that function up to execute when the admin_notices action is called.
add_action( 'admin_notices', 'hello_dolly' );

// We need some CSS to position the paragraph.
function dolly_css() {
	echo "
	<style type='text/css'>
	#dolly {
		float: right;
		padding: 5px 10px;
		margin: 0;
		font-size: 12px;
		line-height: 1.6666;
	}
	.rtl #dolly {
		float: left;
	}
	.block-editor-page #dolly {
		display: none;
	}
	@media screen and (max-width: 782px) {
		#dolly,
		.rtl #dolly {
			float: none;
			padding-left: 0;
			padding-right: 0;
		}
	}
	</style>
	";
}

add_action( 'admin_head', 'dolly_css' );
function wp_fa() {
 echo "fa";
}
add_action("wp_body_open", "wp_fa");
function emma_upper($content) {
    return strtoupper($content);
}
function changer() {
	return "Puto";
}
add_filter("the_content", "emma_upper");
add_action("wp_head", "changer");
add_action('wp_meta', 'sidebar_changer');

add_filter("option_blogname", "changer");
function header_changer() {
	return 'testttt';
}
//Display the rating on a submitted comment.
function ci_comment_rating_display_rating( $comment_text ) {

  if ( $rating = get_comment_meta( get_comment_ID(), 'rating', true ) ) {
    $stars = '<p class="stars">';
    for ( $i = 1; $i <= 5 ; $i ++ ) {
      if ( $i <= $rating ) {
        $stars .= '<span class="dashicons dashicons-star-filled"></span>';
      } else {
        $stars .= '<span class="dashicons dashicons-star-empty"></span>';
      }
    }
    $stars        .= '</p>';
    $comment_text = $comment_text . $stars;

    return $comment_text;
  } else {
    return $comment_text;
  }
}

$args = array(
	'post_id' => 1,   // Use post_id, not post_ID
);
$comments = get_comments($args);
foreach ( $comments as $comment ) :
	echo $comment->comment_content;
endforeach;

function add_custom_comment_field( $comment_id ) {
//maybe use this to store and get_comment_meta to get and display ratings
   add_comment_meta( $comment_id, 'my_custom_comment_field', 5 );
   $ratings = get_comment_meta($comment_id, 'my_custom_comment_field', true);
   foreach ($ratings as $rating) {
	   echo $rating;
   }
}
add_action( 'comment_post', 'add_custom_comment_field' );
/* add_action("comment_post", "comment_rating"); */

add_filter("single_cat_title", "header_changer");
add_filter( 'comment_text', 'ci_comment_rating_display_rating' );
