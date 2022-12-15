<div id="side">



<?php if( is_home() || is_page( array( 96,98 ) ) ): ?>
<!-- <p class="side_gsubttl">Twetter</p>
<div class="sitetwitter">
<a class="twitter-timeline" height="300" data-chrome="noheader noborders" data-lang="ja" data-link-color="#981CEB" href="https://twitter.com/ginzawhitening">Tweets by zaginwhitening</a>
<script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
</div> -->
<?php endif; ?>

<!-- <p class="sidebana"><a href="<?php echo esc_url( home_url( '/' ) ); ?>price/"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/sr_bn.jpg" alt="極上コース" /></a></p> -->


<?php echo do_shortcode('[metaslider id="278"]'); ?>

<!-- <p class="sidebana"><a href="<?php echo esc_url( home_url( '/' ) ); ?>recruit/"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/recruit_bana.jpg" alt="ホワイトニングサロン スタッフ募集" /></a></p> -->

<?php if( has_nav_menu( 'footer_nav' ) ){ ?>
<p class="side_gsubttl">Contents</p>
<?php
wp_nav_menu(
  array(
    'theme_location'  => 'footer_nav',
    'menu_class'      => '',
    'menu_id'         => 'fnav',
    'container'       => 'nav',
    'items_wrap'      => '<ul id="footer-nav" class="%2$s">%3$s</ul>'
  )
);?>
<?php } //if footer_nav ?>



</div>
