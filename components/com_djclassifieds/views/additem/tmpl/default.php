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

//jimport('joomla.media.images');
JHTML::_('behavior.Mootools' );
JHTML::_('behavior.formvalidation');
JHTML::_('behavior.modal');
$par = &JComponentHelper::getParams( 'com_djclassifieds' );

$imglimit = $par->get('img_limit','3');
$unit_price = $par->get('unit_price','');	
$id = JRequest::getVar('id', 0, '', 'int' );
$user = JFactory::getUser();

$mod_attribs=array();
$mod_attribs['style'] = 'xhtml';

$toolTipArray = array('className'=>'djcf_prom');
JHTML::_('behavior.tooltip', '.Tips1', $toolTipArray);
?>
<div id="dj-classifieds" class="clearfix">
	<?php 
		$modules_djcf = &JModuleHelper::getModules('djcf-top');			
		if(count($modules_djcf)>0){
			echo '<div class="djcf-ad-top clearfix">';
			foreach (array_keys($modules_djcf) as $m){
				echo JModuleHelper::renderModule($modules_djcf[$m],$mod_attribs);
			}
			echo'</div>';		
		}		
	
		$modules_djcf = &JModuleHelper::getModules('djcf-additem-top');			
		if(count($modules_djcf)>0){
			echo '<div class="djcf-ad-items-top clearfix">';
			foreach (array_keys($modules_djcf) as $m){
				echo JModuleHelper::renderModule($modules_djcf[$m],$mod_attribs);
			}
			echo'</div>';		
		}	
	?>	
	
<div class="dj-additem clearfix" >
<form action="index.php" method="post" class="form-validate" name="djForm" id="djForm"  enctype="multipart/form-data">
        <div class="additem_djform">
        
		    <div class="title_top"><?php 
				if(JRequest::getVar('id', 0, '', 'int' )>0){
					echo JText::_('COM_DJCLASSIFIEDS_EDIT_AD');
				}else{
					echo JText::_('COM_DJCLASSIFIEDS_NEW_AD');	
				}
			?></div>
			<div class="additem_djform_in">
        	<center><img src='<?php echo JURI::base() ?>/components/com_djclassifieds/assets/images/long_loader.gif' alt='LOADING' style='display: none;' id='upload_loading' /><div id="alercik"></div></center>
            <div class="djform_row">
                <label class="label" for="name" id="name-lbl">
                    <?php echo JText::_('COM_DJCLASSIFIEDS_TITLE');?> *
                </label>
                <div class="djform_field">
                    <input class="inputbox required" type="text" name="name" id="name" size="50" maxlength="250" value="<?php echo $this->item->name; ?>" />
                </div>
                <div style="clear:both"></div>
            </div>
            <?php /* ?>
           	<div class="djform_row">
                <label class="label" for="sp" id="sp-lbl">
                	<?php $prom_price = $par->get('prom_price');
                    echo JText::_('aa');?>
					
                </label>
                <div class="djform_field">
					<input type="checkbox" class="required validate-checkboxes" name="sp[]" value="1" /><?php echo JText::_('JYES'); ?>
					<input type="checkbox" class="required validate-checkboxes" name="sp[]" value="2" /><?php echo JText::_('JNO'); ?>
					<input type="checkbox" class="required validate-checkboxes" name="sp[]" value="3" /><?php echo JText::_('JNO'); ?>		
                </div>
                <div style="clear:both"></div>
            </div>
            <?php */?>
            <div class="djform_row">
                <label class="label" for="cat_0" id="cat_0-lbl">
                	       <?php echo JText::_('COM_DJCLASSIFIEDS_CATEGORY'); ?> *					
                </label>
                <div class="djform_field">
                    <?php
				$cat_sel = '<select autocomplete="off" class="required" id="cat_0" style="width:210px" name="cats[]" onchange="new_cat(0,this.value);getFields(this.value);"><option value="">'.JText::_('COM_DJCLASSIFIEDS_PLEASE_SELECT_CATEGORY').'</option>';
				$parent_id=0;	
				foreach($this->cats as $l){
					if($parent_id!=$l->parent_id){
						break;
					}	
					if($l->price>0){
						$l->price = $l->price/100;						
						$l->name .= ' ('.$l->price.' '.$unit_price.')'; 
					}
					$cat_sel .= '<option value="'.$l->id.'">'.str_ireplace("'", "&apos;", $l->name).'</option>';
				}
					$cat_sel .= '</select>';
					echo $cat_sel;				
				
				?><div style="clear:both"></div>
				<div id="after_cat_0"></div>
				<script type="text/javascript">
					var cats=new Array();
					
				<?
				$cat_sel = '<select style="width:210px" name="cats[]" id="cat_0" onchange="new_cat(0,this.value);getFields(this.value);">';
				$parent_id=0;	
				
				foreach($this->cats as $l){
					if($parent_id!=$l->parent_id){
						$cat_sel .= '</select>';
						echo "cats[$parent_id]='$cat_sel<div id=\"after_cat_$parent_id\"></div>';";
						$parent_id=$l->parent_id;
						$cat_sel = '<div style="clear:both"></div><select style="width:210px" name="cats[]" id="cat_'.$l->parent_id.'" onchange="new_cat('.$parent_id.',this.value);getFields(this.value);">';
						$cat_sel .= '<option value="p'.$parent_id.'"> - - - </option>';		
					}	
					if($l->price>0){
						$l->price = $l->price/100;						
						$l->name .= ' ('.$l->price.' '.$unit_price.')'; 
					}
					$cat_sel .= '<option value="'.$l->id.'">'.str_ireplace("'", "&apos;", $l->name).'</option>';
				}
				$cat_sel .= '</select>';	
				echo "cats[$parent_id]='$cat_sel<div id=\"after_cat_$parent_id\"></div>';";
				
				?>	
				var current=0;
				
				function new_cat(parent,a_parent){
					if(cats[a_parent]){
						//alert(cats[v]);	
						$('after_cat_'+parent).innerHTML = cats[a_parent]; 
						$('cat_'+parent).value=a_parent;
					}else{
						$('after_cat_'+parent).innerHTML = '';
						$('cat_'+parent).value=a_parent;		
					}
					
				}
				<?php echo $this->cat_path;?>
				</script>
					
                </div>
                <div style="clear:both"></div>
            </div>
            <div class="djform_row extra_fields">        
				<div id="ex_fields"></div>        
                <div style="clear:both"></div>
            </div>   		
            <?php if(count($this->regions) && $par->get('show_regions','1')){?>
    		<div class="djform_row">
               	<label class="label" for="reg_0" id="reg_0-lbl">
                    <?php echo JText::_('COM_DJCLASSIFIEDS_LOCALIZATION'); ?> *		
                </label>
                <div class="djform_field">
                    <?php
						$reg_sel = '<select autocomplete="off" id="reg_0" class="required" style="width:210px" name="regions[]" onchange="new_reg(0,this.value);"><option value="">'.JText::_('COM_DJCLASSIFIEDS_SELECT_LOCALIZATION').'</option>';
						$parent_id=0;	
						foreach($this->regions as $l){
							if($parent_id!=$l->parent_id){
								break;
							}	
							$reg_sel .= '<option value="'.$l->id.'">'.$l->name.'</option>';
							//$ri++;
						}
							$reg_sel .= '</select>';
							echo $reg_sel;
						
						?><div style="clear:both"></div>
						<div id="after_reg_0"></div>
						<script type="text/javascript">
							var regs=new Array();
							
						<?
						$reg_sel = '<select style="width:210px" name="regions[]" id="reg_0" onchange="new_reg(0,this.value);">';
						$parent_id=0;	
						
						foreach($this->regions as $l){
							if($parent_id!=$l->parent_id){
								$reg_sel .= '</select>';
								echo "regs[$parent_id]='$reg_sel<div id=\"after_reg_$parent_id\"></div>';";
								$parent_id=$l->parent_id;
								$reg_sel = '<div style="clear:both"></div><select style="width:210px" name="regions[]" id="reg_'.$l->parent_id.'" onchange="new_reg('.$parent_id.',this.value);">';
								$reg_sel .= '<option value=""> - - - </option>';		
							}	
							$reg_sel .= '<option value="'.$l->id.'">'.$l->name.'</option>';
						}
						$reg_sel .= '</select>';	
						echo "regs[$parent_id]='$reg_sel<div id=\"after_reg_$parent_id\"></div>';";
						
						?>	
						var current=0;
						
						function new_reg(parent,a_parent){
							if(regs[a_parent]){
								//alert(cats[v]);	
								$('after_reg_'+parent).innerHTML = regs[a_parent]; 
								$('reg_'+parent).value=a_parent;
							}else{
								$('after_reg_'+parent).innerHTML = '';
								$('reg_'+parent).value=a_parent;		
							}
							
						}
						<?php echo $this->reg_path;?>
						</script>
							
                </div>
                <div style="clear:both"></div>
            </div>  
            <?php }else{ ?>
            	<input type="hidden" name="regions[]" value="0" />
            <?php } ?>
            <div class="djform_row">
                <label class="label" >
                    <?php echo JText::_('COM_DJCLASSIFIEDS_ADDRESS');?>
                </label>
                <div class="djform_field">
                    <input class="text_area" type="text" name="address" id="address" size="50" maxlength="250" value="<?php echo $this->item->address; ?>" />
                </div>
                <div style="clear:both"></div>
            </div>      
            <?php            
             $exp_days_list = $par->get('exp_days_list','');
			$exp_days = $par->get('exp_days','');
			if($par->get('durations_list','') && $id==0 && count($this->days)){
				//print_r($this->days);die();				
					?>
	    		<div class="djform_row">
	                <label class="label" for="exp_days" id="exp_days-lbl">                	
	                    <?php echo JText::_('COM_DJCLASSIFIEDS_EXPIRE_AFTER');?>				
	                </label>
	                <select id="exp_days" name="exp_days">
					<?php 					
						foreach($this->days as $day){
							echo '<option value="'.$day->days.'"';	
								if($day->days==$exp_days){
									echo ' SELECTED ';	
								}							
								echo '>';
								if($day->days==1){
									echo $day->days.'&nbsp;'.JText::_('COM_DJCLASSIFIEDS_DAY');
								}else{
									echo $day->days.'&nbsp;'.JText::_('COM_DJCLASSIFIEDS_DAYS');	
								} 
								
								if($day->price !='0.00'){
									echo '&nbsp;-&nbsp;'.$day->price.'&nbsp; '.$par->get('unit_price');	
								}							
							echo '</option>';
						}
					?>
					</select>
	                <div style="clear:both"></div>
	            </div>                
            <?php } ?>
    		<div class="djform_row">
                <label class="label" for="intro_desc" id="intro_desc-lbl">                	
                    <?php 
					echo JText::_('COM_DJCLASSIFIEDS_INTRO_DESCRIPTION');?> *
 					<div id="introdesc_limit">(<?php echo $par->get('introdesc_char_limit');?>)</div>					
                </label>
                <div class="djform_field">
		            <textarea id="intro_desc" name="intro_desc" rows="5" cols="55" class="inputbox required" onkeyup="introdescLimit(<?php echo $par->get('introdesc_char_limit');?>);" onkeydown="checkt(<?php echo $par->get('introdesc_char_limit');?>);"><?php echo $this->item->intro_desc; ?></textarea>
                </div>
                <div style="clear:both"></div>
            </div>
    		<div class="djform_row">
                <label class="label" id="description_l">                 	
                    <?php echo JText::_('COM_DJCLASSIFIEDS_DESCRIPTION');?>
                </label>
                <div class="djform_field">
                	<?php 
                	if($par->get('desc_editor','1')){
                		$editor = & JFactory::getEditor('tinymce');
						$p = array('mode'=>'0');		 
						echo $editor->display("description",$this->item->description,'460','250','5','55', false, '', '', '',$p);	
                	}else{
                		$this->item->description = strip_tags($this->item->description); ?>
  				        <textarea id="description" name="description" rows="5" cols="55" class="inputbox" ><?php echo $this->item->description; ?></textarea>
                	<?php }					   
                	?>                  
                </div>
                <div style="clear:both"></div>
            </div>
            <?php if($par->get('show_contact','1')){?>
    		<div class="djform_row">
                <label class="label" for="contact" id="contact-lbl">                   	
                    <?php echo JText::_('COM_DJCLASSIFIEDS_CONTACT');?> *
                </label>
                <div class="djform_field">
		            <textarea id="contact" name="contact" rows="4" cols="55" class="inputbox required"><?php echo $this->item->contact; ?></textarea>                  
                </div>
                <div style="clear:both"></div>
            </div>
            <?php } ?>
    		<div class="djform_row">
                <label class="label" >
                    <?php echo JText::_('COM_DJCLASSIFIEDS_PRICE');?>
                </label>
                <div class="djform_field">
                    <input class="text_area" type="text" name="price" id="price" size="30" maxlength="250" value="<?php echo $this->item->price; ?>" /> <?php echo $par->get('unit_price','EUR'); ?>
                </div>
                <div style="clear:both"></div>
            </div>
			<?php
			$images = array();
			if(JRequest::getVar('id', 0, '', 'int' )>0){
			?>		
    		<div class="djform_row">
                <label class="label" >
                    <?php echo JText::_('COM_DJCLASSIFIEDS_IMAGES');?>
                </label>
                <div class="djform_field">
			<?php
				$images_count = 0;
				if(!$image = $this->item->image_url){
					echo JText::_('COM_DJCLASSIFIEDS_NO_IMAGES_INCLUDED');
				}else{
					echo '<input type="hidden" name="image_url" value="'.$this->item->image_url.'" />';
					$images=explode(';', substr($image,0,-1));
					for($i=0; $i<count($images); $i++){
						?>
						<?php 
					      $img_path= JURI::base().'/components/com_djclassifieds/images/';
						  $img_path .= $images[$i];
						  ?>
						  <img src="<?php echo $img_path;?>.th.jpg"/>
						  <input type="checkbox" name="del_img[]" id="del_img[]" value="<?php echo $images[$i];?>"/>
						  <?php echo JText::_('COM_DJCLASSIFIEDS_CHECK_TO_DELETE'); ?>
						  <br/>
						<?php
					}
					echo '<input type="hidden" id="count_images" value="'.count($images).'">';
				}
				?>
                </div>
                <div style="clear:both"></div>
            </div>
				<?php
			}
			if(count($images)<$imglimit){
			?>
    		<div class="djform_row">
                <label class="label" >

                    <?php echo JText::_('COM_DJCLASSIFIEDS_ADD_IMAGE');
					$img_maxsize = $par->get('img_maxsize',0);	
					if($img_maxsize>0){
						echo '<br />'.JText::_('COM_DJCLASSIFIEDS_MAX_IMAGE_SIZE').': '.$img_maxsize.' MB';
					}
					echo '<br /><span>'.JText::_('COM_DJCLASSIFIEDS_FIRST_IMAGES_IS_MAIN_IMAGE').'</span>';
					?>
                </label>
                <div class="djform_field">
                    <?php $image_urls = ""?>
					<div id="uploader">
					<input type="file"  name="image[]" class="inputbox" />
					<?php if($imglimit>1){ ?>
						</div><a class="add_another_image" href="#" onclick="addImage(<?php echo $imglimit;?>); return false;" ><?php echo JText::_('COM_DJCLASSIFIEDS_ADD_NEX_IMAGE')?></a>
					<?php }?>
                </div>
                <div style="clear:both"></div>
            </div>
 		 	<?php if($par->get('terms',1)>0 && $par->get('terms_article_id',0)>0 && $this->terms_link && JRequest::getVar('id', 0, '', 'int' )==0){ ?>				
    		<div class="djform_row">
                <label class="label" >&nbsp;</label>
                <div class="djform_field">
                	<input type="checkbox" name="terms_and_conditions" id="terms_and_conditions" value="1" class="required" />
					<?php 
					 
					echo ' <label class="label_terms" for="terms_and_conditions" id="terms_and_conditions-lbl" >'.JText::_('COM_DJCLASSIFIEDS_I_AGREE_TO_THE').' </label>';					
					if($par->get('terms',0)==1){
						echo '<a href="'.$this->terms_link.'" target="_blank">'.JText::_('COM_DJCLASSIFIEDS_TERMS_AND_CONDITIONS').'</a>';
					}else if($par->get('terms',0)==2){
						echo '<a href="'.$this->terms_link.'" rel="{size: {x: 700, y: 500}, handler:\'iframe\'}" class="modal" target="_blank">'.JText::_('COM_DJCLASSIFIEDS_TERMS_AND_CONDITIONS').'</a>';
					}
					
					?> *
                </div>
                <div style="clear:both"></div>
            </div>
		 <?php } ?>	
 		 <?php
 		 }
			if($par->get('promotion','1')=='1' && count($this->promotions)>0){ ?>							
			</div></div><div class="prom_rows additem_djform">
				<div class="title_top"><?php echo JText::_('COM_DJCLASSIFIEDS_PROMOTIONS');	?></div>
				<div class="additem_djform_in">
				<?php foreach($this->promotions as $prom){ ?>	
	    		<div class="djform_row">
	                <label class="label" >
	                	<?php echo JText::_($prom->label).'<br /><span>'.JText::_('COM_DJCLASSIFIEDS_PRICE').'&nbsp;'.$prom->price.'&nbsp; '.$par->get('unit_price').'</span>';?>						
	                </label>
	                <div class="djform_field">
						<div class="djform_prom_v" >
							<div class="djform_prom_v_in" >
							<input type="radio" name="<?php echo $prom->name;?>" value="1" <?php  if(strstr($this->item->promotions, $prom->name)){echo "checked";}?> /><label><?php echo JText::_('JYES'); ?></label>
							<input type="radio" name="<?php echo $prom->name;?>" value="0" <?php  if(!strstr($this->item->promotions, $prom->name)){echo "checked";}?> /><label><?php echo JText::_('JNO'); ?></label>
							</div>
						</div>
						<div class="djform_prom_img" >							
							<div class="djform_prom_img_in" >
								<?php 
									$tip_content = '<img src=\''.JURI::base().'/components/com_djclassifieds/assets/images/'.$prom->name.'_h.png\' />'; 
									echo '<img class="Tips1" title="'.$tip_content.'" src="'.JURI::base().'/components/com_djclassifieds/assets/images/'.$prom->name.'.png" />';
								 ?>
							</div>
						</div>
						<div class="djform_prom_desc" >
							<div class="djform_prom_desc_in" >
							<?php echo JText::_($prom->description); ?>
							</div>
						</div>
							
	                </div>
	                <div style="clear:both"></div>
	            </div>
	            <?php } ?>
	            </div>
            </div>
		 <?php } ?>			
        </div>
		<label id="verification_alert" class="invalid" style="display:none; text-align:center;" /><br /></label>
     <div class="classifieds_buttons">
     	<?php if($user->id>0){
	     	$cancel_link = JRoute::_('index.php?option=com_djclassifieds&view=useritems&Itemid='.JRequest::getVar('Itemid','0'));
	     }else{
	     	$cancel_link = JRoute::_('index.php?option=com_djclassifieds&view=items&cid=0&Itemid='.JRequest::getVar('Itemid','0'));
	     } 	     
	     ?>
	     <a class="button" href="<?php echo $cancel_link;?>"><?php echo JText::_('COM_DJCLASSIFIEDS_CANCEL')?></a>
	     <button class="button" type="submit" id="submit_button" ><?php echo JText::_('COM_DJCLASSIFIEDS_SAVE'); ?></button>	     
		 <input type="hidden" name="option" value="com_djclassifieds" />
		<?php
			echo '<input type="hidden" name="id" value="'.JRequest::getVar('id', 0, '', 'int' ).'" />';
			echo '<input type="hidden" name="view" value="additem" />';
			echo '<input type="hidden" name="task" value="save" />';
		?>
		<input type="hidden" name="boxchecked" value="0" />
	</div>
</form>
</div>
</div>
<script type="text/javascript">	
	
function addImage(imglimit){

	lim=document.djForm['image[]'].length;
	if(!lim){
		lim=1;
	}
	
	if(document.djForm['del_img[]']){
		lim_old=document.djForm['del_img[]'].length;
		if(!lim_old){
			lim_old=1;
		}
		lim = lim + lim_old;	
	}
	
	
	if(lim==imglimit){
		alert('<?php echo JText::_('COM_DJCLASSIFIEDS_MAXIMUM_NUMBER_OF_IMAGES_IS');?> '+imglimit);
	}else{
		var inputdiv = document.createElement('input');
		inputdiv.setAttribute('name','image[]');
		inputdiv.setAttribute('type','file');
		var ni = $('uploader');
		ni.appendChild(document.createElement('br'))
		ni.appendChild(inputdiv);		
	}

}


	
function introdescLimit(limit){
	if(document.djForm.intro_desc.value.length<=limit){
		a=document.djForm.intro_desc.value.length;
		b=limit;
		c=b-a;
		document.getElementById('introdesc_limit').innerHTML= '('+c+')';
	}else{
		document.djForm.intro_desc.value = document.djForm.intro_desc.value.substring(0, limit);
	}
}



	function getFields(cat_id){
	var el = document.getElementById("ex_fields");
	var before = document.getElementById("ex_fields").innerHTML.trim();	
	
	if(cat_id!=0){
		el.innerHTML = '<div style="text-align:center"><img src="<?php echo JURI::base(); ?>components/com_djclassifieds/assets/images/loading.gif" /></div>';
		var url = 'index.php?option=com_djclassifieds&view=additem&task=getFields&cat_id=' + cat_id <?php if($this->item->id){echo "+ '&id='+".$this->item->id;} ?>;
					  var myRequest = new Request({
				    url: 'index.php',
				    method: 'post',
					data: {
				      'option': 'com_djclassifieds',
				      'view': 'additem',
				      'task': 'getFields',
					  'cat_id': cat_id,
					  <?php if($this->item->id){echo "'id':'".$this->item->id."'";} ?>					  
					  },
				    onRequest: function(){
				        //myElement.set('html', '<div style="text-align:center;"><img style="margin-top:10px;" src="<?php echo JURI::base().'components/'.JRequest::getString('option').'/images/long_loader.gif';?>" /><br />loading...</div>');
				    },
				    onSuccess: function(responseText){																
						el.innerHTML = responseText;						 	
				    },
				    onFailure: function(){
				        myElement.set('html', 'Sorry, your request failed, please contact to ');
				    }
				});
				myRequest.send();	
	}else{
		el.innerHTML = '';
		//el.innerHTML='<?php echo JText::_('COM_DJCLASSIFIEDS_PLEASE_SELECT_CATEGORY');?>';
	}
	
}

function getCities(region_id){
	var el = document.getElementById("city");
	var before = document.getElementById("city").innerHTML.trim();	
	
	if(region_id>0){
		el.innerHTML = '<img src="<?php echo JURI::base(); ?>components/com_djclassifieds/assets/images/loading.gif" />';
		var url = 'index.php?option=com_djclassifieds&view=additemtask=getCities&r_id=' + region_id <?php if($this->item->id){echo "+ '&id='+".$this->item->id;} ?>;
			var myRequest = new Request({
				    url: 'index.php',
				    method: 'post',
					data: {
				      'option': 'com_djclassifieds',
				      'view': 'additem',
				      'task': 'getCities',
					  'r_id': region_id,
					  <?php if($this->item->id){echo "'id':'".$this->item->id."'";} ?>					  
					  },
				    onRequest: function(){
				        //myElement.set('html', '<div style="text-align:center;"><img style="margin-top:10px;" src="<?php echo JURI::base().'components/'.JRequest::getString('option').'/images/long_loader.gif';?>" /><br />loading...</div>');
				    },
				    onSuccess: function(responseText){																
						el.innerHTML = responseText;						 	
				    },
				    onFailure: function(){
				        myElement.set('html', 'Sorry, your request failed, please contact to ');
				    }
				});
				myRequest.send();	
	}else{
		el.innerHTML='<?php echo JText::_('COM_DJCLASSIFIEDS_PLEASE_SELECT_REGION');?>';
	}
	
}

window.addEvent("load", function(){
	introdescLimit(<?php echo $par->get('introdesc_char_limit');?>);
	getFields(<?php echo $this->item->cat_id; ?>);
}
);

</script>