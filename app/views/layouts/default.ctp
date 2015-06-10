<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		<meta charset="utf-8"></meta>
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="/apple-touch-icon.png" rel="apple-touch-icon" />
        <link href="/apple-touch-icon-76x76.png" rel="apple-touch-icon" sizes="76x76" />
        <link href="/apple-touch-icon-120x120.png" rel="apple-touch-icon" sizes="120x120" />
        <link href="/apple-touch-icon-152x152.png" rel="apple-touch-icon" sizes="152x152" />
		<meta name="description" content="Lucca Antiques provides a valuable resource for designers and celebrity clients seeking pieces that are unique in scale, composition and origin. A constantly changing inventory assures that the merchandise is always fresh and the eclectic mix of elements makes shopping at Lucca exciting.">
        <meta name="keywords" content="antiques, lucca, tables, chairs, vintage, hollywood, designer, custom, unique" />
	<title><?php echo $title_for_layout?></title>
	<?php echo $scripts_for_layout; ?>

	<script type="text/javascript" src="/js/jquery.min.js"></script> 
	<script type="text/javascript" src="/js/jquery.easing-1.3.pack.js"></script> 
	<script type="text/javascript" src="/js/jquery.fancybox-1.3.0.pack.js"></script> 
	<script type="text/javascript" src="/js/jquery.lightbox-0.5.js"></script> 
	<script type="text/javascript" src="/js/jquery.hp-slideshow.js"></script>
	<script type="text/javascript" src="/js/search.js"></script>
	<script type="text/javascript" src="/js/menus.js"></script>
	<script type="text/javascript" src="/js/flash_message.js"></script>
	<script type="text/javascript" src="/js/cloudzoom/cloudzoom.js"></script>
	<script type="text/javascript" src="/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/3.9.3/lodash.min.js"></script>
    <link type="text/css" rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" />
    <?php
    echo $this->element('versioned_css', array(
        'files' => array('base', 'menus', 'jquery.lightbox-0.5', 'jquery.fancybox-1.3.0', 'hp-slideshow')
    ));
    ?>

	<!-- Include Cloud Zoom script. -->

	<script type="text/javascript">
		$(document).ready(function(){
			$("#SearchAddForm label").click(function(){
				$("#SearchAddForm").submit();
			});
		});
	</script>
	</head>

	<body>
		<div class="container">
		    <?php if($title_for_layout !== "Lucca Antiques"){ ?>
			<!-- <dl><dd class="cart"><a href="/orders/view/">View Cart (<?php if(isset($cart_count)) { echo $cart_count; } else { echo '0';} ?>)</a></dd></dl> -->
			<?php } ?> 
			<dl class="header">
				<dd style="text-align:center">
                    <a href="/"><img src="/img/logoboxtop.png" style="width:175px" alt="Lucca Antiques" class="active"/></a>
                </dd>
                <dd>
                    <?php if(!$session->check('Trade') && !$session->check('User')) {?>
                        <a href="/trade/login" class="pull-right" style="color: #9A9B9C">Trade Sign In</a>
                    <?php } elseif($session->check('Trade')) { ?>
                        <a href="/trade/logout" class="pull-right" style="color: #9A9B9C">Logout</a>
                    <?php } ?>
                </dd>
				<dd>
					<!--
					<dl class="nav<?php if(isset($current_item_type_id)) { echo '-'. $current_item_type_id;} if(isset($item_type_id)) { echo '-'. ($item_type_id);} ?>">
						<dd class="first"><a href="/item/grid/1/all/"><span>Lighting</span></a></dd>
						<dd class="second"><a href="/item/grid/2/all/"><span>Seating</span></a></dd>
						<dd class="third"><a href="/item/grid/3/all/"><span>Tables</span></a></dd>
						<dd class="fourth"><a href="/item/grid/4/all/"><span>Wall Decor</span></a></dd>
						<dd class="fifth"><a href="/item/grid/5/all/"><span>Case Goods</span></a></dd>
						<dd class="sixth"><a href="/item/grid/6/all/"><span>Garden &amp; More</span></a></dd>
						<dd class="seventh"><a href="/item/grid/all/all/"><span>All Inventory</span></a></dd>
					</dl>
					-->
					<div class="menu-block navbar navbar-default">
						<div class="navbar-header">
							<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#menu-navbar">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
						</div>
						<div class="collapse navbar-collapse" id="menu-navbar">
							<ul class="menus nav navbar-nav">
							<?php 
									/*$i = 0;
								while ($i<6){
								$value=each($item_types);
									$i++;*/
								?>
								<?php foreach($item_types['base_types'] as $item):?>
								<li class="menu">
									<a href="/item/grid/<?php echo ($item['id']) ?>/all/all" <?php if (isset($current_item_type_id)&&($current_item_type_id == $item['id'])) echo 'class="selected"'; ?> title="<?php echo $item['name'];?>"><?php echo $item['name'] ?></a>
								</li>
								<?php endforeach;?>
								<?php if(!empty($item_types['over_base_types'])):?>
								<li class="menu6">
									<div class="locations">
										<a href="#" title="Objects">Objects</a>
										<div class="pulldown">
											<ul class="submenus">
											<?php /*while (current($item_types)){
												$value=each($item_types);*/
								?>
												<?php foreach($item_types['over_base_types'] as $item):?>
												<li class="submenu">
													<a href="/item/grid/<?php echo ($item['id']) ?>/all/all" title="<?php echo $item['name'];?>"><?php echo $item['name']; ?></a>
												</li>
												<?php endforeach;?>
											</ul>
										</div>
									</div>
								</li>
								<?php endif;?>
								<li class="menu dropdown">
									<div class="locations">
										<a href="/item/grid/all/all/all" class="dropdown-toggle <?php if (isset($current_item_type_id) && $current_item_type_id == 0) echo 'selected'; ?>" data-toggle="dropdown" title="All Inventory">All Inventory <span class="caret"></span></a>
										<ul class="submenus dropdown-menu" role="menu">
											<?php foreach($list_location_menu as $location_id => $display_name) {
                                                    if($location_id == "3"){ continue; }
													echo $html->tag('li', $html->link($display_name, "/item/grid/all/all/" . $location_id, array('title' => "")), array('class' => "submenu" . $location_id));
											} ?>
										</ul>
									</div>
								</li>
								<li class="search">
										<div class="searchform <?php echo (empty($searchString)) ? "disabled" : "enabled"; ?>">
											<?php echo $form->create('Search', array('url' => array('controller' => 'item', 'action' => 'search'))); ?>
												<?php echo $form->text('Search.item', array('value' => $searchString)); ?>
												<?php echo $form->label('Search.item', 'Search'); ?>
											<?php echo $form->end(); ?>
										</div>
								</li>
							</ul>
						</div>
					</div>
				</dd>
			</dl>

			<?php echo $content_for_layout ?>

			<dl class="footer">
				<dd class="first"><a href="/"><span>home</span></a></dd>
				<dd class="second"><a href="/pages/about/"><span>about</span></a></dd>
				<dd class="fourth"><a href="/pages/disclosure/"><span>disclosure</span></a></dd>
				<dd class="fifth"><a href="/pages/contact/"><span>contact</span></a></dd>
				<dd class="sixth"><a href="/pages/press/"><span>press</span></a></dd>
                <dd class="sixth"><a href="/pages/careers/"><span>careers</span></a></dd>
				<dd class="seventh"><span> &#169; <?php echo date('Y')?> Lucca Antiques</span></dd>
			</dl>
		</div>
		<!-- <p class="design-credits"><a href="http://www.btrax.com">web design by btrax, Inc.</a></p> -->

        <div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="border-bottom:none">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>

                    <div class="modal-body" style="padding:0px 0px 0px 30px">
                        <div class="row" id="trade">
                            <div class="col-sm-6">
                                <h2>LUCCA TRADE ACCOUNT</h2>
                                <p style="font-family: vinchandregular; font-size:30px">Lucca trade/wholesale account gives access to:</p>
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
                                <p style="font-family: vinchandregular; font-size:30px;padding-top:10px">Login to Trade Account</p>
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
                    <div class="modal-footer" style="border-top:none">
                        &nbsp;
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">
//            $(document).ready(function(){
//                var cook = $.cookie('splash');
//                if(_.isEmpty(cook)) {
//                    $.cookie('splash','shown',{ expires: 365, path: '/' });
//                    $('#login-modal').modal('show');
//                }
//            });
        </script>

        <div class="modal fade" id="forgot_password" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <span style="font-family: vinchandregular; font-size:30px;padding-top:10px">Forgot password?</span>
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
                        <button type="button" class="btn btn-lucca" id="send_email"><span id="spiner" class="hidden glyphicon glyphicon-refresh glyphicon-spin"></span> Send Reset Email</button>
                    </div>
                </div>
            </div>
        </div>
		
		<script type="text/javascript">
		var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
		document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
		</script>
		<script type="text/javascript">
		try {
		var pageTracker = _gat._getTracker("UA-12063725-1");
		pageTracker._trackPageview();
		} catch(err) {}</script>

	</body>
	
</html> 

</body>
</html>
