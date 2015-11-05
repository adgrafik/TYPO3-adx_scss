<?php
namespace AdGrafik\AdxScss\Hooks;

use TYPO3\CMS\Core\DataHandling\DataHandler;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use AdGrafik\AdxScss\Utility\ScssUtility;

class T3libTceMainHook {

	/**
	 * @param array $parameters
	 * @param \TYPO3\CMS\Core\DataHandling\DataHandler $parentObject
	 * @return void
	 */
	function clearCachePostProc($parameters, DataHandler $parentObject) {

		if (isset($parameters['cacheCmd']) === FALSE) {
			return;
		}

		if ($parameters['cacheCmd'] == 'all') {
			$absoluteWritePath = GeneralUtility::getFileAbsFileName('typo3temp/tx_adxscss/');
			GeneralUtility::rmdir($absoluteWritePath, TRUE);
		}
	}
}