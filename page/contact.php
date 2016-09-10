<?php
class contact{
    private $db,$view,$lang;
    function __construct($db,$lang='vi'){
        $this->db=$db;
        $this->db->reset();
        $this->lang=$lang;
        $db->where('id',9);
        $item=$db->getOne('menu');
        if($lang=='en'){
            $this->view=$item['e_view'];
        }else{
            $this->view=$item['view'];
        }
    }
    function breadcrumb(){
        $this->db->reset();
        $str.='
        <ul class="breadcrumb clearfix">
        	<li><a href="'.myWeb.'"><i class="fa fa-home"></i></a></li>
            <li><a href="'.myWeb.$this->view.'">Liên Hệ</a></li>';
        $str.='
        </ul>';
        return $str;
    }
    function contact_insert(){
        $this->db->reset();
        if(isset($_POST['contact_send'])){
            $name=htmlspecialchars($_POST['name']);
            $adds=htmlspecialchars($_POST['adds']);
            $phone=htmlspecialchars($_POST['phone']);
            $email=htmlspecialchars($_POST['email']);
            $subject=htmlspecialchars($_POST['subject']);
            $content=htmlspecialchars($_POST['content']);
            $insert=array(
                'name'=>$name,'adds'=>$adds,'phone'=>$phone,
                'email'=>$email,'fax'=>$subject,'content'=>$content,
                'dates'=>date("Y-m-d H:i:s")
            );
            try{
                //$this->send_mail($insert);
                $this->db->insert('contact',$insert);                
               // header('Location:'.$_SERVER['REQUEST_URI']);
               echo '<script>alert("Thông tin của bạn đã được gửi đi, BQT sẽ phản hồi sớm nhất có thể, Xin cám ơn!");
                    location.href="'.$_SERVER['REQUEST_URI'].'"
               </script>';
            }catch(Exception $e){
                echo $e->errorInfo();
            }
        }
    }
    function contact(){
        $this->contact_insert();
        $this->db->reset();
        $item=$this->db->where('id',3)->getOne('qtext','content');
        $str.='    
        <section id="contact-page">
        <div class="contact-header">
            <h1>'.contact.'</h1>
            <p>EMSVN luôn lắng nghe ý kiến của khách hàng.</p>
        </div>
            <div class="container padding-container">
                <div class="row contact-wrap"> 
                    <div class="status alert alert-success" style="display: none"></div>
                    <form data-toggle="validator" role="form" class="contact-form" name="contact-form" method="post" action="">
                        <div class="col-sm-6">
                            <p>
                            '.common::qtext($this->db,3).'
                            </p>
                            <h4 id="contact-header-title">Form liên hệ</h4>
                            <div class="form-group">
                                <input type="text" name="name" class="form-control" placeholder="Họ tên khách hàng" required/>
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="form-group">
                                <input type="text" name="company_name" class="form-control" placeholder="Tên công ty" required/>
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="form-group">
                                <input type="text" name="address" class="form-control" placeholder="Địa chỉ" required/>
                                <div class="help-block with-errors"></div>
                            </div>                            
                            <div class="form-group">
                                <input type="email" name="email" class="form-control" placeholder="Email" required/>
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Điện thoại">
                            </div>   
                            <div class="form-group">
                                <textarea name="message" id="message" placeholder="Nội dung" required class="form-control" rows="13"></textarea>
                                <div class="help-block with-errors"></div>
                            </div>                        
                            <div class="form-group">
                                <button type="submit" name="submit" class="btn btn-primary btn-contact-frm">
                                    Gửi
                                </button>
                                <button type="reset" name="reset" class="btn btn-primary btn-contact-frm">
                                    Xoá
                                </button>
                            </div>                 
                        </div>
                        <div class="col-sm-6">
                            <div class="gmap">
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.055422631661!2d106.69673731421496!3d10.807067261583935!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x317528c096513faf%3A0xb604d8b3d555b84b!2zNDlBIELDuWkgxJDDrG5oIFR1w70sIFBoxrDhu51uZyAyNCwgQsOsbmggVGjhuqFuaCwgSOG7kyBDaMOtIE1pbmgsIFZpZXRuYW0!5e0!3m2!1sen!2s!4v1472448762214" width="500" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
                            </div>
                        </div>
                    </form> 
                </div><!--/.row-->
            </div><!--/.container-->
        </section><!--/#contact-page-->';
        return $str;
    }
    function send_mail($item){
        //Create a new PHPMailer instance
        $mail = new PHPMailer;
        $mail->setFrom('info@quangdung.com.vn', 'Website administrator');
        //Set an alternative reply-to address
        $mail->addReplyTo($item['email'], $item['name']);
        //Set who the message is to be sent to
        $mail->addAddress('czanubis@gmail.com');
        //Set the subject line
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject =  'Contact sent from website';
        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body
        //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
        //Replace the plain text body with one created manually
        $mail->Body = '
        <html>
        <head>
        	<title>'.$mail->Subject.'</title>
        </head>
        <body>
        	<p>Full Name: '.$item['name'].'</p>
        	
        	<p>Address: '.$item['adds'].'</p>
        	<p>Phone: '.$item['phone'].'</p>
        	
        	<p>Email: '.$item['email'].'</p>
            <p>Tiêu Đề: '.$item['fax'].'</p>
        	<p>Content: '.nl2br($item['content']).'</p>
        </body>
        </html>
        ';
        //Attach an image file
        //$mail->addAttachment('images/phpmailer_mini.png');
        
        //send the message, check for errors
        //$mail->send();
        if ($mail->send()) {
            echo "Message sent!";
        } else {
            echo "Mailer Error: " . $mail->ErrorInfo;
        }
    }
}
?>
