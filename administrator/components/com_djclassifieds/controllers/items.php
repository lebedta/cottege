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

// No direct access.
defined('_JEXEC') or die;
JTable::addIncludePath(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_djclassifieds'.DS.'tables');
jimport('joomla.application.component.controlleradmin');

class DJClassifiedsControllerItems extends JControllerAdmin
{
	public function getModel($name = 'Item', $prefix = 'DJClassifiedsModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}
	
	function recreateThumbnails_org(){
    $task = JRequest::getVar('t', 'post');
	$par = &JComponentHelper::getParams( 'com_djclassifieds' );
    global $mainframe;
    $cid = JRequest::getVar('cid', array (), '', 'array');
    $db = & JFactory::getDBO();
    if (count($cid))
    {
        $cids = implode(',', $cid);
        $query = "SELECT id,image_url FROM #__djcf_items WHERE id IN ( ".$cids." )";
		$db->setQuery($query);
		$items = $db->loadObjectList();
		$sciezka = JPATH_BASE."/../components/com_djclassifieds/images/";
			$nw = (int)$par->get('th_width',-1);
    		$nh = (int)$par->get('th_height',-1);
			$nws = $par->get('smallth_width',-1);
    		$nhs = $par->get('smallth_height',-1);
			$nwm = $par->get('middleth_width',-1);
    		$nhm = $par->get('middleth_height',-1);
			$nwb = $par->get('bigth_width',-1);
    		$nhb = $par->get('bigth_height',-1);	
	
	
		foreach($items as $i){
			if($i->image_url){				
				$images = explode(";",$i->image_url);
				for($ii=0; $ii<count($images)-1;$ii++ ){												
        				if (JFile::exists($sciezka.$images[$ii].'.thb.jpg')){
            				JFile::delete($sciezka.$images[$ii].'.thb.jpg');
  						}
						if (JFile::exists($sciezka.$images[$ii].'.th.jpg')){
            				JFile::delete($sciezka.$images[$ii].'.th.jpg');
        				}
						if (JFile::exists($sciezka.$images[$ii].'.thm.jpg')){
            				JFile::delete($sciezka.$images[$ii].'.thm.jpg');
        				}
        				if (JFile::exists($sciezka.$images[$ii].'.ths.jpg')){
            				JFile::delete($sciezka.$images[$ii].'.ths.jpg');
        				}
						
					$this->makeimg($sciezka.$images[$ii], $nw, $nh, 'th');
			 		$this->makeimg($sciezka.$images[$ii], $nws, $nhs, 'ths');
					$this->makeimg($sciezka.$images[$ii], $nwm, $nhm, 'thm');
					$this->makeimg($sciezka.$images[$ii], $nwb, $nhb, 'thb');
				}
			}
		}

        
    }
	$limit = JRequest::getVar('limit', 10, '', 'int');
		$limitstart = JRequest::getVar('limitstart', 0, '', 'int');
		$order = JRequest::getVar('order');
		$cat_id = JRequest::getVar('cat_id',0,'',int);
        $redirect = 'index.php?option=com_djclassifieds&task='.$task.'&cat_id='.$cat_id.'&order='.$order.'&limit='.$limit.'&limitstart='.$limitstart;
    $mainframe->redirect($redirect, JText::_('THUMBNAILS_RECREATED'));
}
	
	function recreateThumbnails(){	
		$app = JFactory::getApplication();
		$par = &JComponentHelper::getParams( 'com_djclassifieds' );
	    $cid = JRequest::getVar('cid', array (), '', 'array');
		
	    $db = & JFactory::getDBO();
	    if (count($cid))
	    {
	        $cids = implode(',', $cid);
	        $query = "SELECT id, image_url FROM #__djcf_items WHERE id IN ( ".$cids." )";
			$db->setQuery($query);
			$items = $db->loadObjectList();
			$path = JPATH_BASE."/../components/com_djclassifieds/images/";
				$nw = (int)$par->get('th_width',-1);
	    		$nh = (int)$par->get('th_height',-1);
				$nws = $par->get('smallth_width',-1);
	    		$nhs = $par->get('smallth_height',-1);
				$nwm = $par->get('middleth_width',-1);
	    		$nhm = $par->get('middleth_height',-1);
				$nwb = $par->get('bigth_width',-1);
	    		$nhb = $par->get('bigth_height',-1);							
		
			foreach($items as $i){
				if($i->image_url){				
					$images = explode(";",$i->image_url);
					for($ii=0; $ii<count($images)-1;$ii++ ){												
	        				if (JFile::exists($path.$images[$ii].'.thb.jpg')){
	            				JFile::delete($path.$images[$ii].'.thb.jpg');
	  						}
							if (JFile::exists($path.$images[$ii].'.th.jpg')){
	            				JFile::delete($path.$images[$ii].'.th.jpg');
	        				}
							if (JFile::exists($path.$images[$ii].'.thm.jpg')){
	            				JFile::delete($path.$images[$ii].'.thm.jpg');
	        				}
	        				if (JFile::exists($path.$images[$ii].'.ths.jpg')){
	            				JFile::delete($path.$images[$ii].'.ths.jpg');
	        				}
							
						DJClassifiedsImage::makeThumb($path.$images[$ii], $nw, $nh, 'th');
				 		DJClassifiedsImage::makeThumb($path.$images[$ii], $nws, $nhs, 'ths');
						DJClassifiedsImage::makeThumb($path.$images[$ii], $nwm, $nhm, 'thm');
						DJClassifiedsImage::makeThumb($path.$images[$ii], $nwb, $nhb, 'thb');				
					}
				}
			}				        
	    }
	    $redirect = 'index.php?option=com_djclassifieds&view=items';
	    $app->redirect($redirect, JText::_('COM_DJCLASSIFIEDS_THUMBNAILS_RECREATED'));
	}
	
	function delete()
	{
	    $app = JFactory::getApplication();
	    $cid = JRequest::getVar('cid', array (), '', 'array');
	    $db = & JFactory::getDBO();
	    if (count($cid))
	    {
	        $cids = implode(',', $cid);
	        $query = "SELECT id,image_url FROM #__djcf_items WHERE id IN ( ".$cids." )";
			$db->setQuery($query);
			$items = $db->loadObjectList();
			$path_to_delete = JPATH_BASE."/../components/com_djclassifieds/images/";
			foreach($items as $i){
				if($i->image_url){				
					$images = explode(";",$i->image_url);
					for($ii=0; $ii<count($images)-1;$ii++ ){
							if (JFile::exists($path_to_delete.$images[$ii])){
	            				JFile::delete($path_to_delete.$images[$ii]);
	        				}
	        				if (JFile::exists($path_to_delete.$images[$ii].'.thb.jpg')){
	            				JFile::delete($path_to_delete.$images[$ii].'.thb.jpg');
	  						}
							if (JFile::exists($path_to_delete.$images[$ii].'.th.jpg')){
	            				JFile::delete($path_to_delete.$images[$ii].'.th.jpg');
	        				}
							if (JFile::exists($path_to_delete.$images[$ii].'.thm.jpg')){
	            				JFile::delete($path_to_delete.$images[$ii].'.thm.jpg');
	        				}
	        				if (JFile::exists($path_to_delete.$images[$ii].'.ths.jpg')){
	            				JFile::delete($path_to_delete.$images[$ii].'.ths.jpg');
	        				}
					}
				}
			}
			
	        $cids = implode(',', $cid);
	        $query = "DELETE FROM #__djcf_items WHERE id IN ( ".$cids." )";
	        $db->setQuery($query);
	        if (!$db->query())
	        {
				echo $row->getError();
        		exit ();	
	        }
			
			$query = "DELETE FROM #__djcf_fields_values WHERE item_id IN ( ".$cids." )";
	        $db->setQuery($query);
	        $db->query();
			
			$query = "DELETE FROM #__djcf_payments WHERE item_id IN ( ".$cids." )";
	        $db->setQuery($query);
	        $db->query();
	    }
	    $app->redirect('index.php?option=com_djclassifieds&view=items', JText::_('COM_DJCLASSIFIEDS_ITEMS_DELETED'));
	}

}