<?php 

require get_template_directory() . '/vendor/autoload.php';
use Automattic\WooCommerce\Client;

$woocommerce = new Client(
    'http://localhost/dylan-woo-cms',
    'ck_c0b1ff30fda323e235368d1f602a443d96cf4261',
    'cs_93404a268fe05a065d25bcbc7be3e11c3861aab5',
    [
      'version' => 'wc/v3',
    ]
);


require get_template_directory() . '/inc/admin/enqueue.php';

//FUNCTIONS
require get_template_directory() . '/inc/functions/setup.php';
require get_template_directory() . '/inc/functions/structure.php';
require get_template_directory() . '/inc/functions/ajax.php';



//POST TYPES
// require get_template_directory() . '/inc/post-types/product.php';
require get_template_directory() . '/inc/post-types/slider.php';
require get_template_directory() . '/inc/post-types/product-brand.php';

// require get_template_directory() . '/inc/post-types/downloadable.php';
// require get_template_directory() . '/inc/post-types/gallery.php';
// require get_template_directory() . '/inc/post-types/stores.php';


//METABOXES
require get_template_directory() . '/inc/metaboxes/mtb-slider.php';
require get_template_directory() . '/inc/metaboxes/taxonomies/product-brand.php';
require get_template_directory() . '/inc/metaboxes/taxonomies/post.php';
require get_template_directory() . '/inc/metaboxes/mtb-nav-menu.php';
// require get_template_directory() . '/inc/metaboxes/mtb-product.php';
// require get_template_directory() . '/inc/metaboxes/mtb-downloadable.php';
// require get_template_directory() . '/inc/metaboxes/mtb-gallery.php';
// require get_template_directory() . '/inc/metaboxes/mtb-store.php';

//PRODUCT HOOK
// require get_template_directory() . '/inc/product/single-product.php';



//WIDGETS
// require get_template_directory() . '/inc/widgets/post-by-category.php';


//gutenberg
require get_template_directory() . '/inc/functions/gutenberg.php';


//menupage
// require get_template_directory() . '/inc/menupage/store.php';
require get_template_directory() . '/inc/menupage/me-setting.php';



//SHORTCODE

// require get_template_directory() . '/inc/shortcodes/sc-contact.php';
// require get_template_directory() . '/inc/shortcodes/sc-gallery.php';
// require get_template_directory() . '/inc/shortcodes/sc-product.php';
// require get_template_directory() . '/inc/shortcodes/sc-posts.php';
// require get_template_directory() . '/inc/shortcodes/sc-form-contact.php';

//CUSTOM WOOCOMMERCE 

require get_template_directory() . '/inc/woocommerce/attributes-metabox.php';
require get_template_directory() . '/inc/woocommerce/term-metabox.php';



//RESTAPI
require get_template_directory() . '/inc/restapi/config.php';
require get_template_directory() . '/inc/restapi/stores.php';
require get_template_directory() . '/inc/restapi/post.php';
require get_template_directory() . '/inc/restapi/slider.php';
require get_template_directory() . '/inc/restapi/menu.php';
require get_template_directory() . '/inc/restapi/product-attributes.php';
require get_template_directory() . '/inc/restapi/product-by-ids.php';
require get_template_directory() . '/inc/restapi/product-category.php';
require get_template_directory() . '/inc/restapi/product-review.php';


//auth
require get_template_directory() . '/inc/restapi/jwt-auth.php';

require get_template_directory() . '/inc/restapi/auth.php';


//user
require get_template_directory() . '/inc/restapi/user/profile.php';
require get_template_directory() . '/inc/restapi/user/create.php';

//rest wc

require get_template_directory() . '/inc/restapi/woo/product.php';
require get_template_directory() . '/inc/restapi/woo/product-brand.php';
require get_template_directory() . '/inc/restapi/woo/setting.php';
require get_template_directory() . '/inc/restapi/woo/product-shipping.php';
require get_template_directory() . '/inc/restapi/woo/payment-gateways.php';
require get_template_directory() . '/inc/restapi/woo/woo-data.php';
require get_template_directory() . '/inc/restapi/woo/shipping-methods.php';
require get_template_directory() . '/inc/restapi/woo/shipping-zones.php';
require get_template_directory() . '/inc/restapi/woo/order.php';





//HELPERS
// require get_template_directory() . '/inc/helpers/gallery.php';
require get_template_directory() . '/inc/helpers/curl.php';
require get_template_directory() . '/inc/helpers/validate.php';
require get_template_directory() . '/inc/helpers/woocommerce.php';
require get_template_directory() . '/inc/helpers/product.php';




