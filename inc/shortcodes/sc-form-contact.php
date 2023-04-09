<?php 

function dvu_form_contact( $atts ) {
    $attr = shortcode_atts( array(
        'title'     => 'Ý tưởng kiến trúc',
        'link'      =>  '',
        'ids'       =>  '13,14,16,17',
        'default'   =>  '13'
    ), $atts );
   
    ob_start();

   
    ?>
        <div class="wrap__form form__contact">
            <form id="dvu_registerform" action="">
                <div class="form-group form-inline">
                    <input type="text" class="form-control" name="email" placeholder="Nhập email của bạn...">
                    <button type="submit" class="btn btn-danger btn-submit js__registration">Gửi</button>
                </div>
                <p class="message"></p>
            </form>
        </div>

<?php 
	return ob_get_clean();
}
add_shortcode( 'dvu_form', 'dvu_form_contact' );