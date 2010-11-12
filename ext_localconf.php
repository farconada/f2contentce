<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

Tx_Extbase_Utility_Extension::configurePlugin(
	$_EXTKEY,
	'Feed',
	array(
		'Contentce' => 'feed',
	),
	array(
		'Contentce' => 'feed',
	)
);

Tx_Extbase_Utility_Extension::configurePlugin(
	$_EXTKEY,
	'Gmaps',
	array(
		'Contentce' => 'gmaps',
	),
	array(
		'Contentce' => 'gmaps',
	)
);


?>