<?php

// ファンクション
require_once('lib/admin/init.php');
require_once('lib/admin/manual.php');

require_once('lib/functions/asset.php');
require_once('lib/functions/head.php');
require_once('lib/functions/custom-post.php');
require_once('lib/functions/bzb-functions.php');
require_once('lib/functions/setting.php');
require_once('lib/functions/custom-fields.php');
require_once('lib/functions/category-custom-fields.php');
require_once('lib/functions/widget.php');
//require_once('lib/functions/lang.php');
require_once('lib/functions/postviews.php');
// require_once('lib/functions/json-ld.php');
require_once('lib/functions/social_btn.php');
require_once('lib/functions/show_avatar.php');
require_once('lib/functions/shortcode.php');
require_once('lib/functions/rss.php');


//　head内（ヘッダー）から不要なコード削除
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'index_rel_link' );
remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0 );
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'feed_links_extra', 3);

//head内（ヘッダー）絵文字削除 
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('admin_print_scripts', 'print_emoji_detection_script');
remove_action('wp_print_styles', 'print_emoji_styles' );
remove_action('admin_print_styles', 'print_emoji_styles');

//head内（ヘッダー）Embed系の記述削除 
remove_action('wp_head','rest_output_link_wp_head');
remove_action('wp_head','wp_oembed_add_discovery_links');
remove_action('wp_head','wp_oembed_add_host_js');
remove_action('template_redirect', 'rest_output_link_header', 11 );

//無駄なソースを吐き出さずにアイキャッチ画像を表示する方法
add_filter('wp_calculate_image_srcset_meta', '__return_null');

//サムネイル画像を正方形に
add_image_size( 'thumb-square', 600, 1000, true );


// 固定ページの画像へのショートカット
function replaceImagePath($arg) {
	$content = str_replace('"img/', '"' . get_bloginfo('stylesheet_directory') . '/img/', $arg);
	return $content;
}  
add_action('the_content', 'replaceImagePath');

//管理画面に優先度メニューを追加
function add_page_to_admin_menu() {
  add_menu_page( 'スケジュール管理', 'スケジュール管理', 'manage_options', 'https://zagin-whitening.com/admin_scheduler/', '', 'dashicons-calendar-alt', 2);
  add_menu_page( 'プロフィール作成', 'プロフィール登録', 'manage_options', './post-new.php', '', 'dashicons-heart', 2);
  add_menu_page( 'プロフィール編集', 'プロフィール一覧', 'manage_options', './edit.php', '', 'dashicons-heart', 2);
  add_menu_page( 'カレンダーに登録', 'カレンダーに登録', 'manage_options', './user-new.php', '', 'dashicons-edit', 3);
  add_menu_page( 'カレンダー一覧', 'カレンダー一覧', 'manage_options', './users.php', '', 'dashicons-edit', 3);

}
add_action( 'admin_menu', 'add_page_to_admin_menu' );


/**
 * 管理画面に追加のスタイルを追加する例です。
 * 例は管理画面のカテゴリーの段組を解除する追加スタイルです。
 */
function custom_admin_style() {
	?><style>
		/* カテゴリーの2段組を解除 */

.dashicons-heart:before{color:#c00 !important;}
.dashicons-calendar-alt:before{color:#ffea00 !important;}
.dashicons-edit:before{color:#db1449 !important;}
	</style><?php
}
add_action( 'admin_head', 'custom_admin_style' );



//管理画面のメニュー削除
function remove_menus() {
 
	//remove_menu_page('index.php');					// ダッシュボード
	//remove_menu_page('edit.php');					// 投稿
	//remove_menu_page('upload.php');					// メディア
	//remove_menu_page('edit.php?post_type=page');	// 固定ページ
	remove_menu_page('edit-comments.php');			// コメント
	//remove_menu_page('themes.php');					// 外観
	//remove_menu_page('plugins.php');				// プラグイン
	//remove_menu_page('users.php');					// ユーザー
	//remove_menu_page('tools.php');					// ツール
	//remove_menu_page('options-general.php');		// 設定
 
}
add_action('admin_menu', 'remove_menus');

function my_admin_style() {
  echo '<style>
  #menu-posts-cta, #menu-posts-lp, #toplevel_page_bzb_option_start{
	  display:none;
	}
  </style>'.PHP_EOL;
}
add_action('admin_print_styles', 'my_admin_style');


//管理画面のメニュー名前変える

function edit_admin_menus() {
  global $menu;
//  $menu[5][0] = '';    // 投稿
  $menu[20][0]  = 'ページ';    // 固定ページ
  $menu[10][0]  = '画像管理';    // 固定ページ
}
add_action( 'admin_menu', 'edit_admin_menus' );





//カスタムメニューの位置をfunctions.phpに定義する

if ( ! function_exists( 'lab_setup' ) ) :

function lab_setup() {
	
	register_nav_menus( array(
		//'global' => 'グローバルナビ',
		//'header' => 'ヘッダーナビ',
		//'footer' => 'フッターナビ',
		'footer2' => 'フッター２ーナビ',
	) );
	
}
endif;
add_action( 'after_setup_theme', 'lab_setup' );



//管理画面のヘッダーにメニュー
add_action( 'wp_before_admin_bar_render', 'add_custom_page_edit_in_admin_bar' );
function add_custom_page_edit_in_admin_bar() {
  global $wp_admin_bar;
  $wp_admin_bar->add_menu(array(
    'id'    => 'custom-page-edit',
    'title' => '♥セラピスト管理'
  ));
  $wp_admin_bar->add_menu(array(
    'parent' => 'custom-page-edit',
    'id'     => 'custom-page-edit-sub01',
    'title'  => 'flow1 プロフィール作成',
    'href'   => 'post-new.php'
  ));
  $wp_admin_bar->add_menu(array(
    'parent' => 'custom-page-edit',
    'id'     => 'custom-page-edit-sub02',
    'title'  => 'flow2 新規ID登録',
    'href'   => 'user-new.php'
  ));
  $wp_admin_bar->add_menu(array(
    'parent' => 'custom-page-edit',
    'id'     => 'custom-page-edit-sub03',
    'title'  => 'flow3 表示順並べ替え（出勤）',
    'href'   => 'users.php?role=subscriber'
  ));
  $wp_admin_bar->add_menu(array(
    'parent' => 'custom-page-edit',
    'id'     => 'custom-page-edit-sub04',
    'title'  => '削除1／プロフィール',
    'href'   => 'edit.php?category_name=therapist'
  ));
  $wp_admin_bar->add_menu(array(
    'parent' => 'custom-page-edit',
    'id'     => 'custom-page-edit-sub05',
    'title'  => '削除2／新規ID',
    'href'   => 'users.php?role=subscriber'
  ));

  global $wp_admin_bar;
  $wp_admin_bar->add_menu(array(
    'id'    => 'custom-page-edit2',
    'title' => '★出勤管理'
  ));
  $wp_admin_bar->add_menu(array(
    'parent' => 'custom-page-edit2',
    'id'     => 'custom-page-edit2-sub01',
    'title'  => 'スケジュール',
    'href'   => 'https://zagin-whitening.com/admin_scheduler/',
  ));
}




//権限名を変える
function change_role_name() {
  global $wp_roles;
  if ( !isset($wp_roles) )
    $wp_roles = new WP_Roles();

    // 「購読者」を「こうどくしゃ」に変更
    $wp_roles -> roles ['subscriber']['name'] = 'セラピスト';
    $wp_roles -> role_names ['subscriber'] = 'セラピスト';

    // 「編集者」を「へんしゅうしゃ」に変更
    $wp_roles -> roles ['editor']['name'] = 'スタッフ';
    $wp_roles -> role_names ['editor'] = 'スタッフ';

    // 「管理者」を「かんりしゃ」に変更
    $wp_roles -> roles ['administrator']['name'] = 'システム管理者';
    $wp_roles -> role_names ['administrator'] = 'システム管理者';
}
add_action ( 'init', 'change_role_name' );


//カスタムフィールドを表示させるショートコード
function inpostCf() {
    $field = get_post_meta(get_the_ID(), 'therapist-new', true);
    return $field;
}
add_shortcode('viewCf', 'inpostCf');


//スマートフォンで表示を変える
function is_mobile() {
  $useragents = array(
    'iPhone',          // iPhone
    'iPod',            // iPod touch
    'Android',         // 1.5+ Android
    'dream',           // Pre 1.5 Android
    'CUPCAKE',         // 1.5+ Android
    'blackberry9500',  // Storm
    'blackberry9530',  // Storm
    'blackberry9520',  // Storm v2
    'blackberry9550',  // Storm v2
    'blackberry9800',  // Torch
    'webOS',           // Palm Pre Experimental
    'incognito',       // Other iPhone browser
    'webmate'          // Other iPhone browser
  );
  $pattern = '/'.implode('|', $useragents).'/i';
  return preg_match($pattern, $_SERVER['HTTP_USER_AGENT']);
}