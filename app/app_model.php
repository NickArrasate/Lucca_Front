<?php
class AppModel extends Model {
	function afterFind($results, $primary=false) {
		if ($primary == true) {
			if (Set::check($results, '0.0')) {
				$fields = array_keys( $results[0][0] );
				foreach($results as $key => $value) {
					foreach( $fields as $fieldName ) {
						$results[$key][$this->alias][$fieldName] = $value[0][$fieldName];
					}
					unset($results[$key][0]);
				}
			}
		}

		return $results;
	}
}
?>