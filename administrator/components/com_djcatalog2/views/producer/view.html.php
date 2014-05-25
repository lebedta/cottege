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
jimport('joomla.application.component.view');

class Djcatalog2ViewProducer extends JView {
	protected $state;
	protected $item;
	protected $form;
	
	public function display($tpl = null)
	{
		$this->state	= $this->get('State');
		$this->item		= $this->get('Item');
		$this->form		= $this->get('Form');

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
		JRequest::setVar('hidemainmenu', true);

		$user		= JFactory::getUser();
		$isNew		= ($this->item->id == 0);

		$text = $isNew ? JText::_( 'COM_DJCATALOG2_NEW' ) : JText::_( 'COM_DJCATALOG2_EDIT' );
		JToolBarHelper::title(   JText::_( 'COM_DJCATALOG2_PRODUCER' ).': <small><small>[ ' . $text.' ]</small></small>', 'generic.png' );
		
		JToolBarHelper::apply('producer.apply', 'JTOOLBAR_APPLY');
		JToolBarHelper::save('producer.save', 'JTOOLBAR_SAVE');
		JToolBarHelper::custom('producer.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
		JToolBarHelper::custom('producer.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
		JToolBarHelper::cancel('producer.cancel', 'JTOOLBAR_CANCEL');
	}
}
?>