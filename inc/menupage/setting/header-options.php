<?php 

if ( isset( $_GET['settings-updated'] ) ) {
    // add settings saved message with the class of "updated"
    add_settings_error( 'dvu_messages', 'dvu_messages', __( 'Lưu thành công', 'dvtheme' ), 'updated' );
}

// show error/update messages
    settings_errors( 'dvu_messages' );
 ?>

<div class="wrap">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <div class="dvu-wraper">
            <div class="inner-wrapper">
                <form action="options.php" method="post">
                <?php
               
                    settings_fields( 'mepage_header' );

                    do_settings_sections( 'mepage_header' );
           
                    submit_button( 'Lưu cài đặt' );
                    ?>
                </form>
            </div>
        </div>
	</div>