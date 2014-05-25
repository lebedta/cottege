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

$user = JFactory::getUser();

	if($menu_item){
		$fav_ads_link='index.php?option=com_djclassifieds&view=items&cid=0&Itemid='.$menu_item->id;
	}else if($menu_item_blog){
		$fav_ads_link='index.php?option=com_djclassifieds&view=items&layout=blog&cid=0&Itemid='.$menu_item_blog->id;
	}else{
		$fav_ads_link='index.php?option=com_djclassifieds&view=items&cid=0&Itemid=';
	}
$fav_ads_link .='&fav=1';

$new_ad_link='index.php?option=com_djclassifieds&view=additem';
	if($menu_newad_itemid){
		$new_ad_link .= '&Itemid='.$menu_newad_itemid->id;
	}

$user_ads_link='index.php?option=com_djclassifieds&view=useritems';
	if($menu_uads_itemid){
		$user_ads_link .= '&Itemid='.$menu_uads_itemid->id;
	}
	?>
	<div class="djcf_user_menu djcf_menu">
		<ul class="menu">
		<?php 
			if($params->get('new_ad_link','1')==1){
				echo '<li><a href="'.JRoute::_($new_ad_link).'">'.JText::_('MOD_DJCLASSIFIEDS_USER_MENU_NEW_ADD').'</a></li>';
			} 
			if($params->get('user_ads_link','1')==1){
				echo '<li><a href="'.JRoute::_($user_ads_link).'">'.JText::_('MOD_DJCLASSIFIEDS_USER_MENU_USER_ADS').'</a></li>';
			}
			if($params->get('user_fav_link','1')==1 && $user->id>0){
				echo '<li><a href="'.JRoute::_($fav_ads_link).'">'.JText::_('MOD_DJCLASSIFIEDS_USER_MENU_FAVOURITE_ADS').'</a></li>';
			}?>
		</ul>
	</div>	
	 