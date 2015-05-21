<?php
echo $this->element('versioned_css', array('files' => array('trade','footer-pages')));
?>
<div class="footerpage about">
    <div class="overlay">
        <div class="wrapper col-xs-12">
            <div class="row" id="trade">
                <div class="col-sm-6">
                    <h2>LUCCA STUDIO CUSTOM SIZES</h2>
                    <p>To request a Lucca Studio piece in a custom size, simply follow the instructions to the right.</p>
                    <p>Please remember...</p>
                    <ul>
                        <li>Price quotes will be provided within 48 hrs of receiving completed form (email form to the
                            Lucca store in your geographic area)
                        </li>
                        <li>Price quote is valid for 30 days from date of request</li>
                        <li>RUSH charges are calculated at 20% additional to net pricing</li>
                        <li>All approved and signed custom orders must be paid in full prior to start of production</li>
                </div>
                <div class="col-sm-6">
                        <div style="border-left:1px solid #ccc;padding:0px 50px;margin-top:60px" >
                            <h4>Request a custom piece</h4>
                        <h4 style="color:#666">1.  Fill out <a style="text-decoration: underline" href="/CustomRequestForm.pdf" class="btn-lucca-link" target="_blank">this form</a> with your request</h4>
                        <h4 style="color:#666">2.  Email the form to the Lucca Antiques location in your area:</h4>
                        <div class="row" style="margin-left:10px">
                            <div class="col-sm-6">
                                <h4 style="color:#333">Los Angeles</h4>
                                <a class="btn-lucca-link" href="mailto:<?php echo LA_EMAIL ?>"><?php echo LA_EMAIL ?></a>
                            </div>
                            <div class="col-sm-6">
                                <h4 style="color:#333">New York</h4>
                                <a class="btn-lucca-link" href="mailto:<?php echo NY_EMAIL?>"><?php echo NY_EMAIL?></a>
                            </div>
                            <div class="col-sm-6">
                                <h4 style="color:#333">San Francisco</h4>
                                <a class="btn-lucca-link" href="mailto:<?php echo SF_EMAIL?>"><?php echo SF_EMAIL?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="item-category-border"></div>
</div>