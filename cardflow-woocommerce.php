<?php
/**
 * Plugin Name: Cardflow for WooCommerce
 * Plugin URI: https://github.com/cardflow/cardflow-for-woocommerce
 * Description: WordPress plugin for accepting Cardflow gift cards in your WooCommerce shop.
 * Domain Path: /languages
 * Version: 1.0.5
 * Author: Cardflow B.V.
 * Author URI: https://cardflow.nl
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Requires PHP: 7.2
 * Requires at least: 5.4
 * Tested up to: 6.4.1
 * WC requires at least: 4.4.0
 * WC tested up to: 8.6.1
 */

declare( strict_types=1 );

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

require plugin_dir_path( __FILE__ ) . '/vendor/autoload.php';

if ( ! class_exists( 'Cardflow_WooCommerce' ) ) {
    final class Cardflow_WooCommerce {

        public function __construct() {
            add_action( 'plugins_loaded', [ $this, 'init' ] );
        }

        public function init() {
            load_plugin_textdomain( 'cardflow-woocommerce', false, basename( dirname( __FILE__ ) ) . '/languages/' );

            // Check if WooCommerce is installed
            if ( ! class_exists( 'WooCommerce' ) ) {
                add_action( 'admin_notices', [ $this, 'notice_missing_wc_install' ] );
                return;
            }

            // Register the Cardflow plugin
            if ( class_exists( 'WC_Integration' ) ) {
                // Register the integration.
                add_filter( 'woocommerce_integrations', [ $this, 'add_integration' ] );
            }

            add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), [ $this, 'plugin_action_links' ] );

						add_action( 'before_woocommerce_init', function() {
							if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
								\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
							}
						});
        }

        public function add_integration( array $integrations ): array {
            $integrations[] = \Cardflow\WooCommerce\WC_Integration_Cardflow::class;
            return $integrations;
        }

        public function notice_missing_wc_install() {
            echo '<div class="error"><p><strong>' . sprintf(
                    esc_html__(
                        'Cardflow for WooCommerce requires WooCommerce to be installed and active. You can download %s here.',
                        'cardflow-woocommerce'
                    ),
                    '<a href="https://woocommerce.com/" target="_blank">WooCommerce</a>'
                ) . '</strong></p></div>';
        }

        public function plugin_action_links( array $links ): array {
            $links[] = sprintf(
                '<a href="admin.php?page=wc-settings&tab=integration&section=cardflow-woocommerce">%s</a>',
                esc_html__( 'Settings', 'cardflow-woocommerce' )
            );

            return $links;
        }
    }

    $cardflowWooCommercePlugin = new Cardflow_WooCommerce();
}
