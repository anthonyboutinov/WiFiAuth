<script>
$(document).ready(function() {
	<?php if ($database->meetsAccessLevel('ROOT')) { ?>
	
		var fixVarTableClicked = false;
	
		$("#fix-var-table").click(function(e) {
			e.preventDefault();
			
			if (fixVarTableClicked == true) {return;}
			fixVarTableClicked = true;
			
			var i = $(this).find("i");
			$(i).removeAttr('class').addClass('fa fa-fw fa-pulse fa-spinner');
			
			$.ajax({
                type: "POST",
                url: "superadmin-query.php",
                data:{ 
                    'form-name': 'fix-var-table'
                },
                success: function(msg){
					$(i).removeAttr('class').addClass('fa fa-fw fa-check');
					fixVarTableClicked = false;
                },
                error: function(){
					$(i).removeAttr('class').addClass('fa fa-fw fa-times').parent().attr('title', 'Ошибка');
					fixVarTableClicked = false;
                }
            }); 
		});
	
	<?php } ?>
});
</script>