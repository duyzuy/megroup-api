<?php 

function map_shortcode($atts, $content = null) {

	wp_enqueue_script( 'scg-map-js');
	
    $attr = shortcode_atts( array(
		'title'   => 'ĐẠI LÝ CHÍNH THỨC',
		'id'	=>	45
	), $atts );
	
	   
    ob_start();

	$args = array('post_type' => 'store',
		'tax_query' => array(
			array(
				'taxonomy' => 'cat_store',
				'field' => 'term_id',
				'terms' => $attr['id'],
			),
		),
	);
	
	$the_query = new WP_Query( $args );



	?>
	<div class="content ">
		<h1 class="title"><?php echo $attr['title'] ?></h1>
		<div id="location" class="location" data-type-id="<?php echo $attr['id'] ?>">
			<div class="location__content">
				<div class="location__inner__content">
					<div class="location__form__action">
						<div class="form-group row">
							<label class="col col-12 col-lg-5 col-form-label col-form-label-sm">Tìm kiếm cửa hàng</label>
							<div class="col col-12 col-lg-7">
								<div class="form-group">
									<select class="custom-select" id="area-select">
										<option value="">Chọn vùng</option>
										<?php 
											$terms = get_terms( 'area_store', array(
												'hide_empty' => false,
												'parent' => 0,
												'orderby'   =>  'name',
												'order'    => 'ASC'
											) );

											foreach($terms as $term){
												echo '<option value="'.$term->term_id.'">'. $term->name.'</option>';
											}

										?>
									</select>
								</div>
								<div class="form-group">
									<select class="custom-select" id="cities-select">
										<option selected>Chọn thành phố</option>
									</select>
								</div>
							</div>
						</div>
					</div>
					<div id="store_list_items" class="location__lists list__2">
						<?php 
								if ( $the_query->have_posts() ) {
									$website_link = '';
									$locations = array();
									$i = 0;
									while ( $the_query->have_posts() ) {
										$the_query->the_post();
										$address = get_post_meta( get_the_ID(), '_store_address', true);
										$email = get_post_meta( get_the_ID(), '_store_email', true);
										$phone = get_post_meta( get_the_ID(), '_store_phone', true);
										$hotline = get_post_meta( get_the_ID(), '_store_hotline', true);
										$fax = get_post_meta( get_the_ID(), '_store_fax', true);
										$website = get_post_meta( get_the_ID(), '_store_website', true);
										$website_link = get_post_meta( get_the_ID(), '_store_website_link', true);
										$lat = get_post_meta( get_the_ID(), '_store_lat', true);
										$lang = get_post_meta( get_the_ID(), '_store_lang', true);

										$locations[$i]['title'] = get_the_title();
										$locations[$i]['position']['lat'] = $lat;
										$locations[$i]['position']['lng'] = $lang;
										?>
										<div class="location__item">
											<h4 class="title"><?php the_title() ?></h4>
											<ul class="location__item__info">
												<?php 
													if($address){
														echo '<li><i class="dvu dvu-pin mr-2"></i>Địa chỉ: '.$address.'</li>';
													}
													if($phone){
														echo '<li><i class="dvu dvu-phone mr-2"></i>Điện thoại: '.$phone.'</li>';
													}
													if($hotline){
														echo '<li><i class="dvu dvu-phone mr-2"></i>Hotline: '.$hotline.'</li>';
													}
													if($email){
														echo '<li><i class="dvu dvu-email mr-2"></i>Email: '.$email.'</li>';
													}
													if($fax){
														echo '<li><i class="dvu dvu-inbox mr-2"></i>Fax: '.$fax.'</li>';
													}
													
													if($website){
														echo '<li><i class="dvu dvu-web mr-2"></i>Web: <a href="'.$website_link.'" target="_blank">'.$website.'</a></li>';
													}
												?>
											</ul>
										</div>
										<?php 
									$i++;
									}
									
							
								} else {
									// no posts found
								}
								wp_reset_postdata(); // reset global $post;
						?>
					</div>
				</div>
			</div>
			<div id="maper" data-pins='<?php echo json_encode($locations) ?>'> </div>
		</div>
	</div>

<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDyVvuRxbLP8kRu23TqUrWDs3xvf9JHSBk&callback=initMap"></script>
<?php
	return ob_get_clean();

}
add_shortcode( 'map', 'map_shortcode' );