<?php
/**
 * @author David Spreekmeester
 * @license http://opensource.org/licenses/BSD-3-Clause
 *
 */
class OsxFileTagExtractor {
	protected $_ignorePaths = array('.', '..');


	public function listFilesWithTags($dirPath) {
		$Directory = new RecursiveDirectoryIterator($dirPath);
		$Iterator = new RecursiveIteratorIterator($Directory);

		foreach ($Iterator as $path) {
			$this->_processNode($path);
		}
	}

	protected function _processNode($path) {
		$filename = basename($path);

		if (in_array($filename, $this->_ignorePaths)) {
			return;
		}

		echo "\033[1;34mProcessing $filename\033[m\n";

		$command = $this->_composeAttrExtractionShellCommand($path);

		unset($xmlTags);
		exec($command, $xmlTags, $returnStatus);
		if ($returnStatus === 1) {
			return;
		}

		$xmlString = implode($xmlTags);

		$xml = new SimpleXMLElement($xmlString);
		$result = $xml->xpath('/plist/array');
		print_r($result[0]->string) . "\n";
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
