$( function(){
	
	//init
	$('form.card').card({
		container: '.card-wrapper',
		formSelectors: {
	        numberInput: 'input#cc_number',
	        expiryInput: 'input#cc_exp',
	        cvcInput: 'input#cc_cvc',
	        nameInput: 'input#cc_name'
	    },
		placeholders: {
	        number: '•••• •••• •••• ••••',
	        name: 'NOME COMPLETO',
	        expiry: '••/••',
	        cvc: '•••'
	    },
	    messages: {
	        validDate: 'DATA DE\nVALIDADE',
	        monthYear: 'MÊS / ANO'
	    }
	});
	
	//diff input expiry
	$('select#cc_exp_month, select#cc_exp_year').on('change', function () {
		
	    $('#cc_exp').val($('select#cc_exp_month').val() + '/' + $('select#cc_exp_year').val());
	    var evt = document.createEvent('HTMLEvents');
	    evt.initEvent('change', false, true);
	    document.getElementById('cc_exp').dispatchEvent(evt);
	    
	});
	
	//styles
	$('.jp-card-front').css('background', '#999');
	$('.jp-card-container').css('margin', '0 0 20px 0');
	
});