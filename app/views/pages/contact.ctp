
<?php
	echo $this->element('versioned_css', array('files' => 'footer-pages'));
	$this->pageTitle = 'Lucca Antiques - Contact Hollywood and New York Locations';
?>
<div class="footerpage">
	<div class="overlay">
		<div class="wrapper col-xs-12">
			<h2>Contact Lucca Antiques</h2>
			<p>Thank you for visiting the Lucca Antiques web site. If you require additional information please contact us directly. </p>
						
            <div class="contactLeft">
                <img src="/img/LA-opt.jpg" height="200" />
                <dl>
                    <dt>Los Angeles Store:</dt>
                    <dd>Lucca Antiques</dd>
                    <dd>744 N. La Cienega Blvd.</dd>
                    <dd>Los Angeles, CA 90069  </dd>
                    <dd><a target="_blank" href="http://maps.google.com/maps?f=q&source=s_q&hl=en&geocode=&q=Lucca+Antiques&sll=34.09361,-118.376484&sspn=0.083304,0.110378&ie=UTF8&hq=Lucca+Antiques&hnear=&ll=34.094321,-118.376484&spn=0.083303,0.110378&z=13&iwloc=A">Click for Map</a></dd>
                    <dd>Phone: 310-657-7800</dd>
                    <dd>Fax: 310-657-7804</dd>
                    <dd>Email: <a href="mailto:<?php echo LA_EMAIL?>"><?php echo LA_EMAIL?></a></dd>
                    
                </dl>
                <dl>
                    <dt>Los Angeles Store Hours:</dt>
                    <dd>Monday - Friday: 10:00am - 6:00pm</dd>
                </dl>
			</div>
            
			<div class="contactLeft">
                <img src="/img/NY-opt.jpg" height="200" />
                <dl>
                    <dt>New York Store:</dt>
                    <dd>Lucca Antiques</dd>
                    <dd>306 E. 61st Street (between 1st and 2nd Avenue)</dd>
                    <dd>4th Floor</dd>
                    <dd>New York, NY 10065</dd>
                    <dd><a target="_blank" href="http://maps.google.com/maps?f=q&source=s_q&hl=en&geocode=&q=306+E+61st+Street+New+York,+NY">Click for Map</a></dd>
                    <dd>Phone: 212-343-9005</dd>
                    <dd>Fax:   212-343-9006</dd>
                    <dd>Email: <a href="mailto:<?php echo NY_EMAIL?>"><?php echo NY_EMAIL?></a></dd>
                </dl>
                <dl>
                    <dt>New York Store Hours:</dt>
                    <dd>Monday - Friday: 10:00am - 6:00pm</dd>
                </dl>
			</div>
            
            <div class="contactBottom" style="display:none">
                <dl>
                    <dt>Santa Barbara Store: </dt>
                    <dd>By Appointment</dd>
                    <dd>Phone: <?php echo WH_PHONE?></dd>
                    <dd>Email: <a href="mailto:<?php echo WH_EMAIL?>"><?php echo WH_EMAIL?></a></dd>
                </dl>
            </div>
		</div>
	</div>
	<div class="item-category-border"></div>
</div>


