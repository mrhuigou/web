<?php
namespace common\component\Payment\Bestone;
class BestoneSubmit
{

    function create_html($args)
    {
        $pay_url = BestoneConfig::$pay_url;
        $html = "<form  id='bestone_submit' name='pay_form' action='{$pay_url}' method='post'>";
        foreach ($args as $key => $value) {
            $html .= " <input type=\"hidden\" name=\"{$key}\" id=\"{$key}\" value=\"{$value}\" />\n";
        }
        $html .= "</form>";
        $html = $html . "<script>document.forms['bestone_submit'].submit();</script>";
        return $html;
    }

    function create_query_string($args)
    {
        $query_string = '';
        foreach ($args as $key => $value) {
            if ($key != 'sign' && $key != 'sign_type' && !empty($value)) {
                $query_string .= sprintf("%s=%s&", $key, ($value));
            }
        }
        $query_string = trim($query_string,'&');
        return $query_string;
    }

    // params array
    function getSign($params)
    {
        ksort($params);
        $args_string = $this->create_query_string($params);
        //print_r($args_string);exit;
        $sign = $this->hmac($args_string, BestoneConfig::$appkey);
        return $sign;
    }

    function hmac($data, $key)
    {
        if (function_exists('hash_hmac')) {
            return hash_hmac('md5', $data, $key);
        }

        $key = (strlen($key) > 64) ? pack('H32', 'md5') : str_pad($key, 64, chr(0));
        $ipad = substr($key, 0, 64) ^ str_repeat(chr(0x36), 64);
        $opad = substr($key, 0, 64) ^ str_repeat(chr(0x5C), 64);
        return md5($opad . pack('H32', md5($ipad . $data)));
    }

    function verifySign($data)
    {
        if($data && isset($data['sign'])){
            $get_sign = $data['sign'];
            $my_sign = $this->getSign($data);
            if ($my_sign == $get_sign) {
                return true;
            } else {
                return false;
            }
        }

    }

    function get_signature($str, $key)
    {
        $signature = "";
        if (function_exists('hash_hmac')) {
            $signature = base64_encode(hash_hmac("sha1", $str, $key, true));
        } else {
            $blocksize = 64;
            $hashfunc = 'sha1';
            if (strlen($key) && $blocksize) {
                $key = pack('H*', $hashfunc($key));
            }
            $key = str_pad($key, $blocksize, chr(0x00));
            $ipad = str_repeat(chr(0x36), $blocksize);
            $opad = str_repeat(chr(0x5c), $blocksize);
            $hmac = pack(
                'H*', $hashfunc(
                    ($key ^ $opad) . pack(
                        'H*', $hashfunc(
                            ($key ^ $ipad) . $str
                        )
                    )
                )
            );
            $signature = base64_encode($hmac);
        }

        return $signature;

    }
}