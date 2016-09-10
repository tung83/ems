<?php
class serv{
    private $db,$view,$lang,$title;
    function __construct($db,$lang='vi'){
        $this->db=$db;
        $this->db->reset();
        $this->lang=$lang;
        $db->where('id',8);
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
            $item=$this->db->getOne('serv','id,title');
            $str.='
            <li><a href="#">'.$item['title'].'</a></li>';
        }
        $str.='
        </ul>
        <div class="serv-header">
        
        </div>';
        return $str;
    }
    function serv_item($item){
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
    function serv_cate($pId=0){
        $page=isset($_GET['page'])?intval($_GET['page']):1;
        $this->db->reset();
        $this->db->where('active',1);
        if($pId!=0) $this->db->where('pId',$pId);
        $this->db->orderBy('id');
        $this->db->pageLimit=limit;
        $list=$this->db->paginate('serv',$page);
        $count=$this->db->totalCount;
        if($count>0){
            $this->db->where('active',1)->orderBy('id');
            $list=$this->db->get('serv',null);
            $str='
            <section id="ind-serv">
                <div class="ind-serv wow fadeInUp" data-wow-duration="1s">
                <h2>Dịch vụ EMSVN trọn gói</h2>
                <p>Check out the Windows website, which has more information, downloads, and ideas for the most out</p>        
                <ul class="clearfix">';
            foreach($list as $item){
                $lnk=myWeb.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
                $str.='
                <li>
                    <a href="'.$lnk.'">
                    <img src="'.webPath.$item['img'].'" alt="" title=""/>
                    <h3>'.$item['title'].'</h3>
                    <p>'.common::str_cut($item['sum'],200).'</p>
                    </a>
                </li>';
            }
            $str.='
                </ul>
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
    function serv_one(){
        $id=intval($_GET['id']);
        $item=$this->db->where('id',$id)->getOne('serv');
        $str='
        <div class="ind-serv wow fadeInUp" data-wow-duration="1s"> 
        <article class="article">
            <h1 class="article text-center">'.$item['title'].'</h1>
            <p>'.$item['content'].'</p>
        </article>
        </div>';
        return $str;
    }
    function ind_serv(){
        $this->db->where('active',1)->orderBy('id');
        $list=$this->db->get('serv',null);
        $str='
        <section id="ind-serv">
            <div class="ind-serv wow fadeInUp" data-wow-duration="1s">
            <h2>Dịch vụ EMSVN trọn gói</h2>
            <p>Check out the Windows website, which has more information, downloads, and ideas for the most out</p>        
            <div class="row clearfix">';
        foreach($list as $item){
            $lnk=myWeb.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
            $str.='
                <div class="col-xs-3 ind-serv-item">
                    <a href="'.$lnk.'">
                    <img src="'.webPath.$item['img'].'" alt="" title="" class="img-responsive center-block"/>
                    <h3>'.$item['title'].'</h3>
                    <p>'.common::str_cut($item['sum'],200).'</p>
                    </a>
                </div>';
        }
        $str.='
            </div>
            </div>
        </section>';
        return $str;
    }
    function one_ind_serv($id){
        $this->db->reset();
        $this->db->where('id',$id);
        $item=$this->db->getOne('serv','id,img,title,sum');
        $lnk=myWeb.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
        $str='
        <div class="ind_serv">
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
