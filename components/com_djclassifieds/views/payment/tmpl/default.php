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
JHTML::_('behavior.Mootools' );
JHTML::_('behavior.formvalidation');
JHTML::_('behavior.modal');
$par = &JComponentHelper::getParams( 'com_djclassifieds' );
?>
<div id="dj-classifieds">
	<table cellpadding="0" cellspacing="0" width="98%" border="0" class="paymentdetails first">
		<tr>
			<td class="td_title">
				<h2><?php echo JText::_('COM_DJCLASSIFIEDS_PAYMENT_DETAILS');?></h2>
			</td>
		</tr>
		<tr>
			<td class="td_pdetails">
				<?php 
					$p_count =0;
					$p_total=0;
					if(strstr($this->item->pay_type, 'cat')){
						$c_price = $this->item->c_price/100;
						echo '<div class="pd_row"><span>'.JText::_('COM_DJCLASSIFIEDS_CATEGORY').'</span><span class="price">'.$c_price.' '.$par->get('unit_price','').'</span></div>';
						$p_total+=$c_price;
						$p_count++;
					}													
					if(strstr($this->item->pay_type, 'duration')){
						echo '<div class="pd_row"><span>'.JText::_('COM_DJCLASSIFIEDS_DURATION').' '.$this->item->exp_days.' ';
							if($this->item->exp_days==1){
								echo JText::_('COM_DJCLASSIFIEDS_DAY');
							}else{
								echo JText::_('COM_DJCLASSIFIEDS_DAYS');
							}
						echo '</span><span class="price">'.$this->duration->price.' '.$par->get('unit_price','').'</span></div>';
						$p_total+=$this->duration->price;
						$p_count++;			
					}

					foreach($this->promotions as $prom){
						if(strstr($this->item->pay_type, $prom->name)){
							echo '<div class="pd_row"><span>'.JText::_($prom->label).'</span>';
							echo '<span class="price">'.$prom->price.' '.$par->get('unit_price','').'</span></div>';
							$p_total+=$prom->price;
							$p_count++;			
						}	
					}
					if($p_count>1){
						echo '<div class="pd_row_total"><span>'.JText::_('COM_DJCLASSIFIEDS_TOTAL').'</span><span class="price">'.$p_total.' '.$par->get('unit_price','').'</span></div>';
					}
				?>
			</td>
		</tr>			
	</table>
	<table cellpadding="0" cellspacing="0" width="98%" border="0" class="paymentdetails">
		<tr>
			<td class="td_title">
				<h2><?php echo JText::_("COM_DJCLASSIFIEDS_PAYMENT_METHODS"); ?></h2>
			</td>
		</tr>
		<tr>
			<td class="table_payment">
				<table cellpadding="0" cellspacing="0" width="100%" border="0">
					<?php
	
						$i = 0;					
						foreach($this->PaymentMethodDetails AS $pminfo)
						{
							if($pminfo==''){
								continue;
							}
							$paymentLogoPath = JURI::root()."plugins/djclassifiedspayment/".$this->plugin_info[$i]->name."/images/".$pminfo["logo"];
							?>
								<tr>
									<td class="payment_td">
										<?php echo $pminfo; ?>
									</td>
								</tr>
							<?php
							$i++;
						}
					?>
				</table>
			</td>
		</tr>
	</table>
</div>