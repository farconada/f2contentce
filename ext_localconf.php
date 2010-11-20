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

Tx_Extbase_Utility_Extension::configurePlugin(
	$_EXTKEY,
	'Video',
	array(
		'Contentce' => 'vimeo,youtube,googlevideo',
	),
	array(
		'Contentce' => 'vimeo,youtube,googlevideo',
	)
);

Tx_Extbase_Utility_Extension::configurePlugin(
	$_EXTKEY,
	'Gallery',
	array(
		'Contentce' => 'cycleGallery',
	),
	array(
		'Contentce' => 'cycleGallery',
	)
);

// Extbase < 1.3
t3lib_extMgm::addTypoScript($_EXTKEY,'setup',
		'[GLOBAL]
		tt_content.f2contentce_feed < tt_content.list.20.f2contentce_feed
		tt_content.f2contentce_gmaps < tt_content.list.20.f2contentce_gmaps',
		true
);
?>
