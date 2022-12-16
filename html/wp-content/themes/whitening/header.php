<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php wp_head(); ?>
</head>
<body class="drawer drawer--right">
	<div class="c-header u-shadow">
		<div class="c-header__container">
			<a href="/" class="c-header__logo">
				<div class="c-header__logo-wrapper">
					<img src="<?php bloginfo('template_url'); ?>/images/logo@2x.png" alt="ロゴ" class="c-header__img">
				</div>
			</a>
			<div class="c-header__info">
				<div class="c-header__feature">
					<div class="c-header__text-area">
						<a href="tel:03-4531-2559" class="c-header__tel">tel.<span class="c-header__tel --accent">03-4531-2559</span></a>
						<p class="c-header__businessday">月曜日～土曜日　10時～22時(最終受付21時) <br>日曜日　10時～19時(最終受付18時) </p>
					</div>
					<div class="c-header__icon">
						<a href="#" class="c-header__icon-wrapper">
							<img src="<?php bloginfo('template_url'); ?>/images/LINE_icon@2x.png" alt="LINE" class="c-header__img">
						</a>
						<a href="#" class="c-header__icon-wrapper">
							<img src="<?php bloginfo('template_url'); ?>/images/instagram_icon@2x.png" alt="インスタグラム" class="c-header__img">
						</a>
						<p class="c-header__icon-text">ご予約は<br>コチラ</p>
						<div class="c-header__icon-wrapper--arrow">
							<img src="<?php bloginfo('template_url'); ?>/images/arrow@2x.png" alt="矢印" class="c-header__img">
						</div>
						<a href="#" class="c-header__icon-wrapper">
							<img src="<?php bloginfo('template_url'); ?>/images/hotpepper@2x.png" alt="ホットペッパー" class="c-header__img">
						</a>
					</div>
				</div>
				<div class="c-header__navi">
					<ul class="c-header__navi-list">
						<li class="c-header__navi-item"><a href="#home">Home</a></li>
						<li class="c-header__navi-item"><a href="#reason">Reason</a></li>
						<li class="c-header__navi-item"><a href="#price">System</a></li>
						<li class="c-header__navi-item"><a href="#guide">Guide</a></li>
					</ul>
				</div>
				<div class="drawer drawer--right drawer-color">
					<button type="button" class="drawer-toggle drawer-hamburger drawer">
						<span class="drawer-hamburger__bar"></span>
						<span class="drawer-hamburger__bar"></span>
						<span class="drawer-hamburger__bar"></span>
					</button>
				</div>
			</div>
		</div>
	</div>
	<nav class="drawer-nav" role="navigation">
		<ul class="drawer-nav__menu drawer-menu">
			<li class="drawer-menu-list"><a href="#home">Home</a></li>
			<li class="drawer-menu-list"><a href="#reason">Reason</a></li>
			<li class="drawer-menu-list"><a href="#price">Syste</a></li>
			<li class="drawer-menu-list"><a href="#guide">Guide</a></li>
			<p class="drawer-menu-tel">tel.<span class="drawer-menu-tel --accent">03-4531-2559</span></p>
			<p class="drawer-menu-worktime">月曜日～土曜日　10時～22時(最終受付21時) <br>日曜日　10時～19時(最終受付18時) </p>
			<div class="c-header__icon drawer-menue-icon">
				<a href="#" class="c-header__icon-wrapper">
					<img src="<?php bloginfo('template_url'); ?>/images/LINE_icon@2x.png" alt="LINE" class="c-header__img">
				</a>
				<a href="#" class="c-header__icon-wrapper">
					<img src="<?php bloginfo('template_url'); ?>/images/instagram_icon@2x.png" alt="インスタグラム" class="c-header__img">
				</a>
				<p class="c-header__icon-text">ご予約は<br>コチラ</p>
				<div class="c-header__icon-wrapper--arrow">
					<img src="<?php bloginfo('template_url'); ?>/images/arrow@2x.png" alt="矢印" class="c-header__img">
				</div>
				<a href="#" class="c-header__icon-wrapper">
					<img src="<?php bloginfo('template_url'); ?>/images/hotpepper@2x.png" alt="ホットペッパー" class="c-header__img">
				</a>
			</div>
		</ul>
	</nav>
</body>
