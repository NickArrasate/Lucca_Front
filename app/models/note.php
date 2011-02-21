<?php
class Note extends AppModel {
	var $name = 'Note';
	var $useTable = 'note';
	var $hasMany = array(
		'Comments' => array(
			'className' => 'Note', 
			'foreignKey' => 'parent', 
			'conditions' => 'Comments.parent > 0 AND Comments.parent IS NOT NULL',
			'order' => array('Comments.created' => 'asc')
		)
	);
}
