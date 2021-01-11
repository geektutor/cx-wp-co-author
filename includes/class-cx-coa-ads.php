<?php
defined( 'ABSPATH' ) || exit;

/**
 * Class CX_COA_Ads.
 */
class CX_COA_Ads {

    /**
     * Initialiseeee
     */
    public static function init() {

        // Register meta key in REST.
        add_action( 'init', array( __CLASS__, 'register_meta' ) );

		// Add ad to paragraph.
		add_filter( 'the_content', array( __CLASS__, 'place_ad_content' ) ); 

    }

	/**
	 * Register meta keys for block editor.
	 * 
	 * @since 1.0.0
	 */
	public static function register_meta() {
        // Ads.
		register_meta( 'post', 'cx_coa_ad_link', array(
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
     * Place ad content in Post where necessary.
     * 
     * @param string $content
     */
    public static function place_ad_content( $content ) {
		global $post;

		// Post type we're capturing.
   	    $post_types = array( 'post', 'page' );
        
		// Only continue when $post is valid and its a supported post type.
       	if ( ! $post || ! is_single() || ! in_array( $post->post_type, $post_types, true ) ) {
			return $content;
		}

		$ad_link = trim( get_post_meta( $post->ID, 'cx_coa_ad_link', true ) );

		if ( empty( $ad_link ) ) {
			return $content;
		}

		$splitter   = '</p>';
		$paragraphs = explode( $splitter, $content );
		$p_count    = count( $paragraphs ) - 1;

		// No Paragraph?
		if ( $p_count < 1 ) {
			return $content;
		}

        // After what paragraph to add ad content. Note. values in quote are "x to the last paragraph".
		$ad_sections   = array( 4, 10, '3' );
		$section_count = 0; // Use this to know the next paragraph to add ad content :).

		// Is our paragraph less than 10?
		if ( $p_count < 10 ) {
			$ad_sections = array( 5 );
		}

		$ads_content = '<a class="cx-coa-adspace" href="' . esc_attr( $ad_link ) . '" style="margin:50px 0;display:block;">' . $ad_link . '</a>';
		
		for ( $i = 0; $i < count( $paragraphs ); $i++ ) {
			$p_num              = $i + 1;
			$current_ad_section = $ad_sections[ $section_count ];

			// Is the current paragraph equal to the current section we need to add ads?
			if ( $p_num  === $current_ad_section  ) {
				$paragraphs[ $i ] .= $ads_content;
				++$section_count;
			}
		}

		// Don't forget "X to the last".
		foreach ( $ad_sections as $section ) {
			if ( ! is_int( $section ) ) {
				// Not an int, so it's "X to the last" scene.
				$x_to_last = $p_count - $section;

				$paragraphs[ $x_to_last ] .= ( strpos( $paragraphs[ $x_to_last ], $ads_content ) !== false ? '' : $ads_content );
			}
		}
		
		$content = implode( $splitter, $paragraphs );
		return $content;
    }
}

CX_COA_Ads::init();
