<?php
require_once "lib/TaggedFileList.php";

class TaggedFileListTest extends PHPUnit_Framework_TestCase {
	public function testListShouldReactProperlyToAddingNodes() {
		$list = new TaggedFileList;

		$this->assertTrue(
			sizeof($list) === 0,
			'List should initially be empty'
		);

		$list->addNode(null);
		$this->assertTrue(
			sizeof($list) === 0,
			'List should stay empty after inserting null node'
		);

		$list->addNode(array('foo' => 'bar'));
		$this->assertTrue(
			sizeof($list) === 1,
			'List should grow after adding valid node'
		);

		$this->assertTrue(true);
	}
}
