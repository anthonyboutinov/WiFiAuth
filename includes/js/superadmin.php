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
			
			var i = $(this).find("i");
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
                },
                error: function(){
					$(i).removeAttr('class').addClass('fa fa-fw fa-times').parent().attr('title', 'Ошибка');
					addNotification('Ошибка при выполнении запроса', 'danger');
					fixVarTableClicked = false;
                }
            }); 
		});
	});
<?php } ?>
</script>