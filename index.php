<?php include 'function.php';?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>.:EMSVN.com:.</title>
	<link rel="icon" type="image/png" href="<?=selfPath?>logo.png"/> 
    <?=common::basic_css()?>
    <?=common::basic_js()?>
</head>
<body>
<header>
    <div class="container">
        <div class="top clearfix">
            <img src="<?=selfPath?>logo.png" class="logo"/>
            <div class="hotline">
                <i class="glyphicon glyphicon-phone-alt"></i> 0123 456 789
            </div>
            <div class="top-menu">
                <?=top_menu($db,$view)?>
            </div>        
        </div>
        <div class="bottom clearfix">
            <div class="bottom_menu">
                <?=bottom_menu($db,$view)?>
            </div>
            <div class="cart-and-search">
                <span><i class="fa fa-shopping-cart"></i> (0) Sản Phẩm</span>
                <span><i class="fa fa-search"></i> Tìm Kiếm</span> 
            </div>
        </div>
    </div>
</header>
<?php
$str='';
switch($view){
    case 'gioi-thieu':
        $str = about($db);
        break;
    case 'hoi-dap':
        $str = faqs($db);
        break;
    case 'tin-tuc':
        $str = news($db);
        break;
    case 'dich-vu':
        $str = serv($db);
        break;
    case 'lien-he':
        $str = contact($db);
        break;
    case 'khach-hang':
        $str = customer($db);
        break;
    default:
        $str = home($db);
        break;
}
echo wrap_body_content($str);
?>
<footer>    
    <div class="footer clearfix">
        <div class="container">
            <div id="footer-menu">
                <div class="clearfix">
                    <?=foot_menu($db,$view)?>
                </div>
            </div>
            <div id="footer-qtext">
                <?=common::qtext($db,4)?>
            </div>
            <div id="bottom-right">
                <img src="/file/self/footer_map.png" class="footer-map">
                <img src="/file/self/footer_barcode.png" class="footer-barcode">
            </div>
        </div>
    </div>
    <div class="copyright">
        <div class="container">
            Copyright &copy; 2016 <a>EMSVN</a>. All rights reserved
        </div>
    </div>
</footer>
</body>
</html>