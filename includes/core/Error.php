<?php
	/// Позволяет выводить ошибки
	class Error {

		const PORTAL_NAME = "Re[Spot]";

		/// Вывести сообщение об ошибке по общему шаблону ошибки
		/**
		 * @author Anthony Boutinov
 		 * @access protected
		 * @static
		 * @param mixed $title			Заголовок
		 * @param mixed $description	Подробное описание
		 * @return void
		 */
		protected static function generalErrorTemplate($title, $description) {

			?><!DOCTYPE html>
			<html lang="ru">
				<head>
					<?php include 'includes/base/headBootstrapAndBasics.php'; ?>
					<title><?=$title;?> — <?=Error::PORTAL_NAME;?></title>
				</head>
				<body class="admin-page-login"><div class="background-cover"></div>
					<div class="container absolute-center-center text-center">
						<h1><?=$title;?></h1>
						<div class="page-wrapper">
							<?=$description;?>
						</div>
						<?php include 'includes/base/footer.php'; ?>
					</div>
				</body>
			</html>
			<?php die();

		}


		/// Вывести ошибку соединения с базой данных
		/**
		 * @author Anthony Boutinov
		 * @access public
		 * @static
		 * @return void
		 */
		public static function dbConnectionFailure() {
			$title = 'Ошибка соединения с базой данных';
			ob_start(); ?>
				<p>Произошла ошибка при подключении к&nbsp;базе данных.</p>
				<p>Вернитесь назад и&nbsp;попробуйте выполнить запрос еще&nbsp;раз.</p>
				<p>В&nbsp;случае, если ошибка повторяется, обратитесь к&nbsp;администратору</a>.</p>
			<?php
			Error::generalErrorTemplate($title, ob_get_clean());
		}

		/// Фатальная ошибка
		/**
		 * @author Anthony Boutinov
		 * @access public
		 * @static
		 * @param mixed $error
		 * @param mixed $description (default: null)
		 * @return void
		 */
		public static function fatalError($error, $description = null) {

			if ($description) {
				Error::generalErrorTemplate($error, $description);
			} else {

				?><!DOCTYPE html>
				<html lang="ru">
					<head>
						<title>Ошибка — Re[Spot]</title>
					</head>
					<body style="-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;">
						<p style="font-family: 'Lucida Console', Monaco, monospace; text-align: center; vertical-align: middle; padding-top: 10%; color: #4b4b4b">
							/*&nbsp; <?=$error;?> &nbsp;*/
						</p>
					</body>
				</html>
				<?php die();

			}

		}

	}
?>