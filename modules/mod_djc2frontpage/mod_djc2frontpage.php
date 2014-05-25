<?php
/**
* @version $Id: mod_djc2frontpage.php 74 2012-06-21 10:23:31Z michal $
* @package DJ-Catalog2
* @copyright Copyright (C) 2010 Blue Constant Media LTD, All rights reserved.
* @license http://www.gnu.org/licenses GNU/GPL
* @author url: http://design-joomla.eu
* @author email contact@design-joomla.eu
* @developer Michal Olczyk - michal.olczyk@design-joomla.eu
*
*
* DJ-Catalog2 is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* DJ-Catalog2 is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with DJ-Catalog2. If not, see <http://www.gnu.org/licenses/>.
*
*/

defined('_JEXEC') or die ('Restricted access');

$app = JFactory::getApplication();

$document = JFactory::getDocument();
JHTML::_('behavior.mootools');
JHTML::_('behavior.modal');

require_once(JPATH_BASE.DS.'components'.DS.'com_djcatalog2'.DS.'helpers'.DS.'theme.php');
DJCatalog2ThemeHelper::setThemeAssets();

$option = JRequest::getVar('option','','default','string');

$componentParams = $app->getParams('com_djcatalog2');
$categories = null;
if (is_array($params->get('catid')))
{
	$categories = implode('|',$params->get('catid'));
}
else {
	$categories = $params->get('catid');
}

$mid = $module->id;

$css = JURI::base().'modules/mod_djc2frontpage/css/style.css';
$js = JURI::base().'modules/mod_djc2frontpage/js/djfrontpage.js';
$document->addStyleSheet($css);
$document->addScript($js);
$document->addScriptDeclaration('
//<!--
	var sliderOptions_'.$mid.' = {
		moduleId: \''.$mid.'\',
		baseurl: \''.JURI::base().'index.php?option=com_djcatalog2&format=raw&task=modfp\',
		showcategorytitle: \''. $params->get('showcattitle').'\',
		showtitle: \''. $params->get('showtitle',1).'\',
		linktitle: \''. $params->get('linktitle',1).'\',
		showpagination: \''. $params->get('showpagination').'\',
		order: \''. $params->get('orderby').'\',
		orderdir: \''. $params->get('orderbydir',0).'\',
		featured_only: \''. $params->get('featured_only',0).'\',
		featured_first: \''. $params->get('featured_first',0).'\',
		columns: \''. $params->get('cols').'\',
		rows: \''. $params->get('rows').'\',
		allcategories: \''. $params->get('catsw').'\',
		categories: \''. $categories.'\',
		trunc: \''. $params->get('trunc','0').'\',
		trunclimit: \''. $params->get('trunclimit','0').'\',
		showreadmore: \''. $params->get('showreadmore','1').'\',
		readmoretext: \''. urlencode($params->get('readmoretext','')).'\',
		url : \'\'	
	};
	
	window.addEvent(\'domready\', function(){
	    this.DJFrontpage_'. $mid.' = new DJFrontpage(sliderOptions_'.$mid.');
	});
// -->
');

require(JModuleHelper::getLayoutPath('mod_djc2frontpage'));
?>