<?php
common::load('base','page');
class about{
    private $db;
    private $lang;
    private $view,$title;
    function __construct($db,$lang='vi'){
        $this->db=$db;
        $this->db->reset();
        $this->lang=$lang;
        $db->where('id',2);
        $item=$db->getOne('menu');
        if($lang=='en'){
            $this->view=$item['e_view'];
            $this->title=$item['e_title'];
        }else{
            $this->view=$item['view'];
            $this->title=$item['title'];
        }
    }
    function ind_about(){
        $this->db->where('active',1);
        $this->db->orderBy('id','ASC');
        $item=$this->db->getOne('about');
        $lnk=myWeb.$this->view;
        $str='
        <section id="feature">
            <div class="container">
               <div class="row ribbon-bring">
                    <h1 class="ribbon">
                       <strong class="ribbon-content">
                            '.$item['title'].'
                       </strong>
                    </h1>
                    <p class="lead">'.$item['sum'].'</p>                    
                </div>    
                <div class="row">
                    <div class="features">
                        <div class="col-md-4 col-sm-6 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="600ms">
                            <div class="feature-wrap">
                                <i class="fa fa-bullhorn"></i>
                                <h2>Sản phẩm chính hãng</h2>
                                <h3>
                                Sản phẩm có công bố và hợp pháp.
                                </h3>
                            </div>
                        </div><!--/.col-md-4-->
    
                        <div class="col-md-4 col-sm-6 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="600ms">
                            <div class="feature-wrap">
                                <i class="fa fa-comments"></i>
                                <h2>Luôn tiếp nhận ý kiến</h2>
                                <h3>Tinh thần cầu thị, tiếp nhận mọi ý kiến đóng góp.</h3>
                            </div>
                        </div><!--/.col-md-4-->                        
                    
                        <div class="col-md-4 col-sm-6 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="600ms">
                            <div class="feature-wrap">
                                <i class="fa fa-leaf"></i>
                                <h2>Bảo vệ môi trường</h2>
                                <h3>Mỹ phẩm sạch, môi trường sạch, con người sạch.</h3>    
                            </div>
                        </div><!--/.col-md-4-->
    
                        <div class="col-md-4 col-sm-6 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="600ms">
                            <div class="feature-wrap">
                                <i class="fa fa-cogs"></i>
                                <h2>Hệ thống phân phối rộng lớn</h2>                                
                            </div>
                        </div><!--/.col-md-4-->
    
                        <div class="col-md-4 col-sm-6 wow fadeInDown" data-wow-duration="1000ms" data-wow-delay="600ms">
                            <div class="feature-wrap">
                                <i class="fa fa-heart"></i>
                                <h2>Bảo vệ sức khoẻ cộng đồng</h2>                               
                            </div>
                        </div><!--/.col-md-4-->
                    </div><!--/.services-->
                </div><!--/.row-->    
            </div><!--/.container-->
        </section><!--/#feature-->';
        return $str;
    }
    function breadcrumb(){
        $this->db->reset();
        $str.='
        <ul class="breadcrumb clearfix">
        	<li><a href="'.myWeb.'"><i class="fa fa-home"></i></a></li>
            <li><a href="'.myWeb.$this->view.'">'.$this->title.'</a></li>';
        if(isset($_GET['id'])){
            $this->db->where('id',intval($_GET['id']));
            $item=$this->db->getOne('about','id,title,pId');
            $cate=$this->db->where('id',$item['pId'])->getOne('about_cate','id,title');
            $cate_lnk=myWeb.$this->view.'/'.common::slug($cate['title']).'-p'.$cate['id'];
            $str.='
            <li><a href="'.$cate_lnk.'">'.$cate['title'].'</a></li>
            <li><a href="#">'.$item['title'].'</a></li>';
        }elseif(isset($_GET['pId'])){
            $cate=$this->db->where('id',intval($_GET['pId']))->getOne('about_cate','id,title');
            $str.='
            <li><a href="#">'.$cate['title'].'</a></li>';
        }
        $str.='
        </ul>
        <div class="about-header">
        
        </div>';
        return $str;
    }
    
    function about_item($item){
        $lnk=myWeb.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
        $str.='
        <a href="'.$lnk.'" class="about-item clearfix">
            <img src="'.webPath.$item['img'].'" class="img-responsive" alt="" title=""/>
            <div>
                <h2>'.$item['title'].'</h2>
                <span>'.nl2br(common::str_cut($item['sum'],620)).'</span>
            </div>
        </a>';
        return $str;
    }
    function check_pId(){
        if(isset($_GET['pId'])){
            $pId=intval($_GET['pId']);
        }elseif(isset($_GET['id'])){
            $item=$this->db->where('id',intval($_GET['id']))->getOne('about','pId');
            $pId=$item['pId'];
        }else $pId=0;
        return $pId;
    }
    function category(){
        $pId=$this->check_pId();
        $list=$this->db->where('active',1)->orderBy('ind','ASC')->get('about_cate',null,'id,title');
        $str='
        <div class="row text-center news-category">';
        foreach($list as $item){
            if($item['id']==$pId){
                $active=' class="active"';
            }else{
                $active='';
            }
            $str.='
            <a href="'.myWeb.$this->view.'/'.common::slug($item['title']).'-p'.$item['id'].'"'.$active.'>
                '.$item['title'].'
            </a>';
        }
        $str.='
        </div>';
        return $str;
    }
    function about_cate(){
        $pId=$this->check_pId();
        $page=isset($_GET['page'])?intval($_GET['page']):1;
        $this->db->reset();
        $this->db->where('active',1);
        if($pId!=0) $this->db->where('pId',$pId);
        $this->db->orderBy('id');
        $this->db->pageLimit=limit;
        $list=$this->db->paginate('about',$page);
        $count=$this->db->totalCount;
        $str='
        <section id="ind-serv">
        <div class="container">
            <div class="ind-serv wow fadeInUp" data-wow-duration="1s">
            <h2>Giới Thiệu IT EMSVN</h2>
            <p>Check out the Windows website, which has more information, downloads, and ideas for the most out</p>';
        $str.=$this->category();  
        if($count>0){             
            $str.='
                <div class="row clearfix">';
            foreach($list as $item){
                $lnk=myWeb.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
                $str.='
                <div class="wow fadeInLeft col-xs-3 ind-news-item" data-wow-duration="2s">
                    <a href="'.$lnk.'">
                        <img src="'.webPath.$item['img'].'" class="img-responsive center-block"/>
                        <h2>'.$item['title'].'</h2>
                        <p>'.common::str_cut($item['sum'],100).'</p>
                    </a>
                </div>';
            }
            $str.='
                </div>';            
        }   
        $str.='
            </div>
        </div>
        </section>'; 
        $pg = new Pagination();
        $pg->pagenumber = $page;
        $pg->pagesize = limit;
        $pg->totalrecords = $count;
        $pg->showfirst = true;
        $pg->showlast = true;
        $pg->paginationcss = "pagination-large";
        $pg->paginationstyle = 1; // 1: advance, 0: normal
        if($pId==0){
            $pg->defaultUrl = myWeb.$this->view;
            $pg->paginationUrl = myWeb.'[p]/'.$this->view;    
        }else{
            $cate=$this->db->where('id',$pId)->getOne('serv_cate','id,title');            
            $pg->defaultUrl = myWeb.$this->view.'/'.common::slug($cate['title']).'-p'.$cate['id'];
            $pg->paginationUrl = myWeb.$this->view.'/[p]/'.common::slug($cate['title']).'-p'.$cate['id'];
        }
        $str.= '<div class="pagination pagination-centered">'.$pg->process().'</div>';
        return $str;
    }
    function about_one(){
        $id=intval($_GET['id']);
        $item=$this->db->where('id',$id)->getOne('about');
        $str='
        <section id="ind-serv">
        <div class="container">
            <div class="ind-serv wow fadeInUp" data-wow-duration="1s">
            <h2>Tin Tức IT EMSVN</h2>
            <p>Check out the Windows website, which has more information, downloads, and ideas for the most out</p>';
        $str.=$this->category();                      
        $str.='
        <article class="article">
            <h1 class="article text-center">'.$item['title'].'</h1>
            <p>'.$item['content'].'</p>
        </article>';
        $str.='
            </div>
        </div>
        </section>';                 
        return $str;
    }
    
    function product_image_first($db,$pId){
        $db->where('active',1)->where('pId',$pId);
        $item=$db->getOne('product_image','img');
        return $item['img'];
    }
}


?>
