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

function DJClassifiedsBuildRoute(&$query){

	$segments = array();
	if(isset($query['view'])){
			if($query['view']=='item'){
				$segments[]='ad';
				unset($query['view']);
				if(isset($query['cid'])){
					$db	=& JFactory::getDBO();
					$q = "SELECT id,name FROM #__djcf_categories WHERE id=".$query['cid']." LIMIT 1" ; 
					$db->setQuery($q);
					$cc = $db->loadObject();	
					if(!isset($cc)){
                        $segments[] = '0';
                    }else{
                        $cc->name = strtolower($cc->name);
                        $cc->name = strip_tags($cc->name);
						$cc->name = JFilterOutput::stringURLSafe($cc->name);
                        $c=str_ireplace(' ', '_', $cc->name);                    
                        $segments[] = $c.','.$query['cid'];                        
                    } 
					unset($query['cid']);
				}
				if(isset($query['id'])){
										$db	=& JFactory::getDBO();
					$q = "SELECT id,name FROM #__djcf_items WHERE id=".$query['id']." LIMIT 1" ; 
					$db->setQuery($q);
					$cc = $db->loadObject();
					if($cc){
						$cc->name = strtolower($cc->name);
						$cc->name = strip_tags($cc->name);
						//$cc->name = preg_replace('/[^a-z0-9_ ]/i', '', $cc->name);
						$cc->name = JFilterOutput::stringURLSafe($cc->name);
						$c=str_ireplace(' ', '_', $cc->name);						
					}else{
						$c='';
					}
					
					
					$segments[] = $c.','.$query['id'];	
					unset($query['id']);
				}
			}
			elseif($query['view']=='items'){
				$segments[]='ads';
				unset($query['view']);				
				if(isset($query['cid'])){
					$db	=& JFactory::getDBO();
					$q = "SELECT id,name FROM #__djcf_categories WHERE id=".$query['cid']." LIMIT 1" ; 
					$db->setQuery($q);
					$cc = $db->loadObject();
					if(!isset($cc)){
                        $segments[] = '0';
                    }else{
                        $cc->name = strtolower($cc->name);
                        $cc->name = strip_tags($cc->name);
						$cc->name = JFilterOutput::stringURLSafe($cc->name);
                        $c=str_ireplace(' ', '_', $cc->name);                    
                        $segments[] = $c.','.$query['cid'];                        
                    }  
					unset($query['cid']);
				}
				else {
					$segments[] = '0';
				}
				if(isset($query['order'])){
					$segments[] = $query['order'];
					unset($query['order']);
				}					
			}elseif($query['view']=='edititem'){
				$segments[]='edititem';
				unset($query['view']);				
				if(isset($query['id'])){
					$segments[] = $query['id'];
					unset($query['id']);
				}				
			}elseif($query['view']=='additem'){
				$segments[]='additem';
				unset($query['view']);							
			}elseif($query['view']=='useritems'){
				$segments[]='useritems';
				unset($query['view']);							
			}			
	}

	return $segments;
}

function DJClassifiedsParseRoute(&$segments){
$query=array();

		if($segments[0]){
			if($segments[0]=='ad'){
				$query['view']='item';
				if(isset($segments[1])){
					$cids = explode(',', $segments[1]);
					$query['cid']=end($cids);
				}
				if(isset($segments[2])){
					$ids = explode(',', $segments[2]);
					$query['id']=end($ids);
				}
			}
			elseif($segments[0]=='ads'){
				$query['view']='items';
				if(isset($segments[1])){
					$cids = explode(',', $segments[1]);
					$query['cid']=end($cids);
				}
				if(isset($segments[2])){
					$query['order']=$segments[2];
				}
			}elseif($segments[0]=='edititem'){
				$query['view']='edititem';
				if(isset($segments[1])){
					$query['id']=$segments[1];
				}
			}elseif($segments[0]=='additem'){
				$query['view']='additem';
			}elseif($segments[0]=='useritems'){
				$query['view']='useritems';
			}
		}
	return $query;
}

?>