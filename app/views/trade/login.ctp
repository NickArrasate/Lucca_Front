<?php
echo $this->element('versioned_css', array('files' => array('trade','footer-pages')));
?>
<script type="text/javascript" src="/js/login.js"></script>
<div class="footerpage about">
    <div class="overlay">
        <div class="wrapper col-xs-12">
            <div class="row" id="trade">
                <div class="col-sm-6">
                    <h2>LUCCA TRADE ACCOUNT</h2>
                    <p>Lucca trade/wholesale account gives access to:</p>
                    <ul>
                        <li>Trade pricing on tear sheets</li>
                        <li>Online access to inventory for immediate in-stock ordering</li>
                        <li>New product updates</li>
                        <li>New store openings and events</li>
                        <li>Career opportunities</li>
                        <li><a class="btn-lucca-link" href="/trade/custom">Custom request form for Lucca Studio products</a></li>
                    </ul>
                    <a class="btn btn-lucca" style="margin-left:20px" href="/trade/register">Register for a Trade Account</a>
                </div>
                <div class="col-sm-6">
						<?php echo $form->create('Trade', array(
							'type' => 'post',
							'url' => array('controller' => 'trade', 'action' => 'login'),
							'class' => 'form',
							'style' => "border-left:1px solid #ccc;padding:0px 50px;margin-top:60px"
						));
						?>
                        <h4>Login to Trade Account</h4>
						<?php 
							if($session->check('Message.flash')) {
								echo "<p>";
								$session->flash();
								echo "</p>";
							}
						?>
						<?php echo $form->input('email', array(
								'type' => 'text',
								'class' => 'form-control',
								'placeholder' => "Email",
								'div' => 'form-group',
								'label' => false
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
                        <div class="form-group">
                            <button type="submit" class="btn btn-lucca">Submit</button>
							<a href="#" class="btn btn-lucca-link pull-right" data-toggle="modal" data-target="#forgot_password">Forgot your password?</a>
                        </div>
                    <?php echo $form->end(); ?>
                </div>
            </div>

        </div>
    </div>
    <div class="item-category-border"></div>

	<div class="modal fade" id="forgot_password" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Forgot your password?</h4>
				</div>

				<div class="modal-body">
					<p id="forgot_form_message">Enter your email address below to reset your password.  </p>
					<?php 
						echo $form->create('Trade', array(
							'type' => 'post',
							'class' => 'form',
							'style' => "margin-top:20px"
						));
						echo $form->input('email', array(
							'id' => 'email',
							'type' => 'text',
							'class' => 'form-control',
							'placeholder' => "Email",
							'div' => 'form-group',
							'label' => false
						));
						echo $form->end();
					?>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-lucca" id="send_email">Send Reset Email</button>
				</div>
			</div>
		</div>
	</div>
	
</div>
