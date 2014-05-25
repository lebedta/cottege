<?php
/**
 * @version $Id: default.php 79 2012-07-31 11:10:30Z michal $
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

defined ('_JEXEC') or die('Restricted access');

?>

<?php if ($this->params->get( 'show_page_heading', 1) /*&& ($this->params->get( 'page_heading') != @$this->item->name)*/) : ?>
<h1 class="componentheading<?php echo $this->params->get( 'pageclass_sfx' ) ?>">
	<?php echo $this->escape($this->params->get('page_heading')); ?>
</h1>
<?php endif; ?>

<div id="djcatalog" class="djc_list<?php echo $this->params->get( 'pageclass_sfx' ).' djc_theme_'.$this->params->get('theme','default') ?>">
<?php if ($this->params->get('showcatdesc') && $this->item && $this->item->id > 0) { ?>
	<div class="djc_category djc_clearfix">
		<?php 
			if ($this->item->images = DJCatalog2ImageHelper::getImages('category',$this->item->id)) {
				echo $this->loadTemplate('images'); 
			} 
		?>
		<h2 class="djc_title"><?php echo $this->item->name; ?></h2>
		<div class="djc_description">
			<div class="djc_fulltext">
				<?php echo JHTML::_('content.prepare', $this->item->description); ?>
			</div>
			<?php if (isset($this->item->tabs)) { ?>
            	<div class="djc_clear"></div>
            	<div class="djc_tabs">
            		<?php echo $this->item->tabs; ?>
            	</div>
            <?php } ?>
		</div>
	</div>
<?php } ?>

<?php if ($this->params->get('showsubcategories') && $this->subcategories) { ?>
	<div class="djc_subcategories">
		<?php if ($this->params->get('showsubcategories_label')) { ?>
			<h2 class="djc_title"><?php echo JText::_('COM_DJCATALOG2_SUBCATEGORIES'); ?></h2>
		<?php } ?>
		<div class="djc_subcategories_grid djc_clearfix">
			<?php echo $this->loadTemplate('subcategories'); ?>
		</div>
	</div>
	<?php } ?>
<?php if (($this->params->get('product_catalogue') == '0' || count($this->items) > 0) && ($this->params->get('show_category_filter') > 0 || $this->params->get('show_producer_filter') > 0 || $this->params->get('show_search') > 0)) { ?>
	<div class="djc_filters djc_clearfix" id="tlb">
		<?php echo $this->loadTemplate('filters'); ?>
	</div>
<?php } ?>
<?php 
	if (count($this->items) > 0 && ($this->params->get('show_category_orderby') > 0 || $this->params->get('show_producer_orderby') > 0 || $this->params->get('show_name_orderby') > 0 || $this->params->get('show_price_orderby') > 0)) { ?>
	<div class="djc_order djc_clearfix">
		<?php echo $this->loadTemplate('order'); ?>
	</div>
<?php } ?>
<?php if (count($this->items) > 0){ ?>
	<?php 
	/*
	$switchLink = null;
	$switchLinkText = null;
	if ($this->params->get('list_layout','items') == 'items') {
		$switchLink = JRoute::_(DJCatalogHelperRoute::getCategoryRoute(JRequest::getVar('cid')).'&l=table');
		$switchLinkText = JText::_('COM_DJCATALOG2_TABLE_VIEW');
	} else {
		$switchLink = JRoute::_(DJCatalogHelperRoute::getCategoryRoute(JRequest::getVar('cid')).'&l=items');
		$switchLinkText = JText::_('COM_DJCATALOG2_LIST_VIEW');
	} ?>
	<a href="<?php echo $switchLink; ?>"><?php echo $switchLinkText ?></a>
	<?php */ ?>
	<div class="djc_items djc_clearfix">
		<?php echo $this->loadTemplate($this->params->get('list_layout','items')); ?>
	</div>
<?php } ?>
<div class="djc_pagination pagination djc_clearfix">
<?php
	echo $this->pagination->getPagesLinks();
?>
</div>
<?php 
	if ($this->params->get('show_footer')) echo DJCATFOOTER;
?>
</div>
