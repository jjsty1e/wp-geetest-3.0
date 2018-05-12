<?php
define('GT_API_SERVER', 'https://api.geetest.com');
define('GT_SDK_VERSION', 'wordpress_1.0');

class geetestlib
{

    public static $connectTimeout = 1;
    public static $socketTimeout  = 1;

    private $privatekey;
    private $captchaid;
    private $response;

    function __construct()
    {
        $this->challenge = "";
    }

    function register($pubkey)
    {
        $param = array(
            "user_id" => "test", # 网站用户id
            "client_type" => "web", #web:电脑上的浏览器；h5:手机上的浏览器，包括移动应用内完全内置的web_view；native：通过原生SDK植入APP应用的方式
            "ip_address" => "127.0.0.1" # 请在此处传输用户请求验证时所携带的IP
        );

        $data = array(
            'gt' => $pubkey,
            'new_captcha' => 1
        );

        $data = array_merge($data, $param);

        $query = http_build_query($data);
        $url = "http://api.geetest.com/register.php?" . $query;
        $this->challenge = $this->send_request($url);

        if (strlen($this->challenge) != 32) {
            $this->failback_process();
            $status = 0;
        }
        $this->success_process($this->challenge);
        $status =  1;

        $_SESSION['gtserver'] = $status;
        $_SESSION['user_id'] = $data['user_id'];

        return $this->get_response_str();
    }

    /**
     * @param $challenge
     */
    private function success_process($challenge) {
        $challenge      = md5($challenge . $this->privatekey);
        $result         = array(
            'success'   => 1,
            'gt'        => $this->captchaid,
            'challenge' => $challenge,
            'new_captcha'=>1
        );
        $this->response = $result;
    }

    /**
     *
     */
    private function failback_process() {
        $rnd1           = md5(rand(0, 100));
        $rnd2           = md5(rand(0, 100));
        $challenge      = $rnd1 . substr($rnd2, 0, 2);
        $result         = array(
            'success'   => 0,
            'gt'        => $this->captchaid,
            'challenge' => $challenge,
            'new_captcha'=>1
        );
        $this->response = $result;
    }

    /**
     * @return mixed
     */
    public function get_response_str() {
        return json_encode($this->response);
    }

    function get_widget($captchaid, $is_md5, $privatekey, $button, $lang_options)
    {
        $this->privatekey = $privatekey;
        $this->captchaid = $captchaid;

        $challengeData = $this->register($captchaid);
        $challengeData = json_decode($challengeData, true);

        $challenge = $challengeData['challenge'];

        if ($lang_options == 1) {
            $lang = "en";
        } else {
            $lang = "zh-cn";
        }

        $output = '<script type="text/javascript">';
        $output .= 'var handler = function (captchaObj) {captchaObj.appendTo("#';
        $output .= $button;
        $output .= '");};';
        $output .= "\r\n";

        $output .= 'initGeetest({gt:"';
        $output .= $captchaid;
        $output .= '",challenge:"';
        $output .= $challenge;
        $output .= '",new_captcha:"1';
        $output .= '",lang:"';
        $output .= $lang;
        $output .= '",product:"popup",}, handler);';
        $output .= '</script>' . "\n";
        return $output;
    }

    private function send_request($url)
    {
        if (function_exists('curl_exec')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, self::$connectTimeout);
            curl_setopt($ch, CURLOPT_TIMEOUT, self::$socketTimeout);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $data = curl_exec($ch);
            $curl_errno = curl_errno($ch);
            curl_close($ch);
            if ($curl_errno >0) {
                return 0;
            }else{
                return $data;
            }
        } else {
            $opts    = array(
                'http' => array(
                    'method'  => "GET",
                    'timeout' => self::$connectTimeout + self::$socketTimeout,
                )
            );
            $context = stream_context_create($opts);
            $data    = @file_get_contents($url, false, $context);
            if($data){
                return $data;
            }else{
                return 0;
            }
        }
    }

    /**
     * Calls an HTTP POST function to verify if the user's guess was correct
     * @param string $privkey
     * @param string $remoteip
     * @param string $challenge
     * @param string $response
     * @param array $extra_params an array of extra variables to post to the server
     * @return ReCaptchaResponse
     */
    function geetest_check_answer($privkey, $challenge, $validate, $seccode)
    {
        if ($privkey == null || $privkey == '') {
            die ("To use GeeTest you must get an API key from <a href='http://www.geetest.com/'>http://www.geetest.com/</a>");
        }

        return $this->geetest_validate($privkey, $challenge, $validate, $seccode);
    }

    function geetest_validate($privkey, $challenge, $validate, $seccode)
    {
        $apiserver = 'api.geetest.com';
        if (strlen($validate) > 0 && $validate == md5($privkey . 'geetest' . $challenge)) {
            $query = http_build_query(array("seccode" => $seccode, "sdk" => GT_SDK_VERSION));
            $servervalidate = $this->_http_post($apiserver, '/validate.php', $query);
            if (strlen($servervalidate) > 0 && $servervalidate == md5($seccode)) {
                return TRUE;
            }
        }

        return FALSE;
    }

    /**
     *解码随机参数
     *
     * @param $challenge
     * @param $string
     * @return
     */
    private function decode_response($challenge, $string)
    {
        if (strlen($string) > 100) {
            return 0;
        }
        $key = array();
        $chongfu = array();
        $shuzi = array("0" => 1, "1" => 2, "2" => 5, "3" => 10, "4" => 50);
        $count = 0;
        $res = 0;
        $array_challenge = str_split($challenge);
        $array_value = str_split($string);
        for ($i = 0; $i < strlen($challenge); $i++) {
            $item = $array_challenge[$i];
            if (in_array($item, $chongfu)) {
                continue;
            } else {
                $value = $shuzi[$count % 5];
                array_push($chongfu, $item);
                $count++;
                $key[$item] = $value;
            }
        }

        for ($j = 0; $j < strlen($string); $j++) {
            $res += $key[$array_value[$j]];
        }
        $res = $res - $this->decodeRandBase($challenge);
        return $res;
    }


    /**
     *
     * @param $x_str
     * @return
     */
    private function get_x_pos_from_str($x_str)
    {
        if (strlen($x_str) != 5) {
            return 0;
        }
        $sum_val = 0;
        $x_pos_sup = 200;
        $sum_val = base_convert($x_str, 16, 10);
        $result = $sum_val % $x_pos_sup;
        $result = ($result < 40) ? 40 : $result;
        return $result;
    }

    /**
     *
     * @param full_bg_index
     * @param img_grp_index
     * @return
     */
    private function get_failback_pic_ans($full_bg_index, $img_grp_index)
    {
        $full_bg_name = substr(md5($full_bg_index), 0, 9);
        $bg_name = substr(md5($img_grp_index), 10, 9);

        $answer_decode = "";
        // 通过两个字符串奇数和偶数位拼接产生答案位
        for ($i = 0; $i < 9; $i++) {
            if ($i % 2 == 0) {
                $answer_decode = $answer_decode . $full_bg_name[$i];
            } elseif ($i % 2 == 1) {
                $answer_decode = $answer_decode . $bg_name[$i];
            }
        }
        $x_decode = substr($answer_decode, 4, 5);
        $x_pos = $this->get_x_pos_from_str($x_decode);
        return $x_pos;
    }

    /**
     * 输入的两位的随机数字,解码出偏移量
     *
     * @param challenge
     * @return
     */
    private function decodeRandBase($challenge)
    {
        $base = substr($challenge, 32, 2);
        $tempArray = array();
        for ($i = 0; $i < strlen($base); $i++) {
            $tempAscii = ord($base[$i]);
            $result = ($tempAscii > 57) ? ($tempAscii - 87) : ($tempAscii - 48);
            array_push($tempArray, $result);
        }
        $decodeRes = $tempArray['0'] * 36 + $tempArray['1'];
        return $decodeRes;
    }

    /**
     * 得到答案
     *
     * @param validate
     * @return
     */
    public function get_answer($validate)
    {
        if ($validate) {
            $value = explode("_", $validate);
            $challenge = $_SESSION['challenge'];
            $ans = $this->decode_response($challenge, $value['0']);
            $bg_idx = $this->decode_response($challenge, $value['1']);
            $grp_idx = $this->decode_response($challenge, $value['2']);
            $x_pos = $this->get_failback_pic_ans($bg_idx, $grp_idx);
            $answer = abs($ans - $x_pos);
            if ($answer < 4) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }

    }

    function _http_post($host, $path, $data, $port = 80)
    {
        $http_request = "POST $path HTTP/1.0\r\n";
        $http_request .= "Host: $host\r\n";
        $http_request .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $http_request .= "Content-Length: " . strlen($data) . "\r\n";
        $http_request .= "\r\n";
        $http_request .= $data;

        $response = '';
        if (($fs = @fsockopen($host, $port, $errno, $errstr, 10)) == false) {
            die ('Could not open socket! ' . $errstr);
        }

        fwrite($fs, $http_request);

        while (!feof($fs))
            $response .= fgets($fs, 1160);
        fclose($fs);

        $response = explode("\r\n\r\n", $response, 2);
        return $response[1];
    }

    public function send_post($url, $post_data)
    {
        $postdata = http_build_query($post_data);
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-type:application/x-www-form-urlencoded',
                'content' => $postdata,
                'timeout' => 15 * 60 // expire time
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return $result;
    }
}

?>