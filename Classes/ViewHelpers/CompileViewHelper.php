<?php
namespace AdGrafik\AdxScss\ViewHelpers;

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

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class CompileViewHelper extends AbstractViewHelper {

	/**
	 * @param string $data
	 * @param array $variables
	 * @param mixed $importPaths Comma seperated string or array with x = directory.
	 * @param string $targetFilename
	 * @param mixed $returnUri
	 * @param string $formatter Set the formatter: Options: 0 (default) is normal formatting, nested, compressed
	 * @param integer $numberPrecision Set the numberPrecision (default: 5)
	 * @param string $lineNumberStyle Set the lineNumberStyle Options: 0 (default), lineComments, debugInfo
	 * @return string
	 * @api
	 */
	public function render($data = NULL, $variables = NULL, $importPaths = NULL, $targetFilename = NULL, $returnUri = TRUE, $formatter = NULL, $numberPrecision = NULL, $lineNumberStyle = NULL) {

		if ($data === NULL) {
			$data = $this->renderChildren();
			if ($data === NULL) {
				return '';
			}
		}

		$configuration = array(
			'variables' => $variables,
			'importPaths' => $importPaths,
			'targetFilename' => $targetFilename,
			'returnUri' => $returnUri,
			'formatter' => $formatter,
			'numberPrecision' => $numberPrecision,
			'lineNumberStyle' => $lineNumberStyle,
		);

		$scss = GeneralUtility::makeInstance('AdGrafik\\AdxScss\\Scss');
		$content = $scss->compile($data, $configuration);

		return $content;
	}
}

?>