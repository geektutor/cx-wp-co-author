<?php
defined( 'ABSPATH' ) || exit;

/**
 * Class CX_COA_Co_Authors.
 */
class CX_COA_Co_Authors {


    /**
     * Initialiseeee
     */
    public static function init() {

        // Register meta key in REST.
        add_action( 'init', array( __CLASS__, 'register_meta' ) );

		// Add coauthors link to post.
		add_filter( 'the_author_posts_link', array( __CLASS__, 'co_authors_post_link' ), 1 );

    }

	/**
	 * Register meta keys for block editor.
	 * 
	 * @since 1.0.0
	 */
	public static function register_meta() {
        // Co-Authors.
		register_meta( 'post', 'cx_coa_co_authors', array(
			'show_in_rest'      => true,
			'type'              => 'string',
			'single'            => true,
			'sanitize_callback' => 'sanitize_text_field',
			'auth_callback'     => function() { 
				return current_user_can( 'edit_posts' );
			}
		));

    }
   
	/**
	 * Get the Co Authors Details.
	 *
	 * @param int $post_id
	 * @return array
	 */
	public static function get_co_authors( $post_id ) {
		$coauthors = get_post_meta( $post_id, 'cx_coa_co_authors', true );

		if ( empty( $coauthors ) ) {
			return array();
		}

		$coauthors_details = array();
		$coauthors_data = explode( ',', $coauthors );

		foreach( $coauthors_data as $coauthor ) {
			$user = get_user_by( 'user_login', $coauthor );
			if ( $user ) {
				$coauthors_details[] = $user; 
			}
		}
		return $coauthors_details;
	}

	/**
	 * Adds the coauthors details to the post.
	 *
	 * @param string $link
	 * @return string
	 * @since 1.0.0
	 */
	public static function co_authors_post_Link( $link ) {
		global $post;
		var_dump("meeeeee");
		$new_link   = $link;
		$post_id    = $post->ID;
		$co_authors = self::get_co_authors( $post_id );

		foreach ( $co_authors as $co_author ) {
			if ( ! empty( $new_link ) ) {
				$new_link .= ', ';
			}

			$new_link .= '<a href="' . $co_author->link . '" title="' . $co_author->display_name
				. '" rel="author" itemprop="author" itemscope="itemscope" itemtype="https://schema.org/Person">'
				. $co_author->display_name . '</a>';
		}

		return $new_link;
	}

}

CX_COA_Co_Authors::init();






















