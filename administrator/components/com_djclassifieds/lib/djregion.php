<?php
/**
* @version		2.0
* @package		DJ Classifieds
* @subpackage 	DJ Classifieds Component
* @copyright 	Copyright (C) 2010 DJ-Extensions.com LTD, All rights reserved.
* @license 		http://www.gnu.org/licenses GNU/GPL
* @author 		url: http://design-joomla.eu
* @author 		email contact@design-joomla.eu
* @developer 	Łukasz Ciastek - lukasz.ciastek@design-joomla.eu
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

defined('_JEXEC') or die('Restricted access');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'lib'.DS.'djcategory.php');

JHTML::_('behavior.modal');

class RegionItem{
	var $id;
	var $name;
	var $parent_id;
	var $parent_name;
	var $city;
	var $country;	
	var $published;
	var $level;

	function __construct(){
		$id=null;
		$name=null;
		$parent_id=null;
		$parent_name=null;
		$city=null;
		$country=null;	
		$published=null;
		$level=0;
	}
	
}

class DJClassifiedsRegion {
	
var $parent_id;
var $id;
var $name;
var $childs = Array();
var $level;

function __construct(){
$parent_id=null;
$id=null;
$name=null;
$childs[]=null;
$elem[]=null;
$level=0;
}

public function getRegSelect(){
	$regions = DJClassifiedsRegion::getRegions();
	
	$sort_regions = DJClassifiedsRegion::getListSelect($regions,$regions);
	//echo '<pre>';print_r($sort_regions);echo '</pre>';die();
	
	return $sort_regions;
	
}

public function getRegAll(){
	$regions = DJClassifiedsRegion::getRegions();
	
	$sort_regions = DJClassifiedsRegion::getListAll($regions,$regions);
	//echo '<pre>';print_r($sort_regions);echo '</pre>';die();
	
	return $sort_regions;
	
}

public function getSubReg($id){
	$regions = DJClassifiedsRegion::getRegions();
		
	$sort_regions = DJClassifiedsRegion::getListSubreg($regions,$regions,$id);
	//echo '<pre>';print_r($sort_regions);echo '</pre>';die();
	
	return $sort_regions;
	
}
	
public	function getRegions(){
		if(empty($this->_allregions)) {						
		
			$db= &JFactory::getDBO();	
			$query = "SELECT r.*, rr.name as parent_name FROM #__djcf_regions r "
					."LEFT JOIN #__djcf_regions rr ON r.parent_id=rr.id "
					."ORDER BY r.parent_id, r.name ";
			
				$db->setQuery($query);
				$this->_allregions=$db->loadObjectList();
				//echo '<pre>';print_r($db);print_r($this->_allregions);die();
		}
		return $this->_allregions;
	}	
	
public function getListSelect(& $lists,& $lists_const,& $option=Array(),& $checked=';'){

	$liczba = count($lists_const);
	foreach($lists as $list){

			if(strstr($checked,';'.$list->id.';')){
				$flag=1;
			}else{
				$flag=0;
			}
			
			if($flag==0){
			$checked.=$list->id.';';
			
			$this->parent_id = $list->parent_id;
			$this->id = $list->id;
			//$this->name = $list->name;
			$op= new DJOptionList;
			$op->text=$list->name;
			$op->value=$this->id;
			$option[]=$op;
			
				$this->childs=Array();					
				   for($i=0; $i<$liczba;$i++ ){
					if($lists_const[$i]->parent_id==$list->id){		
						$child=new DJClassifiedsRegion();
						$child->id=$lists_const[$i]->id;
						$child->parent_id=$lists_const[$i]->parent_id;
						if(isset($list->level)){
							$child->level=$list->level+1;	
						}else{
							$child->level=1;
						}
						
						$new_name=$lists_const[$i]->name;
							for($lev=0;$lev<$child->level;$lev++){
								//$new_name="&nbsp;&nbsp;&nbsp;".$new_name;
								$new_name="- ".$new_name;
							}
						$child->name=$new_name;

						
						$this->childs[]=$child;
						DJClassifiedsRegion::getListSelect($this->childs,$lists_const,$option,$checked);
					
					}
				}	
				
			}
	}
	return($option);		
}

public function getListAll(& $lists,& $lists_const,& $option=Array(),& $checked=';'){

	$liczba = count($lists_const);
	foreach($lists as $list){

			if(strstr($checked,';'.$list->id.';')){
				$flag=1;
			}else{
				$flag=0;
			}
			
			if($flag==0){
			$checked.=$list->id.';';
			
			$cat_item =  new CatItem;
			$cat_item->id=$list->id;
			$cat_item->name=$list->name;
			$cat_item->parent_id=$list->parent_id;
			$cat_item->parent_name=$list->parent_name;
			$cat_item->country=$list->country;
			$cat_item->city=$list->city;
			$cat_item->published=$list->published;
			if(isset($list->level)){
				$cat_item->level= $list->level;	
			}else{
				$cat_item->level= 0;
			}
					
			$option[]=$cat_item;
			
				$this->childs=Array();					
				   for($i=0; $i<$liczba;$i++ ){
					if($lists_const[$i]->parent_id==$list->id){		
						$child=new CatItem();
						$child->id=$lists_const[$i]->id;
						$child->name=$lists_const[$i]->name;						
						$child->parent_id=$lists_const[$i]->parent_id;
						$child->parent_id=$lists_const[$i]->parent_id;
						$child->parent_name=$lists_const[$i]->parent_name;
						$child->country=$lists_const[$i]->country;
						$child->city=$lists_const[$i]->city;
						$child->published=$lists_const[$i]->published;
						
						if(isset($list->level)){
							$child->level=$list->level+1;	
						}else{
							$child->level=1;
						}												
						$this->childs[]=$child;
						DJClassifiedsRegion::getListAll($this->childs,$lists_const,$option,$checked);
					
					}
				}	
				
			}
	}
	return($option);		
}

public function getListSubreg(& $lists,& $lists_const, $main_id=0, $main_level=0,$main_f =0 , & $option=Array(),& $checked=';'){

	$liczba = count($lists_const);
	foreach($lists as $list){

			if(strstr($checked,';'.$list->id.';')){
				$flag=1;
			}else{
				$flag=0;
			}
			
			if($flag==0){
			$checked.=$list->id.';';
			
				if(isset($list->level)){
					$current_level= $list->level;	
				}else{
					$current_level= 0;
				}
							
			if($main_f==1 && ($main_level>$current_level || $current_level==$main_level)){
				break;
			}
			
			if($main_id==$list->id){
				$main_f=1;	
				$main_level = $current_level;
			}
			
	
			
			if($main_f==1 && $main_level<$current_level){
				$cat_item =  new CatItem;
				$cat_item->id=$list->id;
				$cat_item->name=$list->name;
				$cat_item->parent_id=$list->parent_id;
				$cat_item->parent_name=$list->parent_name;
				$cat_item->country=$list->country;
				$cat_item->city=$list->city;
				$cat_item->published=$list->published;					
				$cat_item->level= $current_level;
						
				$option[]=$cat_item;
			}
				$this->childs=Array();					
				   for($i=0; $i<$liczba;$i++ ){
					if($lists_const[$i]->parent_id==$list->id){		
						$child=new CatItem();
						$child->id=$lists_const[$i]->id;
						$child->name=$lists_const[$i]->name;						
						$child->parent_id=$lists_const[$i]->parent_id;
						$child->parent_name=$lists_const[$i]->parent_name;
						$child->parent_id=$lists_const[$i]->parent_id;
						$child->country=$lists_const[$i]->country;
						$child->city=$lists_const[$i]->city;
						$child->published=$lists_const[$i]->published;
						
						if(isset($list->level)){
							$child->level=$list->level+1;	
						}else{
							$child->level=1;
						}												
						$this->childs[]=$child;
						DJClassifiedsRegion::getListSubreg($this->childs,$lists_const,$main_id,$main_level,$main_f,$option,$checked);
					
					}
				}	
				
			}
	}
	return($option);		
}
}
