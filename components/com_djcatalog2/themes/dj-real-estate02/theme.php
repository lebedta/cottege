<?php
/**
 * @version $Id: theme.php 28 2011-10-11 11:54:13Z michal $
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

class Djcatalog2Theme {
	public function setStyles(&$params) {
		$document = JFactory::getDocument();
		$imageTypes = array('item','category','producer');
		
		$imageParams = array();

		foreach ($imageTypes as $value) {
			$imageParams[$value]['resize'] = intval($params->get($value.'_resize', $params->get('resize', 0)));
			
			$imageParams[$value]['width'] = $params->get($value.'_width', $params->get('width', 'auto'));
			$imageParams[$value]['height'] = $params->get($value.'_height', $params->get('height', 'auto'));

			$imageParams[$value]['medium_width'] = $params->get($value.'_th_width', $params->get('th_width', 'auto'));
			$imageParams[$value]['medium_height'] = $params->get($value.'_th_height', $params->get('th_height', 'auto'));

			$imageParams[$value]['small_width'] = $params->get($value.'_smallth_width', $params->get('smallth_width', 'auto'));
			$imageParams[$value]['small_height'] = $params->get($value.'_smallth_height', $params->get('smallth_height', 'auto'));
			
			$imageParams[$value]['smallth_spacing_h'] = $params->get($value.'_smallth_spacing_h', $params->get('smallth_spacing_h', 5));
			$imageParams[$value]['smallth_spacing_v'] = $params->get($value.'_smallth_spacing_v', $params->get('smallth_spacing_v', 2));
			$imageParams[$value]['smallth_padding_h'] = $params->get($value.'_smallth_padding_h', $params->get('smallth_padding_h', 4));
		}
		
		
		/*
		 * Wzór na wyliczenie szerokości małej miniatury
		 * 
		 * Ws - szer. małej miniatury
		 * Wl - szer. dużej miniatury
		 * TC - liczba miniatur w jednym wierszu
		 * S - odstęp między miniaturami (2 * padding + spacing)
		 * 
		 * Ws = (Wl - S * (TC - 1)) / TC
		 * 
		 * czyli dla TC = 1, Ws = Wl, zatem spacing nie ma znaczenia
		 * 
		 * a dla Lm > 1:
		 * S = (2 * padding) + spacing = (Ws * TC) / (Wl * (TC - 1))
		 * 
		 */
		$css='';
		
		foreach ($imageParams as $key => $value) {
			
			// single item, single producer, single category
			$selector = ' .djc_'.$key;
			
			$thumbHSpacing = $value['smallth_spacing_h'];
			$thumbHPadding = $value['smallth_padding_h'];
			$thumbVSpacing = $value['smallth_spacing_v'];
			
			$css .= $selector. ' .djc_mainimage { margin-left: '.$thumbHSpacing.'px; margin-bottom: '.$thumbVSpacing.'px; } ';
			$css .= $selector. ' .djc_mainimage img { padding: '.$thumbHPadding.'px; } ';
			
			$css .= $selector. ' .djc_thumbnail { margin-left: '.$thumbHSpacing.'px; margin-bottom: '.$thumbVSpacing.'px; } ';
			$css .= $selector. ' .djc_thumbnail img {  padding: '.$thumbHPadding.'px;  } ';
				
			if ($value['width'] != 'auto') {
				$css .= $selector. ' .djc_images {width: '.($value['width']+2*($thumbHPadding)+$thumbHSpacing).'px; } ';
			}
			if ($value['small_width'] != 'auto') {
				$css .= $selector. ' .djc_thumbnail { width: '.($value['small_width'] + (2*$thumbHPadding)).'px; } ';
			}
			if ($value['small_height'] != 'auto') {
				//$css .= $selector. ' .djc_thumbnail a { line-height: '.($value['small_height']).'px; } ';
			}
			
			if ($key == 'category') {
				$selector = ' .djc_subcategory';
				$css .= $selector. ' .djc_image img { padding: '.$thumbHPadding.'px;}';
			}
			if ($key == 'item') {
				$selector = ' .djc_items';
				$css .= $selector. ' .djc_image img { padding: '.$thumbHPadding.'px;}';
				$css .= ' .djc_related_items .djc_image img { padding: '.$thumbHPadding.'px;}';
			}
		}
		
		$document->addStyleDeclaration($css);

	}
}