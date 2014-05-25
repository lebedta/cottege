<?php
/**
* @version 2.0
* @package DJ Classifieds
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


defined('_JEXEC') or die('Restricted access');
//error_reporting(E_STRICT);

require_once(JPATH_COMPONENT.DS.'defines.djclassifieds.php');

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'lib'.DS.'djcategory.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'lib'.DS.'djimage.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'lib'.DS.'djregion.php');
require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'lib'.DS.'djnotify.php');

/*
require_once(JPATH_COMPONENT.DS.'helpers'.DS.'route.php');
require_once(JPATH_COMPONENT.DS.'helpers'.DS.'html.php');
require_once(JPATH_COMPONENT.DS.'helpers'.DS.'theme.php');
*/
$document=& JFactory::getDocument();
$cs = JURI::base().'components/com_djclassifieds/assets/style.css';
$document->addStyleSheet($cs);

$par = &JComponentHelper::getParams( 'com_djclassifieds' );
if($par->get('include_css','1')){
	$cs = JURI::base().'components/com_djclassifieds/assets/style_default.css';
	$document->addStyleSheet($cs);	
}

if(JRequest::getVar('view')=='item'){
	$document=& JFactory::getDocument();
	$assets=JURI::base().'/components/com_djclassifieds/assets/slimbox-1.71a/';	
	$document->addScript($assets.'js/slimbox.js');
	$document->addStyleSheet($assets.'css/slimbox.css');
}


require_once(JPATH_COMPONENT.DS.'controller.php');


$view=JRequest::getCmd('view','show');
if($view=='item' || $view=='additem'){
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$view.'.php';
	jimport('joomla.filesystem.file');	
	if(JFile::exists($path)){
		require_once($path);
	}else{
		JError::raiseError('500',JText::_('Unknown controller'));
	}
	
	jimport('joomla.utilities.string');
	
	$c = 'DJClassifiedsController'.ucfirst($view);
	$controller = new $c();	
		
}else{
	$controller = new DJClassifiedsController();
}

$controller->execute( JRequest::getCmd('task','display'));
$controller->redirect();

?>

