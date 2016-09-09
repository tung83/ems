<?php
class faqs{
    private $db,$view,$lang,$title;
    function __construct($db,$lang='vi'){
        $this->db=$db;
        $this->db->reset();
        $this->lang=$lang;
        $db->where('id',10);
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
            $item=$this->db->getOne('faqs','id,title');
            $str.='
            <li><a href="#">'.$item['title'].'</a></li>';
        }
        $str.='
        </ul>
        <div class="faqs-header">
        
        </div>';
        return $str;
    }
    function ind_faqs(){
        $list=$this->db->where('active',1)->orderBy('ind','ASC')->orderBy('id')->get('faqs',5,'id,title');
        $str='
        <section id="ind-faqs">
            <div class="clearfix">
                <div class="left">';
        foreach($list as $item){
            $str.='
            <a href="" class="triangle-right right">
                <span><i class="fa fa-question-circle-o"></i>'.$item['title'].'</span>
            </a>';
        }  
        $str.='
                </div>
            </div>
        </section>';
        return $str;
    }
    function hot_faqs(){
        $list=$this->db->where('active',1)->orderBy('ind','ASC')->orderBy('id')->get('faqs',5);
        foreach($list as $item){
            $lnk=myWeb.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'];
            $str.='
            <a href="'.$lnk.'" class="faqs-hot-list triangle-right left">
                <i class="fa fa-question-circle-o"></i> <span>'.$item['title'].'<span>
            </a>';
        }  
        return $str;
    }
    function faqs_cate($pId=0){
        $page=isset($_GET['page'])?intval($_GET['page']):1;
        $this->db->reset();
        $this->db->where('active',1);
        if($pId!=0) $this->db->where('pId',$pId);
        $this->db->orderBy('id');
        $this->db->pageLimit=limit;
        $list=$this->db->paginate('faqs',$page);
        $count=$this->db->totalCount;
        if($count>0){
            foreach($list as $item){
                $str.='
                <a href="'.myWeb.$this->view.'/'.common::slug($item['title']).'-i'.$item['id'].'" class="faqs-list">
                    <i class="fa fa-question-circle-o"></i> '.$item['title'].'
                </a>';
            }
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

        $pg->defaultUrl = myWeb.$this->view;
        $pg->paginationUrl = myWeb.'[p]/'.$this->view;    
        
        $str.= '<div class="pagination pagination-centered">'.$pg->process().'</div>';
        return $str;
    }
    function faqs_one(){
        $id=intval($_GET['id']);
        $item=$this->db->where('id',$id)->getOne('faqs'); 
        $str.='
        <article class="article">
            <h1 class="article text-center">'.$item['title'].'</h1>
            <p>'.$item['content'].'</p>
        </article>';       
        return $str;
    }

}
?>
