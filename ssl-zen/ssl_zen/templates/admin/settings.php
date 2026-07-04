<?php
/**
 * Template
 *
 * @var string $tab
 * @var string $stage
 * @var ssl_zen_admin self
 */
?>
<div class="ssl-zen-content-container <?php echo esc_attr( $tab == 'review' ? 'review-page' : '' ); ?>">
    <header class="header clearfix">
        <div class="container">
            <div class="row align-items-center ">
                <div class="col-lg-6 text-lg-left text-center logo mb-3 mb-lg-0">
                    <img src="<?php echo esc_url( SSL_ZEN_URL ); ?>img/logo.svg" alt="logo">
                    <span>V<?php echo esc_html( SSL_ZEN_PLUGIN_VERSION ); ?></span>
                    <span><?php echo esc_html( sz_fs()->can_use_premium_code__premium_only() ? 'Premium' : ' Free' ); ?></span>
                </div>
                <div class="col-lg-6 text-lg-right text-center external-actions-container">
					<?php
					// show settings button only when the stage is that.
					if ( $stage === 'settings' && ssl_zen_helper::isTabAvailableAtThisStage( $tab, 'settings', ssl_zen_admin::$allowedTabs ) ) { ?>
                        <a class="settings" href="<?php echo admin_url( 'admin.php?page=ssl_zen&tab=settings' ); ?>">
							<?php esc_html_e( 'Settings', 'ssl-zen' ); ?>
                        </a>
					<?php }
					if ( ssl_zen_helper::isTabAvailableAtThisStage( $tab, 'upgrade', ssl_zen_admin::$allowedTabs ) && ! sz_fs()->is_premium() ) {
						$szHeaderUpgradeUrl = add_query_arg( array(
							'checkout'      => 'true',
							'plan_id'       => 7397,
							'plan_name'     => 'pro',
							'billing_cycle' => 'annual',
							'pricing_id'    => 7115,
							'currency'      => 'usd',
						), sz_fs()->get_upgrade_url() ); ?>
                        <a class="upgrade sz-upgrade" href="<?php echo esc_url( $szHeaderUpgradeUrl ); ?>">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M13 2L3 14h7l-1 8 10-12h-7z"/></svg>
							<?php esc_html_e( 'Upgrade to Pro', 'ssl-zen' ); ?>
                        </a>
					<?php }
					if ( $stage !== 'settings' ) { ?>
                        <a class="settings" href="<?php echo admin_url( 'admin.php?page=ssl_zen&tab=settings' ); ?>">
							<?php esc_html_e( 'Debug', 'ssl-zen' ); ?>
                        </a>
					<?php }
					if ( ssl_zen_helper::isTabAvailableAtThisStage( $tab, 'support', ssl_zen_admin::$allowedTabs ) ) { ?>
                        <a class="support" href="<?php echo admin_url( 'admin.php?page=ssl_zen-contact' ); ?>">
							<?php esc_html_e( 'Support', 'ssl-zen' ); ?>
                        </a>
					<?php } ?>
                </div>
            </div>
        </div>
    </header>
    <div class="container mt-5">
		<?php
		// Check weather to show steps navigation
		if ( ssl_zen_helper::showLayoutPart( $tab, ssl_zen_admin::$allowedTabs, 'steps_nav' ) ) {
			ssl_zen_admin::stepsNavigation( $tab );
		}
		// Show message container
		self::showMessage();

		// v4.7.13: self-serve account controls (license re-sync + manage/cancel
		// subscription) — deflects the two highest-volume support tickets.
		if ( isset( $tab ) && $tab == 'settings' ) {
			require SSL_ZEN_TEMPLATE_DIR . 'admin/self-serve.php';
		}
		?>
        <section class="ssl-zen-container">
			<?php
			$tabMethod = isset( ssl_zen_admin::$allowedTabs[ $tab ]['method'] ) ? ssl_zen_admin::$allowedTabs[ $tab ]['method'] : '';
			if ( method_exists( ssl_zen_admin::class, $tabMethod ) ) {
				self::$tabMethod();
			} else {
				$tabMethod = ssl_zen_admin::$allowedTabs[ get_option( 'ssl_zen_settings_stage', 'system_requirements' ) ]['method'];
				self::$tabMethod();
			}
			?>
        </section>
    </div>
	<?php if ( ssl_zen_helper::showLayoutPart( $tab, ssl_zen_admin::$allowedTabs, 'footer' ) && ! sz_fs()->is_premium() && SSLZenCPanel::detect_cpanel() ) {
		$upgradeUrl = add_query_arg( array(
			'checkout'      => 'true',
			'plan_id'       => 7397,
			'plan_name'     => 'pro',
			'billing_cycle' => 'annual',
			'pricing_id'    => 7115,
			'currency'      => 'usd'
		), sz_fs()->get_upgrade_url() );
		?>
        <footer class="ssl-zen-footer container">
            <a class="sz-upbar" href="<?php echo esc_url( $upgradeUrl ); ?>">
                <span class="sz-upbar-ic">
                    <svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><defs><linearGradient id="szub" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#F871A0"/><stop offset="1" stop-color="#E6447D"/></linearGradient></defs><path d="M25.96 10.25 A11.5 11.5 0 1 1 18 4.67" stroke="url(#szub)" stroke-width="3.4" fill="none" stroke-linecap="round"/><circle cx="16" cy="14.6" r="3" fill="url(#szub)"/><rect x="14.9" y="16.4" width="2.2" height="5.6" rx="1.1" fill="url(#szub)"/></svg>
                </span>
                <span class="sz-upbar-txt">
                    <strong><?php esc_html_e( 'Go hands-off with SSL Zen Pro', 'ssl-zen' ); ?></strong>
                    <span><?php esc_html_e( 'Automatic domain verification, installation & renewal — your site never slips back to “Not Secure”.', 'ssl-zen' ); ?></span>
                </span>
                <span class="sz-upbar-price"><b>$29</b>/yr &middot; <?php esc_html_e( '14-day money-back', 'ssl-zen' ); ?></span>
                <span class="sz-upbar-btn"><?php esc_html_e( 'Upgrade to Pro', 'ssl-zen' ); ?></span>
            </a>
        </footer>
	<?php } ?>
</div>
