<?php 
// trang liên hệ

class ContactController {
    function form(){
        require "view/contact/form.php";
    }
    function send() {
        $name = $_POST["fullname"];
        $email = $_POST["email"];
        $message = $_POST["content"];
        $mobile = $_POST["mobile"];
        $emailService = new EmailService();
        $to = EMAIL_SHOP;
        $subject = "[Godashop] khách hàng $email liên hệ";
        $content = "
        Chào chủ shop, <br>
        Khách hàng có email $email liên hệ, <br>
        Số điên thoại KH là: $mobile, <br>
        Nội dung là: <br>
        $message";
        if($emailService->send($to, $subject, $content)) {
            echo "đã gởi gmail thành công";
        }
        else {
            echo $emailService->getError();
        }

    }

}
 ?>