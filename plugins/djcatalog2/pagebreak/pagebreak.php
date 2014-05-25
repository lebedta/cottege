<?php
/**
* @version $Id: pagebreak.php 28 2011-10-11 11:54:13Z michal $
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
class plgDJCatalog2Pagebreak extends JPlugin{
	
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}
	
	function onPrepareItemDescription( &$row, &$params, $page=0 )
	{
		
		// expression to search for
		$regex = '#<hr([^>]*?)class=(\"|\')system-pagebreak(\"|\')([^>]*?)\/*>#iU';
	
		// Get Plugin info
		$pluginParams	= $this;
		if (!$pluginParams->get('enabled', 1)) {
			return true;
		}
		JPlugin::loadLanguage( 'plg_djcatalog2_pagebreak', JPATH_ADMINISTRATOR );
		// replacing readmore with <br /> - we don't need it
		$row->description = str_replace("<hr id=\"system-readmore\" />", "<br />", $row->description);
	
	    if ( strpos( $row->description, 'class="system-pagebreak' ) === false && strpos( $row->description, 'class=\'system-pagebreak' ) === false ) {
			return true;
		}
	
	    $view  = JRequest::getCmd('view');
	
		if (!JPluginHelper::isEnabled('djcatalog2', 'pagebreak') || ($view != 'item' && $view != 'itemstable' && $view != 'items' && $view != 'producer')) {
			$row->description = preg_replace( $regex, '', $row->description );
			return;
		}
	
		// find all instances of plugin and put in $matches
		$matches = array();
		preg_match_all( $regex, $row->description, $matches, PREG_SET_ORDER );
		
		// split the text around the plugin
		$text = preg_split( $regex, $row->description );
		$title = array();
	
		// count the number of pages
		$n = count( $text );
	
		if ($n > 1)
		{
			$pluginParams = $this->params;
			
			$row->description = '';
			$row->description .= $text[0];
			
			$i = 1;
			
			foreach ( $matches as $match ) {
				if ( @$match[0] )
				{
					$attrs = JUtility::parseAttributes($match[0]);
		
					if ( @$attrs['alt'] )
					{
						$title[] = stripslashes( $attrs['alt'] );
					}
					elseif ( @$attrs['title'] )
					{
						$title[] = stripslashes( $attrs['title'] );
					}
					else
					{
						$title[] =  JText::sprintf( 'PLG_DJCATALOG2_PAGEBREAK_TOGGLE', $i );
					}
				}
				else
				{
					$title[] =  JText::sprintf( 'PLG_DJCATALOG2_PAGEBREAK_TOGGLE', $i );
				}
				$i++;
			}
			
			if ($pluginParams->get( 'accordion', 2 ) == 2) {
				
				$row->tabs .='<div class="djc_ultab">';
				for($i = 1; $i < $n; $i++) {
					$row->tabs .= '<span class="djc_litab"><span>'.$title[$i-1].'</span></span>';
				}
				$row->tabs .='</div>';
				$row->tabs .= '<div class="djc_divtab">';
				$row->tabs .= '<div class="djc_divtab_in">';
				for($i = 1; $i < $n; $i++) {
					$row->tabs .= '<div class="djc_rowtab">'.$text[$i].'</div>';
				}
				$row->tabs .= '</div>';
				$row->tabs .= '</div>';
			}
			else if ($pluginParams->get( 'accordion', 2 ) == 1) {
				$row->tabs .= '<div class="djc_divacc">';
				$row->tabs .= '<div class="djc_divacc_in">';
				
				for($i = 1; $i < $n; $i++) {
					$row->tabs .= '<h3 class="djc_h3acc">'.$title[$i-1].'</h3>';
					$row->tabs .= '<div class="djc_rowacc">'.$text[$i].'</div>';
				}
				
				$row->tabs .= '</div>';
				$row->tabs .= '</div>';
			}
			else {
				$row->tabs .= '<div class="djc_divpb">';
				$row->tabs .= '<div class="djc_divpb_in">';
				
				for($i = 1; $i < $n; $i++) {
					$row->tabs .= '<h3 class="djc_h3pb">'.$title[$i-1].'</h3>';
					$row->tabs .= '<div class="djc_rowpb">'.$text[$i].'</div>';
				}
				
				$row->tabs .= '</div>';
				$row->tabs .= '</div>';
			}
		}
	
		return true;
	}
	
}


