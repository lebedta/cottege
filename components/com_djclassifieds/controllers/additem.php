<?php
/**
* @version 2.0
* @package DJ Classifieds
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

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');


class DJClassifiedsControllerAddItem extends JController {
	
	
	function captcha(){
		require_once(JPATH_COMPONENT.DS.'assets'.DS.'recaptchalib.php');
		$app	= JFactory::getApplication();
		
		$privatekey = "6LfzhgkAAAAAAOJNzAjPz3vXlX-Bw0l-sqDgipgs";

		  $resp = recaptcha_check_answer ($privatekey,
                                  $_SERVER["REMOTE_ADDR"],
                                  $_POST["recaptcha_challenge_field"],
                                  $_POST["recaptcha_response_field"]);
		  if ($resp->is_valid) {
			$session = &JFactory::getSession();		
			$session->set('captcha_sta','1');				
			$message = '';	
		  }else {								
			$message = JText::_("COM_DJCLASSIFIEDS_INVALID_CODE");			
		  }
		  	$redirect= 'index.php?option=com_djclassifieds&view=additem' ;
			$app->redirect($redirect,$message,'error');	
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
		 $user = & JFactory::getUser();
			 if($user->id==0){
			 	$id=0;
			 }
	     $db = & JFactory::getDBO();
		 if($id>0){
		 	$query = "SELECT user_id FROM #__djcf_items WHERE id='".$id."' LIMIT 1";
		 	$db->setQuery($query);
		 	$item_user_id =$db->loadResult();	
			if($item_user_id!=$user->id){
				$id=0;
			}
		 }
	     $query ="SELECT f.*, v.value, fx.ordering FROM #__djcf_fields f, #__djcf_fields_xref fx "
		 		."LEFT JOIN (SELECT * FROM #__djcf_fields_values WHERE item_id=".$id.") v "
				."ON v.field_id=fx.field_id "
		 		."WHERE f.id=fx.field_id AND fx.cat_id  = ".$cid." AND f.published=1 ORDER BY fx.ordering";
	     $db->setQuery($query);
		 $fields_list =$db->loadObjectList();
		 //echo '<pre>'; print_r($db);print_r($fields_list);die(); 
		 
		 
		 if(count($fields_list)==0){
		 	die();
		 }else{
		 		//echo '<pre>';	print_r($fields_list);echo '</pre>';						 	
		 	foreach($fields_list as $fl){
		 		echo '<div class="djform_row">';

				if($fl->type=="inputbox"){
						if($id>0){
							$fl_value = $fl->value; 	
						}else{
							$fl_value = $fl->default_value;
						}						
						
						$val_class='';
						$req = '';
						if($fl->required){
							if($fl_value==''){
								$val_class=' aria-required="true" ';			
							}else{
								$val_class=' aria-invalid="false" ';
							}
							$cl = 'class="inputbox required" '.$val_class.' required="required"';
							$req = ' * ';
						}else{
							$cl = 'class="inputbox"';
						}
						
						echo '<label class="label" for="dj'.$fl->name.'" id="dj'.$fl->name.'-lbl" >'.$fl->label.$req.'</label>';
						echo '<div class="djform_field">';															

						echo '<input '.$cl.' type="text" id="dj'.$fl->name.'" name="'.$fl->name.'" '.$fl->params; 
						echo ' value="'.$fl_value.'" '; 	
						echo ' />';					
				}else if($fl->type=="textarea"){
						if($id>0){
							$fl_value = $fl->value; 	
						}else{
							$fl_value = $fl->default_value;
						}						
						
						$val_class='';
						$req = '';
						if($fl->required){
							if($fl_value==''){
								$val_class=' aria-required="true" ';			
							}else{
								$val_class=' aria-invalid="false" ';
							}
							$cl = 'class="inputbox required" '.$val_class.' required="required"';
							$req = ' * ';
						}else{
							$cl = 'class="inputbox"';
						}
					
						echo '<label class="label" for="dj'.$fl->name.'" id="dj'.$fl->name.'-lbl">'.$fl->label.$req.'</label>';
						echo '<div class="djform_field">';
						echo '<textarea '.$cl.' id="dj'.$fl->name.'" name="'.$fl->name.'" '.$fl->params.' />'; 
						echo $fl_value; 	
						echo '</textarea>';					
				}else if($fl->type=="selectlist"){
						if($id>0){
							$fl_value=$fl->value; 	
						}else{
							$fl_value=$fl->default_value;
						}
			
						$val_class='';
						$req = '';
						if($fl->required){
							if($fl_value==''){
								$val_class=' aria-required="true" ';			
							}else{
								$val_class=' aria-invalid="false" ';
							}
							$cl = 'class="inputbox required" '.$val_class.' required="required"';
							$req = ' * ';
						}else{
							$cl = 'class="inputbox"';
						}
					
								
						echo '<label class="label" for="dj'.$fl->name.'" id="dj'.$fl->name.'-lbl">'.$fl->label.$req.'</label>';
						echo '<div class="djform_field">';						
						echo '<select '.$cl.' id="dj'.$fl->name.'" name="'.$fl->name.'" '.$fl->params.' >';
							if(substr($fl->values, -1)==';'){
								$fl->values = substr($fl->values, 0,-1);
							}
							$val = explode(';', $fl->values);
							for($i=0;$i<count($val);$i++){
								if($fl_value==$val[$i]){
									$sel="selected";
								}else{
									$sel="";
								}
								echo '<option '.$sel.' value="'.$val[$i].'">'.$val[$i].'</option>';
							}
							
						echo '</select>';					
				}else if($fl->type=="radio"){				
						if($id>0){
							$fl_value=$fl->value; 	
						}else{
							$fl_value=$fl->default_value;
						}
			
						$val_class='';
						$req = '';
						if($fl->required){
							if($fl_value==''){
								$val_class=' aria-required="true" ';			
							}else{
								$val_class=' aria-invalid="false" ';
							}
							$cl = 'class="required validate-radio" '.$val_class.' required="required"';
							$req = ' * ';
						}else{
							$cl = 'class=""';
						}
						
						echo '<label class="label" for="dj'.$fl->name.'" id="dj'.$fl->name.'-lbl">'.$fl->label.$req.'</label>';
						echo '<div class="djform_field">';		
							if(substr($fl->values, -1)==';'){
								$fl->values = substr($fl->values, 0,-1);
							}
						$val = explode(';', $fl->values);
						echo '<div class="radiofield_box" style="float:left">';
							for($i=0;$i<count($val);$i++){
								$checked = '';
									if($fl_value == $val[$i]){
										$checked = 'CHECKED';
									}									 	
								
								echo '<div style="float:left;"><input type="radio" '.$cl.'  '.$checked.' value ="'.$val[$i].'" name="'.$fl->name.'" /><span class="radio_label">'.$val[$i].'</span></div>';
								echo '<div style="clear:both"></div>';
							}	
						echo '</div>';	
				}else if($fl->type=="checkbox"){
						echo '<label class="label" for="dj'.$fl->name.'" id="dj'.$fl->name.'-lbl">'.$fl->label.'</label>';
						echo '<div class="djform_field">';	
							if(substr($fl->values, -1)==';'){
								$fl->values = substr($fl->values, 0,-1);
							}							
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
								
								echo '<div style="float:left;"><input type="checkbox" '.$checked.' value ="'.$val[$i].'" name="'.$fl->name.'[]" /><span class="radio_label">'.$val[$i].'</span></div>';
								echo '<div style="clear:both"></div>';
							}	
						echo '</div>';	
				}
				echo '</div><div style="clear:both"></div>';			
				echo '</div>';	
		 	}		 				
		 	die();
	 	}	
	}	
	
	function save(){
		$app = JFactory::getApplication();
		JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables');		
		jimport( 'joomla.database.table' );

    	$row = &JTable::getInstance('Items', 'DJClassifiedsTable');
		$par = &JComponentHelper::getParams( 'com_djclassifieds' );
		$user = & JFactory::getUser();
				
		$db =& JFactory::getDBO();
		$id = JRequest::getVar('id', 0, '', 'int' );
		$redirect = '';

			$menus	= JSite::getMenu();
			$menu_item = $menus->getItems('link','index.php?option=com_djclassifieds&view=items',1);
			
			$itemid = ''; 
			if($menu_item){
				$itemid='&Itemid='.$menu_item->id;
			}

		

		if($user->id==0 && $id>0){		 	
			$message = JText::_('COM_DJCLASSIFIEDS_WRONG_AD');
			$redirect="index.php?option=com_djclassifieds&view=items&cid=0".$itemid;
			$app->redirect($redirect, $message,'error');			
		}
		
	     $db = & JFactory::getDBO();
		 if($id>0){
		 	$query = "SELECT user_id FROM #__djcf_items WHERE id='".$id."' LIMIT 1";
		 	$db->setQuery($query);
		 	$item_user_id =$db->loadResult();	
			if($item_user_id!=$user->id){
				$message = JText::_('COM_DJCLASSIFIEDS_WRONG_AD');
				$redirect="index.php?option=com_djclassifieds&view=items&cid=0".$itemid;
				$app->redirect($redirect, $message,'error');
			}
		 }
		
		
		$row->bind(JRequest::get('post'));
			
		if((int)$par->get('allow_htmltags','0')){
			$row->description = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);
			
			$allowed_tags = explode(';', $par->get('allowed_htmltags',''));
			$a_tags = '';
			for($a = 0;$a<count($allowed_tags);$a++){				
				$a_tags .= '<'.$allowed_tags[$a].'>';	
			}
			
			$row->description = strip_tags($row->description, $a_tags);
		}else{
			$row->description = nl2br(JRequest::getVar('description', '', 'post', 'string'));
		}
		
		$row->contact = nl2br(JRequest::getVar('contact', '', 'post', 'string'));
   
	//removing images from folder and from database
	$path = JPATH_BASE."/components/com_djclassifieds/images/";
    $images = $row->image_url;
		if(isset($_POST['del_img'])){			
			$del_image = $_POST['del_img'];	
		}else{
			$del_image = array();
		}    
    

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
        if (JFile::exists($path.$del_image[$i].'.ths.jpg')){
            JFile::delete($path.$del_image[$i].'.ths.jpg');
        }
    }

 
    //add images
    $new_files = $_FILES['image'];
    if(count($new_files['name'])>0 && $row->id==0){			
		$query = "SELECT id FROM #__djcf_items ORDER BY id DESC LIMIT 1";
		$db->setQuery($query);
		$last_id =$db->loadResult();
		$last_id++;
	}else{
		$last_id= $row->id;
	}
	
	$nw = (int)$par->get('th_width',-1);
    $nh = (int)$par->get('th_height',-1);
	$nws = $par->get('smallth_width',-1);
    $nhs = $par->get('smallth_height',-1);
	$nwm = $par->get('middleth_width',-1);
    $nhm = $par->get('middleth_height',-1);			
	$nwb = $par->get('bigth_width',-1);
    $nhb = $par->get('bigth_height',-1);		
	$img_maxsize = $par->get('img_maxsize',0);		
		if($img_maxsize>0){
			$img_maxsize = $img_maxsize*1024*1024;
		}

    for ($i = 0; $i < count($new_files['name']); $i++)
    {
        if (substr($new_files['type'][$i], 0, 5) == "image")
        {
   			if($img_maxsize>0 && $new_files['size'][$i]>$img_maxsize){
   				$app->enqueueMessage(JText::_('COM_DJCLASSIFIEDS_TO_BIG_IMAGE').' : \''.$new_files['name'][$i].'\'','error');
				continue;
			}
			$n_name = $last_id.'_'.$new_files['name'][$i];            	
        	$new_path = JPATH_BASE."/components/com_djclassifieds/images/".$n_name;
			$nimg= 0;			
			while(JFile::exists($new_path)){
				$nimg++;
    			$n_name = $last_id.'_'.$nimg.'_'.$new_files['name'][$i];            	
        		$new_path = JPATH_BASE."/components/com_djclassifieds/images/".$n_name;
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
	$duration_price =0;
		if($row->id==0){
			$exp_days = JRequest::getVar('exp_days', $par->get('exp_days'), '', 'int' );
			if($par->get('durations_list','')){
				$query = "SELECT price FROM #__djcf_days WHERE days = ".$exp_days;	
				$db->setQuery($query);
				$duration_price = $db->loadResult(); 
			}												
			$row->date_exp = date("Y-m-d G:i:s",mktime(date("G"), date("i"), date("s"), date("m")  , date("d")+$exp_days, date("Y")));
			$row->exp_days = $exp_days;
		}

		$row->cat_id= end($_POST['cats']);
		if(!$row->cat_id){
			$row->cat_id =$_POST['cats'][count($_POST['cats'])-2];
		}	
		$row->cat_id = str_ireplace('p', '', $row->cat_id);
		$row->region_id= end($_POST['regions']);
		if(!$row->region_id){
			$row->region_id =$_POST['regions'][count($_POST['regions'])-2];
		}
		
		$row->user_id = $user->id;
				
		$row->promotions='';
		if($par->get('promotion','1')=='1'){
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
		} 

		if(strstr($row->promotions, 'p_first')){
			$row->special = 1;
		}else{
			$row->special = 0;
		}

		$query = "SELECT price,autopublish FROM #__djcf_categories WHERE id = ".$row->cat_id;	
		$db->setQuery($query);
		$cat = $db->loadObject();
		
		if($row->id>0){	
			$query = "SELECT cat_id, special,pay_type,payed,exp_days,promotions FROM #__djcf_items WHERE id = ".$row->id;			
			$db->setQuery($query);
			$old_row = $db->loadObject();
		
			$query = "DELETE FROM #__djcf_fields_values WHERE item_id= ".$row->id." ";
	    	$db->setQuery($query);
	    	$db->query();	
			
			$row->payed = $old_row->payed;
			$row->pay_type = $old_row->pay_type;
			$row->exp_days = $old_row->exp_days;
		}

		  	 if($cat->autopublish=='0'){
				if($par->get('autopublish')=='1'){
					$row->published = 1;
					$message = JText::_('COM_DJCLASSIFIEDS_AD_ADDED_SUCCESSFULLY'); 
				}else{
					$row->published = 0;
					$message = JText::_('COM_DJCLASSIFIEDS_AD_ADDED_SUCCESSFULLY_WAITING_FOR_PUBLISH'); 
					$redirect="index.php?option=com_djclassifieds&view=items&cid=0".$itemid;					
				}
			 }elseif($cat->autopublish=='1'){
				$row->published = 1;
				$message = JText::_('COM_DJCLASSIFIEDS_AD_ADDED_SUCCESSFULLY'); 
			 }elseif($cat->autopublish=='2'){
				$row->published = 0;
				$message = JText::_('COM_DJCLASSIFIEDS_AD_ADDED_SUCCESSFULLY_WAITING_FOR_PUBLISH');
				$redirect="index.php?option=com_djclassifieds&view=items&cid=0".$itemid;
			 }

			$pay_redirect=0;
			$row->pay_type='';
			//echo '<pre>';print_r($old_row);print_r($row);die();
			if(isset($old_row)){				
				if($cat->price==0 && $row->promotions==''){
					$row->payed = 1;
					$row->pay_type ='';					
				}else if(($old_row->cat_id!=$row->cat_id && $cat->price>0) || ($old_row->promotions!=$row->promotions)){							
					$row->pay_type = '';
					if($old_row->cat_id!=$row->cat_id && $cat->price>0){
						$row->pay_type = 'cat,';
					}
					if($old_row->promotions!=$row->promotions){						
						$prom_new = explode(',', $row->promotions);
						for($pn=0;$pn<count($prom_new);$pn++){
							if(!strstr($old_row->promotions, $prom_new[$pn])){
								$row->pay_type .= $prom_new[$pn].',';		
							}	
						}
						
					}
					if($row->pay_type){
						$row->published = 0;
						$row->payed = 0;
						$pay_redirect=1;						
					}										
				}else if($row->payed==0 && ($cat->price>0 || $row->promotions!='')){
					$row->payed = 0;
					$row->published = 0;
					$pay_redirect=1;
				}
				
			}else if($cat->price>0 || $row->promotions!='' || $duration_price>0){												
				if($cat->price>0){
					$row->pay_type .= 'cat,';
				}				
				if($duration_price>0){
					$row->pay_type .= 'duration,';
				}
				if($row->promotions!=''){
					$row->pay_type .= $row->promotions;
				}
				$row->published = 0;
				$row->payed = 0;
				$pay_redirect=1;				
			}else{
				$row->payed = 1;
				$row->pay_type = '';
			}
	
				
		//echo '<pre>';print_r($row);die();echo '</pre>';
		if (!$row->store()){
			//echo $row->getError();exit ();	
    	}
			 
		$query ="SELECT f.* FROM #__djcf_fields f, #__djcf_fields_xref fx "
		 		."WHERE f.id=fx.field_id AND fx.cat_id  = ".$row->cat_id." ";
	     $db->setQuery($query);
		 $fields_list =$db->loadObjectList();
		// echo '<pre>'; print_r($db);print_r($fields_list);die();
		 $ins=0;		 
		 if(count($fields_list)>0){
			$query = "INSERT INTO #__djcf_fields_values(`field_id`,`item_id`,`value`) VALUES ";
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
			if($ins>0){
				$query = substr($query, 0, -2).';';
				$db->setQuery($query);
    			$db->query();	
			}
		
		if($par->get('notify_admin','0') && $par->get('notify_user_email','')!=''){
			if($par->get('notify_admin','0')==1){
				DJClassifiedsNotify::notifyAdmin($row->id);	
			}else if($par->get('notify_admin','0')==2 && $id==0){
				DJClassifiedsNotify::notifyAdmin($row->id);	
			}
			
		}					 

		if($pay_redirect==1){
			$redirect= 'index.php?option=com_djclassifieds&view=payment&id='.$row->id.$itemid;
			$message=JTExt::_('COM_DJCLASSIFIEDS_AD_ADDED_SUCCESSFULLY_CHOOSE_PAYMENT');
		}
	
		if(!$redirect){
			$redirect= 'index.php?option=com_djclassifieds&view=item&cid='.$row->cat_id.'&id='.$row->id.$itemid;	
		}

		$app->redirect($redirect, $message);

	}
}

?>