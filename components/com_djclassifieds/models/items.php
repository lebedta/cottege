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

class DjclassifiedsModelItems extends JModel
{	
	
	
	function getItems($catlist=''){
		$par = &JComponentHelper::getParams( 'com_djclassifieds' );
		$limit	= JRequest::getVar('limit', $par->get('limit_djitem_show'), '', 'int');
		$limitstart	= JRequest::getVar('limitstart', 0, '', 'int');
		$user = JFactory::getUser();
			
			$where = '';
			if($catlist){
				$where =' AND i.cat_id IN ('.$catlist.') '; 
			}
			
			$uid=JRequest::getVar('uid','0','','int'); 
			if($uid>0){
				$where .= " AND i.user_id=".$uid." ";
			}
			
			$fav_s='';
			$fav_lj='';
			if($par->get('favourite','1') && $user->id>0){
				$fav_lj = "LEFT JOIN ( SELECT * FROM #__djcf_favourites WHERE user_id=".$user->id.") f ON i.id=f.item_id ";
				$fav_s = ',f.id as f_id ';
				$fav=JRequest::getVar('fav','0','','int');				
				if($fav>0){
					$where .= " AND f.id IS NOT NULL ";
				}
			}
		
				$search ='';
				$search_fields='';
				$cat_id = 0;
				$reg_id = 0;
				if(JRequest::getVar('se','0','','string')!='0'){			
					if(JRequest::getVar('search',JText::_('COM_DJCLASSIFIEDS_SEARCH'),'','string')!=JText::_('COM_DJCLASSIFIEDS_SEARCH')){
						$search = " AND CONCAT(i.name,i.intro_desc,i.description) LIKE '%".JRequest::getVar('search','','','string')."%' ";
					}
					if(isset($_GET['se_cats'])){
						$cat_id= end($_GET['se_cats']);						
						if($cat_id=='' && count($_GET['se_cats'])>2){
							$cat_id =$_GET['se_cats'][count($_GET['se_cats'])-2];
						}
					}
						
						
					
					if($cat_id>0){
						$cats= DJClassifiedsCategory::getSubCat($cat_id,1);					
						$catlist= $cat_id;			
						foreach($cats as $c){
							$catlist .= ','. $c->id;
						}
						$search .= ' AND i.cat_id IN ('.$catlist.') ';	
					
						$search_fields = $this->getSearchFields();
					}
					
					if(isset($_GET['se_regs'])){
						$reg_id= end($_GET['se_regs']);
						if($reg_id=='' && count($_GET['se_regs'])>2){
							$reg_id =$_GET['se_regs'][count($_GET['se_regs'])-2];
						}	
					}


					if($reg_id>0){
						$regs= DJClassifiedsRegion::getSubReg($reg_id,1);
																
						$reglist= $reg_id;			
						foreach($regs as $r){
							$reglist .= ','. $r->id;
						}
						$search .= ' AND i.region_id IN ('.$reglist.') ';						
					}

					
					$se_price_from = JRequest::getInt('se_price_f','');
					$se_price_to = JRequest::getInt('se_price_t','');
					if($se_price_from){
						$search .= " AND i.price >= '".$se_price_from."' ";
					}
					
					if($se_price_to && $se_price_to>$se_price_from){
						$search .= " AND i.price <= '".$se_price_to."' ";
					}
				
						
				}
				
			 
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
				$ord="ABS(i.price) ";
			}elseif($order=="display"){
				$ord="i.display ";
			}elseif($order=="date_a"){
				$ord="i.date_start ";
			}elseif($order=="date_e"){
				$ord="i.date_exp ";
			}
		
			if($ord_t == 'desc'){
				$ord .= 'DESC';
			}else{
				$ord .= 'ASC';
			}
			
			$date_time =& JFactory::getDate();
			$date_exp=$date_time->toMySQL();
			
			$query = "SELECT i.*, c.name AS c_name, r.name as r_name ".$fav_s." FROM ".$search_fields." #__djcf_items i "
					."LEFT JOIN #__djcf_categories c ON i.cat_id = c.id "
					."LEFT JOIN #__djcf_regions r ON i.region_id = r.id "
					.$fav_lj
					."WHERE i.date_exp > NOW() AND i.published=1 "
					.$where;
					
				if($search_fields){
					$query .=" AND sf.item_id=i.id ";			
				}
			$query .= $search." ORDER BY i.special DESC, ".$ord."";
		
			$items = $this->_getList($query, $limitstart, $limit);	
			
			//	$db= &JFactory::getDBO();$db->setQuery($query);$items=$db->loadObjectList();
			//	echo '<pre>';print_r($db);print_r($items);echo '<pre>';die();				
			return $items;
	}
	
	function getCountItems($catlist=''){
			$par = &JComponentHelper::getParams( 'com_djclassifieds' );
			$user=JFactory::getUser();
			$where = '';
			if($catlist){
				$where =' AND i.cat_id IN ('.$catlist.') '; 
			}
			$uid=JRequest::getVar('uid','0','','int'); 
			if($uid>0){
				$where .= " AND i.user_id=".$uid." ";
			}

			$fav_lj='';
			if($par->get('favourite','1') && $user->id>0){
				$fav_lj = "LEFT JOIN ( SELECT * FROM #__djcf_favourites WHERE user_id=".$user->id.") f ON i.id=f.item_id ";
				$fav=JRequest::getVar('fav','0','','int');				
				if($fav>0){
					$where .= " AND f.id IS NOT NULL ";
				}
			}
				$search ='';
				$search_fields='';
				$cat_id = 0;
				$reg_id = 0;
				if(JRequest::getVar('se','0','','string')!='0'){			
					if(JRequest::getVar('search',JText::_('COM_DJCLASSIFIEDS_SEARCH'),'','string')!=JText::_('COM_DJCLASSIFIEDS_SEARCH')){
						$search = " AND CONCAT(i.name,i.intro_desc,i.description) LIKE '%".JRequest::getVar('search','','','string')."%' ";
					}
					
					if(isset($_GET['se_cats'])){
						$cat_id= end($_GET['se_cats']);						
						if($cat_id=='' && count($_GET['se_cats'])>2){
							$cat_id =$_GET['se_cats'][count($_GET['se_cats'])-2];
						}
					}
					
					if($cat_id>0){
						$cats= DJClassifiedsCategory::getSubCat($cat_id,1);					
						$catlist= $cat_id;			
						foreach($cats as $c){
							$catlist .= ','. $c->id;
						}
						$search .= ' AND i.cat_id IN ('.$catlist.') ';	
					
						$search_fields = $this->getSearchFields();
					}
					
					if(isset($_GET['se_regs'])){
						$reg_id= end($_GET['se_regs']);
						if($reg_id=='' && count($_GET['se_regs'])>2){
							$reg_id =$_GET['se_regs'][count($_GET['se_regs'])-2];
						}	
					}


					if($reg_id>0){
						$regs= DJClassifiedsRegion::getSubReg($reg_id,1);										
						$reglist= $reg_id;			
						foreach($regs as $r){
							$reglist .= ','. $r->id;
						}
						$search .= ' AND i.region_id IN ('.$reglist.') ';						
					}
					
					
					$se_price_from = JRequest::getCMD('se_price_f','');
					$se_price_to = JRequest::getCMD('se_price_t','');
					if($se_price_from){
						$search .= " AND i.price >= '".$se_price_from."' ";
					}
					
					if($se_price_to && $se_price_to>$se_price_from){
						$search .= " AND i.price <= '".$se_price_to."' ";
					}					
				
						
				}
					
			$date_time =& JFactory::getDate();
			$date_exp=$date_time->toMySQL();
			
			$query = "SELECT count(i.id) FROM ".$search_fields." #__djcf_items i "
					.$fav_lj
					."WHERE i.date_exp > NOW() AND i.published=1 "
					.$where.$search;		
				if($search_fields){
					$query .=" AND sf.item_id=i.id ";			
				}			
						
				$db= &JFactory::getDBO();
				$db->setQuery($query);
				$items_count=$db->loadResult();
				
				//echo '<pre>';print_r($db);print_r($items_count);echo '<pre>';die();	
			return $items_count;
	}	
	
	function getUserName($uid){						
			$db= &JFactory::getDBO();
			$query = "SELECT name FROM #__users WHERE id=".$uid." LIMIT 1";
			$db->setQuery($query);
			$username=$db->loadResult();
			return $username;
	}
	
	
	function getMainCat($cat_id){						
			$db= &JFactory::getDBO();
			$query = "SELECT * FROM #__djcf_categories WHERE id=".$cat_id." LIMIT 1";
			$db->setQuery($query);
			$cat_name=$db->loadObject();
			return $cat_name;
	}	
	
	function getSearchFields(){
		$search_fields = '';
			$session = &JFactory::getSession();							
			$cat_id= end($_GET['se_cats']);
			if(!$cat_id){
				$cat_id =$_GET['se_cats'][count($_GET['se_cats'])-2];
			}	
			
			$db= &JFactory::getDBO();
			$query = "SELECT f.* FROM #__djcf_fields f, #__djcf_fields_xref fx "
					."WHERE fx.field_id=f.id AND fx.cat_id=".$cat_id."";
			$db->setQuery($query);
			$fields=$db->loadObjectList();
				
				$search_fields = 'SELECT * FROM (SELECT COUNT( * ) AS c, item_id FROM (';
				$sf_count = 0;
			
				foreach($fields as $f){
					if($f->search_type=='select_min_max' || $f->search_type=='inputbox_min_max'){
						if(isset($_GET['se_'.$f->id.'_min']) && $_GET['se_'.$f->id.'_max']){	
							$f_v1 =  $_GET['se_'.$f->id.'_min'];
							$f_v2 =  $_GET['se_'.$f->id.'_max'];
							if($f_v1 && $f_v1){
								$sf_count ++;	
								if(is_numeric($f_v1) && is_numeric($f_v2)){
									$search_fields .= " (SELECT * FROM #__djcf_fields_values f WHERE f.field_id=".$f->id." AND f.value >= ".$f_v1."";
									$search_fields .= " AND f.value <= ".$f_v2." ) UNION ";	
								}else{
									$search_fields .= " (SELECT * FROM #__djcf_fields_values f WHERE f.field_id=".$f->id." AND f.value >= '".$f_v1."'";
									$search_fields .= " AND f.value <= '".$f_v2."' ) UNION ";
								}
								$session->set('se_'.$f->id.'_min',$f_v1);
								$session->set('se_'.$f->id.'_max',$f_v2);
							}
						}
						if(!isset($_GET['se_'.$f->id.'_min'])){
							$session->set('se_'.$f->id.'_min','');
						}
						if(!isset($_GET['se_'.$f->id.'_max'])){
							$session->set('se_'.$f->id.'_max','');
						} 
							
					}elseif($f->search_type=='checkbox'){
						if(isset($_GET['se_'.$f->id])){
							$v_chec = array();
							$v_chec =  $_GET['se_'.$f->id];
							$f_v1 =';';
							//print_R($f);die();
							if(count($v_chec)>0){
								$sf_count ++;
								if($f->type=='checkbox'){
									$search_fields .= " (SELECT * FROM #__djcf_fields_values f WHERE f.field_id=".$f->id." ";
									for($ch=0;$ch<count($v_chec);$ch++){
										$search_fields .= " AND f.value LIKE '%;".$v_chec[$ch].";%' ";
										$f_v1 .= $v_chec[$ch].';';
									}										
								}else{								
									$search_fields .= " (SELECT * FROM #__djcf_fields_values f WHERE f.field_id=".$f->id." ";
									for($ch=0;$ch<count($v_chec);$ch++){
										if($ch==0){
											$search_fields .= " AND ( f.value = '".$v_chec[$ch]."' ";	
										}else{
											$search_fields .= " OR f.value = '".$v_chec[$ch]."' ";
										}																				
										$f_v1 .= $v_chec[$ch].';';
									}
									$search_fields .= ') ';									
								}
									$search_fields .= " ) UNION ";
									$session->set('se_'.$f->id,$f_v1);
							}
							
						}else{
							$session->set('se_'.$f->id,'');	
						}						
					}else{
						if(isset($_GET['se_'.$f->id])){							
							$f_v1 =  $_GET['se_'.$f->id];
							if($f_v1!=''){
								$sf_count ++;
								$search_fields .= " (SELECT * FROM #__djcf_fields_values f WHERE f.field_id=".$f->id." AND f.value LIKE '%".$f_v1."%' ) UNION ";
								$session->set('se_'.$f->id,$f_v1);								
							}else{
								$session->set('se_'.$f->id,'');	
							}
						}else{
							$session->set('se_'.$f->id,'');	
						}						
					}
										
				}

					if($sf_count>0){
						$search_fields = '('.substr($search_fields, 0, -6);
						$search_fields .= ' ) AS f GROUP BY f.item_id ) f WHERE f.c = '.$sf_count.' ) sf, ';	
					}else{
						$search_fields = '';
					}
					
					//print_r($search_fields);die();

					
					return $search_fields;
				
	}

	function resetSearchFilters(){
		$session = &JFactory::getSession();								
			
		$db= &JFactory::getDBO();
		$query = "SELECT f.* FROM #__djcf_fields f "
				."WHERE f.published=1";
		$db->setQuery($query);
		$fields=$db->loadObjectList();
		
			foreach($fields as $f){
				if($f->search_type=='select_min_max' || $f->search_type=='inputbox_min_max'){
					$session->set('se_'.$f->id.'_min','');
					$session->set('se_'.$f->id.'_max','');
				}else{
					$session->set('se_'.$f->id,'');															
				}									
			}

		return null; 
		
	}
	
	
}

