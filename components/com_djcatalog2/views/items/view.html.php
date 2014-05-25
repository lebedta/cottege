<?php
/**
* @version $Id: view.html.php 28 2011-10-11 11:54:13Z michal $
* @package DJ-Catalog2
* @copyright Copyright (C) 2010 Blue Constant Media LTD, All rights reserved.
* @license http://www.gnu.org/licenses GNU/GPL
* @author url: http://design-joomla.eu
* @author email contact@design-joomla.eu
* @developer Michal Olczyk - michal.olczyk@design-joomla.eu
*
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

jimport('joomla.application.component.view');
jimport('joomla.html.pagination');

class DJCatalog2ViewItems extends JView {
	function display($tpl = null) {
		JHTML::_( 'behavior.modal' );
		$app = JFactory::getApplication();
		$view = JRequest::getVar('view');
		$document= JFactory::getDocument();
		$model = $this->getModel();
		$params = $model->getParams();
		
		$menus		= $app->getMenu('site');
		$menu  = $menus->getActive();
		$layout = JRequest::getVar('layout', 'default', 'default', 'string');
		$dispatcher	= JDispatcher::getInstance();
		$categories = Djc2Categories::getInstance(array('state'=>'1'));
		
		$limitstart	= JRequest::getVar('limitstart', 0, '', 'int');
		$limit_items_show = $params->get('limit_items_show',10);
		JRequest::setVar('limit', $limit_items_show);
		
		$filter_catid		= JRequest::getVar( 'cid',0,'default','int' );
		$filter_producerid	= JRequest::getVar( 'pid',0,'default','string' );
		$filter_order		= JRequest::getVar( 'order',$params->get('items_default_order','i.ordering'),'default','cmd' );
		$filter_order_Dir	= JRequest::getVar( 'dir',	$params->get('items_default_order_dir','asc'),'default','word' );
		$search				= urldecode(JRequest::getVar( 'search','','default','string' ));
		$search				= JString::strtolower( $search );
		if (strlen($search) > 0 && (strlen($search)) < 3 || strlen($search) > 20) {
			 JError::raiseNotice(  E_USER_NOTICE, JText::_( 'COM_DJCATALOG2_SEARCH_RESTRICTION') );
		}
		if ($filter_order_Dir == '' || $filter_order_Dir == 'desc') {
			$lists['order_Dir'] = 'asc';			
		} else {
			$lists['order_Dir'] = 'desc';
		}
		$lists['order'] = $filter_order;
		
		$list = $model->getList();
		$total = $model->getTotal();
		$pagination = $model->getPagination();
		
		// search filter
		$lists['search']= $search;
		
		// category filter
		$category_options = $categories->getOptionList('- '.JText::_('COM_DJCATALOG2_SELECT_CATEGORY').' -');
		$lists['categories'] = JHTML::_('select.genericlist', $category_options, 'cid', 'class="inputbox" onchange="document.djcatalogForm.submit()"', 'value', 'text', $filter_catid);
		
		// producer filter
		$producers_first_option = new stdClass();
		$producers_first_option->id = '';
		$producers_first_option->text = '- '.JText::_('COM_DJCATALOG2_SELECT_PRODUCER').' -';
		$producers_first_option->disable = false;
		$prodList = $model->getProducers();
		$producers = count($prodList) ? array_merge(array($producers_first_option),$prodList) : array($producers_first_option);
		$lists['producers'] = JHTML::_('select.genericlist', $producers, 'pid', 'class="inputbox" onchange="document.djcatalogForm.submit()"', 'id', 'text', $filter_producerid);
		
		// current category
		$category = $categories->get((int) JRequest::getVar('cid',0,'default','default'));
		$subcategories = null;
		if (!empty($category)) {
			$subcategories = $category->getChildren();
		}
		/* If Cateogory not published set 404 */
		if (($category && $category->id > 0 && $category->published == 0) || empty($category)) {
				return JError::raiseError( 404, JText::_( 'COM_DJCATALOG2_PRODUCT_NOT_FOUND') );
		}
		
		/* plugins */
		if ($category && $category->id > 0) {
			JPluginHelper::importPlugin('djcatalog2');
			$results = $dispatcher->trigger('onPrepareItemDescription', array (& $category, & $params, $limitstart));
		}
		
		$this->assignref('document',$document);
		$this->assignref('item',$category);
		$this->assignref('categories',$categories);
		$this->assignref('subcategories',$subcategories);
		$this->assignref('lists', $lists);
		$this->assignref('items', $list);
		$this->assignref('lists',	$lists);
		$this->assignref('total', $total);
		$this->assignref('pagination',	$pagination);
		$this->assignref('params',	$params);
		$this->_prepareDocument();
		
        parent::display($tpl);
	}
	
	protected function _prepareDocument() {
		$app		= JFactory::getApplication();
		$menus		= $app->getMenu();
		$pathway	= $app->getPathway();
		$title		= null;
		$heading		= null;

		$menu = $menus->getActive();
		
		$id = (int) @$menu->query['cid'];
		
		if ($menu) {
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		}
		else {
			$this->params->def('page_heading', JText::_('COM_DJCATALOG2'));
		}
		$title = $this->params->get('page_title', '');

		if ($menu && ($menu->query['option'] != 'com_djcatalog2' || $menu->query['view'] == 'item' || $id != $this->item->id)) {
			
			if ($this->item->name) {
				$title = $this->item->name;
			}
			
			$path = array(array('title' => $this->item->name, 'link' => ''));
			$category = $this->categories->get($this->item->parent_id);
			while (($menu->query['option'] != 'com_djcatalog2' || $menu->query['view'] == 'item' || $id != $category->id) && $category->id > 0)
			{
				$path[] = array('title' => $category->name, 'link' => DJCatalogHelperRoute::getCategoryRoute($category->catslug));
				$category = $this->categories->get($category->parent_id);
			}

			$path = array_reverse($path);
			
			foreach ($path as $item)
			{
				$pathway->addItem($item['title'], $item['link']);
			}
		}

		if (empty($title)) {
			$title = $app->getCfg('sitename');
		}
		elseif ($app->getCfg('sitename_pagetitles', 0)) {
			$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		}

		$this->document->setTitle($title);

		if (!empty($this->item->metadesc))
		{
			$this->document->setDescription($this->item->metadesc);
		}
		elseif (empty($this->item->metadesc) && $this->params->get('menu-meta_description')) 
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		if (!empty($this->item->metakey))
		{
			$this->document->setMetadata('keywords', $this->item->metakey);
		}
		elseif (empty($this->item->metakey) && $this->params->get('menu-meta_keywords')) 
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

	}

}




