<?php 

namespace app\api\controller;

use think\Session;
use app\api\controller\Base;

use app\admin\model\Sort as SortModel;

/**
 * 接口主页方法
 */

class Sort extends Base
{
	private $Sort;

	//构造函数
	public function __construct()
	{
		$this->Sort = new SortModel();

		parent::__construct();
	}

	//获取分类列表
	public function GetSortList($sorting=false)
	{
		$data = $this->Sort->GetListByPage(array('sort_status'=>1),1,100000);

		$i = 0;

		$page = array();

		foreach ($data as $key => $value) {

			$data[$key]['sort_icon'] = session::get('config.website_indexurl').$value['sort_icon'];

			if($sorting){

				if($key % 8 == 0){
					$page[] = ++$i;
				}

				$sort[$i][] = $value;
			}
		}

		if($sorting) $data = $sort;

		if(empty($data)) return json_encode(array('code'=>0,'msg'=>'分类为空'));

		return json_encode(array('code'=>1,'msg'=>'获取成功','sort'=>$data,'page'=>$page));
	}
}