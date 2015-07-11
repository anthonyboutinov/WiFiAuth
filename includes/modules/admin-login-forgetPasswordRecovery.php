<h1>Восстановление пароля</h1>
<div class="form">
	<form method="post">
		<input type="hidden" name="form-name" value="forget-password-recovery">

		<div class="form-group">
			<label for="login">Логин или email</label>
			<input type="text" class="form-control position-relative" id="login" name="login" placeholder="Логин или email" value="<?=$_POST['login'];?>">
		</div>
		
		<p>Вам будет выслано письмо на ваш email со ссылкой на страницу, на которой вы сможете задать новый пароль.</p>

		<button type="submit" class="btn btn-lg btn-black gradient">Продолжить <i class="fa fa-chevron-right"></i></button>

	</form>
</div>
<script>
/* *
   * Ограничение вводимых данных
 */

 	$("#login").alphanum({
	 	allow: '-_',
	    allowSpace: false,
	    allowNumeric: true,
	    allowOtherCharSets: false
	});
</script>