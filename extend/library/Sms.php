<?php
namespace library;

// 短信
use TencentCloud\Common\Credential;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;

// 短信模板
use TencentCloud\Sms\V20190711\Models\SendSmsRequest;
use TencentCloud\Sms\V20190711\SmsClient;
use TencentCloud\Sms\V20210111\Models;
use TencentCloud\Sms\V20210111\Models\AddSmsTemplateRequest;

class Sms
{

    /**
     * 发送短信
     *
     * @param  array $data
     * @return json $resp->toJsonString()
     */
    public function smsSend($data)
    {

        /**********************   组装模板参数   **********************/
        $content       = rand(100000, 999999); // 要发送的验证码
        $templateParam = array("{$content}");

        // 电话是否为数组
        $phone = array('+86' . $data['phone']);
        /*if (is_array($phone)) {
        $temp = [];
        foreach ($phone as $key => $value) {
        $temp[] = '+86' . $value;
        }
        $phone = $temp;
        } else {
        $phone = array('+86' . $phone);
        }*/

        /**********************   1:注册 2:手机登录 3:找回密码   **********************/
        switch (intval($data['type'])) {
            case 1:
                $templateid = config('sms.templateid_1');
                break;
            case 2:
                $templateid      = config('sms.templateid_2');
                $templateParam[] = "5"; // 短信过期时间，只有登录有这个叼模板参数
                break;
            case 3:
                $templateid = config('sms.templateid_3');
                break;
            case 4:
                $templateid    = config('sms.templateid_4');
                $templateParam = array($data['title'], $data['start_time']); // 活动标题，活动开始时间
                break;
            case 5:
                $templateid    = config('sms.templateid_5');
                $templateParam = array($data['title'], $data['start_time']); // 课程标题，课程开始时间
                break;
            case 6:
                $templateid = config('sms.templateid_6');
                break;
        }

        try {
            //认证对象
            $cred = new Credential(config('sms.secretid'), config('sms.secretkey'));
            //实例化SMS
            $client = new SmsClient($cred, "ap-shanghai");
            //请求对象
            $req              = new SendSmsRequest();
            $req->SmsSdkAppid = config('sms.appid');
            $req->Sign        = config('sms.sign');

            /* 下发手机号码，采用 E.164 标准，+[国家或地区码][手机号]
             * 示例如：+8613711112222， 其中前面有一个+号 ，86为国家码，13711112222为手机号，最多不要超过200个手机号*/
            $req->PhoneNumberSet = $phone;

            $req->SenderId = config('sms.senderid');
            // 用户的 session 内容
            $req->SessionContext = "{$content}";
            $req->TemplateID     = $templateid;
            //模板参数: 若无模板参数，则设置为空
            $req->TemplateParamSet = $templateParam;
            $resp                  = $client->SendSms($req);
            // dump($resp->toJsonString());return;
            return $resp->toJsonString();
            //print_r($resp->TotalCount);
        } catch (TencentCloudSDKException $e) {
            return $e;
        }
    }

    /**
     * 申请短信模板
     *
     * @param  $template_centont string
     * @return json $resp->toJsonString()
     */
    public function smsTemplate($template_centont)
    {
        try {
            /* 必要步骤：
             * 实例化一个认证对象，入参需要传入腾讯云账户密钥对 secretId 和 secretKey
             * 本示例采用从环境变量读取的方式，需要预先在环境变量中设置这两个值
             * 您也可以直接在代码中写入密钥对，但需谨防泄露，不要将代码复制、上传或者分享给他人
             * CAM 密钥查询：https://console.cloud.tencent.com/cam/capi*/

            $cred = new Credential(config('sms.secretid'), config('sms.secretkey'));
            //$cred = new Credential(getenv("TENCENTCLOUD_SECRET_ID"), getenv("TENCENTCLOUD_SECRET_KEY"));

            // 实例化一个 http 选项，可选，无特殊需求时可以跳过
            $httpProfile = new HttpProfile();
            // 配置代理
            // $httpProfile->setProxy("https://ip:port");
            $httpProfile->setReqMethod("GET"); // POST 请求（默认为 POST 请求）
            $httpProfile->setReqTimeout(30); // 请求超时时间，单位为秒（默认60秒）
            $httpProfile->setEndpoint("sms.tencentcloudapi.com"); // 指定接入地域域名（默认就近接入）

            // 实例化一个 client 选项，可选，无特殊需求时可以跳过
            $clientProfile = new ClientProfile();
            $clientProfile->setSignMethod("TC3-HMAC-SHA256"); // 指定签名算法（默认为 HmacSHA256）
            $clientProfile->setHttpProfile($httpProfile);

            // 实例化 SMS 的 client 对象，clientProfile 是可选的
            // 第二个参数是地域信息，可以直接填写字符串 ap-guangzhou，或者引用预设的常量
            $client = new SmsClient($cred, "ap-guangzhou", $clientProfile);

            // 实例化一个 AddSmsTemplateRequest 请求对象，每个接口都会对应一个 request 对象。
            $req = new AddSmsTemplateRequest();

            /* 填充请求参数，这里 request 对象的成员变量即对应接口的入参
             * 您可以通过官网接口文档或跳转到 request 对象的定义处查看请求参数的定义
             * 基本类型的设置:
             * 帮助链接：
             * 短信控制台：https://console.cloud.tencent.com/smsv2
             * sms helper：https://cloud.tencent.com/document/product/382/3773
             */

            /* 模板名称 */
            $req->TemplateName = "通用短信通知";
            /* 模板内容 */
            $req->TemplateContent = $template_centont;
            /* 短信类型：0表示普通短信, 1表示营销短信 */
            $req->SmsType = 0;
            /* 是否国际/港澳台短信：
            0表示国内短信
            1表示国际/港澳台短信 */
            $req->International = 0;
            /* 模板备注：例如申请原因，使用场景等 */
            $req->Remark = "草帽跨境通用短信通知";
            // 通过 client 对象调用 AddSmsTemplate 方法发起请求。注意请求方法名与请求对象是对应的
            $resp = $client->AddSmsTemplate($req);
            // 输出 JSON 格式的字符串回包
            return $resp->toJsonString();

            // 可以取出单个值，您可以通过官网接口文档或跳转到 response 对象的定义处查看返回字段的定义
            print_r($resp->TotalCount);
        } catch (TencentCloudSDKException $e) {
            return $e;
        }
    }

}
