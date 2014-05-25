<?php
/**
 * @version $Id: admin.djcatalog2.php 79 2012-07-31 11:10:30Z michal $
 * @package DJ-Catalog2
 * @copyright Copyright (C) 2010 Blue Constant Media LTD, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://design-joomla.eu
 * @author email contact@design-joomla.eu
 * @developer Michal Olczyk - michal.olczyk@design-joomla.eu
 *
 * DJ-Catalog2 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * DJ-Catalog2 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with DJ-Catalog2. If not, see <http://www.gnu.org/licenses/>.
 *
 */

defined( '_JEXEC' ) or die( 'Restricted access' );
//error_reporting(E_STRICT);

// DJ-Catalog2 version no.

define ('DJCATVERSION', '2.2.12');
define('DJCATFOOTER', '<div style="text-align: center; margin: 10px 0;">DJ-Catalog2 (ver. '.DJCATVERSION.'), &copy; 2009-2011 Copyright by <a target="_blank" href="http://dj-extensions.com">dj-extensions.com</a>, All Rights Reserved.<br /><a target="_blank" href="http://design-joomla.eu"><img src="'.JURI::base().'components/com_djcatalog2/images/djextensions.png" alt="dj-extensions.com" style="margin-top: 20px;"/></a></div>');

define ('DJCATIMGFOLDER', JPATH_SITE.DS.'media'.DS.'djcatalog2'.DS.'images');
define ('DJCATIMGURLPATH', str_replace('/administrator', '', JURI::base()).'media/djcatalog2/images');

define ('DJCATATTFOLDER', JPATH_SITE.DS.'media'.DS.'djcatalog2'.DS.'files');
define ('DJCATATTURLPATH', str_replace('/administrator', '', JURI::base()).'media/djcatalog2/files');

jimport('joomla.utilities.string');
jimport('joomla.filesystem.file');
jimport('joomla.application.component.controller');

require_once(JPATH_COMPONENT.DS.'lib'.DS.'categories.php');
require_once(JPATH_COMPONENT.DS.'lib'.DS.'events.php');
require_once(JPATH_COMPONENT.DS.'lib'.DS.'djlicense.php');
require_once(JPATH_COMPONENT.DS.'helpers'.DS.'image.php');
require_once(JPATH_COMPONENT.DS.'helpers'.DS.'file.php');

$dispatcher = JDispatcher::getInstance();
new Djcatalog2Event($dispatcher);

$controller	= JController::getInstance('Djcatalog2');
$controller->execute(JRequest::getCmd('task','display'));
$controller->redirect();
?>