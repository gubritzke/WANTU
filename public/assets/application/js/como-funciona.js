function accordionClick(button)
{
	var grp = button.parent(".faq-item");
	var target = grp.find(".boxAccordionContent");
	
	target.slideToggle();
	grp.toggleClass("boxAccordionHide");
	
}

$( function(){
	
	//funÃ§Ã£o ao clicar
	$(document).on('click', '.boxAccordionBt', function(){
		accordionClick($(this));
	});
	
});