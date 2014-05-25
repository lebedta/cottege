<?php
/**
 * @version $Id: theme.php 62 2011-11-29 08:52:56Z michal $
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

defined('_JEXEC') or die('Restricted access');

require_once(JPATH_BASE.DS.'components'.DS.'com_djcatalog2'.DS.'defines.djcatalog2.php');

class DJCatalog2ThemeHelper {
	static $themeSet = null;
	public static function setThemeAssets() {
		if (!isset(self::$themeSet)) {
			$app = JFactory::getApplication();
			$document = JFactory::getDocument();
			$params = $app->getParams('com_djcatalog2');
			$theme = $params->get('theme','default');
			
			JHTML::_('behavior.mootools');
			JHTML::_('behavior.modal');
			
			$theme_css_url = JURI::base().'components/com_djcatalog2/themes/default/css/theme.css';
			$theme_js_url = JURI::base().'components/com_djcatalog2/themes/default/js/theme.js';
			$theme_class_file = DJCATCOMPONENTPATH.DS.'themes'.DS.'default'.DS.'theme.php';
			$theme_class = 'Djcatalog2Theme';
			
			if (JFile::exists( DJCATCOMPONENTPATH.DS.'themes'.DS.$theme.DS.'css'.DS.'theme.css' )) {
				$theme_css_url = JURI::base().'components/com_djcatalog2/themes/'.$theme.'/css/theme.css';
			} 
			if (JFile::exists( DJCATCOMPONENTPATH.DS.'themes'.DS.$theme.DS.'js'.DS.'theme.js' )) {
				$theme_js_url = JURI::base().'components/com_djcatalog2/themes/'.$theme.'/js/theme.js';
			}
			if (JFile::exists( DJCATCOMPONENTPATH.DS.'themes'.DS.$theme.DS.'theme.php' )) {
				$theme_class_file = DJCATCOMPONENTPATH.DS.'themes'.DS.$theme.DS.'theme.php';
			}
			
			$document->addStyleSheet($theme_css_url);
			$document->addScript($theme_js_url);
			
			if (!class_exists($theme_class)) {
				if (file_exists($theme_class_file)) {
					require_once $theme_class_file;
				}
			}
			if (class_exists($theme_class)) {
				$themeClass = new $theme_class();
				if (method_exists($themeClass, 'setStyles')) {
					$themeClass->setStyles($params);				
				}
			}
			self::$themeSet = true;
		}
	}
	public static function getThemeImage($filename) {
		$app = JFactory::getApplication();
		$document = JFactory::getDocument();
		$params = $app->getParams('com_djcatalog2');
		$theme = $params->get('theme','default');
		if (JFile::exists(DJCATCOMPONENTPATH.DS.'themes'.DS.$theme.DS.'images'.DS.$filename)) {
			return JURI::base().'components/com_djcatalog2/themes/'.$theme.'/images/'.$filename;
		} else if (JFile::exists(DJCATCOMPONENTPATH.DS.'themes'.DS.'default'.DS.'images'.DS.$filename)) {
			return JURI::base().'components/com_djcatalog2/themes/default/images/'.$filename;
		} else {
			return '';
		}
	}
}