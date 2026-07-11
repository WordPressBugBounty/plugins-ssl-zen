<?php
/**
 * Reusable "Recommended for your site" cross-sell card (v4.7.21).
 *
 * 404Zen (own product, free) always shows — $0 network acquisition.
 * Affiliate slots appear ONLY when their URL is set, so out of the box the
 * card shows just 404Zen; each sponsored slot auto-appears the moment a
 * tracking link is added. First-party only — no third-party ad/tracker
 * scripts (matches the privacy-first Zen Plugins brand).
 *
 * Add / edit items or paste affiliate links via the `ssl_zen_recommendations`
 * filter, or by setting the URLs below.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

$sz_recs = apply_filters( 'ssl_zen_recommendations', array(
    array(
        'id'     => '404zen',
        'family' => true,
        'icon'   => '&#128279;', // link
        'badge'  => __( 'Zen Plugins · Free', 'ssl-zen' ),
        'title'  => '404Zen',
        'desc'   => __( 'Find and fix broken links, 404s and dead images before Google does — one Link Health Grade for your whole site. Runs on your server, unlimited, free.', 'ssl-zen' ),
        'url'    => 'https://wordpress.org/plugins/404zen-broken-link-fixer/?utm_source=sslzen&utm_medium=plugin&utm_campaign=crosssell',
        'cta'    => __( 'Get 404Zen — free', 'ssl-zen' ),
    ),
    array(
        'id'    => 'kinsta',
        'icon'  => '&#9889;', // bolt
        'badge' => __( 'Sponsored', 'ssl-zen' ),
        'title' => __( 'Kinsta hosting', 'ssl-zen' ),
        'desc'  => __( 'Fast, secure managed WordPress hosting with SSL built in and expert support.', 'ssl-zen' ),
        'url'   => '', // paste Kinsta affiliate link here to show this slot
        'cta'   => __( 'Learn more', 'ssl-zen' ),
    ),
    array(
        'id'    => 'blogvault',
        'icon'  => '&#128737;', // shield
        'badge' => __( 'Sponsored', 'ssl-zen' ),
        'title' => __( 'BlogVault backups', 'ssl-zen' ),
        'desc'  => __( 'Automatic daily off-site backups with one-click restore — your undo button if anything breaks.', 'ssl-zen' ),
        'url'   => '', // paste BlogVault affiliate link here to show this slot
        'cta'   => __( 'Learn more', 'ssl-zen' ),
    ),
) );

// Hide any slot without a URL (empty affiliate slots stay hidden until a link is added).
$sz_recs = array_values( array_filter( (array) $sz_recs, function ( $r ) {
    return ! empty( $r['url'] );
} ) );
if ( empty( $sz_recs ) ) { return; }
?>
<div class="sz-recs" id="sz-recs">
    <div class="sz-recs-head">
        <strong><?php esc_html_e( 'Recommended for your site', 'ssl-zen' ); ?></strong>
        <span class="sz-recs-disc"><?php esc_html_e( 'Tools we use and trust. We may earn a commission — at no cost to you.', 'ssl-zen' ); ?></span>
        <button type="button" class="sz-recs-x" id="sz-recs-x" aria-label="<?php esc_attr_e( 'Dismiss recommendations', 'ssl-zen' ); ?>">&times;</button>
    </div>
    <div class="sz-recs-grid">
        <?php foreach ( $sz_recs as $r ) :
            $is_family = ! empty( $r['family'] ); ?>
            <div class="sz-rec<?php echo $is_family ? ' own' : ''; ?>">
                <div class="sz-rec-top">
                    <span class="sz-rec-ic" aria-hidden="true"><?php echo wp_kses_post( $r['icon'] ); ?></span>
                    <span class="sz-rec-tag <?php echo $is_family ? 'family' : 'spon'; ?>"><?php echo esc_html( $r['badge'] ); ?></span>
                </div>
                <h5><?php echo esc_html( $r['title'] ); ?></h5>
                <p><?php echo esc_html( $r['desc'] ); ?></p>
                <a class="sz-rec-cta <?php echo $is_family ? 'grad' : 'ghost'; ?>"
                   href="<?php echo esc_url( $r['url'] ); ?>"
                   target="_blank"
                   rel="noopener nofollow sponsored"><?php echo esc_html( $r['cta'] ); ?></a>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<script>
(function(){
    var box = document.getElementById('sz-recs'), x = document.getElementById('sz-recs-x');
    if(!box || !x){ return; }
    try{ if(localStorage.getItem('sz_recs_dismissed') === '1'){ box.style.display = 'none'; } }catch(e){}
    x.addEventListener('click', function(){
        box.style.display = 'none';
        try{ localStorage.setItem('sz_recs_dismissed', '1'); }catch(e){}
    });
})();
</script>
