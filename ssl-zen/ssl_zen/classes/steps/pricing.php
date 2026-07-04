<?php

/**
 * SSL Zen upgrade / pricing screen (tab=pricing).
 *
 * Redesigned 2026-07-04: slim value-prop header (the old full-width pink hero
 * banner was mostly empty space), a benefit-driven Free-vs-Pro comparison that
 * justifies the upgrade (the real hook is that Let's Encrypt certificates expire
 * every 90 days, so Free = redo the whole install four times a year), a compact
 * "Done For You" strip, and a single honest $29/yr Pro plan (7397) for every
 * host. The old non-cPanel variant showed $49 and linked its button to the
 * retired hidden CDN plan 10884 — a dead-end checkout Freemius will not open.
 */

if ( ! function_exists( 'ssl_zen_pro_upgrade_url' ) ) {
    /**
     * The one working Pro checkout URL. Plan 7397 / pricing 7115, $29/yr annual.
     */
    function ssl_zen_pro_upgrade_url() {
        return add_query_arg( array(
            'checkout'      => 'true',
            'plan_id'       => 7397,
            'plan_name'     => 'pro',
            'billing_cycle' => 'annual',
            'pricing_id'    => 7115,
            'currency'      => 'usd',
        ), sz_fs()->get_upgrade_url() );
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
    /**
     * Compact "Done For You" upsell strip (was a full card row).
     */
    function ssl_zen_done_for_you() {
        ?>
        <div class="sz-up-dfy">
            <div class="sz-up-dfy-text">
                <b><?php esc_html_e( 'Prefer we do it for you?', 'ssl-zen' ); ?></b>
                <?php esc_html_e( 'A professional tech expert installs your SSL certificate for you — limited spots.', 'ssl-zen' ); ?>
            </div>
            <a class="sz-up-dfy-btn" target="_blank" rel="noopener" href="https://sslzen.com/premium-done-for-you-solution/?utm_source=plugin&utm_medium=pricing&utm_campaign=done_for_you"><?php esc_html_e( 'Done-For-You setup', 'ssl-zen' ); ?></a>
        </div>
        <?php
    }
}

if ( ! function_exists( 'ssl_zen_pricing_table' ) ) {
    /**
     * The unified upgrade screen — same honest $29 Pro offer for every host.
     */
    function ssl_zen_pricing_table() {
        $upgrade = ssl_zen_pro_upgrade_url();
        $check   = '<svg class="sz-up-ic" width="15" height="15" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M16.7 5.7 8.2 14.2 3.7 9.7" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>';

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
            <div class="ssl-zen-steps-container sz-up p-0 border-0">

                <div class="sz-up-hero">
                    <div class="sz-up-hero-icon">
                        <svg width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <rect x="4" y="10" width="16" height="10" rx="2.5" stroke="currentColor" stroke-width="1.8"/>
                            <path d="M8 10V7a4 4 0 0 1 8 0v3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                            <path d="m10.5 14.7 1 1 2-2.2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="sz-up-hero-text">
                        <h1><?php esc_html_e( 'Stop reinstalling SSL by hand every 90 days', 'ssl-zen' ); ?></h1>
                        <p><?php esc_html_e( 'Pro verifies, installs and auto-renews your certificate — forever. Set it once and forget it.', 'ssl-zen' ); ?></p>
                    </div>
                    <div class="sz-up-hero-price"><s>$69</s><b>$29</b><span><?php esc_html_e( '/ year', 'ssl-zen' ); ?></span><em class="sz-up-save"><?php esc_html_e( 'Save 58%', 'ssl-zen' ); ?></em></div>
                </div>

                <div class="sz-up-compare">
                    <div class="sz-up-row sz-up-head">
                        <div class="sz-up-feat"></div>
                        <div class="sz-up-free"><b><?php esc_html_e( 'Free', 'ssl-zen' ); ?></b><span>$0</span></div>
                        <div class="sz-up-pro"><b><?php esc_html_e( 'Pro', 'ssl-zen' ); ?></b><span><s>$69</s> $29<?php esc_html_e( '/yr', 'ssl-zen' ); ?></span></div>
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
                    <div class="sz-up-row sz-up-cta-row">
                        <div class="sz-up-feat"></div>
                        <div class="sz-up-free"><span class="sz-up-current"><?php esc_html_e( 'Your current plan', 'ssl-zen' ); ?></span></div>
                        <div class="sz-up-pro"><a class="sz-up-btn" href="<?php echo esc_url( $upgrade ); ?>"><?php esc_html_e( 'Upgrade to Pro', 'ssl-zen' ); ?></a></div>
                    </div>
                </div>

                <?php ssl_zen_done_for_you(); ?>
                <?php ssl_zen_social_proof(); ?>
            </div>
        </form>
        <?php
    }
}

/*
 * The old cPanel / StackPath split is gone — both hosts now see the same honest
 * Pro offer. The legacy function names are kept as thin wrappers so any external
 * reference keeps working.
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
