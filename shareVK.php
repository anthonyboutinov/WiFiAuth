<!DOCTYPE html>
<html lang="ru">
<head>
	<title></title>
	<script src="//vk.com/js/api/openapi.js"></script>
	<script type="text/javascript" src="https://vk.com/js/api/share.js?90" charset="windows-1251"></script>
	<script type="text/javascript" src="https://vk.com/js/api/share.js?84" charset="windows-1251"></script>
</head>
<body>
<script type="text/javascript">
  VK.init(
  	{apiId: 4933055}
  	);
</script>
<script type="text/javascript">

VK.Api.call('wall.get',{
						count:1,
						filter:'owner'
					}, function (r){
						console.log(r);
						alert(r.response[1].attachment.link.url);
					});
</script>
</body>
</html>