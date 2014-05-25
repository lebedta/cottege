<?php
/**
* @version		2.0
* @package		DJ Classifieds
* @subpackage	DJ Classifieds Search Module
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

	$menus	= JSite::getMenu();
	$menu_item = $menus->getItems('link','index.php?option=com_djclassifieds&view=items',1);
			
	$menu_item = $menus->getItems('link','index.php?option=com_djclassifieds&view=items&cid=0',1);
	$menu_item_blog = $menus->getItems('link','index.php?option=com_djclassifieds&view=items&layout=blog&cid=0',1);
			
	$itemid = ''; 
	$link_reset='index.php?option=com_djclassifieds&view=items&cid=0';
	if($menu_item){
		$itemid=$menu_item->id;
		$link_reset .= '&Itemid='.$itemid;
	}else if($menu_item_blog){
		$itemid=$menu_item_blog->id;
		$link_reset='index.php?option=com_djclassifieds&view=items&layout=blog&cid=0&Itemid='.$itemid;
	}	
	$link_reset .= '&reset=1';
	

?>
<div class="dj_cf_search">
<form action="index.php" method="get" name="" id="form-search">
	<input type="hidden" name="option" id="opt_search" value="com_djclassifieds" />
   	<input type="hidden" name="view" value="items" />
   	<input type="hidden" name="se" value="1" />
   	<input type="hidden" name="Itemid" value="<?php echo $itemid;?>" />	
   	
   	<?php
   	if($params->get('show_input','1')==1){   	
	   	 $s_value = JRequest::getVar('search',JText::_('COM_DJCLASSIFIEDS_SEARCH'));
	   	// onblur="if(this.value=='') this.value='<?php echo JText::_('COM_DJCLASSIFIEDS_SEARCH'); ? >';"
	   	?>
		<input type="text" size="12" name="search" class="inputbox first_input" value="<?php echo $s_value; ?>" onfocus="if(this.value=='<?php echo JText::_('COM_DJCLASSIFIEDS_SEARCH'); ?>') this.value='';" />
<?php }
 if($params->get('show_loc','1')==1){	?>
<div class="search_regions">	
	<?php 
	
	$reg_sel = '<select autocomplete="off" class="inputbox" id="se_reg_0" name="se_regs[]" onchange="se_new_reg(0,this.value);"><option value="0">'.JText::_('COM_DJCLASSIFIEDS_SELECT_LOCALIZATION').'</option>';
				$parent_id=0;	
				$lc=0;
				$lcount = count($regions);
				
				foreach($regions as $l){
					$lc++;
					if($parent_id!=$l->parent_id){
						$reg_sel .= '</select>';
						echo $reg_sel;
						break;
					}	
					$reg_sel .= '<option value="'.$l->id.'">'.$l->name.'</option>';
					
					if($parent_id==$l->parent_id && $lc==$lcount){
						$reg_sel .= '</select>';
						echo $reg_sel;
						break;
					}
				}
				
				?>

				<div id="se_after_reg_0"></div>
				<script type="text/javascript">
					var se_regs=new Array();
					
				<?php
				$reg_sel = '<select class="inputbox" name="se_regs[]" id="se_reg_0" onchange="se_new_reg(0,this.value);">';
				$parent_id=0;	
				
				foreach($regions as $l){
					if($parent_id!=$l->parent_id){
						$reg_sel .= '</select>';
						echo "se_regs[$parent_id]='$reg_sel<div id=\"se_after_reg_$parent_id\"></div>';";
						$parent_id=$l->parent_id;
						$reg_sel = '<select class="inputbox" name="se_regs[]" id="se_reg_'.$l->parent_id.'" onchange="se_new_reg('.$parent_id.',this.value);">';
						$reg_sel .= '<option value="'.$parent_id.'"> - - - </option>';		
					}	
					$reg_sel .= '<option value="'.$l->id.'">'.$l->name.'</option>';
				}
				$reg_sel .= '</select>';	
				echo "se_regs[$parent_id]='$reg_sel<div id=\"se_after_reg_$parent_id\"></div>';";
				
				
					$se_url = '';
					foreach ($_GET as $k => $v) {						
						if(strstr($k,'se_')){
							$se_url .= '&'.$k.'='.$v;		
						}
					}
				?>	
				var se_current=0;
				
				function se_new_reg(parent,a_parent){
					if(se_regs[a_parent]){
						//alert(cats[v]);
						if(parent==a_parent){
							$('se_after_reg_'+parent).innerHTML = '';
						}else{
							$('se_after_reg_'+parent).innerHTML = se_regs[a_parent];
						}													 
						$('se_reg_'+parent).value=a_parent;
					}else{
						$('se_after_reg_'+parent).innerHTML = '';
						$('se_reg_'+parent).value=a_parent;		
					}
					
				}				
				</script>

		</div>	
	<?php }
	
	if($params->get('show_cat','1')==1){	?>
	<div class="search_cats">
	<?php 
	
	$cat_sel = '<select autocomplete="off" class="inputbox" id="se_cat_0" name="se_cats[]" onchange="se_new_cat(0,this.value);se_getFields(this.value);"><option value="">'.JText::_('COM_DJCLASSIFIEDS_PLEASE_SELECT_CATEGORY').'</option>';
				$parent_id=0;	
				$lc=0;
				$lcount = count($list);
				
				foreach($list as $l){
					$lc++;
					if($parent_id!=$l->parent_id){
						$cat_sel .= '</select>';
						echo $cat_sel;
						break;
					}	
					$cat_sel .= '<option value="'.$l->id.'">'.str_ireplace("'", "&apos;", $l->name).'</option>';
					
					if($parent_id==$l->parent_id && $lc==$lcount){
						$cat_sel .= '</select>';
						echo $cat_sel;
						break;
					}
				}
				
				?>

				<div id="se_after_cat_0"></div>
				<script type="text/javascript">
					var se_cats=new Array();
					
				<?php
				$cat_sel = '<select class="inputbox" name="se_cats[]" id="se_cat_0" onchange="se_new_cat(0,this.value);se_getFields(this.value);">';
				$parent_id=0;	
				
				foreach($list as $l){
					if($parent_id!=$l->parent_id){
						$cat_sel .= '</select>';
						echo "se_cats[$parent_id]='$cat_sel<div id=\"se_after_cat_$parent_id\"></div>';";
						$parent_id=$l->parent_id;
						$cat_sel = '<select class="inputbox select_mod" name="se_cats[]" id="se_cat_'.$l->parent_id.'" onchange="se_new_cat('.$parent_id.',this.value);se_getFields(this.value);">';
						$cat_sel .= '<option value="'.$parent_id.'"> - - - </option>';		
					}	
					$cat_sel .= '<option value="'.$l->id.'">'.str_ireplace("'", "&apos;", $l->name).'</option>';
				}
				$cat_sel .= '</select>';	
				echo "se_cats[$parent_id]='$cat_sel<div id=\"se_after_cat_$parent_id\"></div>';";
				
				
					$se_url = '';
					foreach ($_GET as $k => $v) {						
						if(strstr($k,'se_')){
							$se_url .= '&'.$k.'='.$v;		
						}
					}
				?>	
				var se_current=0;
				
				function se_new_cat(parent,a_parent){
					if(se_cats[a_parent]){
						//alert(se_cats[v]);	
						$('se_after_cat_'+parent).innerHTML = se_cats[a_parent]; 
						$('se_cat_'+parent).value=a_parent;
					}else{
						$('se_after_cat_'+parent).innerHTML = '';
						$('se_cat_'+parent).value=a_parent;		
					}
					
				}
				
			function se_getFields(cat_id){
					
				var el = document.getElementById("search_ex_fields");
				var before = document.getElementById("search_ex_fields").innerHTML.trim();	
				
				if(cat_id!=0){	
					el.innerHTML = '<div style="text-align:center"><img src="<?php echo JURI::base(); ?>components/com_djclassifieds/assets/images/loading.gif" /></div>';
					var url = 'index.php?option=com_djclassifieds&view=item&task=getSearchFields&cat_id=' + cat_id;
								 var myRequest = new Request({
							    url: 'index.php',
							    method: 'post',
								data: {
							      'option': 'com_djclassifieds',
							      'view': 'item',
							      'task': 'getSearchFields',
								  'cat_id': cat_id			  
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
				<?php // echo $this->cat_path; ?>
								
				
				</script>

		</div>		
		<div id="search_ex_fields"></div>
		<?php } 
		if($params->get('show_price','1')==1){	?>
			<div class="search_price">				
				<label><?php echo JText::_('MOD_DJCLASSIFIEDS_SEARCH_PRICE').' ( '.$comparams->get('unit_price','EUR').' )'; ?></label>
				<span class="label1"><?php echo JText::_('MOD_DJCLASSIFIEDS_SEARCH_PRICE_FROM'); ?></span>
				<input size="5" class="inputbox" type="text" name="se_price_f" value="<?php echo JRequest::getVar('se_price_f',''); ?>"/>
				<span class="label2"><?php echo JText::_('MOD_DJCLASSIFIEDS_SEARCH_PRICE_TO'); ?></span>
				<input size="5" class="inputbox" type="text" name="se_price_t" value="<?php echo JRequest::getVar('se_price_t',''); ?>"/>
			</div>
	<?php }?>
					
	<button type="submit" class="button"><?php echo JText::_('MOD_DJCLASSIFIEDS_SEARCH_SEARCH');?></button>
	
	<?php 
	if(JRequest::getInt('se',0)==1 || (JRequest::getInt('cid',0)>0 && JRequest::getInt('option','')=='com_djclassifieds')){ ?>
		<a href="<?php echo JRoute::_($link_reset);?>" class="button"><?php echo JText::_('MOD_DJCLASSIFIEDS_SEARCH_RESET');?></a>	
	<?php } ?>
	 
</form>
 
<div style="clear:both"></div>
</div>

<?php
$cat_id_se = 0;
if($params->get('show_cat','1')==1){
	if(JRequest::getVar('se','0','','string')!='0' && isset($_GET['se_cats'])){
		$cat_id_se= end($_GET['se_cats']);
		 
		if($cat_id_se=='' && count($_GET['se_cats'])>2){
			$cat_id_se =$_GET['se_cats'][count($_GET['se_cats'])-2];
		}	
		
	}
	if($cat_id_se=='0'){
		$cat_id_se = JRequest::getInt('cid','0');	
	}	
	$se_parents = array();
	if($cat_id_se > 0){
		foreach($list as $c){
			if($cat_id_se == $c->id ){
				$se_parents[] = $c->parent_id;
				$act_parent = $c->parent_id;
				break;		
			}
		}
		while($act_parent!=0){
			foreach($list as $c){
				if($act_parent == $c->id ){
					$se_parents[] = $c->parent_id;
					$act_parent = $c->parent_id;
					break;		
				}
			}	
		}
		
	}
}
$reg_id_se = 0;

if($params->get('show_loc','1')==1){
	$act_reg_parent = 0;
	if(JRequest::getVar('se','0','','string')!='0' && isset($_GET['se_regs'])){
		$reg_id_se= end($_GET['se_regs']);
		if(!$reg_id_se && count($_GET['se_regs'])>2){
			$reg_id_se =$_GET['se_regs'][count($_GET['regs'])-2];
		}
	}
	if($reg_id_se=='0'){
		$reg_id_se = JRequest::getInt('rid','0');	
	}	
	$se_reg_parents = array();
	if($reg_id_se > 0){
		foreach($regions as $r){
			if($reg_id_se == $r->id ){
				$se_reg_parents[] = $r->parent_id;
				$act_reg_parent = $r->parent_id;
				break;		
			}
		}
		while($act_reg_parent!=0){
			foreach($regions as $r){
				if($act_reg_parent == $r->id ){
					$se_reg_parents[] = $r->parent_id;
					$act_reg_parent = $r->parent_id;
					break;		
				}
			}	
		}
	}	
}
if($cat_id_se > 0 || $reg_id_se > 0){ 

	?>
	<script type="application/javascript">
		window.addEvent("load", function(){
			<?php 		
			if($cat_id_se>0){
				for($sp=count($se_parents)-1;$sp>0 ;$sp--){		
					echo 'se_new_cat('.$se_parents[$sp] .','.$se_parents[$sp-1].');';
				} 
				?>
				se_new_cat(<?php echo $se_parents[0]; ?>,<?php echo $cat_id_se; ?>);
				se_getFields(<?php echo $cat_id_se; ?>);
				
			<?php } ?>
			<?php
			if($reg_id_se > 0){
				for($sp=count($se_reg_parents)-1;$sp>0 ;$sp--){		
					echo 'se_new_reg('.$se_reg_parents[$sp] .','.$se_reg_parents[$sp-1].');';
				} 
				
				if($reg_id_se>0){ ?>
				se_new_reg(<?php echo $se_reg_parents[0]; ?>,<?php echo $reg_id_se; ?>);
				<?php }
			}	 ?>			
		});
	</script>
	<?php
	
} 

?>
