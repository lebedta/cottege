<?php
/**
 * @version $Id: djcatalog2.php 79 2012-07-31 11:10:30Z michal $
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

class Djcatalog2Helper {
	static $params = null;
	
	public static function getParams() {
		if (!self::$params) {
			$params = JComponentHelper::getParams( 'com_djcatalog2' );
			$app		= JFactory::getApplication();
			$mparams = ($app->getParams()); 
			$params->merge($mparams);
			
			$listLayout = JRequest::getVar('l');
			if ($listLayout == 'items') {
				$params->set('list_layout', 'items');
			} else if ($listLayout == 'table') {
				$params->set('list_layout', 'table');
			}
			
			$catalogMode = JRequest::getVar('cm', null);
			$globalSearch = urldecode(JRequest::getVar( 'search','','default','string' ));
			$globalSearch = trim(JString::strtolower( $globalSearch ));
			if (substr($globalSearch,0,1) == '"' && substr($globalSearch, -1) == '"') { 
				$globalSearch = substr($globalSearch,1,-1);
			}
			if (strlen($globalSearch) > 0 && (strlen($globalSearch)) < 3 || strlen($globalSearch) > 20) {
				 $globalSearch = null;
			}
			if ($catalogMode === '0' || $globalSearch) {
				$params->set('product_catalogue','0');
			}
			
			self::$params = $params;
		}
		return self::$params;
	}
}