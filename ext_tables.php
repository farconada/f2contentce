<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');

Tx_Extbase_Utility_Extension::registerPlugin(
	$_EXTKEY,
	'Feed',
	'f2contentce Feed'
);
Tx_Extbase_Utility_Extension::registerPlugin(
	$_EXTKEY,
	'Gmaps',
	'f2contentce Gmaps'
);

Tx_Extbase_Utility_Extension::registerPlugin(
	$_EXTKEY,
	'Video',
	'f2contentce Video'
);
Tx_Extbase_Utility_Extension::registerPlugin(
	$_EXTKEY,
	'Flickr',
	'f2contentce Flickr'
);

Tx_Extbase_Utility_Extension::registerPlugin(
	$_EXTKEY,
	'Gallery',
	'f2contentce Gallery'
);


t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'f2contentce');

// Flexform para Feeds
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY.'_feed'] = 'pi_flexform';
t3lib_extMgm::addPiFlexFormValue($_EXTKEY.'_feed', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_feed.xml');
// Flexform para Video
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY.'_video'] = 'pi_flexform';
t3lib_extMgm::addPiFlexFormValue($_EXTKEY.'_video', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_video.xml');
// Flexform para Google Maps
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY.'_gmaps'] = 'pi_flexform';
t3lib_extMgm::addPiFlexFormValue($_EXTKEY.'_gmaps', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_gmaps.xml');
// Flexform para Galerias de fotos
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY.'_gallery'] = 'pi_flexform';
t3lib_extMgm::addPiFlexFormValue($_EXTKEY.'_gallery', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_gallery.xml');

	// Icono en el asistente de elementos de contenido
if (TYPO3_MODE == 'BE')	{
	$TBE_MODULES_EXT['xMOD_db_new_content_el']['addElClasses']['tx_f2contentce_wizicon'] = t3lib_extMgm::extPath($_EXTKEY).'Classes/Util/class.tx_f2contentce_wizicon.php';
}
?>