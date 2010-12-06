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

// Previw del plugin en la vista de lista
if (TYPO3_MODE == 'BE') {
			// Hook for the TV page module used for preview of content
		$TYPO3_CONF_VARS['EXTCONF']['templavoila']['mod1']['renderPreviewContentClass']['f2contentce_bepreview'] = 'EXT:f2contentce/Classes/Util/class.tx_f2contentce_bepreview.php:tx_f2contentce_bepreview';
}

?>
