<?php
class product{
    private $db,$view,$lang,$title;
    function __construct($db,$lang='vi'){
        $this->db=$db;
        $this->db->reset();
        $this->lang=$lang;
        $db->where('id',13);
        $item=$db->getOne('menu');
        if($lang=='en'){
            $this->view=$item['e_view'];
            $this->title=$item['e_title'];
        }else{
            $this->view=$item['view'];
            $this->title=$item['title'];
        }
    }
    function breadcrumb(){
        $this->db->reset();
        $str.='
        <div class="container">
        <ul class="breadcrumb clearfix">
        	<li><a href="'.myWeb.'"><i class="fa fa-home"></i></a></li>
            <li><a href="'.myWeb.$this->view.'">'.$this->title.'</a></li>';
        if(isset($_GET['id'])){
            $this->db->where('id',intval($_GET['id']));
            $item=$this->db->getOne('product','id,title,pId');
            //$cate_sub=$this->db->where('id',$item['pId'])->getOne('product_cate','id,title,pId');
            //$cate=$this->db->where('id',$cate_sub['pId'])->getOne('product_cate','id,title');
            /*$str.='
            <li><a href="'.myWeb.$this->view.'/'.common::slug($cate['title']).'-cate'.$cate['id'].'">'.$cate['title'].'</a></li>
            <li><a href="'.myWeb.$this->view.'/'.common::slug($cate_sub['title']).'-p'.$cate_sub['id'].'">'.$cate_sub['title'].'</a></li>';*/
            $str.='
            <li><a href="#">'.$item['title'].'</a></li>';
        }elseif(isset($_GET['cate_id'])){
            $cate=$this->db->where('id',intval($_GET['cate_id']))->getOne('product_cate','id,title');
            $str.='
            <li><a href="#">'.$cate['title'].'</a></li>';
        }elseif(isset($_GET['pId'])){
            $cate_sub=$this->db->where('id',intval($_GET['pId']))->getOne('product_cate','id,title,pId');
            $cate=$this->db->where('id',$cate_sub['pId'])->getOne('product_cate','id,title');
            $str.='
            <li><a href="'.myWeb.$this->view.'/'.common::slug($cate['title']).'-cate'.$cate['id'].'">'.$cate['title'].'</a></li>            
            <li><a href="#">'.$cate_sub['title'].'</a></li>';
        }
        $str.='
        </ul>
        </div>';
        return $str;
    }
    function ind_product(){
        $str='
        <section id="web-design">
        <div class="container">
            <div class="wow fadeInDown row">
                <h2>Các mẫu website mới</h2>
                <p>Check out the Windows website, which has more information, downloads, and ideas for the most out</p>  
            </div>';
        $list=$this->db->where('home',1)->where('active',1)->orderBy('id')->get('product',null);
        if(count($list)>0){
            $str.='
            <ul class="product-list clearfix">';
            foreach($list as $item){
                $str.=$this->product_item($item);
            }    
            $str.='
            </ul>';
        }        
        $str.='   
        </div>
        </section>';
        return $str;
    }
    function past_product(){
        $str='
        <section id="past-web-design">
        <div class="container">
            <div class="wow fadeInDown row text-center">
                <h2>Các mẫu website đã thiết kế</h2>
                <p>Check out the Windows website, which has more information, downloads, and ideas for the most out</p>  
            </div>';
        $list=$this->db->where('home',1)->where('active',1)->orderBy('id')->get('product',null);
        if(count($list)>0){
            $str.='
            <ul class="product-list clearfix">';
            foreach($list as $item){
                $str.=$this->product_item($item);
            }    
            $str.='
            </ul>';
        }        
        $str.='   
        </div>
        </section>';
        return $str;
    }
   
    function product_item($item){
        $lnk=myWeb.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
        //$img=$this->first_image($item['id']);
        $str.='
        <li class="wow fadeInLeft" data-wow-duration="2s">
			<figure>
				<img src="'.webPath.$item['img'].'" alt="'.$item['title'].'" title="'.$item['title'].'">
                <div class="row">
                    <div class="col-xs-6">
                        <form>
                            <button><i class="fa fa-shopping-cart"></i> Đặt mua</button>
                        </form>
                    </div>
                    <div class="col-xs-6">
                        <form>
                            <button><i class="fa fa-eye"></i> Demo</button>
                        </form>
                    </div>
                </div>
				<figcaption>
					<div class="row">
                        <div class="col-xs-6">
                            Mẫu số: <b>'.$item['title'].'</b>
                        </div>
                        <div class="col-xs-6 price text-right">
                            '.number_format($item['price'],0,'.','.').'VNĐ
                        </div>
                    </div>
				</figcaption>
			</figure>
		</li>';
        return $str;
    }
    function product_list_item($item,$type=1){
        $lnk=myWeb.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
        $img=$this->first_image($item['id']);
        if(trim($img)==='') $img='holder.js/400x300';else $img=webPath.$img;
        if($type==1){
            $str='
            <div class="col-xs-12 col-sm-6 col-md-3 product-item">';    
        }else{
            $str='
            <div class="col-xs-12 col-sm-6 col-md-4 product-item">';
        }        
        $str.='
        <a href="'.$lnk.'">
            <div>
                <p>'.($item['price']==0?contact:number_format($item['price'],0,',','.').' VNĐ').'</p>
                <img src="'.$img.'" class="img-responsive" />
                <p>
                    <h2>'.$item['title'].'</h2>
                    <button class="btn btn-default">'.more.'</button>
                </p>
            </div>
        </a>
        </div>';
        return $str;
    }
    function category($pId){
        $this->db->reset();
        $cate=$this->db->where('id',$pId)->getOne('product_cate','id,pId,lev');
        if($cate['lev']==1) $pId=$cate['id'];
        else $pId=$cate['pId'];
        $this->db->where('active',1)->where('lev',1)->orderBy('ind','ASC')->orderBy('id');
        $list=$this->db->get('product_cate',null,'id,title,lev,pId');
        $str='
        <span class="box-title">Danh Mục</span>
        <ul id="accordion" class="accordion">';
        foreach($list as $item){
            $dimension=($pId==$item['id'])?' id="active"':'';
            $this->db->reset();
            $sub_list=$this->db->where('pId',$item['id'])->where('active',1)->orderBy('ind','ASC')->get('product_cate',null,'id,title');
            $str.='
            <li'.$dimension.'>
                <div class="link"><i class="fa fa-cube"></i>'.$item['title'].'<i class="fa fa-chevron-right"></i></div>
                <ul class="submenu">';
            foreach($sub_list as $sub_item){
                $str.='
                <li><a href="'.myWeb.$this->view.'/'.common::slug($sub_item['title']).'-p'.$sub_item['id'].'">
                    '.$sub_item['title'].'
                </a></li>';
            }
            $str.='
                    <li><a href="'.myWeb.$this->view.'/'.common::slug($item['title']).'-cate'.$item['id'].'"> Xem tất cả</a></li>
                </ul>
            </li>';
        }
        $str.='
        </ul>
        <script>
        $(function() {
        	var Accordion = function(el, multiple) {
        		this.el = el || {};
        		this.multiple = multiple || false;

        		// Variables privadas
        		var links = this.el.find(".link");
        		// Evento
        		links.on("click", {el: this.el, multiple: this.multiple}, this.dropdown)
        	}

        	Accordion.prototype.dropdown = function(e) {
        		var $el = e.data.el;
        			$this = $(this),
        			$next = $this.next();

        		$next.slideToggle();
        		$this.parent().toggleClass("open");

        		if (!e.data.multiple) {
        			$el.find(".submenu").not($next).slideUp().parent().removeClass("open");
        		};
        	}

        	var accordion = new Accordion($("#accordion"), false);
        });
        $("#active").toggleClass("open");
        $("#active").find(".submenu").slideToggle();
        </script>';
        return $str;
    }
    function product_cate($pId=0){
        $this->db->reset();
        if($pId>0){
            $lev=$this->db->where('id',$pId)->getOne('product_cate','lev');
            if($lev['lev']==1){
                $cate_sub=$this->db->where('pId',$pId)->where('active',1)->get('product_cate',null,'id');
                foreach($cate_sub as $cate_sub_item){
                    $arr[]=$cate_sub_item['id'];
                }
                $this->db->where('pId',$arr,'in');
            }else $this->db->where('pId',$pId);
        }
        $this->db->where('active',1)->orderBy('id');
        $this->db->pageLimit=pd_lim;
        $page=isset($_GET['page'])?intval($_GET['page']):1;
        $list=$this->db->paginate('product',$page);
        $count=$this->db->totalCount;
        $i=1;
        $str='
        <section id="recent-works">
            <div class="container">';
        $str.='
        <div class="grid cs-style-2 row">';
        foreach($list as $item){
            $str.=$this->product_item($item);
        }    
        $str.='
        </div>';
        $str.='
            </div>
        </section>';
        return $str; 
    }
    function product_list($pId,$type=1){
        $page=isset($_GET['page'])?intval($_GET['page']):1;
        $this->db->reset();
        if($pId!=0) $this->db->where('pId',$pId);
        $this->db->where('active',1)->orderBy('id');
        $this->db->pageLimit=limit;
        $list=$this->db->paginate('product',$page,'id,title,price,price_reduce');
        $str='
        <div class="row">';
        foreach($list as $item){
            $str.=$this->product_list_item($item,$type);
        }
        $str.='
        </div>';
        return $str;
    }
    function product_one($id){
        $this->db->where('id',$id);
        $item=$this->db->getOne('product','id,price,price_reduce,title,content,pId,feature,manual,promotion,video');
        $this->db->where('id',$item['id'],'<>')->where('active',1)->orderBy('rand()');
        $list=$this->db->get('product',5);
        $lnk=domain.'/'.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
        $str.='
        <div class="container">
        <div class="row product-detail clearfix">
            <div class="col-md-5">
                '.$this->product_image_show($item['id']).'
            </div>
            <div class="col-md-7">
                <article class="product-one">
                <h1>'.$item['title'].'</h1>
                <b>Giá Bán Lẻ: <em>'.number_format($item['price'],0,',','.').'VNĐ</em></b>
                <form action="javascript:add_cart('.$item['id'].',1)">
                    <button class="btn btn-default"><i class="fa fa-shopping-cart"></i> Mua Hàng</button>
                </form>
                <p>'.$item['feature'].'</p>
                </article>
            </div>
        </div>                   
        <div>
            <div id="tabs" class="tabs">
                <ul>
                    <li><a href="#tabs-1">MÔ TẢ CHI TIẾT</a></li>
                    <li><a href="#tabs-4">BÌNH LUẬN</a></li>
                </ul>
                <div id="tabs-1">
                    <article>
                        <p>'.$item['content'].'</p>
                    </article>
                </div>
                <div id="tabs-4">
                    <div class="fb-comments" data-width="100%" data-href="'.$lnk.'" data-numposts="5"></div>
                </div>
            </div>       
        </div>       ';
        if(count($list)>0){
            $str.='
            <div class="wow fadeInDown row ribbon-bring">
                <h1 class="ribbon">
                   <strong class="ribbon-content">
                        SẢN PHẨM CÙNG LOẠI
                   </strong>
                </h1>
            </div>';
            $str.='
            <div class="grid cs-style-2 row">';
            foreach($list as $item){
                $str.=$this->product_item($item);
            }    
            $str.='
            </div>';    
        }        
        $str.='
        </div>';    
        return $str;
    }
    function product_image_show($id){
        $this->db->reset();
        $list=$this->db->where('active',1)->where('pId',$id)->orderBy('ind','ASC')->orderBy('id')->get('product_image');
        $temp=$tmp='';
        foreach($list as $item){
            $temp.='
            <li>
                <a href="'.webPath.$item['img'].'" >
                    <img src="'.webPath.$item['img'].'" alt="" title="" class=""/>
                </a>
            </li>';
            $tmp.='
            <li>
                <img src="'.webPath.'thumb_'.$item['img'].'" alt="" title=""/>
            </li>';
        }
        $str.='
        <!-- Place somewhere in the <body> of your page -->
        <div id="image-slider" class="flexslider">
          <ul class="slides popup-gallery">
            '.$temp.'
          </ul>
        </div>
        <div id="carousel" class="flexslider" style="margin-top:-50px;margin-bottom:10px">
          <ul class="slides">
            '.$tmp.'
          </ul>
        </div>
        <script>
        $(window).load(function() {
          // The slider being synced must be initialized first
          $("#carousel").flexslider({
            animation: "slide",
            controlNav: false,
            animationLoop: false,
            slideshow: false,
            itemWidth: 80,
            itemMargin: 5,
            asNavFor: "#image-slider"
          });
         
          $("#image-slider").flexslider({
            animation: "slide",
            controlNav: false,
            animationLoop: false,
            slideshow: false,
            sync: "#carousel"
          });
        });
        </script>';
        return $str;
    }
    function first_image($id){
        $this->db->reset();
        $this->db->where('active',1)->where('pId',$id)->orderBy('ind','ASC')->orderBy('id');
        $img=$this->db->getOne('product_image','img');
        return $img['img'];
    }
}
?>