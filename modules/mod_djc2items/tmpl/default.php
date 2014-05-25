<?php
/**
* @version $Id: default.php 65 2011-12-30 07:25:41Z michal $
* @package DJ-Catalog2
* @copyright Copyright (C) 2010 Blue Constant Media LTD, All rights reserved.
* @license http://www.gnu.org/licenses GNU/GPL
* @author url: http://design-joomla.eu
* @author email contact@design-joomla.eu
* @developer Michal Olczyk - michal.olczyk@design-joomla.eu
*
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

defined ('_JEXEC') or die('Restricted access');

?>
<div class="djc_items mod_djc_items">
<?php
$categories = Djc2Categories::getInstance(array('state'=>'1'));
foreach ($items as $item) { ?>
	<div class="djc_item mod_djc_item">
		<?php if ($item->item_image && $params->get('thumbnail')) { ?>
        	<div class="djc_image">
				<a href="<?php echo JRoute::_(DJCatalogHelperRoute::getItemRoute($item->slug, $item->catslug)); ?>"><img alt="<?php echo $item->image_caption; ?>" src="<?php echo DJCatalog2ImageHelper::getImageUrl($item->item_image,$params->get('thumbnail','medium')); ?>"/></a>
        	</div>
		<?php } ?>
		<div class="djc_title">
	        <h4><?php
	          echo (JHTML::link(JRoute::_(DJCatalogHelperRoute::getItemRoute($item->slug, $item->catslug)), $item->name));
	        ?></h4>
	    </div>
	    <div class="djc_description">
			<?php if ($params->get('show_category_name') > 0) { ?>
			<div class="djc_category_info">
            	<?php 
				if ($params->get('show_category_name') == 2) {
            		echo JText::_('MOD_DJC2ITEMS_CATEGORY').': '?>
            		<span><?php echo $item->category; ?></span> 
				<?php }
				else {
					echo JText::_('MOD_DJC2ITEMS_CATEGORY').': ';?>
					<a href="<?php echo JRoute::_(DJCatalogHelperRoute::getCategoryRoute($item->catslug)); ?>">
						<span><?php echo $item->category; ?></span>
					</a> 
				<?php } ?>
            </div>
			<?php } ?>
			<?php if ($params->get('show_producer_name') > 0 && $item->producer && $item->publish_producer) { ?>
			<div class="djc_producer_info">
				<?php 
				if ($params->get('show_producer_name') == 2) {
            		echo JText::_('MOD_DJC2ITEMS_PRODUCER').': '; ?>
            		<span><?php echo $item->producer;?></span>
				<?php }
				else if(($params->get('show_producer_name') == 3)) {
					echo JText::_('MOD_DJC2ITEMS_PRODUCER').': ';?>
					<a class="modal" rel="{handler: 'iframe', size: {x: 800, y: 600}}" href="<?php echo JRoute::_(DJCatalogHelperRoute::getProducerRoute($item->prodslug).'&tmpl=component'); ?>">
						<span><?php echo $item->producer; ?></span>
					</a> 
				<?php }
				else {
					echo JText::_('MOD_DJC2ITEMS_PRODUCER').': ';?>
					<a href="<?php echo JRoute::_(DJCatalogHelperRoute::getProducerRoute($item->prodslug)); ?>">
						<span><?php echo $item->producer; ?></span>
					</a> 
				<?php } ?>
            </div>
			<?php } ?>
            <?php 
				if ($item->price) { 
			?>
            <div class="djc_price">
            	<?php echo JText::_('MOD_DJC2ITEMS_PRICE').': ';?><span><?php echo $item->price; ?></span>
            </div>
			<?php } ?>
			<?php if ($params->get('items_show_intro')) {?>
			<div class="djc_introtext">
				<?php if ($params->get('items_intro_length') > 0 ) {
						?><p><?php echo DJCatalog2HtmlHelper::trimText($item->intro_desc, $params->get('items_intro_length'));?></p><?php
					}
					else {
						echo $item->intro_desc; 
					}
				?>
			</div>
			<?php } ?>
            </div>
             <div class="djc_clear"></div>
            <?php if ($params->get('showreadmore_item')) { ?>
				<div class="djc_readon">
					<a href="<?php echo JRoute::_(DJCatalogHelperRoute::getItemRoute($item->slug, $item->catslug)); ?>"><?php echo JText::sprintf('MOD_DJC2_ITEM_READMORE'); ?></a>
				</div>
			<?php } ?>
	</div>
	<?php } ?>
</div>
