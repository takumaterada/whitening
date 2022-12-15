<?php get_header(); ?>

<div class="content_wrap">

<?php bzb_breadcrumb(); ?>

<?php if(in_category('2')): ?>
<div class="therapist_detail">


<div class="h1name">
<h1><?php //the_title(); ?><span><?php echo post_custom('age'); ?> ／ <?php echo post_custom('position'); ?></span></h1>
<!-- <p class="shukkin"><span><?php //$userid = post_custom('userid');  echo do_shortcode('[attmgr_today_work id="'.$userid.'" text="本日出勤"]'); ?></span></p> -->
</div>


<div class="photo">
<div id="main-image">
<?php if( get_the_post_thumbnail() ) : ?>
<?php the_post_thumbnail(800,800); ?>
<?php endif; ?>
<?php if(in_category(4)) : ?><p class="sinjinicon"><?php $cats = get_the_category(); foreach($cats as $cat) :if($cat->parent) echo $cat->cat_name . ' '; endforeach; ?></p><?php endif; ?>
</div>

<ul class="sp">
<?php if( get_the_post_thumbnail() ) : ?><li><img class="thumbnail" data-url="<?php the_post_thumbnail_url(1000,1000); ?>" src="<?php the_post_thumbnail_url(300,300); ?>"/><?php endif; ?></li>
<?php if(get_post_meta($post->ID,'photo1',true)): ?><li><img class="thumbnail" data-url="<?php echo wp_get_attachment_url(get_post_meta($post->ID,"photo1",true));?>" src="<?php echo wp_get_attachment_url(get_post_meta($post->ID,"photo1",true));?>" /></li><?php endif; ?>
<?php if(get_post_meta($post->ID,'photo2',true)): ?><li><img class="thumbnail" data-url="<?php echo wp_get_attachment_url(get_post_meta($post->ID,"photo2",true));?>" src="<?php echo wp_get_attachment_url(get_post_meta($post->ID,"photo2",true));?>" /></li><?php endif; ?>
</ul>


</div>


<div class="profile">
<?php echo  nl2br(post_custom('comment')); ?>

<ul class="pc">
<?php if( get_the_post_thumbnail() ) : ?><li><img class="thumbnail" data-url="<?php the_post_thumbnail_url(1000,1000); ?>" src="<?php the_post_thumbnail_url(300,300); ?>"/><?php endif; ?></li>
<?php if(get_post_meta($post->ID,'photo1',true)): ?><li><img class="thumbnail" data-url="<?php echo wp_get_attachment_url(get_post_meta($post->ID,"photo1",true));?>" src="<?php echo wp_get_attachment_url(get_post_meta($post->ID,"photo1",true));?>" /></li><?php endif; ?>
<?php if(get_post_meta($post->ID,'photo2',true)): ?><li><img class="thumbnail" data-url="<?php echo wp_get_attachment_url(get_post_meta($post->ID,"photo2",true));?>" src="<?php echo wp_get_attachment_url(get_post_meta($post->ID,"photo2",true));?>" /></li><?php endif; ?>
</ul>


<a href="https://mitsuraku.jp/pm/online/index/y0g7l1" target="_blank" class="webyoyaku"><span><i class="fa fa-heart"></i></span>今すぐWEB予約をする</a>
</div>




<ul class="nextdelink">
	<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>category/staff/" <?php if(is_category(2)): ?>class="onbutton"<?php endif; ?>>スタッフ一覧を見る</a></li>
<!-- 	<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>weekly/"<?php if(is_page(8)): ?>class="onbutton"<?php endif; ?>>出勤表を見る</a></li> -->
</ul>

</div>


<?php else: ?>

  <div id="main">

    <?php if ( have_posts() ) :while ( have_posts() ) : the_post(); ?>

    <?php  global $post; $cf = get_post_meta($post->ID);?>

    <ul class="post-meta list-inline">
      <li class="date updated" itemprop="datePublished" datetime="<?php the_time('c');?>"><i class="fa fa-clock-o"></i> <?php the_time('Y.m.d');?></li>
    </ul>
    <h1 class="post-title" itemprop="headline"><?php the_title(); ?></h1>
      <?php bzb_social_buttons();?>

    <?php if( get_the_post_thumbnail() ) : ?>
      <?php the_post_thumbnail(); ?>
    <?php endif; ?>

    <?php
      the_content();
      $args = array(
       'before' => '<div class="pagination">',
       'after' => '</div>',
       'link_before' => '<span>',
       'link_after' => '</span>'
      );
      wp_link_pages($args);
    ?>

    <?php  endwhile;  else : ?>
    <p>投稿が見つかりません。</p>
    <?php  endif; ?>

  </div><!-- /main -->

<?php get_sidebar(); ?>

<?php endif; ?>

</div><!-- /content_wrap -->

<?php get_footer(); ?>
