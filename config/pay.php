<?php

return [
    'alipay' => [

        'activity' => [
            //应用ID,您的APPID
            'app_id'               => "2021002189685626",

            //商户私钥
            'merchant_private_key' => "MIIEpAIBAAKCAQEAw7NhbE8sZcKpOt/Dth0fnK56voGxIczIX56AN5FDI8/G5oQcJ9JUMXPv9Pfb3R0h/aI6Dqg7j+uBeahRbXuWp2khMiJBLPZcYJDyIx+sbHEflqdNROjDlTtG5p4drFa7ZEFgr6fJsBytugTYSryi3HgfLMGpQJ70mjEhOCQQWsif2JOEevSMT57QCiphP65eSue08Zk1E863G18VwygfTsbAyB4/FuCEvQwY/O7y0/Aej/262UdFC2ZxHvGWsda8255sryoYEAIrAtmpaDJ2YnW88dH4S9hdXjzg6NPSQExcsQtrhYTKSr7DRnC4X6xW8bFbFonlIRMTse4jh3qIUQIDAQABAoIBAFL/uhrofUjvRTy9+FA9i9G0wkQX1xshsf2zyGdIyXPfxFixwQs2jhSnOiboFCx0FZ1vdzYOLay9Uh1ZwKcxWlTIJpcPphq+pbEi6D7zSPp5A8+uRvwpyAF7sXdPqgAUjMdZXvtAqy5xC1Ewitcvp9bfb0FZJUJN6CmZGqCn0vPdEuCjLg8Gy6UOnfKwXOFO75kBc7q4F9pAoWS/ohd2uqZ6fpxPPNAJoMKlsEJhBKMlR7W19cLaC/w4dx+6zq7xILwDjWGuTUKsIfppdyU53bb1Fw4bXg13mQfz7FDiQ3BFnpp7B3eU22cVfOizp8wKciZ+d8X6Shh7d37iAx46W1ECgYEA5ELxXQtPJD8kLuzFK0G2x8hJBInZGMaWZEz+SeiTonx7iMW/gSybjb4cqZ990KHlG6srj/Cl2P14qvOzm7ZkehgfCNZ9BTgba4x+7gOT3rmlB5rDNemsN4ubkPCGhzEokq63CVjNnT0HBKydUo1wcr3DRKl2iGHTCBR1zCpqdUUCgYEA23t+fELP0E7LHjcin94LxL8VwMCAnmcrj67EpYKwGVbWd+TFBWAOREuWoVk/qjreAttpPVl1V9g10rFvySze2AiU0/Ou/WlIO5pRB0eK4gi+AuhFu+tc6WE5aJv2o8ucAxyqTCzEjwYjgUbrM6V4PqOlKHcFrNUoAbhU8jRneZ0CgYBhnctQyjRXQPV9Z95OSHUdbSbk3PrN4KeST2b0PpVciLXf4Qmr+WKwtFvXopCQt5ueQI6JqRWT/ZosKbpm3hKMMGKdSfT/VWZMkTH7IeaL+oJ8BRB0rvDqdDbcPjCaAkjiqfs28RZFp0KoGhXRpxEu0L6AT6SUYJl6PB3AaWdLrQKBgQCIQAWpRiCUBBX7z+ZisAlGPglW+yndK9bS/39778E6Obox+4aDa7nvk60SxMDNAHZJ98NBHoPF4KapARWQIZbFLa6WVTRQhEcfGREsH7GL2Dl8vIxtuBKZno1w70ERNZjIJcdiOnhbAUiRPGFOUm5vFE+26wxO2pRXXQL+yKFj0QKBgQCA7MVrnH7hcwAES0S8mxyxj2TeRcbRhA9Y6zgv8tPjqHyZFoxM1zkguRj1FXZu0PZfmny3JbikO38YNt2c3dtbsjoUXg/6uo5Kz8ifYd4GgxneTzLt12qttUYG7Y59eCbiqn7WL7hztV0wpZFE6Sz5W+Ra1uRTzyaMgwbBeys7nw==",

            //异步通知地址
            'notify_url'           => "https://www.caomaokj.com/index/activity_pay/notifyUrl",

            //同步跳转
            'return_url'           => "https://www.caomaokj.com/index/activity_pay/returnUrl",

            //编码格式
            'charset'              => "UTF-8",

            //签名方式
            'sign_type'            => "RSA2",

            //支付宝网关
            'gatewayUrl'           => "https://openapi.alipay.com/gateway.do",

            //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
            'alipay_public_key'    => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAh7l5A0sCDdudzJZElD47Op/FqUTGcZ0Q/djpu6/0+nynL5r0eilnsEDNH2RQHNNtirL+RA1mD7Q3J4jis8Eh0xbH5RHK/lFngTc67a8mv97b1e6Vks0PxrwIjZl7ZPP/Tm4gQaRPslZW+1aMrPqqXTGRmd1hxlEsykzSHPTtQ107x3rGpKTg4LeBX9DiARyn3PQFIw+UkeLvfolmXrC5LD1ES4zaCAJjRMYzJHszljBKP4zF2Y5ZndOCLRJU5IxhP3pPuBU4pPdI1hFMfOIQH6GK2EKXmwLkHGk2lckBi1pu87wp1N4wF9Ll25bpRF4jKPyJzacHW3x/wQDmkCNIXQIDAQAB",
        ],
        'college'  => [
            //应用ID,您的APPID
            'app_id'               => "2021002189685626",

            //商户私钥
            'merchant_private_key' => "MIIEpAIBAAKCAQEAw7NhbE8sZcKpOt/Dth0fnK56voGxIczIX56AN5FDI8/G5oQcJ9JUMXPv9Pfb3R0h/aI6Dqg7j+uBeahRbXuWp2khMiJBLPZcYJDyIx+sbHEflqdNROjDlTtG5p4drFa7ZEFgr6fJsBytugTYSryi3HgfLMGpQJ70mjEhOCQQWsif2JOEevSMT57QCiphP65eSue08Zk1E863G18VwygfTsbAyB4/FuCEvQwY/O7y0/Aej/262UdFC2ZxHvGWsda8255sryoYEAIrAtmpaDJ2YnW88dH4S9hdXjzg6NPSQExcsQtrhYTKSr7DRnC4X6xW8bFbFonlIRMTse4jh3qIUQIDAQABAoIBAFL/uhrofUjvRTy9+FA9i9G0wkQX1xshsf2zyGdIyXPfxFixwQs2jhSnOiboFCx0FZ1vdzYOLay9Uh1ZwKcxWlTIJpcPphq+pbEi6D7zSPp5A8+uRvwpyAF7sXdPqgAUjMdZXvtAqy5xC1Ewitcvp9bfb0FZJUJN6CmZGqCn0vPdEuCjLg8Gy6UOnfKwXOFO75kBc7q4F9pAoWS/ohd2uqZ6fpxPPNAJoMKlsEJhBKMlR7W19cLaC/w4dx+6zq7xILwDjWGuTUKsIfppdyU53bb1Fw4bXg13mQfz7FDiQ3BFnpp7B3eU22cVfOizp8wKciZ+d8X6Shh7d37iAx46W1ECgYEA5ELxXQtPJD8kLuzFK0G2x8hJBInZGMaWZEz+SeiTonx7iMW/gSybjb4cqZ990KHlG6srj/Cl2P14qvOzm7ZkehgfCNZ9BTgba4x+7gOT3rmlB5rDNemsN4ubkPCGhzEokq63CVjNnT0HBKydUo1wcr3DRKl2iGHTCBR1zCpqdUUCgYEA23t+fELP0E7LHjcin94LxL8VwMCAnmcrj67EpYKwGVbWd+TFBWAOREuWoVk/qjreAttpPVl1V9g10rFvySze2AiU0/Ou/WlIO5pRB0eK4gi+AuhFu+tc6WE5aJv2o8ucAxyqTCzEjwYjgUbrM6V4PqOlKHcFrNUoAbhU8jRneZ0CgYBhnctQyjRXQPV9Z95OSHUdbSbk3PrN4KeST2b0PpVciLXf4Qmr+WKwtFvXopCQt5ueQI6JqRWT/ZosKbpm3hKMMGKdSfT/VWZMkTH7IeaL+oJ8BRB0rvDqdDbcPjCaAkjiqfs28RZFp0KoGhXRpxEu0L6AT6SUYJl6PB3AaWdLrQKBgQCIQAWpRiCUBBX7z+ZisAlGPglW+yndK9bS/39778E6Obox+4aDa7nvk60SxMDNAHZJ98NBHoPF4KapARWQIZbFLa6WVTRQhEcfGREsH7GL2Dl8vIxtuBKZno1w70ERNZjIJcdiOnhbAUiRPGFOUm5vFE+26wxO2pRXXQL+yKFj0QKBgQCA7MVrnH7hcwAES0S8mxyxj2TeRcbRhA9Y6zgv8tPjqHyZFoxM1zkguRj1FXZu0PZfmny3JbikO38YNt2c3dtbsjoUXg/6uo5Kz8ifYd4GgxneTzLt12qttUYG7Y59eCbiqn7WL7hztV0wpZFE6Sz5W+Ra1uRTzyaMgwbBeys7nw==",

            //异步通知地址
            'notify_url'           => "https://www.caomaokj.com/index/college_pay/notifyUrl",

            //同步跳转
            'return_url'           => "https://www.caomaokj.com/index/college_pay/returnUrl",

            //编码格式
            'charset'              => "UTF-8",

            //签名方式
            'sign_type'            => "RSA2",

            //支付宝网关
            'gatewayUrl'           => "https://openapi.alipay.com/gateway.do",

            //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
            'alipay_public_key'    => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAh7l5A0sCDdudzJZElD47Op/FqUTGcZ0Q/djpu6/0+nynL5r0eilnsEDNH2RQHNNtirL+RA1mD7Q3J4jis8Eh0xbH5RHK/lFngTc67a8mv97b1e6Vks0PxrwIjZl7ZPP/Tm4gQaRPslZW+1aMrPqqXTGRmd1hxlEsykzSHPTtQ107x3rGpKTg4LeBX9DiARyn3PQFIw+UkeLvfolmXrC5LD1ES4zaCAJjRMYzJHszljBKP4zF2Y5ZndOCLRJU5IxhP3pPuBU4pPdI1hFMfOIQH6GK2EKXmwLkHGk2lckBi1pu87wp1N4wF9Ll25bpRF4jKPyJzacHW3x/wQDmkCNIXQIDAQAB",
        ],
    ],
    'wechat' => [

        'activity' => [

            // APPID
            'appid'                       => '1615672936',

            // 商户号
            'merchantId'                  => '1615672936',

            // 商户API证书序列号
            'merchantCertificateSerial'   => '22790A8E8CF22C1490192851ABE389AC75761480',

            // 商户私钥，文件路径假定为 `/path/to/merchant/apiclient_key.pem`
            'merchantPrivateKeyFilePath'  => 'file:///pay/wechatpay/apiclient_key.pem', // 注意 `file://` 开头协议不能少

            // 「平台证书」，可由下载器 `./bin/CertificateDownloader.php` 生成并假定保存为 `/path/to/wechatpay/cert.pem`
            'platformCertificateFilePath' => 'file:///pay/wechatpay/apiclient_cert.pem', // 注意 `file://` 开头协议不能少

            'apiKey'                      => 'de8b8c9dcb0a7666eec3d58388cde6e1', // APIv3密钥

            // 微信网关
            'gatewayUrl'                  => "https://api.mch.weixin.qq.com/v3/pay/transactions/native",

            // 异步通知地址
            'notify_url'                  => "https://www.caomaokj.com/index/activity_pay/wecathNotifyUrl",

            // 币种
            'currency'                    => "CNY",
        ],

    ],
];
