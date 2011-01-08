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

	/**
	 * Inicializacion comun para todas las Action
	 *
	 * @see Tx_Extbase_MVC_Controller_ActionController::initializeAction()
	 * @return void
	 */
	public function initializeAction() {
			// En TS plugin.tx_f2contentce.settings.actioname.js
			// Puede ser relativo a EXT:
		$this->addJavaScript(str_replace('EXT:', t3lib_extMgm::siteRelPath('f2contentce'), $this->settings[$this->request->getControllerActionName()]['js']));
			// En TS plugin.tx_f2contentce.settings.actioname.stylesheet
			// Puede ser relativo a EXT:
		$this->addStylesheet(str_replace('EXT:', t3lib_extMgm::siteRelPath('f2contentce'), $this->settings[$this->request->getControllerActionName()]['stylesheet']));
	}

	/**
	 * Sobrescribit el fichero de plantilla para usar uno personalizado
	 *
	 * @param object $view The view object
	 * @see Tx_Extbase_MVC_Controller_ActionController::initializeView()
	 * @return void
	 */
	public function initializeView($view) {
			// Utiliza el template pasado en el Flexform
		$this->overrideViewFile(trim($this->settings['templateFile']));
	}

	/**
	 * Galeria de imagenes con fotos en el propio gestor
	 *
	 * @return html generado por la vista
	 */
	public function cycleGalleryAction() {
			// JQuery Cycle plugin
		$this->response->addAdditionalHeaderData('<script type="text/javascript" src="'.t3lib_extMgm::extRelPath('f2contentce').'Resources/Public/JavaScript/jquery.cycle.lite.min.js"></script>');

			// Imagenes y opciones de imagenes
		$this->view->assign('images', $this->settings['images']);
		$this->view->assign('minWidth', t3lib_div::intval_positive($this->settings['minWidth']));
		$this->view->assign('maxWidth', t3lib_div::intval_positive($this->settings['maxWidth']));

		if ( !isset($GLOBALS['f2contentce_gallery_cycle'])) {
			$GLOBALS['f2contentce_gallery_cycle'] = TRUE;
				// Codigo JS apra jecutar la galeria
				// Solo se personaliza el tiempo entre imagenes
			$this->response->addAdditionalHeaderData("
				<script type=\"text/javascript\">
					$(function() {
						$('.f2contentce.cyclegallery').cycle({
							timeout: ". t3lib_div::intval_positive($this->settings['displayTime']) * 1000 .
						"});
					});
				</script>
				<style type=\"text/css\">
					/* give slideshow some style */
					.f2contentce.cyclegallery { margin: 20px auto; height: ". t3lib_div::intval_positive($this->settings['galleryHeight']) ."px }

					/* give each slide the same dimensions */
					.f2contentce.cyclegalleryr div { height: ". t3lib_div::intval_positive($this->settings['galleryHeight']) ."px;  }
				</style>
			");
		}
	}

	/**
	 * CE galeria sencilla de Flickr. Coge unas fotos de un feed de flickr y las
	 * muestra en una galeria de JQuery Cycle
	 *
	 * @return html generado por la vista
	 */
	public function flickrAction() {
			// JQuery Cycle plugin
		$this->response->addAdditionalHeaderData('<script type="text/javascript" src="'.t3lib_extMgm::extRelPath('f2contentce').'Resources/Public/JavaScript/jquery.cycle.lite.min.js"></script>');
			// FeedParser de la URL de Flickr
		$feedParser = new Tx_F2contentce_Service_FeedParser($this->settings['url']);
		$feedParser->setMaxEntries($this->settings['feed']['maxEntries']);

			// Funcion que mapea un Feed general en un array asociativo
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

			// recupera las entradas del feed desde Flickr
		try {
			$data = array();
			$data = $feedParser->getEntriesAsArray();
		} catch (Exception $e) {
			$this->flashMessages->add($e->getMessage());
			return NULL;
		}

			// Se cambia el tamaño de las fotos al seleccionado
		foreach ($data as $key => $photo) {
			$data[$key]['photo'] = preg_replace('/_m.jpg/', '_'.$this->settings['flickrPhotoSize'].'.jpg', $data[$key]['photo']);
		}

		$this->view->assign('feedEntries', $data);
		if ( !isset($GLOBALS['f2contentce_feed_cycle'])) {
			$GLOBALS['f2contentce_feed_cycle'] = TRUE;
			$this->response->addAdditionalHeaderData("
				<script type=\"text/javascript\">
					$(function() {
						$('.f2contentce.feedEntries.flickr').cycle();
					});
				</script>
				<style type=\"text/css\">
						/* give slideshow some style */
						.f2contentce.feedEntries.flickr { margin: 20px auto; height: ". t3lib_div::intval_positive($this->settings['galleryHeight']) ."px }

						/* give each slide the same dimensions */
						.f2contentce.feedEntries.flickr div { height: ". t3lib_div::intval_positive($this->settings['galleryHeight']) ."px;  }
				</style>
			");
		}
	}

	/**
	 * CE video de YouTube con metadatos
	 *
	 * @return html generado por la vista
	 */
	public function youtubeAction() {
		$video['height'] = t3lib_div::intval_positive($this->settings['height']);
		$video['width'] = t3lib_div::intval_positive($this->settings['width']);
		$video['id'] = $this->settings['videoId'];
		$this->view->assign('video', $video);

			// Si hay que mostrar mas cosas ademas del video
		if ($this->settings['showMetadata']) {
			$yt = new Zend_Gdata_YouTube();
			try {
					// hay que ir a YouTube para coger los metadatos y puede
					// una excepcion
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
	 * @return html generado por la vista
	 */
	public function vimeoAction() {
		$video['height'] = t3lib_div::intval_positive($this->settings['height']);
		$video['width'] = t3lib_div::intval_positive($this->settings['width']);
		$video['id'] = $this->settings['videoId'];
		$this->view->assign('video', $video);

			// Si hay que mostrar mas cosas ademas del video
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
	 * Es recomendable configurar la pagina en UTF-8
	 *
	 * @return html generado por la vista
	 */
	public function feedAction() {
		$feedParser = new Tx_F2contentce_Service_FeedParser($this->settings['url']);
		$feedParser->setMaxEntries($this->settings['feed']['maxEntries']);
			// Funcion que mapea un Feed general en un array asociativo
			// hay dos funciones en funcion de si hay que quitar o no el HTML
			// del propio feed
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
			$data = array();
			$data = $feedParser->getEntriesAsArray();
		} catch (Exception $e) {
			$this->flashMessages->add($e->getMessage());
			return;
		}
		$this->view->assign('feedEntries', $data);

	}

	/**
	 * CE GoogleMaps: marker + KML
	 *
	 * @return html generado por la vista
	 */
	public function gmapsAction() {
			// JS de Google Maps v3
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
	 * @param string $stylesheet Path con la CSS
	 * @return void
	 */
	private function addStylesheet($stylesheet){
		if($stylesheet && file_exists($stylesheet)) {
				// different solution to add the css if the action is cached or uncached
			if ($this->request->isCached()) {
					$GLOBALS['TSFE']->getPageRenderer()->addCssFile($stylesheet);
			} else {

					$this->response->addAdditionalHeaderData('<link rel="stylesheet" type="text/css" href="'.
									$stylesheet.'" media="all" />');
			}
		}
	}

	/**
	 * Carga un JS configurado como plugin.tx_f2contentce.settings.actioname.js
	 *
	 * @param string $jsFile JavaScript file
	 * @return void
	 */
	private function addJavaScript($jsFile){
		if($jsFile && file_exists($jsFile)) {
				// different solution to add the JS if the action is cached or uncached
			if ($this->request->isCached()) {
					$GLOBALS['TSFE']->getPageRenderer()->addJsFile($jsFile);
			} else {

					$this->response->addAdditionalHeaderData('<script src="'.
									$jsFile.'" type="text/javascript" />');
			}
		}
	}

	/**
	 * Permite cargar otro template en la vista
	 *
	 * @param file $templateFile Fichero fluid
	 * @return void
	 */
	private function overrideViewFile($templateFile) {
		if($templateFile && file_exists($templateFile)) {
			$this->view->setTemplatePathAndFilename($templateFile);
		}
	}

}
?>
