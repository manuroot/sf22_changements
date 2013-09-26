$(document).ready(function() {
  
$("#dialog").dialog({
	autoOpen: false,
	modal: true,
	width: 400,
	height: 200,
	closeOnEscape: false,
	draggable: true,
	resizable: false,
	buttons: {
		'Oui, Continuer Cette session': function(){
			$(this).dialog('close');
		},
		'Non, se deconnecter': function(){
			// fire whatever the configured onTimeout callback is.
			// using .call(this) keeps the default behavior of "this" being the warning
			// element (the dialog in this case) inside the callback.
			$.idleTimeout.options.onTimeout.call(this);
		}
	}
});

// cache a reference to the countdown element so we don't have to query the DOM for it on each ping.
var $countdown = $("#dialog-countdown");
 var url_check =  Routing.generate('ajax_checkuser');
// start the idle timer plugin
$.idleTimeout('#dialog', 'div.ui-dialog-buttonpane button:first', {
     
        idleAfter: 900,
	pollingInterval: 300,
	keepAliveURL:  url_check,
	serverResponseEquals: 'OK',
	onTimeout: function(){
         var url_login = Routing.generate('fos_user_security_logout');
             window.location.replace(url_login);
		/*window.location = "timeout.htm";*/
	},
	onIdle: function(){
    $(this).removeClass("hidden");
		$(this).dialog("open");
	},
	onCountdown: function(counter){
		$countdown.html(counter); // update the counter
	}
});
  
}); //Eof:: ready