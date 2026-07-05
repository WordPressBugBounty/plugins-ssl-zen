<?php

/**
 * Helps install a free SSL certificate from LetsEncrypt, fixes mixed content, insecure content by redirecting to https, and forces SSL on all pages.
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * Plugin Name:       Free SSL Certificate & HTTPS Redirector for WordPress - SSL Zen
 * Plugin URI:        https://sslzen.com
 * Description:       Helps install a free SSL certificate from LetsEncrypt, fixes mixed content, insecure content by redirecting to https, and forces SSL on all pages.
 * Version:           1.9.6
 * Author:            SSL
 * Author URI:        http://sslzen.com
 * License:           GNU General Public License v3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       ssl-zen
 * Domain Path:       ssl_zen/languages
 *
 * @author   SSL
 * @category Plugin
 * @license  http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */
if ( !class_exists( 'SSLZenScripts' ) ) {
    /**
     * Class to manage the scripts and styles for SSL Zen
     */
    class SSLZenScripts {
        /**
         * Add hooks and filters to enqueue scripts and styles needed for SSL Zen
         *
         * @since  1.0
         * @static
         */
        public static function init() {
            $page = ( isset( $_REQUEST['page'] ) ? sanitize_text_field( $_REQUEST['page'] ) : '' );
            if ( $page == 'ssl_zen' ) {
                add_action( 'admin_enqueue_scripts', __CLASS__ . '::admin_enqueue_scripts' );
                add_action( 'admin_enqueue_scripts', __CLASS__ . '::admin_enqueue_scripts_no_conflict', 11 );
            }
            add_action( 'admin_enqueue_scripts', __CLASS__ . '::admin_enqueue_scripts_for_notice' );
        }

        /**
         * Hook to add scripts and styles for SSL Zen admin
         *
         * @since  1.0
         * @static
         */
        public static function admin_enqueue_scripts() {
            // Enqueue Styles
            wp_enqueue_style(
                'ssl-zen-font-css',
                SSL_ZEN_URL . 'css/fonts.css',
                [],
                SSL_ZEN_PLUGIN_VERSION
            );
            wp_enqueue_style(
                'ssl-zen-bootstrap-css',
                SSL_ZEN_URL . 'css/bootstrap.min.css',
                [],
                SSL_ZEN_PLUGIN_VERSION
            );
            wp_enqueue_style(
                'ssl-zen-build-css',
                SSL_ZEN_URL . 'css/build.css',
                [],
                SSL_ZEN_PLUGIN_VERSION
            );
            wp_enqueue_style(
                'ssl-zen-bootstrap-toggle-css',
                SSL_ZEN_URL . 'css/bootstrap-toggle.min.css',
                [],
                SSL_ZEN_PLUGIN_VERSION
            );
            wp_enqueue_style(
                'ssl-zen-style-css',
                SSL_ZEN_URL . 'css/style.css',
                [],
                SSL_ZEN_PLUGIN_VERSION
            );
            // v4.7.10: modern UI layer — loaded AFTER style.css so it refines
            // the admin look (SSL Zen pink brand, softer cards) without touching
            // the original stylesheet or any wizard markup.
            wp_enqueue_style(
                'ssl-zen-modern-css',
                SSL_ZEN_URL . 'css/ssl-zen-modern.css',
                ['ssl-zen-style-css'],
                SSL_ZEN_PLUGIN_VERSION
            );
            // Enqueue Scripts
            wp_enqueue_script( 'jQuery' );
            wp_enqueue_script(
                'ssl-zen-jquery-validate-js',
                SSL_ZEN_URL . 'js/jquery.validate.js',
                ['jquery'],
                SSL_ZEN_PLUGIN_VERSION
            );
            wp_enqueue_script(
                'ssl-zen-bootstrap-js',
                SSL_ZEN_URL . 'js/bootstrap.bundle.min.js',
                ['jquery'],
                SSL_ZEN_PLUGIN_VERSION
            );
            wp_enqueue_script(
                'ssl-zen-bootstrap-toggle-js',
                SSL_ZEN_URL . 'js/bootstrap-toggle.min.js',
                ['jquery'],
                SSL_ZEN_PLUGIN_VERSION
            );
            wp_enqueue_script(
                'ssl-zen-main-js',
                SSL_ZEN_URL . 'js/main.js',
                ['jquery', 'clipboard'],
                SSL_ZEN_PLUGIN_VERSION
            );
            wp_localize_script( 'ssl-zen-main-js', 'params', [
                'l10n' => [
                    'copied_success' => __( 'Copied successfully.', 'ssl-zen' ),
                    'copied_failure' => __( 'Failed to copy.', 'ssl-zen' ),
                ],
            ] );
            // ---------------------------------------------------------------
            // SSL Zen self-hosted support widget (replaces the Help Scout Beacon).
            // Branded, answers-first help widget served from our own helpdesk.
            // It auto-attaches diagnostic context so the AI can answer without
            // the user having to hunt down their WordPress / plugin details.
            //
            // The helpdesk origin is filterable so it can be re-pointed without
            // editing the plugin: add_filter( 'ssl_zen_support_widget_base', ... ).
            // ---------------------------------------------------------------
            $support_base = apply_filters( 'ssl_zen_support_widget_base', 'https://support.sslzen.com' );
            $support_base = untrailingslashit( esc_url_raw( $support_base ) );
            if ( ! empty( $support_base ) ) {
                global $wp_version;
                $home = wp_parse_url( home_url() );
                $ctx = [
                    'domain'         => isset( $home['host'] ) ? $home['host'] : '',
                    'site_url'       => admin_url( 'admin.php?page=ssl_zen' ),
                    'wp_version'     => $wp_version,
                    'plugin_version' => SSL_ZEN_PLUGIN_VERSION,
                    'plan'           => ( sz_fs()->is_free_plan() ? 'Free' : 'Premium' ),
                    'php_version'    => PHP_VERSION,
                    'step'           => ( isset( $_REQUEST['tab'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['tab'] ) ) : '' ),
                ];
                echo '<script type="text/javascript">window.SSLZEN_SUPPORT = '
                    . wp_json_encode( [ 'base' => $support_base, 'context' => $ctx ] )
                    . ';</script>' . "\n";
                echo '<script type="text/javascript" src="' . esc_url( $support_base . '/chat-widget.js' )
                    . '" defer></script>' . "\n";

                // -----------------------------------------------------------
                // v4.7.39: anonymous setup-funnel analytics (first-party, no PII).
                // Only active when the user opted in to usage tracking on the
                // Freemius consent screen (is_tracking_allowed). Sends which setup
                // step is reached and which buttons are clicked to our own endpoint
                // so we can see where installs drop off. Uses a random per-install
                // id — no personal data, no third-party trackers.
                // -----------------------------------------------------------
                $sz_track_ok = ( function_exists( 'sz_fs' ) && method_exists( sz_fs(), 'is_tracking_allowed' ) && sz_fs()->is_tracking_allowed() );
                if ( $sz_track_ok ) {
                    $sz_anon = get_option( 'ssl_zen_anon_id' );
                    if ( empty( $sz_anon ) ) {
                        $sz_anon = function_exists( 'wp_generate_uuid4' ) ? wp_generate_uuid4() : md5( uniqid( 'sz', true ) );
                        update_option( 'ssl_zen_anon_id', $sz_anon, false );
                    }
                    $sz_stage = get_option( 'ssl_zen_settings_stage', '' );
                    $sz_step  = ( $sz_stage === 'review' ) ? 'activated' : ( $sz_stage ? $sz_stage : 'unknown' );
                    echo '<script type="text/javascript">window.SSLZEN_TRACK='
                        . wp_json_encode( array(
                            'url'  => $support_base . '/api/track',
                            'anon' => $sz_anon,
                            'v'    => SSL_ZEN_PLUGIN_VERSION,
                            'step' => $sz_step,
                        ) ) . ';</script>' . "\n";
                    echo '<script type="text/javascript">'
                        . '(function(){var C=window.SSLZEN_TRACK;if(!C||!C.url)return;'
                        . 'function b(ev,st){try{var d=JSON.stringify({event:ev,step:st||null,anon_id:C.anon,v:C.v});'
                        . 'if(navigator.sendBeacon){navigator.sendBeacon(C.url,new Blob([d],{type:"text/plain"}));}'
                        . 'else{fetch(C.url,{method:"POST",headers:{"Content-Type":"text/plain"},body:d,keepalive:true}).catch(function(){});}}catch(e){}}'
                        . 'b("step_reached",C.step);'
                        . 'document.addEventListener("click",function(e){var el=e.target.closest&&e.target.closest("button,a.btn,a.review,input[type=submit],.btn,.review,.primary,.button-primary");if(!el||!el.closest(".ssl-zen-content-container"))return;'
                        . 'var l=(el.getAttribute("data-sz-track")||(el.textContent||el.value||"").replace(/\\s+/g," ").trim().slice(0,24)||"button");b("button_click",l);},true);'
                        . '})();'
                        . '</script>' . "\n";
                }
            }
        }

        /**
         * Hook to add scripts and styles for SSL Zen admin
         *
         * @since  1.0
         * @static
         */
        public static function admin_enqueue_scripts_no_conflict() {
            wp_enqueue_script(
                'ssl-zen-jquery-no-conflict',
                SSL_ZEN_URL . 'js/jquery.no-conflict.js',
                ['jquery'],
                SSL_ZEN_PLUGIN_VERSION
            );
            wp_enqueue_script(
                'ssl-zen-donutty-jquery.min',
                SSL_ZEN_URL . 'js/donutty-jquery.min.js',
                ['jquery'],
                SSL_ZEN_PLUGIN_VERSION
            );
        }

        /**
         * Function for print the js required for removing notice
         *
         * @since  1.7
         * @static
         */
        public static function admin_enqueue_scripts_for_notice() {
            wp_enqueue_script(
                'ssl-zen-notice-js',
                SSL_ZEN_URL . 'js/ssl-zen-notice.js',
                ['jquery'],
                SSL_ZEN_PLUGIN_VERSION
            );
            wp_localize_script( 'ssl-zen-notice-js', 'ssl_zen_notice_nonce', [
                'nonce' => wp_create_nonce( 'ssl_zen_notice_nonce_action' ),
            ] );
        }

    }

    /**
     * Initialize SSL Zen scripts.
     */
    SSLZenScripts::init();
}