<?php
/**
 * @version $Id: file.php 70 2012-01-27 09:31:21Z michal $
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

class DJCatalog2FileHelper extends JObject {
	public static function renderInput($itemtype, $itemid=null) {
		if (!$itemtype) {
			return false;
		}
		$db = JFactory::getDbo();
		$atts = array();
		if ($itemid) {
			$db->setQuery('SELECT * '.
						' FROM #__djc2_files '.
						' WHERE item_id='.intval($itemid). 
						' 	AND type='.$db->Quote($itemtype).
						' ORDER BY ordering ASC, name ASC ');
			$atts = $db->loadObjectList();
		}

		$out = '';

		if (count($atts)) {
			$out .= '<ul>';
			foreach ($atts as $attachment) {
				$out .= '
					<li>
						<label for="djc2attOrder_'.$attachment->id.'">'.JText::_('COM_DJCATALOG2_FILE_ORDER_LABEL').'</label>
						<input id="djc2attOrder_'.$attachment->id.'" type="text" name="att_order_'.$itemtype.'['.$attachment->id.']" value="'.$attachment->ordering.'" />
						<label for="djc2attCaption_'.$attachment->id.'">'.JText::_('COM_DJCATALOG2_FILE_CAPTION_LABEL').'</label>
						<input id="djc2attCaption_'.$attachment->id.'" type="text" name="att_caption_'.$itemtype.'['.$attachment->id.']" value="'.$attachment->caption.'" />
						<span class="faux-label">'.JText::_('COM_DJCATALOG2_FILE_HITS_LABEL').'</span>
						<span style="float: left; margin: 5px 5px 5px 0">'.$attachment->hits.'</span>
						<input type="hidden" name="att_hits_'.$itemtype.'['.$attachment->id.']" value="'.$attachment->hits.'" />
						<label for="djc2attDelete_'.$attachment->id.'">'.JText::_('COM_DJCATALOG2_FILE_DELETE_LABEL').'</label>
						<input id="djc2attDelete_'.$attachment->id.'" type="checkbox" name="att_delete_'.$itemtype.'['.$attachment->id.']" value="1" />
						<span class="faux-label">'.JText::_('COM_DJCATALOG2_FILE_FILENAME').'</span><span style="float: left; margin: 5px 5px 5px 0"><a target="_blank" href="index.php?option=com_djcatalog2&task=item.download&format=raw&fid='.$attachment->id.'">'.$attachment->fullname.'</a></span>
						<input type="hidden" name="att_id_'.$itemtype.'[]" value="'.$attachment->id.'" />
					</li>
					<li><div style="clear:both; border-bottom:1px dashed #ccc; width: 100%; padding-top: 10px; margin-bottom: 10px;"></div></li>
				';
			}
			$out .= '</ul>';
		}
		else {
			$out .= JText::_('COM_DJCATALOG2_NO_FILES_INCLUDED').'<br />';
		}

		//$out .= '<div style="clear:both; border-bottom:1px dashed #ccc; width: 100%; padding-top: 10px; margin-bottom: 10px;"></div>';
		$out .= '
				<ul id="att_uploader_'.$itemtype.'">
					<li>
						<span class="faux-label">'.JText::_('COM_DJCATALOG2_FILE_CAPTION_LABEL').'</span>
						<input type="text" name="att_file_caption_'.$itemtype.'[]" />
						<span class="faux-label">'.JText::_('COM_DJCATALOG2_FILE').'</span>
						<input type="file"  name="att_file_'.$itemtype.'[]" />
					</li>
				</ul>
				<div style="clear:both; border-bottom:1px dashed #ccc; width: 100%; padding-top: 10px; margin-bottom: 10px;"></div>
				<div class="button2-left"><div class="blank"><span onclick="addAtt_'.$itemtype.'(); return false;">'.JText::_('COM_DJCATALOG2_ADD_FILE_LINK').'</span></div></div>
				';
		$out .= '
			<script type="text/javascript">
				function addAtt_'.$itemtype.'(){
					var fileinput = document.createElement(\'input\');
					fileinput.setAttribute(\'name\',\'att_file_'.$itemtype.'[]\');
					fileinput.setAttribute(\'type\',\'file\');
					
					var captioninput = document.createElement(\'input\');
					captioninput.setAttribute(\'name\',\'att_file_caption_'.$itemtype.'[]\');
					captioninput.setAttribute(\'type\',\'text\');
					
					var captionlabel = document.createElement(\'span\');
					captionlabel.setAttribute(\'class\',\'faux-label\');
					captionlabel.innerHTML=\''.JText::_('COM_DJCATALOG2_FILE_CAPTION_LABEL').'\';
					
					var filelabel = document.createElement(\'span\');
					filelabel.setAttribute(\'class\',\'faux-label\');
					filelabel.innerHTML=\''.JText::_('COM_DJCATALOG2_FILE').'\';
					
					var fileFormDiv = document.createElement(\'li\');
					
					fileFormDiv.appendChild(captionlabel);
					fileFormDiv.appendChild(captioninput);
					fileFormDiv.appendChild(filelabel);
					fileFormDiv.appendChild(fileinput);
					
					var ni = document.id(\'att_uploader_'.$itemtype.'\');
					ni.appendChild(fileFormDiv);
				}
			</script>
		';

		return $out;

	}
	public static function getFiles($itemtype, $itemid) {
		if (!$itemtype || !$itemid) {
			return false;
		}
		$db = JFactory::getDbo();
		$atts = array();
		$db->setQuery('SELECT * '.
						' FROM #__djc2_files '.
						' WHERE item_id='.intval($itemid). 
						' 	AND type='.$db->Quote($itemtype).
						' ORDER BY ordering ASC, name ASC ');
		$atts = $db->loadObjectList();

		if (count($atts)) {
			foreach ( $atts as $key=>$att) {
				$atts[$key]->size = self::formatBytes(filesize(DJCATATTFOLDER.DS.$att->fullname));
			}
		}

		return $atts;

	}
	public static function getFile($fileid) {
		$db = JFactory::getDbo();
		$document = JFactory::getDocument();
		$db->setQuery('SELECT * '.
						' FROM #__djc2_files '.
						' WHERE id='.intval($fileid));
		$file=$db->loadObject();
		
		$filename = DJCATATTFOLDER.DS.$file->fullname;
		
		if ($file) {
			if ($fd = JFile::read($filename)) {
				
				// hit file
				$db->setQuery('UPDATE #__djc2_files SET hits='.($file->hits+1).' WHERE id='.$fileid);
				$db->query();
				
				$filesize = filesize($filename);
				$parts = pathinfo($filename);
				$ext = strtolower($parts["extension"]);
				ob_start();
				
				// Required for some browsers
				if(ini_get('zlib.output_compression'))
				    ini_set('zlib.output_compression', 'Off'); 
				
				// Determine Content Type
			    switch ($ext) {
			      case "pdf": $ctype="application/pdf"; break;
			      case "exe": $ctype="application/octet-stream"; break;
			      case "zip": $ctype="application/zip"; break;
			      case "doc": $ctype="application/msword"; break;
			      case "xls": $ctype="application/vnd.ms-excel"; break;
			      case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
			      case "gif": $ctype="image/gif"; break;
			      case "png": $ctype="image/png"; break;
			      case "jpeg":
			      case "jpg": $ctype="image/jpg"; break;
			      default: $ctype="application/force-download";
			    }
				
				$document->setMimeEncoding($ctype);

				header("Pragma: public"); // required
			    header("Expires: 0");
			    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			    header("Cache-Control: private",false); // required for certain browsers
			    header("Content-Type: ".$ctype);
			    header("Content-Disposition: filename=\"".$parts["basename"]."\";" );
			    //header("Content-Disposition: attachment; filename=\"".$parts["basename"]."\";" );
			    header("Content-Transfer-Encoding: binary");
			    header("Content-Length: ".$filesize);  
				echo $fd;
				$output = ob_get_contents();
				ob_end_clean();
				
				return $output;
			}
			else {
				return false;
			}
		} else {
			return false;
		}
	}
	public static function deleteFiles($itemtype, $itemid) {
		if (!$itemtype || !$itemid) {
			return false;
		}
		$db = JFactory::getDbo();
		$atts = array();
		$db->setQuery('SELECT id, fullname '.
						' FROM #__djc2_files '.
						' WHERE item_id='.intval($itemid). 
						' 	AND type='.$db->Quote($itemtype).
						' ORDER BY ordering ASC, name ASC ');
		$atts = $db->loadObjectList();

		$atts_to_remove = array();
		if (count($atts)) {
			foreach ($atts as $key=>$attachment) {
				if (JFile::exists(DJCATATTFOLDER.DS.$attachment->fullname)) {
					if (JFile::delete(DJCATATTFOLDER.DS.$attachment->fullname)) {
						$atts_to_remove[] = $attachment->id;
					}
				}
			}
		}
		if (count($atts_to_remove)) {
			$ids = implode(',',$atts_to_remove);
			$db->setQuery('DELETE FROM #__djc2_files WHERE id IN ('.$db->getEscaped($ids, false).')');
			$db->query();
		}

		return true;

	}
	public static function saveFiles($itemtype, $itemid, &$params, $isNew) {
		if (!$itemtype || !$itemid || empty($params)) {
			return false;
		}

		$db = JFactory::getDbo();
		$app = JFactory::getApplication();

		$attachment_id = JRequest::getVar('att_id_'.$itemtype, array(),'default');
		$caption = JRequest::getVar('att_caption_'.$itemtype, array(),'default');
		$delete = JRequest::getVar('att_delete_'.$itemtype, array(),'default');
		$order = JRequest::getVar('att_order_'.$itemtype, array(),'default');
		$hits = JRequest::getVar('att_hits_'.$itemtype, array(),'default');
		$files = JRequest::get('files');

		$atts_to_update = array();
		$atts_to_save = array();
		$atts_to_copy = array();

		$orderingCounter = 0;


		//delete files
		if (count($delete) && !$isNew) {
			$cids = implode(',', array_keys($delete));
			$db->setQuery('SELECT id, fullname FROM #__djc2_files WHERE id IN ('.$db->getEscaped($cids, false).')');
			$atts_to_delete = $db->loadObjectList();
			foreach ($atts_to_delete as $row) {
				if (JFile::exists(DJCATATTFOLDER.DS.$row->fullname)) {
					if (!JFile::delete(DJCATATTFOLDER.DS.$row->fullname)) {
						$app->setError(JText::_('COM_DJCATALOG2_FILE_FILE_DELETE_ERROR'));
						unset($delete[$row->id]);
					}
				}
			}
			$cids = implode(',', array_keys($delete));
			$db->setQuery('DELETE FROM #__djc2_files WHERE id IN ('.$db->getEscaped($cids, false).')');
			$db->query();
			foreach ($delete as $key => $value) {
				if ($value == 1) {
					$idx = array_search($key, $attachment_id);
					if (array_key_exists($idx, $attachment_id)) {
						unset($attachment_id[$idx]);
					}
				}
			}
		}

		// fetch images that need to be updated/copied to the new item
		if (count($attachment_id)) {
			$ids = implode(',', $attachment_id);
			$db->setQuery('SELECT * FROM #__djc2_files WHERE id IN ('.$db->getEscaped($ids, false).') ORDER BY ordering ASC, name ASC');
			$atts = $db->loadObjectList();
			foreach ($attachment_id as $key) {
				foreach ($atts as $attachment) {
					if ($attachment->id == $key && !array_key_exists($key, $delete)) {
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
							$obj['ordering'] = $attachment->ordering;
						}
						$obj['name'] = $attachment->name;
						$obj['fullname'] = $attachment->fullname;
						$obj['ext'] = $attachment->ext;
						$obj['item_id'] = $itemid;
						$obj['type'] = $itemtype;
						$obj['hits'] = ($isNew) ? 0:$hits[$key];

						if ($obj['id']) {
							$atts_to_update[] = $obj;
						} else {
							$atts_to_copy[] = $obj;
						}
					}
				}
			}
			usort($atts_to_update, array('DJCatalog2FileHelper', 'setOrdering'));
		}

		// copy images
		if (count($atts_to_copy)) {
			foreach ($atts_to_copy as $key => $copyme) {
				$new_file_name = self::createFileName($copyme['fullname'], DJCATATTFOLDER);
				if (!JFile::copy(DJCATATTFOLDER.DS.$copyme['fullname'], DJCATATTFOLDER.DS.$new_file_name)) {
					$app->setError(JText::_('COM_DJCATALOG2_FILE_COPY_ERROR'));
					unset($atts_to_copy[$key]);
				} else {
					$atts_to_copy[$key]['fullname'] = $new_file_name;
					$atts_to_copy[$key]['name'] = self::stripExtension($new_file_name);
					$atts_to_copy[$key]['ext'] = self::getExtension($new_file_name);
				}
			}
		}

		// save uploaded images
		$destExist = false;
		if (!JFolder::exists(DJCATATTFOLDER)) {
			$destExist = JFolder::create(DJCATATTFOLDER, 0755);
		} else {
			$destExist = true;
		}

		if ($destExist) {
			$file_caption = JRequest::getVar('att_file_caption_'.$itemtype,array(),'default');
			if(array_key_exists('att_file_'.$itemtype, $files)) {
				$file = $files['att_file_'.$itemtype];
				foreach ($file['name'] as $key => $name) {
					if ($name && $file['error'][$key] == 0 && $file['tmp_name'][$key]) {
						$obj = array();
						$obj['id'] = null;

						$obj['fullname'] = self::createFileName($name, DJCATATTFOLDER);
						$obj['ordering'] = 0;
						$obj['name'] = self::stripExtension($obj['fullname']);
						$obj['ext'] = self::getExtension($obj['fullname']);
						$obj['item_id'] = $itemid;
						$obj['type'] = $itemtype;
						if (isset($file_caption[$key]) && $file_caption[$key] != '') {
							$obj['caption'] = $file_caption[$key];
						} else {
							$obj['caption'] = $obj['name'];
						}
						if (JFile::upload($file['tmp_name'][$key], DJCATATTFOLDER.DS.$obj['fullname'])) {
							$atts_to_save[] = $obj;
						}
						else {
							$app->setError(JText::_('COM_DJCATALOG2_FILE_UPLOAD_ERROR'));
						}
					}
				}
			}
		}

		// order images
		$ordering = 1;
		foreach ($atts_to_update as $k=>$v) {
			$atts_to_update[$k]['ordering'] = $ordering++;
			$obj = new stdClass();
			foreach ($atts_to_update[$k] as $key=>$data) {
				$obj->$key = $data;
			}
			if ($isNew) {
				$ret = $db->insertObject( '#__djc2_files', $obj, 'id');
			} else {
				$ret = $db->updateObject( '#__djc2_files', $obj, 'id', false);
			}
			if( !$ret ){
				$app->setError(JText::_('COM_DJCATALOG2_FILE_STORE_ERROR').$db->getErrorMsg());
				continue;
			}
		}

		$atts_to_process = array_merge($atts_to_copy, $atts_to_save);
		foreach ($atts_to_process as $k=>$v) {
			$atts_to_process[$k]['ordering'] = $ordering++;
			$obj = new stdClass();
			foreach ($atts_to_process[$k] as $key=>$data) {
				$obj->$key = $data;
			}
			$ret = $db->insertObject( '#__djc2_files', $obj, 'id');
			if( !$ret ){
				unset($atts_to_process[$k]);
				$app->setError(JText::_('COM_DJCATALOG2_FILE_STORE_ERROR').$db->getErrorMsg());
				continue;
			}
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
	public static function setOrdering($file1, $file2){
		return (int)($file1['ordering'] - $file2['ordering']);
	}
	protected static function formatBytes($size) {
		$units = array(' B', ' KB', ' MB', ' GB', ' TB');
		for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;
		return round($size, 2).$units[$i];
	}
}