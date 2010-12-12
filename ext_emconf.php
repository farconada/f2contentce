<?php

########################################################################
# Extension Manager/Repository config file for ext "f2searchnews".
#
# Auto generated 30-09-2010 16:41
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'f2contentce',
	'description' => '',
	'category' => 'plugin',
	'author' => '',
	'author_email' => '',
	'author_company' => '',
	'shy' => '',
	'dependencies' => 'cms,extbase,fluid,zend_framework',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'alpha',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'version' => '0.2.0',
	'constraints' => array(
		'depends' => array(
			'cms' => '',
			'extbase' => '',
			'fluid' => '',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'suggests' => array(
	),
	'_md5_values_when_last_written' => 'a:18:{s:12:"ext_icon.gif";s:4:"e922";s:17:"ext_localconf.php";s:4:"407b";s:14:"ext_tables.php";s:4:"aa4a";s:14:"ext_tables.sql";s:4:"d41d";s:16:"kickstarter.json";s:4:"f5b3";s:43:"Classes/Controller/SearchNewsController.php";s:4:"c44a";s:29:"Classes/Domain/Model/News.php";s:4:"5ad9";s:44:"Classes/Domain/Repository/NewsRepository.php";s:4:"8697";s:34:"Configuration/TypoScript/setup.txt";s:4:"f3ea";s:40:"Resources/Private/Language/locallang.xml";s:4:"f8d5";s:43:"Resources/Private/Language/locallang_db.xml";s:4:"386a";s:38:"Resources/Private/Layouts/default.html";s:4:"caf5";s:55:"Resources/Private/Templates/SearchNews/ListResults.html";s:4:"db4f";s:59:"Resources/Private/Templates/SearchNews/ListResultsAjax.html";s:4:"cf18";s:54:"Resources/Private/Templates/SearchNews/ReindexAll.html";s:4:"4af2";s:50:"Resources/Private/Templates/SearchNews/Search.html";s:4:"6ba5";s:35:"Resources/Public/Icons/relation.gif";s:4:"e615";s:66:"Resources/Public/Icons/tx_f2searchnews_domain_model_searchnews.gif";s:4:"905a";}',
);

?>