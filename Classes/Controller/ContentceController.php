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

/**
 * Controller for the ContentCE object
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_F2contentce_Controller_ContentceController extends Tx_Extbase_MVC_Controller_ActionController {



	/**
	 * (non-PHPdoc)
	 *
	 * @see Tx_Extbase_MVC_Controller_ActionController::initializeAction()
	 * @return void
	 */
	protected function initializeAction(){

	}

		// TODO Photo Gallery
	public function photoGalleryAction() {

	}

		// TODO YouTube video
	public function youtubeAction() {

	}
	// TODO Vimeo Video
	public function vimeoAction() {

	}

		// TODO Google Video
	public function googlevideoAction() {

	}
	/**
	 * lista los elementos del feed de typo RSS o ATOM
	 *
	 * @return string The rendered HTML string
	 */
	public function listAction() {
		t3lib_div::debug($this->settings);
	}


	public function feedAction() {
		$feedArray = null;
		$myEntries = array();

		try {
				// TODO parametrizar URL en un Flexform
			$feedLinks = Zend_Feed_Reader::findFeedLinks('http://www.darknet.org.uk/');
		} catch (Exception $e) {
			$this->flashMessages->add("Error: ha habido un problema en el servidor al recuperar el feed");
		}

		if (count($feedLinks) == 0) {
			$this->flashMessages->add("No hay feeds que recuperar");
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
			$this->flashMessages->add("No hay entradas en el feeds que recuperar");
		}

		$this->view->assign('feedEntries',$data);



	}

	// TODO Accion para mostrar un CE de Google maps sencillo
	/**
	 *
	 * Enter description here ...
	 */
	public function gmapsAction() {

	}
	/**
	 * Convierte un Objeto a un string sin tags HTML
	 *
	 * @param 	Object	$object 	Element elemento a convertir
	 * @return	String	Cadena sin tags HTML
	 *
	 */
	private function objectToPlainText($object) {
		return strip_tags($object.'');
	}
}
?>
