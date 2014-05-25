<?php
/**
 * @version $Id: producer.php 79 2012-07-31 11:10:30Z michal $
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

jimport('joomla.application.component.model');

JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');



class DJCatalog2ModelProducer extends JModel {	
	var $_item;
	var $_id;
	var $_catpath=array();
	var $_params = null;
	
	function __construct()
	{
		parent::__construct();

		$id = JRequest::getVar('pid', 0, '', 'int');
		$this->setId((int)$id);
	}
	
	function setId($id)
	{
		$this->_id		= $id;
		$this->_item	= null;
	}

	function &getData()
	{
		if (!$this->_loadData()) {
			$this->_initData();
		}
		
		return $this->_item;
	}
	function _loadData() {
		if (empty($this->_item)) {
			$query = 'SELECT p.*, '.
					' CASE WHEN CHAR_LENGTH(p.alias) THEN CONCAT_WS(":", p.id, p.alias) ELSE p.id END as prodslug '.
					' FROM #__djc2_producers AS p' .
					' WHERE p.id = '.(int)$this->_id;
			$this->_db->setQuery($query);
			$this->_item = $this->_db->loadObject();
			return (boolean) $this->_item;
		}
		return true;
	}
	function _initData()
	{
		if (empty($this->_item))
		{
			
			$item = new stdClass();
			$item->id = 0;
			$item->name = null;
			$item->alias = null;
			$item->description = null;
			$item->image_url = null;
			$item->published = 1;
			$item->ordering = 0;
			$this->_item = $item;
			return (boolean) $this->_item;
		}
		return true;
	}
}