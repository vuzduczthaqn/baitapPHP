<?php

namespace WPAdminify\Inc\Modules\DashboardWidget;

use  WPAdminify\Inc\Utils ;
use  WPAdminify\Inc\Classes\Multisite_Helper ;
use  WPAdminify\Inc\Modules\DashboardWidget\DashboardWidgetModel ;
// no direct access allowed
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * WPAdminify
 *
 * @package Module: Dashboard Widget
 *
 * @author Jewel Theme <support@jeweltheme.com>
 */
class DashboardWidget extends DashboardWidgetModel
{
    public  $url ;
    public  $roles ;
    public  $options ;
    public  $current_role ;
    public function __construct()
    {
        $this->options = ( new DashboardWidget_Setttings() )->get();
        $this->url = WP_ADMINIFY_URL . 'Inc/Modules/DashboardWidget';
        
        if ( is_admin() ) {
            add_action( 'admin_enqueue_scripts', [ $this, 'jltwp_adminify_enqueue_scripts' ] );
            add_action( 'wp_dashboard_setup', [ $this, 'create_dashboard_widgets' ], 999 );
            add_action( 'wp_network_dashboard_setup', [ $this, 'create_dashboard_widgets' ], 999 );
            add_action( 'wp_loaded', [ $this, 'override_elementor_shortcodes' ] );
            // Welcome Panel Initialize
            add_action( 'admin_init', [ $this, 'jltwp_adminify_welcome_init' ] );
        }
    
    }
    
    /**
     * Override elementor-template shortcode
     */
    public function override_elementor_shortcodes()
    {
        if ( is_admin() && shortcode_exists( 'elementor-template' ) ) {
            add_shortcode( 'elementor-template', [ $this, 'override_elementor_template' ] );
        }
    }
    
    public function override_elementor_template( $atts )
    {
        extract( shortcode_atts( [
            'id' => '',
        ], $atts ) );
        $elementor = \Elementor\Plugin::$instance;
        $output = '';
        $output .= $elementor->frontend->register_styles();
        $output .= $elementor->frontend->enqueue_styles();
        $output .= $elementor->frontend->get_builder_content( $id, true );
        $output .= $elementor->frontend->register_scripts();
        $output .= $elementor->frontend->enqueue_scripts();
        return $output;
    }
    
    /**
     * Welcome Panel Initialize
     */
    public function jltwp_adminify_welcome_init()
    {
        if ( empty($this->options['dashboard_widget_types']) ) {
            return;
        }
        $option = ( !empty($this->options['dashboard_widget_types']['welcome_dash_widget']) ? $this->options['dashboard_widget_types']['welcome_dash_widget'] : '' );
        if ( !empty($option['enable_custom_welcome_dash_widget']) ) {
            
            if ( !empty($option['widget_template_type']) ) {
                // Restricted for User Roles
                $restricted_for_dash_widget = ( !empty($option['user_roles']) ? $option['user_roles'] : '' );
                if ( !Utils::restricted_for( $restricted_for_dash_widget ) ) {
                    return;
                }
                $this->render_welcome_panel_output();
            }
        
        }
    }
    
    /**
     * Render Welcome Panel Content
     *
     * @return void
     */
    public function render_welcome_panel_output()
    {
        remove_action( 'welcome_panel', 'wp_welcome_panel' );
        add_action( 'welcome_panel', [ $this, 'render_welcome_panel' ] );
        // custom fallback for the users who don't have
        // enough capabilities to display welcome panel.
        if ( !current_user_can( 'edit_theme_options' ) ) {
            add_action( 'admin_notices', [ $this, 'render_welcome_panel' ] );
        }
    }
    
    /**
     * Render Welcome Panel
     *
     * @return void
     */
    public function render_welcome_panel()
    {
        $latest_wordpress_version = get_bloginfo( 'version' );
        ?>
		<div class="welcome-panel-content adminify-panel-content">
			<?php 
        
        if ( $latest_wordpress_version >= '6.2' ) {
            ?>
				<div class="welcome-panel-header">
					<div class="welcome-panel-header-image">
						<svg preserveAspectRatio="xMidYMin slice" viewBox="0 0 1232 240" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
							<g clip-path="url(#a)">
								<path class="curve" d="M1430.91 497.569c63.48-63.482 112.65-137.548 146.13-220.1 32.34-79.71 48.73-163.936 48.73-250.299 0-86.362-16.39-170.588-48.73-250.298-33.48-82.573-82.65-156.618-146.13-220.1-63.48-63.482-137.55-112.651-220.1-146.135-79.71-32.336-163.94-48.725-250.301-48.725-86.363 0-170.589 16.389-250.299 48.725-82.573 33.484-156.618 82.653-220.1 146.135-63.481 63.482-112.65 137.547-146.135 220.1C311.64-143.418 295.25-59.192 295.25 27.19c0 86.383 16.39 170.589 48.725 250.299 33.485 82.573 82.654 156.618 146.135 220.1a683.438 683.438 0 0 0 14.475 14.031l85.576-85.577a560.502 560.502 0 0 1-14.535-13.99C472.814 309.24 416.206 172.56 416.206 27.17c0-145.389 56.608-282.069 159.42-384.882 102.813-102.813 239.494-159.42 384.883-159.42 145.391 0 282.071 56.607 384.881 159.42 102.81 102.813 159.42 239.493 159.42 384.882 0 145.39-56.61 282.07-159.42 384.883L861.587 895.857H747.545l540.815-540.815c87.57-87.572 135.81-204.013 135.81-327.851 0-123.84-48.22-240.28-135.81-327.852-87.57-87.572-204.01-135.814-327.851-135.814-123.839 0-240.28 48.222-327.852 135.814C545.085-213.069 496.844-96.648 496.844 27.19c0 123.839 48.221 240.28 135.813 327.852 4.758 4.758 9.636 9.374 14.575 13.93l85.637-85.637c-5.019-4.475-9.938-9.072-14.696-13.829-133.616-133.616-133.616-351.035 0-484.671 133.616-133.616 351.037-133.616 484.667 0 64.74 64.731 100.38 150.792 100.38 242.335 0 91.544-35.64 177.604-100.38 242.336L576.493 895.857H462.452l683.378-683.362c102.19-102.188 102.19-268.442 0-370.629-49.49-49.492-115.31-76.767-185.301-76.767-69.993 0-135.814 27.255-185.305 76.767-49.491 49.491-76.767 115.311-76.767 185.304 0 69.994 27.256 135.814 76.767 185.305a262.783 262.783 0 0 0 14.797 13.708l86.02-86.02a143.305 143.305 0 0 1-15.281-13.224c-26.65-26.651-41.326-62.09-41.326-99.789 0-37.698 14.676-73.138 41.326-99.789 26.651-26.65 62.091-41.326 99.789-41.326s73.141 14.676 99.791 41.326c55.01 55.015 55.01 144.543 0 199.578L295.29 891.986v124.804h1330.52V895.837h-593.13l398.27-398.268h-.04ZM-1234.11-301.729c-82.74 82.734-146.8 179.217-190.43 286.787-42.11 103.881-63.46 213.608-63.46 326.178s21.35 222.297 63.48 326.158c43.63 107.571 107.69 204.053 190.43 286.787 82.73 82.739 179.21 146.799 286.784 190.429 103.861 42.11 213.608 63.48 326.158 63.48 112.55 0 222.297-21.35 326.158-63.48 107.57-43.63 204.053-107.69 286.787-190.429 82.734-82.734 146.8-179.216 190.425-286.787 42.113-103.861 63.482-213.608 63.482-326.158 0-112.549-21.349-222.297-63.482-326.158C138.597-122.492 74.531-218.975-8.203-301.709c-53.382-53.382-112.711-99.063-177.08-136.519l-88.963 88.963c66.284 34.815 126.883 79.448 180.527 133.092C47.155-75.299 124.748 112.021 124.748 311.256c0 199.235-77.593 386.556-218.467 527.43-140.873 140.873-328.194 218.464-527.429 218.464-199.235 0-386.552-77.591-527.432-218.464-140.87-140.874-218.46-328.195-218.46-527.43 0-199.235 77.59-386.555 218.46-527.429L-484.77-880h-171.052l-578.288 578.271Z" fill="#213FD4"></path>
								<path class="curve" d="M85.415-880H-85.637L-949.02-16.635c-87.569 87.572-135.809 204.012-135.809 327.851s48.22 240.28 135.809 327.852c87.572 87.572 204.012 135.813 327.851 135.813s240.3-48.241 327.852-135.813c87.572-87.572 135.813-204.013 135.813-327.852 0-123.839-48.221-240.28-135.813-327.872-55.701-55.68-123.073-95.434-196.574-117.025L-593.209-30.364c81 6.491 156.275 41.145 214.375 99.245 64.731 64.731 100.373 150.792 100.373 242.335 0 91.544-35.642 177.604-100.373 242.336-64.732 64.731-150.792 100.373-242.336 100.373-91.544 0-177.604-35.642-242.335-100.373-64.732-64.732-100.374-150.792-100.374-242.336 0-91.543 35.642-177.604 100.374-242.335L85.415-880Z" fill="#213FD4"></path>
								<path class="dot" d="M961 40c16.569 0 30-13.431 30-30 0-16.569-13.431-30-30-30-16.569 0-30 13.431-30 30 0 16.569 13.431 30 30 30Z" fill="#33F078"></path>
							</g>
							<defs>
								<clipPath id="a">
								<path fill="#fff" d="M0 0h1232v240H0z"></path>
								</clipPath>
							</defs>
						</svg>
					</div>
					<?php 
            $this->render_welcome_template();
            ?>
				</div>
			<?php 
        } else {
            ?>

				<?php 
            
            if ( !current_user_can( 'edit_theme_options' ) ) {
                ?>
					<a class="welcome-panel-close" href="<?php 
                echo  esc_url( admin_url( 'welcome=0' ) ) ;
                ?>"><?php 
                esc_html_e( 'Dismiss' );
                ?></a>
				<?php 
            }
            
            ?>

				<?php 
            $this->render_welcome_template();
            ?>
			<?php 
        }
        
        ?>
		</div>

		<?php 
        if ( !current_user_can( 'edit_theme_options' ) ) {
            ?>
			<script type="text/javascript">
				;
				(function($) {
					$(document).ready(function() {
						$('<div id="adminify-welcome-panel" class="adminify-welcome-panel"></div>').insertBefore('#dashboard-widgets-wrap').append($('.adminify-panel-content'));
					});
				})(jQuery);
			</script>
		<?php 
        }
        ?>
		<?php 
    }
    
    public function render_welcome_template()
    {
        $option = ( isset( $this->options['dashboard_widget_types']['welcome_dash_widget'] ) ? $this->options['dashboard_widget_types']['welcome_dash_widget'] : '' );
        
        if ( isset( $option['widget_template_type'] ) && !empty($option['widget_template_type']) ) {
            $from_multisite = false;
            $ms_helper = new Multisite_Helper();
            $switch_blog = ( $from_multisite && $ms_helper->needs_to_switch_blog() ? true : false );
            if ( is_plugin_active( 'elementor/elementor.php' ) ) {
                $elementor = \Elementor\Plugin::$instance;
            }
            echo  '<style>' ;
            $css = '';
            // $css .= '.welcome-panel-content{max-width:95%;}';
            $css = str_replace( [
                "\r\n",
                "\n",
                "\r\t",
                "\t",
                "\r"
            ], '', $css );
            $css = preg_replace( '/\\s+/', ' ', $css );
            echo  Utils::wp_kses_custom( $css ) ;
            echo  '</style>' ;
            
            if ( $switch_blog ) {
                global  $blueprint ;
                switch_to_blog( $blueprint );
            }
            
            switch ( $option['widget_template_type'] ) {
                case 'specific_page':
                    $page_id = $option['custom_page'];
                    
                    if ( $page_id ) {
                        $page = get_page( $page_id );
                        $content = apply_filters( 'the_content', $page->post_content );
                        $content = str_replace( ']]>', ']]&gt;', $content );
                        echo  wp_kses_post( $content ) ;
                    }
                    
                    break;
                case 'elementor_template':
                    
                    if ( is_plugin_active( 'elementor/elementor.php' ) ) {
                        $template_id = $option['elementor_template_id'];
                        
                        if ( $template_id ) {
                            $elementor->frontend->register_styles();
                            $elementor->frontend->enqueue_styles();
                            echo  Utils::wp_kses_custom( $elementor->frontend->get_builder_content( $template_id, true ) ) ;
                            $elementor->frontend->register_scripts();
                            $elementor->frontend->enqueue_scripts();
                        }
                    
                    }
                    
                    break;
                case 'elementor_section':
                    
                    if ( is_plugin_active( 'elementor/elementor.php' ) ) {
                        $template_id = $option['elementor_section_id'];
                        
                        if ( $template_id ) {
                            $elementor->frontend->register_styles();
                            $elementor->frontend->enqueue_styles();
                            echo  Utils::wp_kses_custom( $elementor->frontend->get_builder_content( $template_id, true ) ) ;
                            $elementor->frontend->register_scripts();
                            $elementor->frontend->enqueue_scripts();
                        }
                    
                    }
                    
                    break;
                case 'elementor_widget':
                    
                    if ( is_plugin_active( 'elementor/elementor.php' ) ) {
                        $template_id = $option['elementor_widget_id'];
                        
                        if ( $template_id ) {
                            $elementor->frontend->register_styles();
                            $elementor->frontend->enqueue_styles();
                            echo  Utils::wp_kses_custom( $elementor->frontend->get_builder_content( $template_id, true ) ) ;
                            $elementor->frontend->register_scripts();
                            $elementor->frontend->enqueue_scripts();
                        }
                    
                    }
                    
                    break;
                case 'oxygen_template':
                    
                    if ( is_plugin_active( 'oxygen/functions.php' ) ) {
                        $template_id = $option['oxygen_template_id'];
                        if ( $template_id ) {
                            echo  do_shortcode( get_post_meta( $template_id, 'ct_builder_shortcodes', true ) ) ;
                        }
                    }
                    
                    break;
            }
            if ( $switch_blog ) {
                restore_current_blog();
            }
        }
    
    }
    
    // Add Custom Dashboard Widgets
    public function create_dashboard_widgets()
    {
        $options = $this->options;
        $options = ( !empty($this->options['dashboard_widget_types']['dashboard_widgets']) ? $this->options['dashboard_widget_types']['dashboard_widgets'] : '' );
        if ( empty($options) ) {
            return;
        }
        $before_content = '';
        $after_content = '';
        $dash_widget_data = [];
        foreach ( $options as $value ) {
            
            if ( is_array( $value ) && !empty($value) ) {
                // Restricted for User Roles
                $restricted_for_dash_widget = ( !empty($value['user_roles']) ? $value['user_roles'] : '' );
                if ( !Utils::restricted_for( $restricted_for_dash_widget ) ) {
                    return;
                }
                $dash_widget_title = ( isset( $value['title'] ) ? $value['title'] : '' );
                $dash_widget_position = ( isset( $value['widget_pos'] ) ? $value['widget_pos'] : 'normal' );
                add_meta_box(
                    'adminify_widget_' . Utils::jltwp_adminify_class_cleanup( $dash_widget_title ),
                    $dash_widget_title,
                    [ $this, 'render_dashboard_widget' ],
                    'dashboard',
                    $dash_widget_position,
                    'high',
                    $value
                );
            }
        
        }
    }
    
    // Render Dashboard Widget
    public function render_dashboard_widget( $content = '', $value = '' )
    {
        switch ( $value['args']['widget_type'] ) {
            case 'editor':
                echo  wp_kses_post( $value['args']['dashw_type_editor'] ) ;
                break;
            case 'icon':
                break;
            case 'video':
                break;
            case 'shortcode':
                break;
            case 'rss_feed':
                break;
            case 'script':
                break;
        }
    }
    
    /**
     * Scripst / Styles
     */
    public function jltwp_adminify_enqueue_scripts()
    {
        global  $pagenow ;
        // Load Scripts/Styles only WP Adminify Dashboard Widget
        if ( 'admin.php' === $pagenow && 'adminify-dashboard-widgets' === $_GET['page'] ) {
            $this->dashboard_widgets_admin_script();
        }
    }
    
    // WP Adminify Dashboard Widgets Style
    public function dashboard_widgets_admin_script()
    {
        echo  '<style>.wp-adminify-dashboard-widgets .adminify-container{ max-width:60%; margin:0 auto;} .wp-adminify-dashboard-widgets .adminify-header-inner{padding:0;}.wp-adminify-dashboard-widgets .adminify-field-subheading{font-size:20px; padding-left:0;}.adminify-dashboard-widgets .adminify-nav,.adminify-dashboard-widgets .adminify-search,.adminify-dashboard-widgets .adminify-footer,.adminify-dashboard-widgets .adminify-reset-all,.adminify-dashboard-widgets .adminify-expand-all,.adminify-dashboard-widgets .adminify-header-left,.adminify-dashboard-widgets .adminify-reset-section,.adminify-dashboard-widgets .adminify-nav-background{display: none !important;}.adminify-dashboard-widgets .adminify-nav-normal + .adminify-content{margin-left: 0;}
        /*
        .wp-adminify #wpbody-content .adminify-section[data-section-id] .adminify-data-wrapper .adminify-cloneable-item .adminify-cloneable-title{ border:none !important; }
*/
        /* If needed for white top-bar */
        .adminify-dashboard-widgets .adminify-header-inner {
            background-color: #fafafa !important;
            border-bottom: 1px solid #f5f5f5;
        }
        </style>' ;
    }

}