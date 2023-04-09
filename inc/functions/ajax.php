<?php 
add_action('wp_ajax_nopriv_dvu_posts_list', 'get_post_by_cat');
add_action('wp_ajax_dvu_posts_list', 'get_post_by_cat');
function get_post_by_cat(){

    $term_id = $_POST['id'];
    $nonce = $_POST['nonce'];

    $nonce = wp_verify_nonce( $nonce, 'dvu_ajax_nonce' );

    if(!$nonce){
        echo 'No allow please';
        die();
    }

    $args = array(
        'post_type'     =>  'post',
        'cat'   =>  $term_id,
        'posts_per_page'     =>  5,
        'post_status' => 'publish',
    );
    $output = '';
    $query = new WP_Query( $args );
    if($query->have_posts()): 

        while($query->have_posts()): $query->the_post();
            $output .= '<div class="box__idea ajax__animation"><div class="inner__box">';
            $output .=  '<a href="'. get_the_permalink( get_the_ID()) .'" alt="'.get_the_title().'">';  
                $url = '';
                if(has_post_thumbnail()):
                    $url = get_the_post_thumbnail_url( get_the_ID(), 'large' );
                endif;
                       
            $output .= '<div class="image" style="background-image: url('.$url.')"></div>';
            $output .= '<div class="box__content"><h3 class="title">'. get_the_title() .'</h3></div>';
            $output .=  '</a></div></div>'   ;              

        endwhile;
        wp_reset_postdata();

    else:
        $output .= "no post found";
    endif;

    echo wp_json_encode( $output);

    die();
}   


//send mail

add_action( 'wp_ajax_nopriv_dvu_sendmail', 'dvu_send_register' );
add_action('wp_ajax_dvu_sendmail', 'dvu_send_register');
function dvu_send_register(){

       $email = $_POST['email'];
       $nonce = $_POST['nonce'];

       if ( ! wp_verify_nonce( $nonce, 'dvu_ajax_nonce' ) ){
            die ( 'you can\'t not allowed');
       }
          
       
    $data = array(
        'email'=> $email,
    );

       $to = 'vutruongduy2109@gmail.com';

       $subject = 'SCG - Registration -' .$email;

       $headers[] = 'From: '.get_bloginfo('name').'<'.$to.'>';
       $headers[] = 'Reply-to: '.$name.'<'.$email.'>';
       $headers[] = 'Content-Type: text/html; charset=UTF-8';
       $infor = 'Email đăng ký nhận thông tin <br/> Email: '.$email.'<br/>';

        $send_mail = wp_mail($to, $subject, $infor, $headers);
        if($send_mail){
            $data['status'] = 'success';
            
        }else{
                $data['status'] = 'error';
        }
        echo wp_json_encode($data); 

      
   die();
}

