
# TYPO3-adx_scss

## Overview

This TYPO3 extension contains the SCSS compiler https://github.com/leafo/scssphp which is compatible with Bootstrap 4.x.

- Supports a hook for \TYPO3\CMS\Core\Page\PageRenderer which compiles SCSS files for `includeCSS`
- a function for USER-cObject
- hooks for rtehtmlarea, tinymce_rte and tinymce4_rte
- and a ViewHelper for Fluid.

To delete or regenerate compiled files proceed the "Flush general caches".


## TypoScript

### TypoScript properties

property | description | type | default
-------- | ----------- | ---- | -------
variables | Array of variables which schould be included to the compiler. Set the variable name as key and without `@`. | `array` | `NULL`
importPaths | Comma seperated string and/or array of directories where should be look at `@import`. | `array|string` | `NULL`
targetFilename | If set the compiler will save the file with this name. | `string` | `NULL`
returnUri | If the keyword `absolute` is set, the compiler returns the absolute path to the file. If set to `siteURL` it returns the complete URL with `TYPO3_SITE_URL`. If `TRUE` the returned value is the relative path, else if `FALSE` it will return the parsed content. | `boolean|string` | `NULL`
formatter | Set the formatter. Options: `Nested`, `Compressed`, `Compact`, `Crunched`, `Debug`, `Expanded`, `OutputBlock` | `string` | `Nested`
numberPrecision | Set the numberPrecision | `integer` | 5
lineNumberStyle | Set the lineNumberStyle. Options: `lineComments`, `debugInfo` | `string` | `NULL`


### Configuration

	plugin.tx_adxscss {
	
		# If set the compiler will save the file with this name.
		targetFilename = 
	
		# Comma seperated string or array with x = directory.
		importPaths = 
	
		# Set the formatter. Options: Nested (default), Compressed, Compact, Crunched, Debug, Expanded, OutputBlock
		formatter = 
	
		# Set the numberPrecision (default: 5)
		numberPrecision = 5
	
		# Set the lineNumberStyle Options: 0 (default), lineComments, debugInfo
		lineNumberStyle = 0
	
		variables {
			# Place your own variables here.
		}
	}


### USER-cObject

	page.headerData.1367742474 = COA
	page.headerData.1367742474 {
	
		# Set the USER content object where you want. The function includeCss will generate the CSS file and append it with the PageRenderer.
		10 = USER
		10.userFunc = AdGrafik\AdxScss\Utility\ScssUtility->includeCss
		10.compilerSettings =< plugin.tx_adxscss
		10.includeCssSettings {
			media = print
		}
		10.file = path/to/my/style-file.scss
		10.data (
	$nice-blue: blue;
	body {
	  border: 1px solid $nice-blue;
	}
	)
	}


## ViewHelper

Returns parsed SCSS as CSS.

	{namespace scss=AdGrafik\AdxScss\ViewHelpers}
	<scss:compile data="[string]" variables="[array]" />
	<scss:compileAndInclude data="[string]" variables="[array]" />


### ViewHelper properties

#### for scss:compile

property | description | type | default
-------- | ----------- | ---- | -------
data | SCSS data or path and filename to the SCSS file. | `string` | `NULL`
variables | Array of variables which schould be included to the compiler. Set the variable name as key and without `@`. | `array` | `NULL`
importPaths | Comma seperated string and/or array of directories where should be look at `@import`. | `array|string` | `NULL`
targetFilename | If set the compiler will save the file with this name. | `string` | `NULL`
returnUri | If the keyword `absolute` is set, the compiler returns the absolute path to the file. If set to `siteURL` it returns the complete URL with `TYPO3_SITE_URL`. If `TRUE` the returned value is the relative path, else if `FALSE` it will return the parsed content. | `boolean|string` | `NULL`
formatter | Set the formatter. Options: `Nested`, `Compressed`, `Compact`, `Crunched`, `Debug`, `Expanded`, `OutputBlock` | `string` | `Nested`
numberPrecision | Set the numberPrecision | `integer` | 5
lineNumberStyle | Set the lineNumberStyle. Options: `lineComments`, `debugInfo` | `string` | `NULL`


#### additinally for scss:compileAndInclude

property | description | type | default
-------- | ----------- | ---- | -------
includeCssSettings | Same as TYPO3 property `page.includeCss` but without `stdWrap`. | `array` | `NULL`


### Examples

XML/HTML:

	<scss:compile data="div { color: $color; }" variables="{ color: 'red' }" />

Output:

	div { color: red; }

XML/HTML:

	<scss:compileAndInclude variables="{ color: 'red' }" includeCssOptions="{ media: 'print' }">
		div {
			color: $color;
		}
	</scss:compile>

Output:

	<link rel="stylesheet" type="text/css" href="typo3temp/tx_adxscss/compliled.06038bd94eba2da1b219679e2fb8c822-58c67ef417c6c12e5afbe77144770bc9.scss.css" media="print">

Include the css file into the page via `PageRenderer`.


## Hooks

### PageRenderer

	page.includeCSS.styles = path/to/my/style-file.scss


### tinymce_rte

	RTE.default.init.content_css = path/to/my/style-file.scss

or append multiply files

	RTE.default.init.content_css = path/to/my/style-file-1.scss,path/to/my/style-file-2.scss,path/to/my/style-file-3.scss


### tinymce4_rte

	RTE.default.contentCSS = path/to/my/style-file.scss

or append multiply files by comma seperated string

	RTE.default.contentCSS = path/to/my/style-file-1.scss,path/to/my/style-file-2.scss,path/to/my/style-file-3.scss

or append multiply files by key

	RTE.default.contentCSS {
		file1 = path/to/my/style-file-1.scss
		file2 = path/to/my/style-file-2.scss
		file3 = path/to/my/style-file-3.scss
	}


### rtehtmlarea

	RTE.default.init.contentCSS = path/to/my/style-file.scss


## Using in extensions

	// Create object
	$scss = GeneralUtility::makeInstance('AdGrafik\\AdxScss\\Scss');
	// Fetch extension configuration. Allowed parameters are cObject or PID.
	$configuration = AdGrafik\AdxScss\Utility\ScssUtility::getConfiguration($GLOBALS['TSFE']->cObj);
	// Compile the SCSS file. Will return the filepath of the parsed SCSS file.
	$pathAndFilename = 'path/to/my/style-file.scss';
	$compiledPathAndFilename = $scss->compile($pathAndFilename, $configuration);