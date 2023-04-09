<?php 
if(isset($_GET['action']) && $_GET['action'] == 'editTag'){

    require get_template_directory() . '/inc/menupage/templates/area/edit.php';

}else{

    ?>


<div class="wrap nosubsub">
    <h1 class="wp-heading-inline">Thành phố</h1>

    <?php 
        
        if( 'POST' == $_SERVER['REQUEST_METHOD'] && $_POST['action'] == 'add-tag') {


            if ( ! isset( $_POST['pyre_tag_nonce'] ) || ! wp_verify_nonce( $_POST['pyre_tag_nonce'], 'pyre_add_tag_nonce' ) 
            ) {
                print 'Sorry, your nonce did not verify.';
                return;
            } 

            $referer = $_POST['_wp_http_referer'];
            
            // Check to see if input field for new term is set 
            if ($_POST['tag-name'] == '') {

                echo '<div id="message" class="notice notice-error"><p><strong>Không để trống tên thành phố.</strong></p></div>';
         
            } else if ($_POST['tag-parent'] == '') {

                echo '<div id="message" class="notice notice-error"><p><strong>Chọn khu vực cho thành phố</strong></p></div>';
        
            } else{

                $postType = $_GET['post_type'];
                $taxonomy = $_GET['taxonomy'];
                $newTerm = $_POST['tag-name'];
                $slug = '';
                if($_POST['tag-slug'] != ''){
                    $slug = $_POST['tag-slug'];
                }
               
                $parent = $_POST['tag-parent'];
         
                wp_insert_term($newTerm, $taxonomy, array(
                    'description' => $newTerm,
                    'slug'        => $slug,
                    'parent'      => $parent,
                ));
                ?>
                    <div id="message" class="notice notice-success"><p><strong>Thêm thành phố thành công.</strong></p></div>
                <?php
            }
        }

        if( 'POST' == $_SERVER['REQUEST_METHOD'] && $_POST['action'] == 'delete-tag' && $_POST['taxonomy'] == 'area_store') {



            if ( ! isset( $_POST['pyre_dtag_nonce'] ) || ! wp_verify_nonce( $_POST['pyre_dtag_nonce'], 'pyre_delete_tag_nonce' ) ) {
                print 'Sorry, your nonce did not verify.';
                return;
            } 
            
          
                $postType = $_POST['post_type'];
                $taxonomy = $_POST['taxonomy'];
                $termId = $_POST['tag-id'];
         
                wp_delete_term($termId, $taxonomy);
                ?>
                <div id="message" class="notice notice-success"><p><strong>Xoá thành công.</strong></p></div>
            <?php
        }

    ?>
    <div id="col-container" class="wp-clearfix">

        <div id="col-left">
            <div class="col-wrap">
                <div class="form-wrap">
                    <form action="edit.php?post_type=<?php echo $_GET['post_type'] ?>&page=store-config&taxonomy=<?php echo $_GET['taxonomy']?>" method="POST" class="validate">
                        <input type="hidden" name="action" value="add-tag">
                        <input type="hidden" name="post_type" value="<?php echo $_GET['post_type'] ?>">
                        <input type="hidden" name="taxonomy" value="<?php echo $_GET['taxonomy'] ?>">
                        <input type="hidden" name="_wp_http_referer" value="edit.php?post_type=<?php echo $_GET['post_type'] ?>&page=store-config&taxonomy=<?php echo $_GET['taxonomy']?>">
                        <?php wp_nonce_field( 'pyre_add_tag_nonce', 'pyre_tag_nonce' ); ?>
                        <div class="form-field form-required term-name-wrap">
                            <label for="tag-name">Thành phố</label>
                            <input name="tag-name" id="tag-name" type="text" size="40" aria-required="true">
                            <p>Tên thành phố.</p>
                        </div>
                        <div class="form-field term-slug-wrap">
                            <label for="tag-slug">Đường dẫn</label>
                            <input name="tag-slug" id="tag-slug" type="text" size="40" aria-required="true">
                            <p>Chuỗi cho đường dẫn tĩnh là phiên bản của tên hợp chuẩn với Đường dẫn (URL). Chuỗi này bao gồm chữ cái thường, số và dấu gạch ngang (-).</p>
                        </div>
                        <div class="form-field term-slug-wrap">
                            <label for="tag-slug">Khu vực</label>
                           <select name="tag-parent" id="tag-parent">
                           <option value="">Chọn khu vực</option>
                           <?php 
                            $taxonomy = $_GET['taxonomy'];
                            $terms = get_terms( $taxonomy, array(
                                'hide_empty' => false,
                                'parent' => 0
                            ) );
                           foreach($terms as $term) {?>
                                
                                <option value="<?php echo $term->term_id ?>"><?php echo $term->name ?></option>

                        <?php } ?>
                           </select>
                           <p>Vui lòng chọn khu vực cho Tỉnh/Thành phố</p>
                        </div>
                        <div class="form-field">
                        <p class="submit">
                            <input type="submit" name="submit" id="submit" class="button button-primary"
                                value="Thêm khu vực">
                            <span class="spinner"></span>
                        </p>
                        </div>
                        
                    </form>
                    <a href="edit.php?post_type=store&page=store-config"> ← Back</a>
                </div>
            </div>

        </div>
        <div id="col-right">
                <div class="col-wrap">
                    <table class="wp-list-table widefat fixed striped table-view-list tags">
                        <thead>
                        
                            <tr>
                               
                                <td>Thành phố</td>
                                <td>Khu vực</td>
                            </tr>
                        </thead>
                        <tbody id="the-list">
                        <?php 
                         $taxonomy = 'area_store';
                         $terms = get_terms( $taxonomy, array(
                             'hide_empty' => false,
                             'childless' => true
                         ) );
                        foreach($terms as $term) { 
                            if($term->parent != 0){
                            ?>

                            <tr>
                                <td class="name column-name has-row-actions column-primary">
                                    <strong><?php echo $term->name ?></strong>
                                    <div class="row-actions">
                                        <span class="edit">
                                            <a href="edit.php?post_type=<?php echo $_GET['post_type'] ?>&page=store-config&taxonomy=<?php echo $_GET['taxonomy']?>&tagId=<?php echo $term->term_id ?>&action=editTag">Chỉnh sửa</a> | 
                                        </span>
                                        <span class="delete">
                                        <form id="delete-<?php echo $term->term_id ?>"
                                            action="edit.php?post_type=<?php echo $_GET['post_type'] ?>&page=store-config&taxonomy=<?php echo $taxonomy ?>"
                                            method="POST">
                                            <input type="hidden" name="action" value="delete-tag">
                                            <input type="hidden" name="post_type" value="<?php echo $_GET['post_type'] ?>">
                                            <input type="hidden" name="taxonomy" value="<?php echo $taxonomy ?>">
                                            <?php wp_nonce_field( 'pyre_delete_tag_nonce', 'pyre_dtag_nonce' ); ?>
                                            <input type="hidden" name="tag-id" value="<?php echo $term->term_id ?>">
                                            <input type="submit" class="button-submit" value="Xoá">
                                        </form>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                
                                <?php 
                                  $term_parent =  get_term($term->parent, $taxonomy);

                                  echo $term_parent->name;
                                ?>
                                </td>
                            </tr>

                        <?php } } ?>
                        </tbody>
                    </table>
                </div>
            </div>
    </div>
</div>

<?php 
}