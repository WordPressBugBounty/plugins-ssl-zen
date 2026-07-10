<?php

/**
 * Template
 *
 * @var string $heading
 * @var string $image
 * @var string $tagline
 * @var array $apiResponse
 */
?>
<form name="frmstep1" id="frmstep1" action="" method="post"
      autocomplete="off">
	<?php 
wp_nonce_field( 'ssl_zen_generate_certificate', 'ssl_zen_generate_certificate_nonce' );
?>
    <div class="ssl-zen-steps-container mb-4">
        <div class="row">
            <div class="col-12">
                <p class="starting-quote">
					<?php 
echo esc_html( $heading );
?>
                </p>
                <div class="media">
                    <div class="media-left">
                        <img class="media-object"
                             src="<?php 
echo esc_url( SSL_ZEN_URL );
?>img/<?php 
echo $image;
?>.svg"
                             alt="encrypt">
                    </div>
                    <div class="media-body">
                        <p>
							<?php 
echo esc_html( $tagline );
?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row align-items-center p-4">
            <div class="col-sm-3">
                <div>
					<?php 
esc_html_e( 'Domain Details', 'ssl-zen' );
?>
                </div>
            </div>
            <div class="col-sm-9 pt-4 pb-4">
                <label for="domaiAdress"><?php 
esc_html_e( 'Domain Address', 'ssl-zen' );
?></label>
                <br>
                <span class="text mb-3">
                    <?php 
$urlInfo = parse_url( get_site_url() );
$host = ( isset( $urlInfo['host'] ) ? $urlInfo['host'] : '' );
echo esc_html( $host );
?>
                </span>
                <input type="hidden" name="base_domain_name"
                       id="base_domain_name"
                       value="<?php 
echo esc_attr( $host );
?>">
				<?php 
if ( sz_fs()->is_plan( 'cdn', true ) ) {
    ?>
                    <span class="mini-message d-block w-100"><?php 
    esc_html_e( 'The domain name you would like to point to the StackPath Edge.', 'ssl-zen' );
    ?></span>
				<?php 
}
?>

				<?php 
if ( !ssl_zen_helper::checkWWWSubDomainExistence( $host ) && !sz_fs()->is_plan( 'cdn', true ) ) {
    ?>
                    <div class="checkbox checkbox-success checkbox-circle">
                        <input type="checkbox" class="styled" name="include_www" id="include_www"
                               value="1" <?php 
    echo esc_attr( ( get_option( 'ssl_zen_include_wwww', '' ) == '1' ? 'checked="checked"' : '' ) );
    ?> >
                        <label for="include_www">
							<?php 
    esc_html_e( 'Include www-prefixed version too?', 'ssl-zen' );
    ?> &nbsp;
                            <a href="#"
                               data-toggle="tooltip"
                               data-placement="right"
                               title="<?php 
    esc_html_e( 'By default, we generate SSL certificate only for domain.com. If user enters www.domain.com your website will show a not secure warning. Check this box to create a certificate for www.domain.com too. Make sure you have a CNAME or A record added for www in your domain panel.', 'ssl-zen' );
    ?>">
                                <img src="<?php 
    echo esc_url( SSL_ZEN_URL );
    ?>img/imp.svg" alt="">
                            </a>
                        </label>
                    </div>
				<?php 
}
?>
            </div>
            <!-- Additional two columns for showing message container -->
            <div class="col-md-3"></div>
            <div class="col-md-9">
                <div class="message-container"></div>
            </div>
            <!-- end message container -->

			<?php 
if ( sz_fs()->is_plan( 'cdn', true ) ) {
    ?>
                <div class="col-sm-3">
                    <div>
						<?php 
    esc_html_e( 'Hostname/IP Address', 'ssl-zen' );
    ?>
                        &nbsp;
                    </div>
                </div>
                <div class="col-sm-9 pt-4 pb-4">
                    <label for="ip_address"><?php 
    esc_html_e( 'Hostname/IP Address', 'ssl-zen' );
    ?></label>
                    <br>
                    <span class="text mb-3">
                            <?php 
    echo esc_html( $apiResponse['ip'] );
    ?>
                                </span>
                    <input type="hidden" name="ip_address"
                           id="ip_address"
                           value="<?php 
    echo esc_html( $apiResponse['ip'] );
    ?>">
                    <span class="mini-message d-block w-100"><?php 
    esc_html_e( 'The IP address of your website.', 'ssl-zen' );
    ?></span>

                </div>
				<?php 
} else {
    ?>
                <div class="col-sm-3">
                    <div>
						<?php 
    esc_html_e( 'Contact Details', 'ssl-zen' );
    ?>
                        &nbsp;
                    </div>
                </div>
                <div class="col-sm-9 pt-4 pb-4">
                    <label for="email"><?php 
    esc_html_e( 'Email Address', 'ssl-zen' );
    ?></label> <br>
                    <input type="email" name="email" id="email"
                           placeholder="<?php 
    esc_html_e( 'Enter your email address', 'ssl-zen' );
    ?>"
                           value="<?php 
    echo esc_attr( get_option( 'ssl_zen_email' ) );
    ?>"
                           required>
                </div>
				<?php 
}
?>
			<?php 
?>
			<?php 
if ( !sz_fs()->is_plan( 'cdn', true ) ) {
    ?>
                <div class="col-sm-3 mt-4"></div>
                <div class="col-sm-9 mt-4">
                    <div class="sz-check">
                        <input type="checkbox" name="terms" id="terms" value="1" required>
                        <label for="terms">
							<?php 
    echo sprintf( 
        /* translators: 1: Start of link tag 2: End of link tag*/
        __( 'I agree to %1$sTerms and Conditions%2$s', 'ssl-zen' ),
        '<a href="https://sslzen.com/terms-of-service/" target="_blank">',
        '</a>'
     );
    ?>
                        </label>
                    </div>
                    <div style="margin-top:14px;padding:14px 16px;background:#fdf0f6;border:2px solid #e5397f;border-radius:10px;box-shadow:0 2px 8px rgba(229,57,127,0.12);">
                        <span style="display:inline-block;background:#e5397f;color:#fff;font-size:10.5px;font-weight:700;letter-spacing:.04em;text-transform:uppercase;padding:2px 10px;border-radius:20px;margin-bottom:9px;"><?php esc_html_e( 'Recommended', 'ssl-zen' ); ?></span>
                        <div class="sz-check">
                            <input type="checkbox" name="marketing_optin" id="marketing_optin" value="1">
                            <label for="marketing_optin">
                                <strong style="display:block;color:#1b1620;font-size:15px;margin-bottom:2px;"><?php esc_html_e( 'Email me before my SSL certificate expires', 'ssl-zen' ); ?></strong>
                                <span style="color:#5a5560;font-size:13px;line-height:1.5;"><?php esc_html_e( 'Get a renewal reminder so your site never slips back to “Not Secure” — plus occasional SSL security tips and new free tools from Zen Plugins. No spam, unsubscribe in one click anytime.', 'ssl-zen' ); ?></span>
                            </label>
                        </div>
                    </div>
                </div>
			<?php 
}
?>
        </div>
    </div>
        <div class="sz-next-row">
            <a class="sslzen-step1-next-button primary next" href="#"><?php
esc_html_e( 'Next', 'ssl-zen' );
?></a>
        </div>
</form>
