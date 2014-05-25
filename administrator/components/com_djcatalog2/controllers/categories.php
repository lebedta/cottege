<?php
/**
 * @version $Id: categories.php 28 2011-10-11 11:54:13Z michal $
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


class Djcatalog2ControllerCategories extends JControllerAdmin
{
	public function &getModel($name = 'Category', $prefix = 'Djcatalog2Model')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	public function recreateThumbnails() {
		JRequest::checkToken('default') or jexit( 'COM_DJCATALOG2_INVALID_TOKEN' );

		$cid = JRequest::getVar( 'cid', array(), 'default', 'array' );
		JArrayHelper::toInteger($cid);

		if (count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'COM_DJCATALOG2_SELECT_ITEM_TO_RECREATE_THUMBS ' ) );
		}
		
		$tmp = array();
		$tmp[0] = $cid[0];
		unset($cid[0]);

		$model = $this->getModel('categories');
		if(!$model->recreateThumbnails($tmp)) {
			$this->setRedirect( 'index.php?option=com_djcatalog2&view=categories',$model->getError() );
		}
		if (count( $cid ) < 1) {
			$this->setRedirect( 'index.php?option=com_djcatalog2&view=categories' );	
		} else {
			$cids = null;
			foreach ($cid as $value) {
				$cids .= '&cid[]='.$value; 
			}
			echo '<h3>'.JTEXT::_('COM_DJCATALOG2_RESIZING_CATEGORY').' [id = '.$tmp[0].']... '.JTEXT::_('COM_DJCATALOG2_PLEASE_WAIT').'</h3>';
			header("refresh: 0; url=".JURI::base().'index.php?option=com_djcatalog2&task=categories.recreateThumbnails'.$cids.'&'.JUtility::getToken().'=1');
		}
	}
}