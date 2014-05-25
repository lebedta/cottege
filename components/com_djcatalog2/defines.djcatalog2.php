<?php
/**
 * @version $Id: defines.djcatalog2.php 62 2011-11-29 08:52:56Z michal $
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
defined('_JEXEC') or die('Restricted access');

define ('DJCATIMGFOLDER', JPATH_SITE.DS.'media'.DS.'djcatalog2'.DS.'images');
define ('DJCATIMGURLPATH', JURI::base().'media/djcatalog2/images');

define ('DJCATATTFOLDER', JPATH_SITE.DS.'media'.DS.'djcatalog2'.DS.'files');
define ('DJCATATTURLPATH', JURI::base().'media/djcatalog2/files');

define('DJCATFOOTER', '<div style="text-align: center; padding: 2px 0px;"><a style="font-size:10px;" target="_blank" href="http://design-joomla.eu">Joomla! templates &amp; addons</a></div>');
define('DJCATCOMPONENTPATH',JPATH_BASE.DS.'components'.DS.'com_djcatalog2');
