/**
 * @version 2.1
 * @package DJ Catalog 2
 * @copyright Copyright (C) 2010 Blue Constant Media LTD, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://design-joomla.eu
 * @author email contact@design-joomla.eu
 * @developer Michal Olczyk - michal.olczyk@design-joomla.eu
 * 
 * DJ Catalog 2 is free software: you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation, either version 3 of the License, or (at your option) any later
 * version.
 * 
 * DJ Catalog 2 is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 * 
 * You should have received a copy of the GNU General Public License along with
 * DJ Catalog 2. If not, see <http://www.gnu.org/licenses/>.
 * 
 */

(function($) {
	function DJCatMatchModules(className, setLineHeight) {
		var maxHeight = 0;
		if ($$(className)) {
			var divs = $$(className);
			divs.each(function(element) {
				maxHeight = Math.max(maxHeight, parseInt(element.getStyle('height')));
			});
			
			divs.setStyle('height', maxHeight);
			if (setLineHeight) {
				divs.setStyle('line-height', maxHeight);
			}
		}
	}
	
	this.DJCatImageSwitcher = function (){
		var mainimagelink = $('djc_mainimagelink');
		var mainimage = $('djc_mainimage');
		var thumbs = $('djc_thumbnails') ? $('djc_thumbnails').getElements('img') : null;
		var thumblinks = $('djc_thumbnails') ? $('djc_thumbnails').getElements('a') : null;
		
		if(mainimagelink && mainimage) {
			mainimagelink.removeEvents('click').addEvent('click', function(evt) {
				var rel = mainimagelink.rel;
				$(rel).fireEvent('click', $(rel));

				if(!/android|iphone|ipod|series60|symbian|windows ce|blackberry/i.test(navigator.userAgent)) {
					return false;
				}
				return true;
			});
		}
		
		if (!mainimage || !mainimagelink || !thumblinks || !thumbs) return false;
		
		thumblinks.each(function(thumblink,index){
			var fx = new Fx.Tween(mainimage, {link: 'cancel', duration: 200});

			thumblink.addEvent('click',function(element){
				new Event(element).stop();
				/*
				mainimage.onload = function() {
					fx.start('opacity',0,1);
				};
				*/
				var img = new Image();
				img.onload = function() {
					fx.start('opacity',0,1);
				};
				
				fx.start('opacity',1,0).chain(function(){
					mainimagelink.href = thumblink.href;
					mainimagelink.title = thumblink.title;
					mainimagelink.rel = 'djc_lb_'+index;
					img.src = thumblink.rel;
					mainimage.src = img.src;
					mainimage.alt = thumblink.title;
				});
				return false;
			});
		});
	}; 
	
	window.addEvent('domready', function(){
		DJCatImageSwitcher();
	});

	window.addEvent('load', function() {
		DJCatMatchModules('.djc_subcategory_bg', false);
		DJCatMatchModules('.djc_item_bg', false);
		DJCatMatchModules('.djc_thumbnail', true);

		var djcatpagebreak_acc = new Accordion('h3.djc_h3acc',
				'div.djc_rowacc', {
					alwaysHide : false,
					display : 0,
					duration : 150,
					onActive : function(toggler) {
						toggler.addClass('djc_h3acc_active');
					},
					onBackground : function(toggler) {
						toggler.removeClass('djc_h3acc_active');
					}
				});
		var djcatpagebreak_tab = new Accordion('span.djc_litab',
				'div.djc_rowtab', {
					alwaysHide : true,
					display : 0,
					duration : 150,
					onActive : function(toggler) {
						toggler.addClass('djc_litab_active');
					},
					onBackground : function(toggler) {
						toggler.removeClass('djc_litab_active');
					}
				});
	});
})(document.id);
