<?php
declare (strict_types = 1);

namespace app\index\controller;

use app\common\model\ActivityJoin as AJ;
use app\common\model\CollegeJoin as CJ;
use app\common\model\Member as Mb;
use library\Sms;
use think\facade\Cache;
use think\Request;

class CodeApi extends BaseApi
{
    /**
     * 发送短信验证码 - 注册、登录、找回密码操作
     *
     * @param  Request $request
     * @return \think\Response
     */
    public function getCode(Request $request)
    {
        /**********************   参数接收   **********************/
        $data = [
            'phone' => $request->param('phone/d'),
            'type'  => $request->param('type/d'),
        ];

        /**********************   验证手机和发送类型   **********************/
        $validate = validate('Phone');
        if (!$validate->check($data)) {
            return $this->create(400, $validate->getError());
        }

        /**********************   判断手机注册、登录、找回密码情况   **********************/
        $user = Mb::where('phone', $data['phone'])->find();
        if ($data['type'] == 1 && !empty($user)) {

            return $this->create(400, '手机号码已经注册');

        } else if ($data['type'] == 2 && empty($user) || $data['type'] == 3 && empty($user)) {

            return $this->create(400, '手机号码尚未注册');

        }

        /**********************   是否以获取过验证码 判断是否已过一分钟   **********************/
        $codeCache = Cache::get('sms-' . $data['type'] . '-' . $data['phone']);
        if (!empty($codeCache) && time() - $codeCache['create_tiem'] < 60) {
            // 剩余时间 - 秒数
            $timeLeft = 60 - (time() - $codeCache['create_tiem']);
            return $this->create(400, '请在' . $timeLeft . '秒后，发送短信');
        }

        /**********************   发送手机验证码和缓存验证码   **********************/
        // $sms     = new Sms;
        $result  = (new Sms)->smsSend($data);
        $smsJson = json_decode($result, true);
        if (isset($smsJson['SendStatusSet'][0]['Code']) && $smsJson['SendStatusSet'][0]['Code'] === 'Ok') {
            $smsData = [
                'phone'       => $smsJson['SendStatusSet'][0]['PhoneNumber'],
                'code'        => $smsJson['SendStatusSet'][0]['SessionContext'],
                'create_tiem' => time(),
                'verify_number' => 0, // 用户已输入验证码的次数，不能超过五次
            ];

            /**********************   缓存短信信息   **********************/
            Cache::set('sms-' . $data['type'] . '-' . $data['phone'], $smsData);
            return $this->create(200, '短信发生成功，请注意查收');
        } else {
            return $this->create(400, '短信发送失败，请稍候获取');
        }
    }

    /**
     * 发送短信验证码 - 其它操作
     *
     * @param  Request $request
     * @return \think\Response
     */
    public function getPhoneCode(Request $request)
    {
        /**********************   参数接收   **********************/
        $data = [
            'phone' => $request->param('phone/d'),
            'type'  => $request->param('type/d'),
        ];

        /**********************   验证手机和发送类型   **********************/
        $validate = validate('Phone');
        if (!$validate->check($data)) {
            return $this->create(400, $validate->getError());
        }

        /**********************   是否以获取过验证码 判断是否已过一分钟   **********************/
        $codeCache = Cache::get('sms-' . $data['type'] . '-' . $data['phone']);

        // 是否未超过一分钟
        if (!empty($codeCache) && time() - $codeCache['create_tiem'] < 60) {
            // 剩余时间 - 秒数
            $timeLeft = 60 - (time() - $codeCache['create_tiem']);
            return $this->create(400, '请在' . $timeLeft . '秒后，发送短信');
        }

        /**********************   发送手机验证码和缓存验证码   **********************/
        // $sms     = new Sms;
        $result  = (new Sms)->smsSend($data);
        $smsJson = json_decode($result, true);
        if (isset($smsJson['SendStatusSet'][0]['Code']) && $smsJson['SendStatusSet'][0]['Code'] === 'Ok') {
            $smsData = [
                'phone'       => $smsJson['SendStatusSet'][0]['PhoneNumber'],
                'code'        => $smsJson['SendStatusSet'][0]['SessionContext'],
                'create_tiem' => time(),
                'verify_number' => 0, // 用户已输入验证码的次数，不能超过五次
            ];

            /**********************   缓存短信信息   **********************/
            Cache::set('sms-' . $data['type'] . '-' . $data['phone'], $smsData);
            return $this->create(200, '短信发生成功，请注意查收');
        } else {
            return $this->create(400, '短信发送失败，请稍候获取');
        }
    }

    /**
     * 检查短信验证码
     *
     * @param  int $phone 手机号码
     * @param  string $code 验证码
     * @param  int $type 短信类型 1:注册, 2:手机登录, 3:重置密码
     * @return \think\Response
     */
    public function checkCode($phone, $code, $type = 1)
    {
        /**********************   获取短信缓存   **********************/
        $phoneCache = Cache::get('sms-' . $type . '-' . $phone);
        if (empty($phoneCache)) {
            return ['code' => 10001, 'msg' => '请重新发送验证码'];
        }

        // 输入验证码是否已超过五次
        if($phoneCache['verify_number'] >= 3){
            return $this->create(400, '输入验证码已超过五次，请重新发送验证码');
        }else{
            // 短信验证次数加一
            $phoneCache['verify_number'] += 1;
            Cache::set('sms-' . $type . '-' . $phone, $phoneCache);
        }

        /**********************   检查验证码   **********************/
        if ($phoneCache['code'] !== $code) {
            return ['code' => 10002, 'msg' => '验证码不正确'];
        }

        return ['code' => 0];
    }

    /**
     * 设置短信模板
     *
     * @param
     * @return \think\Response
     */
    public function setTemplate()
    {
        $template_centont = "验证码：{1}（5分钟内有效）。您正在进行草帽跨境验证码通行证，请勿将验证码告诉他人哦";
        $result           = (new Sms)->smsTemplate($template_centont);
        $smsJson          = json_decode($result, true);
        return $this->create(200, '短信模板设置成功', $smsJson);
    }
}
