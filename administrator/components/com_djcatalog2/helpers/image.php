<?php
/**
 * @version $Id: image.php 84 2012-08-01 13:21:08Z michal $
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

defined('_JEXEC') or die();

class DJCatalog2ImageHelper extends JObject {

	static $images = null;

	public static function renderInput($itemtype, $itemid=null) {
		if (!$itemtype) {
			return false;
		}
		$db = JFactory::getDbo();
		$images = array();
		if ($itemid) {
			$db->setQuery('SELECT * '.
						' FROM #__djc2_images '.
						' WHERE item_id='.intval($itemid). 
						' 	AND type='.$db->Quote( $itemtype).
						' ORDER BY ordering ASC, name ASC ');
			$images = $db->loadObjectList();
		}

		$out = '';

		if (count($images)) {
			$out .= '<ul>';
			foreach ($images as $image) {
				$out .= '
					<li>
						<label for="djc2imageOrder_'.$image->id.'">'.JText::_('COM_DJCATALOG2_IMAGE_ORDER_LABEL').'</label>
						<input id="djc2imageOrder_'.$image->id.'" type="text" name="order_'.$itemtype.'['.$image->id.']" value="'.$image->ordering.'" />
						<label for="djc2imageCaption_'.$image->id.'">'.JText::_('COM_DJCATALOG2_IMAGE_CAPTION_LABEL').'</label>
						<input id="djc2imageCaption_'.$image->id.'" type="text" name="caption_'.$itemtype.'['.$image->id.']" value="'.$image->caption.'" />
						<label for="djc2imageDelete_'.$image->id.'">'.JText::_('COM_DJCATALOG2_IMAGE_DELETE_LABEL').'</label>
						<input id="djc2imageDelete_'.$image->id.'" type="checkbox" name="delete_'.$itemtype.'['.$image->id.']" value="1" />
						<a class="modal" href="'.DJCATIMGURLPATH.'/'.$image->fullname.'">
							<img src="'.DJCATIMGURLPATH.'/'.self::addSuffix($image->fullname, '_s').'" alt="'.$image->fullname.'" />
						</a>
						<input type="hidden" name="image_id_'.$itemtype.'[]" value="'.$image->id.'" />
					</li>
					<li><div style="clear:both; border-bottom:1px dashed #ccc; width: 100%; padding-top: 10px; margin-bottom: 10px;"></div></li>
				';
			}
			$out .= '</ul>';
		}
		else {
			$out .= JText::_('COM_DJCATALOG2_NO_IMAGES_INCLUDED').'<br />';
		}

		//$out .= '<div style="clear:both; border-bottom:1px solid #ccc; width: 100%; padding-top: 10px; margin-bottom: 10px;"></div>';
		$out .= '
				<ul id="uploader_'.$itemtype.'">
					<li>
						<span class="faux-label">'.JText::_('COM_DJCATALOG2_IMAGE_CAPTION_LABEL').'</span>
						<input type="text" name="file_caption_'.$itemtype.'[]" />
						<span class="faux-label">'.JText::_('COM_DJCATALOG2_IMAGE').'</span>
						<input type="file"  name="file_'.$itemtype.'[]" />
					</li>
				</ul>
				<div style="clear:both; border-bottom:1px dashed #ccc; width: 100%; padding-top: 10px; margin-bottom: 10px;"></div>
				<div class="button2-left"><div class="blank"><span onclick="addImage_'.$itemtype.'(); return false;">'.JText::_('COM_DJCATALOG2_ADD_IMG_LINK').'</span></div></div>
				';
		$out .= '
			<script type="text/javascript">
				function addImage_'.$itemtype.'(){
					var fileinput = document.createElement(\'input\');
					fileinput.setAttribute(\'name\',\'file_'.$itemtype.'[]\');
					fileinput.setAttribute(\'type\',\'file\');
					
					var captioninput = document.createElement(\'input\');
					captioninput.setAttribute(\'name\',\'file_caption_'.$itemtype.'[]\');
					captioninput.setAttribute(\'type\',\'text\');
					
					var captionlabel = document.createElement(\'span\');
					captionlabel.setAttribute(\'class\',\'faux-label\');
					captionlabel.innerHTML=\''.JText::_('COM_DJCATALOG2_IMAGE_CAPTION_LABEL').'\';
					
					var filelabel = document.createElement(\'span\');
					filelabel.setAttribute(\'class\',\'faux-label\');
					filelabel.innerHTML=\''.JText::_('COM_DJCATALOG2_IMAGE').'\';
					
					var fileFormDiv = document.createElement(\'li\');
					
					fileFormDiv.appendChild(captionlabel);
					fileFormDiv.appendChild(captioninput);
					fileFormDiv.appendChild(filelabel);
					fileFormDiv.appendChild(fileinput);
					
					var ni = document.id(\'uploader_'.$itemtype.'\');
					ni.appendChild(fileFormDiv);
				}
			</script>
		';

		return $out;

	}
	public static function getImages($itemtype, $itemid) {
		if (!$itemtype || !$itemid) {
			return false;
		}
		$hash = $itemtype.'.'.$itemid;
		if (isset(self::$images[$hash])) {
			return self::$images[$hash];
		}
		$db = JFactory::getDbo();
		$images = array();
		$db->setQuery('SELECT * '.
						' FROM #__djc2_images '.
						' WHERE item_id='.intval($itemid). 
						' 	AND type='.$db->Quote($itemtype).
						' ORDER BY ordering ASC, name ASC ');
		$images = $db->loadObjectList();

		if (count($images)) {
			foreach ( $images as $key=>$image) {
				$images[$key]->original = self::getImageUrl($image->fullname);
				$images[$key]->fullscreen = self::getImageUrl($image->fullname,'fullscreen');
				$images[$key]->frontpage = self::getImageUrl($image->fullname,'frontpage'); 
				$images[$key]->large = self::getImageUrl($image->fullname,'large');
				$images[$key]->medium = self::getImageUrl($image->fullname,'medium');
				$images[$key]->small = self::getImageUrl($image->fullname,'small');
				$images[$key]->thumb = self::getImageUrl($image->fullname,'thumb');
			}
		}
		self::$images[$hash] = $images;

		return self::$images[$hash];

	}
	
	public static function getImageUrl($fullname, $size = null) {
		$suffix = '';
		switch($size) {
			case 'fullscreen': $suffix = '_f'; break;
			case 'frontpage': $suffix = '_fp'; break;
			case 'large': $suffix = '_l'; break;
			case 'medium': $suffix = '_m'; break;
			case 'small': $suffix = '_t'; break;
			case 'thumb': $suffix = '_s'; break;
			case 'original': 
			default: $suffix = ''; break;		
		}
		return DJCATIMGURLPATH.'/'.self::addSuffix($fullname, $suffix);
	}
	public static function deleteImages($itemtype, $itemid) {
		if (!$itemtype || !$itemid) {
			return false;
		}
		$db = JFactory::getDbo();
		$images = array();
		$db->setQuery('SELECT id, fullname '.
						' FROM #__djc2_images '.
						' WHERE item_id='.intval($itemid). 
						' 	AND type='.$db->Quote($itemtype).
						' ORDER BY ordering ASC, name ASC ');
		$images = $db->loadObjectList();

		$images_to_remove = array();
		if (count($images)) {
			foreach ($images as $key=>$image) {
				if (JFile::exists(DJCATIMGFOLDER.DS.$image->fullname)) {
					if (JFile::delete(DJCATIMGFOLDER.DS.$image->fullname)) {
						$images_to_remove[] = $image->id;
						if (JFile::exists(DJCATIMGFOLDER.DS.self::addSuffix($image->fullname, '_s'))) {
							JFile::delete(DJCATIMGFOLDER.DS.self::addSuffix($image->fullname, '_s'));
						}
						if (JFile::exists(DJCATIMGFOLDER.DS.self::addSuffix($image->fullname, '_f'))) {
							JFile::delete(DJCATIMGFOLDER.DS.self::addSuffix($image->fullname, '_f'));
						}
						if (JFile::exists(DJCATIMGFOLDER.DS.self::addSuffix($image->fullname, '_fp'))) {
							JFile::delete(DJCATIMGFOLDER.DS.self::addSuffix($image->fullname, '_fp'));
						}
						if (JFile::exists(DJCATIMGFOLDER.DS.self::addSuffix($image->fullname, '_t'))) {
							JFile::delete(DJCATIMGFOLDER.DS.self::addSuffix($image->fullname, '_t'));
						}
						if (JFile::exists(DJCATIMGFOLDER.DS.self::addSuffix($image->fullname, '_m'))) {
							JFile::delete(DJCATIMGFOLDER.DS.self::addSuffix($image->fullname, '_m'));
						}
						if (JFile::exists(DJCATIMGFOLDER.DS.self::addSuffix($image->fullname, '_l'))) {
							JFile::delete(DJCATIMGFOLDER.DS.self::addSuffix($image->fullname, '_l'));
						}
					}
				}
			}
		}
		if (count($images_to_remove)) {
			$ids = implode(',',$images_to_remove);
			$db->setQuery('DELETE FROM #__djc2_images WHERE id IN ('.$db->getEscaped($ids, false).')');
			$db->query();
		}

		return true;

	}
	public static function saveImages($itemtype, $itemid, &$params, $isNew) {
		if (!$itemtype || !$itemid || empty($params)) {
			return false;
		}

		$db = JFactory::getDbo();
		$app = JFactory::getApplication();

		$image_id = JRequest::getVar('image_id_'.$itemtype, array(),'default');
		$caption = JRequest::getVar('caption_'.$itemtype, array(),'default');
		$delete = JRequest::getVar('delete_'.$itemtype, array(),'default');
		$order = JRequest::getVar('order_'.$itemtype, array(),'default');
		$files = JRequest::get('files');

		$images_to_update = array();
		$images_to_save = array();
		$images_to_copy = array();

		$orderingCounter = 0;


		//delete files
		if (count($delete) && !$isNew) {
			$cids = implode(',', array_keys($delete));
			$db->setQuery('SELECT id, fullname FROM #__djc2_images WHERE id IN ('.$db->getEscaped($cids, false).')');
			$images_to_delete = $db->loadObjectList();
			foreach ($images_to_delete as $row) {
				if (JFile::exists(DJCATIMGFOLDER.DS.$row->fullname)) {
					if (!JFile::delete(DJCATIMGFOLDER.DS.$row->fullname)) {
						$app->setError(JText::_('COM_DJCATALOG2_IMAGE_FILE_DELETE_ERROR'));
						unset($delete[$row->id]);
					} else {
						if (JFile::exists(DJCATIMGFOLDER.DS.self::addSuffix($row->fullname, '_s'))) {
							JFile::delete(DJCATIMGFOLDER.DS.self::addSuffix($row->fullname, '_s'));
						}
						if (JFile::exists(DJCATIMGFOLDER.DS.self::addSuffix($row->fullname, '_f'))) {
							JFile::delete(DJCATIMGFOLDER.DS.self::addSuffix($row->fullname, '_f'));
						}
						if (JFile::exists(DJCATIMGFOLDER.DS.self::addSuffix($row->fullname, '_fp'))) {
							JFile::delete(DJCATIMGFOLDER.DS.self::addSuffix($row->fullname, '_fp'));
						}
						if (JFile::exists(DJCATIMGFOLDER.DS.self::addSuffix($row->fullname, '_t'))) {
							JFile::delete(DJCATIMGFOLDER.DS.self::addSuffix($row->fullname, '_t'));
						}
						if (JFile::exists(DJCATIMGFOLDER.DS.self::addSuffix($row->fullname, '_m'))) {
							JFile::delete(DJCATIMGFOLDER.DS.self::addSuffix($row->fullname, '_m'));
						}
						if (JFile::exists(DJCATIMGFOLDER.DS.self::addSuffix($row->fullname, '_l'))) {
							JFile::delete(DJCATIMGFOLDER.DS.self::addSuffix($row->fullname, '_l'));
						}
					}
				}
			}
			$cids = implode(',', array_keys($delete));
			$db->setQuery('DELETE FROM #__djc2_images WHERE id IN ('.$db->getEscaped($cids, false).')');
			$db->query();
			foreach ($delete as $key => $value) {
				if ($value == 1) {
					$idx = array_search($key, $image_id);
					if (array_key_exists($idx, $image_id)) {
						unset($image_id[$idx]);
					}
				}
			}
		}

		// fetch images that need to be updated/copied to the new item
		if (count($image_id)) {
			$ids = implode(',', $image_id);
			$db->setQuery('SELECT * FROM #__djc2_images WHERE id IN ('.$db->getEscaped($ids, false).') ORDER BY ordering ASC, name ASC');
			$images = $db->loadObjectList();
			foreach ($image_id as $key) {
				foreach ($images as $image) {
					if ($image->id == $key && !array_key_exists($key, $delete)) {
						$obj = array();
						$obj['id'] = ($isNew) ? null:$key;
						if (isset($caption[$key])) {
							$obj['caption'] = $caption[$key];
						} else {
							$obj['caption'] = '';
						}
						if (isset($order[$key])) {
							$obj['ordering'] = intval($order[$key]);
						} else {
							$obj['ordering'] = $image->ordering;
						}
						$obj['name'] = $image->name;
						$obj['fullname'] = $image->fullname;
						$obj['ext'] = $image->ext;
						$obj['item_id'] = $itemid;
						$obj['type'] = $itemtype;

						if ($obj['id']) {
							$images_to_update[] = $obj;
						} else {
							$images_to_copy[] = $obj;
						}
					}
				}
			}
			usort($images_to_update, array('DJCatalog2ImageHelper', 'setOrdering'));
		}

		// copy images
		if (count($images_to_copy)) {
			foreach ($images_to_copy as $key => $copyme) {
				$new_file_name = self::createFileName($copyme['fullname'], DJCATIMGFOLDER);
				if (!JFile::copy(DJCATIMGFOLDER.DS.$copyme['fullname'], DJCATIMGFOLDER.DS.$new_file_name)) {
					$app->setError(JText::_('COM_DJCATALOG2_IMAGE_FILE_COPY_ERROR'));
					unset($images_to_copy[$key]);
				} else {
					$images_to_copy[$key]['fullname'] = $new_file_name;
					$images_to_copy[$key]['name'] = self::stripExtension($new_file_name);
					$images_to_copy[$key]['ext'] = self::getExtension($new_file_name);
				}
			}
		}

		// save uploaded images
		$destExist = false;
		if (!JFolder::exists(DJCATIMGFOLDER)) {
			$destExist = JFolder::create(DJCATIMGFOLDER, 0755);
		} else {
			$destExist = true;
		}

		if ($destExist) {
			$file_caption = JRequest::getVar('file_caption_'.$itemtype,array(),'default');
			if(array_key_exists('file_'.$itemtype, $files)) {
				$file = $files['file_'.$itemtype];
				foreach ($file['name'] as $key => $name) {
					if ($name && $file['error'][$key] == 0 && $file['tmp_name'][$key]) {
						$imgAttrs = getimagesize($file['tmp_name'][$key]);
						$obj = array();
						$obj['id'] = null;
						$gd_info = gd_info();
						
						if ($imgAttrs[2] == 1 && array_key_exists('GIF Create Support',$gd_info) && $gd_info['GIF Create Support'] == 1) {
							$obj['ext'] = 'gif';
						} else if ($imgAttrs[2] == 2 && ((array_key_exists('JPEG Support',$gd_info) && $gd_info['JPEG Support'] == 1) || (array_key_exists('JPG Support',$gd_info) && $gd_info['JPG Support'] == 1))) {
							$obj['ext'] = 'jpg';
						} else if ($imgAttrs[2] == 3 && array_key_exists('PNG Support',$gd_info) && $gd_info['PNG Support'] == 1) {
							$obj['ext'] = 'png';
						} else {
							JError::raiseError( E_USER_ERROR, JText::_( 'COM_DJCATALOG2_IMAGE_FILE_WRONG_TYPE') );
							continue;
						}
						$obj['fullname'] = self::createFileName($name, DJCATIMGFOLDER);
						$obj['ordering'] = 0;
						$obj['name'] = self::stripExtension($obj['fullname']);
						$obj['item_id'] = $itemid;
						$obj['type'] = $itemtype;
						if (isset($file_caption[$key]) && $file_caption[$key] != '') {
							$obj['caption'] = $file_caption[$key];
						} else {
							$obj['caption'] = $obj['name'];
						}
						if (JFile::upload($file['tmp_name'][$key], DJCATIMGFOLDER.DS.$obj['fullname'])) {
							$images_to_save[] = $obj;
						}
						else {
							$app->setError(JText::_('COM_DJCATALOG2_IMAGE_UPLOAD_ERROR'));
						}
					}
				}
			}
		}

		// order images
		$ordering = 1;
		foreach ($images_to_update as $k=>$v) {
			$images_to_update[$k]['ordering'] = $ordering++;
			$obj = new stdClass();
			foreach ($images_to_update[$k] as $key=>$data) {
				$obj->$key = $data;
			}
			if ($isNew) {
				$ret = $db->insertObject( '#__djc2_images', $obj, 'id');
			} else {
				$ret = $db->updateObject( '#__djc2_images', $obj, 'id', false);
			}
			if( !$ret ){
				$app->setError(JText::_('COM_DJCATALOG2_IMAGE_STORE_ERROR').$db->getErrorMsg());
				continue;
			}
		}

		$images_to_process = array_merge($images_to_copy, $images_to_save);
		foreach ($images_to_process as $k=>$v) {
			$images_to_process[$k]['ordering'] = $ordering++;
			$obj = new stdClass();
			foreach ($images_to_process[$k] as $key=>$data) {
				$obj->$key = $data;
			}
			$ret = $db->insertObject( '#__djc2_images', $obj, 'id');
			if( !$ret ){
				unset($images_to_process[$k]);
				$app->setError(JText::_('COM_DJCATALOG2_IMAGE_STORE_ERROR').$db->getErrorMsg());
				continue;
			}
			self::processImage(DJCATIMGFOLDER, $v['fullname'], $itemtype, $params);
		}
		return true;
	}

	protected static function createFileName($filename, $path, $ext = null) {
		$lang = JFactory::getLanguage();

		$filename = $lang->transliterate($filename);
		$filename = strtolower($filename);
		$filename = JFile::makeSafe($filename);

		$namepart = self::stripExtension($filename);
		$extpart = ($ext) ? $ext : self::getExtension($filename);
		if (JFile::exists($path.DS.$filename)) {
			if (is_numeric(self::getExtension($namepart)) && count(explode(".", $namepart))>1) {
				$namepart = self::stripExtension($namepart);
			}
			$iterator = 1;
			$newname = $namepart.'.'.$iterator.'.'.$extpart;
			while (JFile::exists($path.DS.$newname)) {
				$iterator++;
				$newname = $namepart.'.'.$iterator.'.'.$extpart;
			}
			$filename = $newname;
		}

		return $filename;
	}

	public static function processImage($path, $filename, $itemtype, &$params) {
		$resize = intval($params->get($itemtype.'_resize', $params->get('resize', 0)));

		$width = $params->get($itemtype.'_width', $params->get('width', 300));
		$height = $params->get($itemtype.'_height', $params->get('height', 300));
		
		$fp_width = $params->get($itemtype.'_fp_width', $params->get('fp_width', 300));
		$fp_height = $params->get($itemtype.'_fp_height', $params->get('fp_height', 300));

		$medium_width = $params->get($itemtype.'_th_width', $params->get('th_width', 120));
		$medium_height = $params->get($itemtype.'_th_height', $params->get('th_height', 120));

		$small_width = $params->get($itemtype.'_smallth_width', $params->get('smallth_width', 92));
		$small_height = $params->get($itemtype.'_smallth_height', $params->get('smallth_height', 92));


		if (JFile::exists($path.DS.self::addSuffix($filename, '_s'))) {
			JFile::delete($path.DS.self::addSuffix($filename, '_s'));
		}
		if (JFile::exists($path.DS.self::addSuffix($filename, '_f'))) {
			JFile::delete($path.DS.self::addSuffix($filename, '_f'));
		}
		self::resizeImage($path.DS.$filename, $path.DS.self::addSuffix($filename, '_s'), 75, 45, true);
		
		self::resizeImage($path.DS.$filename, $path.DS.self::addSuffix($filename, '_f'), 1920, 1920, true, false);

		if (JFile::exists($path.DS.self::addSuffix($filename, '_t'))) {
			JFile::delete($path.DS.self::addSuffix($filename, '_t'));
		}
		if (JFile::exists($path.DS.self::addSuffix($filename, '_m'))) {
			JFile::delete($path.DS.self::addSuffix($filename, '_m'));
		}
		if (JFile::exists($path.DS.self::addSuffix($filename, '_l'))) {
			JFile::delete($path.DS.self::addSuffix($filename, '_l'));
		}
		if (JFile::exists($path.DS.self::addSuffix($filename, '_fp'))) {
			JFile::delete($path.DS.self::addSuffix($filename, '_fp'));
		}

		switch ($resize) {
			case 1: {
				self::resizeImage($path.DS.$filename, $path.DS.self::addSuffix($filename, '_l'), $width, 0);
				self::resizeImage($path.DS.$filename, $path.DS.self::addSuffix($filename, '_fp'), $fp_width, 0);
				self::resizeImage($path.DS.$filename, $path.DS.self::addSuffix($filename, '_m'), $medium_width, 0);
				self::resizeImage($path.DS.$filename, $path.DS.self::addSuffix($filename, '_t'), $small_width, 0);
				break;
			}

			case 2 : {
				self::resizeImage($path.DS.$filename, $path.DS.self::addSuffix($filename, '_l'), 0, $height);
				self::resizeImage($path.DS.$filename, $path.DS.self::addSuffix($filename, '_fp'), 0, $fp_height);
				self::resizeImage($path.DS.$filename, $path.DS.self::addSuffix($filename, '_m'), 0, $medium_height);
				self::resizeImage($path.DS.$filename, $path.DS.self::addSuffix($filename, '_t'), 0, $small_height);
				break;
			}

			case 3 : {
				self::resizeImage($path.DS.$filename, $path.DS.self::addSuffix($filename, '_l'), $width, $height);
				self::resizeImage($path.DS.$filename, $path.DS.self::addSuffix($filename, '_fp'), $fp_width, $fp_height);
				self::resizeImage($path.DS.$filename, $path.DS.self::addSuffix($filename, '_m'), $medium_width, $medium_height);
				self::resizeImage($path.DS.$filename, $path.DS.self::addSuffix($filename, '_t'), $small_width, $small_height);
				break;
			}

			case 0 :
			default: {
				self::resizeImage($path.DS.$filename, $path.DS.self::addSuffix($filename, '_l'), $width, $height, true);
				self::resizeImage($path.DS.$filename, $path.DS.self::addSuffix($filename, '_fp'), $fp_width, $fp_height, true);
				self::resizeImage($path.DS.$filename, $path.DS.self::addSuffix($filename, '_m'), $medium_width, $medium_height, true);
				self::resizeImage($path.DS.$filename, $path.DS.self::addSuffix($filename, '_t'), $small_width, $small_height, true);

				break;
			}

			/*case 0:
			 default: {
			 JFile::copy($path.DS.$filename, $path.DS.self::addSuffix($filename, '_l'));
			 self::resizeImage($path.DS.$filename, $path.DS.self::addSuffix($filename, '_m'), $medium_width, 0);
			 self::resizeImage($path.DS.$filename, $path.DS.self::addSuffix($filename, '_t'), $small_width, 0);
			 break;
			 }*/
		}

		return true;
	}

	public static function resizeImage($path, $newpath, $nw = 0, $nh = 0, $keep_ratio = false, $enlarge = true) {
		
		$params = JComponentHelper::getParams( 'com_djcatalog2' );
		
		if (!$path || !$newpath)
		return false;
		if (! list ($w, $h, $type, $attr) = getimagesize($path)) {
			return false;
		}

		$OldImage = null;

		switch($type)
		{
			case 1:
				$OldImage = imagecreatefromgif($path);
				break;
			case 2:
				$OldImage = imagecreatefromjpeg($path);
				break;
			case 3:
				$OldImage = imagecreatefrompng($path);
				break;
			default:
				return  false;
				break;
		}
		
		if ($nw == 0 && $nh == 0) {
			$nw = 75;
			$nh = (int)(floor(($nw * $h) / $w));
		}
		elseif ($nw == 0) {
			$nw = (int)(floor(($nh * $w) / $h));
		}
		elseif ($nh == 0) {
			$nh = (int)(floor(($nw * $h) / $w));
		}
		if ($keep_ratio) {
			$x_ratio = $nw / $w;
			$y_ratio = $nh / $h;

			if (($x_ratio * $h) < $nh){
				$nh = ceil($x_ratio * $h);
			}else{
				$nw = ceil($y_ratio * $w);
			}
		}
		
		if ( ($nw > $w || $nh > $h) && !$enlarge) {
			$nw = $w;
			$nh = $h;
		}

		// check if ratios match
		$_ratio=array($w/$h, $nw/$nh);
		if ($_ratio[0] != $_ratio[1]) { // crop image

			// find the right scale to use
			$_scale=min((float)($w/$nw),(float)($h/$nh));

			// coords to crop
			$cropX=(float)($w-($_scale*$nw));
			$cropY=(float)($h-($_scale*$nh));

			// cropped image size
			$cropW=(float)($w-$cropX);
			$cropH=(float)($h-$cropY);

			$crop=ImageCreateTrueColor($cropW,$cropH);
			if ($type == 3) {
				imagecolortransparent($crop, imagecolorallocate($crop, 0, 0, 0));
				imagealphablending($crop, false);
				imagesavealpha($crop, true);
			}
			
			$cropCoeffsX = array('l' => 0, 'm' => 0.5, 'r' => 1);
			$cropCoeffsY = array('t' => 0, 'm' => 0.5, 'b' => 1);
			
			$cropAlignmentX = $params->get('crop_alignment_h', 'm');
			$cropAlignmentY = $params->get('crop_alignment_v', 'm');
			
			if (!array_key_exists($cropAlignmentX, $cropCoeffsX)) {
				$cropAlignmentX = 'm';
			}
			
			if (!array_key_exists($cropAlignmentY, $cropCoeffsY)) {
				$cropAlignmentY = 'm';
			}
			
			ImageCopy(
			$crop,
			$OldImage,
			0,
			0,
			(int)($cropX * $cropCoeffsX[$cropAlignmentX]),
			(int)($cropY * $cropCoeffsY[$cropAlignmentY]),
			$cropW,
			$cropH
			);
		}

		// do the thumbnail
		$NewThumb=ImageCreateTrueColor($nw,$nh);
		if ($type == 3) {
			imagecolortransparent($NewThumb, imagecolorallocate($NewThumb, 0, 0, 0));
			imagealphablending($NewThumb, false);
			imagesavealpha($NewThumb, true);
		}
		if (isset($crop)) { // been cropped
			ImageCopyResampled(
			$NewThumb,
			$crop,
			0,
			0,
			0,
			0,
			$nw,
			$nh,
			$cropW,
			$cropH
			);
			ImageDestroy($crop);
		} else { // ratio match, regular resize
			ImageCopyResampled(
			$NewThumb,
			$OldImage,
			0,
			0,
			0,
			0,
			$nw,
			$nh,
			$w,
			$h
			);
		}

		$thumb_path = $newpath;
		if (is_file($thumb_path))
		unlink($thumb_path);
		switch($type)
		{
			case 1:
				imagegif($NewThumb, $thumb_path);
				break;
			case 2:
				imagejpeg($NewThumb, $thumb_path, 85);
				break;
			case 3:
				imagepng($NewThumb, $thumb_path);
				break;
		}

		ImageDestroy($NewThumb);
		ImageDestroy($OldImage);

		return true;
	}

	protected static function stripExtension($filename) {
		$fileParts = preg_split("/\./", $filename);
		$no = count($fileParts);
		if ($no > 0) {
			unset ($fileParts[$no-1]);
		}
		$filenoext = implode('.',$fileParts);
		return $filenoext;
	}

	protected static function getExtension($filename) {
		$arr = explode(".", $filename);
		$ext = end($arr);
		return $ext;
	}

	protected static function addSuffix($filename, $suffix) {
		return self::stripExtension($filename).$suffix.'.'.self::getExtension($filename);
	}
	public static function setOrdering($img1, $img2){
		return (int)($img1['ordering'] - $img2['ordering']);
	}
}