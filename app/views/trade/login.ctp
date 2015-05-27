<?php
echo $this->element('versioned_css', array('files' => array('trade','footer-pages')));
?>
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
                    <form name="trade-register" class="form" style="border-left:1px solid #ccc;padding:0px 50px;margin-top:60px" method="post">
                        <h4>Login to Trade Account</h4>
                        <div class="form-group">
                            <input type="text" class="form-control" name="email" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" name="password" placeholder="Password">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-lucca">Submit</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
    <div class="item-category-border"></div>
</div>