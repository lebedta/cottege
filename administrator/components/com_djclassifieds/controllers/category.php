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


class DJClassifiedsControllerCategory extends JController {
	
	public function getModel($name = 'Category', $prefix = 'DJClassifiedsModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}
	
	public function getTable($type = 'Category', $prefix = 'DJClassifiedsTable', $config = array())
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
        JRequest::setVar('view', JRequest::getCmd('view', 'category'));
        parent::display($cachable);
        }
	
	public function add(){
		//$data = JFactory::getApplication();
		JRequest::setVar('view','category');
		parent::display();
	}
	
	public function cancel() {
		$app	= JFactory::getApplication();
		$app->redirect( 'index.php?option=com_djclassifieds&view=categories' );
	}
	
	public function save(){
    	$app = JFactory::getApplication();
		
		$model = $this->getModel('category');
		$row = &JTable::getInstance('Categories', 'DJClassifiedsTable');
		
		$par = &JComponentHelper::getParams( 'com_djclassifieds' );				
    	$row->bind(JRequest::get('post'));
    			
			if(JRequest::getVar('del_icon', '0','','int')){
			    $path_to_delete = JPATH_BASE."/../components/com_djclassifieds/images/".$row->icon_url;
		        if (JFile::exists($path_to_delete)){
		           	JFile::delete($path_to_delete);
			       }		
		       	if (JFile::exists($path_to_delete.'.ths.jpg')){
		           	JFile::delete($path_to_delete.'.ths.jpg');
			       }
				$icon_url='';
			}
			
			//add icon
		    $new_icon = $_FILES['icon'];					
		    if (substr($new_icon['type'], 0, 5) == "image")
		        {
				    $path_to_delete = JPATH_BASE."/../components/com_djclassifieds/images/".$row->icon_url;
		    	    if (JFile::exists($path_to_delete)){
		            	JFile::delete($path_to_delete);
			        }
		        	if (JFile::exists($path_to_delete.'.ths.jpg')){
		            	JFile::delete($path_to_delete.'.ths.jpg');
			        }
					
					if(count($new_icon['name'])>0 && $row->id==0){			
						$query = "SELECT id FROM #__djcf_categories ORDER BY id DESC LIMIT 1";
						$db =& JFactory::getDBO();		
						$db->setQuery($query);
						$last_id =$db->loadResult();
						$last_id++;
					}else{
						$last_id= $row->id;
					}
					
						$lang = JFactory::getLanguage();
						$icon_name = str_ireplace(' ', '_',$new_icon['name'] );						
						$icon_name = $lang->transliterate($icon_name);
						$icon_name = strtolower($icon_name);
						$icon_name = JFile::makeSafe($icon_name);
					
					$icon_name = 'cat'.$last_id.'_'.$icon_name;
		            $icon_url = $icon_name;
		            $path = JPATH_BASE."/../components/com_djclassifieds/images/".$icon_name;
		            move_uploaded_file($new_icon['tmp_name'], $path);
		
					$nw = $par->get('catth_width',-1);
		        	$nh = $par->get('catth_height',-1);

					DJClassifiedsImage::makeThumb($path, $nw, $nh, 'ths');
		        }
		
		    $row->icon_url = $icon_url;
			$row->price=$row->price*100;
		
			if(!$row->ordering){
				$query = "SELECT ordering FROM #__djcf_categories WHERE parent_id = ".$row->parent_id." ORDER BY ordering DESC LIMIT 1";
				$db =& JFactory::getDBO();		
				$db->setQuery($query);
				$order =$db->loadObject();
				$row->ordering = $order->ordering + 1;
			}
		
		if (!$row->store()){
			echo $row->getError();
        	exit ();	
    	}
				
    	switch(JRequest::getVar('task'))
    	{
	        case 'apply':
            	$link = 'index.php?option=com_djclassifieds&task=category.edit&id='.$row->id;
            	$msg = JText::_('COM_DJCLASSIFIEDS_CATEGORY_SAVED');
            	break;
			case 'save2new':
            	$link = 'index.php?option=com_djclassifieds&task=category.add';
            	$msg = JText::_('COM_DJCLASSIFIEDS_CATEGORY_SAVED');
            	break;				
        	case 'saveItem':
        	default:
	            $link = 'index.php?option=com_djclassifieds&view=categories';
            	$msg = JText::_('COM_DJCLASSIFIEDS_CATEGORY_SAVED');
            	break;
    	}

    	$app->redirect($link, $msg);
	
	}
	
	
}

?>