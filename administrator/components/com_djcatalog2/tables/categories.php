<?php
/**
 * @version $Id: categories.php 72 2012-03-06 13:25:31Z michal $
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
class Djcatalog2TableCategories extends JTable
{
	public function __construct(&$db)
	{
		parent::__construct('#__djc2_categories', 'id', $db);
	}
	function bind($array, $ignore = '')
	{	
		if(empty($array['alias'])) {
			$array['alias'] = $array['name'];
		}
		$array['alias'] = JApplication::stringURLSafe($array['alias']);
		if(trim(str_replace('-','',$array['alias'])) == '') {
			$datenow =& JFactory::getDate();
			$array['alias'] = $datenow->toFormat("%Y-%m-%d-%H-%M-%S");
		}
		
		return parent::bind($array, $ignore);
	}
	public function store($updateNulls = false)
	{
		$date	= JFactory::getDate();
		$user	= JFactory::getUser();
		if (!$this->id) {
			if (!intval($this->created)) {
				$this->created = $date->toMySQL();
			}
			if (empty($this->created_by)) {
				$this->created_by = $user->get('id');
			}
		}	
		
		$table = JTable::getInstance('Categories', 'Djcatalog2Table');
		if ($table->load(array('alias'=>$this->alias,'parent_id'=>$this->parent_id)) && ($table->id != $this->id || $this->id==0)) {
			$this->setError(JText::_('COM_DJCATALOG2_ERROR_UNIQUE_ALIAS'));
			return false;
		}
		return parent::store($updateNulls);
	}
}
