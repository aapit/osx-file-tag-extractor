<?php
/**
 * @author David Spreekmeester
 * @license http://opensource.org/licenses/BSD-3-Clause
 *
 */
require_once 'lib/OsxFileTagExtractor.php';
require_once 'lib/TagIntegrator.php';

if (!array_key_exists(1, $argv)) {
	echo "Please provide the path to process as the first argument";
	exit(1);
}

$path = $argv[1];

$extractor = new OsxFileTagExtractor();
$list = $extractor->listFilesWithTags($path);
$integrator = new TagIntegrator($path);
$integrator->integrateTags($list);

