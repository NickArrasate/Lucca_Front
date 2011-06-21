<?php
class ItemOccurrence extends AppModel {
	var $name = 'ItemOccurrence';

	function moveLeft($occurrenceId, $from, $to) {
		$this->query(
			sprintf('
				UPDATE
					`item_occurrences` AS `ItemOccurrence`
				SET
					`ItemOccurrence`.`left` = `ItemOccurrence`.`left` - 2, `ItemOccurrence`.`right` = `ItemOccurrence`.`right` - 2
				WHERE
					`ItemOccurrence`.`occurrence_id` = %s AND
					`ItemOccurrence`.`left` > %s AND
					`ItemOccurrence`.`left` <= %s;',
				$occurrenceId, $from, $to
			)
		);
	}

	function moveRight($occurrenceId, $from, $to) {
		$this->query(
			sprintf('
				UPDATE
					`item_occurrences` AS `ItemOccurrence`
				SET
					`ItemOccurrence`.`left` = `ItemOccurrence`.`left` + 2, `ItemOccurrence`.`right` = `ItemOccurrence`.`right` + 2
				WHERE
					`ItemOccurrence`.`occurrence_id` = %s AND
					`ItemOccurrence`.`left` >= %s AND
					`ItemOccurrence`.`left` < %s;',
				$occurrenceId, $from, $to
			)
		);
	}

	function createItemOccurrences($itemId, $categoryId = 0, $subcategoryId = 0, $locationId = array()) {
		$this->Occurrence = ClassRegistry::init('Occurrence');

		$occurrencesList = array(
			array('category' => 0, 'subcategory' => 0, 'location' => 0)
		);

		if ($categoryId) {
			array_push($occurrencesList, array('category' => $categoryId, 'subcategory' => 0, 'location' => 0));
		}

		if ($subcategory) {
			array_push($occurrencesList, array('category' => 0, 'subcategory' => $subcategoryId, 'location' => 0));
			array_push($occurrencesList, array('category' => $categoryId, 'subcategory' => $subcategoryId, 'location' => 0));
		}

		foreach ($locationId as $location) {
			array_push($occurrencesList, array('category' => 0, 'subcategory' => 0, 'location' => $location));
			array_push($occurrencesList, array('category' => $categoryId, 'subcategory' => 0, 'location' => $locationId));
			array_push($occurrencesList, array('category' => 0, 'subcategory' => $subcategoryId, 'location' => $locationId));
			array_push($occurrencesList, array('category' => $categoryId, 'subcategory' => $subcategoryId, 'location' => $locationId));
		}

		$occurrencesIdList = array();
		foreach ($occurrencesList as $occurrenceCondition) {
			$occurrence = $this->Occurrence->find('first', array('conditions' => $occurrenceCondition));
			array_push($occurrencesIdList, $occurrence['Occurrence']['id']);
		}

		$occurrencesIdList = array_unique($occurrencesIdList);

		foreach ($occurrencesIdList as $occurrenceId) {
			$existItemUccerrence = $this->find('first', array('conditions' => array('ItemOccurrence.occurrence_id' => $occurrenceId, 'ItemOccurrence.item_id' => $itemId), 'order' => array('ItemOccurrence.left' => 'desc')));
			if (!$existItemUccerrence) {
				$maxItemUccerrence = $this->find('first', array('conditions' => array('ItemOccurrence.occurrence_id' => $occurrenceId), 'order' => array('ItemOccurrence.left' => 'desc')));
				$this->create();
				$this->set('item_id', $itemId);
				$this->set('occurrence_id', $occurrenceId);
				$this->set('left', $maxItemUccerrence['ItemOccurrence']['left'] + 2);
				$this->set('right', $maxItemUccerrence['ItemOccurrence']['right'] + 2);
				$this->save();
			}
		}
	}
}
?>