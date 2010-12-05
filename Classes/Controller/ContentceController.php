<?php

/***************************************************************
*  Copyright notice
*
*  (c) 2010
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

require_once 'Zend/Feed/Reader.php';
require_once 'Zend/Gdata/YouTube.php';
require_once 'Zend/Rest/Client.php';

/**
 * Controller for the ContentCE object
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_F2contentce_Controller_ContentceController extends Tx_Extbase_MVC_Controller_ActionController {



	public function cycleGalleryAction() {
		$this->response->addAdditionalHeaderData('<script type="text/javascript" src="'.t3lib_extMgm::extRelPath('f2contentce').'Resources/Public/JavaScript/jquery.cycle.lite.min.js"></script>');

		$this->view->assign('images', $this->settings['images']);
		$this->view->assign('minWidth', t3lib_div::intval_positive($this->settings['minWidth']));
		$this->view->assign('maxWidth', t3lib_div::intval_positive($this->settings['maxWidth']));

		$this->response->addAdditionalHeaderData("
			<script type=\"text/javascript\">
				$(function() {
					$('.f2contentce.feedEntries.cyclegallery').cycle({
						timeout: ". t3lib_div::intval_positive($this->settings['displayTime'])*1000
					."});
				});
			</script>
		");
	}

	/**
	 * CE galeria sencilla de Flickr
	 *
	 * @return void
	 */
	public function flickrAction() {
		$this->response->addAdditionalHeaderData('<script type="text/javascript" src="'.t3lib_extMgm::extRelPath('f2contentce').'Resources/Public/JavaScript/jquery.cycle.lite.min.js"></script>');
		$feedParser = new Tx_F2contentce_Service_FeedParser($this->settings['url']);
		$feedParser->setMaxEntries($this->settings['feed']['maxEntries']);

		$feedParser->setArrayMapperFunction( function($entry, $otherArgs){
			return array(
						'title'        => strip_tags($entry->getTitle()),
						'description'  => strip_tags($entry->getDescription()),
						'date' => $entry->getDateModified()->toString('d-M-y'),
						'author'      => is_array($entry->getAuthors()) ? implode(' , ', $entry->getAuthors()): $entry->getAuthors(),
						'link'         => $entry->getLink(),
						'enclosure'		=> $entry->getEnclosure()->url,
						'photo'			=> preg_match('/http:\/\/farm.*_m.jpg/', $entry->getContent(), $matches) ? $matches[0]: NULL,
						'content'      => strip_tags($entry->getContent())
				);
		});

		try {
			$data = $feedParser->getEntriesAsArray();
				// Se cambia el tamaño de las fotos al seleccionado
			foreach ($data as $key => $photo) {
				$data[$key]['photo'] = preg_replace('/_m.jpg/', '_'.$this->settings['flickrPhotoSize'].'.jpg', $data[$key]['photo']);
			}
			$this->view->assign('feedEntries', $data);
		} catch (Exception $e) {
			$this->flashMessages->add($e->getMessage());
			return ;
		}
		$this->response->addAdditionalHeaderData("
			<script type=\"text/javascript\">
				$(function() {
					$('.f2contentce.feedEntries.flickr').cycle();
				});
			</script>
		");
	}

	/**
	 * CE video de YouTube con metadatos
	 *
	 * @return void
	 */
	public function youtubeAction() {
		$video['height'] = t3lib_div::intval_positive($this->settings['height']);
		$video['width'] = t3lib_div::intval_positive($this->settings['width']);
		$video['id'] = $this->settings['videoId'];
		$this->view->assign('video', $video);

		if ($this->settings['showMetadata']) {
			$yt = new Zend_Gdata_YouTube();
			try {
				$youtubeVideo = $yt->getVideoEntry($video['id']);
			} catch (Exception $e) {
				$this->flashMessages->add('Error: ha habido un problema en el servidor al recuperar la informacion del video');
				return;
			}
			$metadata['viewCount'] = $youtubeVideo->getStatistics()->getViewCount();
			$metadata['comments'] = count($youtubeVideo->getComments());
			$metadata['url'] = $youtubeVideo->getVideoWatchPageUrl();
			$this->view->assign('metadata', $metadata);

		}
	}

	/**
	 * CE video de Vimeo con metadatos
	 *
	 * @return void
	 */
	public function vimeoAction() {
		$video['height'] = t3lib_div::intval_positive($this->settings['height']);
		$video['width'] = t3lib_div::intval_positive($this->settings['width']);
		$video['id'] = $this->settings['videoId'];
		$this->view->assign('video', $video);

		if ($this->settings['showMetadata']) {
			$apiUrl = 'http://vimeo.com/api/v2/video/'.$video['id'].'.php';
			$curl = curl_init($apiUrl);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			$metadata = unserialize(curl_exec($curl));
				// Use only the first item of the array if exists
			$metadata = is_array($metadata) ? $metadata[0]: NULL;
			$this->view->assign('metadata', $metadata);
		}
	}



	/**
	 * Busca los Feeds de una URL y muestra las entradas
	 *
	 * @return	void La vista HTML
	 */
	public function feedAction() {
		$feedParser = new Tx_F2contentce_Service_FeedParser($this->settings['url']);
		$feedParser->setMaxEntries($this->settings['feed']['maxEntries']);
		if ($this->settings['feed']['stripHtmlTags']) {
			$feedParser->setArrayMapperFunction( function($entry){
				return array(
							'title'        => strip_tags($entry->getTitle()),
							'description'  => strip_tags($entry->getDescription()),
							'date' => $entry->getDateModified()->toString('d-M-y'),
							'author'      => is_array($entry->getAuthors()) ? implode(' , ', $entry->getAuthors()): $entry->getAuthors(),
							'link'         => $entry->getLink(),
							'content'      => strip_tags($entry->getContent())
					);
			});
		} else {
			$feedParser->setArrayMapperFunction( function($entry){
				return array(
							'title'        => $entry->getTitle(),
							'description'  => $entry->getDescription(),
							'date' => $entry->getDateModified()->toString('d-M-y'),
							'author'      => is_array($entry->getAuthors()) ? implode(' , ', $entry->getAuthors()): $entry->getAuthors(),
							'link'         => $entry->getLink(),
							'content'      => $entry->getContent()
					);
			});

		}

		try {
			$data = $feedParser->getEntriesAsArray();
			$this->view->assign('feedEntries', $data);
		} catch (Exception $e) {
			$this->flashMessages->add($e->getMessage());
		}

	}

	/**
	 * CE GoogleMaps: marker + KML
	 *
	 * @return void
	 */
	public function gmapsAction() {
		$this->response->addAdditionalHeaderData('<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&language=es"></script>');

		$map['id'] = 'map-'.md5(time());
		$map['zoom'] = t3lib_div::intval_positive($this->settings['zoom']);
		$map['latlong'] =
			t3lib_div::intval_positive($this->settings['latitude']) . ',' .
			t3lib_div::intval_positive($this->settings['longitude']);
		$map['kml'] = $this->addBaseUriIfNecessary( $this->settings['kml']);

			// refactorizable?
		$this->response->addAdditionalHeaderData('
			<style>
			#'.$map['id'].'
					{
					width: 100%;
					border: 1px solid #000;
					height:'.t3lib_div::intval_positive($this->settings['height']).'px;
			}
			</style>
		');

		$this->view->assign('map', $map);
	}
	/**
	 * Carga un CSS configurado como plugin.tx_f2contentce.settings.actioname.stylesheet
	 *
	 * @return void
	 */
	private function addStylesheet(){
			$stylesheet = $this->settings[$this->request->getControllerActionName()]['stylesheet'];
				// "EXT:" shortcut replaced with the extension path
			$stylesheet = str_replace('EXT:', t3lib_extMgm::siteRelPath('f2contentce'), $stylesheet);

				// different solution to add the css if the action is cached or uncached
			if ($this->request->isCached()) {
					$GLOBALS['TSFE']->getPageRenderer()->addCssFile($stylesheet);
			} else {

					$this->response->addAdditionalHeaderData('<link rel="stylesheet" type="text/css" href="'.
									$stylesheet.'" media="all" />');
			}
	}

	/**
	 * Carga un JS configurado como plugin.tx_f2contentce.settings.actioname.js
	 *
	 * @return void
	 */
	private function addJs(){
			$js = $this->settings[$this->request->getControllerActionName()]['js'];
				// "EXT:" shortcut replaced with the extension path
			$js = str_replace('EXT:', t3lib_extMgm::siteRelPath('f2contentce'), $js);

				// different solution to add the css if the action is cached or uncached
			if ($this->request->isCached()) {
					$GLOBALS['TSFE']->getPageRenderer()->addJsFile($js);
			} else {

					$this->response->addAdditionalHeaderData('<script src="'.
									$stylesheet.'" type="text/javascript" />');
			}
	}

}
?>
