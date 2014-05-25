<?php
/**
 * @version $Id: djcatalog2.php 73 2012-06-13 07:59:06Z michal $
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

// No direct access
defined('_JEXEC') or die;

class Djcatalog2Helper
{
	public static function addSubmenu($vName = 'cpanel')
	{
		JSubMenuHelper::addEntry(JText::_('COM_DJCATALOG2_CPANEL'), 'index.php?option=com_djcatalog2&view=cpanel', $vName=='cpanel');
		JSubMenuHelper::addEntry(JText::_('COM_DJCATALOG2_CATEGORIES'), 'index.php?option=com_djcatalog2&view=categories', $vName=='categories');
		JSubMenuHelper::addEntry(JText::_('COM_DJCATALOG2_PRODUCERS'), 'index.php?option=com_djcatalog2&view=producers', $vName=='producers');
		JSubMenuHelper::addEntry(JText::_('COM_DJCATALOG2_ITEMS'), 'index.php?option=com_djcatalog2&view=items', $vName=='items');
		//JSubMenuHelper::addEntry(JText::_('COM_DJCATALOG2_DJLIC_LICENSE_MANAGER'), 'index.php?option=com_djcatalog2&view=licence', $vName=='licence');
	}

	public static function getActions($asset = null, $assetId = 0)
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		if ( !$asset) {
			$assetName = 'com_djcatalog2';
		} else if ($assetId != 0){
			$assetName = 'com_djcatalog2.'.$asset.$assetId;
		} else {
			$assetName = 'com_djcatalog2.'.$asset;
		}

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);
		
		$actions = array(
			'catalog2.admin','core.admin'
		);

		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, $assetName));
		}

		return $result;
	}
}
