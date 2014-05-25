<?php

/**
 * @version		$Id: modules.php 10822 2008-08-27 17:16:00Z tcp $
 * @package		Joomla
 * @copyright	Copyright (C) 2005 - 2007 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// no direct access
defined('_JEXEC') or die;

function modChrome_jmmodule($module, &$params, &$attribs) {
?>
<div class="jm-module <?php echo $params->get('moduleclass_sfx'); ?>" id="Mod<?php echo $module->id; ?>">
	<div class="jm-module-in">
		<?php if (!$module->showtitle == 0) : ?>
		<div class="jm-title-wrap">
	   		<h3 class="jm-title"><?php echo $module->title; ?></h3>
	   	</div>
	    <?php endif; ?>
	    <div class="jm-module-content">
	    	<?php echo $module->content; ?>
	    </div>
	</div>
</div>
<?php 
}
?>