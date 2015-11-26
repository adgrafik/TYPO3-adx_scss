<?php

$EM_CONF[$_EXTKEY] = array(
	'title' => 'SCSS compiler',
	'description' => 'Contains the SCSS compiler https://github.com/leafo/scssphp which is compatible with Bootstrap 3.4.x. Supports a new function for USER-cObject, hooks for diffrent RTEs which compiles SCSS files for "includeCSS", "content_css" or "contentCSS" and a ViewHelper for Fluid.',
	'author' => 'Arno Dudek',
	'author_email' => 'webmaster@adgrafik.at',
	'author_company' => 'ad:grafik',
	'version' => '1.0.2',
	'category' => 'fe',
	'state' => 'stable',
	'clearcacheonload' => TRUE,
	'constraints' => array(
		'depends' => array(
			'typo3' => '6.2.0-7.99.99',
		),
		'suggests' => array(
		),
		'conflicts' => array(
		),
	),
);

?>