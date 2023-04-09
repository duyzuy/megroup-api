<?php 

 if( 'POST' == $_SERVER['REQUEST_METHOD'] && $_POST['action'] == 'delete-tag' && $_POST['taxonomy'] == 'type_store') {



    if ( ! isset( $_POST['pyre_dtag_nonce'] ) || ! wp_verify_nonce( $_POST['pyre_dtag_nonce'], 'pyre_delete_tag_nonce' ) ) {
        print 'Sorry, your nonce did not verify.';
        return;
    } 
    
  
        $postType = $_POST['post_type'];
        $taxonomy = $_POST['taxonomy'];
        $termId = $_POST['tag-id'];
 
        wp_delete_term($termId, $taxonomy);
    ?>
    <div id="message" class="notice notice-success">
        <p><strong>Đã xoá thành công.</strong></p>
    </div>
<?php } ?>



<div class="col-wrap">
    <div class="pyre_col-header content_space_between pyre_flex">
        <h2 class="wp-heading-inline">Loại cửa hàng</h2>
        <p> <a href="edit.php?post_type=store&page=store-config&taxonomy=type_store"
                class="button button-primary">Thêm</a></p>

    </div>
    <table class="wp-list-table widefat fixed table-view-list tags">
        <thead>
            <?php
                            
                            $taxonomy = 'type_store';
                         
                         
                            $terms = get_terms( $taxonomy, array(
                                'hide_empty' => false,
                                'parent' => 0,
                                'orderby'   =>  'name',
                                'order'    => 'ASC'
                            ) );

                            ?>
            <tr>
                <th>Loại cửa hàng</th>


            </tr>
        </thead>
        <tbody>

            <?php foreach($terms as $term) {?>
            <tr>
                <td class="name column-name has-row-actions column-primary">
                    <?php echo $term->name ?>
                <div class="pyre-action">
                    <span class="edit">
                        <a
                            href="edit.php?post_type=<?php echo $_GET['post_type'] ?>&page=store-config&taxonomy=<?php echo $taxonomy ?>&tagId=<?php echo $term->term_id ?>&action=editTag">Chỉnh
                            sửa</a> |
                    </span>
                    <span class="delete">
                        <form id="delete-<?php echo $term->term_id ?>"
                            action="edit.php?post_type=<?php echo $_GET['post_type'] ?>&page=store-config"
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

            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>