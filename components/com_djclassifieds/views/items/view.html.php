<?php
/**
* @version		2.0
* @package		DJ Classifieds
* @subpackage	DJ Classifieds Component
* @copyright	Copyright (C) 2010 DJ-Extensions.com LTD, All rights reserved.
* @license		http://www.gnu.org/licenses GNU/GPL
* @autor url    http://design-joomla.eu
* @autor email  contact@design-joomla.eu
* @Developer    Lukasz Ciastek - lukasz.ciastek@design-joomla.eu
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
jimport('joomla.html.pagination');

class DJClassifiedsViewItems extends JView{

	function display($tpl = null){
					
		$document = & JFactory::getDocument();
		$par = &JComponentHelper::getParams( 'com_djclassifieds' );
		$app	= JFactory::getApplication();
		JHTML::_( 'behavior.modal' );
		
		$model = $this->getModel();
		
		$cat_id	= JRequest::getVar('cid', 0, '', 'int');
		$uid	= JRequest::getVar('uid', 0, '', 'int');
		$reset	= JRequest::getVar('reset', 0, '', 'int');
		
		if($reset){
			$items= $model->resetSearchFilters();	
		}
		
		$catlist = ''; 
		if($cat_id>0){
			$cats= DJClassifiedsCategory::getSubCatIemsCount($cat_id,1);
			$catlist= $cat_id;			
			foreach($cats as $c){
				$catlist .= ','. $c->id;
			}				
		}else{
			$cats= DJClassifiedsCategory::getCatAllItemsCount(1);
		}
		
		$items= $model->getItems($catlist);
		$countitems = $model->getCountItems($catlist);
		
		$cat_id	= JRequest::getVar('cid', 0, '', 'int');
		if($cat_id>0){
			$main_cat= $model->getMainCat($cat_id);		
					
			if($main_cat->metakey!=''){
				$document->setMetaData('keywords',$main_cat->metakey);
			}
			$c_title = $document->getTitle();
			$document->setTitle($c_title.' - '.strip_tags($main_cat->name));
			
			if($main_cat->metadesc!=''){
				$document->setDescription($main_cat->metadesc);
			}	
		}else{
			$main_cat = '';
		}
		
		$cat_path = array();
		if($cat_id>0){
			$pathway =$app->getPathway();
			if($main_cat->parent_id==0){
				$pathway->addItem($main_cat->name);
			}else{				
				$cat_path= DJClassifiedsCategory::getParentPath(1,$main_cat->id);	
				for($c=count($cat_path);$c>0;$c--){
					$pathway->addItem($cat_path[$c-1]->name, 'index.php?option=com_djclassifieds&view=items&cid='.$cat_path[$c-1]->id);			
				}					
			}	
		}
		
		if($uid>0){
			$u_name = $model->getUserName($uid);
			$this->assignRef('u_name', $u_name);
		}
		
		
		$limit	= JRequest::getVar('limit', $par->get('limit_djitem_show'), '', 'int');
		$limitstart	= JRequest::getVar('limitstart', 0, '', 'int');
		$pagination = new JPagination( $countitems, $limitstart, $limit );

	//	$main_name = $model->makePathway($cat_list);

		if ($par->get('rss_feed', 1) == 1){
			$link	= '&format=feed&limitstart=';
			$attribs = array('type' => 'application/rss+xml', 'title' => 'RSS 2.0');
			$document->addHeadLink(JRoute::_($link.'&type=rss'), 'alternate', 'rel', $attribs);
			$attribs = array('type' => 'application/atom+xml', 'title' => 'Atom 1.0');
			$document->addHeadLink(JRoute::_($link.'&type=atom'), 'alternate', 'rel', $attribs);
		}
	
			
		$this->assignRef('cats',$cats);
		$this->assignRef('cat_path',$cat_path);
		$this->assignRef('items', $items);
		$this->assignRef('main_cat', $main_cat);
		$this->assignRef('pagination', $pagination);

		parent::display('cat');			
        parent::display($tpl);
	}

}




