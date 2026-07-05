<?php
/**
 * Template
 *
 * @var string $selectedVariant
 * @var boolean $cPanel
 * @var boolean $httpRisky
 */
?>
<form name="frmstep2" id="frmstep2" action="" method="post">
	<?php
	wp_nonce_field( 'ssl_zen_verify', 'ssl_zen_verify_nonce' );
	if ( empty( $selectedVariant ) ) :
		$showNextButton = true;
		// On subfolder / non-web-root installs the HTTP file check can't be
		// reached at the domain root, so default to (and recommend) DNS.
		$defaultVariant = ! empty( $httpRisky ) ? 'dns' : 'http';
		?>
        <input type="hidden" id="ssl_zen_domain_verification"
               name="ssl_zen_domain_verification"
               value="<?php echo esc_attr( $defaultVariant ); ?>">
        <input type="hidden" id="ssl_zen_sub_step"
               name="ssl_zen_sub_step" value="1">
        <div class="ssl-zen-steps-container mb-4">
            <div class="row">
                <div class="col-md-12 mb-5">
                    <p class="verification-question">
						<?php esc_html_e( 'Which domain verification process would you like to use?', 'ssl-zen' ); ?>
                    </p>
					<?php if ( ! empty( $httpRisky ) ) : ?>
                        <div class="message info mt-3">
							<?php esc_html_e( 'Heads up: WordPress looks like it runs from a subfolder or a directory that isn’t your domain’s web root. The HTTP (file upload) method usually fails on setups like this, so we’ve selected DNS verification for you — it verifies through a DNS record and doesn’t depend on where files are placed.', 'ssl-zen' ); ?>
                        </div>
					<?php endif; ?>
                </div>
                <div class="col-md-6">
                    <div class="ssl-zen-domain-verification-variant-container http <?php echo esc_attr( $selectedVariant == 'http' || ( $selectedVariant == '' && empty( $httpRisky ) ) ? 'selected' : '' ); ?> p-4">
                        <div class="d-flex justify-content-between mb-5">
                            <div>
                                <span class="font-weight-bold http">HTTP</span>
                            </div>
                            <div>
                                <span class="minute">10 mins</span>
                            </div>
                        </div>
                        <div class="mb-4">
                            <h5><?php esc_html_e( 'Step 1', 'ssl-zen' ); ?></h5>
                            <p><?php esc_html_e( 'Create .well-known/acme-challenge folder ', 'ssl-zen' ); ?></p>
                        </div>
                        <div>
                            <h5><?php esc_html_e( 'Step 2 ', 'ssl-zen' ); ?></h5>
                            <p><?php esc_html_e( 'Upload verification file(s) ', 'ssl-zen' ); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="ssl-zen-domain-verification-variant-container <?php echo esc_attr( $selectedVariant == 'dns' || ( $selectedVariant == '' && ! empty( $httpRisky ) ) ? 'selected' : '' ); ?> p-4">
                        <div class="d-flex justify-content-between mb-5">
                            <div>
                                <span class="font-weight-bold dns">DNS</span>
                            </div>
                            <div>
                                <span class="minute">7 mins</span>
                            </div>
                        </div>
                        <div class="mb-4">
                            <h5><?php esc_html_e( 'Step 1', 'ssl-zen' ); ?></h5>
                            <p><?php esc_html_e( 'Identify your domain host', 'ssl-zen' ); ?></p>
                        </div>
                        <div>
                            <h5><?php esc_html_e( 'Step 2', 'ssl-zen' ); ?></h5>
                            <p><?php esc_html_e( 'Add a domain TXT record', 'ssl-zen' ); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	<?php else:
		// If selected variant was HTTP , then we need to fetch pending authorizations for further download
		$arrPendingHttp = ssl_zen_certificate::getPendingAuthorization( \LEClient\LEOrder::CHALLENGE_TYPE_HTTP, false );
		$arrPendingDns = ssl_zen_certificate::getPendingAuthorization( \LEClient\LEOrder::CHALLENGE_TYPE_DNS, false );
		// Get verification status
		$showNextButton = get_option( 'ssl_zen_domain_verified', '' );
		// Get next DNS check time left and calc diff if it is not empty
		$dnsCheckActivation = get_option( 'ssl_zen_dns_check_activation', '' );
		$diff               = ! empty( $dnsCheckActivation ) ? $dnsCheckActivation - time() : null;
		// v4.7.11: if the challenge has died (no pending authorizations left) and
		// the domain isn't verified yet, recreate a fresh order so the user gets a
		// new record and can retry — instead of being stranded with a permanently
		// disabled Scan button and a hidden record table.
		if ( empty( $arrPendingDns ) && empty( $arrPendingHttp ) && empty( $showNextButton ) ) {
			ssl_zen_certificate::forceNewOrder();
			$arrPendingHttp     = ssl_zen_certificate::getPendingAuthorization( \LEClient\LEOrder::CHALLENGE_TYPE_HTTP, false );
			$arrPendingDns      = ssl_zen_certificate::getPendingAuthorization( \LEClient\LEOrder::CHALLENGE_TYPE_DNS, false );
			$dnsCheckActivation = '';
			$diff               = null;
		}
		// Logic for scan-dns button class and also timer class
		if ( empty( $arrPendingDns ) ) {
			$scanDnsButtonClass = 'disabled';
			$timerButtonClass   = 'd-none';
		} else {
			if ( empty( $diff ) || $diff < 0 ) {
				$scanDnsButtonClass = '';
				$timerButtonClass   = 'd-none';
			} else {
				$scanDnsButtonClass = 'disabled';
				$timerButtonClass   = '';
			}
		}
		//TODO show success message in proper variant container or in general container(is stage step2 and is verified)
		?>
        <input type="hidden" id="ssl_zen_sub_step"
               name="ssl_zen_sub_step" value="2">
        <ul class="ssl-zen-domain-verification-variant-tabs d-flex m-0">
            <li class="http <?php echo esc_attr( $selectedVariant == 'http' ? 'active' : '' ); ?>">HTTP
            </li>
            <li class="dns <?php echo esc_attr( $selectedVariant == 'dns' ? 'active' : '' ); ?>">DNS
            </li>
        </ul>
        <div class="ssl-zen-steps-container <?php echo esc_attr( $selectedVariant == 'dns' ? 'p-0' : '' ); ?> mb-4 custom-round">
            <div class="row">
                <div class="col-md-12 ssl-zen-domain-verification-variant-tab-container http <?php echo esc_attr( $selectedVariant == 'http' ? '' : 'd-none' ); ?>">
                    <div class="row ">
                        <div class="col-md-12">
                            <div class="border-bottom pb-5">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4 class="mb-4">
											<?php esc_html_e( 'HTTP Verification', 'ssl-zen' ); ?>

											<?php if ( $cPanel ) : ?>
                                                <a href="https://www.youtube.com/watch?v=9PT7r8TSHks"
                                                   class="tutorial ml-3"
                                                   target="_blank"><?php esc_html_e( 'Video Tutorial', 'ssl-zen' ); ?></a>

											<?php else: ?>
                                                <a href="https://www.youtube.com/watch?v=XApeU26YcV8"
                                                   class="tutorial ml-3"
                                                   target="_blank"><?php esc_html_e( 'Video Tutorial', 'ssl-zen' ); ?></a>
											<?php endif; ?>
                                        </h4>
                                    </div>
                                </div>
                                <p class="szv-intro"><?php esc_html_e( 'A quick one-time step to prove you own this site: download the file below, upload it to your website inside the .well-known/acme-challenge folder shown, then click Verify. Prefer we do it automatically? That is what Pro is for.', 'ssl-zen' ); ?></p>
                                <div class="row">
                                    <div class="col-md-4">
                                        <h5><span class="szv-num">1</span><?php esc_html_e( 'STEP 1', 'ssl-zen' ); ?></h5>
                                        <p><?php esc_html_e( 'Create a folder to upload verification files', 'ssl-zen' ); ?></p>
                                    </div>
                                    <div class="col-md-8">
                                        <span><?php esc_html_e( 'Navigate to the Folder where you have hosted WordPress.', 'ssl-zen' ); ?></span><br>
                                        <span><?php esc_html_e( 'Create a folder', 'ssl-zen' ); ?></span>
                                        <span class="folder">.well-known</span>
                                        <span><?php esc_html_e( 'and inside it another folder', 'ssl-zen' ); ?></span><br>
                                        <span class="folder">acme-challenge</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mt-5">
                            <h5><span class="szv-num">2</span><?php esc_html_e( 'STEP 2', 'ssl-zen' ); ?></h5>
                            <p><?php esc_html_e( 'Upload the verification file(s), then verify', 'ssl-zen' ); ?></p>
                        </div>
                        <div class="col-md-8 mt-5">
                            <span><?php esc_html_e( 'Download the file(s) below on your local computer and', 'ssl-zen' ); ?></span>
                            <br>
                            <span><?php esc_html_e( 'upload them in', 'ssl-zen' ); ?></span>
                            <span class="folder">.well-known/acme-challenge</span>
                            <span>folder</span><br>
                        </div>
                    </div>
                    <div class="row justify-content-end">
                        <div class="col-md-8 mt-3">
                            <div class="d-flex justify-content-start align-items-center">
								<?php if ( ! empty( $arrPendingHttp ) ) :
									foreach (
										$arrPendingHttp as $index => $item
									) {
										?>
                                        <a href="<?php echo wp_nonce_url( admin_url( 'admin.php?page=ssl_zen&tab=step2&download=' . $index ), 'ssl_zen_download', '_sslzen_dl' ); ?>"
                                           class="download-file primary mr-3"><?php echo esc_html( __( 'Download file', 'ssl-zen' ) . ' ' . ( $index + 1 ) ); ?>
                                        </a>
										<?php
									}
								endif; ?>
                                <a class="scan-http primary mr-3 <?php echo esc_attr( empty( $arrPendingHttp ) ? 'disabled' : '' ); ?>"><?php esc_html_e( 'Verify', 'ssl-zen' ); ?></a>
                                <div class="message-container"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 ssl-zen-domain-verification-variant-tab-container dns <?php echo esc_attr( $selectedVariant == 'dns' ? '' : 'd-none' ); ?>">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="ssl-zen-domain-verification-variant-tab-container-left">
                                <h4 class="mb-4">
									<?php esc_html_e( 'DNS Verification', 'ssl-zen' ); ?>
                                    <a href="https://youtu.be/ubT5EpBr6-U"
                                       class="tutorial ml-3"
                                       target="_blank"><?php esc_html_e( 'Video Tutorial', 'ssl-zen' ); ?></a>
                                </h4>
                                <p><?php _e(
										'To verify domain ownership, you will need to create a DNS record of the
                                                TXT type as shown below.', 'ssl-zen'
									); ?>
                                </p>
								<?php if ( ! empty( $arrPendingDns ) ) : ?>
                                    <div class="record-table mt-4">
                                        <div class="head"><?php esc_html_e( 'Domain TXT Record', 'ssl-zen' ); ?></div>
                                        <div class="head"><?php esc_html_e( 'Value', 'ssl-zen' ); ?></div>
										<?php
										foreach ( $arrPendingDns as $key => $item ) :
											$rowClass = ! $key ? 'first' : 'second';
											$value = ssl_zen_helper::checkWWWSubDomainExistence( $item['identifier'] ) ? '_acme-challenge.www' : '_acme-challenge';
											?>
                                            <div class="record <?php echo esc_attr( $rowClass ); ?> d-flex align-items-center justify-content-between">
                                                <input class="acme"
                                                       type="text"
                                                       value="<?php echo esc_attr( $value ); ?>">
                                                <i class="copy"
                                                   title="<?php esc_attr_e( 'Copy', 'ssl-zen' ) ?>"></i>
                                            </div>
                                            <div class="record <?php echo esc_attr( $rowClass ); ?> d-flex align-items-center justify-content-between">
                                                <input class="txt"
                                                       type="text"
                                                       value="<?php echo esc_attr( $item['DNSDigest'] ); ?>">
                                                <i class="copy"
                                                   title="<?php esc_attr_e( 'Copy', 'ssl-zen' ) ?>"></i>
                                            </div>
										<?php
										endforeach;
										?>
                                    </div>
								<?php endif; ?>
                                <div class="align-items-center d-flex mt-4">
                                    <a class="scan-dns primary mr-3 <?php echo esc_attr( $scanDnsButtonClass ); ?>">Scan
                                        DNS Record</a>
									<?php if ( ! is_null( $diff ) && $diff > 0 ) :
										?>
                                        <script>
                                            var sslDnsCheckTimeLeft = <?php echo esc_attr( $diff ); ?>;
                                        </script>
									<?php endif; ?>
                                    <span class="time-wait <?php echo esc_attr( $timerButtonClass ); ?>">
                                                    <?php echo sprintf(
                                                    /* translators: %s: Milliseconds div */
	                                                    __( 'Wait for %s to try again.', 'ssl-zen' ),
	                                                    '<span class="ms"></span>'
                                                    ) ?>
                                                </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="description pb-5 pt-5 pl-4 pr-4">
                                <h4><?php esc_html_e( 'How to add a TXT record ?', 'ssl-zen' ) ?></h4>
                                <ul>
                                    <li><?php esc_html_e( 'Sign in to your domain host.', 'ssl-zen' ) ?></li>
                                    <li><?php esc_html_e( 'Go to your domain’s DNS records.', 'ssl-zen' ) ?>
										<?php esc_html_e( 'The page might be called something like', 'ssl-zen' ) ?>
                                        DNS Management, Name Server
                                        Management, Control Panel,
                                        or Advanced
                                        Settings. <?php esc_html_e( 'Select the option to add a new record.', 'ssl-zen' ) ?>
                                    </li>
                                    <li><?php esc_html_e( 'For the record type, select TXT', 'ssl-zen' ) ?></li>
                                    <li><?php esc_html_e( 'In the Name/Host/Alias field, enter ', 'ssl-zen' ) ?> [
                                        _acme-challenge ]
                                    </li>
                                    <li><?php esc_html_e( 'In the TTL field, enter 300 or lower', 'ssl-zen' ) ?></li>
                                    <li><?php esc_html_e( 'In the Value/Answer/Destination field, paste the verification record and Save the record.', 'ssl-zen' ) ?></li>
                                    <li><?php esc_html_e( 'Come back here and click on Scan DNS Record button.', 'ssl-zen' ) ?></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	<?php endif;
	$nextButtonClass = empty( $showNextButton ) ? 'disabled' : '';
	?>
    <div class="text-right mb-4">
        <a class="primary next <?php echo esc_attr( $nextButtonClass ); ?>"
           href="#"><?php esc_html_e( 'Next', 'ssl-zen' ); ?></a>
    </div>
</form>
