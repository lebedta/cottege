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



class DjclassifiedsModelItem extends JModel
{	

	function getItem(){
		$par = &JComponentHelper::getParams( 'com_djclassifieds' );
		$id= JRequest::getVar('id', 0, '', 'int');	
		$user = JFactory::getUser();
		
		$fav_s='';
		$fav_lj='';
		if($par->get('favourite','1') && $user->id>0){
			$fav_lj = "LEFT JOIN ( SELECT * FROM #__djcf_favourites WHERE user_id=".$user->id.") f ON i.id=f.item_id ";
			$fav_s = ',f.id as f_id ';
		}

		$db= &JFactory::getDBO();
		$query = "SELECT i.*, u.name as username, u.email ".$fav_s." FROM #__djcf_items i "
				.$fav_lj
				."LEFT JOIN #__users u ON u.id = i.user_id  WHERE  i.id = ".$id." LIMIT 1";
		$db->setQuery($query);
		$item=$db->loadObject();
		return $item;
	}
	
	function getCategory($cid){		
		$db= &JFactory::getDBO();
		$query = "SELECT c.* FROM #__djcf_categories c WHERE id='".$cid."' LIMIT 1 ";
		$db->setQuery($query);
		$cat=$db->loadObject();
		return $cat;
	}
	
	function getUserItemsCount($uid){		
		$db= &JFactory::getDBO();
		$date_exp = mktime();
		
		$query = "SELECT COUNT(i.id) FROM #__djcf_items i WHERE i.published=1 AND i.date_exp>'".$date_exp."' AND i.user_id  = ".$uid." ";
		$db->setQuery($query);
		$item=$db->loadResult();
		return $item;
	}
	
/*	function makePathway($item_name){
		global $mainframe;
			$db= &JFactory::getDBO();
			$query = "SELECT * FROM #__djcf_categories";
			$db->setQuery($query);
			$list=$db->loadObjectList();
					
		$document	=& JFactory::getDocument();
		$cid	= JRequest::getVar('cid', 0, '', 'int');
		$pathway =& $mainframe->getPathway();				
		$id = Array();
		$name = Array();
		
		if($cid!=0){
			while($cid!=0){	
				foreach($list as $li){
					if($li->id==$cid){
						$cid=$li->parent_id;
						$id[]=$li->id;
						$name[]=$li->name;
						break;
					}
				}
			}
		}

		
		for($i=count($id)-1;$i>-1;$i--){				
			if($i!=0){
				$pathway->addItem($name[$i], 'index.php?option=com_djclassifieds&view=show&cid='.$id[$i]);	
			}else{
				$pathway->addItem($name[$i], 'index.php?option=com_djclassifieds&view=showlist&cid='.$id[$i]);
			}
		}
		$pathway->addItem($item_name);
		
	}
	*/
	
	function getFields($cid){
		
		$id= JRequest::getVar('id', 0, '', 'int');	
		$db= &JFactory::getDBO();
			     $query ="SELECT f.*, v.value FROM #__djcf_fields f, #__djcf_fields_xref fx "
		 		."LEFT JOIN (SELECT * FROM #__djcf_fields_values WHERE item_id=".$id.") v "
				."ON v.field_id=fx.field_id "
		 		."WHERE f.id=fx.field_id AND fx.cat_id  = ".$cid." AND f.published=1 ORDER BY fx.ordering ";
	     
		$db->setQuery($query);
		$item=$db->loadObjectList();
		
		return $item;
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