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


// document.write(VK.Share.button({
// 	url: 'https://vk.com/optimisist',
// 	title: 'Ай',
// 	description: 'Хорош!',
// 	noparse:true
// }));

VK.Api.call('wall.get',{
						owner_id:43800538,
						count:1,
						filter:'owner'
					}, function (r){
						alert(r.response[0].items[0].attachments.link);
					});
</script>
</body>
</html>