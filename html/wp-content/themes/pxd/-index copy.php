<?php get_header(); ?>

<?php if ( wp_is_mobile() ) : ?>
<img src="<?php echo get_stylesheet_directory_uri(); ?>/img/mobile.jpg"  alt="銀座のセルフホワイトニング＆フェイシャルエステ" />

<div class="index_wrap2">
<?php else: ?>
<div id="container">
	<?php if( is_home( )): ?>
    <section id="main_content">
        <div id="head">
            <h1>セルフホワイトニング＆フェイシャルエステ</h1>
            <p class="sub_head">銀座で完全個室のメンズサロン「ザギンdeホワイトニング」<br>お客様一人に一人のセラピスト、清潔感と高級感のある贅沢な空間で最高の癒しをご提供致します。</p>
        </div>
        <?php /* ?>
        	<ul>
			<li><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/ginza.jpg"  alt="銀座本店" /></li>
			<li><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/ichigaya.jpg"  alt="市ヶ谷店" /></li>
			<li><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/nihonbashi.jpg"  alt="東日本橋店" /></li>
			<li><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/kayabachou.jpg"  alt="茅場町店" /></li>
			</ul>
		<?php */ ?>
    </section>
    <?php endif; ?>
</div>



<div class="index_wrap">	    
 <?php endif; ?>
 
<div id="main">

<h2 class="gsubttl">Real time<span>Twitterでリアルタイム情報 発信中！ <br>現在：<span><?php date_default_timezone_set('Asia/Tokyo'); echo date("Y/m/d H:i") ?></span></span></h2>
<div class="twitterwrap">
<div>
<a class="twitter-timeline"  data-theme="dark" height="300" data-chrome="noheader noborders" data-lang="ja" data-link-color="#981CEB" href="https://twitter.com/zaginwhitening">Tweets by zaginwhitening</a> 
<script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
</div>
</div>


<div class="tabs">
<h2 class="gsubttl">Today's schedule<span>本日のセラピストのご案内</span></h2>

<?php echo do_shortcode('[attmgr_daily guide="2week" shop="銀座"]'); ?>

</div>


<h2 class="gsubttl">Instagram<span>インスタグラム</span></h2>

<?php echo do_shortcode('[instashow rows="2"]');?>



</div><!-- /main -->
  


<?php include('sidebarpage.php'); ?>

</div><!-- /wrap -->
  




<?php get_footer(); ?>


