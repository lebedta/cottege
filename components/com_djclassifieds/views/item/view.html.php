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


class DJclassifiedsViewItem extends JView{
		
	function display($tpl = null){
		
		$model =& $this->getModel();
		$par = &JComponentHelper::getParams( 'com_djclassifieds' );
		$document = & JFactory::getDocument();
		$app	= JFactory::getApplication();
		$item=$model->getItem();
		$category=$model->getCategory($item->cat_id);
		$fields=$model->getFields($item->cat_id);
		if($item->user_id!=0){
			$user_items_c=$model->getUserItemsCount($item->user_id);
			$this->assignRef('user_items_c',$user_items_c);
		}
		
			if($category->metakey!=''){
				$document->setMetaData('keywords',$category->metakey);
			}			
			$c_title = $document->getTitle();
			$document->setTitle($c_title.' - '.strip_tags($item->name));			
			if($category->metadesc!=''){
				$document->setDescription($category->metadesc);
			}	
			
			$document->setMetaData('og:title',$item->name);
			if($item->image_url!=''){
				$images = explode(";",$item->image_url);
				$document->setMetaData('og:image',JURI::base().'components/com_djclassifieds/images/'.$images[0]);
			} 
		 	
		
			$pathway =$app->getPathway();
			if($category->id!=0){	
				$cat_path= DJClassifiedsCategory::getParentPath(1,$category->id);	
				for($c=count($cat_path);$c>0;$c--){
					$pathway->addItem($cat_path[$c-1]->name, 'index.php?option=com_djclassifieds&view=items&cid='.$cat_path[$c-1]->id);			
				}					
			}	
		$regions=$model->getRegions();
		$country='';
		$city='';		
		
		if($item->region_id!=0 && $par->get('show_regions','1')){
			$address='';								
			$rid = $item->region_id;
			if($rid!=0){
				while($rid!=0){	
					foreach($regions as $li){
						if($li->id==$rid){
							$rid=$li->parent_id;
							$address.=$li->name.', ';
							if($li->country){
								$country =$li->name; 
							}
							if($li->city){
								$city =$li->name; 
							}
							break;
						}
					}
					if($rid==$item->region_id){break;}
				}
			}
			$address = substr($address, 0, -2);
		}

		$pathway->addItem($item->name);
		$this->assignRef('item',$item);
		$this->assignRef('fields',$fields);
		$this->assignRef('country',$country);
		$this->assignRef('city',$city);
		$this->assignRef('address',$address);

        parent::display($tpl);
	}

}




