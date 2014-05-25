<?php
/**
 * @version $Id: categories.php 77 2012-06-28 08:45:28Z michal $
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

defined('_JEXEC') or die();

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class Djcatalog2ModelCategories extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.id',
				'name', 'a.name',
				'alias', 'a.alias',
				'ordering', 'a.ordering',
				'checked_out', 'a.checked_out',
				'checked_out_time', 'a.checked_out_time',
				'published', 'a.published',
				'access', 'a.access', 'access_level',
				'created', 'a.created',
				'created_by', 'a.created_by',
				'language', 'a.language'
				);
		}

		parent::__construct($config);
	}
	protected function populateState($ordering = null, $direction = null)
	{
		parent::populateState('a.ordering', 'asc');
		
		// Initialise variables.
		$app = JFactory::getApplication();
		$session = JFactory::getSession();

		// Load the parameters.
		$params = JComponentHelper::getParams('com_djcatalog2');
		$this->setState('params', $params);
		$this->setState('list.limit', 0);

	}

	protected function getListQuery()
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select('a.*');
		$query->from('#__djc2_categories AS a');
		
		// Join over the users for the checked out user.
		$query->select('uc.name AS editor');
		$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');
		
		$query->select('img.fullname AS item_image, img.caption AS image_caption');
		//$query->join('LEFT', '#__djc2_images AS img ON img.item_id=a.id AND img.type=\'category\' AND img.ordering=1');
		//$query->join('left', '(SELECT im1.* from #__djc2_images as im1 GROUP BY im1.item_id, im1.type ORDER BY im1.ordering asc) AS img ON img.item_id = a.id AND img.type=\'category\'');
		$query->join('left', '(select im1.fullname, im1.caption, im1.type, im1.item_id from #__djc2_images as im1, (select item_id, type, min(ordering) as lowest_order from #__djc2_images group by item_id, type) as im2 where im1.item_id = im2.item_id and im1.type=im2.type and im1.ordering = im2.lowest_order) AS img ON img.item_id = a.id AND img.type=\'category\'');
		
		//$parent_id = JRequest::getInt('parent_id', 0);
		
		//$query->where('a.parent_id=\''.$parent_id.'\'');
		
		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
		if ($orderCol == 'a.ordering') {
			$orderCol = 'a.parent_id '.$orderDirn.', a.ordering '.$orderDirn;
			$query->order($db->getEscaped($orderCol));
		} else {
			$query->order($db->getEscaped($orderCol.' '.$orderDirn));
		}
		return $query;
	}
	public function getItems() {
		$items = parent::getItems();
		//return $items;
		$children = array();
		foreach ($items as $item )
		{
			$pt = $item->parent_id;
			$list = array();
			if (array_key_exists($pt, $children)) {
				$list = $children[$pt];
			}
			array_push( $list, $item );
			$children[$pt] = $list;
		}
		$items = $this->makeCategoryTree(0, array(), $children,0);

		$items = array_slice($items, 0);
		return $items;
	}
	function recreateThumbnails($cid = array())
	{
		if (count( $cid ))
		{
			JArrayHelper::toInteger($cid);
			$cids = implode( ',', $cid );

			$query = 'SELECT fullname FROM #__djc2_images'
					. ' WHERE item_id IN ( '.$cids.' )'
					. ' AND type=\'category\''
			;
			$this->_db->setQuery( $query );
			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			$items = $this->_db->loadObjectList();
			$params = &JComponentHelper::getParams( 'com_djcatalog2' );

			foreach($items as $item) {
				DJCatalog2ImageHelper::processImage(DJCATIMGFOLDER, $item->fullname, 'category', $params);
			}
		}
		return true;
	}
	protected function makeCategoryTree( $id, $list, &$children, $level=0) {
		if (array_key_exists($id, $children)) {
			foreach ($children[$id] as $child)
			{
				$id = $child->id;

				$pt = $child->parent_id;
				$list[$id] = $child;
				if (array_key_exists($id, $children)) {
					$list[$id]->children = count( $children[$id] );
				}
				else {
					$list[$id]->children = 0;
				}
				$list = $this->makeCategoryTree( $id, $list, $children, $level+1);
			}
				
		}
		return $list;
	}


}