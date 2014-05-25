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
	$menus	= JSite::getMenu();
	$menu_item = $menus->getItems('link','index.php?option=com_djclassifieds&view=items',1);
			
	$itemid = ''; 
	if($menu_item){
		$itemid='&Itemid='.$menu_item->id;
	}
	
	$page_title = $menus->getActive()->title ;

	if(!$page_title){
		//$page_title = JText::_('COM_DJCLASSIFIEDS_CATEGORY_TREE');	
	}
	
	$level_limit = $this->par->get('cattree_levels','0');	
	$cols_limit = $this->par->get('cattree_cols','3');
	$items_in_c_type = $this->par->get('cattree_ic','1');		
	
	$cats_c=0;
	if($cols_limit>1){
		if($level_limit>0){
			foreach($this->cats as $c ){
				if($c->level<$level_limit){
					$cats_c++;	
				}
			}
		}else{
			$cats_c=count($this->cats);	
		}
		
		$col_limit = ceil($cats_c/$cols_limit);
	}
	
	$ct_type  = $this->par->get('cattree_type',null);

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

	 $modules_djcf = &JModuleHelper::getModules('djcf-cattree-top');
			if(count($modules_djcf)>0){
				echo '<div class="djcf-ad-cattree-top clearfix">';
				foreach (array_keys($modules_djcf) as $m){
					echo JModuleHelper::renderModule($modules_djcf[$m],$mod_attribs);
				}
				echo'</div>';		
			}
			?>
			
	<div class="dj-cattree-box cols<?php echo $cols_limit ?>" >	
		<div class="title_top"><?php echo $page_title; ?></div>
		<div class="dj-cattree-wrap">
		<ul class="dj-cattree col1" >
			<?php 
				$c_count=0;
				$prev_l = 0;
				$col_n=1;
				//echo '<li>';
				foreach($this->cats as $c){
					
					if($level_limit>0 && $c->level>=$level_limit){
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
					
						if($c->level==0 && $c_count>0){
							if($ct_type){							
								$col_number = $col_n%$cols_limit;
								if($col_number==0){
									$col_number++;
									echo '</ul><div style="clear:both"></div><ul class="dj-cattree col'.$col_number.'">';
								}else{
									$col_number++;
									echo '</ul><ul class="dj-cattree col'.$col_number.'">';
								}							
								$col_n++;	
								
							}else{							
								if(ceil($c_count/$col_limit)==$col_n){
									$col_number = $col_n%$cols_limit;
									$col_number++;
									echo '</ul><ul class="dj-cattree col'.$col_number.'">';
									$col_n++;	
								}								
							}
						}
					
					
					$prev_l = $c->level;
					/*if(strstr($cat_path,','.$c->id.',')){
						if($cid==$c->id){
							$cl='class="active current"';
						}else{
							$cl='class="active"';
						}
					}else{*/
						$cl='';
				//	}
					
					
					$items_in_c = '';
					
					if($c->items_count){						
						if($items_in_c_type==2){
							$items_in_c = ' <span>('.$c->items_count.')</span>';		
						}else if($items_in_c_type==1 && $c->level==0){
							$items_in_c = ' <span>('.$c->items_count.')</span>';
						}
					}		
					
					
					
					echo '<li '.$cl.'><a href="index.php?option=com_djclassifieds&view=items&cid='.$c->id.$itemid.'">'.$c->name.$items_in_c.'</a>';	
					$c_count++;
				}		
				if($prev_l>0){
					for($i=0;$i<$prev_l;$i++){
						echo '</li></ul>';	
					}					
				}	
			?>			
		</ul>
		<div style="clear:both"></div>
		</div>
	</div>	
</div>
