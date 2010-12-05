<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');
// TODO hacer que las acciones sean cacheables

Tx_Extbase_Utility_Extension::configurePlugin(
	$_EXTKEY,
	'Feed',
	array(
		'Contentce' => 'feed,flickr',
	),
	array(
		'Contentce' => 'feed,flickr',
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
		'Contentce' => 'vimeo,youtube',
	),
	array(
		'Contentce' => 'vimeo,youtube',
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


?>
