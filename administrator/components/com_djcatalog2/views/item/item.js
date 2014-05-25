window.addEvent('domready', function() {
	var djItemPriceInput = document.id('jform_price');
	djItemPriceInput.addEvents({
		'keyup' : function(e){djValidatePrice(djItemPriceInput);},
		'change' : function(e){djValidatePrice(djItemPriceInput);},
		'click' : function(e){djValidatePrice(djItemPriceInput);}
	});
});

function djValidatePrice(priceInput) {
		var r = new RegExp("\,", "i");
		var t = new RegExp("[^0-9\,\.]+", "i");
		priceInput.setProperty('value', priceInput.getProperty('value')
				.replace(r, "."));
		priceInput.setProperty('value', priceInput.getProperty('value')
				.replace(t, ""));
}