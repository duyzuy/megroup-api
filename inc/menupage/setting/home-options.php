<?php 

	if ( isset( $_GET['settings-updated'] ) ) {
		add_settings_error( 'dvu_messages', 'dvu_messages', __( 'Lưu thành công', 'dvtheme' ), 'updated' );
	}

	settings_errors( 'dvu_messages' );
	?>

<div class="wrap">
  <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
  <div class="dvu-option-container">
    <div class="inner-container">
      <form action="options.php" method="post">
        <?php
					settings_fields( 'mepage' );
					do_settings_sections( 'mepage' );
					submit_button( 'Lưu cài đặt' );
				?>
      </form>
    </div>
  </div>
</div>