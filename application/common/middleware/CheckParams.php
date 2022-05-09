<?php
namespace app\common\middleware;

/**
 * 验证参数，参数过滤
 *
 * @param Request     $request 请求对象
 * @param Closure     $next
 * @return return           $next($request);
 */
class CheckParams
{
    public function handle($request, \Closure $next)
    {
        // 验证请求是否超时
        // $this->check_time($params['time']);
        // 验证参数，参数过滤
        // 页码，条数赋值
        if(request()->isPost()){
            if ($request->action() == 'index') {
                $request->page = $request->param('page') ? $request->param('page') : config('app.page');
                $request->size = $request->param('size') ? $request->param('size') : config('app.size');
            }
            
            $this->check_param($request);
        }
        return $next($request);
    }

    /**
     * 验证参数，参数过滤
     *
     * @param array     $array   [除time和token外的所有参数]
     * @return return            [合格的参数数组]
     */
    public function check_param($request)
    {
        $root       = $request->root(); // 应用目录
        $root       = str_replace('/', '\\', $root); // 替换斜杠
        $controller = $request->controller(); // 控制器
        $action     = $request->action(); // 方法名
        $params     = $request->filter(['htmlspecialchars'])->all(); // 获取当前参数
        
        // 拼接验证类名，注意路径不要出错
        $validateClassName = 'app' . $root . '\validate\\' . $controller;
        // 判断当前验证类是否存在
        if (class_exists($validateClassName)) {
            $validate = new $validateClassName;
            // 仅当存在验证场景才校验
            if ($validate->hasScene($action)) {

                /* try {
                validate($validateClassName)->scene($action)->check($params);
                } catch (ValidateException $e) {
                // throw new Params(['msg' => '阿卡丽', 'code' => 202, 'status' => 10002]);
                throw new \Exception($e->getMessage());
                } */
                // 设置当前验证场景
                $validate->scene($action);
                // 校验不通过则直接返回错误信息
                if (!$validate->check($params)) {
                    jinx($validate->getError());
                } else {
                    $resultParams    = $validate->getDateByRule($params);
                    $request->params = $resultParams;
                }
            }
        }/* else{
            $request->params = $params;
        } */
    }

}
