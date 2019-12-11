<?php
namespace app\index\controller;

use app\index\controller\LoginBase;

class Index extends LoginBase
{
    public function index()
    {
        return view();
    }
}
