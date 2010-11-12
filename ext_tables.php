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

t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'f2contentce');

// Flexform para Feeds
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY.'_feed'] = 'pi_flexform';
t3lib_extMgm::addPiFlexFormValue($_EXTKEY.'_feed', 'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/flexform_feed.xml');

?>