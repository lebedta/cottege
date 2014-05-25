<?php
/**
 * @version $Id: router.php 28 2011-10-11 11:54:13Z michal $
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

function DJCatalog2BuildRoute(&$query)
{
	$segments = array();

	$app		= JFactory::getApplication();
	$menu		= $app->getMenu('site');
	if (empty($query['Itemid'])) {
		$menuItem = $menu->getActive();
	} else {
		$menuItem = $menu->getItem($query['Itemid']);
	}
	$mView	= (empty($menuItem->query['view'])) ? null : $menuItem->query['view'];
	$mCatid	= (empty($menuItem->query['cid'])) ? null : $menuItem->query['cid'];
	$mProdid	= (empty($menuItem->query['pid'])) ? null : $menuItem->query['pid'];
	$mId	= (empty($menuItem->query['id'])) ? null : $menuItem->query['id'];
	
	if(isset($query['view'])) {
		switch ($query['view']) {
			case 'item': {
				if ($mView) {
					if ($query['view'] == $mView && isset($query['id'])) {
						if ($query['id'] == $mId) {
							unset($query['view']);
							unset($query['id']);
							if (isset($query['cid'])) {
								unset($query['cid']);
							}
						} else {
							$segments[] = $query['view'];
							if (isset($query['cid'])) {
								$segments[] = $query['cid'] ? $query['cid']:'all';
								unset($query['cid']);
							}
							$segments[] = $query['id'];
							unset($query['view']);
							unset($query['id']);
						}
					} else if (isset($query['cid']) && isset($query['id']) && ($mView == 'items' || $mView=='itemstable')){
						if (intval($query['cid']) == $mCatid) {
							$segments[] = $query['view'];
							$segments[] = $query['id'];
							unset($query['view']);
							unset($query['id']);
							unset($query['cid']);
						} else {
							$segments[] = $query['view'];
							if (isset($query['cid'])) {
								$segments[] = $query['cid'] ? $query['cid']:'all';
								unset($query['cid']);
							}
							$segments[] = $query['id'];
							unset($query['view']);
							unset($query['id']);
						}
					} 
				}
				else {
					$segments[] = $query['view'];
					if (isset($query['cid'])) {
						$segments[] = $query['cid'] ? $query['cid']:'all';
						unset($query['cid']);
					}
					$segments[] = $query['id'];
					unset($query['view']);
					unset($query['id']);
				}
				break;
			}
			case 'items':
			case 'itemstable': {
				if ($query['view'] == $mView && isset($query['cid'])) {
					if (intval($query['cid']) == $mCatid) {
						unset($query['cid']);
						unset($query['view']);
					}
					else if (isset($query['cid'])) {
						$segments[] = $query['view'];
						$segments[] = $query['cid'] ? $query['cid']:'all';
						unset($query['cid']);
						unset($query['view']);
					}
				}
				else if (isset($query['view']) && isset($query['cid'])) {
					$segments[] = $query['view'];
					$segments[] = $query['cid'] ? $query['cid']:'all';
					unset($query['cid']);
					unset($query['view']);
				}
				break;
			}
			case 'producer': {
				if ($query['view'] == $mView && isset($query['pid'])) {
					if (intval($query['pid']) == $mProdid) {
						unset($query['pid']);
						unset($query['view']);
					}
					else if (isset($query['pid'])) {
						$segments[] = $query['view'];
						$segments[] = $query['pid'];
						unset($query['pid']);
						unset($query['view']);
					}
				}
				else if (isset($query['pid'])) {
					$segments[] = $query['view'];
					$segments[] = $query['pid'];
					unset($query['pid']);
					unset($query['view']);
				}
			}
		}
	}
	
	return $segments;
}

function DJCatalog2ParseRoute($segments) {
	
	$app	= JFactory::getApplication();
	$menu	= $app->getMenu();
	$activemenu = $menu->getActive();
	$db = JFactory::getDBO();
	
	$query=array();
	if (isset($segments[0])) {
		switch($segments[0]) {
			case 'items': {
				$query['view'] = 'items';
				if (isset($segments[1])) {
					$query['cid']=($segments[1] == 'all') ? 0 : $segments[1];
				} 
				break;
			}
			case 'itemstable': {
				$query['view'] = 'itemstable';
				if (isset($segments[1])) {
					$query['cid']=($segments[1] == 'all') ? 0 : $segments[1];
				} 
				break;
			}
			case 'item': {
				$query['view'] = 'item';
				
				if (count($segments) > 2) {
					if (isset($segments[1])) {
						$query['cid']=($segments[1] == 'all') ? 0 : $segments[1];
					}
					if (isset($segments[2])) {
						$query['id']=$segments[2];
					}  
				} else {
					if (isset($segments[1])) {
						$query['id']=$segments[1];
						if ($activemenu && $activemenu->query['option'] == 'com_djcatalog2' && $activemenu->query['view'] == 'items' && $activemenu->query['cid']) {
							$query['cid'] = $activemenu->query['cid'];
						}
					}
				}
				break;
			}
			case 'producer': {
				$query['view'] = 'producer';
				if (isset($segments[1])) {
					$query['pid']=$segments[1];
				}  
				break;
			}
			default: {
				if (count($segments) > 2) {
					$query['view'] = 'items';
					if (isset($segments[0])) {
						$query['cid']=($segments[0] == 'all') ? 0 : $segments[0];
					}
					if (isset($segments[2])) {
						$query['id']=$segments[2];
					}  
				}
				else {
					$query['view'] = 'items';
					$query['cid'] = 0;
				}
				break;
			}
		}
	}
	
	
	return $query;
}
