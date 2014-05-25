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
defined ('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');


class DJClassifiedsViewItem extends JView
{

	function display($tpl = null)
	{				
	    //$model =& $this->getModel();
	    $this->item = $this->get('Item');
		$this->regions = $this->get('Regions');
		$this->payment = $this->get('Payment');
		$this->promotions = $this->get('Promotions');
		$country='';
		$city='';
								
		$reg_path='';		
		if($this->item->region_id!=0){								
			$id = Array();
			$name = Array();
			$rid = $this->item->region_id;
			if($rid!=0){
				while($rid!=0){	
					foreach($this->regions as $li){
						if($li->id==$rid){
							$rid=$li->parent_id;
							$id[]=$li->id;
							$name[]=$li->name;
							$reg_path = 'new_reg('.$li->parent_id.','.$li->id.');'.$reg_path;
							if($li->country){
								$country =$li->name; 
							}
							if($li->city){
								$city =$li->name; 
							}
							break;
						}
					}
					if($rid==$this->item->region_id){break;}
				}
			}
		}
		
		$this->country = $country;
		$this->city = $city;
		
		$this->reg_path = $reg_path;
		
		
		
		/*
		$this->cats = $this->get('Categories');
		
		$cat_path='';		
		if($this->item->cat_id!=0){								
			$id = Array();
			$name = Array();
			$cid = $this->item->cat_id;
			if($cid!=0){
				while($cid!=0){	
					foreach($this->cats as $li){
						if($li->id==$cid){
							$cid=$li->parent_id;
							$id[]=$li->id;
							$name[]=$li->name;
							$cat_path = 'new_cat('.$li->parent_id.','.$li->id.');'.$cat_path;
							break;
						}
					}
				}
			}
		}
		
		$this->cat_path = $cat_path;*/
		
		$this->selusers = $this->get('selUsers');
		$this->abuse = $this->get('AbuseRaports');	

				
		
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
		JRequest::setVar('hidemainmenu',1);
		
		$user		= JFactory::getUser();
		$isNew		= ($this->item->id == 0);

		$text = $isNew ? JText::_( 'COM_DJCLASSIFIEDS_NEW' ) : JText::_( 'COM_DJCLASSIFIEDS_EDIT' );
		JToolBarHelper::title(   JText::_( 'COM_DJCLASSIFIEDS_ITEM' ).': <small><small>[ ' . $text.' ]</small></small>', 'generic.png' );

		JToolBarHelper::apply('item.apply', 'JTOOLBAR_APPLY');
		JToolBarHelper::save('item.save', 'JTOOLBAR_SAVE');
		JToolBarHelper::custom('item.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
		//JToolBarHelper::custom('item.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
		JToolBarHelper::cancel('item.cancel', 'JTOOLBAR_CANCEL');
	}

}