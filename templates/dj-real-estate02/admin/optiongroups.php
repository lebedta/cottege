<?php

/*--------------------------------------------------------------
# Copyright (C) joomla-monster.com
# License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
# Website: http://www.joomla-monster.com
# Support: info@joomla-monster.com
---------------------------------------------------------------*/

/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * Form Field class for the Joomla Platform.
 * Supports a generic list of options.
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @since       11.1
 */
class JFormFieldOptiongroups extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'Optiongroups';
    protected $scriptPath = 'dj-real-estate02/admin';

	/**
	 * Method to get the field input markup for a generic list.
	 * Use the multiple attribute to enable multiselect.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput()
	{
		$app = JFactory::getApplication();
        
        $formControl = $this->formControl;
        if ($this->group) {
            $formControl .= '_'.$this->group;
        }
        
		$document = JFactory::getDocument();
		$document->addScript(JURI::base().'../templates/'.$this->scriptPath.'/js/optiongroups.js');
		$document->addScriptDeclaration('
			window.addEvent("domready",function(){
				var optionGroups_'.$this->id.' = new OptionGroups("'.$this->id.'", "'.$formControl.'", "'.$this->value.'");
			});
		');
		// Initialize variables.
		$html = array();
		$attr = '';

		// Initialize some field attributes.
		$attr .= $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';


		// Get the field options.
		$options = (array) $this->getOptions();

		$html[] = JHtml::_('select.genericlist', $options, $this->name, trim($attr), 'value', 'text', $this->value, $this->id);

		return implode($html);
	}

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   11.1
	 */
	protected function getOptions()
	{
		// Initialize variables.
		$options = array();

		foreach ($this->element->children() as $option)
		{

			// Only add <option /> elements.
			if ($option->getName() != 'group')
			{
				continue;
			}
            
            $optionValue = array((string)$option['value']);
            
            $optionChildren = $option->children();
            foreach($optionChildren as $child) {
                $optionValue[] = preg_replace('/[^a-zA-Z0-9_]/', '_',(string)$child['name']);
            }
			// Create a new option object based on the <option /> element.
			// $tmp = JHtml::_(
				// 'select.option', (string) $option['value'],
				// JText::alt(trim((string) $option), preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname)), 'value', 'text',
				// ((string) $option['disabled'] == 'true')
			// );
			$tmp = JHtml::_(
                'select.option', implode(';',$optionValue),
                JText::_((string)($option['label'])), 'value', 'text',
                ((string) $option['disabled'] == 'true')
            );

			// Set some option attributes.
			$tmp->class = (string) $option['class'];

			// Set some JavaScript option attributes.
			$tmp->onclick = (string) $option['onclick'];

			// Add the option object to the result set.
			$options[] = $tmp;
		}

		reset($options);

		return $options;
	}
}
