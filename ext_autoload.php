<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');

$extensionPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('adx_scss');
$vendorPath = $extensionPath . 'Vendor/';

return array(
	'Leafo\\ScssPhp\\Compiler' => $vendorPath . 'leafo/scssphp/src/Compiler.php',
);

?>