<?php
/**
 * @version		$Id: uninstall.sigpro.php 2725 2013-04-06 17:05:49Z joomlaworks $
 * @package		Simple Image Gallery Pro
 * @author		JoomlaWorks - http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2013 JoomlaWorks Ltd. All rights reserved.
 * @license		http://www.joomlaworks.net/license
 */

// no direct access
defined('_JEXEC') or die;

if (version_compare(JVERSION, '1.6.0', 'lt'))
{
	//Import libraries
	jimport('joomla.installer.installer');
	jimport('joomla.filesystem.file');

	//Load language file
	$lang = JFactory::getLanguage();
	$lang->load('com_sigpro', JPATH_ADMINISTRATOR);

	$status = new JObject();
	$status->modules = array();
	$status->plugins = array();

	$modules = $this->manifest->getElementByPath('modules');
	$plugins = $this->manifest->getElementByPath('plugins');

	if (is_a($modules, 'JSimpleXMLElement') && count($modules->children()))
	{
		foreach ($modules->children() as $module)
		{
			$mname = $module->attributes('module');
			$client = $module->attributes('client');
			$client_id = $client == 'administrator' ? 1 : 0;
			$db = JFactory::getDBO();
			$query = "SELECT `id` FROM `#__modules` WHERE module = ".$db->Quote($mname)." AND client_id = ".$client_id;
			$db->setQuery($query);
			$modules = $db->loadResultArray();
			if (count($modules))
			{
				foreach ($modules as $module)
				{
					$installer = new JInstaller;
					$result = $installer->uninstall('module', $module, 0);
				}
			}
			$status->modules[] = array(
				'name' => $mname,
				'client' => $client,
				'result' => $result
			);
		}
	}

	if (is_a($plugins, 'JSimpleXMLElement') && count($plugins->children()))
	{
		foreach ($plugins->children() as $plugin)
		{
			$pname = $plugin->attributes('plugin');
			$pgroup = $plugin->attributes('group');
			$db = JFactory::getDBO();
			$query = 'SELECT `id` FROM #__plugins WHERE element = '.$db->Quote($pname).' AND folder = '.$db->Quote($pgroup);
			$db->setQuery($query);
			$plugins = $db->loadResultArray();
			if (count($plugins))
			{
				foreach ($plugins as $plugin)
				{
					$installer = new JInstaller;
					$result = $installer->uninstall('plugin', $plugin, 0);
				}
			}
			$status->plugins[] = array(
				'name' => $pname,
				'group' => $pgroup,
				'result' => $result
			);
		}
	}
}
?>
<?php if (version_compare(JVERSION, '1.6.0', 'lt')): ?>
<h2><?php echo JText::_('COM_SIGPRO_REMOVAL_STATUS'); ?></h2>
<table class="adminlist table table-striped">
    <thead>
        <tr>
            <th class="title" colspan="2"><?php echo JText::_('COM_SIGPRO_EXTENSION'); ?></th>
            <th width="30%"><?php echo JText::_('COM_SIGPRO_STATUS'); ?></th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <td colspan="3"></td>
        </tr>
    </tfoot>
    <tbody>
        <tr class="row0">
            <td class="key" colspan="2"><?php echo 'Simple Image Gallery Pro '.JText::_('COM_SIGPRO_COMPONENT'); ?></td>
            <td><strong><?php echo JText::_('COM_SIGPRO_REMOVED'); ?></strong></td>
        </tr>
        <?php if (count($status->modules)): ?>
        <tr>
            <th><?php echo JText::_('COM_SIGPRO_MODULE'); ?></th>
            <th><?php echo JText::_('COM_SIGPRO_CLIENT'); ?></th>
            <th></th>
        </tr>
        <?php $rows = 0; foreach ($status->modules as $module): ?>
        <tr class="row<?php echo(++$rows % 2); ?>">
            <td class="key"><?php echo $module['name']; ?></td>
            <td class="key"><?php echo ucfirst($module['client']); ?></td>
            <td><strong><?php echo ($module['result'])?JText::_('COM_SIGPRO_REMOVED'):JText::_('COM_SIGPRO_NOT_REMOVED'); ?></strong></td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>

        <?php if (count($status->plugins)): ?>
        <tr>
            <th><?php echo JText::_('COM_SIGPRO_PLUGIN'); ?></th>
            <th><?php echo JText::_('COM_SIGPRO_GROUP'); ?></th>
            <th></th>
        </tr>
        <?php $rows = 0; foreach ($status->plugins as $plugin): ?>
        <tr class="row<?php echo(++$rows % 2); ?>">
            <td class="key"><?php echo ucfirst($plugin['name']); ?></td>
            <td class="key"><?php echo ucfirst($plugin['group']); ?></td>
            <td><strong><?php echo ($plugin['result'])?JText::_('COM_SIGPRO_REMOVED'):JText::_('COM_SIGPRO_NOT_REMOVED'); ?></strong></td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
<?php endif; ?>