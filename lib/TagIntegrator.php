<?php
/**
 * @author David Spreekmeester
 * @license http://opensource.org/licenses/BSD-3-Clause
 *
 */
class TagIntegrator {
	const TAG_SIGN = '@';
	const SEPARATOR = '';

	protected $_path;


	public function __construct($path) {
		$this->_path = $path;
	}


	public function integrateTags(TaggedFileList $list) {
		foreach ($list as $filename => $tags) {
			$this->_processFile($filename, $tags);
		}

	}

	protected function _processFile($filename, array $tags = null) {
		if (!$tags) {
			return;
		}

		$tagString = $this->_generateTagString($tags);
		$content = $this->_getContent($filename);
		$content = $tagString . "\n\n" . $content;

		$bytesWritten = $this->_putContent($filename, $content);
		
		echo $bytesWritten . ' bytes written into ' . $filename . ".\n";
	}

	protected function _getContent($filename) {
		return file_get_contents($this->_path . $filename);
	}

	protected function _putContent($filename, $content) {
		return file_put_contents($this->_path . $filename, $content);
	}

	protected function _generateTagString(array $tags = null) {
		if (!$tags) {
			return;
		}

		$output = self::TAG_SIGN;
		$output .= implode(
			self::SEPARATOR . ' ' . self::TAG_SIGN,
			$tags
		);

		return $output;
	}
}
