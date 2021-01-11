/*jshint esversion: 6 */
jQuery( document ).ready( function( $ ){
	/**
	 * Display coauthors on the frontend.
	 *
	 * @param {node} $authorEl
	 */
    var displayCoAuthors = function( $authorEl ) {
        var authorData = $( '.cx-coa-authors-data' ).data( 'cx_coa_co_authors' );

        if ( undefined === authorData || authorData.length < 1 ) {
            return;
        }

        var html = '';

		authorData.forEach( function( author ) {
			
			authorName = author.name;
			authorLink = author.link;

			html += ', <a href="' + authorLink + '" rel="author">'	+ authorName + '</a>';
		} );

		/** 
		 * Now find the closest span parent of the author element,
		 * this is needed cause different themes have different designs.
		 * This is coded based on the fact that all author close parent element
		 * are span, no matter the theme, if not, oh well :).
		 */
		$parentauthorEl = $authorEl.closest( 'span' );

		// Add to the "posted by" section 🚀.
		$parentauthorEl.append( html );
	};

	displayCoAuthors( $( 'a[rel="author"]' ) );
});
