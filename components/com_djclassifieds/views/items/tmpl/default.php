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
$toolTipArray = array('className'=>'djcf');
JHTML::_('behavior.tooltip', '.Tips1', $toolTipArray);
$par = &JComponentHelper::getParams( 'com_djclassifieds' );
$user= JFactory::getUser();

$main_id= JRequest::getVar('cid', 0, '', 'int');
$fav_a	= $par->get('favourite','1');
$icon_new_a	= $par->get('icon_new','1');
$icon_new_date = mktime(date("G"), date("i"), date("s"), date("m"), date("d")-$par->get('icon_new_time','3'), date("Y"));

$order = JRequest::getCmd('order', $par->get('items_ordering','date_e'));
$ord_t = JRequest::getCmd('ord_t', $par->get('items_ordering_dir','desc'));
if($ord_t=="desc"){
	$ord_t='asc';
}else{
	$ord_t='desc';
}

$sw = JRequest::getVar('search', '', '', 'string');
$uid	= JRequest::getVar('uid', 0, '', 'int');
$se = JRequest::getVar('se', '0', '', 'int');

$Itemid = JRequest::getVar('Itemid', 0, 'int');
?>
<div class="items">
<?php	
	$r=TRUE;
	?>
	<table class="dj-items" width="100%">
	<tr class="main_title">
		<?php if($order=="title"){$class="active";}else{$class="normal";}?>
		<th colspan="2" class="name first <?php echo $class; ?>">
			<a class="<?php echo $class; ?>" href="index.php?option=com_djclassifieds&view=items&cid=<?php echo $main_id; ?>&order=title&ord_t=<?php echo $ord_t.'&Itemid='.$Itemid;?><?php if($sw){ echo '&search='.$sw; };if($uid){ echo '&uid='.$uid; }?>">
				<?php echo JText::_('COM_DJCLASSIFIEDS_TITLE');
				if($order=="title"){
					if($ord_t=='asc'){ echo '<img src="'.JURI::base().'/components/com_djclassifieds/assets/images/sort_desc.gif" />';
					}else{ echo '<img src="'.JURI::base().'/components/com_djclassifieds/assets/images/sort_asc.gif" />';}					
				}else{	echo '<img src="'.JURI::base().'/components/com_djclassifieds/assets/images/sort.gif" />'; }?>
			</a> 
		</th>
		<?php if($order=="cat"){$class="active";}else{$class="normal";}?>
		<th class="<?php echo $class; ?>">
			<a class="<?php echo $class; ?>" href="index.php?option=com_djclassifieds&view=items&cid=<?php echo $main_id; ?>&order=cat&ord_t=<?php echo $ord_t.'&Itemid='.$Itemid;?><?php if($sw){ echo '&search='.$sw; };if($uid){ echo '&uid='.$uid; }?>">
				<?php echo JText::_('COM_DJCLASSIFIEDS_CATEGORY');
				if($order=="cat"){
					if($ord_t=='asc'){ echo '<img src="'.JURI::base().'/components/com_djclassifieds/assets/images/sort_desc.gif" />';
					}else{ echo '<img src="'.JURI::base().'/components/com_djclassifieds/assets/images/sort_asc.gif" />';}					
				}else{	echo '<img src="'.JURI::base().'/components/com_djclassifieds/assets/images/sort.gif" />'; }?>				
			</a> 
		</th>
		<th>
			<?php echo JText::_('COM_DJCLASSIFIEDS_DESCRIPTION');?>			 
		</th>
		<?php if($par->get('column_loc','1') && $par->get('show_regions','1')){?>
		<?php if($order=="loc"){$class="active";}else{$class="normal";}?>
		<th class="<?php echo $class; ?>">
			<a class="<?php echo $class; ?>" href="index.php?option=com_djclassifieds&view=items&cid=<?php echo $main_id; ?>&order=loc&ord_t=<?php echo $ord_t.'&Itemid='.$Itemid;?><?php if($sw){ echo '&search='.$sw; };if($uid){ echo '&uid='.$uid; }?>">
				<?php echo JText::_('COM_DJCLASSIFIEDS_LOCALIZATION');
				if($order=="loc"){
					if($ord_t=='asc'){ echo '<img src="'.JURI::base().'/components/com_djclassifieds/assets/images/sort_desc.gif" />';
					}else{ echo '<img src="'.JURI::base().'/components/com_djclassifieds/assets/images/sort_asc.gif" />';}					
				}else{	echo '<img src="'.JURI::base().'/components/com_djclassifieds/assets/images/sort.gif" />'; }?>			
			</a> 
		</th>
		<?php }
		if($par->get('column_price','1')){?>
		<?php if($order=="price"){$class="active";}else{$class="normal";}?>
		<th class="<?php echo $class; ?>">
			<a class="<?php echo $class; ?>" href="index.php?option=com_djclassifieds&view=items&cid=<?php echo $main_id; ?>&order=price&ord_t=<?php echo $ord_t.'&Itemid='.$Itemid;?><?php if($sw){ echo '&search='.$sw; };if($uid){ echo '&uid='.$uid; }?>">
			<?php echo JText::_('COM_DJCLASSIFIEDS_DATE_PRICE');
			if($order=="price"){
				if($ord_t=='asc'){ echo '<img src="'.JURI::base().'/components/com_djclassifieds/assets/images/sort_desc.gif" />';
				}else{ echo '<img src="'.JURI::base().'/components/com_djclassifieds/assets/images/sort_asc.gif" />';}					
			}else{	echo '<img src="'.JURI::base().'/components/com_djclassifieds/assets/images/sort.gif" />'; }?>
			</a> 
		</th>		
		<?php }
		if($par->get('column_date_a','1')){?>
		<?php if($order=="date_a"){$class="active";}else{$class="normal";}?>
		<th class="<?php echo $class; ?>">
			<a class="<?php echo $class; ?>" href="index.php?option=com_djclassifieds&view=items&cid=<?php echo $main_id; ?>&order=date_a&ord_t=<?php echo $ord_t.'&Itemid='.$Itemid;?><?php if($sw){ echo '&search='.$sw; };if($uid){ echo '&uid='.$uid; }?>">
				<?php echo JText::_('COM_DJCLASSIFIEDS_DATE_ADDED');
				if($order=="date_a"){
					if($ord_t=='asc'){ echo '<img src="'.JURI::base().'/components/com_djclassifieds/assets/images/sort_desc.gif" />';
					}else{ echo '<img src="'.JURI::base().'/components/com_djclassifieds/assets/images/sort_asc.gif" />';}					
				}else{	echo '<img src="'.JURI::base().'/components/com_djclassifieds/assets/images/sort.gif" />'; }?>
			</a> 
		</th>
		<?php }
		if($par->get('column_date_e','1')){?>
		<?php if($order=="date_e"){$class="active";}else{$class="normal";}?>
		<th class="<?php echo $class; ?>">
			<a class="<?php echo $class; ?>" href="index.php?option=com_djclassifieds&view=items&cid=<?php echo $main_id; ?>&order=date_e&ord_t=<?php echo $ord_t.'&Itemid='.$Itemid;?><?php if($sw){ echo '&search='.$sw; };if($uid){ echo '&uid='.$uid; }?>">
				<?php echo JText::_('COM_DJCLASSIFIEDS_DATE_EXPIRATION');
				if($order=="date_e"){
					if($ord_t=='asc'){ echo '<img src="'.JURI::base().'/components/com_djclassifieds/assets/images/sort_desc.gif" />';
					}else{ echo '<img src="'.JURI::base().'/components/com_djclassifieds/assets/images/sort_asc.gif" />';}					
				}else{	echo '<img src="'.JURI::base().'/components/com_djclassifieds/assets/images/sort.gif" />'; }?>
			</a> 
		</th>
		<?php }
		if($par->get('column_displayed','1')){?>
			<?php if($order=="display"){$class="active";}else{$class="normal";}?>
		<th class="<?php echo $class; ?>">
			<a class="<?php echo $class; ?>" href="index.php?option=com_djclassifieds&view=items&cid=<?php echo $main_id; ?>&order=display&ord_t=<?php echo $ord_t.'&Itemid='.$Itemid;?><?php if($sw){ echo '&search='.$sw; };if($uid){ echo '&uid='.$uid; }?>">
				<?php echo JText::_('COM_DJCLASSIFIEDS_DISPLAYED');
				if($order=="display"){
					if($ord_t=='asc'){ echo '<img src="'.JURI::base().'/components/com_djclassifieds/assets/images/sort_desc.gif" />';
					}else{ echo '<img src="'.JURI::base().'/components/com_djclassifieds/assets/images/sort_asc.gif" />';}					
				}else{	echo '<img src="'.JURI::base().'/components/com_djclassifieds/assets/images/sort.gif" />'; }?>
			</a>			 
		</th>
		<?php }?>
	</tr>		
	<?php
	if($par->get('showitem_jump',0)){
		$anch = '#dj-classifieds';
	}else{
		$anch='';
	}
	foreach($this->items as $i){
		$row = $r==TRUE ? '0' : '1';
		$r=!$r;
		//if($i->special==1){$row.=' special special_first';}
		if($i->promotions){
			$row.=' promotion '.str_ireplace(',', ' ', $i->promotions);
		}
		$icon_fav=0;
		if($user->id>0 && $fav_a){
			if($i->f_id){
				$icon_fav=1;
				$row .= ' item_fav';  
			}
		}
		$icon_new=0;		
		$date_start = strtotime($i->date_start);
		if($date_start>$icon_new_date && $icon_new_a){
			$icon_new=1;
			$row .= ' item_new';  
		}
		echo '<tr class="row'.$row.'">';
			echo '<td class="icon first">';
			if($par->get('tooltip_img','1') || $par->get('tooltip_title','1')){												
					$tip_title=str_ireplace('"',"'",$i->name);
					$tip_cont = '<div class=\'row_title\'>'.JText::_('COM_DJCLASSIFIEDS_DESCRIPTION').'</div><div class=\'desc\'>'.str_ireplace('"',"'",strip_tags(substr($i->description,0,500).'...')).'</div>';
					if($par->get('show_contact','1')){
						$tip_cont .= '<div class=\'row_title\'>'.JText::_('COM_DJCLASSIFIEDS_CONTACT').'</div><div class=\'contact\'>'.$i->contact.'</div>';
					}
						if($par->get('tooltip_price','1')){ 			
							$tip_cont .= '<div class=\'row_title	\'>'.JText::_('COM_DJCLASSIFIEDS_PRICE').'</div><div class=\'price\'>';
								if($par->get('unit_price_position','0')){
									$tip_cont .= $par->get('unit_price','EUR').' '.$i->price;
								}else{
									$tip_cont .= $i->price.' '.$par->get('unit_price','EUR');								
								}
							$tip_cont .= '</div>';
						}
						$timg_limit = $par->get('tooltip_images','3');
						if($i->image_url!='' && $timg_limit>0){
							$tip_cont .= '<div class=\'title\'>'.JText::_('COM_DJCLASSIFIEDS_IMAGES').'</div><div class=\'images_box\'>';
							$images = explode(";",$i->image_url);
							
							
							for($ii=0; $ii<count($images)-1;$ii++ ){
								if($timg_limit==$ii){break;}  				
			   	        		$tip_cont .= '<img src=\''.JURI::base().'components/com_djclassifieds/images/'.$images[$ii].'.ths.jpg\' />';   				
							}
							$tip_cont .= '</div>';
						}
				}

			echo '<a href="index.php?option=com_djclassifieds&view=item&cid='.$i->cat_id.'&id='.$i->id.'&Itemid='.$Itemid.$anch.'">';
				if($i->image_url!=''){
					$images=explode(';',$i->image_url);									
					echo '<img src="'.JURI::base().'/components/com_djclassifieds/images/'.$images[0].'.ths.jpg"';
						if((int)$par->get('tooltip_img','1')){
							echo 'class="Tips1" title="'.$tip_title.' :: '.$tip_cont.'"';
						}
					 echo  '/>';					
				}else{
					echo '<img src="'.JURI::base().'/components/com_djclassifieds/assets/images/no-image.png" ';  
					if((int)$par->get('tooltip_img','1')){
							echo 'class="Tips1" title="'.$tip_title.' :: '.$tip_cont.'"';
						}
					echo '/>';
				}
			echo '</a>';	

		echo '</td>';
		echo '<td class="name">';					
			if((int)$par->get('tooltip_title','1')){
				echo '<h3><a class="title Tips1" href="index.php?option=com_djclassifieds&view=item&cid='.$i->cat_id.'&id='.$i->id.'&Itemid='.$Itemid.$anch.'" title="'.$tip_title.' :: '.$tip_cont.'" >'.$i->name.'</a></h3>';
			}else{
				echo '<h3><a class="title" href="index.php?option=com_djclassifieds&view=item&cid='.$i->cat_id.'&id='.$i->id.'&Itemid='.$Itemid.$anch.'" >'.$i->name.'</a></h3>';
			}
			if($icon_fav){
				echo ' <img src="'.JURI::base().'/components/com_djclassifieds/assets/images/fav_a.png" width="16px" class="fav_ico"/>';
			}
			if($icon_new){
				echo ' <span class="new_icon">'.JText::_('COM_DJCLASSIFIEDS_NEW').'</span>';
			} 			
			
			if(strstr($i->promotions, 'p_special')){
				echo ' <img src="'.JURI::base().'/components/com_djclassifieds/assets/images/promo_star.png" width="31px" class="prom_ico"/>';
			} 		
		echo '</td>';
		echo '<td class="cat_name"><a href="index.php?option=com_djclassifieds&view=items&cid='.$i->cat_id.'&Itemid='.$Itemid.'" >'.$i->c_name.'</a></td>';
		echo '<td class="description"><a class="title" href="index.php?option=com_djclassifieds&view=item&cid='.$i->cat_id.'&id='.$i->id.'&Itemid='.$Itemid.$anch.'">';
			echo mb_substr($i->intro_desc, 0,$par->get('introdesc_char_limit','120'),'UTF-8');
		echo '</a></td>';
		if($par->get('column_loc','1') && $par->get('show_regions','1')){
			echo '<td class="region"><a href="index.php?option=com_djclassifieds&view=items&se=1&se_regs[]='.$i->region_id.'&Itemid='.$Itemid.'">'.$i->r_name.'</td>';
		}
		if($par->get('column_price','1')){
			echo '<td class="price">';
			if($i->price){
				if($par->get('unit_price_position','0')){
					echo $par->get('unit_price','EUR').' '.$i->price;
				}else{
					echo $i->price.' '.$par->get('unit_price','EUR');								
				}
			}else{
				echo '---';
			}				
			echo '</td>';
		}
		if($par->get('column_date_a','1')){
			echo '<td class="date_start">'.$i->date_start.'</td>';
		}
		if($par->get('column_date_e','1')){
			echo '<td class="date_exp">'.$i->date_exp.'</td>';
		}
		if($par->get('column_displayed','1')){
			echo '<td class="date_exp last" align="center">'.$i->display.'</td>';
		}		
		echo '</tr>';
	}

		//echo '<form action="'.JFactory::getURI().'" method="GET">';	     
			//echo $this->pagination->getLimitBox();						 			
			//echo $this->pagination->getListFooter();
		//echo '</form>'; ?>
	</table>
	<div class="pagination">
		<?php echo $this->pagination->getPagesLinks(); ?> 
	</div>
	<?php 
	if($se>0 && count($this->items)==0){
		echo '<div class="no_results">';
			echo JText::_('COM_DJCLASSIFIEDS_NO_RESULTS');
		echo '</div>';
	}
	?>
</div>	

</div>
