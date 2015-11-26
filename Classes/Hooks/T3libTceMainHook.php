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
		if (isset($parameters['cacheCmd']) === FALSE || $parameters['cacheCmd'] != 'system') {
			return;
		}
        if ($parentObject->admin || $parentObject->BE_USER->getTSConfigVal('options.clearCache.system')
            || ((boolean) $GLOBALS['TYPO3_CONF_VARS']['SYS']['clearCacheSystem'] === TRUE && $parentObject->admin)) {
			$absoluteWritePath = GeneralUtility::getFileAbsFileName('typo3temp/tx_adxscss/');
			GeneralUtility::rmdir($absoluteWritePath, TRUE);
        }
	}
}