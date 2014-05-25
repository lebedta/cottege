<?php
/**
 * @version $Id: items.php 79 2012-07-31 11:10:30Z michal $
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

class DJCatalog2ModelItems extends JModel {
	var $_list = null;
	var $_pagination = null;
	var $_total = null;
	var $_producers = null;
	var $_params = null;
		
	function __construct()
	{
		parent::__construct();

		$this->_params = $this->getParams();
		
		$view = JRequest::getVar('view');
		
		$limit		= JRequest::getVar( 'limit', $this->_params->get('limit_items_show',10), 'default', 'int' );
		$limitstart	= JRequest::getVar( 'limitstart', 0, 'default', 'int' );

		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		
	}
	function getList()
	{
		if (empty($this->_list))
		{
			$query = $this->_buildQuery();
			$this->_list = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}

		return $this->_list;
	}

	function getTotal()
	{
		if (empty($this->_total))
		{
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}

		return $this->_total;
	}

	function getPagination()
	{
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		}

		return $this->_pagination;
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
			. ' LEFT JOIN (select im1.fullname, im1.caption, im1.type, im1.item_id from #__djc2_images as im1, (select item_id, type, min(ordering) as lowest_order from #__djc2_images group by item_id, type) as im2 where im1.item_id = im2.item_id and im1.type=im2.type and im1.ordering = im2.lowest_order) AS img ON img.item_id = i.id AND img.type=\'item\''
			. $where
			. $orderby
		;
		
		//echo str_replace('#_', 'jos', $query);
		return $query;
	}

	function _buildContentOrderBy()
	{
		$filter_order		= JRequest::getVar( 'order',$this->_params->get('items_default_order','i.ordering'),'default','cmd' );
		$filter_order_Dir	= JRequest::getVar( 'dir',	$this->_params->get('items_default_order_dir','asc'),'default','word' );
		$filter_featured	= $this->_params->get('featured_first', 0);
		
		if ($filter_order != 'i.ordering' && $filter_order != 'category' && $filter_order != 'producer' && $filter_order != 'i.price' && $filter_order != 'i.name' && $filter_order != 'i.created') {
			$filter_order = 'i.ordering';
		}
		if ($filter_order_Dir != 'asc' && $filter_order_Dir != 'desc') {
			$filter_order_Dir = 'asc';
		}
		
		if ($filter_order == 'i.ordering'){
			if ($filter_featured) {
				$orderby 	= ' ORDER BY i.featured DESC, i.ordering '.$filter_order_Dir.', c.ordering '.$filter_order_Dir;
			} else {
				$orderby 	= ' ORDER BY i.ordering '.$filter_order_Dir.', c.ordering '.$filter_order_Dir;
			}
		} else {
			if ($filter_featured) {
				$orderby 	= ' ORDER BY i.featured DESC, '.$filter_order.' '.$filter_order_Dir.' , i.ordering, c.ordering ';
			}
			else {
				$orderby 	= ' ORDER BY '.$filter_order.' '.$filter_order_Dir.' , i.ordering, c.ordering ';
			}
		}

		return $orderby;
	}

	function _buildContentWhere()
	{
		$view = JRequest::getVar('view');
		$db					= JFactory::getDBO();
		
		$filter_featured	= $this->_params->get('featured_only', 0);
		
		$filter_catid		= (int) JRequest::getVar( 'cid',0,'default','string' );
		$filter_producerid	= (int) JRequest::getVar( 'pid',0,'default','string' );
		$search				= urldecode(JRequest::getVar( 'search','','default','string' ));
		$search				= JString::strtolower( $search );
		$search 			= trim(str_replace(array('#','>','<','\\'), '', $search));
		if (substr($search,0,1) == '"' && substr($search, -1) == '"') { 
			$search = substr($search,1,-1);
		}
		if (strlen($search) > 0 && (strlen($search)) < 3 || strlen($search) > 20) {
			 $search = null;
		}

		$where = array();
		if ($filter_catid >= 0) {
			
			if ($this->_params->get('product_catalogue')) {
				//$where[] = 'i.cat_id = '.(int) $filter_catid;
				$db->setQuery('SELECT item_id 
					   FROM #__djc2_items_categories AS ic
					   INNER JOIN #__djc2_categories AS c ON c.id=ic.category_id 
					   WHERE category_id=\''.(int)$filter_catid.'\' AND c.published = 1');
				$items = $db->loadResultArray();
				if (count ($items) > 0) {
					$items = array_unique($items);
					$where[] = 'i.id IN ('.implode(',',$items).')';
				} else $where[] ='1 = 0';
			}
			else {
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
						} else {
							$where[] = '1 = 0';
						}
						//$where[] = 'i.cat_id IN ( '.$cids.' )';
					}
					else if ($filter_catid != 0){
						JError::raiseError( 404, JText::_("COM_DJCATALOG2_PAGE_NOT_FOUND") );
					}
				}
			}
			
		}
		if ($filter_producerid > 0) {
			$where[] = 'i.producer_id = '.(int) $filter_producerid;
		}
		if ($search) {
			$phrase = 'any';
			$words = explode(' ',$search);
			$wheres = array();
			foreach ($words as $word) {
				$word		= $db->Quote('%'.$db->getEscaped($word, true).'%', false);
				$wheres2	= array();
				$wheres2[]	= 'i.description LIKE '.$word;
				$wheres2[]	= 'i.intro_desc LIKE '.$word;
				$wheres2[]	= 'i.name LIKE '.$word;
				$wheres2[]	= 'i.metadesc LIKE '.$word;
				$wheres2[]	= 'i.metakey LIKE '.$word;
				$wheres[]	= implode(' OR ', $wheres2);
			}
			$where[]	= '(' . implode(($phrase == 'all' ? ') AND (' : ') OR ('), $wheres) . ')';
		}
		if ($filter_featured > 0) {
			$where[] = 'i.featured = 1';
		}
		$where[] = 'i.published = 1';
		//$where[] = 'c.published = 1';
		$where 		= ( count( $where ) ? ' WHERE '. implode( ' AND ', $where ) : '' );
		return $where;
	}
	function getProducers(){
		if(!$this->_producers){
			$query = 'SELECT id, name as text, '
					. ' CASE WHEN CHAR_LENGTH(alias) THEN CONCAT_WS(":", id, alias) ELSE id END as value '
					.' FROM #__djc2_producers WHERE published=1 ORDER BY text';
			$this->_producers = $this->_getList($query,0,0);
		}
		return $this->_producers;
	}	
	/*
	 * function getProducers(){
   
       $db = JFactory::getDBO();
       $filter_catid      = (int) JRequest::getVar( 'cid',0,'default','string' );
       if ($filter_catid > 0) {
           $categories = Djc2Categories::getInstance(array('state'=>'1'));
           if ($parent = $categories->get((int)$filter_catid) ) {
                $childrenList = array($parent->id);
                $parent->makeChildrenList($childrenList);
                $cids = implode(',', $childrenList);
                $query = 'SELECT DISTINCT p.id, p.name as text, p.published as disabled, '
                 . ' CASE WHEN CHAR_LENGTH(p.alias) THEN CONCAT_WS(":", p.id, p.alias) ELSE p.id END as prodslug '
                 .' FROM #__djc2_producers AS p '
                 .' INNER JOIN #__djc2_items AS i ON p.id = i.producer_id '
                 .' INNER JOIN #__djc2_categories AS c ON c.id = i.cat_id '
                 .' WHERE c.id IN ('.$cids.')'
                 .' ORDER BY p.name '
                 ;
            }
       } else {
          $query = 'SELECT id, name as text, published as disabled, '
             . ' CASE WHEN CHAR_LENGTH(alias) THEN CONCAT_WS(":", id, alias) ELSE id END as prodslug '
             .' FROM #__djc2_producers ORDER BY name';
       }
       $db->setQuery($query);
       $_producers = $db->loadAssocList();
       return $_producers;
    }	
	 */
	
	function getParams() {
		return Djcatalog2Helper::getParams();
	}
	
}

