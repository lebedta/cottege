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

defined ('_JEXEC') or die('Restricted access');
JHTML::_( 'behavior.Mootools' );
jimport( 'joomla.html.editor' );
JHTML::_('behavior.calendar');
JHTML::_('behavior.modal');
$par = &JComponentHelper::getParams( 'com_djclassifieds' );
$editor = &JFactory::getEditor();
$document=& JFactory::getDocument();
if($this->item->id>0 && $par->get('show_googlemap')==1  && $this->country!='' && $this->item->region_id>0){
	$document->addScript("http://maps.google.com/maps/api/js?sensor=false");		
}
if($this->item->id>0){
	$exp_date_time = explode(' ', $this->item->date_exp);
	//print_r($e_date);die(); 
	$date_exp = $exp_date_time[0];
	$time_exp = substr($exp_date_time[1],0,-3);
	 
}else{
	$exp_days = (int)$par->get('exp_days');
	$time_exp = date("H:i");
	$date_exp = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")+$exp_days, date("Y")));
}	


?> 
		<form action="index.php?option=com_djclassifieds" method="post" name="adminForm" id="adminForm" enctype='multipart/form-data'>
			<div class="width-60 fltlft">
			<fieldset class="adminform">	
				<table class="admintable">
				<tr>
					<td width="100" align="right" class="key">
						<?php echo JText::_('COM_DJCLASSIFIEDS_NAME');?>
					</td>
					<td>
						<input class="text_area" type="text" name="name" id="name" size="50" maxlength="250" value="<?php echo $this->item->name; ?>" />
					</td>
				</tr>
				<tr>
					<td width="100" align="right" class="key">
						<?php echo JText::_('COM_DJCLASSIFIEDS_CATEGORY');?>
					</td>
					<td>
					<select autocomplete="off" name="cat_id" class="inputbox" onchange="getFields(this.value)" >
						<option value=""><?php echo JText::_('JOPTION_SELECT_CATEGORY');?></option>
						<?php echo JHtml::_('select.options', DJClassifiedsCategory::getCatSelect(), 'value', 'text', $this->item->cat_id, true);?>
					</select>
					</td>
				</tr>
			<tr>
                <td width="100" align="right" class="key">
                    <?php echo JText::_('COM_DJCLASSIFIEDS_EXTRA_FIELDS');?>
		
                </td>
                <td>
					<div id="ex_fields"><?php echo JText::_('COM_DJCLASSIFIEDS_PLEASE_SELECT_CATEGORY');?></div>					
                </td>
            </tr>	
            				
			<?php /*?>	
			<tr>
                <td width="100" align="right" class="label" id="category_l">
                    <?php echo JText::_('Category'); ?>					
                </td>
                <td class="category">
                    <?php
				$cat_sel = '<select id="cat_0" style="width:210px" name="cats[]" onchange="new_cat(0,this.value);"><option value="">Select Category</option>';
				$parent_id=0;	
				foreach($this->cats as $l){
					if($parent_id!=$l->parent_id){
						$cat_sel .= '</select>';
						echo $cat_sel;
						break;
					}	
					$cat_sel .= '<option value="'.$l->id.'">'.$l->name.'</option>';
				}
				
				?><div style="clear:both"></div>
				<div id="after_cat_0"></div>
				<script type="text/javascript">
					var cats=new Array();
					
				<?
				$cat_sel = '<select style="width:210px" name="cats[]" id="cat_0" onchange="new_cat(0,this.value);">';
				$parent_id=0;	
				
				foreach($this->cats as $l){
					if($parent_id!=$l->parent_id){
						$cat_sel .= '</select>';
						echo "cats[$parent_id]='$cat_sel<div id=\"after_cat_$parent_id\"></div>';";
						$parent_id=$l->parent_id;
						$cat_sel = '<div style="clear:both"></div><select style="width:210px" name="cats[]" id="cat_'.$l->parent_id.'" onchange="new_cat('.$parent_id.',this.value);">';
						$cat_sel .= '<option value=""> - - - </option>';		
					}	
					$cat_sel .= '<option value="'.$l->id.'">'.$l->name.'</option>';
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
					
                </td> 
            </tr>
            <?php */ ?>
            <?php if($par->get('show_regions','1')){ ?>
    			<tr>    			
                <td width="100" align="right" class="label" id="category_l">
                    <?php echo JText::_('COM_DJCLASSIFIEDS_LOCALIZATION'); ?>					
                </td>
                <td class="region">
                    <?php
						$reg_sel = '<select id="reg_0" style="width:210px" name="regions[]" onchange="new_reg(0,this.value);"><option value="">'.JText::_('COM_DJCLASSIFIEDS_SELECT_LOCALIZATION').'</option>';
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
							
                </td> 
            </tr>        
            <?php }?>                                                					
       		<tr>
                <td width="100" align="right" class="key">
                    <?php echo JText::_('COM_DJCLASSIFIEDS_ADDRESS');?>
                </td>
                <td>
                	<?php if(!$par->get('show_regions','1')){?>
                		<input class="text_area" type="hidden" name="regions[]" id="regions1" value="0" />
                	<?php }?>
                    <input class="text_area" type="text" name="address" id="price" size="50" maxlength="250" value="<?php echo $this->item->address; ?>" />
                </td>
            </tr>
			<tr>
                <td width="100" align="right" class="key">
                    <?php echo JText::_('COM_DJCLASSIFIEDS_PRICE');?>
                </td>
                <td>
                    <input class="text_area" type="text" name="price" id="price" size="50" maxlength="250" value="<?php echo $this->item->price; ?>" />
                </td>
            </tr>  
			<tr>
                <td width="100" align="right" class="key">
                    <?php echo JText::_('COM_DJCLASSIFIEDS_CONTACT');?>						
                </td>
                <td>
					 <textarea id="contact" name="contact" rows="4" cols="55" class="inputbox" ><?php echo $this->item->contact; ?></textarea>
                </td>
            </tr>	                      		
			<tr>
                <td width="100" align="right" class="key">
                    <?php echo JText::_('COM_DJCLASSIFIEDS_INTRO_DESCRIPTION');?>
 					<div id="ile">(<?php echo $par->get('introdesc_char_limit')-strlen($this->item->intro_desc);?>)</div>						
                </td>
                <td>
					 <textarea id="intro_desc" name="intro_desc" rows="5" cols="55" class="inputbox" onkeyup="checkt(this.form,<?php echo $par->get('introdesc_char_limit');?>);" onkeydown="checkt(this.form,<?php echo $par->get('introdesc_char_limit');?>);"><?php echo $this->item->intro_desc; ?></textarea>
                </td>
            </tr>
            <tr>
                <td width="100" align="right" class="key">
                    <?php echo JText::_('COM_DJCLASSIFIEDS_DESCRIPTION');?>
                </td>
                <td>
                    <?php
					echo $editor->display( 'description', $this->item->description, '450', '350', '50', '20',false );                    
					?>                  
                </td>
            </tr>										
			</table>
		</fieldset>
		</div>
		<div class="width-40 fltrt">
			<fieldset class="adminform">
				<legend><?php echo JText::_('COM_DJCLASSIFIEDS_DETAILS'); ?></legend>
				<table class="admintable">
				<tr>
	                <td width="100" align="right" class="key">
	                    <?php echo JText::_('COM_DJCLASSIFIEDS_EXPIRATION_DATE');?>
			
	                </td>
	                <td>
						<input class="inputbox" type="text" name="date_expir" id="date_expir" size="10" maxlenght="19" value = "<?php echo $date_exp;?>"/>
					        <img class="calendar" src="templates/bluestork/images/system/calendar.png" alt="calendar" id="showArrivalCalendar" />
					        <script type="text/javascript">
					         var startDate = new Date(2008, 8, 7);
					         Calendar.setup({
					            inputField  : "date_expir",
					            ifFormat    : "%Y-%m-%d",                  
					            button      : "showArrivalCalendar",
					            date      : startDate
					         });
					        </script>
					
	                </td>
	            </tr>   
				<tr>
	                <td width="100" align="right" class="key">
	                    <?php echo JText::_('COM_DJCLASSIFIEDS_EXPIRATION_TIME');?><br />
	                    <span style="color:#666">(<?php echo JText::_('COM_DJCLASSIFIEDS_EXPIRATION_TIME_FORMAT');?>)</span>
	                </td>
	                <td>
	                	<input type="text" name="time_expir" value="<?php echo $time_exp; ?>" size="10" />
	                </td>
	            </tr>                    	
					<tr>
						<td width="100" align="right" class="key">
							<?php echo JText::_('COM_DJCLASSIFIEDS_CREATED_BY');?>
						</td>
						<td>
							<?php echo $this->selusers; 
							?>
						</td>
					</tr>
					<tr>
						<td width="100" align="right" class="key">
							<?php echo JText::_('COM_DJCLASSIFIEDS_PUBLISHED');?>
						</td>
						<td>
							<input autocomplete="off" type="radio" name="published" value="1" <?php  if($this->item->published==1 || $this->item->id==0){echo "checked";}?> /><span style="float:left; margin:5px 15px 0 0;"><?php echo JText::_('COM_DJCLASSIFIEDS_YES'); ?></span>				
							<input autocomplete="off" type="radio" name="published" value="0" <?php  if($this->item->published==0 && $this->item->id>0){echo "checked";}?> /><span style="float:left; margin:5px 15px 0 0;"><?php echo JText::_('COM_DJCLASSIFIEDS_NO'); ?></span> 
						</td>
					</tr>	
					<tr>
		                <td width="100" align="right" class="key">
		                    <?php echo JText::_('COM_DJCLASSIFIEDS_ABUSE_RAPORTS');?>
		                </td>
		                <td>
		                	<?php 
		                	$c_abuse = $this->abuse;						
							if($c_abuse>0 && $this->item->id>0){
								echo '<a href="index.php?option=com_djclassifieds&view=abuse&id='.$this->item->id.'&tmpl=component" class="modal" rel="{handler: \'iframe\', size: {x: 800, y: 450}}">';								
								echo $c_abuse.' '.JText::_('COM_DJCLASSIFIEDS_ABUSE_RAPORTS').'</a>';
							}else{
								echo $c_abuse.' '.JText::_('COM_DJCLASSIFIEDS_ABUSE_RAPORTS');	
							}
		                	?>
		                </td>
		            </tr>  	
		            <tr>
		                <td width="100" align="right" class="key">
		                    <?php echo JText::_('COM_DJCLASSIFIEDS_ADDED');?>
		                </td>
		                <td>
		                	<?php echo $this->item->date_start; ?>
		                </td>
	            	</tr>
	            	<tr>
		                <td width="100" align="right" class="key">
		                    <?php echo JText::_('COM_DJCLASSIFIEDS_DURATION');?>
		                </td>
		                <td>
		                	<?php echo $this->item->exp_days.' '.JText::_('COM_DJCLASSIFIEDS_DAYS'); ?>
		                </td>
	            	</tr> 	 									
				</table>
			</fieldset>	
			<fieldset class="adminform">
				<legend><?php echo JText::_('COM_DJCLASSIFIEDS_PROMOTIONS'); ?></legend>
				<table class="admintable">  
					<?php foreach($this->promotions as $prom){ ?>
						<tr>
							<td width="100" align="right" class="key">
								<?php echo JTEXT::_($prom->label);?>
							</td>
							<td>
								<input autocomplete="off" type="radio" name="<?php echo $prom->name;?>" value="1" <?php  if(strstr($this->item->promotions, $prom->name)){echo "checked";}?> /><span style="float:left; margin:5px 15px 0 0;"><?php echo JText::_('COM_DJCLASSIFIEDS_YES'); ?></span>				
								<input autocomplete="off" type="radio" name="<?php echo $prom->name;?>" value="0" <?php  if(!strstr($this->item->promotions, $prom->name)){echo "checked";}?> /><span style="float:left; margin:5px 15px 0 0;"><?php echo JText::_('COM_DJCLASSIFIEDS_NO'); ?></span> 
							</td>
						</tr>
					<?php }?>					
				</table>
			</fieldset>				
			<fieldset class="adminform">
				<legend><?php echo JText::_('COM_DJCLASSIFIEDS_PAYMENTS'); ?></legend>
				<table class="admintable">  
					<tr>
						<td width="100" align="right" class="key">
							<?php echo JText::_('COM_DJCLASSIFIEDS_PAID');?>
						</td>
						<td>
							<input autocomplete="off" type="radio" name="payed" value="1" <?php  if($this->item->payed==1 || $this->item->id==0){echo "checked";}?> /><span style="float:left; margin:5px 15px 0 0;"><?php echo JText::_('COM_DJCLASSIFIEDS_YES'); ?></span>				
							<input autocomplete="off" type="radio" name="payed" value="0" <?php  if($this->item->payed==0){echo "checked";}?> /><span style="float:left; margin:5px 15px 0 0;"><?php echo JText::_('COM_DJCLASSIFIEDS_NO'); ?></span> 
						</td>
					</tr>       
					<?php if($this->payment){ ?>             	
					<tr>
						<td width="100" align="right" class="key">
							<?php echo JText::_('COM_DJCLASSIFIEDS_PAYMENT_TYPE');?>
						</td>
						<td>
							<?php echo $this->payment->method; 
							?>
						</td>
					</tr>
					<tr>
						<td width="100" align="right" class="key">
							<?php echo JText::_('COM_DJCLASSIFIEDS_PAYMENT_STATUS');?>
						</td>
						<td>
							<?php echo $this->payment->status; 
							?> 							
						</td>
					</tr>
					

					<?php } ?>
				</table>
			</fieldset>	
			<fieldset class="adminform">
				<legend><?php echo JText::_('COM_DJCLASSIFIEDS_IMAGES'); ?></legend>
				<table class="admintable">
				<tr>
					<td colspan="2"><b><?php echo JText::_('COM_DJCLASSIFIEDS_FIRST_IMAGES_IS_MAIN_IMAGE'); ?></b></td>
				</tr>
	            <tr>
	                <td width="100" align="right" class="key">
	                    <?php echo JText::_('COM_DJCLASSIFIEDS_IMAGES_INCLUDED');?>
	                </td>
	                <td style="padding-left:15px;" >
	                	<input type="hidden" name="image_url" id="image_url" value="<?php echo $this->item->image_url ?>" />
	                    <?php
								$images_count = 0;
								$images = array();
								if(!$image = $this->item->image_url){
									echo JText::_('COM_DJCLASSIFIEDS_NO_IMAGES_INCLUDED');
								}else{
									$images=explode(';', substr($image,0,-1));
										$path = str_replace('/administrator','',JURI::base());
										$path .= '/components/com_djclassifieds/images/';
									for($i=0; $i<count($images); $i++){
												  ?>
												  <img src="<?php echo $path.$images[$i];?>.th.jpg" alt="" />
												  <input type="checkbox" name="del_image[]" id="del_image[]" value="<?php echo $images[$i];?>"/>
												  <?php echo JText::_('COM_DJCLASSIFIEDS_CHECK_TO_DELETE'); ?>
												  <div style="clear:both"></div>
										<?php
									}
								}
							?>
	                </td>
	            </tr>
				<tr>
	                <td width="100" align="right" class="key">
	                    <?php echo JText::_('COM_DJCLASSIFIEDS_ADD_IMAGE');?>
	                </td>
	                <td style="padding-left:15px;" >
	                    <?php $image_urls = ""?>
						<div id="uploader">
						<input type="file"  name="image[]" />
						
						</div><div style="clear:both" ></div>
						<a href="#" onclick="addImage(); return false;" ><?php echo JText::_('COM_DJCLASSIFIEDS_ADD_IMG_LINK')?></a>
	                </td>
	            </tr>					
				</table>
			</fieldset>		
				<?php 
				if($this->item->id>0 && $par->get('show_googlemap')==1 && $this->country!='' && $this->item->region_id>0){ ?>
					<fieldset class="adminform">
						<legend><?php echo JText::_('COM_DJCLASSIFIEDS_LOCALIZATION'); ?></legend>
							<div id="google_map_box" style="display:none;">
								 <div id='map' style='width: 470px; height: 400px; border: 1px solid #666;'>						  
								 </div>      
							</div>
					</fieldset>	
				<?php }
				?>								
		</div>
			<input type="hidden" name="id" value="<?php echo $this->item->id; ?>" />
			<input type="hidden" name="ordering" value="<?php echo $this->item->ordering; ?>" />
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
		</form>
<script language="javascript" type="text/javascript">

	function submitbutton(pressbutton) {
	alert('a');
		var form = document.adminForm;
		if (pressbutton == 'cancelItem') {
			submitform( pressbutton );
			return;
		}
		
        var wal = 0;
		if (form.name.value == ""){
			alert( "<?php echo JText::_( 'Item must have name', true ); ?>" );
			wal=1;
		}		

		if(wal==0){
			submitform( pressbutton );
		}
	}
	function check(){
	if(document.adminForm.price.value.search(/^[0-9]+(\,{1}[0-9]{2})?$/i)){
				document.adminForm.price.style.backgroundColor='#F00000';
				$('price_alert').innerHTML = "<?php echo JText::_('ALERT_PRICE')?>";
				$('price_alert').setStyle('background','#f00000');
				$('price_alert').setStyle('color','#ffffff');
				$('price_alert').setStyle('font-weight','bold');
			}
			else{
				document.adminForm.price.style.backgroundColor='';
				$('price_alert').innerHTML = '';
				$('price_alert').setStyle('background','none');
			}
}
	
	
function addImage(){
	var inputdiv = document.createElement('input');
	inputdiv.setAttribute('name','image[]');
	inputdiv.setAttribute('type','file');
	
	var div = document.createElement('div');
	div.setAttribute('style','clear:both');

	var ni = $('uploader');
	
	ni.appendChild(document.createElement('br'));
	ni.appendChild(div);
	ni.appendChild(inputdiv);
	
}


	
function checkt(my_form,limit){
if(my_form.intro_desc.value.length<=limit)
{
	a=my_form.intro_desc.value.length;
	b=limit;
	c=b-a;
	document.getElementById('ile').innerHTML= '('+c+')';
}
else
{
	my_form.intro_desc.value = my_form.intro_desc.value.substring(0, limit);
}
}

	function getFields(cat_id){
	var el = document.getElementById("ex_fields");
	var before = document.getElementById("ex_fields").innerHTML.trim();	
	
	if(cat_id>0){
		el.innerHTML = '<img src="<?php echo JURI::base(); ?>components/com_djclassifieds/images/loading.gif" />';
		var url = 'index.php?option=com_djclassifieds&task=getFields&cat_id=' + cat_id <?php if($this->item->id){echo "+ '&id='+".$this->item->id;} ?>;
					  var myRequest = new Request({
				    url: 'index.php',
				    method: 'post',
					data: {
				      'option': 'com_djclassifieds',
				      'task': 'item.getFields',
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
		/*var reque = new Ajax(url, {
			method: 'post',
			onComplete: function(request){
				//alert(request);
				el.innerHTML = request; 			
			}
		}).request();*/	
	}else{
		el.innerHTML='<?php echo JText::_('COM_DJCLASSIFIEDS_PLEASE_SELECT_CATEGORY');?>';
	}
	
}

function getCities(region_id){
	var el = document.getElementById("city");
	var before = document.getElementById("city").innerHTML.trim();	
	
	if(region_id>0){
		el.innerHTML = '<img src="<?php echo JURI::base(); ?>components/com_djclassifieds/images/loading.gif" />';
		var url = 'index.php?option=com_djclassifieds&task=getCities&r_id=' + region_id <?php if($this->item->id){echo "+ '&id='+".$this->item->id;} ?>;
			var myRequest = new Request({
				    url: 'index.php',
				    method: 'post',
					data: {
				      'option': 'com_djclassifieds',
				      'task': 'item.getCities',
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
	getFields(<?php echo $this->item->cat_id; ?>);
	<?php if($this->item->id>0 && $par->get('show_googlemap')==1  && $this->country!='' && $this->item->region_id>0){ ?>
		mapaStart();
	<?php }?>
}
);
</script>

<?php if($this->item->id>0 && $par->get('show_googlemap')==1 && $this->country!='' && $this->item->region_id>0){ 
	$marker_txt = '<div style="width:200px;"><div style="margin-bottom:5px;"><strong>'.$this->item->name.'</strong></div>';
	$marker_txt .= $this->item->intro_desc.'<br />'; 
//	$marker_txt .= '<strong>'.JText::_('Type').'</strong> : '.$i->type_name.'<br />';
//	$marker_txt .= '<strong>'.JText::_('Price').'</strong> : '.$i->price.'<br />';
//	$marker_txt .= '<strong>'.JText::_('Address').'</strong> : '.$i->country.", ".$i->city.'<br />';
//	if($i->street!='' && $this->subsc==1){
//		$marker_txt .= $i->street.'<br />';
//	}

	$marker_txt .= '<div style="margin-top:10px;">';
	

									
	if($this->item->image_url!=''){
		$images=explode(';', substr($this->item->image_url,0,-1));
		
		$path = str_replace('/administrator','',JURI::base());
		$path .= '/components/com_djclassifieds/images/';
		for($ii=0; $ii<count($images); $ii++){
			$marker_txt .= '<div class="display:inline;width:60px;"><img width="60px" src="'.$path.$images[$ii].'.ths.jpg" /></div> ';
			if($ii==3){
				break;
			}
		}
	}
	$marker_txt .='</div></div>';	

?>
 <script type='text/javascript'>    

var map;
        var map_marker = new google.maps.InfoWindow();
        var geokoder = new google.maps.Geocoder();
        
		function addMarker(position,txt,icon)
		{
		    var MarkerOpt =  
		    { 
		        position: position,
		        icon: icon,	 	
		        map: map
		    } 
		    var marker = new google.maps.Marker(MarkerOpt);
		    marker.txt=txt;
		     
		    google.maps.event.addListener(marker,"click",function()
		    {
		        map_marker.setContent(marker.txt);
		        map_marker.open(map,marker);
		    });
		    return marker;
		}
		    	
		 function mapaStart()    
		 {   
			
			var adres = '<?php echo $this->country.", ".$this->city; if($this->item->address!='' ){echo ", ".$this->item->address;}?>';
			geokoder.geocode({address: adres}, function (results, status)
			{
			    if(status == google.maps.GeocoderStatus.OK)
			    {
			    	document.getElementById("google_map_box").style.display='block';
				    var opcjeMapy = {
				       zoom: <?php echo $par->get('gm_zoom','10'); ?>,
				  		center: results[0].geometry.location,
				  		mapTypeId: google.maps.MapTypeId.ROADMAP,
				  		navigationControl: true
				    };
				    map = new google.maps.Map(document.getElementById("map"), opcjeMapy);
				     var size = new google.maps.Size(32,32); 
				     var start_point = new google.maps.Point(0,0);
	                 var anchor_point = new google.maps.Point(0,16);
	            		<?php if($par->get('gm_icon',1)==1 && file_exists(JPATH_BASE.'/images/djcf_gmicon.png')){ ?>
		                	 var icon = new google.maps.MarkerImage("<?php echo str_ireplace('administrator/','', JURI::base());?>images/djcf_gmicon.png", size, start_point, anchor_point);
		                <?php }elseif($par->get('gm_icon',1)==1){ ?>
		                	var icon = new google.maps.MarkerImage("<?php echo str_ireplace('administrator/','', JURI::base());?>components/com_djclassifieds/assets/images/djcf_gmicon.png", size, start_point, anchor_point);
		                <?php }else{ ?>
		              		 var icon = '';  	
		                <?php }?>
		                
			    	var marker = addMarker(results[0].geometry.location,'<?php echo $marker_txt; ?>',icon);
					google.maps.event.trigger(marker,'click');
			    }
			    else{
			        
			    }
			});		      
		 }

</script>
<?php }?>
<?php echo '<div style="clear:both"></div>'.DJCFFOOTER; ?>