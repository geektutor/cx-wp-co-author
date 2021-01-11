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

		// Add coauthors data to post.
		add_filter( 'the_content', array( __CLASS__, 'co_authors_post_link' ) );

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
		$co_authors = get_post_meta( $post_id, 'cx_coa_co_authors', true );

		if ( empty( $co_authors ) ) {
			return array();
		}

		$co_authors_details = array();
		$co_authors_data = explode( ',', $co_authors );

        foreach( $co_authors_data as $co_author ) {
			$user = get_user_by( 'login', $co_author );
			if ( $user ) {
				$co_authors_details[] = $user; 
			}
		}
		return $co_authors_details;
	}

	/**
	 * Adds the coauthors details to the post.
	 *
	 * @param string $content
	 * @return string
	 * @since 1.0.0
	 */
	public static function co_authors_post_Link( $content ) {
		global $post;

		// Post type we're capturing.
        $post_types = array( 'post', 'page' );
        
		// Only continue when $post is valid and its a supported post type.
       	if ( ! $post || ! in_array( $post->post_type, $post_types, true ) ) {
			return $content;
		}

        $co_authors = self::get_co_authors( $post->ID );
        
        if ( empty( $co_authors ) ) {
            return $text;
        }
        
        $span_data  = array();

        foreach ( $co_authors as $co_author ) {
			// Omit the default author, incase.
			if ( $post->post_author !== $co_author->ID ) {
             
           		$span_data[] = array(
            	   'link' => ( ! empty( $co_author->user_url ) ? $co_author->user_url : get_author_posts_url( $co_author->ID ) ),
                    'name' => $co_author->display_name,
                );
            }
        }

        // add it to a span tag with data attribute.
        $coauthors_html = '<span class="cx-coa-authors-data" data-cx_coa_co_authors=\'' . json_encode( $span_data ) . '\'></span>';
		return $coauthors_html . $content;
	}

}

CX_COA_Co_Authors::init();
