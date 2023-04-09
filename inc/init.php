<?php 



require get_template_directory() . '/inc/admin/enqueue.php';

//FUNCTIONS
require get_template_directory() . '/inc/functions/setup.php';
require get_template_directory() . '/inc/functions/breadcrumbs.php';
require get_template_directory() . '/inc/functions/walker.php';
require get_template_directory() . '/inc/functions/enqueue.php';
require get_template_directory() . '/inc/functions/structure.php';
require get_template_directory() . '/inc/functions/widgets.php';
require get_template_directory() . '/inc/functions/ajax.php';



//POST TYPES
require get_template_directory() . '/inc/post-types/product.php';
require get_template_directory() . '/inc/post-types/slider.php';
require get_template_directory() . '/inc/post-types/downloadable.php';
require get_template_directory() . '/inc/post-types/gallery.php';
require get_template_directory() . '/inc/post-types/stores.php';


//METABOXES
require get_template_directory() . '/inc/metaboxes/mtb-slider.php';
require get_template_directory() . '/inc/metaboxes/mtb-product.php';
require get_template_directory() . '/inc/metaboxes/mtb-downloadable.php';
require get_template_directory() . '/inc/metaboxes/mtb-gallery.php';
require get_template_directory() . '/inc/metaboxes/mtb-store.php';
require get_template_directory() . '/inc/metaboxes/taxonomies/post.php';
require get_template_directory() . '/inc/metaboxes/mtb-nav-menu.php';


//PRODUCT HOOK
require get_template_directory() . '/inc/product/single-product.php';



//WIDGETS
require get_template_directory() . '/inc/widgets/post-by-category.php';


//gutenberg
require get_template_directory() . '/inc/functions/gutenberg.php';


//menupage
require get_template_directory() . '/inc/menupage/store.php';


//SHORTCODE

require get_template_directory() . '/inc/shortcodes/sc-contact.php';
require get_template_directory() . '/inc/shortcodes/sc-gallery.php';
require get_template_directory() . '/inc/shortcodes/sc-product.php';
require get_template_directory() . '/inc/shortcodes/sc-posts.php';
require get_template_directory() . '/inc/shortcodes/sc-form-contact.php';



//RESTAPI

require get_template_directory() . '/inc/restapi/stores.php';
require get_template_directory() . '/inc/restapi/post.php';

//HELPERS
require get_template_directory() . '/inc/helpers/gallery.php';