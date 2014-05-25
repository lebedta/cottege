<?php
/**
* @version $Id: helper.php 67 2012-01-02 09:00:30Z michal $
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

require_once(JPATH_BASE.DS.'components'.DS.'com_djcatalog2'.DS.'helpers'.DS.'route.php');
require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_djcatalog2'.DS.'lib'.DS.'categories.php');

class DJC2CategoriesModuleHelper {
	public static function getHtml($cid, $expand, $parent=null) {
		$categories = Djc2Categories::getInstance(array('state'=>'1'));
		$root = $categories->get(0);
		$current = $categories->get($cid);

		$path = array();
		foreach ($current->getPath() as $item) {
			$path[] = (int)$item;
		}
		
		if ($parent) {
			$root = $categories->get($parent);
		} 
		$html = '<ul class="menu">';
		self::makeList($html, $root, $path, $expand, $cid);
		$html .= '</ul>';
		return $html;
	}
	private static function makeList(&$html, &$root, $path, $expand, $cid, $level = 0) {
		$children = $root->getChildren();
		foreach($children as $child) {
			$current = (($child->id == $cid)) ? true:false;
			$parent = (in_array($child->id, $path) || count($child->getChildren())) ? true:false;
			$active = (($current || in_array($child->id, $path))) ? true:false;
			$deeper = ($parent && $expand) ? true:false;

			$class = 'level'.$level;
			$class .= ( $current ) ? ' current':'';
			$class .= ( $active ) ? ' active':'';
			$class .= ( $parent ) ? ' parent':'';
			$class .= ( $deeper ) ? ' deeper':'';

			$html.= '<li class="'.$class.'"><a href="'.JRoute::_(DJCatalogHelperRoute::getCategoryRoute($child->catslug), true).'">'.$child->name.'</a>';
			if (($active || $expand) && count($child->getChildren())) {
				$html .= '<ul>';
				$level++;
				self::makeList($html, $child, $path, $expand, $cid, $level);
				$level--;
				$html .= '</ul>';
			}
			$html .= '</li>';
		}
	}
}
?>
