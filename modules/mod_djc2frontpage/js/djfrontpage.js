/**
* @version 2.12
* @package DJ Catalog
* @subpackage DJ Frontpage Module
* @copyright Copyright (C) 2010 Blue Constant Media LTD, All rights reserved.
* @license http://www.gnu.org/licenses GNU/GPL
* @author url: http://design-joomla.eu
* @author email contact@design-joomla.eu
* @developer Michal Olczyk - michal.olczyk@design-joomla.eu
*
*
* DJ Catalog is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* DJ Catalog is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with DJ Catalog. If not, see <http://www.gnu.org/licenses/>.
*
*/
(function($){
this.DJFrontpage = new Class({
	
	Implements: Chain,
	
	settings: {
		moduleId: 0,
		pagstart:0,
		baseurl: 'null',
		url : '',
		showcategorytitle: 0,
		showtitle: 1,
		linktitle: 1,
		showpagination: 0,
		order: 0,
		featured_only: 0,
		featured_first: 0,
		columns: 1,
		rows: 3,
		allcategories: 1,
		categories: '',
		mainimage: 'medium',
		trunc: 0,
		trunclimit: 0,
		effectduration: 500,
		showreadmore: 1,
		readmoretext: ''
	},
	
	initialize: function(options) {
		this.settings = $merge(this.settings, options);
		if (!this.settings.baseurl) return false;
		this.buildUrl();
		
		this.largeimgcontainer = ('djfimg_' + this.settings.moduleId); 
		this.textcontainer = ('djftext_' + this.settings.moduleId); 
		this.thumbscontainer = ('djfgal_' + this.settings.moduleId); 
		this.categorycontainer = ('djfcat_' + this.settings.moduleId); 
		this.paginationcontainer = ('djfpag_' + this.settings.moduleId); 
		
		this.imgFx = new Fx.Tween(this.largeimgcontainer ,{link: 'cancel', duration: this.settings.effectduration});//,'opacity', {wait: false, duration: this.settings.effectduration}).set(0);
		this.textFx = new Fx.Tween(this.textcontainer,{link: 'cancel', duration: this.settings.effectduration});//,'opacity', {wait: false, duration: this.settings.effectduration}).set(0);
		
		if (this.settings.showcategorytitle == 1) {
			this.categoryFx = new Fx.Tween(this.categorycontainer,{link: 'cancel', duration: this.settings.effectduration});//, 'opacity', {wait: false, duration: this.settings.effectduration}).set(0);
		}
		this.galleryFx = new Fx.Tween(this.thumbscontainer,{link: 'cancel', duration: this.settings.effectduration});//,'opacity', {wait: false, duration: this.settings.effectduration}).set(0);
		this.loadPage(0);
	},
	
	buildUrl: function() {
		this.settings.url = this.settings.baseurl
			+ '&moduleId='	+	this.settings.moduleId 
			+ '&scattitle='	+	this.settings.showcategorytitle 
			+ '&stitle='	+	this.settings.showtitle
			+ '&ltitle='	+	this.settings.linktitle
			+ '&spag='		+	this.settings.showpagination
			+ '&orderby='	+	this.settings.order
			+ '&orderdir='	+	this.settings.orderdir
			+ '&featured_only='	+	this.settings.featured_only
			+ '&featured_first='	+	this.settings.featured_first
			+ '&cols='		+	this.settings.columns
			+ '&rows='		+	this.settings.rows
			+ '&catsw='		+	this.settings.allcategories
			+ '&categories='+	this.settings.categories
			+ '&mainimg='	+	this.settings.mainimage
			+ '&trunc='		+	this.settings.trunc
			+ '&trunclimit='+	this.settings.trunclimit
			+ '&showreadmore='+	this.settings.showreadmore
			+ '&readmoretext='+	this.settings.readmoretext
			+ '&pagstart='	+	this.settings.pagstart;
	},
	
	ajaxResponse: function(response) {
			var xmltext = response;
			var xmlobject = null;
			try //Internet Explorer
			{
				xmlobject = new ActiveXObject("Microsoft.XMLDOM");
				xmlobject.async = "false";
				xmlobject.loadXML(xmltext);
			} 
			catch (e) {
				try //Firefox, Mozilla, Opera, etc.
				{
					xmlobject = (new DOMParser()).parseFromString(xmltext, "text/xml");
				} 
				catch (e) {
					alert(e.message);
				}
			}
			this.loadPageContent(xmlobject);
	},
	
	loadPage: function(page) {
		this.settings.pagstart = (page) ? page : 0;
		this.buildUrl();
		
		this.ajax = new Request({
		    url: this.settings.url,
		    method: 'get',
		    encoding: 'utf-8',
		    onSuccess: function(resp) {
				this.ajaxResponse(resp);
				}.bind(this)
		});
		this.ajax.send.delay(10, this.ajax);
	},
	
	loadPageContent: function (xmlobject){
		var contents = xmlobject.getElementsByTagName("contents")[0];
		var content = contents.getElementsByTagName("content");
		var thumbs = contents.getElementsByTagName("thumb");
		if (contents.getElementsByTagName("pagination").length) {
			if (contents.getElementsByTagName("pagination")[0].firstChild && this.settings.showpagination == 1) {
				$(this.paginationcontainer).innerHTML = contents.getElementsByTagName("pagination")[0].firstChild.nodeValue;
			}
		}
		
		this.data = new Array();
		for (var i = 0; i < content.length; i++) {
			this.data[i] = new Class();
			this.data[i].text = content[i].getElementsByTagName("text")[0].firstChild.nodeValue;
			this.data[i].image = content[i].getElementsByTagName("image")[0].firstChild.nodeValue;
			this.data[i].src = content[i].getElementsByTagName("src")[0].firstChild.nodeValue;
			if (this.settings.showcategorytitle == 1) {
				this.data[i].category = content[i].getElementsByTagName("category")[0].firstChild.nodeValue;				
			}
		}
		
		this.thumbnails = new Array();
		(function() {
			for (var i = 0; i < this.settings.rows * this.settings.columns; i++) {
			$('djfptd_' + this.settings.moduleId + '_' + i).innerHTML = '';
			}
			for (var i = 0; i < thumbs.length; i++) {
				this.thumbnails[i] = thumbs[i].firstChild.nodeValue;
				$('djfptd_' + this.settings.moduleId + '_' + i).innerHTML = this.thumbnails[i];
			}
			this.galleryFx.start('opacity',0,1);
			}).delay(this.settings.effectduration,this);
		
		this.loadItem(0);
	},
	
	loadItem: function(id) {
		if (this.data[id]) {
			this.chain(this.hideItem(id), this.showItem(id));
		}
	},
	hideItem : function(id) {
		this.imgFx.start('opacity',1, 0);
		this.textFx.start('opacity',1, 0);
		if (this.settings.showcategorytitle == 1) {
			this.categoryFx.start('opacity',1, 0);
		}
	},
	
	showItem : function(id) {
		var image = new Image();
		
		image.onload = function(){
			$(this.largeimgcontainer).innerHTML = '';
			$(this.largeimgcontainer).appendChild(image);
			$(this.largeimgcontainer).setAttribute("href", this.data[id].src);
			this.imgFx.start('opacity',0, 1);
			
			$(this.textcontainer).innerHTML = this.data[id].text;
			this.textFx.start('opacity',0, 1);
			
			if (this.settings.showcategorytitle == 1) {
				$(this.categorycontainer).innerHTML = this.data[id].category;
				this.categoryFx.start('opacity',0, 1);
			}
			
		}.bind(this);
		
		(function(){
			image.src = this.data[id].image;
		}).delay(this.settings.effectduration, this);
	}
});
})(document.id);
