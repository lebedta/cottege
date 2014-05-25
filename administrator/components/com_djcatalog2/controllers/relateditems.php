<?php
/**
 * @version $Id: relateditems.php 28 2011-10-11 11:54:13Z michal $
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
defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.application.component.controlleradmin');


class Djcatalog2ControllerRelateditems extends JControllerAdmin
{
public function __construct($config = array())
	{
		parent::__construct($config);

		$this->registerTask('assignclose',		'assign');
	}
	public function &getModel($name = 'Relateditems', $prefix = 'Djcatalog2Model')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	public function assign()
	{
		JHTML::_('behavior.mootools');
		$task		= $this->getTask();
		$item_id = JRequest::getVar('item_id',null);
		$document	= JFactory::getDocument();
		
		// Check for request forgeries
		JRequest::checkToken() or die(JText::_('JINVALID_TOKEN'));

		$session	= JFactory::getSession();
		$registry	= $session->get('registry');

		// Get items to publish from the request.
		$cid	= JRequest::getVar('cid', array(), '', 'array');
		$task 	= $this->getTask();
		if (empty($item_id)) {
			JError::raiseWarning(500, JText::_($this->text_prefix.'_NO_PARENT_ITEM_SELECTED'));
		}
		else {
			// Get the model.
			$model = $this->getModel();

			// Make sure the item ids are integers
			JArrayHelper::toInteger($cid);

			// Publish the items.
			if (!$model->assign($cid, $item_id)) {
				JError::raiseWarning(500, $model->getError());
			}
			else {
				if (count($cid) > 0) {
					$this->setMessage(JText::_($this->text_prefix.'_N_RELATED_ITEMS_ASSIGNED'));
				} else {
					$this->setMessage(JText::_($this->text_prefix.'_RELATED_ITEMS_REMOVED'));
				}
			}
		}
		if ($task == 'assignclose') {
		$document->addScriptDeclaration('window.addEvent(\'domready\',function(){
			window.parent.SqueezeBox.close();
		});');
		} else {
			$extension = JRequest::getCmd('extension');
			$extensionURL = ($extension) ? '&extension=' . JRequest::getCmd('extension') : '';
			$this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view='.$this->view_list.'&item_id='.$item_id.'&tmpl=component'.$extensionURL, false));
		}
		
	}
	
}