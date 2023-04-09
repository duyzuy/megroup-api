<div class="wrap nosubsub">
    <h1 class="wp-heading-inline">Sửa thành phố</h1>

    <?php 
        
        $term_edit =  get_term($_GET['tagId'], $_GET['taxonomy']);
        
       
      

        if( 'POST' == $_SERVER['REQUEST_METHOD'] && $_POST['action'] == 'update-tag') {

            $postType = $_GET['post_type'];
            $taxonomy = $_GET['taxonomy'];
            $newTerm = $_POST['tag-name'];
            
            $slug = '';
            if($_POST['tag-slug'] != ''){
                $slug = $_POST['tag-slug'];
            }
            $parent = $_POST['tag-parent'];
            $referer = $_POST['_wp_http_referer'];
            
            // Check to see if input field for new term is set 
            if ($_POST['tag-name'] == '') {

                echo '<p>Vui lòng nhập thành phố</p>';
         
            } else if ($_POST['tag-parent'] == '') {

                echo '<p>Vui lòng chọn khu vực</p>';
        
            } else{
                
                $update = wp_update_term($term_edit->term_id, $taxonomy, array(
                    'name'      =>  $newTerm,
                    'slug'        => $slug,
                    'parent'      => $parent,
                ));
                if ( ! is_wp_error( $update ) ) {
                    $term_edit =  get_term($_GET['tagId'], $_GET['taxonomy']);
                   ?>

                <div id="message" class="notice notice-success">
                    <p><strong>Đã được cập nhật.</strong></p>
                        <p><a href="edit.php?post_type=<?php echo $_GET['post_type'] ?>&page=store-config&taxonomy=<?php echo $_GET['taxonomy']?>">
                        ← Quay lại Chuyên mục	</a></p>
                </div>

                   <?php 
                 
                }
            }
        }
    ?>

    <form id="edittag" action="edit.php?post_type=<?php echo $_GET['post_type'] ?>&page=store-config&taxonomy=<?php echo $_GET['taxonomy']?>&tagId=<?php echo $_GET['tagId'] ?>&action=editTag"
        method="POST" class="validate">
        <input type="hidden" name="action" value="update-tag">
        <input type="hidden" name="post_type" value="<?php echo $_GET['post_type'] ?>">
        <input type="hidden" name="taxonomy" value="<?php echo $_GET['taxonomy'] ?>">
        <input type="hidden" name="_wp_http_referer"
            value="edit.php?post_type=<?php echo $_GET['post_type'] ?>&page=store-config&taxonomy=<?php echo $_GET['taxonomy']?>">

        <table class="form-table" role="presentation">
            <tbody>
                <tr class="form-field form-required term-name-wrap">
                    <th class="row"><label for="tag-name">Thành phố</label></th>
                    <td>
                        <input name="tag-name" id="tag-name" type="text" value="<?php echo $term_edit->name ?>" size="40"
                            aria-required="true">
                        <p class="description">Tên thành phố.</p>
                    </td>
                </tr>
                <tr class="form-field form-required term-name-wrap">
                    <th class="row"><label for="tag-slug">Đường dẫn</label></th>
                    <td>
                        <input name="tag-slug" id="tag-slug" type="text" value="<?php echo $term_edit->slug ?>" size="40"
                            aria-required="true">
                        <p class="description">Chuỗi cho đường dẫn tĩnh là phiên bản của tên hợp chuẩn với Đường dẫn (URL). Chuỗi này bao
                            gồm chữ cái thường, số và dấu gạch ngang (-).</p>
                    </td>
                </tr>
                <tr class="form-field form-required term-name-wrap">
                    <th class="row"><label for="tag-slug">Khu vực</label></th>
                    <td>
                        <select name="tag-parent" id="tag-parent">
                            <option value="">Chọn khu vực</option>
                            <?php 
                                $taxonomy = $_GET['taxonomy'];
                                $terms = get_terms( $taxonomy, array(
                                    'hide_empty' => false,
                                    'parent' => 0
                                ) );
                              
                            foreach($terms as $term) {?>

                                <option value="<?php echo $term->term_id ?>" <?php echo ($term_edit->parent == $term->term_id) ? 'selected' : '' ?> ><?php echo $term->name ?></option>

                            <?php } ?>
                        </select>
                        <p class="description">Vui lòng chọn khu vực cho Tỉnh/Thành phố</p>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="edit-tag-actions">
            <p class="submit">
                <input type="submit" name="submit" id="submit" class="button button-primary" value="Cập nhật">
                <span id="delete-link">
                    <a href="" class="delete">Xoá</a>
                </span>
            </p>
        </div>

    </form>
    <a href="edit.php?post_type=store&page=store-config"> ← Back</a>
</div>