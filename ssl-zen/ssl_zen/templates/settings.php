<?php
/**
 * Template — post-setup dashboard (redesigned to match the approved mockup:
 * "Your site is secure" hero, Certificate / HTTPS / Site-health cards, Advanced
 * settings, and a Pro upgrade panel). All functional hooks (form nonce, the
 * .toggle-event checkboxes, .save / .deactivate / .renew buttons, Status & Debug
 * tabs) are preserved exactly so behaviour is unchanged.
 *
 * @var array $tabsToShow
 * @var string $activeTab
 * @var string $primaryDomain
 * @var string $issuer
 * @var string $days
 * @var string $circleColor
 * @var string $renewButtonClass
 * @var string $allowRenew
 * @var string $miniMessage
 * @var string $deactivateMsg
 * @var array $serverStatusFields
 * @var array $wordpressStatusFields
 */
$sz_is_free       = ! sz_fs()->is_plan( 'cdn', true ) && sz_fs()->is_free_plan();
// Pro (paid, hands-off) state — shown to real paying customers.
$sz_is_pro        = ( ! sz_fs()->is_plan( 'cdn', true ) && ! sz_fs()->is_free_plan() );
if ( $sz_is_pro ) { $sz_is_free = false; }
$sz_301_on        = ( get_option( 'ssl_zen_enable_301_htaccess_redirect', '' ) == '1' );
$sz_days_val      = is_numeric( $days ) ? min( max( (int) $days, 0 ), 90 ) : 0;
$sz_ring_circ     = 289;
$sz_ring_offset   = (int) round( $sz_ring_circ * ( 1 - ( $sz_days_val / 90 ) ) );
$sz_ring_color    = ! empty( $circleColor ) ? $circleColor : '#1FA971';
?>
<form name="frmSettings" id="frmSettings" action="" method="post">
	<?php wp_nonce_field( 'ssl_zen_settings', 'ssl_zen_settings_nonce' ); ?>
    <ul class="ssl-zen-settings-tab-container d-flex mb-4">
		<?php if ( in_array( 'advanced', $tabsToShow, true ) ) : ?>
            <li data-tab="advanced" class="advanced <?php echo esc_attr( $activeTab === 'advanced' ? 'active' : '' ); ?>"><?php esc_html_e( 'Dashboard', 'ssl-zen' ) ?></li>
		<?php endif; ?>
		<?php if ( in_array( 'status', $tabsToShow, true ) ) : ?>
            <li data-tab="status" class="status <?php echo esc_attr( $activeTab === 'status' ? 'active' : '' ); ?>"><?php esc_html_e( 'Status', 'ssl-zen' ) ?></li>
		<?php endif; ?>
		<?php if ( in_array( 'debug', $tabsToShow, true ) ) : ?>
            <li data-tab="debug" class="debug <?php echo esc_attr( $activeTab === 'debug' ? 'active' : '' ); ?>"><?php esc_html_e( 'Debug', 'ssl-zen' ) ?></li>
		<?php endif; ?>
    </ul>
    <div class="ssl-zen-steps-container p-0 mb-4 border-0">
		<?php if ( in_array( 'advanced', $tabsToShow, true ) ) : ?>
            <div class="ssl-zen-settings-container advanced-container szdash">

                <!-- Hero: is my site secure? -->
                <div class="szdash-hero">
                    <div class="szdash-shield">
                        <svg viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 2l8 3v6c0 5-3.5 8.5-8 10-4.5-1.5-8-5-8-10V5z" fill="#1FA971"/><path d="M8.5 12l2.5 2.5 5-5.5" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </div>
                    <div class="szdash-hero-txt">
                        <div class="szdash-hero-t1"><?php esc_html_e( 'Your site is secure', 'ssl-zen' ); ?></div>
                        <div class="szdash-hero-t2"><?php echo esc_html( $primaryDomain ); ?> &middot; <?php esc_html_e( 'HTTPS enforced', 'ssl-zen' ); ?> &middot; <?php printf( esc_html__( 'certificate issued by %s', 'ssl-zen' ), esc_html( $issuer ) ); ?></div>
                    </div>
                    <div class="szdash-hero-pill">
                        <span class="szdash-chip">&#10003; <?php printf( esc_html__( 'Valid · %s days left', 'ssl-zen' ), esc_html( $sz_days_val ) ); ?></span>
                        <?php if ( $sz_is_free ) : ?><span class="szdash-hero-note"><?php esc_html_e( 'Renews automatically with Pro', 'ssl-zen' ); ?></span><?php elseif ( $sz_is_pro ) : ?><span class="szdash-hero-note szdash-pro-note">&#10003; <?php esc_html_e( 'Auto-renew on · Pro', 'ssl-zen' ); ?></span><?php endif; ?>
                    </div>
                </div>

                <!-- 3 status cards -->
                <div class="szdash-cards">
                    <!-- Certificate -->
                    <div class="szdash-card szdash-cert">
                        <h3><?php esc_html_e( 'Certificate', 'ssl-zen' ); ?></h3>
                        <div class="szdash-sub"><?php echo esc_html( $issuer ); ?> &middot; <?php esc_html_e( '90-day cert', 'ssl-zen' ); ?></div>
                        <div class="szdash-ring">
                            <svg width="104" height="104" viewBox="0 0 104 104">
                                <circle cx="52" cy="52" r="46" fill="none" stroke="#EEE8EF" stroke-width="9"/>
                                <circle cx="52" cy="52" r="46" fill="none" stroke="<?php echo esc_attr( $sz_ring_color ); ?>" stroke-width="9" stroke-linecap="round" stroke-dasharray="<?php echo esc_attr( $sz_ring_circ ); ?>" stroke-dashoffset="<?php echo esc_attr( $sz_ring_offset ); ?>" transform="rotate(-90 52 52)"/>
                            </svg>
                            <div class="szdash-ring-v"><b><?php echo esc_html( $sz_days_val ); ?></b><span><?php esc_html_e( 'days', 'ssl-zen' ); ?></span></div>
                        </div>
                        <?php if ( $sz_is_pro ) : ?>
                            <div class="szdash-autorenew">&#10003; <?php esc_html_e( 'Auto-renews — nothing to do', 'ssl-zen' ); ?></div>
                        <?php else : ?>
                            <a href="#" class="szdash-btn ghost renew <?php echo esc_attr( $renewButtonClass ); ?> <?php echo esc_attr( empty( $allowRenew ) ? 'disabled' : '' ); ?>"><?php esc_html_e( 'Renew now', 'ssl-zen' ); ?></a>
                            <?php if ( $miniMessage ) : ?><span class="szdash-mini"><?php echo esc_html( $miniMessage ); ?></span><?php endif; ?>
                        <?php endif; ?>
                    </div>

                    <!-- HTTPS & redirects -->
                    <div class="szdash-card">
                        <h3><?php esc_html_e( 'HTTPS &amp; redirects', 'ssl-zen' ); ?></h3>
                        <div class="szdash-sub"><?php esc_html_e( 'Every visitor sent to a secure URL', 'ssl-zen' ); ?></div>
                        <div class="szdash-st"><span class="szdash-dot g"></span><?php esc_html_e( 'Force HTTPS', 'ssl-zen' ); ?><span class="szdash-r"><?php esc_html_e( 'On', 'ssl-zen' ); ?></span></div>
                        <div class="szdash-st"><span class="szdash-dot g"></span><?php esc_html_e( 'Mixed content fixed', 'ssl-zen' ); ?><span class="szdash-r"><?php esc_html_e( 'On', 'ssl-zen' ); ?></span></div>
                        <div class="szdash-st"><span class="szdash-dot <?php echo $sz_301_on ? 'g' : 'a'; ?>"></span><?php esc_html_e( '301 .htaccess redirect', 'ssl-zen' ); ?><span class="szdash-r"><?php echo $sz_301_on ? esc_html__( 'On', 'ssl-zen' ) : esc_html__( 'Off', 'ssl-zen' ); ?></span></div>
                        <div class="szdash-st"><span class="szdash-dot g"></span><?php esc_html_e( 'HSTS header', 'ssl-zen' ); ?><span class="szdash-r"><?php esc_html_e( 'On', 'ssl-zen' ); ?></span></div>
                    </div>

                    <!-- Site health -->
                    <div class="szdash-card">
                        <h3><?php esc_html_e( 'Site health', 'ssl-zen' ); ?></h3>
                        <div class="szdash-sub"><?php esc_html_e( 'What browsers see right now', 'ssl-zen' ); ?></div>
                        <div class="szdash-st"><span class="szdash-dot g"></span><?php esc_html_e( 'Padlock showing', 'ssl-zen' ); ?><span class="szdash-r"><?php esc_html_e( 'Yes', 'ssl-zen' ); ?></span></div>
                        <div class="szdash-st"><span class="szdash-dot g"></span><?php esc_html_e( 'No “Not Secure” warning', 'ssl-zen' ); ?><span class="szdash-r"><?php esc_html_e( 'Clear', 'ssl-zen' ); ?></span></div>
                        <div class="szdash-st"><span class="szdash-dot g"></span><?php esc_html_e( 'TLS 1.2 / 1.3', 'ssl-zen' ); ?><span class="szdash-r"><?php esc_html_e( 'Modern', 'ssl-zen' ); ?></span></div>
                        <div class="szdash-st"><span class="szdash-dot g"></span><?php esc_html_e( 'Expiry reminders', 'ssl-zen' ); ?><span class="szdash-r"><?php esc_html_e( 'On', 'ssl-zen' ); ?></span></div>
                    </div>
                </div>

                <!-- Advanced settings + Pro panel -->
                <div class="szdash-g2">
                    <div class="szdash-card">
                        <h3><?php esc_html_e( 'Advanced settings', 'ssl-zen' ); ?></h3>
                        <div class="szdash-sub"><?php esc_html_e( 'For most sites the defaults are perfect.', 'ssl-zen' ); ?></div>
                        <div class="szdash-row">
                            <label class="szdash-sw">
                                <input class="toggle-event" name="enable_301_htaccess_redirect" id="enable_301_htaccess_redirect" type="checkbox" <?php echo $sz_301_on ? 'checked="checked"' : ''; ?>>
                                <span class="szdash-sw-t"></span>
                            </label>
                            <div><div class="szdash-rt"><?php esc_html_e( 'Enable 301 .htaccess redirect', 'ssl-zen' ); ?></div><div class="szdash-rd"><?php esc_html_e( 'Applies the HTTPS redirect at the server level for best performance. If your host shows a redirect loop, switch this off — the built-in redirect keeps your site secure.', 'ssl-zen' ); ?></div></div>
                        </div>
                        <div class="szdash-row">
                            <label class="szdash-sw">
                                <input class="toggle-event" id="lock_htaccess_file" name="lock_htaccess_file" type="checkbox" <?php echo ( get_option( 'ssl_zen_lock_htaccess_file', '' ) == '1' ) ? 'checked="checked"' : ''; ?>>
                                <span class="szdash-sw-t"></span>
                            </label>
                            <div><div class="szdash-rt"><?php esc_html_e( 'Lock down .htaccess file', 'ssl-zen' ); ?></div><div class="szdash-rd"><?php esc_html_e( 'Stops the plugin from editing .htaccess so you can manage it by hand.', 'ssl-zen' ); ?></div></div>
                        </div>
                        <div class="szdash-actions">
                            <a href="#" class="szdash-btn grad save"><?php esc_html_e( 'Save changes', 'ssl-zen' ); ?></a>
                            <a href="#" class="szdash-btn ghost danger deactivate"><?php esc_html_e( 'Deactivate plugin', 'ssl-zen' ); ?></a>
                        </div>
                        <div class="szdash-mini mt-3"><?php echo sprintf( esc_html__( 'Would you like to use SSL Zen in your local language? Click %1$shere%2$s to contribute.', 'ssl-zen' ), '<a href="https://translate.wordpress.org/projects/wp-plugins/ssl-zen/" target="_blank">', '</a>' ); ?></div>
                    </div>

                    <?php if ( $sz_is_free ) : ?>
                    <div class="szdash-up">
                        <div class="szdash-up-halo"></div>
                        <h3><?php esc_html_e( 'Go hands-off with Pro', 'ssl-zen' ); ?></h3>
                        <p><?php esc_html_e( 'Let SSL Zen install and renew your certificate automatically — you never touch it again.', 'ssl-zen' ); ?></p>
                        <ul>
                            <li><?php esc_html_e( 'Automatic domain verification', 'ssl-zen' ); ?></li>
                            <li><?php esc_html_e( 'Automatic installation — no file uploads', 'ssl-zen' ); ?></li>
                            <li><?php esc_html_e( 'Auto-renewal every 90 days', 'ssl-zen' ); ?></li>
                            <li><?php esc_html_e( 'Priority support', 'ssl-zen' ); ?></li>
                        </ul>
                        <a class="szdash-btn grad block" href="<?php echo esc_url( admin_url( 'admin.php?page=ssl_zen&tab=pricing' ) ); ?>"><?php esc_html_e( 'Upgrade to Pro', 'ssl-zen' ); ?></a>
                        <div class="szdash-up-price"><?php esc_html_e( 'From', 'ssl-zen' ); ?> <s>$69</s> <b>$29/yr</b> &middot; <?php esc_html_e( '14-day money-back guarantee', 'ssl-zen' ); ?></div>
                    </div>
                    <?php elseif ( $sz_is_pro ) : ?>
                    <div class="szdash-pro">
                        <div class="szdash-pro-halo"></div>
                        <span class="szdash-pro-badge">&#10003; <?php esc_html_e( 'Pro active', 'ssl-zen' ); ?></span>
                        <h3><?php esc_html_e( 'You’re hands-off', 'ssl-zen' ); ?></h3>
                        <p><?php esc_html_e( 'SSL Zen Pro installs and renews your certificate automatically. There’s nothing left for you to do.', 'ssl-zen' ); ?></p>
                        <ul>
                            <li>&#10003; <?php esc_html_e( 'Domain verification — automatic', 'ssl-zen' ); ?></li>
                            <li>&#10003; <?php esc_html_e( 'Certificate installation — automatic', 'ssl-zen' ); ?></li>
                            <li>&#10003; <?php esc_html_e( 'Renewal every 90 days — automatic', 'ssl-zen' ); ?></li>
                            <li>&#10003; <?php esc_html_e( 'Priority support — active', 'ssl-zen' ); ?></li>
                        </ul>
                        <a class="szdash-btn ghost block" href="<?php echo esc_url( admin_url( 'admin.php?page=ssl_zen&tab=account' ) ); ?>"><?php esc_html_e( 'Manage subscription', 'ssl-zen' ); ?></a>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Cross-promote sibling Zen Plugins products -->
                <div class="szdash-xp">
                    <div class="szdash-xp-head"><?php esc_html_e( 'More from Zen Plugins', 'ssl-zen' ); ?><span class="szdash-xp-trust"><?php esc_html_e( '1M+ downloads · trusted on 77k+ sites', 'ssl-zen' ); ?></span></div>
                    <div class="szdash-xp-grid">
                        <a class="szdash-xp-card" target="_blank" rel="noopener nofollow" href="https://wordpress.org/plugins/404zen-broken-link-fixer/?utm_source=plugin&utm_medium=sslzen_dashboard&utm_campaign=cross_promo">
                            <div class="szdash-xp-top">
                                <span class="szdash-xp-logo lg"><svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="24" cy="24" r="19" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-dasharray="98.2 21.2" transform="rotate(120 24 24)"/><path d="M24 14.5 L32 17.5 V24 C32 29 28.8 33 24 35 C19.2 33 16 29 16 24 V17.5 Z" fill="none" stroke="#fff" stroke-width="2.8" stroke-linejoin="round"/></svg></span>
                                <span class="szdash-xp-flag"><?php esc_html_e( 'Not on your site yet', 'ssl-zen' ); ?></span>
                            </div>
                            <div class="szdash-xp-name">404Zen</div>
                            <div class="szdash-xp-desc"><?php esc_html_e( 'Broken links and 404s quietly cost you SEO and visitors. 404Zen finds and fixes them on your server and grades your whole site — free.', 'ssl-zen' ); ?></div>
                            <span class="szdash-xp-cta"><?php esc_html_e( 'Add to your site', 'ssl-zen' ); ?> &rarr;</span>
                        </a>
                        <a class="szdash-xp-card" target="_blank" rel="noopener nofollow" href="https://redirectzen.com/?utm_source=plugin&utm_medium=sslzen_dashboard&utm_campaign=cross_promo">
                            <div class="szdash-xp-top">
                                <span class="szdash-xp-logo rz"><svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M 21 48 L 21 27 A 11.5 11.5 0 0 1 44 27 L 44 34" stroke="#fff" stroke-width="7.5" stroke-linecap="round"/><path d="M 44 49 L 36.5 36.5 L 51.5 36.5 Z" fill="#fff"/></svg></span>
                                <span class="szdash-xp-flag"><?php esc_html_e( 'Not on your site yet', 'ssl-zen' ); ?></span>
                            </div>
                            <div class="szdash-xp-name">RedirectZen</div>
                            <div class="szdash-xp-desc"><?php esc_html_e( 'Old URLs turning into 404s lose visitors and rankings. RedirectZen catches them with simple 301 redirects — not set up on your site.', 'ssl-zen' ); ?></div>
                            <span class="szdash-xp-cta"><?php esc_html_e( 'Add to your site', 'ssl-zen' ); ?> &rarr;</span>
                        </a>
                    </div>
                </div>
            </div>
		<?php endif; ?>
		<?php
		if ( ! sz_fs()->is_plan( 'cdn', true ) ) :
			$extraClass = in_array( 'advanced', $tabsToShow, true ) || $activeTab !== 'status' ? 'd-none' : '';
			?>
            <div class="row ssl-zen-settings-container status-container <?php echo esc_attr( $extraClass ); ?>">
                <div class="col-md-5">
                    <table class="table table-bordered">
                        <tbody>
                        <tr class="grey"><th>Server</th><th>Info</th></tr>
						<?php foreach ( $serverStatusFields as $key => $field ) : ?>
                            <tr><td><?php echo esc_html( $key ); ?></td><td><?php echo esc_html( $field ); ?></td></tr>
						<?php endforeach; ?>
                        </tbody>
                    </table>
                    <a href="<?php echo wp_nonce_url( admin_url( 'admin.php?page=ssl_zen&tab=settings&download=status_info' ), 'ssl_zen_download', '_sslzen_dl' ); ?>" class="d-inline-block primary mb-2 download-status">Download Status Info</a>
                    <span class="d-block mini-message"><?php esc_html_e( 'When asked, please download and share this file with SSL Zen support team.', 'ssl-zen' ) ?></span>
                </div>
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tbody>
                        <tr class="grey"><th>WordPress</th><th>Info</th></tr>
						<?php foreach ( $wordpressStatusFields as $key => $field ) : ?>
                            <tr><td><?php echo esc_html( $key ); ?></td><td><?php echo esc_html( $field ); ?></td></tr>
						<?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
		<?php endif; ?>
		<?php ssl_zen_debug_container( $tabsToShow, $activeTab ); ?>
    </div>
</form>
