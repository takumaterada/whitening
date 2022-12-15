<footer id="footer">
<?php if( has_nav_menu( 'footer_nav' ) ){ ?>
  <div class="footer-01">
    <div class="wrap">
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
    </div><!-- /wrap -->
  </div><!-- /footer-01 -->
<?php } //if footer_nav ?>
  <div class="footer-02">
    <div class="wrap">
      <p class="footer-copy">
        © Copyright <?php echo date('Y'); ?> <?php echo get_bloginfo('name'); ?>. All rights reserved.
      </p>
    </div><!-- /wrap -->
  </div><!-- /footer-02 -->
  <?php
  // }
  ?>
</footer>
<a href="#" class="pagetop"><span><i class="fa fa-angle-up"></i></span></a>
<?php wp_footer(); ?>

<script>
(function($){

$(function(){
  <?php if( !wp_is_mobile() ){?>
  $(".sub-menu").css('display', 'none');
  $("#gnav-ul li").hover(function(){
    $(this).children('ul').fadeIn('fast');
  }, function(){
    $(this).children('ul').fadeOut('fast');
  });
  <?php }?>
  // スマホトグルメニュー
  
  <?php if( is_front_page() ){ ?>
    $('#gnav').addClass('active');
  <?php }else{ ?>
    $('#gnav').removeClass('active');
    
  <?php } ?>
  
  
  $('#header-menu-tog a').click(function(){
    $('#gnav').toggleClass('active');
  });
});


//header----
$(function() {
  var $win = $(window),
      $main = $('main'),
      $nav = $('header'),
      navHeight = $nav.outerHeight(),
      navPos = $nav.offset().top,
      fixedClass = 'is-fixed';

  $win.on('load scroll', function() {
    var value = $(this).scrollTop();
    if ( value > navPos ) {
      $nav.addClass(fixedClass);
      $main.css('margin-top', navHeight);
    } else {
      $nav.removeClass(fixedClass);
      $main.css('margin-top', '0');
    }
  });
});
//header----

})(jQuery);

</script>


<script>
jQuery(function($){
  $(function(){
    $(".thumbnail").on('mouseover touchend',function(){
      var dataUrl = $(this).attr('data-url');
      $("#main-image img").attr('src',dataUrl);
    });
  });
});
</script>



</body>
</html>