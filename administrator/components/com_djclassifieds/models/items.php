<?php
/**
* @version		2.0
* @package		DJ Classifieds
* @subpackage 	DJ Classifieds Component
* @copyright 	Copyright (C) 2010 DJ-Extensions.com LTD, All rights reserved.
* @license 		http://www.gnu.org/licenses GNU/GPL
* @author 		url: http://design-joomla.eu
* @author 		email contact@design-joomla.eu
* @developer 	Łukasz Ciastek - lukasz.ciastek@design-joomla.eu
*
*
* DJ Classifieds is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* DJ Classifieds is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with DJ Classifieds. If not, see <http://www.gnu.org/licenses/>.
*
*/
defined ('_JEXEC') or die('Restricted access');

/*Items Model*/

//jimport('joomla.application.component.model');
jimport('joomla.application.component.modellist');

class DjClassifiedsModelItems extends JModelList{
	
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'name', 'i.name',
				'id', 'i.id',				
				'cat_id', 'a.cat_id', 'category_name',
				'description', 'i.description',
				'tooltip', 'i.tooltip',
				'art_id', 'i.art_id',
				'details', 'i.details',
				'ordering', 'i.ordering',
				'date_start', 'i.date_start',
				'published', 'i.published',
				'abuse', 'a.c_abuse',
				'special', 'i.special','s_active'
			);
		}

		parent::__construct($config);
	}
	
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication();

		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published', '');
		$this->setState('filter.published', $published);

		$category = $this->getUserStateFromRequest($this->context.'.filter.category', 'filter_category', '');
		$this->setState('filter.category', $category);
		
		$published = $this->getUserStateFromRequest($this->context.'.filter.active', 'filter_active', '');
		$this->setState('filter.active', $published);
		
		// List state information.
		parent::populateState('i.id', 'desc');
	}

	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		$id	.= ':'.$this->getState('filter.published');
		$id	.= ':'.$this->getState('filter.category');
		$id	.= ':'.$this->getState('filter.active');
		
		return parent::getStoreId($id);
	}
	
	public function _buildWhere(){		
		$app = JFactory::getApplication();
		$where= '';
		
		$category = $this->getState('filter.category');		
		if (is_numeric($category) && $category != 0) {
			$catlist = ''; 
			$cats= DJClassifiedsCategory::getSubCatIemsCount((int) $category);
			$catlist= (int) $category;			
			foreach($cats as $c){
				$catlist .= ','. $c->id;
			}
			$where =' AND i.cat_id IN ('.$catlist.') '; 
		}

		$search = $this->getState('filter.search');		
		if (!empty($search)) {
			$db= &JFactory::getDBO();
			$search = $db->Quote('%'.$db->getEscaped($search, true).'%');
			$where .= " AND i.name LIKE ".$search." ";
		}
		
		$published = $this->getState('filter.published');
		if (is_numeric($published)) {
			$where .= ' AND i.published = ' . (int) $published;
		}
		
		$active = $this->getState('filter.active');
		if (is_numeric($active)) {
			if($active){
				$where .= ' AND i.date_start <= NOW() AND i.date_exp >= NOW() ';
			}else{
				$where .= ' AND (i.date_start >= NOW() OR i.date_exp <= NOW()) ';
			}
		}


		return $where;
	}
	
	function getItems(){
		if(empty($this->_items)) {
			$limit = JRequest::getVar('limit', '25', '', 'int');
			$limitstart = JRequest::getVar('limitstart', '0', '', 'int');
			
			$orderCol	= $this->state->get('list.ordering');
			$orderDirn	= $this->state->get('list.direction');
						
			if($orderCol=='i.ordering'){
				$orderCol = 'i.cat_id asc,i.ordering';	
			}elseif($orderCol=='category_name'){
				$orderCol = 'c.name';
			}elseif($orderCol=='s_active'){
				$active = $this->getState('filter.active');
				if (is_numeric($active)) {
					$orderCol=' i.date_exp ';
				}else{
					$orderCol = 's_active '.$orderDirn.', i.date_exp DESC ';
					$orderDirn = '';	
				}
				
			}									
			
			$db= &JFactory::getDBO();	
			$query = "SELECT i.*, c.name as cat_name, u.name as user_name,a.c_abuse, i.date_start <= NOW() AND i.date_exp >= NOW() AS s_active FROM #__djcf_items i "
			 		."LEFT JOIN #__djcf_categories c ON i.cat_id=c.id "
			 		."LEFT JOIN #__users u ON i.user_id=u.id "
			 		."LEFT JOIN (SELECT count(a.id) as c_abuse, a.item_id 
			 					FROM #__djcf_items_abuse a GROUP BY a.item_id ) a ON i.id=a.item_id "
					."  WHERE 1  ".$this->_buildWhere()." order by ".$orderCol." ".$orderDirn." ";
			$this->_items = $this->_getList($query, $limitstart, $limit);
			
			//$db->setQuery($query);$items=$db->loadObjectList();echo '<pre>';print_r($db);print_r($items);die();
		}
		return $this->_items;
	}
	
	protected function getReorderConditions($table)
	{
		$condition = array();
		$condition[] = 'cat_id = '.(int) $table->cat_id;
		return $condition;
	}
	
/*	function getCountItems(){
		$db= &JFactory::getDBO();
		$query = "SELECT count(i.id) FROM #__djcf_items i WHERE 1 ";
		$db->setQuery($query);
		$allelems=$db->loadResult();
		return $allelems;
	}*/
	
	public function getCategories(){
		if(empty($this->_categories)) {
			$query = "SELECT * FROM #__djcf_categories ORDER BY name";
			$this->_categories = $this->_getList($query,0,0);
		}
		return $this->_categories;
	}
	
	public function getCountItems(){
		if(empty($this->_countItems)) {
			$db= &JFactory::getDBO();
			$query = "SELECT count(i.id) FROM #__djcf_items i WHERE 1 ".$this->_buildWhere();
			$db->setQuery($query);
			$this->_countItems=$db->loadResult();
		}
		return $this->_countItems;
	}
	
	/*function getCat(){
		$db= &JFactory::getDBO();
		$query = "SELECT id, name FROM #__djcf_categories ORDER BY name";

		$db->setQuery($query);
		
		$cats[] = JHTML::_('select.option', '0', '- '.JText::_('Select Parent Category').' -', 'id', 'name');
		$db->setQuery($query);
		return array_merge($cats, $db->loadObjectList());
	}
	*/


}
?>