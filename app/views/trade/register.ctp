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
                    <form name="trade-register" class="form" style="border-left:1px solid #ccc;padding:0px 50px;margin-top:60px" method="post">
                        <h4>Please fill out the form below to join the Lucca Trade program</h4>
                        <div class="form-group">
                            <input type="text" class="form-control" name="name" placeholder="Name">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="company_name" placeholder="Company Name">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="address" placeholder="Address">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="phone" placeholder="Phone #">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="resale" placeholder="Resale #">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="email" placeholder="Email Address">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="password" placeholder="Password">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="password_confirm" placeholder="Password Confirm">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-lucca">Submit</button>
                            <a href="/trade/login" class="btn btn-lucca-link pull-right">Already have an account?</a>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
    <div class="item-category-border"></div>
</div>