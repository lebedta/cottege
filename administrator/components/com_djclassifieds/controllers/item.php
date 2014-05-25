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

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');
jimport( 'joomla.database.table' );


class DJClassifiedsControllerItem extends JController {
	
	public function getModel($name = 'Item', $prefix = 'DJClassifiedsModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}
	
	public function getTable($type = 'Items', $prefix = 'DJClassifiedsTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	function __construct($default = array ())
    {
        parent::__construct($default);
        $this->registerTask('apply', 'save');
		$this->registerTask('save2new', 'save');
		$this->registerTask('edit', 'add');
    }
	
	
	function display($cachable = false){
        JRequest::setVar('view', JRequest::getCmd('view', 'item'));
        parent::display($cachable);
        }
	
	public function add(){
		//$data = JFactory::getApplication();		
		JRequest::setVar('view','item');
		parent::display();
	}
	
	public function cancel() {
		$app	= JFactory::getApplication();
		$app->redirect( 'index.php?option=com_djclassifieds&view=items' );
	}
	public function getCities(){
		 $region_id = JRequest::getVar('r_id', '0', '', 'int');
	     
	     $db = & JFactory::getDBO();
	     $query ="SELECT r.name as text, r.id as value "
	     		."FROM #__djcf_regions r WHERE r.parent_id = ".$region_id;			
	     $db->setQuery($query);
		 $cities =$db->loadObjectList();
		 
		 echo '<select name="city" class="inputbox" >';
		 echo '<option value="">'.JText::_('COM_DJCLASSIFIEDS_SELECT_CITY').'</option>';
		    echo JHtml::_('select.options', $cities, 'value', 'text', '');
		 echo '</select>';
		 die();
	}
	public function getFields(){
		global $mainframe;
	     $cid = JRequest::getVar('cat_id', '0', '', 'int');
		 $id = JRequest::getVar('id', '0', '', 'int');
		// echo $id; 
	     $db = & JFactory::getDBO();
	     $query ="SELECT f.*, v.value, fx.ordering FROM #__djcf_fields f, #__djcf_fields_xref fx "
		 		."LEFT JOIN (SELECT * FROM #__djcf_fields_values WHERE item_id=".$id.") v "
				."ON v.field_id=fx.field_id "
		 		."WHERE f.id=fx.field_id AND fx.cat_id  = ".$cid." AND f.published=1 ORDER BY fx.ordering";
	     $db->setQuery($query);
		 $fields_list =$db->loadObjectList();
		 //echo '<pre>'; print_r($db);print_r($fields_list);die(); 
		 
		 
		 if(count($fields_list)==0){
		 	echo JText::_('COM_DJCLASSIFIEDS_NO_EXTRA_FIELDS_FOR_CAT');die();
		 }else{
		 		//echo '<pre>';	print_r($fields_list);echo '</pre>';		 	
		 	foreach($fields_list as $fl){
				if($fl->type=="inputbox"){
			 		echo '<div style="margin:2px 0;" >';
						echo '<span style="text-align:right;display:inline-block;width:100px;margin:5px 10px 5px 0;float:left;" class="label">'.$fl->label.'</span>';
						echo '<input class="inputbox" type="text" name="'.$fl->name.'" '.$fl->params; 
						if($id>0){
							echo ' value="'.$fl->value.'" '; 	
						}else{
							echo ' value="'.$fl->default_value.'" ';
						}
						echo ' />';

					echo '<div style="clear:both"></div></div>';					
				}else if($fl->type=="textarea"){
					echo '<div style="margin:2px 0;">';
						echo '<span style="text-align:right;display:inline-block;width:100px;margin:5px 10px 5px 0;float:left;" class="label">'.$fl->label.'</span>';
						echo '<textarea name="'.$fl->name.'" '.$fl->params.' />'; 
						if($id>0){
							echo $fl->value; 	
						}else{
							echo $fl->default_value;
						}
						echo '</textarea>';

					echo '<div style="clear:both"></div></div>';					
				}else if($fl->type=="selectlist"){
					echo '<div style="margin:2px 0;">';
						echo '<span style="text-align:right;display:inline-block;width:100px;margin:5px 10px 5px 0;float:left;" class="label">'.$fl->label.'</span>';
						echo '<select name="'.$fl->name.'" '.$fl->params.' >';
							$val = explode(';', $fl->values);
								if($id>0){
									$def_value=$fl->value; 	
								}else{
									$def_value=$fl->default_value;
								}
						//		print_r($fl);die();
							for($i=0;$i<count($val);$i++){
								if($def_value==$val[$i]){
									$sel="selected";
								}else{
									$sel="";
								}
								echo '<option '.$sel.' value="'.$val[$i].'">'.$val[$i].'</option>';
							}
							
						echo '</select>';

					echo '<div style="clear:both"></div></div>';					
				}else if($fl->type=="radio"){
					echo '<div style="margin:2px 0;">';
						echo '<span style="text-align:right;display:inline-block;width:100px;margin:5px 10px 5px 0;float:left;" class="label">'.$fl->label.'</span>';						
						$val = explode(';', $fl->values);
						echo '<div class="radiofield_box" style="float:left">';
							for($i=0;$i<count($val);$i++){
								$checked = '';
								if($id>0){
									if($fl->value == $val[$i]){
										$checked = 'CHECKED';
									}									 	
								}else{
									if($fl->default_value == $val[$i]){
										$checked = 'CHECKED';
									}						
								}
								
								echo '<div style="float:left;"><input type="radio" '.$checked.' value ="'.$val[$i].'" name="'.$fl->name.'" /><span class="radio_label" style="float:left; margin:5px 10px 0 0;">'.$val[$i].'</span></div>';
								echo '<div style="clear:both"></div>';
							}	
						echo '</div>';	
						echo '<div style="clear:both"></div>';			
					echo '</div>';	
				}else if($fl->type=="checkbox"){
					echo '<div style="margin:2px 0;">';
						echo '<span style="text-align:right;display:inline-block;width:100px;margin:5px 10px 5px 0;float:left;" class="label">'.$fl->label.'</span>';						
						$val = explode(';', $fl->values);
						echo '<div class="radiofield_box" style="float:left">';
							for($i=0;$i<count($val);$i++){
								$checked = '';
								if($id>0){									
									if(strstr($fl->value,';'.$val[$i].';' )){
										$checked = 'CHECKED';
									}									 	
								}else{
									$def_val = explode(';', $fl->default_value);
									for($d=0;$d<count($def_val);$d++){
										if($def_val[$d] == $val[$i]){
											$checked = 'CHECKED';
										}											
									}
					
								}
								
								echo '<div style="float:left;"><input type="checkbox" '.$checked.' value ="'.$val[$i].'" name="'.$fl->name.'[]" /><span class="radio_label" style="float:left; margin:5px 10px 0 0;">'.$val[$i].'</span></div>';
								echo '<div style="clear:both"></div>';
							}	
						echo '</div>';	
						echo '<div style="clear:both"></div>';			
					echo '</div>';	
				}

		 	}		 				
		 	die();
	 	}	
	}	
	
	public function save(){
    	$app = JFactory::getApplication();
		
		$model = $this->getModel('item');
		$row = &JTable::getInstance('Items', 'DJClassifiedsTable');
		
		$par = &JComponentHelper::getParams( 'com_djclassifieds' );
				
    	$row->bind(JRequest::get('post'));
    	
		    $row->description = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);
		    $row->intro_desc = JRequest::getVar('intro_desc', '', 'post', 'string', JREQUEST_ALLOWRAW);
		    $row->contact = nl2br(JRequest::getVar('contact', '', 'post', 'string'));
			
			$images = $row->image_url;
			if(isset($_POST['del_image'])){
			    $del_image = $_POST['del_image'];    
				$path = JPATH_BASE."/../components/com_djclassifieds/images/";
			
			    for ($i = 0; $i < count($del_image); $i++){
			        $images = str_replace($del_image[$i].';', '', $images);
			        //deleting the main image
			        if (JFile::exists($path.$del_image[$i])){
			            JFile::delete($path.$del_image[$i]);
			        }
			        //deleting thumbnail of image
			        if (JFile::exists($path.$del_image[$i].'.thb.jpg')){
			            JFile::delete($path.$del_image[$i].'.thb.jpg');
			        }
					if (JFile::exists($path.$del_image[$i].'.th.jpg')){
			            JFile::delete($path.$del_image[$i].'.th.jpg');
			        }
					if (JFile::exists($path.$del_image[$i].'.thm.jpg')){
			            JFile::delete($path.$del_image[$i].'.thm.jpg');
			        }		
			        //deleting main image
			        if (JFile::exists($path.$del_image[$i].'.ths.jpg')){
			            JFile::delete($path.$del_image[$i].'.ths.jpg');
			        }
			    }
		    }
		 
		    //add images
		    $new_files = $_FILES['image'];
			if(count($new_files['name'])>0 && $row->id==0){			
				$query = "SELECT id FROM #__djcf_items ORDER BY id DESC LIMIT 1";
				$db =& JFactory::getDBO();		
				$db->setQuery($query);
				$last_id =$db->loadResult();
				$last_id++;
			}else{
				$last_id= $row->id;
			}
			
			$nw = (int)$par->get('th_width',-1);
		    $nh = (int)$par->get('th_height',-1);
			$nws = (int)$par->get('smallth_width',-1);
		    $nhs = (int)$par->get('smallth_height',-1);
			$nwm = (int)$par->get('middleth_width',-1);
		    $nhm = (int)$par->get('middleth_height',-1);
			$nwb = (int)$par->get('bigth_width',-1);
		    $nhb = (int)$par->get('bigth_height',-1);			
				
			
		    for ($i = 0; $i < count($new_files['name']); $i++){
		        if (substr($new_files['type'][$i], 0, 5) == "image"){
		        	
		        	$n_name = $last_id.'_'.str_ireplace(' ', '_',$new_files['name'][$i]);            	
		            $new_path = JPATH_BASE."/../components/com_djclassifieds/images/".$n_name;
					$nimg= 0;			
					while(JFile::exists($new_path)){
						$nimg++;
		        		$n_name = $last_id.'_'.$nimg.'_'.$new_files['name'][$i];            	
		            	$new_path = JPATH_BASE."/../components/com_djclassifieds/images/".$n_name;
					}
					$images .= $n_name.';';
		            move_uploaded_file($new_files['tmp_name'][$i], $new_path);
		            DJClassifiedsImage::makeThumb($new_path, $nw, $nh, 'th');
					 	DJClassifiedsImage::makeThumb($new_path, $nws, $nhs, 'ths');
						DJClassifiedsImage::makeThumb($new_path, $nwm, $nhm, 'thm');
						DJClassifiedsImage::makeThumb($new_path, $nwb, $nhb, 'thb');
		        }
		    }
		    $row->image_url = $images;
			
				//$exp_date = explode('-', $_POST['date_expir']);
				//$exp_time = explode(':', $_POST['time_expir']);
			//$row->date_exp = mktime($exp_time[0],$exp_time[1],0,$exp_date[1],$exp_date[2],$exp_date[0]);
			$row->date_exp = $_POST['date_expir'].' '.$_POST['time_expir'].':00';
			
			if($row->user_id==0){
				$user=JFactory::getUser();
				$row->user_id = $user->id;
			}
			
				$row->region_id= end($_POST['regions']);
				if(!$row->region_id){
					$row->region_id =$_POST['regions'][count($_POST['regions'])-2];
				}
		
		
			$db = & JFactory::getDBO();
			if($row->id>0){	
				$query = "DELETE FROM #__djcf_fields_values WHERE item_id= ".$row->id." ";
    			$db->setQuery($query);
    			$db->query();

				if($row->payed==1){
					$row->pay_type='';
					$query = "UPDATE #__djcf_payments SET status='Completed' WHERE item_id= ".$row->id." ";
	    			$db->setQuery($query);
	    			$db->query();	
				}
			}
	
		$row->promotions='';		
		$query = "SELECT p.* FROM #__djcf_promotions p WHERE p.published=1 ORDER BY p.id ";	
		$db->setQuery($query);
		$promotions=$db->loadObjectList();
			foreach($promotions as $prom){
				if(JRequest::getVar($prom->name,'0')){
					$row->promotions .=$prom->name.',';
				}
			}
		if($row->promotions){
			$row->promotions = substr($row->promotions, 0,-1);
		}
		
		if(strstr($row->promotions, 'p_first')){
			$row->special = 1;
		}else{
			$row->special = 0;
		}
		//echo '<pre>';print_r($_POST);print_r($row);echo '</pre>';die(); 
		
		if (!$row->store())
    	{
			echo $row->getError();
        	exit ();	
    	}
		
		  			
		 $query ="SELECT f.* FROM #__djcf_fields f, #__djcf_fields_xref fx "
		 		."WHERE f.id=fx.field_id AND fx.cat_id  = ".$row->cat_id." ";
	     $db->setQuery($query);
		 $fields_list =$db->loadObjectList();
		// echo '<pre>'; print_r($db);print_r($fields_list);die();
		 
		 if(count($fields_list)>0){
			$query = "INSERT INTO #__djcf_fields_values(`field_id`,`item_id`,`value`) VALUES ";
			$ins=0;
				foreach($fields_list as $fl){
					if($fl->type=='checkbox'){
						if(isset($_POST[$fl->name])){
							$field_v = $_POST[$fl->name];
							$f_value=';';
								for($fv=0;$fv<count($field_v);$fv++){
									$f_value .=$field_v[$fv].';'; 
								}

							$query .= "('".$fl->id."','".$row->id."','".$f_value."'), ";
							$ins++;	
						}
					}else{					
						if(isset($_POST[$fl->name])){							
							$query .= "('".$fl->id."','".$row->id."','".$_POST[$fl->name]."'), ";
							$ins++;	
						}
					}
				}
			}
		 //print_r($query);die();
			if($ins){
				$query = substr($query, 0, -2).';';
				$db->setQuery($query);
    			$db->query();	
			}

			
    	switch(JRequest::getVar('task'))
    	{
	        case 'apply':
            	$link = 'index.php?option=com_djclassifieds&task=item.edit&id='.$row->id;
            	$msg = JText::_('COM_DJCLASSIFIEDS_ITEM_SAVED');
            	break;
			case 'save2new':
            	$link = 'index.php?option=com_djclassifieds&task=item.add';
            	$msg = JText::_('COM_DJCLASSIFIEDS_ITEM_SAVED');
            	break;				
        	case 'saveItem':
        	default:
	            $link = 'index.php?option=com_djclassifieds&view=items';
            	$msg = JText::_('COM_DJCLASSIFIEDS_ITEM_SAVED');
            	break;
    	}

    	$app->redirect($link, $msg);
	
	}
	
	
}

?>