<?php

return [
    'app_id'           => 'wx40069438ee16db7b',
    'app_secret'       => '2e0065a1573e1b0825e954763f34853f',
    'access_token_url' => 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s',
    'jsapi_ticket_url' => 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=%s&type=jsapi',
    'signature_string' => 'jsapi_ticket=%s&noncestr=%s&timestamp=%d&url=%s',
];
