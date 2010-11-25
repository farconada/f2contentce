<?php
require_once 'Zend/Feed/Reader.php';

class Tx_F2contentce_Service_FeedParser {
	private $url;
	private $mapperFunction;
	private $maxEntries;

	public function __construct($url) {
		$this->url = $url;

	}

	public function setArrayMapperFunction($function){
		$this->mapperFunction = $function;
	}

	public function setMaxEntries ($max) {
		$this->maxEntries = $max;
	}

	public function getEntriesAsArray(){
		$entries = array();
		foreach ($this->getEntries() as $entry) {
			$entries[] = is_callable($this->mapperFunction)? $this->mapperFunction($entry): $entry;
		}
	}

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
	private function getEntries(){
		var_dump(Zend_Feed_Reader::import($this->getFeedLink()));
		return Zend_Feed_Reader::import($this->getFeedLink());
	}
}
?>