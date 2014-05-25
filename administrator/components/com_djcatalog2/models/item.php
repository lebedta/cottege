<?php
/**
 * @version $Id: item.php 28 2011-10-11 11:54:13Z michal $
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

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

class Djcatalog2ModelItem extends JModelAdmin
{
	protected $text_prefix = 'COM_DJCATALOG2';
	
	public function __construct($config = array()) {
		$config['event_after_save'] = 'onItemAfterSave';
		$config['event_after_delete'] = 'onItemAfterDelete';
		parent::__construct($config);
	}

	public function getTable($type = 'Items', $prefix = 'Djcatalog2Table', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	public function getForm($data = array(), $loadData = true)
	{
		// Initialise variables.
		$app	= JFactory::getApplication();

		// Get the form.
		$form = $this->loadForm('com_djcatalog2.item', 'item', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}
	
	public function getItem($pk = null) {
		if ($item = parent::getItem($pk)) {
			if ((!isset($item->categories) || !is_array($item->categories)) && isset($item->id)){
				$this->_db->setQuery('SELECT category_id FROM #__djc2_items_categories WHERE item_id=\''.$item->id.'\'');
				$item->categories = $this->_db->loadResultArray();
			}
			return $item;
		} else {
			return false;
		}
	}

	protected function loadFormData()
	{
		$data = JFactory::getApplication()->getUserState('com_djcatalog2.edit.item.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}

	protected function prepareTable(&$table)
	{
		jimport('joomla.filter.output');
		$date = JFactory::getDate();
		$user = JFactory::getUser();

		$table->name		= htmlspecialchars_decode($table->name, ENT_QUOTES);
		$table->alias		= JApplication::stringURLSafe($table->alias);

		if (empty($table->alias)) {
			$table->alias = JApplication::stringURLSafe($table->name);
		}

		if (empty($table->id)) {
			if (empty($table->ordering)) {
				$db = JFactory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM #__djc2_items WHERE cat_id = '.$table->cat_id);
				$max = $db->loadResult();

				$table->ordering = $max+1;
			}
		}
	}

	protected function getReorderConditions($table)
	{
		$condition = array();
		$condition[] = 'cat_id = '.(int) $table->cat_id;
		return $condition;
	}
	
}