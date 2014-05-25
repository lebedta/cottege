<?php
/**
* @version		2.0
* @package		DJ Classifieds
* @subpackage	DJ Classifieds Search Module
* @copyright	Copyright (C) 2010 DJ-Extensions.com LTD, All rights reserved.
* @license		http://www.gnu.org/licenses GNU/GPL
* @autor url    http://design-joomla.eu
* @autor email  contact@design-joomla.eu
* @Developer    Lukasz Ciastek - lukasz.ciastek@design-joomla.eu
* 
* 
* DJ Classifieds is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* DJ Classifieds is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with DJ Classifieds. If not, see <http://www.gnu.org/licenses/>.
* 
*/
defined ('_JEXEC') or die('Restricted access');
require_once(JPATH_BASE.DS.'administrator'.DS.'components'.DS.'com_djclassifieds'.DS.'lib'.DS.'djcategory.php');

// Include the syndicate functions only once
require_once (dirname(__FILE__).DS.'helper.php');
if(JRequest::getVar('option')!='com_djclassifieds'){
	$document=& JFactory::getDocument();
	$cs = JURI::base().'components/com_djclassifieds/assets/style.css';
	$document->addStyleSheet($cs);	
	
	$par = &JComponentHelper::getParams( 'com_djclassifieds' );
	if($par->get('include_css','1')){
		$cs = JURI::base().'components/com_djclassifieds/assets/style_default.css';
		$document->addStyleSheet($cs);	
	}
}
$items = modDjClassifiedsMaps::getItems($params);
$regions = modDjClassifiedsMaps::getRegions();

	foreach($items as $item){
		$country='';
		$city='';				
		
		if($item->region_id!=0){
										
			$rid = $item->region_id;
			if($rid!=0){
				while($rid!=0){	
					foreach($regions as $li){
						if($li->id==$rid){
							$rid=$li->parent_id;
							if($li->country){
								$country =$li->name; 
							}
							if($li->city){
								$city =$li->name; 
							}
							break;
						}
					}
				}
			}
		}
		
		$item->city = $city;
		$item->country = $country; 	
	}
	//echo '<pre>';print_r($items);die();
require (JModuleHelper::getLayoutPath('mod_djclassifieds_maps'));


