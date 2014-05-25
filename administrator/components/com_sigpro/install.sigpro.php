<?php
/**
 * @version		$Id: install.sigpro.php 2835 2013-04-12 15:15:42Z lefteris.kavadas $
 * @package		Simple Image Gallery Pro
 * @author		JoomlaWorks - http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2013 JoomlaWorks Ltd. All rights reserved.
 * @license		http://www.joomlaworks.net/license
 */

// no direct access
defined('_JEXEC') or die;

if (version_compare(JVERSION, '1.6.0', 'lt'))
{
	// Import libraries
	jimport('joomla.installer.installer');
	jimport('joomla.filesystem.file');

	// Load language file
	$lang = JFactory::getLanguage();
	$lang->load('com_sigpro', JPATH_ADMINISTRATOR);

	// Set some variables for later usage
	$db = JFactory::getDBO();
	$status = new JObject();
	$status->modules = array();
	$status->plugins = array();
	$src = $this->parent->getPath('source');
	
	// Migrate plugin settings to component. Only for first time installs
	$db->setQuery("SELECT params FROM #__plugins WHERE folder = 'content' AND element = 'jw_sigpro'");
	$params = $db->loadResult();
	if($params && $params !='')
	{
		$db->setQuery("UPDATE #__components SET params = ".$db->quote($params)." WHERE `option` = 'com_sigpro'");
		$db->query();
			
		$db->setQuery("UPDATE #__plugins SET params = '' WHERE folder = 'content' AND element = 'jw_sigpro'");
		$db->query();
	}

	// Install modules
	$modules = $this->manifest->getElementByPath('modules');
	if (is_a($modules, 'JSimpleXMLElement') && count($modules->children()))
	{
		foreach ($modules->children() as $module)
		{
			$mname = $module->attributes('module');
			$client = $module->attributes('client');
			if (is_null($client))
			{
				$client = 'site';
			}
			($client == 'administrator') ? $path = $src.'/administrator/modules/'.$mname : $path = $src.'/modules/'.$mname;
			$installer = new JInstaller;
			$result = $installer->install($path);
			$status->modules[] = array(
				'name' => $mname,
				'client' => $client,
				'result' => $result
			);
		}
	}

	// Install plugins
	$plugins = $this->manifest->getElementByPath('plugins');
	if (is_a($plugins, 'JSimpleXMLElement') && count($plugins->children()))
	{

		foreach ($plugins->children() as $plugin)
		{
			$pname = $plugin->attributes('plugin');
			$pgroup = $plugin->attributes('group');
			$path = $src.'/plugins/'.$pgroup.'/'.$pname;
			$installer = new JInstaller;
			$result = $installer->install($path);
			$status->plugins[] = array(
				'name' => $pname,
				'group' => $pgroup,
				'result' => $result
			);
			$query = "UPDATE #__plugins SET published=1 WHERE element=".$db->Quote($pname)." AND folder=".$db->Quote($pgroup);
			$db->setQuery($query);
			$db->query();
		}
	}

}
?>
<?php if (version_compare(JVERSION, '1.6.0', 'lt')): ?>
<img src="components/com_sigpro/images/SimpleImageGalleryPro_300x88_24.png" alt="Simple Image Gallery Pro" align="right" />
<h2>Simple Image Gallery Pro <?php echo JText::_('COM_SIGPRO_INSTALLATION_STATUS'); ?></h2>
<table class="adminlist table table-striped">
    <thead>
        <tr>
            <th class="title" colspan="2"><?php echo JText::_('COM_SIGPRO_EXTENSION'); ?></th>
            <th width="30%"><?php echo JText::_('COM_SIGPRO_STATUS'); ?></th>
        </tr>
    </thead>
    <tbody>
        <tr class="row0">
            <td class="key" colspan="2"><?php echo 'Simple Image Gallery Pro '.JText::_('COM_SIGPRO_COMPONENT'); ?></td>
            <td><strong><?php echo JText::_('COM_SIGPRO_INSTALLED'); ?></strong></td>
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
            <td><strong><?php echo ($module['result'])?JText::_('COM_SIGPRO_INSTALLED'):JText::_('COM_SIGPRO_NOT_INSTALLED'); ?></strong></td>
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
            <td><strong><?php echo ($plugin['result'])?JText::_('COM_SIGPRO_INSTALLED'):JText::_('COM_SIGPRO_NOT_INSTALLED'); ?></strong></td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3"></td>
        </tr>
    </tfoot>
</table>
<?php endif; ?>