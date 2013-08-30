/*CUSTOM*/
$(document).ready(function() {

	//Menu
	$(".navigate_btn").toggle(function() {
		 $('.full_nav').fadeToggle(500);
		 $('body,html').animate({
				scrollTop: 0
			}, 300);

		 }, function(){
		 $('.full_nav').fadeToggle(500);	 
	});

	
	
});

