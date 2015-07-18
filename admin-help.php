<?php include 'includes/base/admin.php'; ?><!DOCTYPE html>
<html lang="ru">
	<head>
		<?php include 'includes/base/headBootstrapAndBasics.php'; ?>
		<title>Название страницы <?php echo $adminPanelTitle; ?></title>
	</head>
	<body class="admin-page simple-page"><div class="background-cover"></div>
		<div class="container glass-panel">
			<?php include 'includes/base/navbar.php'; ?>

			<h1 class="huge-cover"><i class="fa fa-fw fa-support"></i> Помощь</h1>
			
			<div class="row">
				<div class="col-md-3 col-md-push-9 hidden-sm hidden-xs">
					<ul class="list-unstyled" role="complementary" data-spy="affix" data-offset-top="208" data-offset-bottom="200" id="affix-menu">
						<li><a href="#section-1">Главная страница</a></li>
						<li><a href="#section-2">Постоянные<br>и недавние пользователи</a></li>
						<li><a href="#section-3">Дни рождения</a></li>
						<li><a href="#section-4">Настройки</a></li>
					</ul>
				</div>
				<div class="col-md-9 col-md-pull-3" role="main">

					<div class="page-wrapper help">
						<?php $i = 1;
							function helpImage($i, $description) { ?>
								<div class="help-image">
									<img src="images/help/image<?=$i;?>.jpg" id="img-<?=$i;?>">
									<label for="img-<?=$i;?>" class="image-label">Рис. <?=$i;?>. <?=$description;?></label>
								</div><?php
							}
						?>
						
						<a name="section-1"><h2>Главная страница</h2></a>
						<p>На <a href="admin-dashboard.php">главной странице</a> личного кабинета владельца выводится общая статистика посещений, а&nbsp;также краткая информация о&nbsp;постоянных пользователях интернета, днях рождениях пользователей и&nbsp;недавних пользователях. График показывает динамику авторизаций через&nbsp;социальные сети.</p>
						<?php helpImage($i++, '<a href="admin-dashboard.php">Главная страница</a> личного кабинета Re[Spot]'); ?>
						<p>На&nbsp;графике авторизаций в&nbsp;сети имеется выпадающий список (на рисунке <?=$i;?> выделен красным цветом). С&nbsp;его помощью можно менять период выборки данных: 2&nbsp;недели, 1&nbsp;месяц, 3&nbsp;месяца, 6&nbsp;месяцев, 1&nbsp;год.</p>
						<?php helpImage($i++, 'Выпадающий список задания периода выборки данных на&nbsp;графике'); ?>
						<p>Кнопка «Подробнее», выводит полную информацию о&nbsp;данной таблице. При нажатии на&nbsp;имя пользователя можно перейти на&nbsp;его страницу в&nbsp;социальной сети либо совершить звонок по&nbsp;номеру телефона (средствами операционной системы или&nbsp;сторонних приложений). Кроме этого, в&nbsp;таблице <a href="admin-birthdays-list.php">«Дни рождения»</a> при&nbsp;наведении на&nbsp;данные выводится информация о&nbsp;том, сколько осталось дней до&nbsp;дня рождения пользователя. Если нажать на&nbsp;шапку таблицы, то&nbsp;появятся настройки таблицы.</p>
						<?php helpImage($i++, 'Продолжение главной страницы'); ?>

						<a name="section-2"><h2>Постоянные и&nbsp;недавние пользователи</h2></a>
						<p>Пункт меню, выделенный красным цветом,— <a href="admin-users-combined-list.php">«Постоянные и&nbsp;недавние пользователи»</a> — переводит на&nbsp;страницу «Постоянные и&nbsp;недавние пользователи». Здесь отображаются полный список посещаемости и&nbsp;полный список постоянных пользователей заведения. В&nbsp;мобильной версии сайта эти две таблицы отображаются как разные пункты меню.</p>
						<?php helpImage($i++, 'Пункт меню «Постоянные и&nbsp;недавние пользователи»'); ?>
						<?php helpImage($i++, 'Страница «Постоянные и&nbsp;недавние пользователи»'); ?>

						<a name="section-3"><h2>Дни рождения</h2></a>
						<p>Пункт меню, выделенный красным цветом,— <a href="admin-birthdays-list.php">«Дни Рождения»</a> — переводит на&nbsp;страницу «Дни Рождения». Здесь отображается полный список именинников, уже&nbsp;побывавших в&nbsp;заведении, указывает сколько лет исполнится гостю. Данные сортируются по&nbsp;близости дней рождений пользователей.</p>
						<?php helpImage($i++, 'Пункт меню «Дни Рождения»'); ?>
						<p>В представлении «Умная сортировка» записи сортируются в&nbsp;соответствии с&nbsp;уровнем лояльности клиентов и&nbsp;близости их&nbsp;дня рождения. Здесь отображаются только пользователи, чей день рождения наступит не&nbsp;позднее чем&nbsp;через 80&nbsp;дней. Опция умной сортировки становится доступной, когда количество посетителей превысит 5&nbsp;человек.</p>
						<?php helpImage($i++, 'Страница «Дни Рождения»'); ?>
						
						<a name="section-4"><h2>Настройки</h2></a>
						<p>В верхнем правом углу находится кнопка «Еще» — это выпадающее меню, содержащее в&nbsp;себе активные кнопки <a href="admin-settings.php">«Настройки»</a>, «Помощь» и&nbsp;«Выйти».</p>
						<?php helpImage($i++, 'Выпадающее меню «Еще»'); ?>
						
						<p>При нажатии на&nbsp;пункт меню <a href="admin-settings.php">«Настройки»</a> происходит переход на&nbsp;страницу настроек. Страницу удобно просматривать, используя меню навигации (на&nbsp;рисунке <?=$i;?> выделено красным цветом).</p>
						<?php helpImage($i++, 'Страница «Настройки»'); ?>
						<p>При нажатии на&nbsp;<strong>любую</strong> из&nbsp;кнопок <strong>«Сохранить»</strong> сохраняются <strong>все изменения</strong> (кнопка «Сохранить» для&nbsp;смены пароля действует независимо от&nbsp;остальных и&nbsp;позволяет <strong>только</strong> сохранять новый пароль).</p>
						<p>На странице можно изменить заголовок, а&nbsp;также описание поста, который будет публиковаться в&nbsp;социальных сетях. На&nbsp;текст существует ограничение в&nbsp;200&nbsp;символов. Также можно добавить ссылки на&nbsp;сообщество заведения в&nbsp;«ВКонтакте» и&nbsp;«Facebook.</p>
						<?php helpImage($i++, 'Настройки Рекламного Поста'); ?>
						<p>Чуть ниже можно выбрать способы авторизации для&nbsp;пользователя.</p>
						<p>Настройки «Сменить пароль». в&nbsp;первой строке вы вводите свой старый пароль, в&nbsp;следующей вводите новый и&nbsp;в&nbsp;последней строке подтверждаете&nbsp;его.</p>
						<?php helpImage($i++, 'Настройки выбора доступных пользователям способов авторизации и&nbsp;смены пароля'); ?>
						<p>В&nbsp;параметрах отображения данных можно задавать, сколько строк будет отображаться в&nbsp;таблицах на&nbsp;главной странице и на остальных страницах.</p>
						<?php helpImage($i++, 'Настройки внешнего вида портала и&nbsp;настройки отображения данных'); ?>
						
					</div>
			
				</div>
			</div>
			<?php	include 'includes/base/footer.php'; ?>
		</div>
		<?php include 'includes/base/jqueryAndBootstrapScripts.html'; ?>
		<script>
/* *
   * Safari bootstrap column reordering & affix bug
 */
	
	/* Check if we are in safari */
	if (navigator.userAgent.indexOf('Safari') != -1 && navigator.userAgent.indexOf('Chrome') == -1) {
	    var stickywidget = $('#affix-menu');
	    var explicitlySetAffixPosition = function() {
	        stickywidget.css('left',stickywidget.offset().left+'px');
	    };
	    /* Before the element becomes affixed, add left CSS that is equal to the distance of the element from the left of the screen */
	    stickywidget.on('affix.bs.affix',function(){
	        explicitlySetAffixPosition();
	    });
	
	    /* On resize of window, un-affix affixed widget to measure where it should be located, set the left CSS accordingly, re-affix it */
	    $(window).resize(function(){
	        if(stickywidget.hasClass('affix')) {
	            stickywidget.removeClass('affix');
	            explicitlySetAffixPosition();
	            stickywidget.addClass('affix');
	        }
	    });
	}
		</script>
	</body>
</html>