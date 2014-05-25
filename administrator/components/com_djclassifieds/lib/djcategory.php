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
JHTML::_('behavior.modal');

class DJOptionList{
var $text;
var $value;
var $disabled;	

function __construct(){
$text=null;
$value=null;			
$disabled=null;
}
	
}

class CatItem{
	var $id;
	var $name;
	var $price;
	var $description;
	var $parent_id;
	var $parent_name;
	var $icon_url;
	var $ordering;	
	var $published;
	var $autopublish;
	var $items_count;
	var $level;

	function __construct(){
		$id=null;
		$name=null;
		$price=null;
		$description=null;
		$parent_id=null;
		$parent_name=null;
		$icon_url=null;
		$ordering=null;	
		$published=null;
		$autopublish=null;
		$items_count=0;
		$level=0;
	}
	
}

class DJClassifiedsCategory {
	
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

public function getCatSelect($pub='0'){
	$cats = DJClassifiedsCategory::getCategories($pub);
	
	$sort_cats = DJClassifiedsCategory::getListSelect($cats,$cats);
	//echo '<pre>';print_r($cats);echo '</pre>';die();
	
	return $sort_cats;
	
}

public function getCatAll($pub='0'){
	$cats = DJClassifiedsCategory::getCategories($pub);
	
	$sort_cats = DJClassifiedsCategory::getListAll($cats,$cats);
	//echo '<pre>';print_r($sort_cats);echo '</pre>';die();
	
	return $sort_cats;
	
}

public function getCatAllItemsCount($pub='0'){
	$cats = DJClassifiedsCategory::getCategories($pub);
	
	$sort_cats = DJClassifiedsCategory::getListAll($cats,$cats);
	$max_level = '0';			
		foreach ($sort_cats as $c){		
			if($c->level>$max_level){
				$max_level = $c->level;
			}
		}		
		
		for($level=$max_level;$level>-1;$level--){
			$parent_value=0;			
			for($c=count($sort_cats);$c>0;$c--){
				if($parent_value>0 && $level>$sort_cats[$c-1]->level){
					$sort_cats[$c-1]->items_count = $sort_cats[$c-1]->items_count + $parent_value;
					$parent_value=0;		
				}					
				if($level==$sort_cats[$c-1]->level){		
					$parent_value =$parent_value + $sort_cats[$c-1]->items_count;
				}
			}		
		}		
	
	return $sort_cats;
	
}

public function getSubCat($id,$pub='0'){
	$cats = DJClassifiedsCategory::getCategories($pub);
		
	$sort_cats = DJClassifiedsCategory::getListSubcat($cats,$cats,$id);
	//echo '<pre>';print_r($sort_cats);echo '</pre>';die();
	
	return $sort_cats;
	
}
public function getSubCatIemsCount($id,$pub='0'){
	$cats = DJClassifiedsCategory::getCategories($pub);
		
	$sort_cats = DJClassifiedsCategory::getListSubcat($cats,$cats,$id);
	//echo '<pre>';print_r($sort_cats);echo '</pre>';die();
	$max_level = '0';			
		foreach ($sort_cats as $c){		
			if($c->level>$max_level){
				$max_level = $c->level;
			}
		}		
		
		for($level=$max_level;$level>-1;$level--){
			$parent_value=0;			
			for($c=count($sort_cats);$c>0;$c--){
				if($parent_value>0 && $level>$sort_cats[$c-1]->level){
					$sort_cats[$c-1]->items_count = $sort_cats[$c-1]->items_count + $parent_value;
					$parent_value=0;		
				}					
				if($level==$sort_cats[$c-1]->level){		
					$parent_value =$parent_value + $sort_cats[$c-1]->items_count;
				}
			}		
		}	
		
	return $sort_cats;
	
}
	
public function getParentPath($pub='0',$cid='0'){
	$cats = DJClassifiedsCategory::getCategories($pub);
	$cat_path=Array();
			
	while($cid!=0){	
		foreach($cats as $c){
			if($c->id==$cid){
				$cid=$c->parent_id;
				$subcat=new DJClassifiedsCategory();
					$subcat->id=$c->id;
					$subcat->name=$c->name;
					$subcat->parent_id=$c->parent_id;	
					$cat_path[]=$subcat;
					break;
				}
			}
		}
			
	//echo '<pre>';print_r($cat_path);echo '</pre>';die();
	
	return $cat_path;
	
}	

public function getMenuCategories($cid='0',$show_count='1'){
	$cats = DJClassifiedsCategory::getCategories(1);
	
	$sort_cats = DJClassifiedsCategory::getListAll($cats,$cats);
	if($show_count){			
		$max_level = '0';			
		foreach ($sort_cats as $c){		
			if($c->level>$max_level){
				$max_level = $c->level;
			}
		}		
		
		for($level=$max_level;$level>-1;$level--){
			$parent_value=0;			
			for($c=count($sort_cats);$c>0;$c--){
				if($parent_value>0 && $level>$sort_cats[$c-1]->level){
					$sort_cats[$c-1]->items_count = $sort_cats[$c-1]->items_count + $parent_value;
					$parent_value=0;		
				}					
				if($level==$sort_cats[$c-1]->level){		
					$parent_value =$parent_value + $sort_cats[$c-1]->items_count;
				}
			}		
		}
	}
	
	$cat_path=','.$cid.',';		
	if($cid>0){								
		$cat_id = $cid;
		while($cat_id!=0){	
			foreach($cats as $c){
				if($c->id==$cat_id){
					$cat_id=$c->parent_id;
					$cat_path .= $cat_id.',';
					break;
				}
			}
		}			
	}
	$menu_cats = array();
	for($i=0;$i<count($sort_cats);$i++){		
		if(strstr($cat_path,','.$sort_cats[$i]->id.',') || strstr($cat_path,','.$sort_cats[$i]->parent_id.',')){
			$menu_cats[] = $sort_cats[$i]; 
		}
		
	}
	$ret = array();
	$ret[]= $menu_cats;
	$ret[]= $cat_path;
	
	return $ret;
}
	
public	function getCategories($p='0'){
								
		
			if($p){
				$pub = 'WHERE c.published=1 ';
			}else{
				$pub ='';
			}
			$db= &JFactory::getDBO();	
			$date_time =& JFactory::getDate();
			$date_exp=$date_time->toMySQL();
			
			$query = "SELECT c.*, cc.name as parent_name,i.items_count FROM #__djcf_categories c "
					."LEFT JOIN #__djcf_categories cc ON c.parent_id=cc.id "
					."LEFT JOIN (SELECT i.cat_id, count(i.id) as items_count "
								."FROM #__djcf_items i WHERE i.published=1 AND i.date_exp > '".$date_exp."' GROUP BY i.cat_id) i ON i.cat_id=c.id "
					.$pub
					."ORDER BY c.parent_id, c.ordering ";
			
				$db->setQuery($query);
				$allcategories=$db->loadObjectList();
				//echo '<pre>';print_r($db);print_r($this->_allcategories);die();
		
		return $allcategories;
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
			/*$this->id = $list->id;
			$this->name = $list->name;
			$op= new DJOptionList;
			$op->text=$this->name;
			$op->value=$this->id;*/
			$this->parent_id = $list->parent_id;
			$op= new DJOptionList;
			$op->text=$list->name;;
			$op->value=$list->id;
			$option[]=$op;
			
				$this->childs=Array();					
				   for($i=0; $i<$liczba;$i++ ){
					if($lists_const[$i]->parent_id==$list->id){		
						$child=new DJClassifiedsCategory();
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
						DJClassifiedsCategory::getListSelect($this->childs,$lists_const,$option,$checked);
					
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
			$cat_item->price=$list->price;
			$cat_item->description=$list->description;
			$cat_item->parent_id=$list->parent_id;
			$cat_item->parent_name=$list->parent_name;
			$cat_item->icon_url=$list->icon_url;
			$cat_item->ordering=$list->ordering;
			$cat_item->published=$list->published;
			$cat_item->autopublish=$list->autopublish;			
			$cat_item->items_count= $list->items_count;	
			
			
			if(isset($list->level)){
				$cat_item->level= $list->level;	
			}else{
				$cat_item->level= 0;
			}
					
			$option[]=$cat_item;
			
				$childs=Array();					
				   for($i=0; $i<$liczba;$i++ ){
					if($lists_const[$i]->parent_id==$list->id){		
						$child=new CatItem();
						$child->id=$lists_const[$i]->id;
						$child->name=$lists_const[$i]->name;						
						$child->parent_id=$lists_const[$i]->parent_id;
						$child->price=$lists_const[$i]->price;
						$child->description=$lists_const[$i]->description;
						$child->parent_id=$lists_const[$i]->parent_id;
						$child->parent_name=$lists_const[$i]->parent_name;
						$child->icon_url=$lists_const[$i]->icon_url;
						$child->ordering=$lists_const[$i]->ordering;
						$child->published=$lists_const[$i]->published;
						$child->autopublish=$lists_const[$i]->autopublish;						
						$child->items_count=$lists_const[$i]->items_count;	
						
						
						if(isset($list->level)){
							$child->level=$list->level+1;	
						}else{
							$child->level=1;
						}												
						$childs[]=$child;
						DJClassifiedsCategory::getListAll($childs,$lists_const,$option,$checked);
					
					}
				}	
				
			}
	}
	return($option);		
}

public function getListSubcat(& $lists,& $lists_const, $main_id=0, $main_level=0,$main_f =0 , & $option=Array(),& $checked=';'){

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
				$cat_item->price=$list->price;
				$cat_item->description=$list->description;
				$cat_item->parent_id=$list->parent_id;
				$cat_item->parent_name=$list->parent_name;
				$cat_item->icon_url=$list->icon_url;
				$cat_item->ordering=$list->ordering;
				$cat_item->published=$list->published;
				$cat_item->autopublish=$list->autopublish;					
				$cat_item->items_count= $list->items_count;
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
						$child->price=$lists_const[$i]->price;
						$child->description=$lists_const[$i]->description;
						$child->parent_id=$lists_const[$i]->parent_id;
						$child->icon_url=$lists_const[$i]->icon_url;
						$child->ordering=$lists_const[$i]->ordering;
						$child->published=$lists_const[$i]->published;
						$child->items_count=$lists_const[$i]->items_count;
						$child->autopublish=$lists_const[$i]->autopublish;
						
						if(isset($list->level)){
							$child->level=$list->level+1;	
						}else{
							$child->level=1;
						}												
						$this->childs[]=$child;
						DJClassifiedsCategory::getListSubcat($this->childs,$lists_const,$main_id,$main_level,$main_f,$option,$checked);
					
					}
				}	
				
			}
	}
	return($option);		
}
}
