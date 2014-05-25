<?php
/**
* @version 2.0
* @package DJ Classifieds Menu Module
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

defined ('_JEXEC') or die('Restricted access');

$cols = $params->get('columns_nr','1');
$items_in_col =ceil ($params->get('items_nr') / $cols);
$col_nr = 1;  
$item_c = 0;
?>
<div class="mod_djclassifieds_items clearfix">
	<div class="items items-cols<?php echo $cols; ?>">
		<div class="items-col icol<?php echo $col_nr; ?>"><div class="icol-in">
		<?php	
			foreach($items as $i){
					
				if($item_c==$items_in_col){
					$col_nr++;
					echo '<div style="clear:both"></div>';
					echo '</div></div><div class="items-col icol'.$col_nr.'"><div class="icol-in">';
					$item_c=0;
				}
				$item_c++;	
				
				$item_class='';
				if($i->promotions){
					$item_class .=' promotion '.str_ireplace(',', ' ', $i->promotions);
				}
				
				echo '<div class="item'.$item_class.'">';
				echo '<div class="title">';
				if($i->image_url!='' && $params->get('show_img')==1){
					$images=explode(';',$i->image_url);			
					echo '<a class="title_img" href="index.php?option=com_djclassifieds&view=item&cid='.$i->cat_id.'&id='.$i->id.$itemid.'">';
					echo '<img style="margin-right:3px;" src="'.JURI::base().'/components/com_djclassifieds/images/'.$images[0].'.ths.jpg"  />';
					echo '</a>';
				}
				if($params->get('show_title','1')==1){ 
					echo '<a class="title" href="index.php?option=com_djclassifieds&view=item&cid='.$i->cat_id.'&id='.$i->id.$itemid.'">'.$i->name.'</a>';
				}
				if(($params->get('show_date')==1) || ($params->get('show_cat')==1) || ($params->get('show_price')==1)){
					echo '<div class="date_cat">';
					if($params->get('show_date')==1){
						echo '<span class="date">'.$i->date_start.'</span>';
					}
					if($params->get('show_cat')==1){				
						echo '<span class="category">';
						if($params->get('cat_link')==1){
							echo '<a class="title_cat" href="index.php?option=com_djclassifieds&view=items&cid='.$i->cat_id.$itemid.'">'.$i->c_name.'</a>';
						}else{
							echo $i->c_name;
						}				
						echo '</span>';
					}				
					if($params->get('show_price')==1 && $i->price){
						echo '<span class="price">';
							if($cfpar->get('unit_price_position','0')){
								echo $cfpar->get('unit_price','EUR').' '.$i->price;
							}else{
								echo $i->price.' '.$cfpar->get('unit_price','EUR');								
							} 
						echo '</span>';
					}
					echo '</div>';
				}		
				echo '</div>';
		
				if($params->get('show_description')==1){
					echo '<div class="desc">';
					if($params->get('desc_source','0')==1){
						echo $i->description;
					}else{
						if($params->get('desc_link')==1){
							echo '<a href="index.php?option=com_djclassifieds&view=item&cid='.$i->cat_id.'&id='.$i->id.$itemid.'">';
						}
							$desc_c = $params->get('char_desc_nr');
							if($desc_c!=0 && $i->intro_desc!='' && strlen($i->intro_desc)>$desc_c){
								echo mb_substr($i->intro_desc, 0, $desc_c,'utf-8').' ...';
							}else{
								echo $i->intro_desc;
							}
						if($params->get('desc_link')==1){
							echo '</a>';
						}	
					} 
					
					echo '</div>';
				}
				echo '</div>';
								
			}
		?>
		<div style="clear:both"></div>
		</div></div>
	</div>
</div>