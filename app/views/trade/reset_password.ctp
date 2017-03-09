<?php
echo $this->element('versioned_css', array('files' => array('trade','footer-pages')));
?>
<div class="footerpage about">
    <div class="overlay">
        <div class="wrapper col-xs-12">
            <div class="row" id="trade">
                <div class="col-sm-6">
                        <h2>LUCCA TRADE RESET PASSWORD</h2>
						<?php echo $form->create('Trade', array(
							'type' => 'post',
							'url' => array('controller' => 'trade', 'action' => 'reset_password'),
							'class' => 'form',
							'style' => "border-left:1px solid #ccc;padding:0px 50px;margin-top:60px"
						));
						?>
                        <h4>Please enter a new password</h4>
						<?php 
							if($session->check('Message.flash')) {
								echo "<p>";
								$session->flash();
								echo "</p>";
							}
						?>
						<?php echo $form->input('trader_id', array(
								'type' => 'hidden',
								'value' => isset($trader_id) ? $trader_id : ''
							));
						?>
						<?php echo $form->input('restore_key', array(
								'type' => 'hidden',
								'value' => isset($restore_key) ? $restore_key : ''
							));
						?>
						<?php echo $form->input('password', array(
								'type' => 'password',
								'class' => 'form-control',
								'placeholder' => "Password",
								'div' => 'form-group',
								'label' => false,
								'value' => ''
							));
						?>
						<?php echo $form->input('password_confirm', array(
								'type' => 'password',
								'class' => 'form-control',
								'placeholder' => "Password Confirm",
								'div' => 'form-group',
								'label' => false,
								'value' => ''
							));
						?>
                        <div class="form-group">
                            <button type="submit" class="btn btn-lucca">Submit</button>
                        </div>
                    <?php echo $form->end(); ?>
                </div>
            </div>

        </div>
    </div>
    <div class="item-category-border"></div>
</div>
