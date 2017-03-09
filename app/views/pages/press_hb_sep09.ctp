<?php
	$javascript->link('easy-tabs', false);
	$javascript->link('jquery', false);
	$javascript->link('jquery.easing.1.3', false);
	$javascript->link('jquery.fancybox-1.2.1.pack', false);
	
	echo $html->css('footer-pages');
	echo $html->css('jquery.fancybox');
	echo $html->css('easy-tabs');
	
	$this->pageTitle = 'Lucca Antiques - House Beautiful September 2009';
?>
<div class="footerpage press">
	<div class="overlay">
		<div class="wrapper">
		<h2>Press</h2>
					<div class="nav">
						<dl>
							<dd><a href="/pages/press/">-- Santa Barbara (2010)</a></dd>
							<dd class="current-press-item"><a href="/pages/press_hb_sep09/">-- House Beautiful (September 2009)</a></dd>							
							<dd><a href="/pages/press_hb_aug09/">-- House Beautiful (August 2009)</a></dd>
							<dd><a href="/pages/press_ed_may09/">-- Elle D&eacute;cor May (May 2009)</a></dd>
                            <dd><a href="/pages/press_hb_aug08/">-- House Beautiful (August 2008)</a></dd>
						</dl>
					</div>
					<div class="details">
						<h3>House Beautiful (September 2009)</h3>
						<p>Bench, Lucca Antiques</p>
						<p class="tabs">
							<span onclick="easytabs('1', '1');" onfocus="easytabs('1', '1');" id="tablink1"><a onclick="return false;" href="#" title="">1</a></span>
							<span onclick="easytabs('1', '2');" onfocus="easytabs('1', '2');" id="tablink2"><a onclick="return false;" href="#" title="">2</a></span>
							<span style="display:none;" onclick="easytabs('1', '3');" onfocus="easytabs('1', '3');" id="tablink3"><a onclick="return false;" href="#" title="">3</a></span>
						</p>
						
						<?php $settings = array('w'=>527, 'crop'=>1); ?>
                        
          
          				<!--Start Tabcontent 1 -->
						<div id="tabcontent1">
						<a class="image" href="/press/images/hb_sep09/1_HBSept_cover.jpg">
						<img src="<?=$resizeimage->resize(WWW_ROOT . '/press/images/hb_sep09/1_HBSept_cover.jpg', $settings)?>" border="0" />
						</a>
						</div>
						<!--End Tabcontent 1-->

						<!--Start Tabcontent 2-->
						<div id="tabcontent2">
						<a class="image" href="/press/images/hb_sep09/2_HBSept_p79.jpg">
						<img src="<?=$resizeimage->resize(WWW_ROOT . '/press/images/hb_sep09/2_HBSept_p79.jpg', $settings)?>" border="0" />
						</a>
						</div>
						<!--End Tabcontent 2 -->

						<!--Start Tabcontent 3-->
						<div id="tabcontent3">
						<a class="image" href=""><img src="images/#.jpg" width="527" alt="" /></a>
						</div>
						<!--End Tabcontent 3-->
	
						
					</div>

		</div>
	</div>
	<div class="item-category-border"></div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$("a.image").fancybox();
	});
</script>