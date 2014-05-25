<?php
/**
* @version $Id: mod_djc2categories.php 67 2012-01-02 09:00:30Z michal $
* @package DJ-Catalog2
* @copyright Copyright (C) 2010 Blue Constant Media LTD, All rights reserved.
* @license http://www.gnu.org/licenses GNU/GPL
* @author url: http://design-joomla.eu
* @author email contact@design-joomla.eu
* @developer Michal Olczyk - michal.olczyk@design-joomla.eu
*
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
require_once(JPATH_BASE.DS.'components'.DS.'com_djcatalog2'.DS.'helpers'.DS.'theme.php');
DJCatalog2ThemeHelper::setThemeAssets();

$cid = JRequest::getVar('cid',0,'','int');
$expand = $params->get('expand');

$parent = $params->get('parent_category',null);
$moduleHtml = DJC2CategoriesModuleHelper::getHtml($cid, $expand, $parent);

require(JModuleHelper::getLayoutPath('mod_djc2categories'));






