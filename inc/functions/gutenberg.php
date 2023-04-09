<?php 
// function dvu_gutenberg_blocks() {

//     $uri = get_template_directory_uri();
//     wp_register_script( 'column-js', $uri.'/assets/js/admin/gutenberg/global.js', array( 'wp-blocks' ) );

//     register_block_type( 'dvu/column-block', array(
//         'editor_script' =>  'column-js'
//     ) );

// }
// add_action( 'init', 'dvu_gutenberg_blocks' );



//filter block type 
add_filter( 'allowed_block_types', 'dvu_blocktype_register' );

function dvu_blocktype_register( $allowed_block_types ) {

    return array(
        'core/paragraph',
        'core/image',
        'core/heading',
        'core/list',
        'core/quote',
        'core/audio',
        'core/cover',
        'core/file',
        'core/code',
        'core/classic',
        'core/table',
        'core/group',
        'core/columns',
        'core/column',
        'core/html',
        'core/gallery',
        'core/button',
        'core/nextpage',
        'core/separator',
        'core/media-text',
        'core/spacer',
        'core/shortcode',
        'core-embed/youtube',
        'core-embed/facebook',
        'core-embed/instagram',
        'core/pullquote'

    );

}



