<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');

$extensionPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('adx_scss');
$scssphpLibSrcPath = $extensionPath . 'Vendor/leafo/scssphp/src/';
$scssphpLibPrefix = 'Leafo\\ScssPhp\\';

$classPaths = array();

// This values was taken from ./Vendor/leafo/scssphp/scss.inc.php
$includes = array('Base/Range', 'Block', 'Colors', 'Compiler',
 'Compiler/Environment', 'Formatter', 'Formatter/Compact',
 'Formatter/Compressed', 'Formatter/Crunched', 'Formatter/Debug',
 'Formatter/Expanded', 'Formatter/Nested', 'Formatter/OutputBlock',
 'Node', 'Node/Number', 'Parser', 'Type', 'Util', 'Version', 'Server');

foreach ($includes as $include) {
  $name = str_replace('/', '\\', $include);
  $classPath[$scssphpLibPrefix . $name] = $scssphpLibSrcPath . $include . '.php';
}

return $classPath;