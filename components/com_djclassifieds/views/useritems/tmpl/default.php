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

$main_id= JRequest::getVar('cid', 0, '', 'int');
$it= JRequest::getVar('Itemid', 0, '', 'int');


$order = JRequest::getCmd('order', $par->get('items_ordering','date_e'));
$ord_t = JRequest::getCmd('ord_t', $par->get('items_ordering_dir','desc'));
if($ord_t=="desc"){
	$ord_t='asc';
}else{
	$ord_t='desc';
}

$sw = JRequest::getVar('search', '', '', 'string');
$uid	= JRequest::getVar('uid', 0, '', 'int');

	$menus	= JSite::getMenu();
	$menu_item = $menus->getItems('link','index.php?option=com_djclassifieds&view=items',1);
		
	$itemid = ''; 
	if($menu_item){
		$itemid='&Itemid='.$menu_item->id;
	}
?>
<div id="dj-classifieds" class="clearfix">
	<div class="title_top"><h1>
		<?php	echo JText::_('COM_DJCLASSIFIEDS_YOUR_ADS');?>
	</h1></div>
<div class="useritems">

<?php	
	$r=TRUE;
	?>
	<table class="dj-items" width="100%">
	<tr class="main_title">
		<?php if($order=="title"){$class="active";}else{$class="normal";}?>
		<th colspan="2" class="name first <?php echo $class; ?>">
			<a class="<?php echo $class; ?>" href="index.php?option=com_djclassifieds&view=useritems&cid=<?php echo $main_id; ?>&order=title&ord_t=<?php echo $ord_t;?><?php if($sw){ echo '&search='.$sw; };if($uid){ echo '&uid='.$uid; }?>">
				<?php echo JText::_('COM_DJCLASSIFIEDS_TITLE');
				if($order=="title"){
					if($ord_t=='asc'){ echo '<img src="'.JURI::base().'/components/com_djclassifieds/assets/images/sort_desc.gif" />';
					}else{ echo '<img src="'.JURI::base().'/components/com_djclassifieds/assets/images/sort_asc.gif" />';}					
				}else{	echo '<img src="'.JURI::base().'/components/com_djclassifieds/assets/images/sort.gif" />'; }?>
			</a> 
		</th>
		<?php if($order=="cat"){$class="active";}else{$class="normal";}?>
		<th class="<?php echo $class; ?>">
			<a class="<?php echo $class; ?>" href="index.php?option=com_djclassifieds&view=useritems&cid=<?php echo $main_id; ?>&order=cat&ord_t=<?php echo $ord_t;?><?php if($sw){ echo '&search='.$sw; };if($uid){ echo '&uid='.$uid; }?>">
				<?php echo JText::_('COM_DJCLASSIFIEDS_CATEGORY');
				if($order=="cat"){
					if($ord_t=='asc'){ echo '<img src="'.JURI::base().'/components/com_djclassifieds/assets/images/sort_desc.gif" />';
					}else{ echo '<img src="'.JURI::base().'/components/com_djclassifieds/assets/images/sort_asc.gif" />';}					
				}else{	echo '<img src="'.JURI::base().'/components/com_djclassifieds/assets/images/sort.gif" />'; }?>
			</a> 
		</th>
		<?php if($order=="loc"){$class="active";}else{$class="normal";}?>
		<?php /* 
		<th class="<?php echo $class; ?>">
			<?php echo JText::_('COM_DJCLASSIFIEDS_DESCRIPTION');?>			 
		</th>
		 */ ?> 
		<?php /* if($par->get('column_loc','1')){?>
		<th class="<?php echo $class; ?>">
			<a class="<?php echo $class; ?>" href="index.php?option=com_djclassifieds&view=useritems&cid=<?php echo $main_id; ?>&order=loc&ord_t=<?php echo $ord_t;?><?php if($sw){ echo '&search='.$sw; };if($uid){ echo '&uid='.$uid; }?>">
				<?php echo JText::_('COM_DJCLASSIFIEDS_LOCALIZATION');
				if($order=="loc"){
					if($ord_t=='asc'){ echo '<img src="'.JURI::base().'/components/com_djclassifieds/assets/images/sort_desc.gif" />';
					}else{ echo '<img src="'.JURI::base().'/components/com_djclassifieds/assets/images/sort_asc.gif" />';}					
				}else{	echo '<img src="'.JURI::base().'/components/com_djclassifieds/assets/images/sort.gif" />'; }?>		
			</a> 
		</th>
		<?php }*/
		//if($par->get('column_date_a','1')){?>
		<?php if($order=="date_a"){$class="active";}else{$class="normal";}?>
		<th class="<?php echo $class; ?>">
			<a class="<?php echo $class; ?>" href="index.php?option=com_djclassifieds&view=useritems&cid=<?php echo $main_id; ?>&order=date_a&ord_t=<?php echo $ord_t;?><?php if($sw){ echo '&search='.$sw; };if($uid){ echo '&uid='.$uid; }?>">
				<?php echo JText::_('COM_DJCLASSIFIEDS_DATE_ADDED');
				if($order=="date_a"){
					if($ord_t=='asc'){ echo '<img src="'.JURI::base().'/components/com_djclassifieds/assets/images/sort_desc.gif" />';
					}else{ echo '<img src="'.JURI::base().'/components/com_djclassifieds/assets/images/sort_asc.gif" />';}					
				}else{	echo '<img src="'.JURI::base().'/components/com_djclassifieds/assets/images/sort.gif" />'; }?>
			</a> 
		</th>
		<?php // }
		//if($par->get('column_date_e','1')){ ?>
		<?php if($order=="date_e"){$class="active";}else{$class="normal";}?>
		<th class="<?php echo $class; ?>">
			<a class="<?php echo $class; ?>" href="index.php?option=com_djclassifieds&view=useritems&cid=<?php echo $main_id; ?>&order=date_e&ord_t=<?php echo $ord_t;?><?php if($sw){ echo '&search='.$sw; };if($uid){ echo '&uid='.$uid; }?>">
			<?php echo JText::_('COM_DJCLASSIFIEDS_DATE_EXPIRATION');
			if($order=="date_e"){
				if($ord_t=='asc'){ echo '<img src="'.JURI::base().'/components/com_djclassifieds/assets/images/sort_desc.gif" />';
				}else{ echo '<img src="'.JURI::base().'/components/com_djclassifieds/assets/images/sort_asc.gif" />';}					
			}else{	echo '<img src="'.JURI::base().'/components/com_djclassifieds/assets/images/sort.gif" />'; }?></a> 
		</th>
		<?php //} ?>
		<?php if($order=="display"){$class="active";}else{$class="normal";}?>
		<th class="<?php echo $class; ?>">
			<a class="<?php echo $class; ?>" href="index.php?option=com_djclassifieds&view=useritems&cid=<?php echo $main_id; ?>&order=published&ord_t=<?php echo $ord_t;?><?php if($sw){ echo '&search='.$sw; };if($uid){ echo '&uid='.$uid; }?>">
			<?php echo JText::_('COM_DJCLASSIFIEDS_PUBLISHED');
			if($order=="published"){
				if($ord_t=='asc'){ echo '<img src="'.JURI::base().'/components/com_djclassifieds/assets/images/sort_desc.gif" />';
				}else{ echo '<img src="'.JURI::base().'/components/com_djclassifieds/assets/images/sort_asc.gif" />';}					
			}else{	echo '<img src="'.JURI::base().'/components/com_djclassifieds/assets/images/sort.gif" />'; }?></a>			 
		</th>
		<th>	
			<?php echo JText::_('COM_DJCLASSIFIEDS_ACTIONS'); ?>
		</th>
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
		if($i->special==1){$row.=' special special_first';}
		echo '<tr class="row'.$row.'">';
			echo '<td class="icon first">';
			
			if((int)$par->get('tooltip_img','1')){												
					$tip_title=str_ireplace('"',"'",$i->name);
					$tip_cont = '<div class=\'row_title\'>'.JText::_('Description').'</div><div class=\'desc\'>'.str_ireplace('"',"'",strip_tags($i->description)).'</div>';
						$tip_cont .= '<div class=\'row_title\'>'.JText::_('Contact').'</div><div class=\'contact\'>'.$i->contact.'</div>';
						$tip_cont .= '<div class=\'row_title	\'>'.JText::_('Price').'</div><div class=\'price\'>'.$i->price.'</div>';
						if($i->image_url!=''){
							$tip_cont .= '<div class=\'title\'>'.JText::_('Images').'</div><div class=\'images_box\'>';
							$images = explode(";",$i->image_url);
							
							for($ii=0; $ii<count($images)-1;$ii++ ){  				
			   	        		$tip_cont .= '<img src=\''.JURI::base().'components/com_djclassifieds/images/'.$images[$ii].'.ths.jpg\' />';   				
							}
							$tip_cont .= '</div>';
						}
				}

			echo '<a href="index.php?option=com_djclassifieds&view=item&cid='.$i->cat_id.'&id='.$i->id.$itemid.$anch.'">';
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
				echo '<a class="title Tips1" href="index.php?option=com_djclassifieds&view=item&cid='.$i->cat_id.'&id='.$i->id.$itemid.$anch.'" title="'.$tip_title.' :: '.$tip_cont.'" >'.$i->name.'</a>';
			}else{
				echo '<a class="title" href="index.php?option=com_djclassifieds&view=item&cid='.$i->cat_id.'&id='.$i->id.$itemid.$anch.'" >'.$i->name.'</a>';
			} 
		echo '</td>';
		echo '<td class="cat_name">'.$i->c_name.'</td>';
		//echo '<td class="description"><a class="title" href="index.php?option=com_djclassifieds&view=item&cid='.$i->cat_id.'&id='.$i->id.$itemid.$anch.'">'.$i->intro_desc.'</a></td>';
	/*	if($par->get('column_loc','1')){
			echo '<td class="region">'.$i->r_name.'</td>';
		}*/
		//if($par->get('column_date_a','1')){
			echo '<td class="date_start">'.$i->date_start.'</td>';
		//}
		//if($par->get('column_date_e','1')){
			echo '<td class="date_exp">';			
				if($i->s_active){
               		echo '<span title="'.$i->date_start.' - '.$i->date_exp.'" style="color:#559D01;font-weight:bold;" >'.$i->date_exp.'</span>';
            	}else{
	              	echo '<span title="'.$i->date_start.' - '.$i->date_exp.'" style="color:#C23C00;font-weight:bold;" >'.$i->date_exp.'</span>';
    	        }
			echo '</td>';
		//}
		
		echo '<td class="date_exp" align="center">';
			if($i->published){
				echo '<img src="'.JURI::base().'components/com_djclassifieds/assets/images/tick.png" alt="'.JText::_('JYES').'" />';	
			}else{
				echo '<img src="'.JURI::base().'components/com_djclassifieds/assets/images/publish_x.png" alt="'.JText::_('JNO').'" />';
			}
		echo '</td>';
		
		echo '<td class="last">';
				echo '<a class="button edit" href="index.php?option=com_djclassifieds&view=additem&id='.$i->id.$itemid.'">'.JText::_('COM_DJCLASSIFIEDS_EDIT').'</a><br />';
				echo '<a class="button renew" href="javascript:void(0)" onclick="confirm_renew(\''.$i->name.'\','.$i->id.')" >'.JText::_('COM_DJCLASSIFIEDS_RENEW').'</a><br />';
				echo '<a class="button delete" href="javascript:void(0)" onclick="confirm_del(\''.$i->name.'\','.$i->id.')" >'.JText::_('COM_DJCLASSIFIEDS_DELETE').'</a><br />';
		echo '</td>';		
		echo '</tr>';
		
	}
	?>	
	<?php
		/*$row = $r==TRUE ? '0' : '1';
		echo '<tr class="row'.$row.'"><td class="pagination" colspan="9" align="center" >';	     
			echo $this->pagination->getPagesLinks(); 
		echo '</td></tr>';*/
	?>
	</table>
	<div class="pagination">
		<?php echo $this->pagination->getPagesLinks(); ?> 
	</div>
</div>	

</div>
<script type="text/javascript">
	function confirm_del(title,id){
	
		var answer = confirm ('<?php echo JText::_('COM_DJCLASSIFIEDS_DELETE_CONFIRM');?>'+' "'+title+'"');
		if (answer){
			 window.location="index.php?option=com_djclassifieds&view=item&task=delete&id="+id+"&Itemid=<?php echo $it;?>";	
		}
	}
	
	function confirm_renew(title,id){
	
		var answer = confirm ('<?php echo JText::_('COM_DJCLASSIFIEDS_RENEW_CONFIRM');?>'+' "'+title+'"');
		if (answer){
			 window.location="index.php?option=com_djclassifieds&view=item&task=renew&id="+id+"&Itemid=<?php echo $it;?>";	
		}
	}
</script>