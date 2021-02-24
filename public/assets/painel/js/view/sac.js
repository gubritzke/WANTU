function scrollToBottom()
{
	if( $("#messages").length > 0 )
	{
		var top = $("#messages")[0].scrollHeight + 300;
		$("#messages").scrollTop(top);
	}
}

$( function(){
	
	scrollToBottom();
	
});