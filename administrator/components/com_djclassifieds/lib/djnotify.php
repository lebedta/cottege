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

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.controller' );


class DJClassifiedsNotify {
	
	function __construct(){
	}

	public function notifyExpired($limit=0,$msg=1){
		$app = JFactory::getApplication();
        $par = &JComponentHelper::getParams( 'com_djclassifieds' );				
		$mailfrom = $app->getCfg( 'mailfrom' );
		$config =& JFactory::getConfig();    
		$fromname=$config->getValue('config.sitename').' - '.str_ireplace('administrator/', '', JURI::base());
		$notify_days = $par->get('notify_days','0');		
		$db= &JFactory::getDBO(); 
		
		if($notify_days>0){			
			$date_exp = date("Y-m-d G:i:s",mktime(date("G"), date("i"), date("s"), date("m")  , date("d")+$notify_days, date("Y")));
			$lim ='';
			
			if($limit>0){
				$lim = ' LIMIT '.$limit;	
			}
			
			
			$query = "SELECT i.id, i.cat_id, i.date_exp, i.name, i.user_id, u.email, u.name as u_name "
					."FROM #__djcf_items i, #__users u WHERE i.user_id = u.id AND i.notify=0 "
					."AND i.date_exp < '".$date_exp."' ".$lim;
			$db->setQuery($query);
			$items = $db->loadObjectList();
			//	echo '<pre>';print_r($db);print_r($items);die();	
		
				$menus	= & JApplication::getMenu('site');
				$menu_item = $menus->getItems('link','index.php?option=com_djclassifieds&view=items',1);
				
				$itemid = ''; 
				if($menu_item){
					$itemid='&Itemid='.$menu_item->id;
				}
				$renew_link=str_ireplace('administrator/', '', JURI::base()).'index.php?option=com_djclassifieds&view=useritems'.$itemid;
				$renew_link = JRoute::_($renew_link);
				
				$update_id = '';
				$items_c=0;
				
				foreach($items as $i){
					$mailto = $i->email;
										
					$subject= sprintf ( JText::_( 'COM_DJCLASSIFIEDS_UNEMAIL_TITLE' ), $i->name);
					$message = sprintf ( JText::_( 'COM_DJCLASSIFIEDS_UNEMAIL_MESSAGE' ), $i->name, $notify_days);
					$message .= sprintf ( JText::_( 'COM_DJCLASSIFIEDS_UNEMAIL_RENEW' ), $renew_link);
					$message .= sprintf ( JText::_( 'COM_DJCLASSIFIEDS_UNEMAIL_REGARDS' ), $config->getValue('config.sitename'));					
					
					JUtility::sendMail($mailfrom, $fromname, $mailto, $subject, $message);			 		
					$update_id .= $i->id.', ';	
					$items_c++;									
				}
				
				
				if($items_c>0){
					$update_id = substr($update_id, 0,-2);
					$query = "UPDATE `#__djcf_items` SET notify=1 WHERE id in (".$update_id.")";
					$db->setQuery($query);
					$db->query();
					if($msg==1){
						$app->enqueueMessage($items_c.' '.JText::_('COM_DJCLASSIFIEDS_NOTIFICATIONS_SENT'));
					}
				}											
		}
		return null;	
	}
	
	public function notifyAdmin($id){
		$app = JFactory::getApplication();
		$par = &JComponentHelper::getParams( 'com_djclassifieds' );	
		if($par->get('notify_admin','0') && $par->get('notify_user_email','')!=''){
			$mailto = $par->get('notify_user_email');
			$mailfrom = $app->getCfg( 'mailfrom' );
			$config =& JFactory::getConfig();    
			$fromname=$config->getValue('config.sitename').' - '.str_ireplace('administrator/', '', JURI::base());
			
			$query = "SELECT i.name, i.intro_desc, i.description, i.user_id, c.name as c_name,u.name as u_name "
					."FROM #__djcf_items i "
					."LEFT JOIN #__djcf_categories c ON c.id=i.cat_id "
					."LEFT JOIN #__users u ON u.id=i.user_id "
					."WHERE i.id = ".$id." LIMIT 1";
			$db =& JFactory::getDBO();		
			$db->setQuery($query);
			$item =$db->loadObject();
			

			$subject = JText::_('COM_DJCLASSIFIEDS_ANEMAIL_TITLE').' '.$config->getValue('config.sitename');
			$m_message = JText::_('COM_DJCLASSIFIEDS_ANEMAIL_TITLE').' '.$config->getValue('config.sitename')."<br /><br />";
			$m_message .= JText::_('COM_DJCLASSIFIEDS_TITLE').': '.$item->name."<br /><br />";
			$m_message .= JText::_('COM_DJCLASSIFIEDS_CATEGORY').': '.$item->c_name."<br /><br />";
			$m_message .= JText::_('COM_DJCLASSIFIEDS_INTRO_DESCRIPTION').': '.$item->intro_desc."<br /><br />";
			$m_message .= JText::_('COM_DJCLASSIFIEDS_DESCRIPTION').': '.$item->description."<br /><br />";
			if($item->user_id>0){
				$m_message .= JText::_('COM_DJCLASSIFIEDS_ANEMAIL_FROM_USER').': '.$item->u_name.' ('.$item->user_id.")<br /><br />";				
			}else{
				$m_message .= JText::_('COM_DJCLASSIFIEDS_ANEMAIL_FROM_USER').': '.JText::_('COM_DJCLASSIFIEDS_GUEST')."<br /><br />";
				
			}

			
			JUtility::sendMail($mailfrom, $fromname, $mailto, $subject, $m_message,$mode=1);
		}
		return null;		
	}


}
