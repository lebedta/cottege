<?php
/**
* @version 2.0
* @package DJ Classifieds Menu Module
* @subpackage DJ Classifieds Component
* @copyright Copyright (C) 2010 DJ-Extensions.com LTD, All rights reserved.
* @license http://www.gnu.org/licenses GNU/GPL
* @author url: http://design-joomla.eu
* @author email contact@design-joomla.eu
* @developer Łukasz Ciastek - lukasz.ciastek@design-joomla.eu
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

class modDjClassifiedsItems{
	function getItems($params){
		
		$date_time =& JFactory::getDate();
		$date_exp=$date_time->toMySQL();
		$ord = "i.date_start DESC";
	
		if($params->get('items_ord')==1){
			$ord = "i.display DESC"; 
		}else if($params->get('items_ord')==2){
			$ord = "rand()";
		}	
		
		$promoted='';
		if($params->get('only_promoted','0')==1){
			$promoted = " AND i.special=1 "; 
		}
		
		$item_ids = $params->get('items_ids','');
		if($item_ids){
			$item_ids = ' AND i.id IN ('.$item_ids.')';
		}else{
			$item_ids = '';
		}
		
		$cid = JRequest::getInt('cid','0');
		$fallow_cat= '';		
		
		if($params->get('fallow_category')==1 && JRequest::getVar('option','')=='com_djclassifieds' && $cid>0){		
			$djcfcatlib = new DJClassifiedsCategory();
			$cats= $djcfcatlib->getSubCat($cid,1);				
			$catlist= $cid;			
			foreach($cats as $c){
				$catlist .= ','. $c->id;
			}
			$fallow_cat = ' AND i.cat_id IN ('.$catlist.') ';	
			
		}
		
		$db= &JFactory::getDBO();	
		$query = "SELECT i.*, c.name as c_name FROM #__djcf_items i , #__djcf_categories c "
				."WHERE i.date_exp > '".$date_exp."' AND i.published = 1 AND i.cat_id=c.id ".$promoted.$item_ids.$fallow_cat 
				."ORDER BY ".$ord." limit ".$params->get('items_nr');
		$db->setQuery($query);
		$items=$db->loadObjectList();
		return $items;
	}
}
?>
