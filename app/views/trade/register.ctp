<?php
echo $this->element('versioned_css', array('files' => array('trade','footer-pages')));
?>
<div class="footerpage about">
    <div class="overlay">
        <div class="wrapper col-xs-12">
            <div class="row" id="trade">
                <div class="col-sm-6">
                    <h2>LUCCA TRADE ACCOUNT REGISTRATION</h2>
                    <p>Apply for a Lucca trade/wholesale account and receive:</p>
                    <ul>
                        <li>Access to trade pricing on tear sheets</li>
                        <li>Online access to inventory for immediate in-stock ordering</li>
                        <li>New product updates</li>
                        <li>New store openings and events</li>
                        <li>Career opportunities</li>
                        <li><a href="/trade/custom">Custom request form for Lucca Studio products</a></li>
                    </ul>

                </div>
                <div class="col-sm-6">
						<?php echo $form->create('Trade', array(
							'type' => 'post',
							'url' => array('controller' => 'trade', 'action' => 'register'),
							'class' => 'form',
							'style' => "border-left:1px solid #ccc;padding:0px 50px;margin-top:60px"
						));
						?>
                        <h4>Please fill out the form below to join the Lucca Trade program</h4>
						<?php 
							if($session->check('Message.flash')) {
								echo "<p>";
								$session->flash();
								echo "</p>";
							}
						?>
						<?php echo $form->input('name', array(
								'type' => 'text',
								'class' => 'form-control',
								'placeholder' => "Name",
								'div' => 'form-group',
								'label' => false
							));
						?>
						<?php echo $form->input('company_name', array(
								'type' => 'text',
								'class' => 'form-control',
								'placeholder' => "Company Name",
								'div' => 'form-group',
								'label' => false
							));
						?>
						<?php echo $form->input('address', array(
								'type' => 'text',
								'class' => 'form-control',
								'placeholder' => "Address",
								'div' => 'form-group',
								'label' => false
							));
						?>
						<?php echo $form->input('phone', array(
								'type' => 'text',
								'class' => 'form-control',
								'placeholder' => "Phone #",
								'div' => 'form-group',
								'label' => false
							));
						?>
						<?php /* echo $form->input('resale', array(
								'type' => 'text',
								'class' => 'form-control',
								'placeholder' => "Resale #",
								'div' => 'form-group',
								'label' => false
							)); */
						?>
						<?php echo $form->input('email', array(
								'type' => 'text',
								'class' => 'form-control',
								'placeholder' => "Email Address",
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
                            <a href="/trade/login" class="btn btn-lucca-link pull-right">Already have an account?</a>
                        </div>
                    <?php echo $form->end(); ?>
                </div>
            </div>

        </div>
    </div>
    <div class="item-category-border"></div>
</div>
