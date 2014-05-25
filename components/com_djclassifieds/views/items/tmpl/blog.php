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

$Itemid = JRequest::getVar('Itemid', 0, 'int');

?>

<div class="dj-items-blog"><div class="djcf_items_blog">
<?php	
	$r=TRUE;
	?>
	
	<?php
	if($par->get('showitem_jump',0)){
		$anch = '#dj-classifieds';
	}else{
		$anch='';
	}
	//$img_w = $par->get("middleth_width")+15;
	$col_n = 100 / $par->get('blog_columns_number',2) - 0.1;
	$col_limit = $par->get('blog_columns_number',2);	
	$ii=0;	
	
	foreach($this->items as $i){		
		$cn= $ii%$par->get('blog_columns_number');
		//echo $col_limit.' '.$cn;
		if($cn==$col_limit-1){
			$cn .= ' last_col';
		}
		$ii++;
		//if($i->special==1){$row=' special special_first';}else{$row='';}
		$row = '';
		if($i->promotions){
			$row .=' promotion '.str_ireplace(',', ' ', $i->promotions);
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
		
		echo '<div class="item_box'.$row.' clearfix" style="width:'.$col_n.'%;"><div class="item_box_bg'.$cn.'"><div class="item_box_in"><div class="item_box_in2">';
		echo '<div class="title">';
			echo '<h2><a href="index.php?option=com_djclassifieds&view=item&cid='.$i->cat_id.'&id='.$i->id.'&Itemid='.$Itemid.$anch.'" >'.$i->name.'</a></h2>';			
			if($icon_fav){
				echo ' <img src="'.JURI::base().'/components/com_djclassifieds/assets/images/fav_a.png" width="16px" class="fav_ico"/>';
			}			
			if($icon_new){
				echo ' <span class="new_icon">'.JText::_('COM_DJCLASSIFIEDS_NEW').'</span>';
			}
		echo '</div>';
		echo '<div class="blog_det">';	
			
			if($par->get('blog_desc_position','right')=='right'){
				echo '<div class="item_box_right">';
			}									
				if($i->image_url!=''){
					echo '<div class="item_img">';
					$images=explode(';',$i->image_url);	
					echo '<a href="index.php?option=com_djclassifieds&view=item&cid='.$i->cat_id.'&id='.$i->id.'&Itemid='.$Itemid.$anch.'">';					
					echo '<img src="'.JURI::base().'/components/com_djclassifieds/images/'.$images[0].'.thm.jpg"  />';
					echo '</a>';
					echo '</div>';
				}																						
							//echo '<div class="cat_name"><span class="label_title">'.JText::_('COM_DJCLASSIFIEDS_CATEGORY').'</span>'.$i->c_name.'</div>';								
			if($par->get('blog_desc_position','right')=='right'){
				echo '<div class="item_desc"><span class="label_title">'.JText::_('COM_DJCLASSIFIEDS_DESCRIPTION').'</span>';
					echo '<span class="desc_info">'.mb_substr($i->intro_desc, 0,$par->get('introdesc_char_limit','120'),'UTF-8');
				echo '</span></div>';
				echo '<div style="clear:both"></div>';	
				echo '</div>';		
			}
				
				if($par->get('column_loc','1') && $i->r_name && $par->get('show_regions','1')){
					echo '<div class="region"><span class="label_title"></span>'.$i->r_name.'</div>';
				}
				if($par->get('column_price','1') && $i->price){
					echo '<div class="price"><span class="label_title"></span>';
						if($par->get('unit_price_position','0')){
							echo $par->get('unit_price','EUR').' '.$i->price;
						}else{
							echo $i->price.' '.$par->get('unit_price','EUR');								
						}
					echo '</div>';	
				}
				/*if($par->get('column_date_a','1')){
					echo '<div class="date_start"><span class="label_title"></span>'.$i->date_start.'</div>';
				}*/							
				if($par->get('blog_desc_position','right')=='bottom'){			
					echo '<div class="item_box_bottom">';		
						echo '<div class="item_desc"><span class="label_title">'.JText::_('COM_DJCLASSIFIEDS_DESCRIPTION').'</span>';
							echo '<span class="desc_info">'.mb_substr($i->intro_desc, 0,$par->get('introdesc_char_limit','120'),'UTF-8');
						echo '</span></div>';					
					echo '</div>';	
				}
			
			echo '<div class="see_details_box"><a class="see_details" href="index.php?option=com_djclassifieds&view=item&cid='.$i->cat_id.'&id='.$i->id.'&Itemid='.$Itemid.$anch.'" >'.JText::_('COM_DJCLASSIFIEDS_SEE_DETAILS').'</a></div>';					
	
		echo '</div>';
		if(strstr($i->promotions, 'p_special')){
			echo '<span class="p_special_img">&nbsp;</span>';
		} 
		echo '</div></div></div></div>';
	}
	?>	
	<?php
		echo '<br style="clear:both"/>';
		echo '<div class="pagination" >';	     
			echo $this->pagination->getPagesLinks(); 
		echo '</div>';
	?>
	</div>

</div>	
</div>
<script type="text/javascript">
	function DJCatMatchModules(className){
	var maxHeight = 0;
	if ($$(className)) {
		var divs = $$(className);
		divs.each(function(element){
			maxHeight = Math.max(maxHeight, parseInt(element.getStyle('height')));
		});
		
		divs.setStyle('height', maxHeight);
	}
}

window.addEvent('load', function(){
	DJCatMatchModules('.item_box_in2');
});

</script>