<?php
require_once 'Zend/Feed/Reader.php';

/**
 * Clase que recupera la entradas de un feed
 *
 * @author falcifer
 *
 */
class Tx_F2contentce_Service_FeedParser {
	private $url;
	private $arrayMapperFunction;
	private $maxEntries = 9999;

	/**
	 * Constructor
	 *
	 * @param string $url URL de donde sacar el feed o de la pagina que tiene el feed
	 * @return void
	 */
	public function __construct($url) {
		$this->url = $url;

	}

	/**
	 * Funcion para mapear objeto de tipo ZendFeedEntry en un array asociativo
	 *
	 * @param function $function Referencia a la funcion de mapeo
	 * @return void
	 */
	public function setArrayMapperFunction($function){
		$this->arrayMapperFunction = $function;
	}

	/**
	 * Maximo de entradas a devolver
	 *
	 * @param integer $max Limite de entradas
	 * @return void
	 */
	public function setMaxEntries ($max) {
		$this->maxEntries = $max;
	}

	/**
	 * Las entradas del feed en un array (se necesita la funcion de mapeo)
	 *
	 * @return Array
	 */
	public function getEntriesAsArray(){
		$entries = array();
		foreach ($this->getEntries() as $entry) {
			$entries[] = is_callable($this->arrayMapperFunction)? call_user_func($this->arrayMapperFunction, $entry): $entry;
		}
		return $entries;
	}

	/**
	 * Consige la primera URL de feed de la URL pasada al constructor
	 *
	 * @throws Exception
	 * @return string URL de feed
	 */
	private function getFeedLink() {
		$url = $this->url;
		try {
			$feedLinks = Zend_Feed_Reader::findFeedLinks($url);
		} catch (Exception $e) {
			throw new Exception('Error: ha habido un problema en el servidor al recuperar el feed', 100, $e);
		}

		if (count($feedLinks) == 0) {
			throw new Exception('Error: no hay feeds que recuperar', 101);
		}

			// solo usa el primer elemento del bucle
		foreach ($feedLinks as $feedLink) {
			return $feedLink['href'];
		}
		return NULL;

	}

	/**
	 * Recupera las entradas de un feed con el limite establecido
	 *
	 * @return Array
	 */
	private function getEntries(){
		$entries = Zend_Feed_Reader::import($this->getFeedLink());
		$entriesInArray = array();
		do {
			$entriesInArray[] = $entries->current();
			$entries->next();
		} while ((count($entriesInArray) < $this->maxEntries) && $entries->valid());

		return $entriesInArray;
	}
}
?>