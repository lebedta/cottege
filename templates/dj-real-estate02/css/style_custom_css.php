<?php

/*--------------------------------------------------------------
# Copyright (C) joomla-monster.com
# License: http://www.joomla-monster.com/license.html Joomla-Monster Proprietary Use License
# Website: http://www.joomla-monster.com
# Support: info@joomla-monster.com
---------------------------------------------------------------*/
?>

body {
	<?php
	switch ($bodyfontchooser) {
	    case "0":
	        $fontfacechooser = ($this->params->get("fontFaceChooser", "Arial, Helvetica, sans-serif"));
	        echo "font-family: " . $fontfacechooser . ";";
	        break;
	    case "1":
	        $customfont = ($this->params->get("customFont", "Tahoma"));
	        echo "font-family: " . $customfont . ";";
	        break;
	    case "2":
	        echo "font-family: " . $bodygooglewebfontfamily . ";";
	        break;
	    default:
	        echo "font-family: Tahoma;";
	}
	?>
	font-size: <?php echo $fontsize; ?>px;
}

.dj-main li a.dj-up_a {
	<?php
	switch ($djmenufontchooser) {
	    case "0":
	        $djmenufontfacechooser = ($this->params->get("djmenuFontFaceChooser", "Arial, Helvetica, sans-serif"));
	        echo "font-family: " . $djmenufontfacechooser . ";";
	        break;
	    case "1":
	        $djmenucustomfont = ($this->params->get("djmenuCustomFont", "Tahoma"));
	        echo "font-family: " . $djmenucustomfont . ";";
	        break;
	    case "2":
	        echo "font-family: " . $djmenugooglewebfontfamily . ";";
	        break;
	    default:
	        echo "font-family: Tahoma;";
	}
	?>
	font-size: <?php echo $djmenufontsize; ?>px;
}

#jm-top-bar-in,
#jm-header-in,
#jm-midpage-in,
#jm-bottompage-in,
#jm-footer-in{
	max-width: <?php echo $templatewidth; ?>px;
}

#jm-left {
	width: <?php echo $columnleft; ?>%;
}

#jm-content-right {
	width: <?php echo $two_cols; ?>%;
}

#jm-content {
	width: <?php echo $two_cols_content; ?>%;
}

#jm-right {
	width: <?php echo $two_cols_right; ?>%;
}

.jm-module .jm-title-wrap h3,
.djslider .slide-desc-text .slide-title,
#dj-classifieds .dj-category .title h2,
#dj-classifieds .dj-category .cat_row_title,
#dj-classifieds .djcf_items_blog .title h2,
#dj-classifieds .dj-additem .title_top,
#dj-classifieds .dj-cattree-box .title_top,
#dj-classifieds .dj-item .title_top h2,
#dj-classifieds .title_top h1,
#dj-classifieds table.paymentdetails td.td_title,
.djf_mod .djf_text h3,
.djslider .slide-desc-text .slide-title,
#djcatalog .djc_subcategories h2.djc_title,
#djcatalog h3.djc_related_title,
#djcatalog .djc_title h3,
#djcatalog h2.djc_title,
.djf_mod .djf_text h3,
.djcf .tip-title,
.view h2,
h1#jm-logo,
input.button, button.validate, button,
p.readmore a:link, p.readmore a:visited,
a.readmore:link, a.readmore:visited,
.djslider .slide-desc-text .slide-title,
.classifieds_buttons a.button:link,
.classifieds_buttons a.button:visited,
.see_details_box a.see_details:link, .see_details_box a.see_details:visited,
#dj-classifieds .djcf_items_blog .see_details_box a.see_details:link,
#dj-classifieds .djcf_items_blog .see_details_box a.see_details:visited,
.djcf_menu .newad_link_bottom a:link,
.djcf_menu .newad_link_bottom a:visited,
.dj_cf_search a.button:link,
.dj_cf_search a.button:visited,
a.readon:link,
a.readon:visited,
.classifieds_buttons a.button:link,
.classifieds_buttons a.button:visited {
	<?php
	switch ($headingsfontchooser) {
	    case "0":
	        $headingsfontfacechooser = ($this->params->get("headingsFontFaceChooser", "Arial, Helvetica, sans-serif"));
	        echo "font-family: " . $headingsfontfacechooser . ";";
	        break;
	    case "1":
	        $headingscustomfont = ($this->params->get("headingsCustomFont", "Tahoma"));
	        echo "font-family: " . $headingscustomfont . ";";
	        break;
	    case "2":
	        echo "font-family: " . $headingsgooglewebfontfamily . ";";
	        break;
	    default:
	        echo "font-family: Tahoma;";
	}
	?>
}

.jm-module .jm-title-wrap h3,
#dj-classifieds .dj-category .cat_row_title,
#dj-classifieds .djcf_items_blog .title h2,
#dj-classifieds .dj-additem .title_top,
#dj-classifieds .dj-cattree-box .title_top,
#dj-classifieds .dj-item .title_top h2,
#dj-classifieds .title_top h1,
#dj-classifieds table.paymentdetails td.td_title,
.djf_mod .djf_text h3,
#djcatalog .djc_subcategories h2.djc_title,
#djcatalog .djc_category h2.djc_title,
.djslider .slide-desc-text .slide-title,
#djcatalog h3.djc_related_title,
.view h2 {
	font-size: <?php echo $headingsfontsize; ?>px;
}

div.blog h2,
div.item-page h2,
div.blog-featured h2,
div.items-more h3,
div.item-page h2,
ul#archive-items li.row0 h2,
div.contact h2,
div.contact-category h2,
div.newsfeed-category h2,
#dj-classifieds h1.main_cat_title {
	<?php
	switch ($articlesfontchooser) {
	    case "0":
	        $articlesfontfacechooser = ($this->params->get("articlesFontFaceChooser", "Arial, Helvetica, sans-serif"));
	        echo "font-family: " . $articlesfontfacechooser . ";";
	        break;
	    case "1":
	        $articlescustomfont = ($this->params->get("articlesCustomFont", "Tahoma"));
	        echo "font-family: " . $articlescustomfont . ";";
	        break;
	    case "2":
	        echo "font-family: " . $articlesgooglewebfontfamily . ";";
	        break;
	    default:
	        echo "font-family: Tahoma;";
	}
	?>
	font-size: <?php echo $articlesfontsize; ?>px;
}

<?php if($this->params->get("advancedSelectors")!='') { ?>
<?php echo $advancedselectors; ?> {
	<?php 
	switch($advancedfontchooser) {
		case "0":
			$advancedfontfacechooser = ($this->params->get("advancedFontFaceChooser", "Arial, Helvetica, sans-serif"));  
			echo "font-family: ".$advancedfontfacechooser.";";
		break;
		case "1":
			$advancedcustomfont = ($this->params->get("advancedCustomFont", "Tahoma"));  
			echo "font-family: ".$advancedcustomfont.";";
		break;
		case "2":		
			echo "font-family: ".$advancedgooglewebfontfamily.";";
		break;
		default: 
			echo "font-family: Tahoma;";
	}
	?>
	font-size: <?php echo $advancedfontsize; ?>px;
}
<?php } ?>