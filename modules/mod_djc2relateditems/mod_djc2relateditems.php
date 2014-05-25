<?php
/**
 * @version $Id: mod_djc2relateditems.php 45 2011-11-07 11:37:41Z michal $
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

defined ('_JEXEC') or die('Restricted access');

require_once(dirname(__FILE__).DS.'helper.php');
require_once(JPATH_BASE.DS.'components'.DS.'com_djcatalog2'.DS.'defines.djcatalog2.php');
require_once(JPATH_BASE.DS.'components'.DS.'com_djcatalog2'.DS.'helpers'.DS.'route.php');
require_once(JPATH_BASE.DS.'components'.DS.'com_djcatalog2'.DS.'helpers'.DS.'html.php');
require_once(JPATH_BASE.DS.'components'.DS.'com_djcatalog2'.DS.'helpers'.DS.'theme.php');
require_once(JPATH_BASE.DS.'administrator'.DS.'components'.DS.'com_djcatalog2'.DS.'lib'.DS.'categories.php');
require_once(JPATH_BASE.DS.'administrator'.DS.'components'.DS.'com_djcatalog2'.DS.'helpers'.DS.'image.php');

$document= JFactory::getDocument();
$db = JFactory::getDBO();

DJCatalog2ThemeHelper::setThemeAssets();

$helperClass = new modDjc2RelateditemsHelper($params);
$items = $helperClass->getData();

$layout = 'default';
if ($items) {
	require(JModuleHelper::getLayoutPath('mod_djc2relateditems',$layout));
} else return false;

?>
