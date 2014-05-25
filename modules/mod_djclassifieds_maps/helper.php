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

class modDjClassifiedsMaps
{
	function getItems($params){
		
		$date_time =& JFactory::getDate();
		$date_exp=$date_time->toMySQL();
		$ord = "i.date_start DESC";
	
		if($params->get('items_ord')==1){
			$ord = "i.display DESC"; 
		}else if($params->get('items_ord')==2){
			$ord = "rand()";
		}	
		
		$where='';
		$cid = JRequest::getInt('cid','0');
				
		if($params->get('fallow_category')==1 && $cid>0){
			$djcfcatlib = new DJClassifiedsCategory();
			$cats= $djcfcatlib->getSubCat($cid,1);				
			$catlist= $cid;			
			foreach($cats as $c){
				$catlist .= ','. $c->id;
			}
			$where .= ' AND i.cat_id IN ('.$catlist.') ';	
			
		}
		
		$db= &JFactory::getDBO();	
		$query = "SELECT i.*, c.name as c_name FROM #__djcf_items i , #__djcf_categories c "
				."WHERE i.date_exp > '".$date_exp."' AND i.published = 1 AND i.cat_id=c.id "
				.$where." "
				."ORDER BY ".$ord." limit ".$params->get('items_limit');
		$db->setQuery($query);
		$items=$db->loadObjectList();
		
		return $items;
	}
	
	function getRegions(){
		$db= &JFactory::getDBO();
		$query = "SELECT r.* FROM #__djcf_regions r "
				."WHERE published=1 ORDER BY r.parent_id ";

		$db->setQuery($query);
		$regions=$db->loadObjectList();

		return $regions;
	}
}