<?php

class Email_handler {

    function url() {
        return ""; // << jika dilempar ke server lain yang bisa smtp
    }
	
	function email_default(){
		return "pmb@polbangtanmalang@gmail.com";
	}
	
	function send_email($recipient, $subyek, $message){
		return $this->option_default($recipient, $subyek, $message);
		//return $this->option_one($recipient, $subyek, $message);
		//return $this->option_two($recipient, $subyek, $message);
	}
	
	function option_default($recipient, $subyek, $message){ // DEFAULT SMTP CI
		date_default_timezone_set('Asia/Jakarta');
		
		$CI = & get_instance();
		$CI->config->load('email');
		$sender = $CI->config->item('smtp_user');
		
		if(!$sender || $sender == null){
			$sender = $this->email_default();
		}
		
		$CI->email->reply_to($recipient);
		$CI->email->from($sender, 'Panitia Penerimaan Mahasiswa Baru PUSDIKTAN');
		$CI->email->to($recipient);
		$CI->email->subject($subyek);
		$CI->email->message($message);
		return $CI->email->send();
	}
	
	function option_one($recipient, $subyek, $message){ // DILEMPAR KE SERVER LAIN YG BISA SMTP
		date_default_timezone_set('Asia/Jakarta');

        $data = array('recipient' => $recipient, 'subyek' => $subyek, 'message' => $message);
        $options = array(
            'http' => array(
                'header' => "Content-type: application/json\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($this->url(), false, $context);

        if (strpos($result, "email sent!") == true) {
            return true;
        } else {
            return false;
        }
	}
	
	function option_two($recipient, $subyek, $message){ // GUNAKAN FUNCTION MAIL STANDAR (WORK IN SHAREHOSTING)
		date_default_timezone_set('Asia/Jakarta');
		
		$sender = $this->email_default();

		$headers = "From: Admin PUSDIKTAN <" . $sender . ">\r\n" ."Reply-To: " . $recipient . "\r\n";
		$headers .= "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		
		if(mail($recipient , $subyek , $message, $headers)) {
			return true;
		} else {
			return false;
		}
	}
}
