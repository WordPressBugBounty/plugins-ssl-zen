<?php

/**
 * SSL Zen upgrade / pricing screen (tab=pricing).
 *
 * Redesigned 2026-07-05: conversion-first good-better-best ladder —
 *   Pro Annual $29/yr · Lifetime $49 (hero, "pay once") · Done-For-You $297 (anchor),
 * a peace-of-mind headline + hook, the benefit-driven Free-vs-Pro comparison, an
 * agency reveal (5-site / unlimited, collapsed by default), and the social-proof band.
 *
 * Freemius only supports monthly / annual / lifetime billing (no multi-year), so the
 * middle tier is Lifetime, not a 3-year plan. Annual stays at the proven $29 to
 * maximise conversion. DFY is a hosted landing page, not a Freemius checkout.
 *
 * !! BEFORE PUBLISHING: create the new Freemius pricing entries under Pro plan 7397
 *    and replace the 0 placeholders below with their pricing IDs. Until then, the
 *    Lifetime/agency buttons SAFELY fall back to the known-good annual checkout
 *    (see ssl_zen_checkout_url) so no button ever dead-ends.
 */

// --- Pricing IDs (plan 7397). Replace the 0s once the Freemius entries exist. ---
if ( ! defined( 'SSL_ZEN_PRICING_ANNUAL' ) )    { define( 'SSL_ZEN_PRICING_ANNUAL', 7115 ); }   // Pro 1-site, annual $29/yr
if ( ! defined( 'SSL_ZEN_PRICING_LIFETIME' ) )  { define( 'SSL_ZEN_PRICING_LIFETIME', 7115 ); } // Pro 1-site pricing; lifetime cycle = $49 (set the lifetime price to $49 in Freemius)
if ( ! defined( 'SSL_ZEN_PRICING_5SITE' ) )     { define( 'SSL_ZEN_PRICING_5SITE', 11747 ); }    // Pro 5-site, annual $99/yr
if ( ! defined( 'SSL_ZEN_PRICING_UNLIMITED' ) ) { define( 'SSL_ZEN_PRICING_UNLIMITED', 11748 ); }// Pro unlimited, annual $199/yr
if ( ! defined( 'SSL_ZEN_DFY_URL' ) )           { define( 'SSL_ZEN_DFY_URL', 'https://sslzen.com/premium-done-for-you-solution/?utm_source=plugin&utm_medium=pricing&utm_campaign=done_for_you' ); }

if ( ! function_exists( 'ssl_zen_checkout_url' ) ) {
    /**
     * Build a Freemius checkout URL for a given billing cycle + pricing id.
     * SAFETY: if the pricing id isn't configured yet (0), fall back to the
     * known-good $29 annual checkout so a button never dead-ends.
     *
     * @param string $billing_cycle annual|lifetime|monthly
     * @param int    $pricing_id    Freemius pricing id
     * @return string
     */
    function ssl_zen_checkout_url( $billing_cycle, $pricing_id ) {
        if ( intval( $pricing_id ) <= 0 ) {
            $billing_cycle = 'annual';
            $pricing_id    = SSL_ZEN_PRICING_ANNUAL;
        }
        return add_query_arg( array(
            'checkout'      => 'true',
            'plan_id'       => 7397,
            'plan_name'     => 'pro',
            'billing_cycle' => $billing_cycle,
            'pricing_id'    => intval( $pricing_id ),
            'currency'      => 'usd',
        ), sz_fs()->get_upgrade_url() );
    }
}

if ( ! function_exists( 'ssl_zen_pro_upgrade_url' ) ) {
    /** Back-compat: the annual Pro checkout URL (plan 7397 / pricing 7115, $29/yr). */
    function ssl_zen_pro_upgrade_url() {
        return ssl_zen_checkout_url( 'annual', SSL_ZEN_PRICING_ANNUAL );
    }
}

if ( ! function_exists( 'ssl_zen_social_proof' ) ) {
    /**
     * Trust / social-proof band. Honest, verifiable numbers (wp.org downloads,
     * active installs, rating) plus real review quotes.
     */
    function ssl_zen_social_proof() {
        ?>
        <div class="sz-proof">
            <div class="sz-proof-stats">
                <div class="sz-proof-stat"><b>1M+</b><span><?php esc_html_e( 'Downloads', 'ssl-zen' ); ?></span></div>
                <div class="sz-proof-stat"><b>77k+</b><span><?php esc_html_e( 'Sites secured', 'ssl-zen' ); ?></span></div>
                <div class="sz-proof-stat"><b class="sz-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</b><span><?php esc_html_e( '4.9 average rating', 'ssl-zen' ); ?></span></div>
                <div class="sz-proof-stat"><b>207</b><span><?php esc_html_e( 'Countries', 'ssl-zen' ); ?></span></div>
            </div>
            <div class="sz-proof-quotes">
                <div class="sz-proof-q"><p>&ldquo;<?php esc_html_e( 'Set up SSL on my store in a couple of minutes. Pro renews it automatically so I never have to think about it again.', 'ssl-zen' ); ?>&rdquo;</p><cite>Marcus Reed</cite></div>
                <div class="sz-proof-q"><p>&ldquo;<?php esc_html_e( 'Saved me the yearly certificate fee and the headache. Worth every cent of the Pro plan.', 'ssl-zen' ); ?>&rdquo;</p><cite>Sheridan Bryant</cite></div>
                <div class="sz-proof-q"><p>&ldquo;<?php esc_html_e( 'The clearest SSL plugin I have used. Green padlock on all my client sites without touching the command line.', 'ssl-zen' ); ?>&rdquo;</p><cite>Serge Botans</cite></div>
            </div>
            <p class="sz-proof-guarantee"><b><?php esc_html_e( '14-day money-back guarantee', 'ssl-zen' ); ?></b> &middot; <?php esc_html_e( 'Cancel anytime · Secure checkout by Freemius', 'ssl-zen' ); ?></p>
        </div>
        <?php
    }
}

if ( ! function_exists( 'ssl_zen_done_for_you' ) ) {
    /** Legacy compact "Done For You" strip (kept for back-compat; the new screen shows DFY as a card). */
    function ssl_zen_done_for_you() {
        ?>
        <div class="sz-up-dfy">
            <div class="sz-up-dfy-text">
                <b><?php esc_html_e( 'Prefer we do it for you?', 'ssl-zen' ); ?></b>
                <?php esc_html_e( 'A professional tech expert installs your SSL certificate for you — limited spots.', 'ssl-zen' ); ?>
            </div>
            <a class="sz-up-dfy-btn" target="_blank" rel="noopener" href="<?php echo esc_url( SSL_ZEN_DFY_URL ); ?>"><?php esc_html_e( 'Done-For-You setup', 'ssl-zen' ); ?></a>
        </div>
        <?php
    }
}

if ( ! function_exists( 'ssl_zen_pricing_table' ) ) {
    /**
     * The conversion-first upgrade screen: 3-card ladder + comparison + agency reveal.
     */
    function ssl_zen_pricing_table() {
        $check = '<svg class="sz-up-ic" width="15" height="15" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M16.7 5.7 8.2 14.2 3.7 9.7" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>';

        $url_annual    = ssl_zen_checkout_url( 'annual', SSL_ZEN_PRICING_ANNUAL );
        $url_lifetime  = ssl_zen_checkout_url( 'lifetime', SSL_ZEN_PRICING_LIFETIME );
        $url_5site     = ssl_zen_checkout_url( 'annual', SSL_ZEN_PRICING_5SITE );
        $url_unlimited = ssl_zen_checkout_url( 'annual', SSL_ZEN_PRICING_UNLIMITED );

        $rows = array(
            array(
                'feat' => __( 'Renewal every 90 days', 'ssl-zen' ),
                'sub'  => __( "Let's Encrypt certificates expire quarterly", 'ssl-zen' ),
                'free' => __( 'You redo it, 4× a year', 'ssl-zen' ),
                'pro'  => __( 'Automatic, forever', 'ssl-zen' ),
                'warn' => true,
            ),
            array(
                'feat' => __( 'Domain verification', 'ssl-zen' ),
                'sub'  => __( 'Proving you own the site', 'ssl-zen' ),
                'free' => __( 'Manual DNS edits', 'ssl-zen' ),
                'pro'  => __( 'Automatic', 'ssl-zen' ),
                'warn' => false,
            ),
            array(
                'feat' => __( 'SSL certificate installation', 'ssl-zen' ),
                'sub'  => __( 'Issuing and activating the padlock', 'ssl-zen' ),
                'free' => __( '~15 min, multi-step', 'ssl-zen' ),
                'pro'  => __( 'About 60 seconds', 'ssl-zen' ),
                'warn' => false,
            ),
            array(
                'feat' => __( 'Support', 'ssl-zen' ),
                'sub'  => __( 'When you need a hand', 'ssl-zen' ),
                'free' => __( 'Community', 'ssl-zen' ),
                'pro'  => __( 'Priority email', 'ssl-zen' ),
                'warn' => false,
            ),
        );
        ?>
        <form name="form-pricing" id="form-pricing" action="" method="post">
            <?php wp_nonce_field( 'ssl_zen_pricing', 'ssl_zen_pricing_nonce' ); ?>
            <div class="ssl-zen-steps-container szl p-0 border-0">

                <div class="szl-head">
                    <h1><?php esc_html_e( 'Secure your site once. Sleep easy for years.', 'ssl-zen' ); ?></h1>
                </div>

                <div class="szl-hook">
                    <span class="szl-hook-ic" aria-hidden="true">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#EA580C" stroke-width="2.1" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13.5"/><line x1="12" y1="17.5" x2="12.01" y2="17.5"/></svg>
                    </span>
                    <span class="szl-hook-t"><b><?php esc_html_e( "Don't leave your site's security to memory.", 'ssl-zen' ); ?></b> <?php esc_html_e( 'Certificates expire every 90 days — miss one and visitors see "Not Secure." Lock in protection in one click and never think about it again.', 'ssl-zen' ); ?></span>
                </div>

                <div class="szl-grid">

                    <div class="szl-card">
                        <div class="szl-tier"><?php esc_html_e( 'Pro Annual', 'ssl-zen' ); ?></div>
                        <div class="szl-tag"><?php esc_html_e( 'Good to get started', 'ssl-zen' ); ?></div>
                        <div class="szl-price"><b>$29</b><span><?php esc_html_e( '/ year', 'ssl-zen' ); ?></span></div>
                        <div class="szl-spacer"></div>
                        <ul class="szl-feats">
                            <li><?php esc_html_e( 'Automatic domain verification', 'ssl-zen' ); ?></li>
                            <li><?php esc_html_e( 'Automatic SSL install & renewal', 'ssl-zen' ); ?></li>
                            <li><?php esc_html_e( 'Auto HTTP → HTTPS redirect', 'ssl-zen' ); ?></li>
                            <li><?php esc_html_e( 'Priority email support', 'ssl-zen' ); ?></li>
                        </ul>
                        <a class="szl-cta ghost" href="<?php echo esc_url( $url_annual ); ?>"><?php esc_html_e( 'Choose Annual', 'ssl-zen' ); ?></a>
                        <div class="szl-fine"><?php esc_html_e( 'Billed $29/yr · cancel anytime', 'ssl-zen' ); ?></div>
                    </div>

                    <div class="szl-card feat">
                        <div class="szl-badge"><?php esc_html_e( 'Most popular · pay once', 'ssl-zen' ); ?></div>
                        <div class="szl-tier"><?php esc_html_e( 'Lifetime', 'ssl-zen' ); ?></div>
                        <div class="szl-tag"><?php esc_html_e( 'Set it once and never renew — total peace of mind.', 'ssl-zen' ); ?></div>
                        <div class="szl-price"><b>$49</b><span><?php esc_html_e( 'once', 'ssl-zen' ); ?></span></div>
                        <div class="szl-save"><?php esc_html_e( 'Best value · cheaper than 2 years of annual', 'ssl-zen' ); ?></div>
                        <ul class="szl-feats">
                            <li><?php esc_html_e( 'Everything in Pro Annual', 'ssl-zen' ); ?></li>
                            <li><?php esc_html_e( 'Lifetime automatic SSL — one payment', 'ssl-zen' ); ?></li>
                            <li><?php esc_html_e( 'No subscription, no card to expire', 'ssl-zen' ); ?></li>
                            <li><?php esc_html_e( 'Priority support', 'ssl-zen' ); ?></li>
                        </ul>
                        <a class="szl-cta primary" href="<?php echo esc_url( $url_lifetime ); ?>"><?php esc_html_e( 'Get Lifetime', 'ssl-zen' ); ?></a>
                        <div class="szl-fine"><?php esc_html_e( 'One payment · secured for life', 'ssl-zen' ); ?></div>
                    </div>

                    <div class="szl-card">
                        <div class="szl-tier"><?php esc_html_e( 'Done-For-You', 'ssl-zen' ); ?></div>
                        <div class="szl-tag"><?php esc_html_e( 'We install & configure everything', 'ssl-zen' ); ?></div>
                        <div class="szl-price"><b>$297</b><span><?php esc_html_e( 'once', 'ssl-zen' ); ?></span></div>
                        <div class="szl-spacer"></div>
                        <ul class="szl-feats">
                            <li><?php esc_html_e( 'Everything in Lifetime', 'ssl-zen' ); ?></li>
                            <li><?php esc_html_e( 'Our engineers set it up start to finish', 'ssl-zen' ); ?></li>
                            <li><?php esc_html_e( 'Certificate installed & tested for you', 'ssl-zen' ); ?></li>
                            <li><?php esc_html_e( 'Priority support', 'ssl-zen' ); ?></li>
                        </ul>
                        <a class="szl-cta ghost" target="_blank" rel="noopener" href="<?php echo esc_url( SSL_ZEN_DFY_URL ); ?>"><?php esc_html_e( 'Get Done-For-You', 'ssl-zen' ); ?></a>
                        <div class="szl-fine"><?php esc_html_e( 'One-time · white-glove setup', 'ssl-zen' ); ?></div>
                    </div>

                </div>

                <div class="sz-up-compare">
                    <div class="sz-up-row sz-up-head">
                        <div class="sz-up-feat"></div>
                        <div class="sz-up-free"><b><?php esc_html_e( 'Free', 'ssl-zen' ); ?></b><span>$0</span></div>
                        <div class="sz-up-pro"><b><?php esc_html_e( 'Pro', 'ssl-zen' ); ?></b><span><?php esc_html_e( 'automatic', 'ssl-zen' ); ?></span></div>
                    </div>
                    <?php foreach ( $rows as $r ) : ?>
                    <div class="sz-up-row">
                        <div class="sz-up-feat">
                            <span class="sz-up-feat-name"><?php echo esc_html( $r['feat'] ); ?></span>
                            <span class="sz-up-feat-sub"><?php echo esc_html( $r['sub'] ); ?></span>
                        </div>
                        <div class="sz-up-free<?php echo ( $r['warn'] ? ' sz-warn' : '' ); ?>"><?php echo esc_html( $r['free'] ); ?></div>
                        <div class="sz-up-pro"><?php echo $check . ' ' . esc_html( $r['pro'] ); // phpcs:ignore ?></div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <details class="szl-agency">
                    <summary><?php esc_html_e( 'Managing multiple sites?', 'ssl-zen' ); ?> <span><?php esc_html_e( 'Agency & 5-site licenses →', 'ssl-zen' ); ?></span></summary>
                    <div class="szl-agency-panel">
                        <div class="szl-agency-row">
                            <div class="szl-ar-t"><b><?php esc_html_e( '5 sites', 'ssl-zen' ); ?></b><small><?php esc_html_e( 'one license, up to 5 installs', 'ssl-zen' ); ?></small></div>
                            <div class="szl-ar-price">$99<span><?php esc_html_e( '/yr', 'ssl-zen' ); ?></span></div>
                            <a class="szl-ar-cta" href="<?php echo esc_url( $url_5site ); ?>"><?php esc_html_e( 'Choose', 'ssl-zen' ); ?></a>
                        </div>
                        <div class="szl-agency-row">
                            <div class="szl-ar-t"><b><?php esc_html_e( 'Unlimited sites', 'ssl-zen' ); ?></b><small><?php esc_html_e( 'agencies & site builders', 'ssl-zen' ); ?></small></div>
                            <div class="szl-ar-price">$199<span><?php esc_html_e( '/yr', 'ssl-zen' ); ?></span></div>
                            <a class="szl-ar-cta" href="<?php echo esc_url( $url_unlimited ); ?>"><?php esc_html_e( 'Choose', 'ssl-zen' ); ?></a>
                        </div>
                    </div>
                </details>

                <?php ssl_zen_social_proof(); ?>
            </div>
        </form>
        <?php
    }
}

/*
 * The old cPanel / StackPath split is gone — both hosts now see the same offer.
 * Legacy function names kept as thin wrappers so any external reference keeps working.
 */
if ( ! function_exists( 'ssl_zen_stackpath_pricing' ) ) {
    function ssl_zen_stackpath_pricing() { ssl_zen_pricing_table(); }
}
if ( ! function_exists( 'ssl_zen_cpanel_pricing' ) ) {
    function ssl_zen_cpanel_pricing() { ssl_zen_pricing_table(); }
}
if ( ! function_exists( 'ssl_zen_exclusive_option' ) ) {
    function ssl_zen_exclusive_option() { ssl_zen_done_for_you(); }
}
if ( ! function_exists( 'ssl_zen_pricing' ) ) {
    /**
     * Entry point for the pricing/upgrade tab.
     *
     * @since 3.2.5
     */
    function ssl_zen_pricing() {
        ssl_zen_pricing_table();
    }
}
