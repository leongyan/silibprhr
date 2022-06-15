$(document).ready(function(){
	$('form').submit(function(){
		var msg = $(this).attr('data-msg');
		if(msg){
			return confirm(msg);
		}
	});
});