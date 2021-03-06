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

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');
jimport( 'joomla.database.table' );


class DJClassifiedsControllerField extends JController {
	
	public function getModel($name = 'Field', $prefix = 'DJClassifiedsModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}
	
	public function getTable($type = 'Field', $prefix = 'DJClassifiedsTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	function __construct($default = array ())
    {
        parent::__construct($default);
        $this->registerTask('apply', 'save');
		$this->registerTask('save2new', 'save');
		$this->registerTask('edit', 'add');
    }
	
	
	function display($cachable = false){
        JRequest::setVar('view', JRequest::getCmd('view', 'field'));
        parent::display($cachable);
        }
	
	public function add(){
		//$data = JFactory::getApplication();		
		JRequest::setVar('view','field');
		parent::display();
	}
	
	public function cancel() {
		$app	= JFactory::getApplication();
		$app->redirect( 'index.php?option=com_djclassifieds&view=fields' );
	}
	
	public function save(){
    	$app = JFactory::getApplication();
		
		$model = $this->getModel('field');
		$row = &JTable::getInstance('Fields', 'DJClassifiedsTable');
		
		$par = &JComponentHelper::getParams( 'com_djclassifieds' );
				
    	if (!$row->bind(JRequest::get('post')))
    	{
	        echo "<script> alert('".$row->getError()."');
				window.history.go(-1); </script>\n";
        	exit ();
    	}

			$row->name=strtolower($row->name);
			$row->name=str_ireplace(' ', '_', $row->name); 
			$row->name=ereg_replace("[^a-z0-9_]", "", $row->name );
		   	$name=$row->name;
			$next_name=1;
				$query = "SELECT count(id) FROM #__djcf_fields WHERE name='".$name."' ";
				if($row->id>0){	
					$query .= " AND id!='".$row->id."' ";
				}
				$db =& JFactory::getDBO();		
				$db->setQuery($query);
				$exist =$db->loadResult();
				while($exist>0){
					$name = $row->name.'_'.$next_name; 
					$query = "SELECT count(id) FROM #__djcf_fields WHERE name='".$name."' ";
					if($row->id>0){	
						$query .= " AND id!='".$row->id."' ";
					}
					$db =& JFactory::getDBO();		
					$db->setQuery($query);
					$exist =$db->loadResult();	
					$next_name++;
				}
				$row->name = $name;
		$row->params = JRequest::getVar('params', '', 'post', 'string', JREQUEST_ALLOWRAW);


		if (!$row->store())
    	{
        	echo "<script> alert('".$row->getError()."');
				window.history.go(-1); </script>\n";
        	exit ();	
    	}
    	switch(JRequest::getVar('task'))
    	{
	        case 'apply':
            	$link = 'index.php?option=com_djclassifieds&task=field.edit&id='.$row->id;
            	$msg = JText::_('COM_DJCLASSIFIEDS_FIELD_SAVED');
            	break;
			case 'save2new':
            	$link = 'index.php?option=com_djclassifieds&task=field.add';
            	$msg = JText::_('COM_DJCLASSIFIEDS_FIELD_SAVED');
            	break;				
        	case 'saveItem':
        	default:
	            $link = 'index.php?option=com_djclassifieds&view=fields';
            	$msg = JText::_('COM_DJCLASSIFIEDS_FIELD_SAVED');
            	break;
    	}

    	$app->redirect($link, $msg);
	
	}
	
	
}

?>