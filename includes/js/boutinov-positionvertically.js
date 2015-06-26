(function ( $ ) {
	
	$.fn.positionVertically(options) {
		
		var settings = $.extend({
            // These are the defaults.
            color: "#556b2f",
            backgroundColor: "white"
        }, options );
		
		function _positionVertically(arr) {
			$.each(arr, function(i, e) {
				
				if ($(e).outerHeight() < $(window).height()) {
					$(e).css('margin-top', ($(window).height() - $(e).outerHeight()) / 2);
					$(footer).css('position', 'fixed');
				} else {
					$(e).css('margin-top', 0);
					$(footer).css('position', 'inherit');
				}
				
				if (postAction)
				
				if (formIsOpen) {
					$(loginInputPasswordForm).css('margin-top', ($(window).height() - $(loginInputPasswordForm).outerHeight()) / 2);
				}
				
			});
		}
		
		_positionVertically(this);
		$(window).resize(_positionVertically(this));
		
		return this;
	}
	
		
}( jQuery ));