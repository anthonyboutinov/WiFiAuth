/* *
   * Textarea restrictions
 */
 
function update_textarea_word_count(txt, word_count) {
	var maxLen = 200;
	
	var len = txt.val().length;
	if (len > maxLen) {
		$(word_count).addClass("bg-danger");
		$(submitButtons).attr('disabled', 'disabled');
	} else {
		$(word_count).removeClass("bg-danger");
		$("#admin-settings-form button[type=\"submit\"]").removeAttr('disabled');
	}
	$(word_count).html(maxLen - len);
}

$(document).ready(function() {
	
/* *
   * Numeric
 */
	
	$("input[type=\"number\"]").numeric({ decimal: false, negative: false }, function() {this.value = "1"; this.focus(); });
	
/* *
   * Remove Success or Failure icons
 */
	
	setTimeout(function(){
		$("i.fa[class^=\"text\"]").remove();
	}, 8000);

/* *
   * All submit buttons restrictions and view changes
 */
	
	var allSubmitButtons = $("button[type=\"submit\"]");
	
	$(allSubmitButtons).click(function(e) {
		
		var fileExtensionError = false;
		$('input[data-type="file-name"]').each(function() {
			if ($(this).val() != '') {
				var ext = $(this).val().split('.').pop().toLowerCase();
				if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {
				    fileExtensionError = true;
				}
			}
		});
		
		if (fileExtensionError == true) {
			addNotification('Загружаемый файл должен быть изображением (.png, .jpg, .jpeg, .gif)!', 'danger');
			e.preventDefault();
		} else {
			
			$(this).html("Сохраняется... <i class=\"fa fa-spinner fa-pulse\"></i>");
			var a = $(this);
			setTimeout(function() {
				$(a).attr('disabled', 'disabled');
			}, 100);
				
		}
	});
	
/*
	$("#admin-settings-form").bind(function(){
		
		$('input[type="file"]').each(function() {
			var ext = $(this).val().split('.').pop().toLowerCase();
			if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {
			    alert('invalid extension!');
			}
		});
		
	});
*/
	
	
/* *
   * Password submit
 */
	
	$("#password-submit").click(function(e) {
		e.preventDefault();
		
		// обработать форму
		if ($("#old-password").val().length == 0 || $("#password").val().length == 0 || $("#repeat-password").val().length == 0) {
			addNotification('Не все поля заполнены!', 'warning');
			return;
		} else if ($("#password").val().length < 8 || $("#repeat-password").val().length < 8) {
			addNotification('Длина нового пароля должа быть не меньше 8 символов!', 'warning');
			return;
		}

		// отправить форму
		$(this).html("Сохраняется... <i class=\"fa fa-spinner fa-pulse\"></i>").attr('disabled', 'disabled');
		var _this = $(this);
		$.ajax({
			type: "POST",
			url: "admin-settings.php",
			data: {
				'form-name': 'admin-password',
				'old-password': $("#old-password").val(),
				'password': $("#password").val(),
				'repeat-password': $("#repeat-password").val()
				},
			success: function(msg) {
				if (msg.lastIndexOf('danger:', 0) === 0) {
					addNotification(msg.substr('danger:'.length), 'danger');
					$(_this).html("Сохранить <i class=\"fa fa-floppy-o\">").removeAttr('disabled');
				} else {
					addNotification(msg, 'success');
					$("#old-password").val('');
					$("#password").val('');
					$("#repeat-password").val('');
					$(_this).html("Готово <i class=\"fa fa-check\"></i>").removeAttr('disabled');
					setTimeout(function() {$(_this).html("Сохранить <i class=\"fa fa-floppy-o\">");}, 5000);
				}
  				
			},
			fail: function() {
				addNotification('Произошла ошибка при отправке запроса', 'danger');
				$(_this).html("Сохранить <i class=\"fa fa-floppy-o\">").removeAttr('disabled');
			}
		});
		
	});
	
	var history_box_is_open = false;
	$("[data-id-var]").click(function(e) {
		e.preventDefault();
		var _this = $(this);
		if (history_box_is_open === false) {
			history_box_is_open = true;
			$.ajax({
				type: "GET",
				url: "includes/modules/history.php",
				data: {
					'ID_VAR': $(this).attr("data-id-var"),
					'presentation': 'none'
				},
				success: function(msg) {
					$(_this).attr('title', 'История значений<span class="pull-right"><small>Изменения сохраняются автоматически</small></span>').attr('data-container', 'body').attr('data-toggle', 'popover').attr('data-placement', 'top').attr('data-content', msg).popover({
						'html': true,
						'trigger': 'click'
					}).popover('show').on('hidden.bs.popover', function() {
						history_box_is_open = false;
					});
				},
				fail: failNotification
			});
		}
	});
	
	
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
	
	
/* *
   * Ограничение вводимых данных
 */

 	$("input[type='email']").alphanum({
	 	allow: '-_@.',
	    allowSpace: false,
	    allowNumeric: true,
	    allowOtherCharSets: false
	});
		
	
	
});