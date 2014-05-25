<?php
/**
 * @version $Id: djlicense.php 85 2012-08-01 14:01:19Z michal $
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

defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.modal');

class DJLicense{
	
	function getSubscription(){
		
		$app	= JFactory::getApplication();		
		$ext = JRequest::getString('option', '');
		
		$ch = curl_init();		
		$db = JFactory::getDBO();
			$query = "SELECT manifest_cache FROM #__extensions WHERE element ='".$ext."' AND type='component' ";
			$db->setQuery($query);
			$mc = json_decode($db->loadResult());
		$version = $mc->version;
		$config = JFactory::getConfig();
		
		$license_file = JPATH_BASE."/components/".JRequest::getVar('option')."/license_".$config->getValue('config.secret').".txt";
		if(JFile::exists($license_file)){
			$fh = fopen($license_file, 'r');
			$license = fgets($fh);	
			fclose($fh);
		}else{
			$license = '';
		}
		
		curl_setopt($ch, CURLOPT_URL,'http://dj-extensions.com/index.php?option=com_djsubscriptions&view=checkDomain&license='.$license.'&ext='.$ext.'&v='.$version.'');
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);		
		$u = JFactory::getURI();
		
		curl_setopt ($ch, CURLOPT_REFERER, $u->getHost());
		
		if(!curl_errno($ch))
		{
			$contents = curl_exec ($ch); 
		}
		
		curl_close ($ch);
		$res= explode(';', $contents);		
		
		if(strstr($res[0], 'E')){
			$t = JRequest::getVar('task',''); 
			if($t!='license' && $t!='Savelicense'){
				if($license==''){
					$app->enqueueMessage(JText::_('COM_DJCATALOG2_DJLIC_ENTER_LICENSE'),'Error');
				}else{
					$app->enqueueMessage(end($res),'Error');
				}					
			}
			$update = '<div style="width:220px;margin-top:10px;">';
			$update .= '<div style="text-align:left;margin-bottom:5px;"><img style="margin:-3px 0 -5px 0 !important;padding:0px;" src="'.JURI::base().'/templates/hathor/images/admin/publish_r.png"/> '.JText::_('COM_DJCATALOG2_DJLIC_YOUR_LIC_IS').' <a style="font-weight:bold;" class="modal" rel="{handler: \'iframe\', size: {x: 500, y: 250},onClose:function(){window.parent.document.location.reload(true);}}" href="index.php?option='.$ext.'&task=license.edit&tmpl=component">'.JText::_('COM_DJCATALOG2_DJLIC_INVALID').'</a></div>';						
			$update .= '<div style="text-align:left;margin-bottom:5px;"><img style="margin:-3px 0 -5px 0 !important;padding:0px;" src="'.JURI::base().'/templates/hathor/images/admin/publish_r.png" /> '.JText::_('COM_DJCATALOG2_DJLIC_EXP_DATE').' </div>';
			$update .= '<div style="text-align:left;margin-bottom:5px;"><img style="margin:-3px 0 -5px 0 !important;padding:0px;" src="'.JURI::base().'/templates/hathor/images/admin/publish_r.png" /> '.JText::_('COM_DJCATALOG2_DJLIC_LAST_VERSION_AVAILABLE').'</div>';
			$update .= '</div>';			
			return $update;			

		}else{					
		
			$update = '<div style="width:220px;margin-top:10px;">';
			$update .= '<div style="text-align:left;margin-bottom:5px;"><img style="margin:-3px 0 -5px 0 !important;padding:0px;" src="'.JURI::base().'/templates/hathor/images/admin/tick.png"/> '.JText::_('COM_DJCATALOG2_DJLIC_YOUR_LIC_IS').' <a style="font-weight:bold;" class="modal" rel="{handler: \'iframe\', size: {x: 500, y: 250},onClose:function(){window.parent.document.location.reload(true);}}" href="index.php?option='.$ext.'&task=license.edit&tmpl=component">'.JText::_('COM_DJCATALOG2_DJLIC_VALID').'</a></div>';						
			$update .= '<div style="text-align:left;margin-bottom:5px;"><img style="margin:-3px 0 -5px 0 !important;padding:0px;" src="'.JURI::base().'/templates/hathor/images/admin/tick.png" /> '.JText::_('COM_DJCATALOG2_DJLIC_EXP_DATE').' <span style="display:inline;">'.$res[2].'</span></div>';

			if(version_compare($version,$res[3],'<')){
				$update .= '<div style="text-align:left;margin-bottom:5px;"><img style="margin:-3px 0 -5px 0 !important;padding:0px;" src="'.JURI::base().'/templates/hathor/images/admin/publish_r.png" /> '.JText::_('COM_DJCATALOG2_DJLIC_NEW_VERSION_AVAILABLE').' <a style="font-weight:bold;" class="modal" rel="{handler: \'iframe\', size: {x: 600, y: 350},onClose:function(){window.parent.document.location.reload(true);}}" href="index.php?option='.$ext.'&task=license.update_list&tmpl=component">'.JText::_('COM_DJCATALOG2_DJLIC_UPDATE').'</a></div>';
			}else{

				$contents = DJlicense::checktVersions();
				$res= explode(';', $contents);
				$update_avaible=0;
				foreach($res as $r){
					$e = explode(',', $r); 									
					//$query = "SELECT manifest_cache FROM #__extensions WHERE element ='".$e[0]."' ";
					
					if(strstr($e[0],'com_')){
						$v_type= " AND type='component' ";
					}else if(strstr($e[0],'com_')){
						$v_type= " AND type='module' ";
					}else{
						$v_type= "";
					}
					$query = "SELECT manifest_cache FROM #__extensions WHERE element ='".$e[0]."' ".$v_type;
					
					
					
					$db->setQuery($query);
					$mc = json_decode($db->loadResult());
					$v = null;
					if(isset($mc)) $v = $mc->version; else $v = '';
					if($v!=''){
						if(version_compare($v,$e[1],'<')){
							$update_avaible = 1;
							break;
						}
					}
				}
				if($update_avaible){
					$update .= '<div style="text-align:left;margin-bottom:5px;"><img style="margin:-3px 0 -5px 0 !important;padding:0px;" src="'.JURI::base().'/templates/hathor/images/admin/publish_r.png" /> '.JText::_('COM_DJCATALOG2_DJLIC_NEW_VERSION_AVAILABLE').' <a style="font-weight:bold;" class="modal" rel="{handler: \'iframe\', size: {x: 600, y: 450},onClose:function(){window.parent.document.location.reload(true);}}" href="index.php?option='.$ext.'&task=license.update_list&tmpl=component">'.JText::_('COM_DJCATALOG2_DJLIC_UPDATE').'</a></div>';
				}else{
					$update .= '<div style="text-align:left;margin-bottom:5px;"><img style="margin:-3px 0 -5px 0 !important;padding:0px;" src="'.JURI::base().'/templates/hathor/images/admin/tick.png" /> '.JText::_('COM_DJCATALOG2_DJLIC_YOU_HAVE_LATEST_VERSION').' <a style="font-weight:bold;" class="modal" rel="{handler: \'iframe\', size: {x: 600, y: 450},onClose:function(){window.parent.document.location.reload(true);}}" href="index.php?option='.$ext.'&task=license.update_list&tmpl=component">'.JText::_('COM_DJCATALOG2_DJLIC_UPDATE').'</a></div>';	
				}
				
			}

			$update .= '';
			$update .= '</div>';
			return $update;			
		}
	}
	
	function checkSubscription($license){
		
		$ch = curl_init();		
		$ext = JRequest::getString('option', '');
		
			$db =  JFactory::getDBO();
			$query = "SELECT manifest_cache FROM #__extensions WHERE element ='".$ext."' ";
			$db->setQuery($query);
			$mc = json_decode($db->loadResult());
			$version = $mc->version;

		
		curl_setopt($ch, CURLOPT_URL,'http://dj-extensions.com/index.php?option=com_djsubscriptions&view=checkDomain&license='.$license.'&ext='.$ext.'&v='.$version.'');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);		
		$u = JFactory::getURI();
		curl_setopt ($ch, CURLOPT_REFERER, $u->getHost());
	
		
		if(!curl_errno($ch))
		{
			$contents = curl_exec ($ch);
		}
		
		curl_close ($ch);
		$res= explode(';', $contents);		
		
		return $res;
	}
	
	function checktVersions(){
		
		$ext = JRequest::getString('option', '');
		$config = JFactory::getConfig();
					
		$license_file = JPATH_BASE."/components/".JRequest::getVar('option')."/license_".$config->getValue('config.secret').".txt";
		if(JFile::exists($license_file)){
			$fh = fopen($license_file, 'r');
			$license = fgets($fh);	
			fclose($fh);
		}else{
			return JText::_('COM_DJCATALOG2_DJLIC_WRONG_LICENSE');
		}
		
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,'http://dj-extensions.com/index.php?option=com_djsubscriptions&view=checkVersions&license='.$license.'&ext='.$ext);		
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);		
			$u = JFactory::getURI();				
			curl_setopt ($ch, CURLOPT_REFERER, $u->getHost());
			if(!curl_errno($ch))
			{
				$contents = curl_exec ($ch); 
			}
			curl_close ($ch);
		
		return $contents;
	}
	
	function getProductName(){
		$ext = JRequest::getString('option', '');
		$db= JFactory::getDBO();			
		//$query = "SELECT name FROM #__extensions WHERE `element` = '".$ext."' LIMIT 1 ";
		$query = "SELECT name FROM #__extensions WHERE `element` = '".$ext."' AND type='component' LIMIT 1 ";
		$db->setQuery($query);
		$name = $db->loadResult();		
		return $name;
	}
}