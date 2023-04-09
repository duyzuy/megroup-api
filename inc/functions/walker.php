<?php 





class Dv_Nav_Walker extends Walker_Nav_Menu
{
    public $isMegaMenu;

    public $isFlexMenu;

    public $count;

    public function __construct(){
        
        $this->isMegaMenu = 0;

        $this->isFlexMenu = 0;

        $this->count = 0;
    }
 
    public function start_lvl(&$output, $depth = 0, $args = array())
    {
        
        $indent = str_repeat("\t", $depth);
        $submenu = ($depth > 0) ? ' sub-menu' : '';
       
        if($this->isMegaMenu != 0 && $depth == 0){

            $output .= "\n$indent<div class='dropdown-megamenu'><ul class=\"sub-megamenu dv-top-ul$submenu depth_$depth\" >\n";

        } else if($this->isFlexMenu != 0 && $depth == 0){
            
            $output .= "\n$indent<div class='dropdown-submenu'><ul class=\"dvu-submenu dv-top-ul$submenu depth_$depth\" >\n";
        
        }else{
            $output .= "\n$indent<ul class=\"dvu-submenu dv-top-ul$submenu depth_$depth\" >\n";
        }

        if($this->isMegaMenu != 0 && $depth == 0){
            $output .= "<li class=\"megamenu-column\"><ul>\n";
        }

    }
    public function end_lvl(&$output, $depth = 0, $args = array())
    {
      
        if($this->isMegaMenu != 0 && $depth == 0){
            $output .= "</ul></li>";
    
        }
      
        $output .= "</ul>";
       
    }
    public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
    {
        
       
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        $li_attributes = '';
        $class_names = $value = '';
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        // managing divider: add divider class to an element to get a divider before it.
     

        
        if ($this->isMegaMenu != 0 && $this->isMegaMenu != intval($item->menu_item_parent) && $depth == 0) {
            $this->isMegaMenu = 0;
        }

        if ($this->isFlexMenu != 0 && $this->isFlexMenu != intval($item->menu_item_parent) && $depth == 0) {
            $this->isFlexMenu = 0;
        }
        
       
        if(array_search('divider_column', $classes) !== false){
            $output .= "</ul></li><li class=\"megamenu-column\"><ul>\n";
        }
       
        $divider_class_position = array_search('line', $classes);
        if ($divider_class_position !== false) {
            $output .= "<li class=\"nav-line\"></li>\n";
            unset($classes[$divider_class_position]);
        }

        if (array_search('megamenu', $classes) !== false) {
            $this->isMegaMenu = $item->ID;
           
        }

        if (array_search('flexmenu', $classes) !== false) {
            $this->isFlexMenu = $item->ID;
           
        }
     
        $classes[] = ($args->has_children) ? 'has-submenu' : '';
        $classes[] = ($item->current || $item->current_item_ancestor) ? 'active' : '';
        $classes[] = 'nav-item menu-item-'.$item->ID;
        if ($depth && $args->has_children) {
            $classes[] = 'dropdown-submenu';
        }

        $class_names = implode(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));

        $class_names = ' class="'.esc_attr($class_names).'"';

        $id = apply_filters('nav_menu_item_id', 'menu-item-'.$item->ID, $item, $args);
        $id = strlen($id) ? ' id="'.esc_attr($id).'"' : '';
       
        $output .= $indent.'<li'.$id.$value.$class_names.$li_attributes.'>';
        

        $attributes = !empty($item->attr_title) ? ' title="'.esc_attr($item->attr_title).'"' : '';
        $attributes .= !empty($item->target) ? ' target="'.esc_attr($item->target).'"' : '';
        $attributes .= !empty($item->xfn) ? ' rel="'.esc_attr($item->xfn).'"' : '';
        $attributes .= !empty($item->url) ? ' href="'.esc_attr($item->url).'"' : '';
        $attributes .= ($args->has_children) ? ' class="nav-link nav-link-has-child"' : '';
        
        $item_output = $args->before;
        $item_output .= '<a'.$attributes.' class="nav-link">';

        //check if item has image featured
        if (array_search('nav-featured', $classes) !== false) {
           

           
         
            if($depth == 1){

                $image_id = get_post_meta( $item->ID, 'jt_hover_image', true );
    
               

                $item_output .= "<img src=\"".wp_get_attachment_image_src( $image_id , 'medium')[0]."\" class=\"img-fluid\"><p class=\"title\">";
              
            }           
           
        }
      
        $item_output .= $args->link_before.apply_filters('the_title', $item->title, $item->ID).$args->link_after;
        
        if (array_search('nav-featured', $classes) !== false) {
            $item_output .= '</p>';
        }
            // add support for menu item title
            if (strlen($item->attr_title) > 2) {
                $item_output .= '<span class="sub">'.$item->attr_title.'</span>';
            }
            // add support for menu item descriptions
            if (strlen($item->description) > 2) {
                $item_output .= '</a> <p class="sub">'.$item->description.'</p>';
            }

        $item_output .= (($depth == 0 || 1) && $args->has_children) ? '</a>' : '</a>';
        $item_output .= $args->after;
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }
    public function display_element($element, &$children_elements, $max_depth, $depth = 0, $args, &$output)
    {
        if (!$element) {
            return;
        }
        $id_field = $this->db_fields['id'];
        //display this element
        if (is_array($args[0])) {
            $args[0]['has_children'] = !empty($children_elements[$element->$id_field]);
        } elseif (is_object($args[0])) {
            $args[0]->has_children = !empty($children_elements[$element->$id_field]);
        }
        $cb_args = array_merge(array(&$output, $element, $depth), $args);
        call_user_func_array(array(&$this, 'start_el'), $cb_args);
        $id = $element->$id_field;
        // descend only when the depth is right and there are childrens for this element
        if (($max_depth == 0 || $max_depth > $depth + 1) && isset($children_elements[$id])) {
            foreach ($children_elements[ $id ] as $child) {
                if (!isset($newlevel)) {
                    $newlevel = true;
              //start the child delimiter
              $cb_args = array_merge(array(&$output, $depth), $args);
                    call_user_func_array(array(&$this, 'start_lvl'), $cb_args);
                }
                $this->display_element($child, $children_elements, $max_depth, $depth + 1, $args, $output);
            }
            unset($children_elements[ $id ]);
        }
        if (isset($newlevel) && $newlevel) {
            //end the child delimiter
          $cb_args = array_merge(array(&$output, $depth), $args);
            call_user_func_array(array(&$this, 'end_lvl'), $cb_args);
        }
        //end this element
        $cb_args = array_merge(array(&$output, $element, $depth), $args);
        call_user_func_array(array(&$this, 'end_el'), $cb_args);
    }
  
 
 
}
    
    class Dv_Nav_Mobile_Walker extends Walker_Nav_Menu
    {
        
            public function check_current($classes)
            {
                return preg_match('/(current[-_])|active|dropdown/', $classes);
            }
            public function start_lvl(&$output, $depth = 0, $args = array())
            {
                $output .= ($depth == 0) ? "\n<ul class=\"sub-menu navbar-nav\">\n" : "\n<ul class=\"sub-menu navbar-nav\">\n";
            }
            public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
            {
                $item_html = '';
                $li_attributes = '';
                $class_names = '';
                $classes = empty($item->classes) ? array() : (array) $item->classes;
               

                parent::start_el($item_html, $item, $depth, $args);
              
                $class_names = implode(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
                $class_names = ' class="'.esc_attr($class_names).'"';

                $item_html = str_replace('<li', '<li class="nav-item" ', $item_html);
                $item_html = str_replace('<a', '<a class="nav-link" ', $item_html);
                if ($item->is_dropdown && ($depth === 0)) {
                    $item_html = str_replace('<li', '<li class="nav-item has-sub-menu" ', $item_html);
                    $item_html = str_replace('</a>', '<i class="fas fa-chevron-down"></i></a>', $item_html);
                } elseif (stristr($item_html, 'li class="divider')) {
                    $item_html = preg_replace('/<a[^>]*>.*?<\/a>/iU', '', $item_html);
                } elseif (stristr($item_html, 'li class="dropdown-header')) {
                    $item_html = preg_replace('/<a[^>]*>(.*)<\/a>/iU', '$1', $item_html);
                }
                elseif($item->is_dropdown && ($depth >= 0)){
                    $item_html = str_replace('<a', '<a class="nav-sub-link" style="font-weight: bold" ', $item_html);
                    $item_html = str_replace('</a>', '</a>', $item_html);
                }
                
                $item_html = apply_filters('roots_wp_nav_menu_item', $item_html);
                $output .= $item_html;
            }
            public function display_element($element, &$children_elements, $max_depth, $depth = 0, $args, &$output)
            {
                $element->is_dropdown = ((!empty($children_elements[$element->ID]) && (($depth + 1) < $max_depth || ($max_depth === 0))));
                if ($element->is_dropdown) {
                    $element->classes[] = 'dv-nav-mobile';
                }
                if ($element && ($depth === 1)) {
                    $element->classes[] = 'dv-nav-dept-1';
                }
                if ($element && ($depth === 2)) {
                    $element->classes[] = 'dv-nav-dept-2';
                }
                parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
            }
            public function end_lvl(&$output, $depth = 0, $args = array())
            {
                $output .= ($depth == 0) ? "\n</ul>\n" : "\n</ul>\n";
            }
        }
