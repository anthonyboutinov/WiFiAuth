<html>
<body>

<?php include 'includes/base/jqueryAndBootstrapScripts.html'; ?>
<script>
function quo(min,max){
		return Math.floor(Math.random()*(max-min+1))+min; 
	}
function test() {  //функция входа по паролю
     
		qu = {};
		qu[0] = quo(1,9);
		qu[1] = quo(1,9);
		qu[2] = quo(1,9);
		qu[3] = quo(1,9);
		qu[4] = quo(1,9);
		password = ""+qu[0]+qu[1]+qu[2]+qu[3]+qu[4];
		phone = '79172856297';
		$.ajax({
			type: "POST",
			url: "loginusingpass.php",
			data: {
				'phone': phone,
				'password':password,
			},
			success: function(msg){
			alert('Смс с кодом отправлено на ваш телефон');
			}
		});
	}

test();

</script>
</body>
</html>