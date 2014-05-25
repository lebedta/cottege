<?php
/**
* @version		2.0
* @package		DJ Classifieds
* @subpackage	DJ Classifieds Component
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

jimport('joomla.application.component.model');

JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');

class DjclassifiedsModelUserItems extends JModel
{	
	
	
	function getItems(){
		$par = &JComponentHelper::getParams( 'com_djclassifieds' );
		$limit	= JRequest::getVar('limit', $par->get('limit_djitem_show'), '', 'int');
		$limitstart	= JRequest::getVar('limitstart', 0, '', 'int');
		$user = & JFactory::getUser();
			 
			 $order = JRequest::getCmd('order', $par->get('items_ordering','date_e'));
			 $ord_t = JRequest::getCmd('ord_t', $par->get('items_ordering_dir','desc'));
			
			$ord="i.date_exp ";
			
			if($order=="title"){
				$ord="i.name ";
			}elseif($order=="cat"){
				$ord="c.name ";
			}elseif($order=="loc"){
				$ord="r.name ";
			}elseif($order=="price"){
				$ord="i.price ";
			}elseif($order=="display"){
				$ord="i.display ";
			}elseif($order=="date_a"){
				$ord="i.date_start ";
			}elseif($order=="date_e"){
				$ord="i.date_exp ";
			}elseif($order=="published"){
				$ord="i.published ";
			}				
		
			if($ord_t == 'desc'){
				$ord .= 'DESC';
			}else{
				$ord .= 'ASC';
			}
			
			$query = "SELECT i.*, c.name AS c_name, r.name as r_name, i.date_start <= NOW() AND i.date_exp >= NOW() AS s_active FROM #__djcf_items i "
					."LEFT JOIN #__djcf_categories c ON i.cat_id = c.id "
					."LEFT JOIN #__djcf_regions r ON i.region_id = r.id "
					."WHERE i.user_id='".$user->id."' "
					."ORDER BY  ".$ord."";
		
			$items = $this->_getList($query, $limitstart, $limit);	
				//$db= &JFactory::getDBO();$db->setQuery($query);$items=$db->loadObjectList();
				//echo '<pre>';print_r($db);print_r($items);echo '<pre>';die();	
			return $items;
	}
	
	function getCountItems(){
					
			$user = & JFactory::getUser();
			$query = "SELECT count(i.id)FROM #__djcf_items i "
					."WHERE i.user_id='".$user->id."' ";				
						
				$db= &JFactory::getDBO();
				$db->setQuery($query);
				$items_count=$db->loadResult();
				
				//echo '<pre>';print_r($db);print_r($items_count);echo '<pre>';die();	
			return $items_count;
	}	
	
	
	
}

