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
JHTML::_('behavior.tooltip');
$toolTipArray = array('className'=>'djcf');
JHTML::_('behavior.tooltip', '.Tips1', $toolTipArray);
$par = &JComponentHelper::getParams( 'com_djclassifieds' );

$main_id= JRequest::getVar('cid', 0, '', 'int');
$user= JFactory::getUser();

$order = JRequest::getCmd('order', $par->get('items_ordering','date_e'));
$ord_t = JRequest::getCmd('ord_t', $par->get('items_ordering_dir','desc'));
if($ord_t=="desc"){
	$ord_t='asc';
}else{
	$ord_t='desc';
}

$se = JRequest::getVar('se', '0', '', 'int');
$uid	= JRequest::getVar('uid', 0, '', 'int');
$fav	= JRequest::getVar('fav', 0, '', 'int');
$fav_a	= $par->get('favourite','1');

$Itemid = JRequest::getVar('Itemid', 0, 'int');
/*$menus	= &JSite::getMenu();
foreach($menus->_items as $m){
	if($m->link == 'index.php?option=com_djclassifieds&view=show&cid=0'){
		$Itemid=$m->id;
		break;
	}
}*/
$layout='';
if(JRequest::getVar('layout','')=='blog'){	
	$layout='&layout=blog';
}

$mod_attribs=array();
$mod_attribs['style'] = 'xhtml';

	if($par->get('category_jump',0)){
		$anch = '#dj-classifieds';
	}else{
		$anch='';
	}
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
	
			$modules_djcf = &JModuleHelper::getModules('djcf-items-top');			
			if(count($modules_djcf)>0){
				echo '<div class="djcf-ad-items-top clearfix">';
				foreach (array_keys($modules_djcf) as $m){
					echo JModuleHelper::renderModule($modules_djcf[$m],$mod_attribs);
				}
				echo'</div>';		
			}			
				
	if($se>0){
		echo '<h1 class="main_cat_title">'.JText::_('COM_DJCLASSIFIEDS_SEARCH_RESULTS').'</h1>';
	}else if($uid>0){
		echo '<h1 class="main_cat_title">'.$this->u_name.' - '.JText::_('COM_DJCLASSIFIEDS_ADS').'</h1>';
	}else if($fav>0 && $user->id>0 && $fav_a){
		echo '<h1 class="main_cat_title">'.JText::_('COM_DJCLASSIFIEDS_FAVOURITES_ADS').'</h1>';
	}else{				
		if($main_id>0){
			echo '<h1 class="main_cat_title">'.$this->main_cat->name;
				if ($par->get('rss_feed', 1) == 1){
					echo '<a class="rss_icon" href="index.php?option=com_djclassifieds&view=items&cid='.$this->main_cat->id.'&Itemid='.$Itemid.'&format=feed&type=rss"><img src="'.JURI::base().'/components/com_djclassifieds/assets/images/rss.png" /></a>';
				}	
			if(count($this->cat_path)>1 && $par->get('title_breadcrumb','1')){
				echo '<br /><span class="main_cat_title_path">';
					for($ii=count($this->cat_path)-1;$ii>0;$ii--){						
						echo '<a href="index.php?option=com_djclassifieds&view=items&cid='.$this->cat_path[$ii]->id.'&Itemid='.$Itemid.'">'.$this->cat_path[$ii]->name.'</a>';
						if($ii>1){ echo ' / '; }			
					}		
				echo '</span>';
			}			
			echo '</h1>';
		}else if($Itemid>0){
			echo '<h1 class="main_cat_title">'.JSite::getMenu()->getActive()->title.'</h1>';
		}	
		if($par->get('show_subcats','1')){		
			if(count($this->cats)>0){		
			?>			
				<div class="dj-category">
					<div class="cat_row_title">
							<?php 
							if($main_id){
								echo JText::_('COM_DJCLASSIFIEDS_SUBCATEGORIES');
							}else{
								echo JText::_('COM_DJCLASSIFIEDS_CATEGORIES');	
								if ($par->get('rss_feed', 1) == 1){
									echo '<a class="rss_icon" href="index.php?option=com_djclassifieds&view=items&cid=0&Itemid='.$Itemid.'&format=feed&type=rss"><img src="'.JURI::base().'/components/com_djclassifieds/assets/images/rss.png" /></a>';
								}								
							} ?>		 
					</div>
					<div class="cat_row cat_row0">						
						<?php
						$row=0;
						$ii = 0;
						$subcats = array();
						$subc = array();
						$sub_dir = $par->get('subcats_dir',0);
						$si=0;
						foreach($this->cats as $c){
							if($c->parent_id==$main_id){
								if($sub_dir){
									$subc[$si][]=$c;
									if($si==2){$si=0; }else{ $si++;}
								}else{
									$subcats[] = $c;	
								}
								
							}
						}
						//echo '<pre>';print_R($subc);die();
						if($sub_dir){							
							for($sc=0;$sc<count($subc[0]);$sc++){
								for($sci=0;$sci<3;$sci++){
									if(isset($subc[$sci][$sc])){
										$subcats[] = $subc[$sci][$sc];	
									}	
								}									
							}							
						}
						foreach($subcats as $c){		
								if($ii%3==0 && $ii!=0){
									$row==0 ? $row=1 : $row=0;
									echo '<div style="clear:both"></div></div><div class="cat_row cat_row'.$row.'"><div class="cat_col" ><div class="cat_col_in" >';	
								}else{
									echo '<div class="cat_col" ><div class="cat_col_in" >';
								}
									echo '<div class="title">';
										if($c->icon_url){
											echo '<img src="'.JURI::base().'/components/com_djclassifieds/images/'.$c->icon_url.'.ths.jpg"  />';
										}
										
										echo '<h2><a href="index.php?option=com_djclassifieds&view=items'.$layout.'&cid='.$c->id.'&Itemid='.$Itemid.$anch.'">';								
											if($par->get('show_adsn_cat')){
												if(!$c->items_count){
													$c->items_count=0;
												}
												echo $c->name.' <span>('.$c->items_count.')</span></a></h2>';	
												echo '<span class="cat_desc">'.$c->description.'</span>';
											}else{
												echo $c->name.'</a>';	
											}				
									echo '</div>';
									
								echo '</div></div>';					
								$ii++;	
						}
						
						/*if($ii%3==1){
							echo '<div class="cat" ></td><td class="cat" ></td>';
						}
						if($ii%3==2){
							echo '<td class="cat" ></td>';
						}*/
					?>
					<div style="clear:both"></div></div>
				</div>
				<?php				
			}
		}
	}
	
	
		$modules_djcf = &JModuleHelper::getModules('djcf-items-categories');
	if(count($modules_djcf)>0){
		echo '<div class="djcf-ad-items-categories clearfix">';
		foreach (array_keys($modules_djcf) as $m){
			echo JModuleHelper::renderModule($modules_djcf[$m],$mod_attribs);
		}
		echo'</div>';		
	}	
	 






/*






if($sw){?>
<div class="searchword">
	<?php echo JText::_('Results for').' : <span>'.$sw.'</span>'; ?>
</div>
<?php } 
if($uid!='0'){	?>
<div class="user_items">
	<?php echo JText::_('Results for').' : <span>'.$this->model->getUserName($uid).' ('.$lista_count_items.')</span>'; ?>
</div>
<?php } 
 * 
 * 
 */
?>