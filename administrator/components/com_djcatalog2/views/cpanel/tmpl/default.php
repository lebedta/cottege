<?php 
/**
* @version 2.12
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

defined('_JEXEC') or die('Restricted access'); ?>

<form action="index.php" method="post" name="adminForm">
<table class="adminform">
	<tr>
		<td width="55%" valign="top">
			<div id="cpanel">
			<div style="float:left;">
				<div class="icon">
					<a href="index.php?option=com_djcatalog2&amp;view=categories">
						<?php echo JHTML::_('image.administrator', 'icon-48-category.png', '/components/com_djcatalog2/images/', null, null, JText::_('COM_DJCATALOG2_CATEGORIES') ); ?>
						<span><?php echo JText::_('COM_DJCATALOG2_CATEGORIES'); ?></span>
					</a>
				</div>
			</div>
			<div style="float:left;">
				<div class="icon">
					<a href="index.php?option=com_djcatalog2&amp;view=producers">
						<?php echo JHTML::_('image.administrator', 'icon-48-producer.png', '/components/com_djcatalog2/images/', null, null, JText::_('COM_DJCATALOG2_PRODUCERS') ); ?>
						<span><?php echo JText::_('COM_DJCATALOG2_PRODUCERS'); ?></span>
					</a>
				</div>
			</div>
			<div style="float:left;">
				<div class="icon">
					<a href="index.php?option=com_djcatalog2&amp;view=items">
						<?php echo JHTML::_('image.administrator', 'icon-48-item.png', '/components/com_djcatalog2/images/', null, null, JText::_('COM_DJCATALOG2_ITEMS') ); ?>
						<span><?php echo JText::_('COM_DJCATALOG2_ITEMS'); ?></span>
					</a>
				</div>
			</div>
			<div style="float:left;">
				<div class="icon">
					<a href="index.php?option=com_djcatalog2&amp;task=category.add">
						<?php echo JHTML::_('image.administrator', 'icon-48-add.png', '/components/com_djcatalog2/images/', null, null, JText::_('COM_DJCATALOG2_NEW_CATEGORY') ); ?>
						<span><?php echo JText::_('COM_DJCATALOG2_NEW_CATEGORY'); ?></span>
					</a>
				</div>
			</div>
			<div style="float:left;">
				<div class="icon">
					<a href="index.php?option=com_djcatalog2&amp;task=producer.add">
						<?php echo JHTML::_('image.administrator', 'icon-48-add.png', '/components/com_djcatalog2/images/', null, null, JText::_('COM_DJCATALOG2_NEW_PRODUCER') ); ?>
						<span><?php echo JText::_('COM_DJCATALOG2_NEW_PRODUCER'); ?></span>
					</a>
				</div>
			</div>
			<div style="float:left;">
				<div class="icon">
					<a href="index.php?option=com_djcatalog2&amp;task=item.add">
						<?php echo JHTML::_('image.administrator', 'icon-48-add.png', '/components/com_djcatalog2/images/', null, null, JText::_('COM_DJCATALOG2_NEW_ITEM') ); ?>
						<span><?php echo JText::_('COM_DJCATALOG2_NEW_ITEM'); ?></span>
					</a>
				</div>
			</div>
			<div style="float:left;">
				<div class="icon">
					<a href="http://dj-extensions.com/extensions/dj-catalog2.html" target="_blank">
						<?php echo JHTML::_('image.administrator', 'icon-48-documentation.png', '/components/com_djcatalog2/images/', null, null, JText::_('COM_DJCATALOG2_DOCUMENTATION') ); ?>
						<span><?php echo JText::_('COM_DJCATALOG2_DOCUMENTATION'); ?></span>
					</a>
				</div>
			</div>
			<div style="float:right;">
				<?php echo DJLicense::getSubscription(); ?>
			</div>
			<div style="clear: both;" ></div>
		</div>
		</td>
	</tr>
</table>

<input type="hidden" name="option" value="com_djcatalog2" />
<input type="hidden" name="c" value="cpanel" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="view" value="cpanel" />
<input type="hidden" name="boxchecked" value="0" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>
<?php echo DJCATFOOTER; ?>