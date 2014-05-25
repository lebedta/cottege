<?php
/**
* @version		1.0
* @package		DJ Classifieds
* @subpackage	DJ Classifieds Payment Plugin
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
defined('_JEXEC') or die('Restricted access');
jimport('joomla.event.plugin');
$lang = & JFactory::getLanguage();
$lang->load('plg_djclassifiedspayment_djcfPayU',JPATH_ADMINISTRATOR);
class plgdjclassifiedspaymentdjcfPayU extends JPlugin
{
	function plgdjclassifiedspaymentdjcfPayU( &$subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage('plg_djcfPayU');
		$params["plugin_name"] = "djcfPayU";
		$params["icon"] = "payu_icon.jpg";
		$params["logo"] = "payu_overview.jpg";
		$params["description"] = JText::_("PLG_DJCFPAYU_PAYMENT_METHOD_DESC");
		$params["payment_method"] = JText::_("PLG_DJCFPAYU_PAYMENT_METHOD_NAME");		
		$params["pos_id"] = $this->params->get("pos_id");
		$params["pos_auth_key"] = $this->params->get("pos_auth_key");
		$params["md5_key"] = $this->params->get("md5_key");
		$params["md5_key2"] = $this->params->get("md5_key2");
		$this->params = $params;

	}
	
	function onPaymentMethodList($val)
	{
		$html ='';
		$user = JFactory::getUser();
		if($this->params["pos_id"]!='' && $this->params["pos_auth_key"]!=''){
			$paymentLogoPath = JURI::root()."plugins/djclassifiedspayment/".$this->params["plugin_name"]."/".$this->params["plugin_name"]."/images/".$this->params["logo"];
			$form_action = JRoute :: _("index.php?option=com_djclassifieds&task=processPayment&ptype=".$this->params["plugin_name"]."&pactiontype=process&id=".$val["id"], false);
			$html ='
			<form action="index.php" method="get" class="form-validate" id="djcf_payu" name="djcf_payu" >
					<input type="hidden" name="option" value="com_djclassifieds" />
					<input type="hidden" name="task" value="processPayment" />
					<input type="hidden" name="ptype" value="'.$this->params["plugin_name"].'" />
					<input type="hidden" name="pactiontype" value="process" />
					<input type="hidden" name="id" value="'.$val["id"].'" />
					<table cellpadding="5" cellspacing="0" width="100%" border="0">				
					<tr>';
						if($this->params["logo"] != ""){
					$html .='<td class="td1" width="160" align="center">
							<img src="'.$paymentLogoPath.'" title="'. $this->params["payment_method"].'"/>
						</td>';
						 }
						$html .='<td class="td2">
							<h2>PayU</h2>
							<p style="text-align:justify;">'.$this->params["description"].'</p>';
							if($user->id==0){
								$html .='<div class="email_box"><span>'.JText::_('JGLOBAL_EMAIL').':*</span> <input size="50" class="validate-email required" type="text" name="email" value=""></div>';
							}
						$html .='</td>
						<td class="td3" width="100" align="center">
							<input type="image" class="image_submit" border="0" src="'.JURI::root().'components/com_djclassifieds/assets/images/buynow.png" width="89" height="28" />
						</td>
					</tr>
				</table>
			</form>';
		}
		return $html;
	}	
	
	function onProcessPayment()
	{
		$ptype = JRequest::getVar('ptype','');
		$id = JRequest::getInt('id','0');
		$html="";

			
		if($ptype == $this->params["plugin_name"])
		{
			$action = JRequest::getVar('pactiontype','');
			switch ($action)
			{
				case "process" :
				$html = $this->process($id);
				break;
				case "notify" :
				$html = $this->_notify_url();
				break;
				case "paymentmessage" :
				$html = $this->_paymentsuccess();
				break;
				default :
				$html =  $this->process($id);
				break;
			}
		}
		return $html;
	}
	function _notify_url()
	{
		$db = JFactory::getDBO();
		$par = JComponentHelper::getParams( 'com_djclassifieds' );

		$user	= JFactory::getUser();
		$id	= JRequest::getInt('session_id','0');
		$app = JFactory::getApplication();
		$itemid = JRequest::getInt("Itemid","");	
		
		$payu_info = $_POST;				
		
		$server = 'www.platnosci.pl';
		$server_script = '/paygw/ISO/Payment/get';
		
		$PLATNOSCI_POS_ID = $this->params["pos_id"];
		$PLATNOSCI_KEY1 = $this->params["md5_key"];
		$PLATNOSCI_KEY2 = $this->params["md5_key2"];
		
		
		/*$fil = fopen('payu_data.txt', 'a');			
		fwrite($fil, "\n\n--------------------post_first-----------------\n");
		$post = $_POST;
		foreach ($post as $key => $value) {
				fwrite($fil, $key.' - '.$value."\n");
			}				
		fclose($fil);
		*/
		
		if(!isset($_POST['pos_id']) || !isset($_POST['session_id']) || !isset($_POST['ts']) || !isset($_POST['sig'])) die('ERROR: EMPTY PARAMETERS'); //-- brak wszystkich parametrow
		
		if ($_POST['pos_id'] != $PLATNOSCI_POS_ID) die('ERROR: WRONG POS ID');   //--- błędny numer POS
		
		$sig = md5( $_POST['pos_id'] . $_POST['session_id'] . $_POST['ts'] . $PLATNOSCI_KEY2);
		if ($_POST['sig'] != $sig) die('ERROR: WRONG SIGNATURE');   //--- błędny podpis
		
		$ts = time();
		$sig = md5( $PLATNOSCI_POS_ID . $_POST['session_id'] . $ts . $PLATNOSCI_KEY1);
		$parameters = "pos_id=" . $PLATNOSCI_POS_ID . "&session_id=" . $_POST['session_id'] . "&ts=" . $ts . "&sig=" . $sig;
		
		$fsocket = false;
		$curl = false;
		$result = false;
		
		if ( (PHP_VERSION >= 4.3) && ($fp = @fsockopen('ssl://' . $server, 443, $errno, $errstr, 30)) ) {
		 $fsocket = true;
		} elseif (function_exists('curl_exec')) {
		 $curl = true;
		}
		
		if ($fsocket == true) {
		 $header = 'POST ' . $server_script . ' HTTP/1.0' . "\r\n" .
		   'Host: ' . $server . "\r\n" .
		   'Content-Type: application/x-www-form-urlencoded' . "\r\n" .
		   'Content-Length: ' . strlen($parameters) . "\r\n" .
		   'Connection: close' . "\r\n\r\n";
		 @fputs($fp, $header . $parameters);
		 $platnosci_response = '';
		 while (!@feof($fp)) {
		  $res = @fgets($fp, 1024);
		  $platnosci_response .= $res;
		 }
		 @fclose($fp);
		  
		} elseif ($curl == true) {
		 $ch = curl_init();
		 curl_setopt($ch, CURLOPT_URL, "https://" . $server . $server_script);
		 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		 curl_setopt($ch, CURLOPT_HEADER, 0);
		 curl_setopt($ch, CURLOPT_TIMEOUT, 20);
		 curl_setopt($ch, CURLOPT_POST, 1);
		 curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
		 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		 $platnosci_response = curl_exec($ch);
		 curl_close($ch);
		} else {
		 die("ERROR: No connect method ...\n");
		}
		
		
		if (eregi("<trans>.*<pos_id>([0-9]*)</pos_id>.*<session_id>(.*)</session_id>.*<order_id>(.*)</order_id>.*<amount>([0-9]*)</amount>.*<status>([0-9]*)</status>.*<desc>(.*)</desc>.*<ts>([0-9]*)</ts>.*<sig>([a-z0-9]*)</sig>.*</trans>", $platnosci_response, $parts)){
			$result = $this->get_status($parts);
		}  
				
		
		if ( $result['code'] ) {  //--- rozpoznany status transakcji
		
		    $pos_id = $parts[1];
		    $session_id = $parts[2];
		    $order_id = $parts[3];
		    $amount = $parts[4];  //-- w groszach
		    $status = $parts[5];
		    $desc = $parts[6];
		    $ts = $parts[7];
		    $sig = $parts[8];
			
					/*$fil = fopen('payu_data.txt', 'a');

					foreach ($result as $key => $value) {
						fwrite($fil, $key.' - '.$value."\n");
					}*/
			
		    /* TODO: zmiana statusu transakcji w systemie Sklepu */
		
		    
		    if ( $result['code'] == '99' ) {			
	    		
				$query = "UPDATE #__djcf_payments SET status='Completed',transaction_id='".$ts."' "
						."WHERE item_id=".$id." AND method='djcfPayU'";
				$db->setQuery($query);
				$db->query();
				
				$query = "SELECT c.*  FROM #__djcf_items i, #__djcf_categories c "
						."WHERE i.cat_id=c.id AND i.id='".$id."' ";
				$db->setQuery($query);
				$cat = $db->loadObject();
				
				$pub=0;
				if(($cat->autopublish=='1') || ($cat->autopublish=='0' && $par->get('autopublish')=='1')){						
					$pub = 1;							 						
				}
		
				$query = "UPDATE #__djcf_items SET payed=1, pay_type='', published='".$pub."' "
						."WHERE id=".$id." ";					
				$db->setQuery($query);
				$db->query();
				
				
		            echo "OK";
		            exit;	    						
	    		
	       			// udalo sie zapisac dane wiec odsylamy OK			
		    } else if ( $result['code'] == '2' ) {
		    	//if ($this->model->set_status_platnosci($session_id,0)){
			         echo "OK";
			         exit;	    						
		    	//}
		    // transakcja anulowana mozemy również anulować zamowienie
		    } 
		    
		
		    // jezeli wszytskie operacje wykonane poprawnie wiec odsylamy ok
		    // w innym przypadku należy wygenerować błąd
		    // if ( wszystko_ok ) {
		        echo "OK";
		        exit;
		    // } else {
		    //
		    // }
		
		  
		} else {
		    /* TODO: obsługa powiadamiania o błędnych statusach transakcji*/
		    /*$fil = fopen('payu_data.txt', 'a');
		    fwrite($fil, "\n\n------------------------BLAD--------------\n");
		    fwrite($fil, "code=" . $result['code'] . " message=" . $result['message'] . "\n");
		    fwrite($fil, $platnosci_response . "\n\n");*/
		    // powiadomienie bedzie wysłane ponownie przez platnosci.pl
		    // ewentualnie dodajemy sobie jakis wpis do logow ...
		}		
		
			
				
		$message=JTExt::_('PLG_DJCFPAYU_AFTER_SUCCESSFULL_MSG');
		$redirect= 'index.php?option=com_djclassifieds&view=items&cid=0&Itemid='.$itemid;
		$app->redirect($redirect, $message);
		
	}

	function get_status($parts){
		$PLATNOSCI_POS_ID = $this->params["pos_id"];
		$PLATNOSCI_KEY1 = $this->params["md5_key"];
		$PLATNOSCI_KEY2 = $this->params["md5_key2"];
		
		
	  if ($parts[1] != $PLATNOSCI_POS_ID) return array('code' => false,'message' => 'błędny numer POS');  //--- bledny numer POS
	      $sig = md5($parts[1].$parts[2].$parts[3].$parts[5].$parts[4].$parts[6].$parts[7].$PLATNOSCI_KEY2);
	      if ($parts[8] != $sig) return array('code' => false,'message' => 'błędny podpis');  //--- bledny podpis
	      switch ($parts[5]) {
	          case 1: return array('code' => $parts[5], 'message' => 'nowa'); break;
	          case 2: return array('code' => $parts[5], 'message' => 'anulowana'); break;
	          case 3: return array('code' => $parts[5], 'message' => 'odrzucona'); break;
	          case 4: return array('code' => $parts[5], 'message' => 'rozpoczęta'); break;
	          case 5: return array('code' => $parts[5], 'message' => 'oczekuje na odbiór'); break;
	          case 6: return array('code' => $parts[5], 'message' => 'autoryzacja odmowna'); break;
	          case 7: return array('code' => $parts[5], 'message' => 'płatność odrzucona'); break;
	          case 99: return array('code' => $parts[5], 'message' => 'płatność odebrana - zakończona'); break;
	          case 888: return array('code' => $parts[5], 'message' => 'błędny status'); break;
	          default: return array('code' => false, 'message' => 'brak statusu'); break;
	  }
	
	}
	
	function process($id)
	{
		$db = JFactory::getDBO();
		$app = JFactory::getApplication();
		$Itemid = JRequest::getInt("Itemid",'0');
		$par = JComponentHelper::getParams( 'com_djclassifieds' );
		$user = JFactory::getUser();
		$ptype=JRequest::getVar('ptype');

		$query ="SELECT i.*, c.price as c_price FROM #__djcf_items i "
			   ."LEFT JOIN #__djcf_categories c ON c.id=i.cat_id "
			   ."WHERE i.id=".$id." LIMIT 1";
		$db->setQuery($query);
		$item = $db->loadObject();
		if(!isset($item)){
			$message = JText::_('COM_DJCLASSIFIEDS_WRONG_AD');
			$redirect="index.php?option=com_djclassifieds&view=items&cid=0";
		}
		
			$query = 'DELETE FROM #__djcf_payments WHERE item_id= "'.$id.'" ';
			$db->setQuery($query);
			$db->query();
			
			
			$query = 'INSERT INTO #__djcf_payments ( item_id,user_id,method,  status)' .
					' VALUES ( "'.$id.'" ,"'.$user->id.'","'.$ptype.'" ,"Start" )'
					;
			$db->setQuery($query);
			$db->query();
			
		
			$amount = 0;
			if(strstr($item->pay_type, 'cat')){
				$amount += ($item->c_price);
			}
			if(strstr($item->pay_type, 'duration')){			
				$query = "SELECT d.price FROM #__djcf_days d "
				."WHERE d.days=".$item->exp_days;
				$db->setQuery($query);
				$amount += ($db->loadResult() * 100);
			}
			
			$query = "SELECT p.* FROM #__djcf_promotions p "
				."WHERE p.published=1 ORDER BY p.id ";
			$db->setQuery($query);
			$promotions=$db->loadObjectList();
			foreach($promotions as $prom){
				if(strstr($item->pay_type, $prom->name)){	
					$amount += ($prom->price * 100); 
				}	
			}
			
			
							
		$itemname = $item->name;
		$payuURL = 'https://www.platnosci.pl/paygw/ISO/NewPayment';
		/*if ($this->params["testmode"]=="1"){
			$itemname = 'TEST_OK';
			$payuURL = 'https://sandbox.payu.pl/index.php';
			//$itemname = 'TEST_ERR';
		}*/
		
		//$crc_hash = md5($item->id.'|'.$this->params["p24_id"].'|'.$amount.'|'.$this->params["p24_crc"]);		
		if($user->id>0){
			$email = $user->email;
		}else{
			$email = JRequest::getVar('email','');
		}
		echo JText::_('PLG_DJCFPAYU_REDIRECTING_PLEASE_WAIT');
			
			$form ='<form id="payuform" action="'.$payuURL.'" method="post">';			
				$form .='<br /><input type="hidden" name="first_name" value="">';
				$form .='<br /><input type="hidden" name="last_name" value="">';
				$form .='<br /><input type="hidden" name="email" value="'.$email.'">';
				$form .='<br /><input type="hidden" name="pos_id" value="'.$this->params["pos_id"].'">';
				$form .='<br /><input type="hidden" name="pos_auth_key" value="'.$this->params["pos_auth_key"].'">';						
				$form .='<br /><input type="hidden" id="session_id" name="session_id" value="'.$item->id.'">';									
				$form .='<br /><input type="hidden" name="amount" value="'.$amount.'">';
				$form .='<br /><input type="hidden" name="desc" value="'.$itemname.'">';
				$form .='<br /><input type="hidden" name="client_ip" value="'.$_SERVER['REMOTE_ADDR'].'">';									
				$form .='<br /><input type="hidden" name="js" value="1">';
			$form .='</form>';
		echo $form;
		
	?>
		<script type="text/javascript">
			callpayment()
			function callpayment(){
				var id = document.getElementById('session_id').value ;
				if ( id > 0 && id != '' ) {
					document.getElementById('payuform').submit();
				}
			}
		</script>
	<?php
	die();
	}


}

?>