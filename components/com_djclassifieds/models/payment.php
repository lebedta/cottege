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

class DjclassifiedsModelPayment extends JModel
{	
	function getUserItem($id){
		$db = JFactory::getDBO();
		
		$query ="SELECT i.*, c.price as c_price FROM #__djcf_items i "
			   ."LEFT JOIN #__djcf_categories c ON c.id=i.cat_id "
			   ."WHERE i.id=".$id." LIMIT 1";
		$db->setQuery($query);
		$item = $db->loadObject();
		
		return $item;
	}	
	
	function getDuration($day){
		$db= &JFactory::getDBO();
		$query = "SELECT d.* FROM #__djcf_days d "
				."WHERE d.days=".$day;
		$db->setQuery($query);
		$day=$db->loadObject();

		return $day;
	}	

	function getPromotions(){
		$db= &JFactory::getDBO();
		$query = "SELECT p.* FROM #__djcf_promotions p "
				."WHERE p.published=1 ORDER BY p.id ";
		$db->setQuery($query);
		$promotions=$db->loadObjectList();
		return $promotions;
	}	
	
}

