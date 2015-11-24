<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');

$extensionPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('adx_scss');
$vendorPath = $extensionPath . 'Vendor/';

return array(
	'Leafo\\ScssPhp\\Base\\Range' => $vendorPath . 'leafo/scssphp/src/Base/Range.php',
	'Leafo\\ScssPhp\\Block' => $vendorPath . 'leafo/scssphp/src/Block.php',
	'Leafo\\ScssPhp\\Colors' => $vendorPath . 'leafo/scssphp/src/Colors.php',
	'Leafo\\ScssPhp\\Compiler' => $vendorPath . 'leafo/scssphp/src/Compiler.php',
	'Leafo\\ScssPhp\\Compiler\\Environment' => $vendorPath . 'leafo/scssphp/src/Compiler/Environment.php',
	'Leafo\\ScssPhp\\Formatter' => $vendorPath . 'leafo/scssphp/src/Formatter.php',
	'Leafo\\ScssPhp\\Formatter\\Compact' => $vendorPath . 'leafo/scssphp/src/Formatter/Compact.php',
	'Leafo\\ScssPhp\\Formatter\\Compressed' => $vendorPath . 'leafo/scssphp/src/Formatter/Compressed.php',
	'Leafo\\ScssPhp\\Formatter\\Crunched' => $vendorPath . 'leafo/scssphp/src/Formatter/Crunched.php',
	'Leafo\\ScssPhp\\Formatter\\Debug' => $vendorPath . 'leafo/scssphp/src/Formatter/Debug.php',
	'Leafo\\ScssPhp\\Formatter\\Expanded' => $vendorPath . 'leafo/scssphp/src/Formatter/Expanded.php',
	'Leafo\\ScssPhp\\Formatter\\Nested' => $vendorPath . 'leafo/scssphp/src/Formatter/Nested.php',
	'Leafo\\ScssPhp\\Formatter\\OutputBlock' => $vendorPath . 'leafo/scssphp/src/Formatter/OutputBlock.php',
	'Leafo\\ScssPhp\\Node' => $vendorPath . 'leafo/scssphp/src/Node.php',
	'Leafo\\ScssPhp\\Node\\Number' => $vendorPath . 'leafo/scssphp/src/Node/Number.php',
	'Leafo\\ScssPhp\\Parser' => $vendorPath . 'leafo/scssphp/src/Parser.php',
	'Leafo\\ScssPhp\\Type' => $vendorPath . 'leafo/scssphp/src/Type.php',
	'Leafo\\ScssPhp\\Util' => $vendorPath . 'leafo/scssphp/src/Util.php',
	'Leafo\\ScssPhp\\Version' => $vendorPath . 'leafo/scssphp/src/Version.php',
	'Leafo\\ScssPhp\\Server' => $vendorPath . 'leafo/scssphp/src/Server.php',
);

?>