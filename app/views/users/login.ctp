
<div class="login">
	<?php echo $form->create('User',array('action'=>'login'));?>
	<fieldset>
 		<legend>Enter Your Username and Password</legend>
		<?php
		echo $form->input('username');
		echo $form->input('password');
		?>
		<div class="input buttons">
			<button type="submit" name="data[User][login]" value="login">Login</button>
		</div>
	</fieldset>
<?php echo $form->end();?>
</div>
