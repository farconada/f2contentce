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
		// no se puede terminar hasta tener extbase 1.3
		t3lib_div::debug($this->settings);
	}

	public function twitterAction(){

	}
	public function flickrAction() {
		$feedParser = new Tx_F2contentce_Service_FeedParser($this->settings['url']);
		$feedParser->setMaxEntries($this->settings['feed']['maxEntries']);
		$feedParser->setArrayMapperFunction( function($entry){
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
			$this->view->assign('feedEntries', $data);
		} catch (Exception $e) {
			$this->flashMessages->add($e->getMessage());
		}
	}

	public function youtubeAction() {
		$video['height'] = t3lib_div::intval_positive($this->settings['height']);
		$video['width'] = t3lib_div::intval_positive($this->settings['width']);
		$video['id'] = $this->settings['videoId'];
		$this->view->assign('video', $video);

		if( $this->settings['showMetadata']) {
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
	 *
	 * Enter description here ...
	 */
	public function vimeoAction() {
		$video['height'] = t3lib_div::intval_positive($this->settings['height']);
		$video['width'] = t3lib_div::intval_positive($this->settings['width']);
		$video['id'] = $this->settings['videoId'];
		$this->view->assign('video', $video);

		if( $this->settings['showMetadata']) {
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
		$feedArray = NULL;
		$myEntries = array();

		$url = $this->settings['url'];
		try {
			$feedLinks = Zend_Feed_Reader::findFeedLinks($url);
		} catch (Exception $e) {
			$this->flashMessages->add('Error: ha habido un problema en el servidor al recuperar el feed');
			return;
		}

		if (count($feedLinks) == 0) {
			$this->flashMessages->add('No hay feeds que recuperar');
		}

			// una pagina puede tener mas de un feed aunque solo se usa el primero
		foreach ($feedLinks as $feedLink) {
			$feed = Zend_Feed_Reader::import($feedLink['href']);
			foreach ($feed as $entry) {
				$edata = array(
						'title'        => strip_tags($entry->getTitle()),
						'description'  => strip_tags($entry->getDescription()),
						'date' => $entry->getDateModified()->toString('d-M-y'),
						'author'      => is_array($entry->getAuthors()) ? implode(' , ', $entry->getAuthors()): $entry->getAuthors(),
						'link'         => $entry->getLink(),
						'content'      => strip_tags($entry->getContent())
				);
				$data[] = $edata;
			}
			break;
		}

		if (count($data) == 0) {
			$this->flashMessages->add('No hay entradas en el feeds que recuperar');
		}

			// Limitar las entradas del feed
		$maxEntries = t3lib_div::intval_positive($this->settings['feed']['maxEntries']);
		if ($maxEntries && count($data) > $maxEntries) {
				$data = array_slice($data, 0, $maxEntries);
		}
		$this->view->assign('feedEntries', $data);



	}

	/**
	 * Enter description here ...
	 *
	 */
	public function gmapsAction() {
		$this->response->addAdditionalHeaderData('<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&language=es"></script>');

		$map['id'] = 'map-'.md5(time());
		$map['zoom'] = t3lib_div::intval_positive($this->settings['zoom']);
		$map['latlong'] =
			t3lib_div::intval_positive($this->settings['latitude']) . ',' .
			t3lib_div::intval_positive($this->settings['longitude']);
		$map['kml'] = $this->addBaseUriIfNecessary( $this->settings['kml']);

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

}
?>
