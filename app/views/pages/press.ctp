<?php
	$javascript->link('easy-tabs', false);
	$javascript->link('jquery', false);
	$javascript->link('jquery.easing.1.3', false);
	$javascript->link('jquery.fancybox-1.2.1.pack', false);
	$javascript->link('jquery.lightbox-0.5', false);
	
	
	echo $html->css('footer-pages');
	echo $html->css('jquery.fancybox');
	echo $html->css('easy-tabs');
	echo $html->css('jquery.lightbox-0.5');
	
	$this->pageTitle = 'Lucca Antiques - House Beautiful August 2008';
?>

<div class="footerpage press">
	<div class="overlay">
		<div class="wrapper">
			<div id="CoversBox">
				<a href="/press/images/sb_10/lighting_FM10.jpg" class="lightbox" title="Lighting">
					<div id="CoverObject">
						<div id="CoverObjectImgWrap">
							<img src="/press/images/sb_10/SB_FebMar10-150.jpg" alt=""></img>
						</div>
						<h5 class="MagTitle">Santa Barbara<br />March 2010</h5>
					</div>
				</a>
				<a href="/press/images/sc_feb10/sc-500.jpg" class="lightbox" title="Wood Circle">
					<div id="CoverObject">
						<div id="CoverObjectImgWrap">
							<img src="/press/images/sc_feb10/sc-150.jpg" alt=""></img>
						</div>
					<h5 class="MagTitle">Style Compass<br />2010<span style="font-size:10px"></span></h5>
					</div>
				</a>
				<a href="/press/images/hb_feb10/hb_feb10-500.jpg" class="lightbox" title="Chandelier">
					<div id="CoverObject">
						<div id="CoverObjectImgWrap">
							<img src="/press/images/hb_feb10/hb_feb_10-150.jpg" alt=""></img>
						</div>
						<h5 class="MagTitle">House Beautiful<br />February 2010<span style="font-size:10px"></span></h5>
						
					</div>
				</a>
				<a href="/press/images/ed_jan10/ed_jan10-550.jpg" class="lightbox" title="Table">
					<div id="CoverObject">
						<div id="CoverObjectImgWrap">
							<img src="/press/images/ed_jan10/ed_jan10-150.jpg" alt=""></img>
						</div>
						<h5 class="MagTitle">Elle D&eacute;cor<br />January 2010</h5>
					</div>
				</a>
				<a href="/press/images/hb_sep09/2_HBSept_p79.jpg" class="lightbox" title="Bench">
					<div id="CoverObject">
						<div id="CoverObjectImgWrap">
							<img src="/press/images/hb_sep09/1_HBSept_cover-200.jpg" alt=""/>
						</div>
						<h5 class="MagTitle">House Beautiful<br />September 2009</h5>		
					</div>
				</a>
				<a href="/press/images/hb_aug09/2_HBAug_pg62-63.jpg" class="lightbox" title="Lamp">
					<div id="CoverObject">
						<div id="CoverObjectImgWrap">
							<img src="/press/images/hb_aug09/1_HBAug_Cover-200.jpg" alt=""/>	
						</div>
						<h5 class="MagTitle">House Beautiful<br />August 2009</h5>
					</div>
				</a>
				<a href="/press/images/ed_may09/2_ed_may09.jpg" class="lightbox" title="Chairs">
					<div id="CoverObject">
						<div id="CoverObjectImgWrap">
							<img src="/press/images/ed_may09/1_ed_may09-200.jpg" alt=""/>
						</div>
						<h5 class="MagTitle">Elle D&eacute;cor<br />May 2009</h5>
					</div>
				</a>
				<a href="/press/images/hb_aug08/1_hb_aug08.jpg" class="lightbox" title="Chairs">
					<div id="CoverObject">
						<div id="CoverObjectImgWrap">
							<img src="/press/images/hb_aug08/1_hb_aug08-200.jpg" alt=""/>
						</div>
						<h5 class="MagTitle">House Beautiful<br />August 2008</h5>					
					</div>
				</a>
			</div>
		</div>
	</div>
<div class="item-category-border"></div>
</div>

<script type="text/javascript">
$(function() {
	$('#CoversBox a').lightBox(); // Select all links with lightbox class
});
</script>
