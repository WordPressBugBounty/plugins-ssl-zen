<?php
/**
 * Template — SSL activated / success screen.
 *
 * Success screen:
 *   1. Confirm the site is secured, with a one-click "verify" link.
 *   2. On the free plan, a single honest Pro nudge at the moment of peak
 *      intent — automatic renewal, so the site never goes "Not Secure" again.
 *   3. A single, ungated review ask shown to everyone, with an always-visible
 *      "get help" link alongside it. (4.7.38 removed the earlier happy/unhappy
 *      sentiment gate: routing only satisfied users to public reviews is a
 *      review-manipulation pattern that risks the wp.org rating — every user now
 *      sees the same ask and the same support option.)
 */
$is_free    = ( function_exists( 'sz_fs' ) && sz_fs()->is_free_plan() );
$site_url   = home_url( '/', 'https' );
$upgrade_url = '';
if ( function_exists( 'sz_fs' ) && method_exists( sz_fs(), 'get_upgrade_url' ) ) {
	$upgrade_url = add_query_arg( array(
		'checkout'      => 'true',
		'plan_id'       => 7397,
		'plan_name'     => 'pro',
		'billing_cycle' => 'annual',
		'pricing_id'    => 7115,
		'currency'      => 'usd',
	), sz_fs()->get_upgrade_url() );
}
?>
<form name="frmReview" id="frmReview" action="" method="post">
	<?php wp_nonce_field( 'ssl_zen_review', 'ssl_zen_review_nonce' ); ?>
    <div class="ssl-zen-steps-container p-0 mb-4 border-0">
        <div class="ssl-arrow"></div>
        <div class="row ssl-zen-review-container">
            <div class="col-md-12">
                <div class="description pl-5 pr-0">
                    <div class="ssl mb-4">
                        <div class="lock"></div>
                        <div class="line"></div>
                    </div>
                    <h4><?php esc_html_e( 'Your site is secured! 🎉', 'ssl-zen' ); ?></h4>
                    <p class="saved-quote">
						<?php esc_html_e( 'SSL is live — your visitors now see the padlock, and you just saved $60/year in certificate fees.', 'ssl-zen' ); ?>
                    </p>

                    <div class="szv-verify-row">
                        <a href="<?php echo esc_url( $site_url ); ?>" target="_blank" rel="noopener" class="szv-verify">
							<?php esc_html_e( 'Open my secure site', 'ssl-zen' ); ?> &rarr;
                        </a>
                        <span class="szv-verify-note"><?php esc_html_e( 'Look for the padlock in the address bar.', 'ssl-zen' ); ?></span>
                    </div>

					<?php if ( $is_free && ! empty( $upgrade_url ) ) : ?>
                    <div class="szv-pro">
                        <div class="szv-pro-body">
                            <strong><?php esc_html_e( 'Never do this by hand again.', 'ssl-zen' ); ?></strong>
                            <span><?php esc_html_e( 'Free certificates expire every ~90 days and must be renewed manually. Pro renews and reinstalls automatically, so your site never slips back to "Not Secure".', 'ssl-zen' ); ?></span>
                        </div>
                        <a href="<?php echo esc_url( $upgrade_url ); ?>" class="szv-pro-btn">
							<?php esc_html_e( 'Turn on automatic renewal', 'ssl-zen' ); ?>
                        </a>
                    </div>
					<?php endif; ?>

                    <div class="szv-review-block">
                        <div class="propose d-lg-flex align-items-center">
                            <i class="star mr-2"></i>
							<?php esc_html_e( 'Enjoying SSL Zen? Please leave a review for SSL Zen on WordPress.org — it genuinely helps a small team.', 'ssl-zen' ); ?>
                        </div>
                        <a href="https://wordpress.org/support/plugin/ssl-zen/reviews/#new-post" target="_blank" rel="noopener" class="review primary mt-4 mb-2"><?php esc_html_e( 'LEAVE A REVIEW', 'ssl-zen' ); ?></a>
                        <span class="review-timing"><?php esc_html_e( 'It only takes a moment.', 'ssl-zen' ); ?></span>
                        <p class="szv-help-line">
							<?php esc_html_e( 'Something not working right?', 'ssl-zen' ); ?>
                            <a href="#" id="szv-open-help"><?php esc_html_e( 'Get help from our team', 'ssl-zen' ); ?></a>
                        </p>
                    </div>

					<?php require SSL_ZEN_TEMPLATE_DIR . 'admin/recommendations.php'; ?>

                </div>
            </div>
        </div>
    </div>
</form>

<style>
.szv-verify-row{margin:6px 0 4px;display:flex;flex-wrap:wrap;align-items:center;gap:6px 14px}
.szv-verify{display:inline-block;font-weight:700;color:#e5397f;text-decoration:none;font-size:15px}
.szv-verify:hover{color:#c72d6c}
.szv-verify-note{color:#8a8f9a;font-size:13px}
.szv-pro{margin:22px 0 6px;background:#fdeef4;border:1px solid #f6d3e2;border-radius:12px;padding:16px 18px;display:flex;flex-wrap:wrap;gap:14px;align-items:center;justify-content:space-between;max-width:720px}
.szv-pro-body{flex:1;min-width:260px}
.szv-pro-body strong{display:block;font-size:15px;color:#1a1d24;margin-bottom:3px}
.szv-pro-body span{color:#5b616e;font-size:13.5px;line-height:1.5}
.szv-pro-btn{white-space:nowrap;background:#e5397f;color:#fff;font-weight:700;font-size:14px;padding:11px 20px;border-radius:9px;text-decoration:none}
.szv-pro-btn:hover{background:#c72d6c;color:#fff}
.szv-review-block{margin-top:26px}
.szv-help-line{margin:14px 0 0;color:#5b616e;font-size:13.5px}
.szv-help-line a{color:#e5397f;font-weight:600;text-decoration:none}
.szv-help-line a:hover{color:#c72d6c}
</style>
<script>
(function(){
    var open = document.getElementById('szv-open-help');
    if(open){
        open.addEventListener('click', function(e){
            if(e && e.preventDefault) e.preventDefault();
            if(window.SSLZEN_SUPPORT && typeof window.SSLZEN_SUPPORT.open === 'function'){
                window.SSLZEN_SUPPORT.open('ask');
            } else {
                var btn = document.querySelector('.szw-btn');
                if(btn) btn.click();
            }
        });
    }
})();
</script>
