<?php
defined( 'ABSPATH' ) || exit;

/**
 * Class CX_COA_Post_Meta.
 */
class CX_COA_Post_Meta {

    /**
     * Initialiseeee
     */
    public static function init() {
		// Save Meta values.
		add_action( 'save_post', array( __CLASS__, 'meta_save' ) );
    }

	/**
	 * Save the Meta Values.
     *
	 * @since 1.0
	 * @param  int $post_id the ID of the post we're saving
	 * @return int
	 */
	public static function meta_save( $post_id ) {

		// Check to see if this is an autosafe and if the nonce is verified.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// Check permissions.
		if ( 'page' === $_POST['post_type'] && ! current_user_can( 'edit_page', $post_id ) ) {
			return $post_id;
		} else if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}

        update_post_meta( $post_id, 'cx_coa_co_authors', sanitize_text_field( $_POST['cx_coa_co_authors'] ) );
        update_post_meta( $post_id, 'cx_coa_ad_link', sanitize_text_field( $_POST['cx_coa_ad_link'] ) );

		return $post_id;
	}

}

CX_COA_Post_Meta::init();
