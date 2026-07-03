<?php
/**
 * Template — SSL activated / success screen.
 *
 * Redesigned (4.7.12) to do more than ask for a review:
 *   1. Confirm the site is secured, with a one-click "verify" link.
 *   2. On the free plan, a single honest Pro nudge at the moment of peak
 *      intent — automatic renewal, so the site never goes "Not Secure" again.
 *   3. A sentiment gate: happy users are routed to a wp.org review (protecting
 *      the star rating — the plugin's biggest asset), and users who aren't
 *      happy are routed to support instead of leaving a public 1-star review.
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

                    <div class="szv-gate" id="szv-gate">
                        <p class="szv-gate-q"><?php esc_html_e( 'Is everything working as expected?', 'ssl-zen' ); ?></p>
                        <div class="szv-gate-btns">
                            <button type="button" class="szv-mood" data-mood="happy">😀 <?php esc_html_e( 'Yes, all good!', 'ssl-zen' ); ?></button>
                            <button type="button" class="szv-mood" data-mood="sad">😕 <?php esc_html_e( 'Not quite', 'ssl-zen' ); ?></button>
                        </div>
                    </div>

                    <div class="szv-reveal" id="szv-review" hidden>
                        <div class="propose d-lg-flex align-items-center">
							<?php esc_html_e( 'Wonderful! Could you do us a BIG favour and give SSL Zen a', 'ssl-zen' ); ?>
                            <i class="star ml-2 mr-2"></i><i class="star mr-2"></i><i class="star mr-2"></i><i class="star mr-2"></i><i class="star mr-2"></i>
							<?php esc_html_e( 'on WordPress.org?', 'ssl-zen' ); ?>
                        </div>
                        <a href="https://wordpress.org/support/plugin/ssl-zen/reviews/#new-post" target="_blank" rel="noopener" class="review primary mt-4 mb-2"><?php esc_html_e( 'LEAVE A REVIEW', 'ssl-zen' ); ?></a>
                        <span class="review-timing"><?php esc_html_e( 'It only takes a moment — and it genuinely helps a small team.', 'ssl-zen' ); ?></span>
                    </div>

                    <div class="szv-reveal" id="szv-help" hidden>
                        <p class="szv-help-q"><?php esc_html_e( "Sorry to hear that — let's get it sorted.", 'ssl-zen' ); ?></p>
                        <p class="szv-help-sub"><?php esc_html_e( 'Search our guides for an instant answer, or send our team a message (your site details are attached automatically so we can help fast).', 'ssl-zen' ); ?></p>
                        <button type="button" class="review primary mt-2 mb-2" id="szv-open-help"><?php esc_html_e( 'GET HELP NOW', 'ssl-zen' ); ?></button>
                    </div>

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
.szv-gate{margin-top:26px}
.szv-gate-q{font-weight:700;font-size:15px;color:#1a1d24;margin:0 0 12px}
.szv-gate-btns{display:flex;gap:12px;flex-wrap:wrap}
.szv-mood{background:#fff;border:1.5px solid #e0e3ec;border-radius:10px;padding:11px 20px;font-size:14px;font-weight:600;cursor:pointer;transition:.12s}
.szv-mood:hover{border-color:#e5397f;color:#c72d6c}
.szv-reveal{margin-top:22px}
.szv-help-q{font-weight:700;font-size:15px;margin:0 0 4px;color:#1a1d24}
.szv-help-sub{color:#5b616e;font-size:13.5px;line-height:1.5;margin:0 0 6px;max-width:640px}
</style>
<script>
(function(){
    var gate = document.getElementById('szv-gate');
    if(!gate) return;
    var review = document.getElementById('szv-review');
    var help = document.getElementById('szv-help');
    gate.querySelectorAll('.szv-mood').forEach(function(b){
        b.addEventListener('click', function(){
            var mood = b.getAttribute('data-mood');
            gate.setAttribute('hidden','');
            if(mood === 'happy'){ if(review) review.removeAttribute('hidden'); }
            else { if(help) help.removeAttribute('hidden'); }
        });
    });
    var open = document.getElementById('szv-open-help');
    if(open){
        open.addEventListener('click', function(){
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
