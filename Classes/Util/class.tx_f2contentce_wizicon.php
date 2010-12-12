<?php
/**
 * Esta clase se usa en un Hook para generar un icono en el asistente de
 * elementos de contenido del BE
 *
 * @author falcifer
 *
 */
class tx_f2contentce_wizicon {

	/**
	 * Adds the newloginbox wizard icon
	 *
	 * @param	array		Input array with wizard items for plugins
	 * @return	array		Modified input array, having the item for newloginbox added.
	 */
	function proc($wizardItems)	{
		$wizardItems['plugins_tx_f2contentce_feed'] = $this->getWizzardArray('feed');
		$wizardItems['plugins_tx_f2contentce_gmpas'] = $this->getWizzardArray('gmaps');
		$wizardItems['plugins_tx_f2contentce_video'] = $this->getWizzardArray('video');
		$wizardItems['plugins_tx_f2contentce_gallery'] = $this->getWizzardArray('gallery');

		return $wizardItems;
	}

	/**
	 * Includes the locallang file
	 *
	 * @return	array		The LOCAL_LANG array
	 */
	function includeLocalLang()	{
		$llFile = t3lib_extMgm::extPath('f2contentce').'Resources/Private/Language/locallang.xml';
		$LOCAL_LANG = t3lib_div::readLLXMLfile($llFile, $GLOBALS['LANG']->lang);
		return $LOCAL_LANG;
	}

	private function getWizzardArray($plugin){
		global $LANG;
		$LL = $this->includeLocalLang();

		$wizardItems = array(
			'icon'=>t3lib_extMgm::extRelPath('f2contentce').'Resources/Public/Icons/'.$plugin.'.gif',
			'title'=>$LANG->getLLL($plugin.'_wiz_title',$LL),
			'description'=>$LANG->getLLL($plugin.'_wiz_description',$LL),
			'params'=>'&defVals[tt_content][CType]=list&defVals[tt_content][list_type]=f2contentce_'.$plugin
		);

		return $wizardItems;
	}

}


?>