<?php
/**
 * @version $Id: helper.php 69 2012-01-13 10:52:55Z michal $
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

class modDjc2ItemsHelper extends JModel {
	var $_data = null;
	var $_cparams = null;
	var $_mparams = null;
	
	function __construct( $params=array() )
	{
		$app = JFactory::getApplication();
		$this->_cparams = $app->getParams('com_djcatalog2');
		$this->_mparams = $params;
	}
	function getData() {
		if (!$this->_data){
			$db					= JFactory::getDBO();
			$db->setQuery($this->_buildQuery());
			$this->_data = $db->loadObjectList();
			
			$price_decimal_separator = null;
			$price_thousands_separator = null;
			
			foreach ($this->_data as $item) {
				//$item->images = DJCatalog2ImageHelper::getImages('item',$item->id);
				
				if ($this->_mparams->get('show_price') == 2 || ( $this->_mparams->get('show_price') == 1 && $item->price > 0.0)) {
					$item->price = DJCatalog2HtmlHelper::formatPrice($item->price, $this->_cparams);
				}
				else {
					$item->price = null;
				}
				
			}
		}
		return $this->_data;
	}
	function _buildQuery()
	{
		$where		= $this->_buildContentWhere();
		$orderby	= $this->_buildContentOrderBy();

		$query = ' SELECT i.*, c.id AS ccategory_id, p.id AS pproducer_id, c.name AS category, p.name AS producer, p.published as publish_producer, img.fullname AS item_image, img.caption AS image_caption, '
			. ' CASE WHEN CHAR_LENGTH(i.alias) THEN CONCAT_WS(":", i.id, i.alias) ELSE i.id END as slug, '
			. ' CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(":", c.id, c.alias) ELSE c.id END as catslug, '
			. ' CASE WHEN CHAR_LENGTH(p.alias) THEN CONCAT_WS(":", p.id, p.alias) ELSE p.id END as prodslug '
			. ' FROM #__djc2_items AS i '
			. ' LEFT JOIN #__djc2_categories AS c ON c.id = i.cat_id '
			. ' LEFT JOIN #__djc2_producers AS p ON p.id = i.producer_id '
			. ' LEFT JOIN #__djc2_images AS img ON img.item_id = i.id AND img.type=\'item\' AND img.ordering = 1 '
			. $where
			. $orderby
		;
		//echo str_replace('#_', 'jos', $query);
		return $query;
	}

	function _buildContentOrderBy()
	{
		$filter_order		= $this->_mparams->get('orderby','i.ordering');
		$filter_order_Dir	= $this->_mparams->get('orderdir','asc');
		$filter_featured	= $this->_mparams->get('featured_first', 0);
		$limit = ($this->_mparams->get('items_limit',0));
		if ($filter_order != 'i.ordering' && $filter_order != 'category' && $filter_order != 'i.created' && $filter_order != 'producer' && $filter_order != 'i.price' && $filter_order != 'i.name' && $filter_order != 'rand()') {
			$filter_order = 'i.ordering';
		}
		if ($filter_order_Dir != 'asc' && $filter_order_Dir != 'desc') {
			$filter_order_Dir = 'asc';
		}
		
		if ($filter_featured) {
			$orderby 	= ' ORDER BY i.featured DESC, '.$filter_order.' '.$filter_order_Dir.' , i.ordering, c.ordering ';
		}
		else if ($filter_order == 'i.ordering'){
			$orderby 	= ' ORDER BY i.ordering '.$filter_order_Dir.', c.ordering '.$filter_order_Dir;
		} 
		else {
			$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir.' , i.ordering, c.ordering ';
		}
		if ($limit > 0) {
			$orderby .= ' LIMIT '.$limit;
		}
		return $orderby;
	}

	function _buildContentWhere()
	{
		$db					= JFactory::getDBO();
		
		$filter_catid		= $this->_mparams->get('catid', array());
		$filter_featured	= $this->_mparams->get('featured_only', 0);
		$where = array();
		if ($filter_featured > 0) {
			$where[] = 'i.featured = 1';
		}
		if (count($filter_catid)) {
			$db->setQuery('SELECT item_id 
						   FROM #__djc2_items_categories AS ic
						   INNER JOIN #__djc2_categories AS c ON c.id=ic.category_id 
						   WHERE category_id IN ('.implode(',',$filter_catid).') AND c.published = 1');
			$items = $db->loadResultArray();
			if (count ($items)) {
				$items = array_unique($items);
				$where[] = 'i.id IN ('.implode(',',$items).')';
			}
		}
		/*
		if ($filter_catid >= 0) {
			$categories = Djc2Categories::getInstance(array('state'=>'1'));
				if ($parent = $categories->get((int)$filter_catid) ) {
					$childrenList = array($parent->id);
					$parent->makeChildrenList($childrenList);
					if ($childrenList) {
						$cids = implode(',', $childrenList);
						$db->setQuery('SELECT item_id 
									   FROM #__djc2_items_categories AS ic
									   INNER JOIN #__djc2_categories AS c ON c.id=ic.category_id 
									   WHERE category_id IN ('.$cids.') AND c.published = 1');
						$items = $db->loadResultArray();
						if (count ($items)) {
							$items = array_unique($items);
							$where[] = 'i.id IN ('.implode(',',$items).')';
						}
						//$where[] = 'i.cat_id IN ( '.$cids.' )';
					}
					else if ($filter_catid != 0){
						JError::raiseError( 404, JText::_("COM_DJCATALOG2_PAGE_NOT_FOUND") );
					}
				}
		}*/
		$where[] = 'i.published = 1';
		$where[] = 'c.published = 1';
		$where 		= ( count( $where ) ? ' WHERE '. implode( ' AND ', $where ) : '' );
		return $where;
	}
}

?>
