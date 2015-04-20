<?php
/**
 * @author David Spreekmeester
 * @license http://opensource.org/licenses/BSD-3-Clause
 * @requires TaggedFileList
 */
require_once "TaggedFileList.php";


class OsxFileTagExtractor {
	protected $_ignorePaths = array('.', '..');


	/**
 	 * Lists the files in a given directory, with their metatags.
 	 * @param $dirPath The directory path, with or without trailing slash.
 	 * @return TaggedFileList A list of the nodes (files / dirs) in the directory, with their tags.
 	 */
	public function listFilesWithTags($dirPath) {
		$list = new TaggedFileList;
		$Directory = new RecursiveDirectoryIterator($dirPath);
		$Iterator = new RecursiveIteratorIterator($Directory);

		foreach ($Iterator as $path) {
			$node = $this->_extractNodeTags($path);
			$list->addNode($node);
		}

		return $list;
	}

	/**
 	 * Processes a node (file or directory) in the directory and extracts the tags.
 	 * @return Array An associative array with filename as key, and a numeric array containing tags.
 	 */
	protected function _extractNodeTags($path) {
		$filename = basename($path);

		if (in_array($filename, $this->_ignorePaths)) {
			return;
		}

		$command = $this->_composeAttrExtractionShellCommand($path);

		$tags = $this->_executeXmlTagsExtractor($command);

		return array(
			$filename => $tags
		);
	}

	/**
 	 * Executes xml tag extractor, and converts output to plain array.
 	 * @param String $command The shell command to extract tags with.
 	 * @return Array An array with the tags, or null if no tags are available or there was an error.
 	 */
	protected function _executeXmlTagsExtractor($command) {
		unset($xmlTags);
		exec($command, $xmlTags, $returnStatus);
		if ($returnStatus === 1) {
			return;
		}

		$xmlString = implode($xmlTags);

		$xml = new SimpleXMLElement($xmlString);
		$result = $xml->xpath('/plist/array');

		return (array)$result[0]->string;
	}

	protected function _composeAttrExtractionShellCommand($path) {
		return
			'xattr -p com.apple.metadata:kMDItemOMUserTags "'
			. str_replace(
				array('"', '$'),
				array('\"', '\$'),
				$path
			)
			. '" 2> /dev/null | xxd -r -p'
			. '| plutil -convert xml1 -o - -'
		;
	}
}
