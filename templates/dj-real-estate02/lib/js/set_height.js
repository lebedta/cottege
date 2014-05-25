/*--------------------------------------------------------------
# Copyright (C) joomla-monster.com
# License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
# Website: http://www.joomla-monster.com
# Support: info@joomla-monster.com
---------------------------------------------------------------*/

function setFrontBottomModulesHeight() {
	var regexp = new RegExp("_mod([0-9]+)$");
	
	if ($$('.jm-module')) {
		var modules = $$('.jm-module');
		modules.each(function(element){
			var match = regexp.exec(element.className);
			if (match) {
				var modHeight = parseInt(match[1]);
				var module_in = element.getElement('.jm-module-in');
				if (module_in) {
					module_in.setStyle('height', modHeight);
				}
			}	
		});
	}
	
}

window.addEvent('domready', function(){
	setFrontBottomModulesHeight();
});
