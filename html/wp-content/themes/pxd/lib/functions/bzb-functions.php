<?php


/* add comment..
* ---------------------------------------- */
add_filter('body_class', 'bzb_body_post_layout');

function bzb_body_post_layout($classes){

  // レイアウトの設定
  global $post;

  $post_layout = "";
  if( is_front_page() || is_home() || is_category() || is_archive() || is_search() ){

    /*$cf = get_post_meta($post->ID);
    if(isset($cf['bzb_post_layout'][0]) && $cf['bzb_post_layout'][0] !==''){
      $post_layout = $cf['bzb_post_layout'][0];
    }else{*/
      $post_layout = get_option('post_layout');
    //}

  }elseif( is_single() || is_page() || is_page_template('page-lp.php') ){
    $cf = get_post_meta($post->ID);
    if(isset($cf['bzb_post_layout']) && $cf['bzb_post_layout'] !== ''){
      if( is_array( $cf['bzb_post_layout'] ) ){
        $post_layout = reset($cf['bzb_post_layout']);
      }else{
        $post_layout = $cf['bzb_post_layout'];
      }
    }else{
        $post_layout = get_option('post_layout');
    }
  }
  $classes[] = esc_attr($post_layout);

  return $classes;
}


/* add comment..
* ---------------------------------------- */
add_filter('body_class', 'bzb_color_scheme');

function bzb_color_scheme($classes){
  $color_scheme = get_option('color_scheme');
  $classes[] = $color_scheme;

  return $classes;
}


/* add comment..
* ---------------------------------------- */
function bzb_show_facebook_block(){

  $facebook_block = '';

  $facebook_app_id = esc_html(get_option('facebook_app_id'));

  $facebook_block=<<<EOF
  <div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/ja_JP/sdk.js#xfbml=1&version=v2.8&appId={$facebook_app_id}";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

EOF;
  echo $facebook_block;
}


/* add comment..
* ---------------------------------------- */
add_action('wp_footer', 'bzb_show_google_plus_block');
function bzb_show_google_plus_block(){
  $google_block = '';
  $google_block =<<<EOF
  <script src="https://apis.google.com/js/platform.js" async defer>
    {lang: 'ja'}
  </script>
EOF;
  echo $google_block;
}


/* add comment..
* ---------------------------------------- */
if( !function_exists('bzb_breadcrumb') ){

  function bzb_breadcrumb(){

    global $post;

    // トップページはパンくずなし
    if( is_home() || is_front_page() ) {
      return;
    }

    // ポストタイプを取得
    $post_type = get_post_type( $post );

    $bc  = '<ol class="breadcrumb clearfix">';
    $bc .= '<li itemscope="itemscope" itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.home_url().'" itemprop="url"><span itemprop="title">ホーム</span></a> / </li>';

    if( is_home() ){
      // メインページ
      $bc .= '<li>最新記事一覧</li>';
    }elseif( is_search() ){
      // 検索結果ページ
      $bc .= '<li>「'.get_search_query().'」の検索結果</li>';
    }elseif( is_404() ){
      // 404ページ
      $bc .= '<li>ページが見つかりませんでした</li>';
    }elseif( is_date() ){
      // 日付別一覧ページ
      $bc .= '<li>';
      if( is_day() ){
        $bc .= get_the_time('Y').'年 ';
        $bc .= get_the_time('n').'月 ';
        $bc .= get_the_time('j').'日';
      }elseif( is_month() ){
        $bc .= get_the_time('Y').'年 ';
        $bc .= get_the_time('n').'月 ';
      }elseif( is_year() ){
        $bc .= get_the_time('Y').'年 ';
      }
      $bc .= '</li>';
    }elseif( is_post_type_archive() ){
      // カスタムポストアーカイブ
      $bc .= '<li>'.post_type_archive_title('', false).'</li>';
    }elseif( is_category() ){
      // カテゴリーページ
      $cat = get_queried_object();
      if( $cat -> parent != 0 ){
        $ancs = array_reverse(get_ancestors( $cat->cat_ID, 'category' ));
        foreach( $ancs as $anc ){
          $bc .= '<li itemscope="itemscope" itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.get_category_link($anc).'" itemprop="url"><span itemprop="title">'.get_cat_name($anc).'</span></a> / </li>';
        }
      }
      $bc .= '<li>'.$cat->cat_name.'</li>';
    }elseif( is_tag() ){
      // タグページ
      $bc .= '<li>'.single_tag_title("",false).'</li>';
    }elseif( is_author() ){
      // 著者ページ
      $bc .= '<li>'.get_the_author_meta('display_name').'</li>';
    }elseif( is_attachment() ){
      // 添付ファイルページ
      if( $post->post_parent != 0 ){
        $bc .= '<li itemscope="itemscope" itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.get_permalink( $post->post_parent ).'" itemprop="url"><span itemprop="title">'.get_the_title( $post->post_parent ).'</span></a> / </li>';
      }
      $bc .= '<li>'.$post->post_title.'</li>';
    }elseif( is_singular('post') ){
      $cats = get_the_category( $post->ID );
      $cat = $cats[0];

      if( $cat->parent != 0 ){
        $ancs = array_reverse(get_ancestors( $cat->cat_ID, 'category' ));
        foreach( $ancs as $anc ){
          $bc .= '<li itemscope="itemscope" itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.get_category_link( $anc ).'" itemprop="url"><span itemprop="title">'.get_cat_name($anc).'</span></a> / </li>';
        }
      }
      $bc .= '<li itemscope="itemscope" itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.get_category_link( $cat->cat_ID ).'" itemprop="url"><span itemprop="title">'.$cat->cat_name.'</span></a> / </li>';
      $bc .= '<li>'.$post->post_title.'</li>';
    }elseif( is_singular('page') ){
      // 固定ページ
      if( $post->post_parent != 0 ){
        $ancs = array_reverse( $post->ancestors );
        foreach( $ancs as $anc ){
          $bc .= '<li itemscope="itemscope" itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.get_permalink( $anc ).'" itemprop="url"><span itemprop="title">'.get_the_title($anc).'</span></a> /';
        }
      }
      $bc .= '<li>'.$post->post_title.'</li>';
    }elseif( is_singular( $post_type ) ){
      // カスタムポスト記事ページ
      $obj = get_post_type_object($post_type);

      if( $obj->has_archive == true ){
      $bc .= '<li itemscope="itemscope" itemtype="http://data-vocabulary.org/Breadcrumb"><a href="'.get_post_type_archive_link($post_type).'" itemprop="url"><span itemprop="title">'.get_post_type_object( $post_type )->label.'</span></a> / </li>';
      }
      $bc .= '<li>'.$post->post_title.'</li>';
    }else{
      // その他のページ
      $bc .= '<li>'.$post->post_title.'</li>';
    }

    $bc .= '</ol>';

    echo $bc;

  }
}


/* レイアウト
* ---------------------------------------- */
function bzb_layout_main(){
  global $post;

  if( !is_object($post) ){
        return;
  }

  $cf = get_post_meta($post->ID);
  $main_layout = '';
  $post_layout = '';

  if(isset( $cf['bzb_post_layout'] )){
    if( is_array( $cf['bzb_post_layout'] ) ){
      $post_layout = reset($cf['bzb_post_layout']);
    }else{
      $post_layout = $cf['bzb_post_layout'];
    }
  }
  $post_layout = get_option('post_layout');
  if( is_single() || is_page() ){
    if( "right-content" == $post_layout ){
      $main_layout = "col-md-8  col-md-push-4";
    }elseif( "one-column" == $post_layout){
      $main_layout = "col-md-10 col-md-offset-1";
    }else{
      $main_layout = "col-md-8";
    }
  }elseif( "right-content" == $post_layout ){
    $main_layout = "col-md-8  col-md-push-4";
  }elseif( "one-column" == $post_layout ){
    $main_layout = "col-md-10 col-md-offset-1";
  }else {
    $main_layout = "col-md-8";
  }

  echo 'class="'.esc_attr($main_layout).'"';
}

function bzb_layout_side(){
  global $post;

  if( !is_object($post) ){
        return;
  }

  $cf = get_post_meta($post->ID);
  $post_layout = '';

  if(isset($cf['bzb_post_layout'])){
    if( is_array( $cf['bzb_post_layout'] ) ){
      $post_layout = reset($cf['bzb_post_layout']);
    }else{
      $post_layout = $cf['bzb_post_layout'];
    }
  }
  $bzb_option = get_option('bzb_option');
  if( is_single() || is_page() ){
    if( "right-content" == $post_layout ){
      $side_layout = "col-md-4 col-md-pull-8";
    }elseif( "one-column" == $post_layout){
      $side_layout = "display-none";
    }else{
      $side_layout = "col-md-4";
    }
  }elseif( "right-content" == $bzb_option['post_layout'] ){
    $side_layout = "col-md-4 col-md-pull-8";
  }elseif( "one-column" == $bzb_option['post_layout'] ){
    $side_layout = "display-none";
  }else{
    $side_layout = "col-md-4";
  }

  echo 'class="'.esc_attr($side_layout).'"';
}

function bzb_layout_side_lp(){
  global $post;
  $cf = get_post_meta($post->ID);
  $post_layout = "";
  if(isset($cf['bzb_post_layout'])){
    if( is_array( $cf['bzb_post_layout'] ) ){
      $post_layout = reset($cf['bzb_post_layout']);
    }else{
      $post_layout = $cf['bzb_post_layout'];
    }
  }
  if( "right-content" == $post_layout ){
    $side_layout = "col-md-4 col-md-pull-8";
  }elseif( "one-column" == $post_layout){
    $side_layout = "display-none";
  }else{
    $side_layout = "col-md-4";
  }

  echo 'class="'.esc_attr($side_layout).'"';
}



/* add comment..
* ---------------------------------------- */
function bzb_get_cta($pid = ""){
  global $post;
  $check_cta = '';
  $org_button_cvtag = '';
  $cp_id = '';
  $bzb_cta = get_post_meta($post->ID, 'bzb_cta', true);
  $select_button = $select_button_url = $select_button_cvtag = '';
  if(is_array($bzb_cta)){
    extract($bzb_cta);
  }

  if( 'none' == $check_cta || '' == $check_cta ) {
    return false;
    //nothing
  }elseif($check_cta == 'custompost'){
    $cp_id =  $cta_select;
    $bzb_cta = get_post_meta($cp_id, 'bzb_cta', true);

    extract($bzb_cta);//select_button,select_button_url

    $customposts = get_post($cp_id);

    $bzb_cta['title'] = ($customposts->post_title);

    $bzb_cta['content'] = apply_filters('the_content', stripslashes($customposts->post_content));

    $bzb_cta['button_text'] = ($select_button);
    $bzb_cta['button_url'] = esc_url($select_button_url);
    $bzb_cta['button_cvtag'] = esc_html($select_button_cvtag);

    $thumbnail_id = get_post_thumbnail_id($cp_id);
    $image = wp_get_attachment_image_src( $thumbnail_id, 'medium' );
    $bzb_cta['img_src'] = $image[0];
    $width = $image[1];
    $height = $image[2];

    $bzb_cta['image'] = '<img src="' . $bzb_cta['img_src'] . '" width="' . $width . '" height="' . $height . '">';
  }elseif($check_cta == 'pageorg'){//オリジナルはエスケープ処理を入れている
    $cta_title = ($org_title);
    $bzb_cta['title'] = esc_html($cta_title);
    $bzb_cta['content'] = apply_filters('the_content', stripslashes($org_content));
    $bzb_cta['img_src'] = $org_image;
    $bzb_cta['image'] = '<img src="' . esc_url($org_image) . '">';
    $bzb_cta['button_text'] = ($org_button_text);
    $bzb_cta['button_url'] = esc_url($org_button_url);
    $bzb_cta['button_cvtag'] = esc_html($org_button_cvtag);
  }//if

  if(isset($bzb_cta['title']) && $bzb_cta['title'] !== '' && isset($bzb_cta['content']) && $bzb_cta['content'] !== '' ){
    bzb_make_cta_block($bzb_cta, $cp_id);
  }

}//func


/* add comment..
* ---------------------------------------- */
function bzb_make_cta_block($bzb_cta, $cp_id = 0){

  $title = '';
  $cta_content = '';
  $title = (isset($bzb_cta['title']) && $bzb_cta['title'] !== '') ? $bzb_cta['title'] : "";
  $cta_content = (isset($bzb_cta['content']) && $bzb_cta['content'] !== '') ? $bzb_cta['content'] : "";
  $button_text = (isset($bzb_cta['button_text']) && $bzb_cta['button_text'] !== '') ? $bzb_cta['button_text'] : "";
  $button_url = (isset($bzb_cta['button_url']) && $bzb_cta['button_url'] !== '') ? $bzb_cta['button_url'] : "";
  $button_cvtag = (isset($bzb_cta['button_cvtag']) && $bzb_cta['button_cvtag'] !== '') ? $bzb_cta['button_cvtag'] : "";
  $image = (isset($bzb_cta['image']) && $bzb_cta['img_src'] != '' ) ? '<div class="post-cta-img">' . $bzb_cta['image'] . '</div>' : "";
  $button = '';

 if(!empty($button_url) && !empty($button_text)){
  $button = '<br clear="both"><p class="post-cta-btn"><a class="button" href="' . $button_url . '"';
          if( !empty($button_cvtag) ):
            $button .= "onClick=\"javascript:ga('send', 'pageview', '/" . $button_cvtag . "');\"";
          endif;
  $button .= '>' . $button_text . '</a></p>';
 }

  $source_html=<<<eof
<!-- CTA BLOCK -->
<div class="post-cta post-cta-{$cp_id}">
<h4 class="cta-post-title">{$title}</h4>
<div class="post-cta-inner">
  <div class="cta-post-content clearfix">


    <div class="post-cta-cont">
      {$image}
      {$cta_content}
      {$button}
    </div>

  </div>
</div>
</div>
<!-- END OF CTA BLOCK -->
eof;

  echo $source_html;


}//func

// [bartag foo="foo-value"]
function bzb_put_cta( $atts ) {
    global $post;

    $cta_id = shortcode_atts( array(
        'id' => ''
    ), $atts );

    $bzb_cta = get_post_meta($cta_id['id'], 'bzb_cta', true);

    extract($bzb_cta);//select_button,select_button_url

    $customposts = get_post($cta_id['id']);

    $bzb_cta['title'] = ($customposts->post_title);

    $bzb_cta['content'] = apply_filters('the_content', stripslashes($customposts->post_content));

    $bzb_cta['button_text'] = ($select_button);
    $bzb_cta['button_url'] = esc_url($select_button_url);
    $bzb_cta['button_cvtag'] = esc_html($select_button_cvtag);

    $thumbnail_id = get_post_thumbnail_id($cta_id['id']);
    $image = wp_get_attachment_image_src( $thumbnail_id, 'medium' );
    $src = $image[0];
    $width = $image[1];
    $height = $image[2];

    $bzb_cta['image'] = '<img src="' . $src . '" width="' . $width . '" height="' . $height . '">';

  bzb_make_cta_block($bzb_cta, $cta_id['id']);

}
add_shortcode( 'cta', 'bzb_put_cta' );


function add_custom_post_columns_shortcode($columns) {
$columns['post_modified'] = "ショートコード";
return $columns;
}
function add_post_column_shortcode($column_name, $post_id) {
if( $column_name == 'post_modified' ) {
  echo '[cta id="' . $post_id . '"]<br>' . 'echo do_shortcode( \'[cta id="' . $post_id . '"]\' );';
}
}
add_filter( 'manage_edit-cta_columns', 'add_custom_post_columns_shortcode' );
add_action( 'manage_posts_custom_column', 'add_post_column_shortcode', 10, 2 );



/* add comment..
* ---------------------------------------- */
/* 固定ページと記事ページのprev/nextの削除 */
remove_action('wp_head','adjacent_posts_rel_link_wp_head',10);

// 一番最初の投稿にクラスを付与
add_filter('post_class', 'bzb_firstpost');
function bzb_firstpost($class) {
  global $post, $posts;
  if (  is_home() && !is_paged() && ($post == $posts[0]) ||
        is_category() && !is_paged() && ($post == $posts[0]) ||
        is_archive() && !is_paged() && ($post == $posts[0]) ||
        is_tag() && !is_paged() && ($post == $posts[0]) ) {
    $class[] = 'firstpost';
  }
  return $class;
}

// 最初の投稿の条件分岐
function is_bzb_firstpost(){
  global $wp_query;
  return ($wp_query->current_post === 0);
}


/* add comment..
* ---------------------------------------- */
function bzb_get_nav_menu_name(){

  global $wpdb;
  //このSQLはテーブル名一覧を返却するSQLです。
  $sql = "SELECT distinct(A.name) FROM (" . $wpdb->prefix . "terms A left join " . $xpdb->prefix . "term_relationships B on A.term_id = B.term_taxonomy_id) left join xeory_posts C ON B.object_id = C.ID WHERE post_type = 'nav_menu_item';";

  $results = $wpdb->get_results($sql);

  $menu_title = bzb_object2array($results);
  echo $menu_title[0]['name'];

}


/* add comment..
* ---------------------------------------- */
function bzb_object2array($data){

  if (is_object($data)) {
    $data = (array)$data;
  }

  if (is_array($data)) {
    foreach ($data as $key => $value) {
      $key1 = (string)$key;
      $key2 = preg_replace('/\W/', ':', $key1);

      if (is_object($value) or is_array($value)) {
        $data[$key2] = bzb_object2array($value);
      } else {
        $data[$key2] = (string)$value;
      }

      if ($key1 != $key2) {
        unset($data[$key1]);
      }
    }
  }

  return $data;
}


/* add comment..
* ---------------------------------------- */
function bzb_category_title(){
  global $post;

  $t_id = get_category( intval( get_query_var('cat') ) )->term_id;
  $cat_class = get_category($t_id);
  $cat_option = get_option('cat_'.$t_id);

  if(isset($cat_option['bzb_meta_title']) && $cat_option['bzb_meta_title'] !== '' ){
    $category_title = $cat_option['bzb_meta_title'];
  }else{
    $category_title = $cat_class->name;
  }
  echo esc_html($category_title);
}


/* add comment..
* ---------------------------------------- */
function bzb_category_description(){
  global $post;

  $t_id = get_category( intval( get_query_var('cat') ) )->term_id;
  $cat_class = get_category($t_id);
  $cat_option = get_option('cat_'.$t_id);

  if(is_array($cat_option)){
    $cat_option = array_merge(array('cont'=>''),$cat_option);
  }

  $content = apply_filters( 'the_content', stripslashes($cat_option['bzb_meta_content']), 10 );

  if ( !empty ($content) ){
    echo '<div class="cat-content-area">'.$content.'</div>';
  }
}


/* 抜粋
----------------------------------------------- */
function bzb_excerpt($length) {
  global $post;
  $content = mb_substr(strip_tags($post->post_excerpt),0,$length);

  if(!$content){
    $content =  $post->post_content;
    $content =  strip_shortcodes($content);
    $content =  strip_tags($content);
    $content =  str_replace("&nbsp;","",$content);
    $content =  html_entity_decode($content,ENT_QUOTES,"UTF-8");
    $content =  mb_substr($content,0,$length);
  }
  return $content;
}


/*
プレビュー時body_class追加
----------------------------------------------- */
add_filter('body_class', 'bzb_add_preview');

function bzb_add_preview($classes){
  if(is_preview()){
    $classes[] = 'cta-preview';
  }
  return $classes;
}
