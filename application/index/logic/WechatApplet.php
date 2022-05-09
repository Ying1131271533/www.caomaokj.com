<?php

namespace app\index\logic;

use think\facade\Cache;

class WechatApplet
{
    // 签名参数
    protected $jsapiTicket;
    protected $noncestr;
    protected $timestamp;
    protected $url;

    // 微信配置
    protected $wxAppID;
    protected $wxAppSecret;
    protected $wxLoginUrl;

    public function __construct($time, $rand_char, $url)
    {
        $this->noncestr         = $rand_char;
        $this->url              = $url;
        $this->timestamp        = $time;

        $this->wxAppID          = config('wechat.app_id');
        $this->wxAppSecret      = config('wechat.app_secret');
        $this->wxAccessTokenUrl = sprintf(config('wechat.access_token_url'), $this->wxAppID, $this->wxAppSecret);
    }

    // 获取签名
    public function getWxSignature()
    {
        // 获取微信access_token
        $access_token = $this->getAccessToken();
        if (!$access_token) {
            return '';
        }
        
        // 获取微信ticket
        $ticket = $this->getJsapiTicket($access_token);
        if (!$ticket) {
            return '';
        }
        $this->jsapiTicket = $ticket;
        
        // 获取生成的签名
        $signature = $this -> getSignature();
        return $signature;
    }
    
    // 将参数生成签名
    public function getSignature(){
        $signature_string = sprintf(config('wechat.signature_string'), $this->jsapiTicket, $this->noncestr, $this->timestamp, $this->url);
        // dump($signature_string);
        $signature = sha1($signature_string);
        // halt($signature);
        return $signature;
    }

    // 获取微信token
    public function getAccessToken()
    {
        // 微信token是否已获取
        $wechat_access_token = Cache::store('redis')->get('wechat_access_token');
        if ($wechat_access_token) {
            return $wechat_access_token;
        }
        // https请求方式: GET
        $url          = sprintf($this->wxAccessTokenUrl, $this->wxAppID, $this->wxAppSecret);
        $access_token = curl_get($url);
        $access_token = json_decode($access_token, true);
        if (empty($access_token)) {
            return '';
        }
        
        $tokenFail = array_key_exists('errcode', $access_token);
        if ($tokenFail) {
            return '';
        } else {
            $result = Cache::store('redis')->set('wechat_access_token', $access_token['access_token'], 5400);
            if (!$result) {
                return '';
            }
            return $access_token['access_token'];
        }
    }

    // 获取微信jsapi_ticket
    public function getJsapiTicket($access_token)
    {
        // 微信jsapi_ticket是否已获取
        $wechat_access_token = Cache::store('redis')->get('wechat_ticket');
        // $wechat_access_token = Cache::store('redis')->get('wechat_ticket');
        if ($wechat_access_token) {
            return $wechat_access_token;
        }

        $url = config('wechat.jsapi_ticket_url');
        $url = sprintf($url, $access_token);
        $jsapi_ticket = curl_get($url);
        $jsapi_ticket = json_decode($jsapi_ticket, true);
        if (empty($jsapi_ticket)) {
            return '';
        } else {
            // $ticketFail = array_key_exists('errcode', $jsapi_ticket);
            if ($jsapi_ticket['errcode'] != 0) {
                return '';
            } else {
                $result = Cache::store('redis')->set('wechat_ticket', $jsapi_ticket['ticket'], 5400);
                if (!$result) {
                    return '';
                }
                return $jsapi_ticket['ticket'];
            }
        }
    }
}
