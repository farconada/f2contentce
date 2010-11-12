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
			$feedArray = Zend_Feed::findFeeds('http://twitter.com/farconadaT3');
		} catch (Exception $e) {
			// TODO añadir codigo de gestion de la excepcion
		}

			// una pagina puede tener mas de un feed
		foreach ($feedArray as $feed) {
			if (is_a($feed, 'Zend_Feed_Rss')){
				foreach ($feed as $entry) {
					$myEntries[] = array(
						'title' 	=> $this->objectToPlainText($entry->title),
						'link' 		=> $this->objectToPlainText($entry->link),
						'content' 	=> $this->objectToPlainText($entry->description),
						'date' 		=> $this->objectToPlainText($entry->pubDate),
						'author' 	=> $this->objectToPlainText($entry->author)
					);
				}
			}elseif (is_a($feed, 'Zend_Feed_Atom')) {
				foreach ($feed as $entry) {
					$myEntries[] = array(
						'title' 	=> $this->objectToPlainText($entry->title),
						'link' 		=> $this->objectToPlainText($entry->link),
						'content' 	=> $this->objectToPlainText($entry->summary) ? $this->objectToPlainText($entry->summary) : $this->objectToPlainText($entry->description) ,
						'date' 		=> $this->objectToPlainText($entry->published),
						'author' 	=> $this->objectToPlainText($entry->author->name)
					);
				}
			}
				// Solo se procesa el primer feed de la pagina
			break;
		}
		// TODO asignar $myEntries a la vista y generarla
		//var_dump($myEntries);



	}

	/**
	 * Convierte un Objeto a un string sin tags HTML
	 *
	 * @param 	Object	$object 	Element elemento a convertir
	 * @return	String	Cadena sin tags HTML
	 *
	 */
	private function objectToPlainText($object) {
		return strip_tags($object->__toString());
	}
}
?>