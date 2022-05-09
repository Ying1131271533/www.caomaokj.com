<?php
namespace app\index\controller;

use app\common\model\CollegeOrder as CO;
use think\Db;
use think\facade\View;

class CollegePay extends Base
{
    /**
     * 初始化
     *
     */
    public function __construct(\think\App $app)
    {
        parent::__construct();
        // 获取支付宝配置
        $this->config = config('pay.alipay.college');
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
        $validate = validate('CollegeOrder');
        if (!$validate->check(['order_id' => $order_id])) {
            return akali($validate->getError());
        }

        /**********************   找出订单   **********************/
        $order = CO::with('join.college')->get($order_id);

        /**********************   构造参数   **********************/

        // 商户订单号，商户网站订单系统中唯一订单号，必填
        $out_trade_no = $order['order_sn'];

        // 订单名称，必填
        $subject = $order['join']['college']['title'];

        // 付款金额，必填
        $total_amount = $order['total'];

        // 商品描述
        $body = '草帽跨境课程';

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
     * 手机网站支付宝支付
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
        $validate = validate('CollegeOrder');
        if (!$validate->check(['order_id' => $order_id])) {
            return akali($validate->getError());
        }

        /**********************   找出订单   **********************/
        $order = CO::with('join.college')->get($order_id);

        /**********************   构造参数   **********************/

        // 商户订单号，商户网站订单系统中唯一订单号，必填
        $out_trade_no = $order['order_sn'];

        // 订单名称，必填
        $subject = $order['join']['college']['title'];

        // 付款金额，必填
        $total_amount = $order['total'];

        // 商品描述
        $body = '草帽跨境课程';

        //超时时间
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
                Db::table('college_order')->where('order_sn', $out_trade_no)->update([
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

            return $this->redirect('college/orderSuccess', ['order_sn' => $out_trade_no]);

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
        $validate = validate('CollegeOrder');
        if (!$validate->check(['order_id' => $order_id])) {
            return akali($validate->getError());
        }

        /**********************   找出订单   **********************/
        $order = CO::with('join.college')->get($order_id);

        /**********************   变量赋值   **********************/
        View::assign([
            'order'   => $order,
            'join'    => $order['join'],
            'college' => $order['join']['college'],
        ]);

        return View::fetch();
    }

}
