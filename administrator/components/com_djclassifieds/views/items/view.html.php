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

jimport('joomla.application.component.view');


class DjClassifiedsViewItems extends JView
{
	protected $items;
	protected $pagination;
	
	function display($tpl = null)
	{
		$this->categories	= $this->get('Categories');		
		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->countItems	= $this->get('CountItems');
		
		
		
		jimport('joomla.html.pagination');		
		$limit = JRequest::getVar('limit', '25', '', 'int');
		$limitstart = JRequest::getVar('limitstart', '0', '', 'int');		
		$pagination = new JPagination($this->countItems, $limitstart, $limit);
		$this->pagination  = $pagination;
				


		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		$this->addToolbar();
		parent::display($tpl);
	}
	
	protected function addToolbar()
	{
		JToolBarHelper::title(JText::_('COM_DJCLASSIFIEDS_ITEMS'), 'generic.png');

		JSubMenuHelper::addEntry(JText::_('COM_DJCLASSIFIEDS_CPANEL'), 'index.php?option=com_djclassifieds&view=cpanel', false);
    	JSubMenuHelper::addEntry(JText::_('COM_DJCLASSIFIEDS_CATEGORIES'), 'index.php?option=com_djclassifieds&view=categories', false);
		JSubMenuHelper::addEntry(JText::_('COM_DJCLASSIFIEDS_ITEMS'), 'index.php?option=com_djclassifieds&view=items', true);
		JSubMenuHelper::addEntry(JText::_('COM_DJCLASSIFIEDS_FIELDS'), 'index.php?option=com_djclassifieds&view=fields', false);
		JSubMenuHelper::addEntry(JText::_('COM_DJCLASSIFIEDS_LOCALIZATION'), 'index.php?option=com_djclassifieds&view=regions', false);
		JSubMenuHelper::addEntry(JText::_('COM_DJCLASSIFIEDS_PROMOTIONS'), 'index.php?option=com_djclassifieds&view=promotions', false);
		JSubMenuHelper::addEntry(JText::_('COM_DJCLASSIFIEDS_DURATIONS'), 'index.php?option=com_djclassifieds&view=days', false);

		JToolBarHelper::addNew('item.add','JTOOLBAR_NEW');
		JToolBarHelper::editList('item.edit','JTOOLBAR_EDIT');
		JToolBarHelper::custom('items.recreateThumbnails','move','move',JText::_('COM_DJCLASSIFIEDS_RECREATE_THUMBNAILS'),true,true);
		JToolBarHelper::divider();
		JToolBarHelper::custom('items.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
		JToolBarHelper::custom('items.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
		JToolBarHelper::deleteList('', 'items.delete','JTOOLBAR_DELETE');
		JToolBarHelper::divider();
		JToolBarHelper::preferences('com_djclassifieds', 450, 800);
		JToolBarHelper::divider();
	}
}
?>