<?php
/**
 * Zakra functions and definitions
 *
 * @link    https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package zakra
 */

if (!function_exists('zakra_setup')):
    // Sets up theme defaults and registers support for various WordPress features.
    function zakra_setup()
    {
        // Make theme available for translation.
        load_theme_textdomain('zakra', get_template_directory() . '/languages');

        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        // Let WordPress manage the document title.
        add_theme_support('title-tag');

        // Enable support for Post Thumbnails on posts and pages.
        add_theme_support('post-thumbnails');

        // Register menu.
        register_nav_menus(
            array(
                'menu-primary' => esc_html__('Primary', 'zakra'),
            )
        );

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support(
            'html5',
            array(
                'search-form',
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
            )
        );

        // Add theme support for selective refresh for widgets.
        add_theme_support('customize-selective-refresh-widgets');

        /**
         * Add support for core custom logo.
         *
         * @link https://codex.wordpress.org/Theme_Logo
         */
        add_theme_support(
            'custom-logo',
            array(
                'width' => 170,
                'height' => 60,
                'flex-width' => true,
                'flex-height' => true,
            )
        );

        // Custom background support.
        add_theme_support('custom-background');

        // Gutenberg Wide/fullwidth support.
        add_theme_support('align-wide');

        // Add support for Block Styles.
        add_theme_support('wp-block-styles');

        // AMP support.
        if (defined('AMP__VERSION') && (!version_compare(AMP__VERSION, '1.0.0', '<'))) {
            add_theme_support(
                'amp',
                apply_filters(
                    'zakra_amp_support_filter',
                    array(
                        'paired' => true,
                    )
                )
            );
        }
    }
endif;
add_action('after_setup_theme', 'zakra_setup');

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function zakra_widgets_init()
{
    $sidebars = apply_filters(
        'zakra_sidebars_args',
        array(
            'sidebar-right' => esc_html__('Sidebar Right', 'zakra'),
            'sidebar-left' => esc_html__('Sidebar Left', 'zakra'),
            'header-top-left-sidebar' => esc_html__('Header Top Bar Left Sidebar', 'zakra'),
            'header-top-right-sidebar' => esc_html__('Header Top Bar Right Sidebar', 'zakra'),
            'footer-sidebar-1' => esc_html__('Footer One', 'zakra'),
            'footer-sidebar-2' => esc_html__('Footer Two', 'zakra'),
            'footer-sidebar-3' => esc_html__('Footer Three', 'zakra'),
            'footer-sidebar-4' => esc_html__('Footer Four', 'zakra'),
            'footer-bar-left-sidebar' => esc_html__('Footer Bottom Bar Left Sidebar', 'zakra'),
            'footer-bar-right-sidebar' => esc_html__('Footer Bottom Bar Right Sidebar', 'zakra'),
        )
    );

    if (zakra_is_woocommerce_active()) {
        $sidebars['wc-left-sidebar'] = esc_html__('WooCommerce Left Sidebar', 'zakra');
        $sidebars['wc-right-sidebar'] = esc_html__('WooCommerce Right Sidebar', 'zakra');
    }

    foreach ($sidebars as $id => $name) {
        register_sidebar(
            apply_filters(
                'zakra_sidebars_widget_args',
                array(
                    'id' => $id,
                    'name' => $name,
                    'description' => esc_html__('Add widgets here.', 'zakra'),
                    'before_widget' => '<section id="%1$s" class="widget %2$s">',
                    'after_widget' => '</section>',
                    'before_title' => '<h2 class="widget-title">',
                    'after_title' => '</h2>',
                )
            )
        );
    }
}

add_action('widgets_init', 'zakra_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function zakra_scripts()
{
    $suffix = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';

    /**
     * Styles.
     */
    // Font Awesome 4.
    wp_register_style('font-awesome', get_template_directory_uri() . '/assets/lib/font-awesome/css/font-awesome' . $suffix . '.css', false, '4.7.0');
    wp_enqueue_style('font-awesome');

    // Theme style.
    wp_register_style('zakra-style', get_stylesheet_uri(), array(), ZAKRA_THEME_VERSION);
    wp_enqueue_style('zakra-style');

    // Support RTL.
    wp_style_add_data('zakra-style', 'rtl', 'replace');

    /**
     * Inline CSS for this theme.
     */
    add_filter('zakra_dynamic_theme_css', array('Zakra_Dynamic_CSS', 'render_output'));

    // Enqueue required Google font for the theme.
    Zakra_Generate_Fonts::render_fonts();

    // Generate dynamic CSS to add inline styles for the theme.
    $theme_dynamic_css = apply_filters('zakra_dynamic_theme_css', '');

    if (zakra_is_zakra_pro_active()) {
        wp_add_inline_style('zakra-pro', $theme_dynamic_css);
    } else {
        wp_add_inline_style('zakra-style', $theme_dynamic_css);
    }

    // Do not load scripts if AMP.
    if (zakra_is_amp()) {
        return;
    }

    /**
     * Scripts.
     */
    wp_enqueue_script('zakra-navigation', get_template_directory_uri() . '/assets/js/navigation' . $suffix . '.js', array(), '20151215', true);
    wp_enqueue_script('zakra-skip-link-focus-fix', get_template_directory_uri() . '/assets/js/skip-link-focus-fix' . $suffix . '.js', array(), '20151215', true);

    // Theme JavaScript.
    wp_enqueue_script('zakra-custom', get_template_directory_uri() . '/assets/js/zakra-custom' . $suffix . '.js', array(), false, true);

    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

}
add_action('wp_enqueue_scripts', 'zakra_scripts', 11);

/**
 * Enqueue block editor styles.
 *
 * @since Zakra 1.4.3
 */
function zakra_block_editor_styles()
{
    wp_enqueue_style('zakra-block-editor-styles', get_template_directory_uri() . '/style-editor-block.css');
}
add_action('enqueue_block_editor_assets', 'zakra_block_editor_styles', 1, 1);

/**
 * Define constants.
 */
define('ZAKRA_PARENT_DIR', get_template_directory());
define('ZAKRA_PARENT_URI', get_template_directory_uri());
define('ZAKRA_PARENT_INC_DIR', ZAKRA_PARENT_DIR . '/inc');
define('ZAKRA_PARENT_INC_URI', ZAKRA_PARENT_URI . '/inc');
define('ZAKRA_PARENT_INC_ICON_URI', ZAKRA_PARENT_URI . '/assets/img/icons');
define('ZAKRA_PARENT_CUSTOMIZER_DIR', ZAKRA_PARENT_INC_DIR . '/customizer');

// Theme version.
$zakra_theme = wp_get_theme('zakra');
define('ZAKRA_THEME_VERSION', $zakra_theme->get('Version'));

// AMP support files.
if (defined('AMP__VERSION') && (!version_compare(AMP__VERSION, '1.0.0', '<'))) {
    require_once ZAKRA_PARENT_INC_DIR . '/compatibility/amp/class-zakra-amp.php';
}

/**
 * Include files.
 */
require ZAKRA_PARENT_INC_DIR . '/helpers.php';
require ZAKRA_PARENT_INC_DIR . '/custom-header.php';
require ZAKRA_PARENT_INC_DIR . '/class-zakra-dynamic-filter.php';
require ZAKRA_PARENT_INC_DIR . '/template-tags.php';
require ZAKRA_PARENT_INC_DIR . '/template-functions.php';
require ZAKRA_PARENT_INC_DIR . '/customizer/class-zakra-customizer.php';
require ZAKRA_PARENT_INC_DIR . '/class-zakra-css-classes.php';
require ZAKRA_PARENT_INC_DIR . '/class-zakra-dynamic-css.php';
require ZAKRA_PARENT_INC_DIR . '/class-zakra-migration.php';

// Load Jetpack compatibility file.
if (defined('JETPACK__VERSION')) {
    require ZAKRA_PARENT_INC_DIR . '/class-zakra-jetpack.php';
}

// WooCommerce hooks and functions.
if (class_exists('WooCommerce')) {
    require ZAKRA_PARENT_INC_DIR . '/compatibility/woocommerce/class-zakra-woocommerce.php';
}

// Load hooks.
require ZAKRA_PARENT_INC_DIR . '/hooks/hooks.php';
require ZAKRA_PARENT_INC_DIR . '/hooks/header.php';
require ZAKRA_PARENT_INC_DIR . '/hooks/footer.php';
require ZAKRA_PARENT_INC_DIR . '/hooks/content.php';
require ZAKRA_PARENT_INC_DIR . '/hooks/customize.php';

// Breadcrumbs class.
require_once ZAKRA_PARENT_INC_DIR . '/class-breadcrumb-trail.php';

// Elementor Pro compatibility.
require_once ZAKRA_PARENT_INC_DIR . '/compatibility/elementor/class-zakra-elementor-pro.php';

// Admin screen.
if (is_admin()) {
    // Meta boxes.
    require ZAKRA_PARENT_INC_DIR . '/meta-boxes/class-zakra-meta-box-page-settings.php';
    require ZAKRA_PARENT_INC_DIR . '/meta-boxes/class-zakra-meta-box.php';

    // Theme options page.
    require ZAKRA_PARENT_INC_DIR . '/admin/class-zakra-admin.php';
    require ZAKRA_PARENT_INC_DIR . '/admin/class-zakra-notice.php';
    require ZAKRA_PARENT_INC_DIR . '/admin/class-zakra-welcome-notice.php';
    require ZAKRA_PARENT_INC_DIR . '/admin/class-zakra-upgrade-notice.php';
    require ZAKRA_PARENT_INC_DIR . '/admin/class-zakra-dashboard.php';
    require ZAKRA_PARENT_INC_DIR . '/admin/class-zakra-theme-review-notice.php';
    require ZAKRA_PARENT_INC_DIR . '/admin/class-zakra-demo-import-migration-notice.php';
    require ZAKRA_PARENT_INC_DIR . '/admin/class-zakra-pro-minimum-version-notice.php';
}

// Set default content width.
if (!isset($content_width)) {
    $content_width = 812;
}

// Calculate $content_width value according to layout options from Customizer and meta boxes.
function zakra_content_width_rdr()
{
    global $content_width;

    // Get layout type.
    $layout_type = zakra_get_layout_type();
    $layouts_sidebar = array('tg-site-layout--left', 'tg-site-layout--right');

    /**
     * Calculate content width.
     */
    // Get required values from Customizer.
    $container_width_arr = get_theme_mod('zakra_general_container_width', 1160);

    $content_width_arr = get_theme_mod('zakra_general_content_width', 70);

    // Calculate Padding to reduce.
    $container_style = get_theme_mod('zakra_general_container_style', 'tg-container--wide');

    $content_padding = ('tg-container--separate' === $container_style) ? 120 : 60;

    if (in_array($layout_type, $layouts_sidebar, true)) {
        $content_width = (((int) $container_width_arr * (int) $content_width_arr) / 100) - $content_padding;
    } else {
        $content_width = (int) $container_width_arr - $content_padding;
    }

}
add_action('template_redirect', 'zakra_content_width_rdr');

/**
 * Get all registered menus
 * @return array List of menus with slug and description
 */
function wp_api_v2_menus_get_all_menus()
{
    $menus = get_terms('nav_menu', array('hide_empty' => true));

    foreach ($menus as $key => $menu) {
        // check if there is acf installed
        if (class_exists('acf')) {
            $fields = get_fields($menu);
            if (!empty($fields)) {
                $menus[$key]->acf = new stdClass();

                foreach ($fields as $field_key => $item) {
                    // add all acf custom fields
                    $menus[$key]->acf->$field_key = $item;
                }
            }
        }
    }

    return apply_filters('wp_api_v2_menus__menus', $menus);
}

/**
 * Get all locations
 * @return array List of locations
 **/

function wp_api_v2_menu_get_all_locations()
{
    $nav_menu_locations = get_nav_menu_locations();
    $locations = new stdClass;
    foreach ($nav_menu_locations as $location_slug => $menu_id) {
        if (get_term($location_slug) !== null) {
            $locations->{$location_slug} = get_term($location_slug);
        } else {
            $locations->{$location_slug} = new stdClass;
        }
        $locations->{$location_slug}->slug = $location_slug;
        $locations->{$location_slug}->menu = get_term($menu_id);
    }

    return apply_filters('wp_api_v2_menus__locations', $locations);
}

/**
 * Get menu's data from his id
 *
 * @param array $data WP REST API data variable
 *
 * @return object Menu's data with his items
 */
function wp_api_v2_locations_get_menu_data($data)
{
    // Create default empty object
    $menu = new stdClass;

    // this could be replaced with `if (has_nav_menu($data['id']))`
    if (($locations = get_nav_menu_locations()) && isset($locations[$data['id']])) {
        // Replace default empty object with the location object
        $menu = get_term($locations[$data['id']]);

        if (is_wp_error($menu) || null === $menu) {
            return new WP_Error('not_found', 'No location has been found with this id or slug: `' . $data['id'] . '`. Please ensure you passed an existing location ID or location slug.', array('status' => 404));
        }

        $menu->items = wp_api_v2_menus_get_menu_items($locations[$data['id']]);
    } else {
        return new WP_Error('not_found', 'No location has been found with this id or slug: `' . $data['id'] . '`. Please ensure you passed an existing location ID or location slug.', array('status' => 404));
    }

    // check if there is acf installed
    if (class_exists('acf')) {
        $fields = get_fields($menu);
        if (!empty($fields)) {
            $menu->acf = new stdClass();

            foreach ($fields as $field_key => $item) {
                // add all acf custom fields
                $menu->acf->$field_key = $item;
            }
        }
    }

    return apply_filters('wp_api_v2_menus__menu', $menu);
}

/**
 * Check if a menu item is child of one of the menu's element passed as reference
 *
 * @param $parents Menu's items
 * @param $child Menu's item to check
 *
 * @return bool True if the parent is found, false otherwise
 */
function wp_api_v2_menus_dna_test(&$parents, $child)
{
    foreach ($parents as $key => $item) {
        if ($child->menu_item_parent == $item->ID) {
            if (!$item->child_items) {
                $item->child_items = [];
            }
            array_push($item->child_items, $child);
            return true;
        }

        if ($item->child_items) {
            if (wp_api_v2_menus_dna_test($item->child_items, $child)) {
                return true;
            }
        }
    }

    return false;
}

/**
 * Search object in an array by ID
 */
function wp_api_v2_find_object_by_id($array, $id)
{
    foreach ($array as $element) {
        if ($id == $element->ID) {
            return $element;
        }
    }

    return false;
}

/**
 * Retrieve items for a specific menu
 *
 * @param $id Menu id
 *
 * @return array List of menu items
 */
function wp_api_v2_menus_get_menu_items($id)
{
    $menu_items = wp_get_nav_menu_items($id);

    // fallback: if menu_items is null then return empty array
    if ($menu_items === false) {
        return [];
    }

    $all_menu_items = $menu_items;

    // check if there is acf installed
    if (class_exists('acf')) {
        foreach ($menu_items as $menu_key => $menu_item) {
            $fields = get_fields($menu_item->ID);
            if (!empty($fields)) {
                $menu_items[$menu_key]->acf = new stdClass();

                foreach ($fields as $field_key => $item) {
                    // add all acf custom fields
                    $menu_items[$menu_key]->acf->$field_key = $item;
                }
            }
        }
    }

    // wordpress does not group child menu items with parent menu items
    $child_items = [];
    // pull all child menu items into separate object
    foreach ($menu_items as $key => $item) {

        if ($item->type == 'post_type') {
            // add slug to menu items
            $slug = get_post_field('post_name', $item->object_id);
            $item->slug = $slug;
        } else if ($item->type == 'taxonomy') {
            $cat = get_term($item->object_id);
            $item->slug = $cat->slug;
        } else if ($item->type == 'post_type_archive') {
            $post_type_data = get_post_type_object($item->object);

            if ($post_type_data->has_archive) {
                $item->slug = $post_type_data->rewrite['slug'];
            }
        }

        if (isset($item->thumbnail_id) && $item->thumbnail_id) {
            $item->thumbnail_src = wp_get_attachment_image_url(intval($item->thumbnail_id), 'post-thumbnail');
        }
        if (isset($item->thumbnail_hover_id) && $item->thumbnail_hover_id) {
            $item->thumbnail_hover_src = wp_get_attachment_image_url(intval($item->thumbnail_hover_id), 'post-thumbnail');
        }

        if ($item->menu_item_parent) {
            array_push($child_items, $item);
            unset($menu_items[$key]);
        }

    }

    // push child items into their parent item in the original object
    do {
        foreach ($child_items as $key => $child_item) {
            $parent = wp_api_v2_find_object_by_id($all_menu_items, $child_item->menu_item_parent);

            if (empty($parent)) {
                unset($child_items[$key]);
            } else if (wp_api_v2_menus_dna_test($menu_items, $child_item)) {
                unset($child_items[$key]);
            }
        }
    } while (count($child_items));

    return apply_filters('wp_api_v2_menus__menu_items', array_values($menu_items));
}

/**
 * Get menu's data from his id.
 *    It ensures compatibility for previous versions when this endpoint
 *    was allowing locations id in place of menus id)
 *
 * @param array $data WP REST API data variable
 *
 * @return object Menu's data with his items
 */
function wp_api_v2_menus_get_menu_data($data)
{
    // This ensure retro compatibility with versions `<= 0.5` when this endpoint
    //   was allowing locations id in place of menus id
    if (has_nav_menu($data['id'])) {
        $menu = wp_api_v2_locations_get_menu_data($data);
    } else if (is_nav_menu($data['id'])) {
        if (is_int($data['id'])) {
            $id = $data['id'];
        } else {
            $id = wp_get_nav_menu_object($data['id']);
        }
        $menu = get_term($id);
        $menu->items = wp_api_v2_menus_get_menu_items($id);
    } else {
        return new WP_Error('not_found', 'No menu has been found with this id or slug: `' . $data['id'] . '`. Please ensure you passed an existing menu ID, menu slug, location ID or location slug.', array('status' => 404));
    }

    // check if there is acf installed
    if (class_exists('acf')) {
        $fields = get_fields($menu);
        if (!empty($fields)) {
            $menu->acf = new stdClass();

            foreach ($fields as $field_key => $item) {
                // add all acf custom fields
                $menu->acf->$field_key = $item;
            }
        }
    }

    return apply_filters('wp_api_v2_menus__menu', $menu);
}

add_action('rest_api_init', function () {
    register_rest_route('menus/v1', '/menus', array(
        'methods' => 'GET',
        'callback' => 'wp_api_v2_menus_get_all_menus',
        'permission_callback' => '__return_true'
    )
    );

    register_rest_route('menus/v1', '/menus/(?P<id>[a-zA-Z0-9_-]+)', array(
        'methods' => 'GET',
        'callback' => 'wp_api_v2_menus_get_menu_data',
        'permission_callback' => '__return_true'
    )
    );

    register_rest_route('menus/v1', '/locations/(?P<id>[a-zA-Z0-9_-]+)', array(
        'methods' => 'GET',
        'callback' => 'wp_api_v2_locations_get_menu_data',
        'permission_callback' => '__return_true'
    )
    );

    register_rest_route('menus/v1', '/locations', array(
        'methods' => 'GET',
        'callback' => 'wp_api_v2_menu_get_all_locations',
        'permission_callback' => '__return_true'
    )
    );
});