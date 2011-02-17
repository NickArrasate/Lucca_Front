<p class="notifications">
<?php
	if(isset($email_result)) {
		foreach($email_result as $er) {
			echo $er . '<br/>';
		}
	}
?>
<?php
	if(isset($email_item_errors)) {
		foreach($email_item_errors as $e) {
			echo $e . '<br/>';
		}
	}
?>
</p>
