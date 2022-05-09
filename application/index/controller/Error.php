<?php
namespace app\index\controller;

use app\index\model\Content;

class Error extends Base
{

    //empty
    public function _empty()
    {
        return $this->fetch(config('app.http_exception_template.404'));
    }

}
