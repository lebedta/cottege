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

defined ('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');



class DJCatalog2ModelItem extends JModel {	
	var $_item;
	var $_id;
	var $_catpath=Array();
	var $_params = null;
	var $_related = null;
	
	function __construct()
	{
		parent::__construct();

		$id = JRequest::getVar('id', 0, '', 'int');
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
		
		if (!$this->_item->cat_pub && $this->_item->cat_id) {
			JError::raiseError( 404, JText::_("COM_DJCATALOG2_PAGE_NOT_FOUND") );
		}
		
		return $this->_item;
	}
	function _loadData() {
		if (empty($this->_item)) {
			$query = 'SELECT i.*, c.name AS category, c.id AS ccategory_id, c.published AS cat_pub, p.name AS producer, p.id AS pproducer_id, p.published AS prod_pub, '.
					' CASE WHEN CHAR_LENGTH(i.alias) THEN CONCAT_WS(":", i.id, i.alias) ELSE i.id END as slug, '.
					' CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(":", c.id, c.alias) ELSE c.id END as catslug, '.
					' CASE WHEN CHAR_LENGTH(p.alias) THEN CONCAT_WS(":", p.id, p.alias) ELSE p.id END as prodslug '.
					' FROM #__djc2_items AS i' .
					' LEFT JOIN #__djc2_categories AS c ON c.id = i.cat_id ' .
					' LEFT JOIN #__djc2_producers AS p ON p.id = i.producer_id ' .
					' WHERE i.id = '.(int)$this->_id;
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
			$item->cat_id = null;
			$item->producer_id = null;
			$item->name = null;
			$item->alias = null;
			$item->description = null;
			$item->intro_desc = null;
			$item->price = 0;
			$item->image_url = null;
			$item->files_url = null;
			$item->metakey = null;
			$item->metadesc = null;
			$item->published = 1;
			$item->ordering = 0;
			$item->params = null;
			$this->_item = $item;
			return (boolean) $this->_item;
		}
		return true;
	}
	
	function getPath($catid){
		if(!$this->_catpath){
			$db= JFactory::getDBO();
			$query = "SELECT * FROM #__djc2_categories";
			$db->setQuery($query);
			$categories = $db->loadObjectList();
			$this->buildPath($categories, $catid,$this->_catpath);
		}
		return $this->_catpath;
	}
	
	function buildPath(&$categories, $catid, &$pathTable) {
		foreach ($categories as $row) {
			if ($row->id == $catid) {
				if ($row->parent_id == 0) {
					$pathTable[]= Array('id' => $row->id, 'name'=> $row->name);
					return true;
				}
				else {
					$pathTable[]= Array('id' => $row->id, 'name'=> $row->name);
					$this->buildPath($categories, $row->parent_id, $pathTable);
				}
			}
		}
		return false;
	}
	function getParams() {
		if (!$this->_params) {
			$params = JComponentHelper::getParams( 'com_djcatalog2' );
			$app		= JFactory::getApplication();
			$mparams = ($app->getParams()); 
			$params->merge($mparams);
			$this->_params = $params;
		}
		return $this->_params;
	}
	function getRelatedItems() {
		if (!$this->_related) {
			$query = ' SELECT i.*, c.id AS ccategory_id, p.id AS pproducer_id, c.name AS category, p.name AS producer, p.published as publish_producer, img.fullname AS item_image, img.caption AS image_caption, '
					. ' CASE WHEN CHAR_LENGTH(i.alias) THEN CONCAT_WS(":", i.id, i.alias) ELSE i.id END as slug, '
					. ' CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(":", c.id, c.alias) ELSE c.id END as catslug, '
					. ' CASE WHEN CHAR_LENGTH(p.alias) THEN CONCAT_WS(":", p.id, p.alias) ELSE p.id END as prodslug '
					. ' FROM #__djc2_items AS i '
					. ' LEFT JOIN #__djc2_categories AS c ON c.id = i.cat_id '
					. ' LEFT JOIN #__djc2_producers AS p ON p.id = i.producer_id '
					. ' LEFT JOIN #__djc2_images AS img ON img.item_id = i.id AND img.type=\'item\' AND img.ordering = 1 '
					. ' WHERE i.published = 1 AND i.id IN (SELECT related_item FROM #__djc2_items_related WHERE item_id='.(int)$this->_id.')' 
					. ' ORDER BY i.ordering ASC ';
			$this->_db->setQuery($query);
			$this->_related = $this->_db->loadObjectList();
		}
		return $this->_related;	
	}
}