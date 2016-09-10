<?php
class news{
    private $db,$view,$lang,$title;
    function __construct($db,$lang='vi'){
        $this->db=$db;
        $this->db->reset();
        $this->lang=$lang;
        $db->where('id',5);
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
        <ul class="breadcrumb clearfix">
        	<li><a href="'.myWeb.'"><i class="fa fa-home"></i></a></li>
            <li><a href="'.myWeb.$this->view.'">'.$this->title.'</a></li>';
        if(isset($_GET['id'])){
            $this->db->where('id',intval($_GET['id']));
            $item=$this->db->getOne('news','id,title,pId');
            $cate=$this->db->where('id',$item['pId'])->getOne('news_cate','id,title');
            $cate_lnk=myWeb.$this->view.'/'.common::slug($cate['title']).'-p'.$cate['id'];
            $str.='
            <li><a href="'.$cate_lnk.'">'.$cate['title'].'</a></li>
            <li><a href="#">'.$item['title'].'</a></li>';
        }elseif(isset($_GET['pId'])){
            $cate=$this->db->where('id',intval($_GET['pId']))->getOne('news_cate','id,title');
            $str.='
            <li><a href="#">'.$cate['title'].'</a></li>';
        }
        $str.='
        </ul>
        <div class="serv-header">
        
        </div>';
        return $str;
    }
    function news_item($item){
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
            $item=$this->db->where('id',intval($_GET['id']))->getOne('news','pId');
            $pId=$item['pId'];
        }else $pId=0;
        return $pId;
    }
    function category(){
        $pId=$this->check_pId();
        $list=$this->db->where('active',1)->orderBy('ind','ASC')->get('news_cate',null,'id,title');
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
    function news_cate(){
        $pId=$this->check_pId();
        $page=isset($_GET['page'])?intval($_GET['page']):1;
        $this->db->reset();
        $this->db->where('active',1);
        if($pId!=0) $this->db->where('pId',$pId);
        $this->db->orderBy('id');
        $this->db->pageLimit=limit;
        $list=$this->db->paginate('news',$page);
        $count=$this->db->totalCount;
        $str='
        <section id="ind-serv">
            <div class="ind-serv wow fadeInUp" data-wow-duration="1s">
            <h2>Tin Tức IT EMSVN</h2>
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
    function news_one(){
        $id=intval($_GET['id']);
        $item=$this->db->where('id',$id)->getOne('news');
        $str='
        <section id="ind-serv">
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
        </section>';                 
        return $str;
    }
    function ind_news(){
        $this->db->reset();
        $this->db->where('active',1)->orderBy('id');
        $list=$this->db->get('news',20,'id,title,sum,img');
        $str='
        <section id="ind-news" class="clearfix">
        <div class="row">
            <div class="background col-xs-4">
                
            </div>
            <div class="main-show col-xs-8 clearfix">
                <div class="your-class clearfix row">';
        foreach($list as $item){
            $lnk=myWeb.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
            $str.='
            <div class="wow fadeInLeft col-xs-4 ind-news-item" data-wow-duration="2s">
                <a href="'.$lnk.'">
                    <img src="'.webPath.$item['img'].'" class="img-responsive center-block"/>
                    <h2>'.$item['title'].'</h2>
                    <p>'.common::str_cut($item['sum'],100).'</p>
                </a>
            </div>';
        }
        $str.='
                </div>
            </div>
        </section>';
        return $str;
    }
    function one_ind_news($id){
        $this->db->reset();
        $this->db->where('id',$id);
        $item=$this->db->getOne('news','id,img,title,sum');
        $lnk=myWeb.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
        $str='
        <div class="ind_news">
            <a href="'.$lnk.'">
                <img src="'.webPath.$item['img'].'" alt="" title="'.$item['title'].'"/>
                <h2>'.$item['title'].'</h2>
                <span>'.common::str_cut($item['sum'],120).'</span>
            </a>
        </div>';
        return $str;
    }
    function product_image_first($db,$pId){
        $db->where('active',1)->where('pId',$pId);
        $item=$db->getOne('product_image','img');
        return $item['img'];
    }

}
?>
