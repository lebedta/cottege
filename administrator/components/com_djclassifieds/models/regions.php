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

class DjClassifiedsModelRegions extends JModelList{
	
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
		
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication();
		
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);		

		$region = $this->getUserStateFromRequest($this->context.'.filter.region', 'filter_region', '');
		$this->setState('filter.region', $region);
		
		// List state information.
		parent::populateState('r.name', 'asc');
	}

	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		$id	.= ':'.$this->getState('filter.region');
		
		return parent::getStoreId($id);
	}
	
	public function _buildWhere(){		
		$app = JFactory::getApplication();
		$where= '';

		$region = $this->getState('filter.region');		
		if (is_numeric($region) && $region != 0) {
			$where = ' AND r.parent_id = ' . (int) $region;
		}
		
		$search = $this->getState('filter.search');		
		if (!empty($search)) {
			$db= &JFactory::getDBO();
			$search = $db->Quote('%'.$db->getEscaped($search, true).'%');
			$where .= " AND r.name LIKE ".$search." ";
		}
		return $where;
	}	

	function getRegions(){
		if(empty($this->_regions)) {
			$search = $this->getState('filter.search');
			if($this->getState('filter.region')!='' || $search!=''){
				$db= &JFactory::getDBO();
				$query = "SELECT r.*, rr.name as parent_name FROM #__djcf_regions r "
						."LEFT JOIN #__djcf_regions rr ON r.parent_id=rr.id "
						."WHERE 1  ".$this->_buildWhere()." ORDER BY r.name";
				$db->setQuery($query);
				$this->_regions=$db->loadObjectList();
			}else{
				$this->_regions = DJClassifiedsRegion::getRegAll();							
			}
			
		}
		return $this->_regions;
	}	
		
	
	public function getCountRegions(){
		if(empty($this->_countRegions)) {
			$db= &JFactory::getDBO();
			$query = "SELECT count(r.id) FROM #__djcf_regions r WHERE 1 ".$this->_buildWhere();
			$db->setQuery($query);
			$this->_countRegions=$db->loadResult();
		}
		return $this->_countRegions;
	}
	
	function getMainRegions(){
		if(empty($this->_mainregions)) {
			$db= &JFactory::getDBO();
			$query = "SELECT r.id as value, r.name as text FROM #__djcf_regions r "
					."WHERE r.parent_id=0 ORDER BY r.name";
			$db->setQuery($query);
			$this->_mainregions=$db->loadObjectList();	
		}
		return $this->_mainregions;
	}	



}
?>