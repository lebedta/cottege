<?php
/**
* @version		2.0
* @package		DJ Classifieds
* @subpackage	DJ Classifieds Items Module
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
	$menu_item = $menus->getItems('link','index.php?option=com_djclassifieds&view=items&cid=0',1);
	$menu_item_blog = $menus->getItems('link','index.php?option=com_djclassifieds&view=items&layout=blog&cid=0',1);
	$menu_newad_itemid = $menus->getItems('link','index.php?option=com_djclassifieds&view=additem',1);
	$menu_uads_itemid = $menus->getItems('link','index.php?option=com_djclassifieds&view=useritems',1);
		

if(JRequest::getVar('option')!='com_djclassifieds'){
	$document=& JFactory::getDocument();
	$cs = JURI::base().'components/com_djclassifieds/assets/style.css';
	$document->addStyleSheet($cs);	
	
	$par = &JComponentHelper::getParams( 'com_djclassifieds' );
	if($par->get('include_css','1')){
		$cs = JURI::base().'components/com_djclassifieds/assets/style_default.css';
		$document->addStyleSheet($cs);	
	}
}

require(JModuleHelper::getLayoutPath('mod_djclassifieds_user_menu'));

?>
