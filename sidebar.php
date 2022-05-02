<?php

$menu_to_display = '';
$mainMenu = '';


function PointDEVGetNavMenu() {
 
    $mainMenu = wp_get_nav_menu_items('nutrizionista-main-menu');
    $menu = array();
    $submenu = array();

    foreach ($mainMenu as $m) {
        if (empty($m->menu_item_parent)) {
            $menu[$m->ID] = array();
            $menu[$m->ID]['ID'] = $m->ID;
            $menu[$m->ID]['title'] = $m->title;
            $menu[$m->ID]['url'] = $m->url;
            $menu[$m->ID]['classes'] = $m->classes;
            $menu[$m->ID]['children'] = array();
        }
    }

    foreach ($mainMenu as $m) {
        if ($m->menu_item_parent) {
            $submenu[$m->ID] = array();
            $submenu[$m->ID]['ID'] = $m->ID;
            $submenu[$m->ID]['title'] = $m->title;
            $submenu[$m->ID]['url'] = $m->url;
            $submenu[$m->ID]['classes'] = $m->classes;
            $menu[$m->menu_item_parent]['children'][$m->ID] = $submenu[$m->ID];
        }
    }
    return $menu;
     
}

function PointDEVGetMenuToDisplay ($menus) {
 
    if ( !is_array($menus) ){

        return false;

    }
    
    $result = '';
    $hasChild = false;

    foreach( $menus as $menu ){

        if( isset($menu['children']) && !empty($menu['children']) ){

            $hasChild = true;

        } else {
            $hasChild = false;
        }

        // Add menu icons, which are parsed by tag <i> with certain class

        if ( is_array($menu['classes']) ){

            $classesToDisplay = '';

            foreach( $menu['classes'] as $class ){

                if ( !empty($class) ){
                    $classesToDisplay .= $class . ' ';
                }

            }

        } else {

            $classesToDisplay = '';

        }
        
        $menu_to_display = '<li><a class="'. ($hasChild == true ? 'has-arrow' : '') .' waves-effect waves-dark" href="' . $menu['url'] . '" aria-expanded="false">';
        $menu_to_display .= '<i class="'. $classesToDisplay .'"></i>'; // Icons on the left
        $menu_to_display .= '<span class="hide-menu">';
        $menu_to_display .= $menu['title'];  // the menu title
        $menu_to_display .= '</span></a>';

        if( $hasChild == true ){

            $menu_to_display .= '<ul aria-expanded="false" class="collapse">';

            foreach($menu['children'] as $children){
                
                $menu_to_display .= '<li><a href="' . $children['url'] . '">' . $children['title'] . '</a></li>';

            }

            $menu_to_display .= '</ul>';
        }

        $menu_to_display .= '</li>';

        $result .= $menu_to_display;
    }

    return $result;
     
}

// Get Nutritionist user to display on frontend
function PointDEVGetNutritionistUserDisplay () {

    global $current_user;

    if ( ( is_user_logged_in() && in_array( 'nutritionist', $current_user->roles) )
    || ( is_user_logged_in() && in_array( 'administrator', $current_user->roles) ) ||
    ( is_user_logged_in() && in_array( 'nutritionist-admin', $current_user->roles) ) ){
        
        $userInfo = array();

        $userInfo['ID'] = $current_user->ID;
        $userInfo['user_login'] = $current_user->data->user_login;
        $userInfo['display_name'] = $current_user->data->display_name;
        $userInfo['user_avatar'] = get_avatar( get_the_author_meta( $current_user->ID ), 32 );
        $pattern = '/photo/i';

        // HTML markup to display
        $user_to_display = '<li class="user-pro"><a class="has-arrow waves-effect waves-dark" href="#" aria-expanded="false">';
        $user_to_display .= preg_replace( $pattern, 'photo img-circle', $userInfo['user_avatar'] ); // Image on the left
        $user_to_display .= '<span class="hide-menu">'. $userInfo['display_name'] .'</span></a>';

        // User Profile pages
        $all_pages = ( new WP_Query() )->query( [ 
            'post_type' => 'page', 
            'posts_per_page' => -1,
            'order' => 'ASC',
            'orderby' => 'menu_order'
        ] );
        
        $about_id = 47; // HERE IS THE ID OF THE TEST USER PAGE
        
        $about_childrens = get_page_children( $about_id, $all_pages );
        // var_dump($about_childrens);

        if ( is_array($about_childrens) && count($about_childrens) > 0 ){

            $user_to_display .= '<ul aria-expanded="false" class="collapse">';

            foreach ( $about_childrens as $child ){

                $user_to_display .= '<li><a href="'. get_permalink( $child->ID ) .'">';
                
                if ( $child->post_name == 'il-mio-profilo' ) {
                    $user_to_display .= '<i class="ti-user">'; // Icons on the left
                } else if ( $child->post_name == 'il-mio-equilibrio' ) {
                    $user_to_display .= '<i class="ti-wallet">'; // Icons on the left
                } else if ( $child->post_name == 'impostazioni-dellaccount' ) {
                    $user_to_display .= '<i class="ti-settings">'; // Icons on the left
                } else if ( $child->post_name == 'disconnettersi' ) {
                    $user_to_display .= '<i class="fa fa-power-off">'; // Icons on the left
                }
                
                $user_to_display .= '</i> '. esc_html( get_the_title($child->ID) ) .'</a></li>';

            }

            $user_to_display .= '</ul>';

        }
        

        $user_to_display .= '</li>';

        return $user_to_display;

    } else {
        return false;
    }

}


$mainMenu = PointDEVGetNavMenu();
$menu_to_display = PointDEVGetMenuToDisplay($mainMenu);
$user_to_display = PointDEVGetNutritionistUserDisplay();
// var_dump($mainMenu);



// $test = wp_get_nav_menu_items('nutrizionista-main-menu');











?>
<!-- Sidebar scroll-->
<div class="scroll-sidebar">
    <!-- Sidebar navigation-->
    <nav class="sidebar-nav">
        <ul id="sidebarnav">
            <?php echo $user_to_display ? $user_to_display : '' ; ?>
            <?php echo $menu_to_display; ?>
            
        </ul>
    </nav>
    <!-- End Sidebar navigation -->
</div>
<!-- End Sidebar scroll-->