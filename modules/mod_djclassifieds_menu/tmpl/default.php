<?php
/**
* @version 2.0
* @package DJ Flyer
* @subpackage DJ Flyer Module
* @copyright Copyright (C) 2010 DJ-Extensions.com LTD, All rights reserved.
* @license http://www.gnu.org/licenses GNU/GPL
* @author url: http://design-joomla.eu
* @author email contact@design-joomla.eu
* @developer Łukasz Ciastek - lukasz.ciastek@design-joomla.eu
*
*
* DJ Flyer is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* DJ Flyer is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with DJ Flyer. If not, see <http://www.gnu.org/licenses/>.
*
*/

//$new_ad_link = JRoute::_($article_link);
$new_ad_link='index.php?option=com_djclassifieds&view=additem';
if($menu_newad_itemid){
	$new_ad_link .= '&Itemid='.$menu_newad_itemid->id;
}
$par = &JComponentHelper::getParams( 'com_djclassifieds' );
$max_level = $params->get('max_level','0'); 
	?>
		<div class="djcf_menu">
		<?php 
		if($params->get('new_ad_link','0')==1){
			echo '<div class="newad_link_top"><a class="button" href="'.$new_ad_link.'">'.JText::_('MOD_DJCLASSIFIEDS_MENU_NEW_ADD').'</a></div>';
		}		
		if($cats){ ?>	
		<ul class="menu">
			<?php 
				$c_count=0;
				$prev_l = 0;
				//echo '<li>';
				foreach($cats as $c){
					if($max_level>0 && $c->level>=$max_level){
						continue;
					}
											
					if($prev_l>$c->level){
						for($i=0;$i<$prev_l-$c->level;$i++){
							echo '</ul>';	
						}
						
					}else if($prev_l<$c->level){
						echo '<ul>';
					}else if($c_count>0){
						echo '</li>';
					}
					
					$prev_l = $c->level;
					if(strstr($cat_path,','.$c->id.',')){
						if($cid==$c->id){
							$cl='class="active current"';
						}else{
							$cl='class="active"';
						}
					}else{
						$cl='';
					}
					
					echo '<li '.$cl.'><a href="index.php?option=com_djclassifieds&view=items&cid='.$c->id.$itemid.'">'.$c->name.'</a>';	
					$c_count++;
				}		
				if($prev_l>0){
					for($i=0;$i<$prev_l;$i++){
						echo '</li></ul>';	
					}					
				}	
			?>			
		</ul>
		<?php
		} 
		if($params->get('new_ad_link','0')==2){
			echo '<div class="newad_link_bottom"><a class="button" href="'.$new_ad_link.'">'.JText::_('MOD_DJCLASSIFIEDS_MENU_NEW_ADD').'</a></div>';
		}?>
	</div>	
	 