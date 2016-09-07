<?php
class customer{
    private $db,$view,$lang,$title;
    function __construct($db,$lang='vi'){
        $this->db=$db;
        $this->db->reset();
        $this->lang=$lang;
        $db->where('id',17);
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
        $str.='
        </ul>';
        $str.=$this->customer_vip();
        return $str;
    }
    function customer_item($item){
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
    function customer_vip(){
        $list=$this->db->where('active',1)->where('vip',1)->orderBy('ind','ASC')->get('customer',null,'id,lnk,img');
        $str.='
        <div class="customer-header">
        <div class="container">
            <div class="customer-vip row">';
        foreach($list as $item){
            $str.='
            <div>
                <a href="'.$item['lnk'].'" target="_blank">
                    <img src="'.webPath.$item['img'].'" class="img-responsive" alt="" title=""/>
                </a>
            </div>';
        }
        $str.='
            </div>
        </div>
        </div>
        <script>
        $(".customer-vip").slick({
            dot:false,
            infinite: true,
            slidesToShow: 8,
            slidesToScroll: 8,
            autoplay:true,
            autoplaySpeed:2000
        })
        </script>';
        return $str;
    }
    function customer_cate(){
        $page=isset($_GET['page'])?intval($_GET['page']):1;
        $this->db->reset();
        $this->db->where('active',1);
        $this->db->orderBy('id');
        $this->db->pageLimit=limit;
        $list=$this->db->paginate('customer',$page);
        $count=$this->db->totalCount;
        if($count>0){
            $this->db->where('active',1)->orderBy('id');
            $list=$this->db->get('customer',null);
            $str='
            <section id="ind-customer">
            <div class="container">
                <div class="ind-customer row wow fadeInUp" data-wow-duration="1s">
                <div class="header text-center">
                    <h2>'.$this->title.'</h2>
                    <p>Check out the Windows website, which has more information, downloads, and ideas for the most out</p>
                </div>        
                <ul class="customer-list clearfix">';
            foreach($list as $item){
                $str.='
                <li>
                    <a href="'.$item['lnk'].'" target="_blank">
                    <img src="'.webPath.$item['img'].'" alt="" title=""/>
                    <h3>'.$item['title'].'</h3>
                    <p>'.common::str_cut($item['sum'],200).'</p>
                    </a>
                </li>';
            }
            $str.='
                </ul>
                </div>
            </div>
            </section>';
            return $str;
        }        
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
}
?>
