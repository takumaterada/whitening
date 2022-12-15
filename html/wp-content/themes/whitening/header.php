<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php wp_head(); ?>
</head>
<body>
	<div class="c-header">
		<a href="/" class="c-header__logo">
			<div class="c-header__logo-wrapper">
				<img src="<?php bloginfo('template_url'); ?>/images/logo@2x.png" alt="ロゴ" class="c-header__img">
			</div>
		</a>
		<div class="c-header__info">
			<div class="c-header__feature">
				<div class="c-header__text-area">
					<a href="tel:03-4531-2559" class="c-header__tel">tel.<span class="c-header__tel --accent">03-4531-2559</span></a>
					<p class="c-header__businessday">月曜~土曜日 10:00~22:00(日曜定休日)</p>
				</div>
				<div class="c-header__icon">
					<a href="#" class="c-header__icon-wrapper">
						<img src="<?php bloginfo('template_url'); ?>/images/LINE_icon@2x.png" alt="LINE" class="c-header__img">
					</a>
					<a href="#" class="c-header__icon-wrapper">
						<img src="<?php bloginfo('template_url'); ?>/images/hotpepper@2x.png" alt="ホットペッパー" class="c-header__img">
					</a>
					<a href="#" class="c-header__icon-wrapper">
						<img src="<?php bloginfo('template_url'); ?>/images/instagram_icon@2x.png" alt="インスタグラム" class="c-header__img">
					</a>
				</div>
			</div>
			<div class="c-header__navi">
				<ul class="c-header__navi-list">
					<li class="c-header__navi-item"><a href="#">Home</a></li>
					<li class="c-header__navi-item"><a href="#">Reason</a></li>
					<li class="c-header__navi-item"><a href="#">System</a></li>
					<li class="c-header__navi-item"><a href="#">Guide</a></li>
				</ul>
			</div>
		</div>
	</div>
</body>
