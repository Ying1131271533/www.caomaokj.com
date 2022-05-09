<?php
$config = array(
    //应用ID,您的APPID。
    'app_id'               => "2021000118630861",

    //商户私钥，您的原始格式RSA私钥
    'merchant_private_key' => "MIIEogIBAAKCAQEAsv6Mf2ethv1o5EWCCYoiUiFmVxMa0yyiLTSnRtfHceqaz1ZgauI1xDcl4hcGjAAeaA8TKzXq/MO+oYyV2mWFyggD8EGoN0PQvPxKKOwPpVOomAy+/vIQsll62JNCRjoxtwYFWKZ8wRXJEpmmdLNS5vVP3ynpYtG7s5jPQB5pT6rX05+bQopP6DwKBLswod2lubsu4nOk3/oAiLgwail6RY1mDWiQ4xIoDwJzjv99/MQ8jOqneUhv7VhGOuhy4FzjTQjkwpp1bHcPv0QAyrZ4yBtnPyvRuV3si/Blr+4QB9pXhtORhd/PPmTSYIperW76XQNsvV9wa9AkJuJEq1d24wIDAQABAoIBADeC9EXK/KkhzDcHS4Xz3iMEkr2sgiQ98mn8q2gwIX212Z811Z8wWqZoyAnvRCuFGekBy9sZ1K/hcSye8haSZ8S5aadIZYgGMyV5RPKeC1glsuK9CTr+DamSUKP8P5CSGbr+VgghUg172Sk4l8QXnT1dlRDL6cKo8wKHALeM9vIqsA2GbEbutnCBXtP0/HEd0CGSHwSUUZ5N+NUFUgKtP2himomUvs0xdu4BYsxYqmEYRPdpZJGi6Mchy0eSmjDS5m1sSnGL3vRxPtseJ8yhGfkAJ+L1bB5BqU8oDD+Uuv+NmQ1SeX3gKRKmNNuu8V6t2g1fNwaBAN6eN+8EidNzxOkCgYEA2LFVEqbjqAiu4J4wWqhBSgbZsUAQThDtVN4gl6ChsVKsTwHPmRtQS5Bj+7BraXgZASHlfvUMkW9ALPy3SH4Ujq2hK1UoXkMufJCYjJy5xzYcBUIfDkfpSjhjxGcBwe46bsvPHNqKyTFXXVwurZTivNF6rFSFxk6SYIDFHLqea50CgYEA03aZJ/jhuSBH++bxAQy9Z7wsSXff5Ev82V/5vTz2nDPB9JHPTJ+Je6sdRJJySlQX10BeK3AzhpoS4QNFyX7MFkwD25AaooMSs3Jre7OmYgRWGl65BCMG/BIC6Fo0QT/RmkZ3WkcT5NOwn4cTMUISYk7Ytt2osG3jVeCtHCa2JH8CgYBJhlokWwYx9MhJpMMpb5+/m3VwBBnEM1AkFL6gVDuo7DFVGXs0KyVijlqugtzfS4XIcZTqMAGUs1fmK9WQvl8BTn66MknhOhBi6bkpNOCAbKazTg01jkblDR8k/AKHp+qhv3vGAY1H2PsYzXd1JGiOwtFJ+d/uOqjGH8tCBTxNrQKBgEJMJZptoj9LSqZEcnz0xJE4Yt4p0i0eUcjJGWBmM58nbwDBki+guRbnwAZWr7R0BwF6ccvGorDpr0+SmhehRFUdQfxdgqw+0ecm0WH4IgsK9v/PgMw2OyNedkDovwdR/eITg6nvLOKKKA7r522DHIZr1/AFMLUXzrWG+l8gF+z7AoGAME6NVoaP12RQnkZ0Kpf6OZ0iE2i6cduIqFqdjD7/9x1TdGNEfaojdX4WgkZPE5dfJnJo3Cv7/Ou3UcOt33FUjS3WXaXif16kTTx6CpTBaE4ikkXUxeJ5sYFkF9RLfYnDA6zr2aTiTw7B4tlO2Beixo68sefaAS+tlj0ZXJBMBzo=",

    //异步通知地址
    'notify_url'           => "http://www.caomaokj.com/index/activity_pay/notifyUrl",

    //同步跳转
    'return_url'           => "http://www.caomaokj.com/index/activity_pay/returnUrl",

    //编码格式
    'charset'              => "UTF-8",

    //签名方式
    'sign_type'            => "RSA2",

    //支付宝网关
    'gatewayUrl'           => "https://openapi.alipaydev.com/gateway.do",

    //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
    'alipay_public_key'    => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAlJuhJ0zG0eYrgLeAVjyx1O7sr4ecqh7+MisnELRHtXY1Zu+rHP2JiPKOWNvmXK+05bVfJueYCvXDI7KIEMTkRpGlY3BpZ9jlBQ2v2I1ZWJBmKfOFqGIypQLCvXNowjPjFV+Jl7VFVkytDXKTKMM/Ww0QhzOniWWZ6EpfIyWazBO0d+QcI8Gu6rF1QDNliELkX3GuZNleINKAbO1FaFupND6qMr97RMJqrYp4m1iIKf8G1m6T4c5K3KAgqxwsfMunTnsiAt5fK+Xq4TKMx2KBcXpi/YtyGXFFuJNWb0Ra7nm4aF7kFT5o7eXmeCZt6f715MsWRkN3x25r3wTXfKwLzQIDAQAB",

);
