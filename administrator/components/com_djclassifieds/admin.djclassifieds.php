<?php
/**
* @version		2.0
* @package		DJ Classifieds
* @subpackage 	DJ Classifieds Component
* @copyright 	Copyright (C) 2010 DJ-Extensions.com LTD, All rights reserved.
* @license 		http://www.gnu.org/licenses GNU/GPL
* @author 		url: http://design-joomla.eu
* @author 		email contact@design-joomla.eu
* @developer 	Łukasz Ciastek - lukasz.ciastek@design-joomla.eu
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

// Include dependancies
jimport('joomla.application.component.controller');
require_once(JPATH_COMPONENT.DS.'lib'.DS.'djimage.php');
require_once(JPATH_COMPONENT.DS.'lib'.DS.'djcategory.php');
require_once(JPATH_COMPONENT.DS.'lib'.DS.'djregion.php');
require_once(JPATH_COMPONENT.DS.'lib'.DS.'djnotify.php');
require_once(JPATH_COMPONENT.DS.'lib'.DS.'djlicense.php');
define('DJCFVERSION', '2.5');
define('DJCFFOOTER', '<div style="text-align: center; margin: 10px 0;">DJ-Classifieds (ver. '.DJCFVERSION.'), &copy; 2009-2012 Copyright by <a target="_blank" href="http://dj-extensions.com">dj-extensions.com</a>, All Rights Reserved.<br /><a target="_blank" href="http://dj-extensions.com"><img src="'.JURI::base().'components/com_djclassifieds/images/djextensions.png" alt="dj-extensions.com" style="margin-top: 20px;"/></a></div>');


$document=& JFactory::getDocument();
$cs = JURI::base().'components/com_djclassifieds/assets/style.css';
$document->addStyleSheet($cs);

$controller	= JController::getInstance('djclassifieds');


// Perform the Request task
$controller->execute( JRequest::getCmd('task'));
$controller->redirect();
?>