<!DOCTYPE HTML>
<html lang="ja">
<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# article: http://ogp.me/ns/article#">
	<meta charset="UTF-8">
	<title><?php bzb_title(); ?></title>
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
	<!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->


<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-PWJNJ8M');</script>
<!-- End Google Tag Manager -->


<?php wp_head(); ?>


<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/img/favicon.ico">

<script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-135776661-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-135776661-1');
</script>

</head>

<body id="top">
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PWJNJ8M"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->



<?php if ( wp_is_mobile() ) : ?>
<ul class="hinfo">
<!-- 	<li><a href="tel:03-4531-2559">03-4531-2559</a><p>営業10:00-22:00(受付 9:45-21:30)</p></li> -->

	<li><a href="tel:03-4531-2559">03-4531-2559</a><p>月〜土　10：00〜22：00<br>定休日　日曜日</p></li>
	<li><a href="https://mitsuraku.jp/pm/online/index/y0g7l1" target="_blank"><span>24時間 予約受付</span>WEB予約</a></li>
	</ul>
<?php else: ?>
<header>
<div>
<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/logo_w.png"  alt="<?php bzb_title(); ?>" /></a>
<ul class="hinfo">
<!-- <li><a href="tel:03-4531-2559">03-4531-2559</a><p>OPEN／10:00-22:00（受付 9:45-21:30）</p></li> -->

<li><a href="tel:03-4531-2559">03-4531-2559</a><p>月〜土　10：00〜22：00<br>定休日　日曜日</p></li>

<li><a href="https://mitsuraku.jp/pm/online/index/y0g7l1" target="_blank"><span>24時間ご予約受付中</span>WEB予約</a></li>
<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>recruit/"><span>セラピスト</span>採用情報</a></li>
</ul>
</div>

<!-- start global nav  -->
<?php if( has_nav_menu( 'global_nav' ) ){ ?>
<nav id="gnav" role="navigation" itemscope="itemscope" itemtype="http://scheme.org/SiteNavigationElement">
  <div class="wrap">
  <?php
    wp_nav_menu(
      array(
        'theme_location'  => 'global_nav',
        'menu_class'      => 'clearfix',
        'menu_id'         => 'gnav-ul',
        'container'       => 'div',
        'container_id'    => 'gnav-container',
        'container_class' => 'gnav-container'
      )
    );?>
    </div>
</nav>
<?php } ?>
<!-- nav -->
</header>

<?php endif; ?>





<?php if (!( wp_is_mobile() )) : ?>
	<?php if( !(is_home( )) ): ?>
	<div id="container" class="page_container">
		<?php if( is_home( )): ?>
	    <?php endif; ?>
	</div>
	<?php endif; ?>
<?php endif; ?>
