<?php
include_once 'front.php';
function top_menu($db,$view){
    $list=$db->where('active',1)->where('pos',1)->orderBy('ind','ASC')->orderBy('id')->get('menu');
    $str='
    <ul>';
    foreach($list as $item){
        $lnk=myWeb.$item['view'];
        if($item['view']=='trang-chu'){
            $title='<i class="fa fa-home"></i>';
        }else{
            $title=$item['title'];
        }
        if($item['view']==$view){
            $active=' class="active"';
        }else{
            $active='';
        }
        $str.='
        <li>
            <a href="'.$lnk.'"'.$active.'>
                <span class="text-center">
                <i class="fa fa-2x fa-'.$item['icon'].'"></i>'.$item['title'].'</span><em></em>
            </a>
        </li>';
    }
    $str.='
    </ul>';
    return $str;
}
function bottom_menu($db,$view){
    $list=$db->where('active',1)->where('pos',2)->orderBy('ind','ASC')->orderBy('id')->get('menu');
    $str='
    <ul>';
    $d=count($list)+20;
    foreach($list as $item){
        $lnk=myWeb.$item['view'];
        if($item['view']==$view){
            $active=' active';
        }else{
            $active='';
        }
        $str.='
        <li class="clearfix'.$active.'" style="z-index:'.$d.'">
            <a href="'.$lnk.'"><em></em><span>'.$item['title'].'</span><em></em></a>
        </li>';
        $d--;
    }
    $str.='
    </ul>';
    return $str;
}
function foot_menu($db,$view){
    $db->reset();
    $list=$db->where('active',1)->where('pos',1)->orderBy('ind','ASC')->orderBy('id')->get('menu');    
    $str.='
    <ul class="footer-menu">';
    foreach($list as $item){
        $str.='
        <li class="clearfix">
            <a href="'.myWeb.$item['view'].'">'.$item['title'].'</a>
        </li>';   
    }
    $str.='
    </ul>';
    
    $list=$db->where('active',1)->where('pos',2)->orderBy('ind','ASC')->orderBy('id')->get('menu');    
    $str.='
    <ul class="footer-menu">';
    foreach($list as $item){
        $str.='
        <li><a href="'.myWeb.$item['view'].'">'.$item['title'].'</a></li>';   
    }
    $str.='
    </ul>';
    return $str;
}
function home($db){
    common::widget('layer_slider');
    $layer_slider=new layer_slider($db);
    $str=$layer_slider->output();
    
    $str.='
    <section id="domain-check">
        <div>
            <h2>Kiểm Tra Tên Miền</h2>
            <p>Đăng ký tên miền ngay để bảo vệ thương hiệu của bạn</p>
            <div class="input-group">
              <input type="text" class="form-control" placeholder="Nhập tên miền cần kiểm tra" aria-describedby="basic-addon2">
              <span class="input-group-btn">
                <button class="btn btn-default" type="button">Kiểm Tra</button>
              </span>
            </div>
        </div>
    </section>';
    
    common::page('serv');
    $serv=new serv($db);
    $str.=$serv->ind_serv();
    
    /*common::page('about');
    $about=new about($db);
    $str.=$about->ind_about();*/
    
    common::page('product');
    $product=new product($db);
    $str.=$product->ind_product();
    $str.=$product->past_product();
    
    common::page('faqs');
    $faqs=new faqs($db);
    $str.=$faqs->ind_faqs();
    
    common::page('news');
    $news=new news($db);
    $str.=$news->ind_news();
    
    $str.=ads_banner($db);
    return $str;
}

function customer($db){
    common::page('customer');
    $cs=new customer($db);
    $str=wrap_breadcrumb($cs->breadcrumb());
    $str.=$cs->customer_cate();
    return $str;
}
function contact($db){
    common::page('contact');
    $contact=new contact($db);
    $str=wrap_breadcrumb($contact->breadcrumb());
    $str.=$contact->contact();
    return $str;
}
function about($db){
    common::page('about');
    $about=new about($db);
    $str=wrap_breadcrumb($about->breadcrumb());
    $str.=$about->about_header();
    if(isset($_GET['id'])){
        $str.=$about->about_one();
    }else{
        $str.=$about->about_cate();
    }
    return $str;
}

function wrap_breadcrumb($str){
    $wrapped_str ='<div id="breadcrumb" class="container">' 
            . $str
            . '</div>';
    return $wrapped_str;
}

function faqs($db){
    common::page('faqs');
    $faqs=new faqs($db);
    $str=wrap_breadcrumb($faqs->breadcrumb());
    $str.='
    <section id="page-container">
        <div class="container clearfix faqs-container">
            <div class="left">';
    if(isset($_GET['id'])){
        $str.=$faqs->faqs_one();
    }else{
        $str.=$faqs->faqs_cate();
    }
    $str.='
            </div>
            <div class="right">';
    $str.=$faqs->hot_faqs();
    $str.='
            </div>
        </div>
    </section>';
    
    return $str;
}
function serv($db){
    common::page('serv');
    $serv=new serv($db);
    $str=wrap_breadcrumb($serv->breadcrumb());
    if(isset($_GET['id'])){
        $str.=$serv->serv_one();
    }else{
        $str.=$serv->serv_cate();
    }
    return $str;
}
function news($db){
    common::page('news');
    $news=new news($db);
    $str=wrap_breadcrumb($news->breadcrumb());
    if(isset($_GET['id'])){
        $str.=$news->news_one();
    }else{
        $str.=$news->news_cate();
    }
    return $str;
}
function product($db,$view){
    common::page('product');
    $pd=new product($db,$view);
    $str=wrap_breadcrumb($pd->breadcrumb());
    if(isset($_GET['id'])){
        $id=intval($_GET['id']);
        $str.=$pd->product_one($id);
    }else{
        $str.=$pd->product_cate();
    }
    return $str;
}
function ads_banner($db){
    $str.='
    <script type="text/javascript" src="/js/jssor.slider-21.1.5.min.js"></script>
    <!-- use jssor.slider-21.1.5.debug.js instead for debug -->
    <script>
        jssor_1_slider_init = function() {
            
            var jssor_1_options = {
              $AutoPlay: true,
              $Idle: 0,
              $AutoPlaySteps: 4,
              $SlideDuration: 3000,
              $SlideEasing: $Jease$.$Linear,
              $PauseOnHover: 4,
              $SlideWidth: 320,
              $Cols: 4
            };
            
            var jssor_1_slider = new $JssorSlider$("jssor_1", jssor_1_options);
        
            function ScaleSlider() {
                var refSize = jssor_1_slider.$Elmt.parentNode.clientWidth;
                if (refSize) {
                    refSize = Math.min(refSize, 1220);
                    jssor_1_slider.$ScaleWidth(refSize);
                }
                else {
                    window.setTimeout(ScaleSlider, 30);
                }
            }
            ScaleSlider();
            $Jssor$.$AddEvent(window, "load", ScaleSlider);
            $Jssor$.$AddEvent(window, "resize", ScaleSlider);
            $Jssor$.$AddEvent(window, "orientationchange", ScaleSlider);
            //responsive code end
        };
    </script>';
    $str.='
    <section id="ads_banner">
    <div id="jssor_1" class="container" style="position: relative; margin: 0 auto; top: 0px; left: 0px; height: 177px; overflow: hidden; visibility: hidden;">
        <div data-u="slides" style="cursor: default; position: relative; top: 0px; left: 0px; width: 1220px; height: 177px; overflow: hidden;">';
    $list=$db->where('active',1)->orderBy('ind','ASC')->get('ads_banner');
    foreach($list as $item){
        $str.='
        <div style="display: none;">
            <img data-u="image" src="'.webPath.$item['img'].'" />
        </div>';
    }
    $str.='
            <a data-u="add" href="http://www.jssor.com/demos/scrolling-logo-thumbnail-slider.slider" style="display:none">
                Scrolling Logo Thumbnail Slider
            </a>        
        </div>
    </div>
    </section>';
    
    $str.='
    <script>
        jssor_1_slider_init();
    </script>';
    return $str;
}
?>
