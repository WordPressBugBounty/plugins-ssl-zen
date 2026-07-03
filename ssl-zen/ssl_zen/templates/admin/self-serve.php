<?php
/**
 * Self-serve account controls (4.7.13).
 *
 * Deflects the two highest-volume support tickets found in a year of Help Scout
 * data — "I upgraded but it still shows Free" (license not synced) and
 * cancellation / stop-auto-renew requests — by letting the user resolve both
 * themselves, using Freemius's own supported account actions (no support email
 * needed). Rendered on the Settings tab only.
 */
if ( ! function_exists( 'sz_fs' ) ) {
	return;
}
$sz_sync_url    = sz_fs()->get_account_url( 'sync_user' );      // re-checks the license with Freemius
$sz_account_url = sz_fs()->get_account_url();                    // manage / cancel subscription + auto-renew
$sz_is_premium  = method_exists( sz_fs(), 'can_use_premium_code__premium_only' ) ? sz_fs()->can_use_premium_code__premium_only() : false;
?>
<div class="ssl-zen-selfserve" style="max-width:960px;margin:18px auto 0;display:grid;grid-template-columns:1fr 1fr;gap:14px;">
    <style>
        .ssl-zen-selfserve .szs-card{background:#fff;border:1px solid #e6e8ef;border-radius:12px;padding:16px 18px;}
        .ssl-zen-selfserve .szs-card h4{margin:0 0 4px;font-size:15px;color:#1a1a2e;}
        .ssl-zen-selfserve .szs-card p{margin:0 0 12px;color:#5b616e;font-size:13px;line-height:1.5;}
        .ssl-zen-selfserve .szs-btn{display:inline-block;background:#e5397f;color:#fff !important;text-decoration:none;font-weight:700;font-size:13px;padding:9px 16px;border-radius:8px;}
        .ssl-zen-selfserve .szs-btn.secondary{background:#fff;color:#c72d6c !important;border:1px solid #f0c4d7;}
        .ssl-zen-selfserve .szs-btn:hover{opacity:.92;}
        @media(max-width:720px){.ssl-zen-selfserve{grid-template-columns:1fr;}}
    </style>

    <div class="szs-card">
        <h4><?php esc_html_e( 'Upgraded but it still says “Free”?', 'ssl-zen' ); ?></h4>
        <p><?php esc_html_e( 'If you bought Pro but the plugin hasn’t switched over yet, re-check your license — this syncs your account with our billing system and activates Pro instantly. No need to contact support.', 'ssl-zen' ); ?></p>
        <a class="szs-btn" href="<?php echo esc_url( $sz_sync_url ); ?>"><?php esc_html_e( 'Re-check my license', 'ssl-zen' ); ?></a>
    </div>

    <div class="szs-card">
        <h4><?php esc_html_e( 'Manage or cancel your subscription', 'ssl-zen' ); ?></h4>
        <p><?php esc_html_e( 'View your plan, turn off auto-renewal, or cancel any time from your account page — it takes effect immediately and your certificate keeps working through the current term.', 'ssl-zen' ); ?></p>
        <a class="szs-btn secondary" href="<?php echo esc_url( $sz_account_url ); ?>"><?php esc_html_e( 'Manage subscription', 'ssl-zen' ); ?></a>
    </div>
</div>
