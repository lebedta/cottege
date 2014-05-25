<?php
/**
* @version $Id: addthis.php 30 2011-10-14 10:41:48Z michal $
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

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.plugin.plugin');

class plgDJCatalog2AddThis extends JPlugin {
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->_params = $this->params;
		$this->_pub_id                      = $this->_params->get('pub_id','');
		$this->_button_style                = $this->_params->get('button_style', 'sm-plus');
		$this->_custom_url                  = $this->_params->get('custom_url');
		$this->_addthis_button_language     = $this->_params->get('addthis_button_language','en');
		$this->_addthis_brand               = $this->_params->get('addthis_brand','');
		$this->_addthis_header_color        = $this->_params->get('addthis_header_color','');
		$this->_addthis_header_background   = $this->_params->get('addthis_header_background','');
		$this->_addthis_options             = $this->_params->get('addthis_options','');
		$this->_addthis_offset_top          = $this->_params->get('addthis_offset_top','');
		$this->_addthis_offset_left         = $this->_params->get('addthis_offset_left','');
		$this->_addthis_hover_delay         = $this->_params->get('addthis_hover_delay');
		$this->_addthis_hide_embed          = $this->_params->get('addthis_hide_embed');
		$this->_addthis_language            = $this->_params->get('addthis_language','');
		$this->_alignment                   = $this->_params->get('alignment','right');
	}

	/**
	 * onAfterDJCatalogDisplayContent
	 *
	 */
	function onAfterDJCatalog2DisplayContent(&$item, &$params, $limitstart)
	{

		$outputValue = "<div style='float:" . $this->_alignment . "'>\r\n";

		$outputValue .="<!-- AddThis Button BEGIN -->\r\n";

		$outputValue .= "<script type='text/javascript'>\r\n";

		if (trim($this->_pub_id) !== "Your Publisher ID" && trim($this->_pub_id) !== "")
		{
			$outputValue .= "var addthis_pub = '" .trim($this->_pub_id). "';\r\n";
		}
		if (trim($this->_addthis_brand) != "")
		{
			$outputValue .= "var addthis_brand = '".trim($this->_addthis_brand)."';\r\n";
		}
		if (trim($this->_addthis_header_color) != "")
		{
			$outputValue .= "var addthis_header_color = '".trim($this->_addthis_header_color)."';\r\n";
		}
		if (trim($this->_addthis_header_background) != "")
		{
			$outputValue .= "var addthis_header_background = '".trim($this->_addthis_header_background)."';\r\n";
		}
		if (trim($this->_addthis_options) != "")
		{
			$outputValue .= "var addthis_options = '".trim($this->_addthis_options)."';\r\n";
		}
		if (intval(trim($this->_addthis_offset_top)) != 0)
		{
			$outputValue .= "var addthis_offset_top = ".$this->_addthis_offset_top.";\r\n";
		}
		if (intval(trim($this->_addthis_offset_left)) != 0)
		{
			$outputValue .= "var addthis_offset_left = ".$this->_addthis_offset_left.";\r\n";
		}
		if (intval(trim($this->_addthis_hover_delay)) > 0)
		{
			$outputValue .= "var addthis_hover_delay = ".$this->_addthis_hover_delay.";\r\n";
		}
		if (trim($this->_addthis_language) != "" )
		{
			$outputValue .= "var addthis_language = '".$this->_addthis_language."';\r\n";
		}
		if (trim($this->_addthis_hide_embed) == '0')
		{
			$outputValue .= "var addthis_hide_embed = false;\r\n";
		}

		$outputValue .= "</script>\r\n";

		$outputValue .= "<a  href='http://www.addthis.com/bookmark.php?v=20' onmouseover=\"return addthis_open(this, '', '" . urldecode($this->getArticleUrl($item)) . "', '" . $item->name . "'); \"   onmouseout='addthis_close();' onclick='return addthis_sendto();'>";

		$outputValue .= "<img src='";

		if (trim($this->_button_style === "custom"))
		{
			if (trim($this->_custom_url) == '')
			{
				$outputValue .= "http://s7.addthis.com/static/btn/" .  $this->getButtonImage('lg-share',$this->_addthis_button_language);
			}
			else $outputValue .= $this->_custom_url;
		}
		else
		{
			$outputValue .= "http://s7.addthis.com/static/btn/" .  $this->getButtonImage($this->_button_style,$this->_addthis_button_language);
		}
		$outputValue .= "' border='0' alt='AddThis Social Bookmark Button' />";
		$outputValue .= "</a>\r\n";

		$outputValue .= "<script type='text/javascript' src='http://s7.addthis.com/js/200/addthis_widget.js'></script>\r\n";

		$outputValue .= "<!-- AddThis Button END -->";

		$outputValue .= "</div>\r\n";

		//Regular expression for finding the custom tag which disables the addthisbutton in the article.
		$switchregex = "#{addthis (on|off)}#s";

		//Ensure the custom tag is not present in the article text.
		if (!(strpos($item->description, '{addthis off}') === false ))
		{
			//Removing the custom tag from the final output.
			$item->description = preg_replace($switchregex, '', $item->description);
			return false;
		}
		else {
			return $outputValue;
		}
		
	}

	/**
	 * getArticleUrl
	 *
	 * Get the static url for the article
	 *
	 * @param object $item - Joomla article object
	 **/
	function getArticleUrl(&$item)
	{
		if (!is_null($item))
		{
			require_once( JPATH_SITE . DS . 'components' . DS . 'com_djcatalog2' . DS . 'helpers' . DS . 'route.php');

			$uri = JURI::getInstance();
			$base = $uri->toString(array('scheme', 'host', 'port'));
			$url = JRoute::_(DJCatalogHelperRoute::getItemRoute($item->slug, $item->catslug));
			return JRoute::_($base . $url, true, 0);
		}
	}

	/**
	 * getButtonImage
	 *
	 * This is used for preparing the image button name.
	 *
	 * @param string $name - Button style of addthis button selected.
	 * @param string $language - The language selected for addthis button.
	 */
	function getButtonImage($name, $language)
	{
		if ($name == "sm-plus")
		{
			$buttonImage = $name . '.gif';
		}
		elseif ($language != 'en')
		{
			if ($name == 'lg-share' || $name == 'lg-bookmark' || $name == 'lg-addthis')
			{
				$buttonImage = 'lg-share-' . $language . '.gif';
			}
			elseif($name == 'sm-share' || $name == 'sm-bookmark')
			{
				$buttonImage = 'sm-share-' . $language . '.gif';
			}
		}
		else
		{
			$buttonImage = $name . '-' . $language . '.gif';
		}

		return $buttonImage;
	}

}

?>