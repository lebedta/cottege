<?php
/**
 * @version 2.14
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

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class Djcatalog2ViewCategories extends JView
{
	protected $items;
	protected $pagination;
	protected $state;

	public function display($tpl = null)
	{
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
		$this->categories = Djc2Categories::getInstance();
		
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
		JToolBarHelper::title(JText::_('COM_DJCATALOG2_CATEGORIES'), 'generic.png');

		JToolBarHelper::addNew('category.add','JTOOLBAR_NEW');

		JToolBarHelper::editList('category.edit','JTOOLBAR_EDIT');
		JToolBarHelper::custom('categories.recreateThumbnails','move','move',JText::_('COM_DJCATALOG2_RECREATE_THUMBNAILS'),true,true);

		JToolBarHelper::divider();
		JToolBarHelper::custom('categories.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
		JToolBarHelper::custom('categories.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);

		JToolBarHelper::deleteList('', 'categories.delete','JTOOLBAR_DELETE');
		JToolBarHelper::divider();

		JToolBarHelper::preferences('com_djcatalog2', 450, 800);
		JToolBarHelper::divider();
	}
}
