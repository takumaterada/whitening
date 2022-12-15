<?php get_header(); ?>

<div class="content_wrap">	


<?php bzb_breadcrumb(); ?>

<?php if(is_page( array( 96,98 ) ) ): ?>
<div id="main"> 
<?php endif; ?>


<?php if ( have_posts() ) :	while ( have_posts() ) : the_post(); ?>


<?php $cf = get_post_meta($post->ID); ?>
<?php
$page = get_post( get_the_ID() );
$slug = $page->post_name;
?>
<h1 class="gsubttl"><?php echo $slug; ?><span><?php the_title(); ?></span></h1>

<div class="content">
      
      <?php if( get_the_post_thumbnail() ) : ?>
      <div class="post-thumbnail">
        <?php the_post_thumbnail(); ?>
      </div>
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
      
     <?php endwhile; else :?>
    <p>投稿が見つかりません。</p>
    <?php endif;?>
</div>


<?php if(is_page( array( 96,98 ) ) ): ?>
</div>
<?php include('sidebarpage.php'); ?>
<?php endif; ?>


</div><!-- /content_wrap -->






<?php get_footer(); ?>
