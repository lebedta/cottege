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

defined('_JEXEC') or die( 'Restricted access' );

jimport( 'joomla.application.component.view');
jimport('joomla.html.pane');

class DjclassifiedsViewCpanel extends JView
{
	function display($tpl = null)
	{
		$par = &JComponentHelper::getParams( 'com_djclassifieds' );
		$notify_days = $par->get('notify_days','0');

		if($notify_days>0 && $par->get('notify_days_trigger','1')==1 ){
			DJClassifiedsNotify::notifyExpired();
		}
		JSubMenuHelper::addEntry(JText::_('COM_DJCLASSIFIEDS_CPANEL'), 'index.php?option=com_djclassifieds&view=cpanel', true);
    	JSubMenuHelper::addEntry(JText::_('COM_DJCLASSIFIEDS_CATEGORIES'), 'index.php?option=com_djclassifieds&view=categories', false);
		JSubMenuHelper::addEntry(JText::_('COM_DJCLASSIFIEDS_ITEMS'), 'index.php?option=com_djclassifieds&view=items', false);
		JSubMenuHelper::addEntry(JText::_('COM_DJCLASSIFIEDS_FIELDS'), 'index.php?option=com_djclassifieds&view=fields', false);
		JSubMenuHelper::addEntry(JText::_('COM_DJCLASSIFIEDS_LOCALIZATION'), 'index.php?option=com_djclassifieds&view=regions', false);
		JSubMenuHelper::addEntry(JText::_('COM_DJCLASSIFIEDS_PROMOTIONS'), 'index.php?option=com_djclassifieds&view=promotions', false);
		JSubMenuHelper::addEntry(JText::_('COM_DJCLASSIFIEDS_DURATIONS'), 'index.php?option=com_djclassifieds&view=days', false);		

		JToolBarHelper::title('DJ Classifieds');
		JToolBarHelper::preferences('com_djclassifieds', 450, 800);
		parent::display($tpl);
	}

}
