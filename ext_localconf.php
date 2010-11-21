<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');
// TODO hacer que las acciones sean cacheables

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
	'Flickr',
	array(
		'Contentce' => 'flickr',
	),
	array(
		'Contentce' => 'flickr',
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

/*
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
*/

?>
