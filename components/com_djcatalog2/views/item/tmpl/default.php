<?php
/**
 * @version $Id: default.php 72 2012-03-06 13:25:31Z michal $
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

defined ('_JEXEC') or die('Restricted access'); ?>

<?php if ($this->params->get( 'show_page_heading', 1) /*&& ($this->params->get( 'page_heading') != @$this->item->name)*/) : ?>
<h1 class="componentheading<?php echo $this->params->get( 'pageclass_sfx' ) ?>">
	<?php echo $this->escape($this->params->get('page_heading')); ?>
</h1>
<?php endif; ?>

<div id="djcatalog" class="djc_clearfix djc_item<?php echo $this->params->get( 'pageclass_sfx' ).' djc_theme_'.$this->params->get('theme','default'); if ($this->item->featured == 1) echo ' featured_item'; ?>">
	<?php if($this->item->event->beforeDJCatalog2DisplayContent) { ?>
	<div class="djc_pre_content">
			<?php echo $this->item->event->beforeDJCatalog2DisplayContent; ?>
	</div>
	<?php } ?>
	<?php if ($this->item->images = DJCatalog2ImageHelper::getImages('item',$this->item->id)) {
		echo $this->loadTemplate('images'); 
	} ?>
	<h2 class="djc_title">
	<?php if ($this->item->featured == 1) { 
		echo '<img class="djc_featured_image" alt="'.JText::_('COM_DJCATALOG2_FEATURED_ITEM').'" src="'.DJCatalog2ThemeHelper::getThemeImage('featured.png').'" />';
	}?>
	<?php echo $this->item->name; ?></h2>
	<?php if($this->item->event->afterDJCatalog2DisplayTitle) { ?>
		<div class="djc_post_title">
			<?php echo $this->item->event->afterDJCatalog2DisplayTitle; ?>
		</div>
	<?php } ?>
    <div class="djc_description">
		<?php if ($this->params->get('show_category_name_item')) { ?>
			<div class="djc_category_info">
			 <?php 
				if ($this->params->get('show_category_name_item') == 2) {
		        	echo JText::_('COM_DJCATALOG2_CATEGORY').': '?><span><?php echo $this->item->category; ?></span> 
				<?php }
				else {
					echo JText::_('COM_DJCATALOG2_CATEGORY').': ';?><a href="<?php echo JRoute::_(DJCatalogHelperRoute::getCategoryRoute($this->item->catslug)) ;?>"><span><?php echo $this->item->category; ?></span></a> 
				<?php } ?>
		    </div>
		<?php } ?>
		<?php if ($this->params->get('show_producer_name_item') > 0 && $this->item->prod_pub == '1' && $this->item->producer) { ?>
			<div class="djc_producer_info">
        		<?php 
					if ($this->params->get('show_producer_name_item') == 2) {
            			echo JText::_('COM_DJCATALOG2_PRODUCER').': '; ?><span><?php echo $this->item->producer;?></span>
					<?php }
					else if(($this->params->get('show_producer_name_item') == 3)) {
						echo JText::_('COM_DJCATALOG2_PRODUCER').': ';?><a class="modal" rel="{handler: 'iframe', size: {x: 800, y: 450}}" href="<?php echo JRoute::_(DJCatalogHelperRoute::getProducerRoute($this->item->prodslug).'&tmpl=component'); ?>"><span><?php echo $this->item->producer; ?></span></a> 
					<?php }
					else {
						echo JText::_('COM_DJCATALOG2_PRODUCER').': ';?><a href="<?php echo JRoute::_(DJCatalogHelperRoute::getProducerRoute($this->item->prodslug)); ?>"><span><?php echo $this->item->producer; ?></span></a>
					<?php } ?>
        	</div>
			<?php } ?>
        	<?php 
				if ($this->params->get('show_price_item') == 2 || ( $this->params->get('show_price_item') == 1 && $this->item->price > 0.0)) { 
					?>
		        	<div class="djc_price">
		        		<?php echo JText::_('COM_DJCATALOG2_PRICE').': ';?><span><?php echo DJCatalog2HtmlHelper::formatPrice($this->item->price, $this->params); ?></span>
		        	</div>
			<?php } ?>
            <div class="djc_fulltext">
                <?php echo JHTML::_('content.prepare', $this->item->description); ?>
            </div>
            <?php if($this->item->event->afterDJCatalog2DisplayContent) { ?>
				<div class="djc_post_content">
					<?php echo $this->item->event->afterDJCatalog2DisplayContent; ?>
				</div>
			<?php } ?>
            <?php if (isset($this->item->tabs)) { ?>
            	<div class="djc_clear"></div>
            	<div class="djc_tabs">
            		<?php echo JHTML::_('content.prepare', $this->item->tabs); ?>
            	</div>
            <?php } ?>
            <?php if ($this->item->files = DJCatalog2FileHelper::getFiles('item',$this->item->id)) {
				echo $this->loadTemplate('files');
			} ?>
			<?php if ($this->relateditems && $this->params->get('related_items_count',2) > 0) {
				echo $this->loadTemplate('relateditems');
			} ?>
        </div>
	<?php 
	if ($this->params->get('show_footer')) echo DJCATFOOTER;
	?>
</div>