<?php
// +----------------------------------------------------------------------
// | 树结构 类库 封装
// +----------------------------------------------------------------------
// | Author: 小柯 <972581428@qq.com> <http://www.usezan.com>
// +----------------------------------------------------------------------
namespace libs;
class Tree {
	public $arr = array();
	public $icon = array('│','├','└');
	public $nbsp = "&nbsp;";
	public $ret = '';
	public $level = 0;

    public function __construct($arr=array()) {
         $this->arr = $arr;
	     $this->ret = '';
	     return is_array($arr);
    }

	public function getchild($bid){
		$a = $newarr = array();
		if(is_array($this->arr)){
			foreach($this->arr as $id => $a){
				if($a['parentid'] == $bid) $newarr[$id] = $a;
			}
		}
		return $newarr ? $newarr : false;
	}

	function get_tree($bid, $str, $sid = 0, $adds = '', $strgroup = ''){
		$number=1;
		$child = $this->getchild($bid);
		if(is_array($child)){
		    $total = count($child);
			foreach($child as $id=>$a){
				$j=$k='';
				if($number==$total){
					$j .= $this->icon[2];
				}else{
					$j .= $this->icon[1];
					$k = $adds ? $this->icon[0] : '';
				}
				$spacer = $adds ? $adds.$j : '';

				@extract($a);
				if(empty($a['selected'])){$selected = $id==$sid ? 'selected' : '';}
                $newstr = null;
				$strgroup ? eval("\$newstr = \"$strgroup\";") : eval("\$newstr = \"$str\";");
				$this->ret .= $newstr;
				$nbsp = $this->nbsp;
				$this->get_tree($id, $str, $sid, $adds.$k.$nbsp,$strgroup);
				$number++;
			}
		}
		return $this->ret;
	}
	
}