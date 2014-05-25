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
JHTML::_('behavior.mootools');
JHTML::_('behavior.formvalidation');
$par = &JComponentHelper::getParams( 'com_djclassifieds' );
$user = & JFactory::getUser();
$Itemid = JRequest::getVar('Itemid', 0,'', 'int');
$i = $this->item;
$item_class='';

$icon_new_a	= $par->get('icon_new','1');
$icon_new_date = mktime(date("G"), date("i"), date("s"), date("m"), date("d")-$par->get('icon_new_time','3'), date("Y"));
$date_start = strtotime($i->date_start);
$icon_new=0;
	if($i->promotions){
		$item_class .=' promotion '.str_ireplace(',', ' ', $i->promotions);
	}
	if($date_start>$icon_new_date && $icon_new_a){
		$icon_new=1;
		$item_class .= ' item_new';  
	}
	
	if($par->get('favourite','1') && $user->id>0){
		if($i->f_id){ $item_class .= ' item_fav'; }
	}	
$document=& JFactory::getDocument();
if($par->get('show_googlemap')==1){
	$document->addScript("http://maps.google.com/maps/api/js?sensor=false");	
}

$session = &JFactory::getSession();
require_once(JPATH_COMPONENT.DS.'assets'.DS.'recaptchalib.php');
$publickey = "6LfzhgkAAAAAAL9RlsE0x-hR2H43IgOFfrt0BxI0"; 
$privatekey = "6LfzhgkAAAAAAOJNzAjPz3vXlX-Bw0l-sqDgipgs";
$error='';
$main_img_width = $par->get('smallth_width','75') * 3 + 28; 

$mod_attribs=array();
$mod_attribs['style'] = 'xhtml';

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

	 $modules_djcf = &JModuleHelper::getModules('djcf-item-top');
			if(count($modules_djcf)>0){
				echo '<div class="djcf-ad-item-top clearfix">';
				foreach (array_keys($modules_djcf) as $m){
					echo JModuleHelper::renderModule($modules_djcf[$m],$mod_attribs);
				}
				echo'</div>';		
			}
			?>
			
<div class="dj-item<?php echo $item_class; ?>">
<?php	
	echo '<div class="title_top info"><h2>'.$i->name.'</h2>';
		if($user->id==$i->user_id && $user->id>0){
			echo '<a href="index.php?option=com_djclassifieds&view=additem&id='.$i->id.'&Itemid='.$Itemid.'" class="title_edit button">'.JText::_('COM_DJCLASSIFIEDS_EDIT').'</a>';
		}		
		if($par->get('favourite','1') && $user->id>0){
			if($i->f_id){
				echo '<a title="'.JText::_('COM_DJCLASSIFIEDS_DELETE_FROM_FAVOURITES').'" href="index.php?option=com_djclassifieds&view=item&task=removeFavourite&cid='.$i->cat_id.'&id='.$i->id.'&Itemid='.$Itemid.'">';
					echo ' <img src="'.JURI::base().'/components/com_djclassifieds/assets/images/fav_a.png" width="16px" class="fav_ico"/>';
				echo '</a>';
			}else{
				echo '<a title="'.JText::_('COM_DJCLASSIFIEDS_ADD_TO_FAVOURITES').'" href="index.php?option=com_djclassifieds&view=item&task=addFavourite&cid='.$i->cat_id.'&id='.$i->id.'&Itemid='.$Itemid.'">';
					echo ' <img src="'.JURI::base().'/components/com_djclassifieds/assets/images/fav_na.png" width="16px" class="fav_ico"/>';
				echo '</a>';
			}
		}
		if($icon_new){
				echo ' <span class="new_icon">'.JText::_('COM_DJCLASSIFIEDS_NEW').'</span>';
			} 			
		if($par->get('sb_position','0')=='top' && $par->get('sb_code','')!=''){
			echo '<span class="sb_top">'.$par->get('sb_code','').'</span>';
		 }
		
	echo '</div>'; ?>
	<div class="dj-item-in">
		<?php if($i->image_url!=''){ ?>
			<div class="images_wrap"><div class="images">
			<?php $images = explode(";",$i->image_url);
			/*for($ii=0; $ii<count($images)-1;$ii++ ){  //-1 bo wrzuca na koniec tablicy puste miejsce
			?>
	   	        <a href="<?php echo JURI::base().'components/com_djclassifieds/images/'.$images[$ii];?>.thb.jpg"  title=""  rel="lightbox-gallery" target="_blank"><img src="<?php echo JURI::base().'components/com_djclassifieds/images/'.$images[$ii].'.th.jpg';?>" style="top: 0;"/></a>
	   		<?php
			} */?>
	
				<div class="djc_images">
					<div class="djc_mainimage">
						<a id="djc_mainimagelink" rel="djc_lb_0" title="<?php echo $i->name; ?>" href="<?php echo $images[0]; ?>.thb.jpg">
							<img width="<?php echo $main_img_width;?>px" id="djc_mainimage" alt="<?php echo $i->name; ?>" src="<?php echo JURI::base().'components/com_djclassifieds/images/'.$images[0];?>.thb.jpg" />
						</a>
					</div>
					<?php if (count($images) > 1) { ?>
						<div class="djc_thumbnails" id="djc_thumbnails">
						<?php for($ii=0; $ii<count($images)-1;$ii++ ){
								if($ii>0 && $ii%3==0){
									echo '<div style="clear:both"></div>';
								}
							 ?>
							<div class="djc_thumbnail">
								<a rel="<?php echo JURI::base().'components/com_djclassifieds/images/'.$images[$ii];?>.thb.jpg" title="<?php echo $images[$ii]; ?>" href="<?php echo JURI::base().'components/com_djclassifieds/images/'.$images[$ii];?>.thb.jpg">
									<img alt="<?php echo $images[$ii]; ?>" src="<?php echo JURI::base().'components/com_djclassifieds/images/'.$images[$ii];?>.ths.jpg" />
								</a>
							</div>
							<?php } ?>
							<div style="clear:both"></div>
						</div>
					<?php } ?>
					<?php for($ii=0; $ii<count($images)-1;$ii++ ){ ?>
						<a id="djc_lb_<?php echo $ii; ?>" rel="lightbox-djitem" title="" href="<?php echo JURI::base().'components/com_djclassifieds/images/'.$images[$ii];?>.thb.jpg" style="display: none;"></a>
					<?php } ?>
				</div>
	
	
			</div></div>
			<?php }	
			
			if($i->image_url==''){
				$gd_class=' no_images';
			}else{
				$gd_class='';
			}		
			?>
		<div class="general_det<?php echo $gd_class;?>"><!--<h2><?php echo JText::_('COM_DJCLASSIFIEDS_GENERAL_DETAILS'); ?></h2>-->
		<?php if($i->price){ ?>
		<div class="row">
			<div class="price_wrap">
				<span class="row_label"><?php echo JText::_('COM_DJCLASSIFIEDS_PRICE'); ?>:</span>
				<span class="row_value">
					<?php if($par->get('unit_price_position','0')){
								echo $par->get('unit_price','EUR').' '.$i->price;
							}else{
								echo $i->price.' '.$par->get('unit_price','EUR');								
							} ?>
				</span>
			</div>
		</div>
		<?php } ?>
		<?php if($par->get('show_contact','1')){?>
		<div class="row contact">
			<span class="row_label"><?php echo JText::_('COM_DJCLASSIFIEDS_CONTACT'); ?></span>
			<span class="row_value"><?php echo $i->contact; ?></span>
		</div>
		<?php } ?>
		<?php if($par->get('show_start_date','1')){?>
			<div class="row added">
				<span class="row_label"><?php echo JText::_('COM_DJCLASSIFIEDS_AD_ADDED'); ?></span>
				<span class="row_value"><?php echo $i->date_start; ?></span>
			</div>
		<?php } ?>
			<?php if($i->date_mod!='0000-00-00 00:00:00'){?>
				<div class="row">
					<span class="row_label"><?php echo JText::_('COM_DJCLASSIFIEDS_AD_MODIFIED'); ?>:</span>
					<span class="row_value"><?php echo $i->date_mod; ?></span>
				</div>
			<?php  }
		
		if($par->get('showauthor','1')==1){?>
		<div class="row user_name">
			<span class="row_label"><?php echo JText::_('COM_DJCLASSIFIEDS_CREATED_BY'); ?></span>
			<span class="row_value">
				<?php 
				if($i->user_id==0){
					echo JText::_('COM_DJCLASSIFIEDS_GUEST');
				}else{			
					echo '<a href="index.php?option=com_djclassifieds&view=items&uid='.$i->user_id.'&Itemid='.$Itemid.'">'.$i->username.' <span>('.$this->user_items_c.')</span></a>';
				}?>
			</span>
		</div>		
		<?php
		}
		 /*
		<div class="row">
			<span><?php echo JText::_('Email:'); ?></span><?php echo $i->email; ?>
		</div>
		*/?>
		<?php
			if($par->get('ask_seller','0')==1 && $i->user_id>0){?>
				<button id="ask_form_button" class="button" type="button" ><?php echo JText::_('COM_DJCLASSIFIEDS_ASK_SELLER'); ?></button>
			<?php }
			if($par->get('abuse_reporting','0')==1 && $par->get('notify_user_email','')!=''){?>
				<button id="abuse_form_button" class="button" type="button" ><?php echo JText::_('COM_DJCLASSIFIEDS_REPORT_ABUSE'); ?></button>
			<?php }
		 
		if($par->get('ask_seller','0')==1 && (($par->get('ask_seller_type','0')==1) || ($par->get('ask_seller_type','0')==0 && $i->user_id>0))){?>
			<div id="ask_form" style="display:none;overflow:hidden;">
			<form action="index.php" method="post" name="djForm" id="djForm" class="form-validate">
				<?php if($par->get('ask_seller_type','0')==0){?>
			   		<label for="ask_name" id="ask_name-lbl"><?php echo JText::_('COM_DJCLASSIFIEDS_YOUR_NAME'); ?></label>
			   		<input type="text" class="inputbox required" value="<?php echo $user->name; ?>" name="ask_name" id="ask_name" />
			   		<label for="ask_email" id="ask_email-lbl"><?php echo JText::_('COM_DJCLASSIFIEDS_YOUR_EMAIL'); ?></label>
			   		<input type="text" class="inputbox required validate-email" value="<?php echo $user->email; ?>" name="ask_email" id="ask_email" />		   
			   		<label for="ask_message" id="ask_message-lbl"><?php echo JText::_('COM_DJCLASSIFIEDS_MESSAGE'); ?></label>
			   		<textarea id="ask_message" name="ask_message" rows="5" cols="55" class="inputbox required"></textarea>
			   	<?php }else{		   						
					?>
					<label for="ask_name" id="ask_name-lbl"><?php echo JText::_('COM_DJCLASSIFIEDS_YOUR_NAME'); ?></label>
			   		<input type="text" class="inputbox required" value="<?php echo $session->get('askform_name',''); ?>" name="ask_name" id="ask_name" />
			   		<label for="ask_email" id="ask_email-lbl"><?php echo JText::_('COM_DJCLASSIFIEDS_YOUR_EMAIL'); ?></label>
			   		<input type="text" class="inputbox required validate-email" value="<?php echo $session->get('askform_email',''); ?>" name="ask_email" id="ask_email" />	   
			   		<label for="ask_message" id="ask_message-lbl"><?php echo JText::_('COM_DJCLASSIFIEDS_MESSAGE'); ?></label>
			   		<textarea id="ask_message" name="ask_message" rows="5" cols="55" class="inputbox required"><?php echo $session->get('askform_message',''); ?></textarea>
				<?php
					echo recaptcha_get_html($publickey, $error); 
			   	}?>
			   		
			   <button class="button" type="submit" id="submit_b" ><?php echo JText::_('COM_DJCLASSIFIEDS_SEND'); ?></button>
			   <input type="hidden" name="ask_status" id="ask_status" value="0" />
			   <input type="hidden" name="item_id" id="item_id" value="<?php echo $i->id; ?>">
			   <input type="hidden" name="cid" id="cid" value="<?php echo $i->cat_id; ?>">
			   <input type="hidden" name="option" value="com_djclassifieds" />
			   <input type="hidden" name="view" value="item" />
			   <input type="hidden" name="task" value="ask" />
			</form> 	 
			</div>
		<?php } ?>
			<?php 
		if($par->get('abuse_reporting','0')==1 && $par->get('notify_user_email','')!=''){?>
			<div id="abuse_form" style="display:none;overflow:hidden;">
			<form action="index.php" method="post" name="djabuseForm" id="djabuseForm" class="form-validate">
			   <label for="abuse_message" id="abuse_message-lbl"><?php echo JText::_('COM_DJCLASSIFIEDS_MESSAGE'); ?></label><br />
			   <textarea id="abuse_message" name="abuse_message" rows="5" cols="55" class="inputbox required"></textarea><br />
			   <button class="button" type="submit" id="submit_b" ><?php echo JText::_('COM_DJCLASSIFIEDS_SEND'); ?></button>
			   <input type="hidden" name="abuse_status" id="abuse_status" value="0" />
			   <input type="hidden" name="item_id" id="item_id" value="<?php echo $i->id; ?>">
			   <input type="hidden" name="cid" id="cid" value="<?php echo $i->cat_id; ?>">
			   <input type="hidden" name="option" value="com_djclassifieds" />
			   <input type="hidden" name="view" value="item" />
			   <input type="hidden" name="task" value="abuse" />
			</form> 	 
			</div>
		<?php } ?>
			<div style="clear:both"></div>
		</div>
		<?php 
		if(!$i->description){$i->description = $i->intro_desc;}
		echo '<div class="description"><h2>'.JText::_('COM_DJCLASSIFIEDS_DESCRIPTION').'</h2>'.$i->description.'</div>';		
	
			$modules_djcf = &JModuleHelper::getModules('djcf-item-description');
				if(count($modules_djcf)>0){
					echo '<div class="djcf-ad-item-description clearfix">';
					foreach (array_keys($modules_djcf) as $m){
						echo JModuleHelper::renderModule($modules_djcf[$m],$mod_attribs);
					}
					echo'</div>';		
				}
			
	/*
		if($i->image_url!=''){
			echo '<div class="images"><h2>'.JText::_('COM_DJCLASSIFIEDS_IMAGES').'</h2>';
			$images = explode(";",$i->image_url);
			for($ii=0; $ii<count($images)-1;$ii++ ){  //-1 bo wrzuca na koniec tablicy puste miejsce
			?>
	   	        <a href="<?php echo JURI::base().'components/com_djclassifieds/images/'.$images[$ii];?>.thb.jpg"  title=""  rel="lightbox-gallery" target="_blank"><img src="<?php echo JURI::base().'components/com_djclassifieds/images/'.$images[$ii].'.th.jpg';?>" style="top: 0;"/></a>
	   		<?php
			}
			echo '</div>';
		}*/
		?>	
		<?php if(count($this->fields)>0){
		?>
			<div class="custom_det">
				<h2><?php echo JText::_('COM_DJCLASSIFIEDS_CUSTOM_DETAILS'); ?></h2>
				<?php foreach($this->fields as $f){
					if($f->value){?>
					<div class="row">
						<span class="row_label"><?php echo JText::_($f->label); ?></span>
						<span class="row_value">
							<?php 
							if($f->type=='textarea'){							
								if($f->value==''){echo '---'; }
								else{echo '<br />'.$f->value;}								
							}else if($f->type=='checkbox'){
								if($f->value==''){echo '---'; }
								else{
									echo str_ireplace(';', ', ', substr($f->value,1,-1));
								}
							}else{
								if($f->value==''){echo '---'; }
								else{echo $f->value;}	
							}
							?>
						</span>
					</div>		
				<?php } 
				} ?>
			</div>
		<?php }?>
		<div class="localization_det"><h2><?php echo JText::_('COM_DJCLASSIFIEDS_LOCALIZATION'); ?></h2>
			<div class="row">
				<span class="row_value" style="display:inline-block;">
					<?php 
					echo $this->address.'<br />';
					echo $i->address;				
					?>
				</span>
			</div>
			<?php 
			if($par->get('show_googlemap')==1){ ?>
				<div id="google_map_box" style="display:none;">
					 <div id='map' style='width: 320px; height: 210px;'>						  
					 </div>					 
					 <div class="map_info"><?php echo JText::_('COM_DJCLASSIFIEDS_MAP_ACCURACY')?></div>
					<?php if($par->get('show_gm_driving')==1){ ?>
						<form action="http://maps.google.com/maps" method="get" class="gm_drive_dir" target="_blank">
							<label><?php echo JText::_('COM_DJCLASSIFIEDS_DRIVE_DIRECTIONS');?></label>
							<input type="text" class="inputbox" name="saddr" value="" />
							<input class="button" type="submit" value="<?php echo JText::_('COM_DJCLASSIFIEDS_SEARCH'); ?>" />
							<input type="hidden" name="daddr" value="<?php echo $this->country;if($this->city!='' ){echo ", ".$this->city;} if($this->item->address!='' ){echo ", ".$this->item->address;}?>" />
						</form>
					<?php } ?>								      
				</div>	
			<?php }	?>		
		</div>
		<?php if((int)$par->get('showaddetails','1')){?>
		<div class="additional"><h2><?php echo JText::_('COM_DJCLASSIFIEDS_AD_DETAILS'); ?></h2>
			<div class="row">
				<span class="row_label"><?php echo JText::_('COM_DJCLASSIFIEDS_AD_ID'); ?>:</span>
				<span class="row_value"><?php echo $i->id; ?></span>
			</div>
			<div class="row">
				<span class="row_label"><?php echo JText::_('COM_DJCLASSIFIEDS_DISPLAYED'); ?>:</span>
				<span class="row_value"><?php echo $i->display; ?></span>
			</div>
			<div class="row">
				<span class="row_label"><?php echo JText::_('COM_DJCLASSIFIEDS_AD_EXPIRES'); ?>:</span>
				<span class="row_value"><?php echo $i->date_exp; ?></span>
			</div>
		</div>
		<?php }?>
		<?php if($par->get('sb_position','0')=='bottom' && $par->get('sb_code','')!=''){
				echo '<span class="sb_bottom">'.$par->get('sb_code','').'</span>';
			}?> 
		<div style="clear:both" ></div>
	</div>
	</div>	
</div>

<script type="text/javascript">
	/*var mySlide = new Fx.Slide('ask_form');
	$('ask_form_button').addEvent('click', function(e){
		var af = document.getElementById("ask_form");
		af.style.display='block'; 
		e = new Event(e);
		mySlide.toggle();
		e.stop();
	});*/
/*function ask(){

var af = document.getElementById("ask_form");
	var $user_id = <?php echo $user->id;?>;
	if($user_id=='0'){
		alert('<?php echo JText::_('COM_DJCLASSIFIEDS_PLEASE_LOGIN');?>');
	}else{
		if(document.getElementById("ask_status").value=='0'){
			af.style.display='block'; 
			document.getElementById("ask_status").value='1'
		}else{
			af.style.display='none';
			document.getElementById("ask_status").value='0'
		}
	}

}*/

window.addEvent('load', function(){
	<?php if($i->image_url!=''){ ?>
			var img_width = $('dj-classifieds').getElement('.djc_images').getSize().x;
			var dj_item = $('dj-classifieds').getElement('.dj-item-in').getSize().x;
			var general_det = dj_item-img_width-19; 		
			$('dj-classifieds').getElement('.general_det').setStyle('width',general_det) ; 	
	<?php } ?>
	<?php  if($i->user_id>0 && ($par->get('ask_seller_type','0')==1 || ($user->id>0 && $par->get('ask_seller_type','0')==0))){ ?>
			if ($('ask_form_button') && $('ask_form')) {
				$('ask_form').setStyle('display','block');
				var formTogglerFx = new Fx.Tween('ask_form' ,{duration: 300});
				var formTogglerHeight = $('ask_form').getSize().y;
				
				$('ask_form_button').addEvent('click', function(){
					if($('ask_form').getStyle('height').toInt() > 0){
						//this.galleryFx.start('opacity',0,1);
						formTogglerFx.start('height',0);
					}else{
						formTogglerFx.start('height',formTogglerHeight);
						
					}
				return false;
				});
			
				$('ask_form').setStyle('height',0);
			}
		
	<?php }else if($par->get('ask_seller','0')==1 && $i->user_id>0){?>	
			$('ask_form_button').addEvent('click', function(){
				alert('<?php echo JText::_('COM_DJCLASSIFIEDS_PLEASE_LOGIN');?>');						
			});
	<?php }?>
	
	<?php if($par->get('abuse_reporting','0')==1 && $par->get('notify_user_email','')!='' && $user->id>0){?>
			if ($('abuse_form_button') && $('abuse_form')) {
				$('abuse_form').setStyle('display','block');
				var formTogglerFx2 = new Fx.Tween('abuse_form' ,{duration: 300});
				var formTogglerHeight2 = $('abuse_form').getSize().y;
				
				$('abuse_form_button').addEvent('click', function(){
					if($('abuse_form').getStyle('height').toInt() > 0){
						formTogglerFx2.start('height',0);
					}else{
						formTogglerFx2.start('height',formTogglerHeight2);
						
					}
				return false;
				});
			
				$('abuse_form').setStyle('height',0);
			}
		
	<?php }else if($par->get('abuse_reporting','0')==1 && $par->get('notify_user_email','')!=''){?>
			$('abuse_form_button').addEvent('click', function(){
				alert('<?php echo JText::_('COM_DJCLASSIFIEDS_PLEASE_LOGIN');?>');						
			});
	<?php }?>
		
	<?php  if(JRequest::getInt('ae',0)){ ?>
			$('ask_form_button').fireEvent('click');	
	<?php	}?>
	
	<?php if($par->get('show_googlemap')==1){?>
		mapaStart();
	<?php }?>
});
	

<?php if($par->get('show_googlemap')==1){ 
	$marker_txt = '<div style="width:200px"><div style="margin-bottom:5px;"><strong>'.$this->item->name.'</strong></div>';
	$marker_txt .= str_ireplace("\r\n", '<br />', $this->item->intro_desc).'<br />'; 
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
			$marker_txt .= '<img width="60px" src="'.$path.$images[$ii].'.ths.jpg" /> ';
			if($ii==3){
				break;
			}
		}
	}
	$marker_txt .='</div></div>';	

?>
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
			
			var adres = '<?php echo $this->country;if($this->city!='' ){echo ", ".$this->city;} if($this->item->address!='' ){echo ", ".$this->item->address;}?>';
			geokoder.geocode({address: adres}, function (results, status)
			{
			    if(status == google.maps.GeocoderStatus.OK)
			    {
			    	document.getElementById("google_map_box").style.display='block';
				    var MapOptions = {
				       zoom: <?php echo $par->get('gm_zoom','10'); ?>,
				  		center: results[0].geometry.location,
				  		mapTypeId: google.maps.MapTypeId.ROADMAP,
				  		navigationControl: true
				    };
				    map = new google.maps.Map(document.getElementById("map"), MapOptions); 
				     var size = new google.maps.Size(32,32);
	               //  var size_shadow = new google.maps.Size(25,25);
	                 var start_point = new google.maps.Point(0,0);
	                 var anchor_point = new google.maps.Point(0,16);
	            		<?php if($par->get('gm_icon',1)==1 && file_exists(JPATH_BASE.'/images/djcf_gmicon.png')){ ?>
		                	 var icon = new google.maps.MarkerImage("<?php echo JURI::base();?>images/djcf_gmicon.png", size, start_point, anchor_point);
		                <?php }elseif($par->get('gm_icon',1)==1){ ?>
		                	var icon = new google.maps.MarkerImage("<?php echo JURI::base();?>components/com_djclassifieds/assets/images/djcf_gmicon.png", size, start_point, anchor_point);
		                <?php }else{ ?>
		              		 var icon = '';  	
		                <?php }?>	
			    	var marker = addMarker(results[0].geometry.location,'<?php echo addslashes($marker_txt); ?>',icon);
					//google.maps.event.trigger(marker,'click');
			    }
			    else{
			        
			    }
			});		      
		 }


<?php }?>


	this.DJCatImageSwitcher = function (){
		var mainimagelink = $('djc_mainimagelink');
		var mainimage = $('djc_mainimage');
		var thumbs = $('djc_thumbnails') ? $('djc_thumbnails').getElements('img') : null;
		var thumblinks = $('djc_thumbnails') ? $('djc_thumbnails').getElements('a') : null;
		
		/*if (mainimagelink && mainimage) {
			mainimagelink.removeEvents('click').addEvent('click',function(evt){
				var rel = mainimagelink.rel;
				$(rel).fireEvent('click');
				return false;
			});
		}*/
		
		if(mainimagelink && mainimage) {
			mainimagelink.removeEvents('click').addEvent('click', function(evt) {
				var rel = mainimagelink.rel;
				$(rel).fireEvent('click', $(rel));

				if(!/android|iphone|ipod|series60|symbian|windows ce|blackberry/i.test(navigator.userAgent)) {
					return false;
				}
				return true;
			});
		}
		
		if (!mainimage || !mainimagelink || !thumblinks || !thumbs) return false;
		
		thumblinks.each(function(thumblink,index){
			var fx = new Fx.Tween(mainimage, {link: 'cancel', duration: 400});

			thumblink.addEvent('click',function(element){
				new Event(element).stop();
								
				var img = new Image();
				img.onload = function() {
					fx.start('opacity',0,1);
				};
				
				/*(function(){
					mainimagelink.href = thumblink.href;
					mainimagelink.title = thumblink.title;
					mainimagelink.rel = 'djc_lb_'+index;
					mainimage.src = thumblink.rel;
					mainimage.alt = thumblink.title;
				}).delay(400);*/
				fx.start('opacity',1,0).chain(function(){
					mainimagelink.href = thumblink.href;
					mainimagelink.title = thumblink.title;
					mainimagelink.rel = 'djc_lb_'+index;
					img.src = thumblink.rel;
					mainimage.src = img.src;
					mainimage.alt = thumblink.title;
				});
				
				return false;
			});
		});
	}; 
	
	window.addEvent('domready', function(){
		DJCatImageSwitcher();
	});
</script>
