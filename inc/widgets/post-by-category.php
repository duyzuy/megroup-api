<?php 


class Postbycategory extends WP_Widget {
	
	function __construct() {
		// Instantiate the parent object
		$widget_ops = array(
			'classname'	=>	'dv-archive-post',
			'description'	=>	'Custom widget for display archive post',
		);
		parent::__construct( 'dv_archive_post_themes', 'DVU - Post by cat', $widget_ops );
	}
	function form( $instance ) {
		
		// Output admin widget options form
		$title 	= (!empty($instance['title']) ? $instance['title'] : 'Post by archive themes');
		$number = (!empty($instance['number']) ? $instance['number'] : 4);
		$catename = (!empty($instance['catename']) ? $instance['catename'] : '--Select Category--');
		
		
		$output = '<p>';
		$output .= '<label for="'.esc_attr($this->get_field_id('title')).'">Title</label>';
		$output .= '<input type="text" class="widefat" id="'.esc_attr($this->get_field_id('title')).'" name ="'.esc_attr($this->get_field_name('title')).'" value="'.esc_attr($title).'">';
		$output .= '</p>';

	
		$terms = get_terms( array(
			'taxonomy' => 'category',
			'hide_empty' => false,
        ) );
       
          

         $output .=   '<p><label for="'. esc_attr($this->get_field_id('catename')).'">Select category</label>';
		 $output .=	 '<select class="widefat" id="'. esc_attr($this->get_field_id('catename')).'" name="'. esc_attr($this->get_field_name('catename')).'" type="text">';
         $output .=	    '<option value="">--Select Category--</option>';               
                        foreach ($terms as $term){
							$term_name = $term->name;
							$term_id = $term->term_id;
						
                            $output .=	'<option value="'.esc_attr($term_id).'" '.esc_attr( $catename == $term_id ? "selected" : "" ).'>'.esc_attr($term_name).'</option>';
						}
				
        $output .='</select></p>';                
     	
		$output .= '<p>';
		$output .= '<label for="'.esc_attr($this->get_field_id('number')).'">Number post display</label>';
		$output .= '<input type="number" class="widefat" id="'.esc_attr($this->get_field_id('number')).'" name ="'.esc_attr($this->get_field_name('number')).'" value="'.esc_attr($number).'">';
		$output .= '</p>';
		
		echo $output;
	}
	function update( $new_instance, $old_instance ) {
		// Save widget options
		$instance =  $old_instance;
		$instance['title'] = (!empty($new_instance['title']) ? strip_tags($new_instance['title']) : '');
		$instance['number'] = (!empty($new_instance['number']) ? strip_tags($new_instance['number']) : 4);
        $instance['catename'] = (!empty($new_instance['catename']) ? $new_instance['catename'] : '--Select Category--');
		return $instance;
	}


	function widget( $args, $instance ) {
		// Widget output
		global $post;
		$number = absint( $instance['number'] );
		$cateid = absint($instance['catename']);
		if(isset($cateid)){
			$post_args = array(
				'post_type'			=>	'post',
				'posts_per_page'		=>	$number,
				'cat'	=> $cateid,
			);
		}
		else{
			$post_args = array(
				'post_type'			=>	'post',
				'posts_per_page'		=>	$number,

			);
		}
	
		echo $args['before_widget'];
		echo '<div id="cate-post-'.$cateid.'">';

			if(!empty($instance['title']) ):
				echo $args['before_title'].apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
			endif;
			$query = new WP_Query( $post_args );
			if($query -> have_posts()):
				echo '<ul id="cate-'.$cateid.'" class="category-lists-post">';
				$output ='';
				while($query -> have_posts()): $query -> the_post();
					$link = get_the_permalink();
					$title = get_the_title();
                    $excerpt = get_the_excerpt();
                    $url = '';
                    $alt = '';
					if(has_post_thumbnail()){
                        $url = get_the_post_thumbnail_url($post->ID, 'thumbnail');
                        $alt = get_the_post_thumbnail_caption( $post->ID );
					}
					

				$output .=	'<li class="item">';
				$output .=	'<a href="'.$link.'" title="'.$title.'"><img class="img-responsive" src="'.$url.'" alt="'.$alt.'">';
				$output .=	'<h4 class="title h6">'.$title.'</h4>';
				$output  .=	'</a></li>';
				endwhile;
				wp_reset_postdata();
				echo $output;
				echo '</ul>';
			
			else: 
				echo 'Bài viết đang cập nhật';
			endif;
		echo '</div>';
		echo $args['after_widget'];

	}

	

	
}

function dvu_post_by_category() {
	register_widget( 'Postbycategory' );
}

add_action( 'widgets_init', 'dvu_post_by_category' );