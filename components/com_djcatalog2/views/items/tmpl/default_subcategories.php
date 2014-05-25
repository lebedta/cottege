<?php
/**
 * @version $Id: default_subcategories.php 28 2011-10-11 11:54:13Z michal $
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

$k = 0; 
$i = 1; 
$col_count = $this->params->get('category_columns',2);
$col_width = ((100/$col_count)-0.01);
foreach($this->subcategories as $item) {
	$subcategory = $this->categories->get($item->id);
	if ($subcategory->published != 1) { 
		//continue; 
	}
	$newrow_open = $newrow_close = false;
	if ($k % $col_count == 0) $newrow_open = true;
	if (($k+1) % $col_count == 0 || count($this->subcategories) <= $k+1) $newrow_close = true;
        
	$rowClassName = 'djc_clearfix djc_subcategory_row';
	if ($k == 0) $rowClassName .= '_first';
	if (count($this->subcategories) <= ($k + $col_count)) $rowClassName .= '_last';
	
	$colClassName ='djc_subcategory_col';
	if ($k % $col_count == 0) { $colClassName .= '_first'; }
	else if (($k+1) % $col_count == 0) { $colClassName .= '_last'; }
	else {$colClassName .= '_'.($k % $col_count);}
	
	if ($newrow_open) { $i = 1 - $i; ?><div class="<?php echo $rowClassName.'_'.$i; ?>"><?php }
	$k++;
?>
<div class="djc_subcategory <?php echo $colClassName; ?>" style="width:<?php echo $col_width; ?>%">
	<div class="djc_subcategory_bg">
		<div class="djc_subcategory_in djc_clearfix">
			<?php if ($subcategory->item_image) { ?>
	        	<div class="djc_image">
	        		<?php if ($this->params->get('image_link_subcategory')) { ?>
						<a rel="lightbox-djsubcategory" title="<?php echo $subcategory->image_caption; ?>" href="<?php echo DJCatalog2ImageHelper::getImageUrl($subcategory->item_image,'fullscreen'); ?>"><img alt="<?php echo $subcategory->image_caption; ?>" src="<?php echo DJCatalog2ImageHelper::getImageUrl($subcategory->item_image,'medium'); ?>"/></a>
	        		<?php } else { ?>
	        			<a href="<?php echo JRoute::_(DJCatalogHelperRoute::getCategoryRoute($subcategory->catslug));?>"><img alt="<?php echo $subcategory->image_caption; ?>" src="<?php echo DJCatalog2ImageHelper::getImageUrl($subcategory->item_image,'medium'); ?>"/></a>
	        		<?php } ?>
				</div>
			<?php } ?>
			<div class="djc_title">
				<h3><a href="<?php echo JRoute::_(DJCatalogHelperRoute::getCategoryRoute($subcategory->catslug));?>"><?php echo $subcategory->name; ?></a></h3>
			</div>
			<?php if ($this->params->get('category_show_intro')) {?>
			<div class="djc_description">
				<?php if ($this->params->get('category_intro_length') > 0 && $this->params->get('category_intro_trunc') ) {
						?><p><?php echo DJCatalog2HtmlHelper::trimText($subcategory->description, $this->params->get('category_intro_length'));?></p><?php
					}
					else {
						echo $subcategory->description; 
					}
				?>
			</div>
			<?php } ?>
			<?php if ($this->params->get('showreadmore_subcategory')) { ?>
				<div class="clear"></div>
				<div class="djc_readon">
					<a href="<?php echo JRoute::_(DJCatalogHelperRoute::getCategoryRoute($subcategory->catslug)); ?>" class="readmore"><?php echo JText::_('COM_DJCATALOG2_BROWSE'); ?></a>
				</div>
			<?php } ?>
		</div>
	</div>
</div>
<?php if ($newrow_close) { ?></div><?php } ?>
<?php } ?>