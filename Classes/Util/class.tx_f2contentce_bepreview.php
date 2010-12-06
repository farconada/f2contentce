<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2009 Steffen Kamper <info@sk-typo3.de>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

class tx_f2contentce_bepreview {
		/**
		 * Function called from TV, used to generate preview of this plugin
		 *
		 * @param  array   $row:        tt_content table row
		 * @param  string  $table:      usually tt_content
		 * @param  bool    $alreadyRendered:  To let TV know we have successfully rendered a preview
		 * @param object   $reference tx_templavoila_module1
		 * @return string  $content
		 */
		public function renderPreviewContent_preProcess ($row, $table, &$alreadyRendered, &$reference) {
				if ($row['CType'] === 'list' && preg_match('/f2contentce_/',$row['list_type'])) {
						$content = $this->preview($row,$row['list_type']);
						$alreadyRendered = TRUE;
						return $content;
				}
		}


		/**
		 * Render the preview
		 *
		 * @param array  $row tt_content row of the plugin
		 * @param string $key plugin key
		 * @return string rendered preview html
		 */
		protected function preview($row,$type) {
				switch ($type) {
					case 'f2contentce_feed':
						$content = '<img src="../../../'.t3lib_extMgm::extRelPath('f2contentce').'Resources/Public/Icons/plugin-preview-feed.png" />';;
						break;
					case 'f2contentce_gmaps':
						$content = '<img src="../../../'.t3lib_extMgm::extRelPath('f2contentce').'Resources/Public/Icons/plugin-preview-gmaps.png" />';;
						break;
					case 'f2contentce_gallery':
						$content = '<img src="../../../'.t3lib_extMgm::extRelPath('f2contentce').'Resources/Public/Icons/plugin-preview-gallery.png" />';;
						break;
					case 'f2contentce_video':
						$content = '<img src="../../../'.t3lib_extMgm::extRelPath('f2contentce').'Resources/Public/Icons/plugin-preview-video.png" />';;
						break;
					default:
						$content = '<img src="../../../'.t3lib_extMgm::extRelPath('f2contentce').'Resources/Public/Icons/plugin-preview.png" />';
						break;
				}

				return $content;
		}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/example_bepreview/lib/class.tx_examplebepreview_bepreview.php'])    {
		include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/example_bepreview/lib/class.tx_examplebepreview_bepreview.php']);
}

?>
