<?php get_header(); ?>

<div class="content_wrap">

<?php bzb_breadcrumb(); ?>

<?php if(in_category('2')): ?>

<?php
  $category = get_the_category();
  $cat_id   = $category[0]->cat_ID;
  $cat_name = $category[0]->cat_name;
  $cat_slug = $category[0]->category_nicename;
?>
<h1 class="gsubttl"><?php echo $cat_slug; ?><span><?php bzb_title(); ?></span></h1>
<p class="subttl">
	一人でも多くのお客様が笑顔に自信を持ち、楽しい幸せな毎日を過ごせることを願っております。<br>当店がそのきっかけ作りのお手伝いが出来るようスタッフ一同サポートさせて頂きます。
</p>
<div class="content">


<div class="therapist_list">
<?php if ( have_posts() ) :  while ( have_posts() ) : the_post(); ?>
	<div>
	<a href="<?php the_permalink(); ?>" class="thumb-square"><?php the_post_thumbnail('thumbnail'); ?><?php if(in_category(4)) : ?><p><?php $cats = get_the_category(); foreach($cats as $cat) :if($cat->parent) echo $cat->cat_name . ' '; endforeach; ?></p><?php endif; ?></a>

	<p href="<?php the_permalink(); ?>" class="list_name">
    <?php /*
  	<a href="<?php the_permalink(); ?>">
      <?php if(mb_strlen($post->post_title,'UTF-8')>60) {$title= mb_substr($post->post_title,0,60,'UTF-8');echo $title.…;} else {echo $post->post_title;}?>
    </a>
    */ ?>
  </p>


<?php echo mb_strimwidth(post_custom('comment'), 0, 70 , '...'); ?>



	</div>
<?php  endwhile; endif; ?>
</div>



<?php else: ?>


<?php
  $category = get_the_category();
  $cat_id   = $category[0]->cat_ID;
  $cat_name = $category[0]->cat_name;
  $cat_slug = $category[0]->category_nicename;
?>
<h1 class="gsubttl"><?php echo $cat_slug; ?><span><?php bzb_title(); ?></span></h1>


<?php if ( have_posts() ) :  while ( have_posts() ) : the_post(); ?>


<ul class="post-meta list-inline">
<li class="date updated" itemprop="datePublished" datetime="<?php the_time('c');?>"><i class="fa fa-clock-o"></i> <?php the_time('Y.m.d');?></li>
</ul>
<h2 class="post-title" itemprop="headline"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>


<?php if( get_the_post_thumbnail() ) { ?>
<div class="post-thumbnail">
<a href="<?php the_permalink(); ?>" rel="nofollow"><?php the_post_thumbnail(); ?></a>
</div>
<?php } ?>

<?php the_content('続きを読む'); ?>

<?php  endwhile; else : ?>
<?php echo get_template_part('content', 'none'); ?>
<?php endif;?>


<?php  endif; ?>

<?php if (function_exists("pagination")) {
pagination($wp_query->max_num_pages);
} ?>


</div>


<?php //include('sidebarpage.php'); ?>

</div><!-- /content_wrap -->

<?php get_footer(); ?>
