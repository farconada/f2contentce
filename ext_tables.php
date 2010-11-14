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

?>