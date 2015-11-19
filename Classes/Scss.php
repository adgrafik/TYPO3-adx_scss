<?php
namespace AdGrafik\AdxScss;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2015 Arno Dudek <webmaster@adgrafik.at>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

include __DIR__ . '/../Vendor/leafo/scssphp/scss.inc.php';

use TYPO3\CMS\Core\Utility\GeneralUtility;

class Scss implements \TYPO3\CMS\Core\SingletonInterface {

	/**
	 * @var \Leafo\ScssPhp\Parser $scss
	 */
	protected $scss;

	/**
	 * @var \TYPO3\CMS\Core\Cache\Frontend\VariableFrontend $cache
	 */
	protected $cache;

	/**
	 * @return void
	 */
	public function __construct() {
		$this->scss = new \Leafo\ScssPhp\Compiler();
		$this->cache = GeneralUtility::makeInstance('TYPO3\\CMS\\Core\\Cache\\CacheManager')->getCache('adx_scss');
	}

	/**
	 * Compiles a SCSS file or string.
	 *
	 * @param string $content		Absolute file or string.
	 * 								If $content is a file, the parsed file will be saved in the given 'writeDirectory' with the name format 'filename.sha1.css'.
	 * @param array $configuration
	 * @param mixed $saveAsFile
	 * @return string				Returns the parsed string if $saveAsFile is FALSE, else if it's TRUE this methode returns the new relative file name.
	 * 								If $saveAsFile is a string, the methode expected a file name and saves the parsed content there.
	 */
	public function compile($content, array $configuration, $saveAsFile = TRUE) {

		$returnUri = isset($configuration['returnUri']) ? $configuration['returnUri'] : TRUE;

		$cacheIdentifier = sha1($content);
		$cached = $this->getCachedContent($cacheIdentifier);
		if ($cached) {
			if ($returnUri === 'absolute') {
				return $cached;
			} else if ($returnUri === 'siteURL') {
				return str_replace(PATH_site, GeneralUtility::getIndpEnv('TYPO3_SITE_URL'), $cached);
			} else if ($returnUri) {
				return str_replace(PATH_site, '', $cached);
			} else if ($returnUri == FALSE) {
				return file_get_contents($cached);
			}
		}

		$absoluteContentPathAndFilename = GeneralUtility::getFileAbsFileName($content);
		$contentIsFile = @is_file($absoluteContentPathAndFilename);

		// Default cache directory is "typo3temp/".
		$absoluteWritePath = GeneralUtility::getFileAbsFileName('typo3temp/tx_adxscss/');
		GeneralUtility::mkdir($absoluteWritePath);

		// Get the target filename. If set the file will be written in the cacheDirectory with this filename appended with hash and suffix ".scss.css".
		// If is FALSE and the given $content is a file, the filename of $content will be used. If $content is not a file and targetFilename is not set, the filename will be "compliled.sha1.scss.css".
		$targetFilename = isset($configuration['targetFilename'])
			? $configuration['targetFilename']
			: NULL;
		if ($targetFilename) {
			$targetFilename = $targetFilename . '.' . $cacheIdentifier . '.scss.css';
		} else if ($contentIsFile) {
			$targetFilename = pathinfo($absoluteContentPathAndFilename, PATHINFO_FILENAME) . '.' . $cacheIdentifier . '.scss.css';
		} else {
			$targetFilename = 'compliled.' . $cacheIdentifier . '.scss.css';
		}

		// If "importPaths" is not an array, split it to an array.
		$importPaths = array();
		if (isset($configuration['importPaths'])) {
			if (is_array($configuration['importPaths'])) {
				$importPaths = $configuration['importPaths'];
			} else {
				$importPaths = (array) GeneralUtility::trimExplode(',', $configuration['importPaths']);
			}
		}
		if (isset($configuration['importPaths.']) && is_array($configuration['importPaths.'])) {
			$importPaths = array_merge($importPaths, $configuration['importPaths.']);
		}
		foreach ($importPaths as $importPath) {
			$importPath = GeneralUtility::getFileAbsFileName($importPath);
			$importPath = rtrim($importPath, '/') . '/';
			$this->scss->addImportPath($importPath);
		}

		// Set the formatter.
		$configuration['formatter'] = isset($configuration['formatter'])
			? $configuration['formatter']
			: NULL;
		switch ($configuration['formatter']) {
			case 'compact':
				$formatter = 'Compact';
				break;
			case 'compressed':
				$formatter = 'Compressed';
				break;
			case 'crunched':
				$formatter = 'Crunched';
				break;
			case 'debug':
				$formatter = 'Debug';
				break;
			case 'expanded':
				$formatter = 'Expanded';
				break;
			case 'output_block':
				$formatter = 'OutputBlock';
				break;
			default:
				$formatter = 'Nested';
				break;
		}
		$this->scss->setFormatter('Leafo\\ScssPhp\\Formatter\\' . $formatter);

		// Set the lineNumberStyle.
		$configuration['lineNumberStyle'] = isset($configuration['lineNumberStyle'])
			? $configuration['lineNumberStyle']
			: NULL;
		switch ($configuration['lineNumberStyle']) {
			case 'lineComments':
				$lineNumberStyle = \Leafo\ScssPhp\Compiler::LINE_COMMENTS;
				break;
			case 'debugInfo':
				$lineNumberStyle = \Leafo\ScssPhp\Compiler::DEBUG_INFO;
				break;
			default:
				$lineNumberStyle = NULL;
				break;
		}
		$this->scss->setLineNumberStyle($lineNumberStyle);

		// Set the numberPrecision.
		$numberPrecision = isset($configuration['numberPrecision'])
			? (integer) $configuration['numberPrecision']
			: 5;
		$this->scss->setNumberPrecision($numberPrecision);

		// Set variables.
		$variables = (isset($configuration['variables']) && is_array($configuration['variables']))
			? $configuration['variables']
			: (isset($configuration['variables.']) && is_array($configuration['variables.']))
				? $configuration['variables.']
				: NULL;
		if ($variables) {
			$this->scss->setVariables($variables);
		}

		// Check if $content is a file
		$absoluteContentPath = NULL;
		if ($contentIsFile) {
			$content = file_get_contents($absoluteContentPathAndFilename);
			$absoluteContentPath = rtrim(dirname($absoluteContentPathAndFilename), '/');
			$this->scss->addImportPath($absoluteContentPath);
		}

		$css = $this->scss->compile($content, $absoluteContentPath);

		// If $returnUri set to "absolute" the full path with filename will be returned, if TRUE then the relative path, else the CSS string will be returned.
		$absoluteWritePathAndFilename = $absoluteWritePath . $targetFilename;
		if ($returnUri === 'absolute') {
			$result = $absoluteWritePathAndFilename;
		} else if ($returnUri === 'siteURL') {
			$result = str_replace(PATH_site, GeneralUtility::getIndpEnv('TYPO3_SITE_URL'), $absoluteWritePathAndFilename);
		} else if ($returnUri) {
			$result = str_replace(PATH_site, '', $absoluteWritePathAndFilename);
		} else {
			$result = $css;
		}

		GeneralUtility::writeFile($absoluteWritePathAndFilename, $css);

		// Save cache depended on sha1 sum of parsed files.
		$parsedFiles = $this->scss->getParsedFiles();
		$cacheFiles = array();
		foreach ($parsedFiles as $parsedPathAndFilename) {
			$cacheFiles[$parsedPathAndFilename] = sha1_file($parsedPathAndFilename);
		}
		$this->cache->set('parsedFiles_' . $cacheIdentifier, $cacheFiles);
		$this->cache->set($cacheIdentifier, $absoluteWritePathAndFilename);

		return $result;
	}

	/**
	 * @param string $cacheIdentifier
	 * @return boolean
	 */
	protected function getCachedContent($cacheIdentifier) {

		// Check cached files first and parse again if one hash not matching.
		$parsedFiles = $this->cache->get('parsedFiles_' . $cacheIdentifier) ?: array();
		foreach ($parsedFiles as $parsedFile => $hash) {
			if (sha1_file($parsedFile) !== $hash) {
				return FALSE;
			}
		}

		$cached = $this->cache->get($cacheIdentifier);

		return @is_file($cached) ? $cached : FALSE;
	}

}
