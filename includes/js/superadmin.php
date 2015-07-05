<script>
<?php if ($database->meetsAccessLevel('ROOT')) { ?>
	$(document).ready(function() {
	
		/* *
		   * FIX-VAR-TABLE
		 */
	
		var fixVarTableClicked = false;
	
		$("#fix-var-table").click(function(e) {
			e.preventDefault();
			
			if (fixVarTableClicked == true) {return;}
			fixVarTableClicked = true;
			
			var i = $(this).parent().parent().parent().find("a[data-toggle='dropdown'] > i");
			var i_old_class = $(i)[0].className;
			$(i).removeAttr('class').addClass('fa fa-fw fa-pulse fa-spinner');
			
			$.ajax({
                type: "POST",
                dataType: 'html',
                url: "superadmin-query.php",
                data:{ 
                    'form-name': 'fix-var-table'
                },
                success: function(msg){
					$(i).removeAttr('class').addClass('fa fa-fw fa-check');
					fixVarTableClicked = false;
					if (msg == undefined || msg == '') {
						msg = "Не было внесено никаких изменений.";
					}
					addNotification(msg, 'success');
					setTimeout(function() {$(i)[0].className = i_old_class;}, 5000);
                },
                error: function(){
					$(i).removeAttr('class').addClass('fa fa-fw fa-times').parent().attr('title', 'Ошибка');
					failNotification();
					fixVarTableClicked = false;
					setTimeout(function() {
						$(i)[0].className = i_old_class;
						$(i).removeAttr('title');
					}, 5000);
                }
            }); 
		});
	});
<?php } ?>
</script>