<?php
/**
 * @author David Spreekmeester
 * @license http://opensource.org/licenses/BSD-3-Clause
 *
 */
class TaggedFileList extends ArrayObject {
	
	public function addNode(array $node = null) {
		if ($node) {
			$this[key($node)] = current($node);
		}
	}

}
