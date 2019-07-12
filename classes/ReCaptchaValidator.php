<?php

    namespace Martin\Forms\Classes;

    use Request;
    use Martin\Forms\Models\Settings;
	
    class ReCaptchaValidator {
    	
	public function validateReCaptcha($attribute, $value, $parameters) {
            $secret_key = Settings::get('recaptcha_secret_key');
            $recaptcha  = post('g-recaptcha-response');
            $ip         = Request::getClientIp();
            $data = array(
            'secret' => $secret_key,
            'response' => $recaptcha,
            'remoteip' => $ip
        	);
      
            if (!function_exists('curl_init')){ 
		        die('CURL is not installed!');
		    }
		    $verify = curl_init();
		    curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
		    curl_setopt($verify, CURLOPT_POST, true);
		    curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
		    curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
		    $response = json_decode(curl_exec($verify), true);
		    curl_close($verify);
		    return ($response['success'] == true);
        }


    }
    

?>
