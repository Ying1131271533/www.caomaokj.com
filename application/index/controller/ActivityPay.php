<?php
namespace app\index\controller;

use app\common\model\ActivityOrder as AO;
use think\Db;
use think\facade\View;
use WeChatPay\Builder;
use WeChatPay\Crypto\AesGcm;
use WeChatPay\Crypto\Rsa;
use WeChatPay\Formatter;
use WeChatPay\Util\PemUtil;

class ActivityPay extends Base
{
    /**
     * 初始化
     *
     */
    public function __construct(\think\App $app)
    {
        parent::__construct();
        // 获取支付宝配置
        $this->config       = config('pay.alipay.activity');
        $this->wechatConfig = config('pay.wechat.activity');
    }

    /*
     * 支付宝支付
     *
     * alipay
     */
    public function alipay()
    {
        /**********************   引用支付宝文件   **********************/
        header('Content-type:text/html;charset=utf-8');
        require '../extend/pay/alipay/pagepay/service/AlipayTradeService.php';
        require '../extend/pay/alipay/pagepay/buildermodel/AlipayTradePagePayContentBuilder.php';

        /**********************   接收参数   **********************/
        $order_id = input('order_id/d'); // 订单id

        /**********************   验证数据   **********************/
        $validate = validate('ActivityOrder');
        if (!$validate->check(['order_id' => $order_id])) {
            return akali($validate->getError());
        }

        /**********************   找出订单   **********************/
        $order = AO::with('join.activity')->get($order_id);

        /**********************   构造参数   **********************/

        // 商户订单号，商户网站订单系统中唯一订单号，必填
        $out_trade_no = $order['order_sn'];

        // 订单名称，必填
        $subject = $order['join']['activity']['title'];

        // 付款金额，必填
        $total_amount = $order['total'];

        // 商品描述
        $body = '草帽跨境活动';

        //构造参数
        $payRequestBuilder = new \AlipayTradePagePayContentBuilder();
        $payRequestBuilder->setBody($body);
        $payRequestBuilder->setSubject($subject);
        $payRequestBuilder->setTotalAmount($total_amount);
        $payRequestBuilder->setOutTradeNo($out_trade_no);
        // $payRequestBuilder->setGoodsDetail($GoodsDetail); // 商品信息暂时不用

        $aop = new \AlipayTradeService($this->config);

        /**
         * pagePay 电脑网站支付请求
         * @param $builder 业务参数，使用buildmodel中的对象生成。
         * @param $return_url 同步跳转地址，公网可以访问
         * @param $notify_url 异步通知地址，公网可以访问
         * @return $response 支付宝返回的信息
         */
        $response = $aop->pagePay($payRequestBuilder, $this->config['return_url'], $this->config['notify_url']);
        // return;
        // 输出表单
        var_dump($response);
    }

    /*
     * 支付宝手机网站支付
     *
     * wapalipay
     */
    public function wapalipay()
    {
        /**********************   引用支付宝文件   **********************/
        header('Content-type:text/html;charset=utf-8');
        require '../extend/pay/wapalipay/wappay/service/AlipayTradeService.php';
        require '../extend/pay/wapalipay/wappay/buildermodel/AlipayTradeWapPayContentBuilder.php';

        // require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'service/AlipayTradeService.php';
        // require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'buildermodel/AlipayTradeWapPayContentBuilder.php';

        /**********************   接收参数   **********************/
        $order_id = input('order_id/d'); // 订单id

        /**********************   验证数据   **********************/
        $validate = validate('ActivityOrder');
        if (!$validate->check(['order_id' => $order_id])) {
            return akali($validate->getError());
        }

        /**********************   找出订单   **********************/
        $order = AO::with('join.activity')->get($order_id);

        /**********************   构造参数   **********************/

        // 商户订单号，商户网站订单系统中唯一订单号，必填
        $out_trade_no = $order['order_sn'];

        // 订单名称，必填
        $subject = $order['join']['activity']['title'];

        // 付款金额，必填
        $total_amount = $order['total'];

        // 商品描述
        $body = '草帽跨境活动';

        // 超时时间
        $timeout_express = "1m";

        $payRequestBuilder = new \AlipayTradeWapPayContentBuilder();
        $payRequestBuilder->setBody($body);
        $payRequestBuilder->setSubject($subject);
        $payRequestBuilder->setOutTradeNo($out_trade_no);
        $payRequestBuilder->setTotalAmount($total_amount);
        $payRequestBuilder->setTimeExpress($timeout_express);

        $payResponse = new \AlipayTradeService($this->config);
        $result      = $payResponse->wapPay($payRequestBuilder, $this->config['return_url'], $this->config['notify_url']);

        return;
    }

    /*
     * 支付宝支付 - 异步通知
     *
     * alipay
     */
    public function notifyUrl()
    {
        /**********************   引用支付宝文件   **********************/
        header('Content-type:text/html;charset=utf-8');
        require '../extend/pay/alipay/pagepay/service/AlipayTradeService.php';
        require '../extend/pay/alipay/pagepay/buildermodel/AlipayTradePagePayContentBuilder.php';

        $arr          = input();
        $alipaySevice = new \AlipayTradeService($this->config);
        $alipaySevice->writeLog(var_export($arr, true));
        $result = $alipaySevice->check($arr);

        /* 实际验证过程建议商户添加以下校验。
        1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
        2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
        3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
        4、验证app_id是否为该商户本身。
         */

        if ($result) {

            //商户订单号

            $out_trade_no = $arr['out_trade_no'];

            // 支付宝交易号

            $trade_no = $arr['trade_no'];

            // 交易状态
            $trade_status = $arr['trade_status'];

            // 商品描述
            $body = $arr['body'];

            // 支付状态
            if ($arr['trade_status'] == 'TRADE_SUCCESS') {

                /**********************   修改订单状态   **********************/
                Db::table('activity_order')->where('order_sn', $out_trade_no)->update([
                    'order_status' => 1,
                    'pay_status'   => 1,
                    'pay_time'     => time(),
                    'alipay_sn'    => $trade_no,
                ]);
            }

            // ——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
            echo "success"; //请不要修改或删除

        } else {

            // 验证失败
            echo "fail";

        }
    }

    /*
     * 支付宝支付 - 同步通知
     *
     * alipay
     */
    public function returnUrl()
    {
        /**********************   引用支付宝文件   **********************/
        header('Content-type:text/html;charset=utf-8');
        require '../extend/pay/alipay/pagepay/service/AlipayTradeService.php';
        require '../extend/pay/alipay/pagepay/buildermodel/AlipayTradePagePayContentBuilder.php';

        /**********************   接收参数   **********************/
        $arr          = input();
        $alipaySevice = new \AlipayTradeService($this->config);
        $result       = $alipaySevice->check($arr);
        if ($result) {
            // 验证成功

            // 商户订单号
            $out_trade_no = htmlspecialchars($arr['out_trade_no']);

            // 支付宝交易号
            $trade_no = htmlspecialchars($arr['trade_no']);

            /**********************   修改订单状态   **********************/
            /*Db::table('activity_order')->where('order_sn', $out_trade_no)->update([
            'order_status' => 1,
            'pay_status'   => 1,
            'pay_time'     => time(),
            'alipay_sn'    => $trade_no,
            ]);*/

            return $this->redirect('activity/orderSuccess', ['order_sn' => $out_trade_no]);

        } else {

            //验证失败
            return akali('支付失败');
        }
    }

    /*
     * 微信支付页面
     *
     * wechat
     */
    public function wechat()
    {
        /**********************   接收参数   **********************/
        $order_id = input('order_id/d');

        /**********************   验证数据   **********************/
        $validate = validate('ActivityOrder');
        if (!$validate->check(['order_id' => $order_id])) {
            return akali($validate->getError());
        }

        /**********************   找出订单   **********************/
        $order = AO::with('join.activity')->get($order_id);

        /**********************   微信支付部分   **********************/

        // 商户号，假定为`1000100`
        $merchantId = $this->wechatConfig['merchantId'];
        // 商户私钥，文件路径假定为 `/path/to/merchant/apiclient_key.pem`
        $merchantPrivateKeyFilePath = $this->wechatConfig['merchantPrivateKeyFilePath']; // 注意 `file://` 开头协议不能少
        // 加载商户私钥
        $merchantPrivateKeyInstance = Rsa::from($merchantPrivateKeyFilePath, Rsa::KEY_TYPE_PRIVATE);
        $merchantCertificateSerial  = $this->wechatConfig['merchantCertificateSerial']; // API证书不重置，商户证书序列号就是个常量
        // // 也可以使用openssl命令行获取证书序列号
        // // openssl x509 -in /path/to/merchant/apiclient_cert.pem -noout -serial | awk -F= '{print $2}'
        // // 或者从以下代码也可以直接加载
        // // 「商户证书」，文件路径假定为 `/path/to/merchant/apiclient_cert.pem`
        // $merchantCertificateFilePath = 'file:///path/to/merchant/apiclient_cert.pem';// 注意 `file://` 开头协议不能少
        // // 解析「商户证书」序列号
        // $merchantCertificateSerial = PemUtil::parseCertificateSerialNo($merchantCertificateFilePath);
        // 「平台证书」，可由下载器 `./bin/CertificateDownloader.php` 生成并假定保存为 `/path/to/wechatpay/cert.pem`
        $platformCertificateFilePath = $this->wechatConfig['platformCertificateFilePath']; // 注意 `file://` 开头协议不能少
        // 加载「平台证书」公钥
        $platformPublicKeyInstance = Rsa::from($platformCertificateFilePath, Rsa::KEY_TYPE_PUBLIC);
        // 解析「平台证书」序列号，「平台证书」当前五年一换，缓存后就是个常量
        $platformCertificateSerial = PemUtil::parseCertificateSerialNo($platformCertificateFilePath);
        // 工厂方法构造一个实例
        $instance = Builder::factory([
            'mchid'      => $merchantId,
            'serial'     => $merchantCertificateSerial,
            'privateKey' => $merchantPrivateKeyInstance,
            'certs'      => [
                $platformCertificateSerial => $platformPublicKeyInstance,
            ],
            // APIv2密钥(32字节)--不使用APIv2可选
            // 'secret' => 'exposed_your_key_here_have_risks',// 值为占位符，如需使用APIv2请替换为实际值
            // 'merchant' => [// --不使用APIv2可选
            //     // 商户证书 文件路径 --不使用APIv2可选
            //     'cert' => $merchantCertificateFilePath,
            //     // 商户API私钥 文件路径 --不使用APIv2可选
            //     'key' => $merchantPrivateKeyFilePath,
            // ],
        ]);

        /**********************   构造参数   **********************/

        try {
            $resp = $instance
                ->v3->pay->transactions->native
                ->post(['json' => [
                    'mchid'        => $this->wechatConfig['merchantId'],
                    'out_trade_no' => $order['order_sn'],
                    'appid'        => $this->wechatConfig['appid'],
                    'description'  => $order['join']['activity']['title'],
                    'notify_url'   => $this->wechatConfig['notify_url'],
                    'amount'       => [
                        'total'    => $order['total'],
                        'currency' => $this->wechatConfig['currency'],
                    ],
                ]]);

            echo $resp->getStatusCode(), PHP_EOL;
            echo $resp->getBody(), PHP_EOL;
        } catch (\Exception $e) {
            // 进行错误处理
            echo $e->getMessage(), PHP_EOL;
            if ($e instanceof \GuzzleHttp\Exception\RequestException && $e->hasResponse()) {
                $r = $e->getResponse();
                echo $r->getStatusCode() . ' ' . $r->getReasonPhrase(), PHP_EOL;
                echo $r->getBody(), PHP_EOL, PHP_EOL, PHP_EOL;
            }
            echo $e->getTraceAsString(), PHP_EOL;
        }
        dump($resp);return;
        /**********************   变量赋值   **********************/
        View::assign([
            'wechat_qr' => $url2,
            'order'     => $order,
            'join'      => $order['join'],
            'activity'  => $order['join']['activity'],
        ]);

        return View::fetch();
    }

    /*
     * 支付宝支付 - 异步通知
     *
     * alipay
     */
    public function wecathNotifyUrl()
    {
        $inWechatpaySignature = ''; // 请根据实际情况获取
        $inWechatpayTimestamp = ''; // 请根据实际情况获取
        $inWechatpaySerial    = ''; // 请根据实际情况获取
        $inWechatpayNonce     = ''; // 请根据实际情况获取
        $inBody               = ''; // 请根据实际情况获取，例如: file_get_contents('php://input');

        $apiv3Key = ''; // 在商户平台上设置的APIv3密钥

        // 根据通知的平台证书序列号，查询本地平台证书文件，
        // 假定为 `/path/to/wechatpay/inWechatpaySerial.pem`
        $platformPublicKeyInstance = Rsa::from('file:///path/to/wechatpay/inWechatpaySerial.pem', Rsa::KEY_TYPE_PUBLIC);

        // 检查通知时间偏移量，允许5分钟之内的偏移
        $timeOffsetStatus = 300 >= abs(Formatter::timestamp() - (int) $inWechatpayTimestamp);
        $verifiedStatus   = Rsa::verify(
            // 构造验签名串
            Formatter::joinedByLineFeed($inWechatpayTimestamp, $inWechatpayNonce, $inBody),
            $inWechatpaySignature,
            $platformPublicKeyInstance
        );
        if ($timeOffsetStatus && $verifiedStatus) {
            // 转换通知的JSON文本消息为PHP Array数组
            $inBodyArray = (array) json_decode($inBody, true);
            // 使用PHP7的数据解构语法，从Array中解构并赋值变量
            ['resource' => [
                'ciphertext'      => $ciphertext,
                'nonce'           => $nonce,
                'associated_data' => $aad,
            ]] = $inBodyArray;
            // 加密文本消息解密
            $inBodyResource = AesGcm::decrypt($ciphertext, $apiv3Key, $nonce, $aad);
            // 把解密后的文本转换为PHP Array数组
            $inBodyResourceArray = (array) json_decode($inBodyResource, true);
            print_r($inBodyResourceArray); // 打印解密后的结果
        }
    }
}
