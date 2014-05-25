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
jimport( 'joomla.application.component.view' );

class DJClassifiedsViewPayment extends JView{


	function display( $tpl = null ){
		$app		= JFactory::getApplication();
		$params 	= $app->getParams();
		$user	=& JFactory::getUser();
		$id = JRequest::getInt("id","0");
		$layout	= JRequest::getVar('layout', '');
		$result = JRequest::getVar("result","");
		$action=JRequest::getVar('action','');
		//$ptype=JRequest::getVar('ptype','');
		$par = &JComponentHelper::getParams( 'com_djclassifieds' );
		$model = $this->getModel();		 						
		  
		if($layout == 'process'){
			JPluginHelper::importPlugin("djclassifiedspayment");
			$dispatcher =& JDispatcher::getInstance();
			$results = $dispatcher->trigger( 'onProcessPayment');
			print_r($results);die();
			$text = trim(implode("\n", $results));
			print_r($text);die();
			echo $text;
		}else{
						
			$item= $model->getUserItem($id);
			$duration='';
			if($par->get('durations_list',1)>0){
				$duration = $model->getDuration($item->exp_days);				
			}						
			$promotions = $model->getPromotions();
			
				$p_total=0;
				if(strstr($item->pay_type, 'cat')){
					$c_price = $item->c_price/100;
					$p_total+=$c_price;
				}													
				if(strstr($item->pay_type, 'duration')){
					$p_total+=$duration->price;			
				}

				foreach($promotions as $prom){
					if(strstr($item->pay_type, $prom->name)){
						$p_total+=$prom->price;
					}	
				}	
			
			$val["id"] = $id;
			$val["total"] = $p_total;
			$options = array( $val);
			JPluginHelper::importPlugin( 'djclassifiedspayment' );
			$dispatcher =& JDispatcher::getInstance();
			$PaymentMethodDetails = $dispatcher->trigger( 'onPaymentMethodList',$options);
			$plugin_info = JPluginHelper::getPlugin('djclassifiedspayment', $plugin=null);
			$PaymentMethodMessage="";
			$message="";

			if($action=="showresult")
			{
				$inv_arg["inv_id"] = $id;
				$arg = ($inv_arg);
				$PaymentMethodMessage = $dispatcher->trigger( 'onAfterFailedPayment',$arg);
				$message=$this->getPaymentPluginStatus($PaymentMethodMessage,$plugin_info,$ptype);
			}
											
			
			$this->assignRef("PaymentMethodDetails",$PaymentMethodDetails);
			$this->assignRef("plugin_info",$plugin_info);
			$this->assignRef("result",$result);
			$this->assignRef("item",$item);
			$this->assignRef('duration',$duration);
			$this->assignRef('promotions',$promotions);

		}


	//	$this->_prepareDocument();

		parent::display( $tpl );
	}

	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{
		$app		= JFactory::getApplication();
		$menus		= $app->getMenu();
		$pathway	= $app->getPathway();
		$title		= null;
		$params 	= $app->getParams();
		$menu		= $menus->getActive();

		// because the application sets a default page title, we need to get it
		// right from the menu item itself
		if (is_object($menu)) {
			$menu_params = new JRegistry;
			$menu_params->loadJSON($menu->params);
			if (!$menu_params->get('page_title')) {
				$params->set('page_title',	JText::_('COM_JEGROUPBUY_TITLE_PAYMENT_DETAILS_LIST'));
			}
		}
		else {
			$params->set('page_title',	JText::_('COM_JEGROUPBUY_TITLE_PAYMENT_DETAILS_LIST'));
		}

		$title = $params->get('page_title');
		if ($app->getCfg('sitename_pagetitles', 0)) {
			$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		}

		$this->document->setTitle($title);
	}
	function limit_characters ( $str, $n )
	{
		if ( strlen ( $str ) <= $n )
		{
			return $str;
		}
		else {
			return substr ( $str, 0, $n ) . '...';
		}
	}

	function getPaymentPluginStatus($PaymentMethodMessage,$plugin_infos,$ptype)
	{
		$i = 0;
		foreach($PaymentMethodMessage as $PaymentMethodMessages)
		{

			if($plugin_infos[$i]->name == $ptype)
			{
				return $PaymentMethodMessages;
			}
			$i++;
		}
	}
}
