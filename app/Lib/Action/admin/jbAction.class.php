<?php

class jbAction extends backendAction{

    public function _initialize() {
        parent::_initialize();
        $this->_mod = M('jb');
    }

    public function index() {
        //默认排序
        $this->sort = 'id';
        $this->order = 'desc';
		//排序
		$model = $this->_mod;
        $mod_pk = $model->getPk();
        if ($this->_request("sort", 'trim')) {
            $sort = $this->_request("sort", 'trim');
        } else if (!empty($sort_by)) {
            $sort = $sort_by;
        } else if ($this->sort) {
            $sort = $this->sort;
        } else {
            $sort = $mod_pk;
        }
        if ($this->_request("order", 'trim')) {
            $order = $this->_request("order", 'trim');
        } else if (!empty($order_by)) {
            $order = $order_by;
        } else if ($this->order) {
            $order = $this->order;
        } else {
            $order = 'DESC';
        }
		$pagesize = 20;
        //如果需要分页       
		$count = $model->where($map)->count($mod_pk);
		$pager = new Page($count, $pagesize);
		$list = $model->where($map)->order($sort . ' ' . $order)->limit($pager->firstRow.','.$pager->listRows)->select();
		$page = $pager->show();
		$this->assign("page", $page);
        $this->assign('list', $list);
        $this->assign('list_table', true);
		$this->display();
    }
	public function ajax_edit(){
		//AJAX修改数据
        $mod = D($this->_name);
        $pk = $mod->getPk();
        $id = $this->_get($pk, 'intval');
        $field = $this->_get('field', 'trim');
        $val = $this->_get('val', 'trim');
        //允许异步修改的字段列表  放模型里面去 TODO
        $mod->where(array($pk=>$id))->setField($field, $val);
		if($val==1){
			//设置积分
			$uid = M("jb")->where("id=$id")->getField('uid');
			$user = M('user')->where("id=$uid")->find();		
			set_score($user,1,"","",1);
			//积分日志
			set_score_log($user,"jb",1,"","",1);
		}
        $this->ajaxReturn(1);
	}
	//审核
	public function sets(){
		$status = $this->_get("status","intval");
		$id = $this->_get("id","intval");
		M("jb")->where("id=$id")->setField(array("status"=>$status));
		$uinfo = M("jb")->field("u.id,u.username,score,exp,offer,coin")->join("try_user as u ON u.id=try_jb.uid")->where("try_jb.id=$id")->find();
		if($status==1){//有效
			//分配积分
			set_score($uinfo,1,"","",1);
			//积分日志
			set_score_log($uinfo,"jb",1,"","",1);
			$this->ajaxReturn(1);
		}elseif($status==2){//无效
			set_score($uinfo,-5,"","",-5);
			//积分日志			
			set_score_log($uinfo,"unjb",-5,"","",-5);
			$this->ajaxReturn(1);
		}
	}
}