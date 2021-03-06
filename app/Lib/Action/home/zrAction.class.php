<?php
class zrAction extends frontendAction {
	
    public function index() {
		$this->_mod = D('zr');
		$this->_cate_mod = D('item_cate');
		$id = $this->_get('id','intval');
		$cate = $this->_cate_mod->where('pid=0')->order("ordid asc,id asc")->select();
		$pagesize=10;
		$id&&$map['cate_id']=$id;
		$map['_string']="status=1 or status=4";
		$count = $this->_mod->where($map)->count();
		$pager = $this->_pager($count,$pagesize);
		$list = $this->_mod->where($map)->order("ordid asc,id desc")->limit($pager->firstRow.",".$pager->listRows)->select();
		$this->assign('cid',$id);
		$this->assign('cate',$cate);
		$this->assign('list',$list);
		$this->assign('pagebar',$pager->fshow());
		$this->_config_seo();
        $this->display();
    }

    public function show() {
        $id = $this->_get("id","intval");
		!$id && $this->_404();
		$mod_cate = M("item_cate");
		$mod = M("zr");	
		$info=$mod->where("id=$id and (status=1 or status=4)")->find();
		!$info && $this->error('该信息不存在或未通过审核');
		$cate = $mod_cate->where("id=$info[cate_id]")->find();
		//属性
		$attr = M("zr_attr")->where("zr_id=$id")->select();
		$this->assign('attr',$attr);
		$this->assign('info',$info);
		$this->assign('cate',$cate);
		//其他
		$other = $mod->where("status=1 and cate_id<>$info[cate_id]")->limit(10)->select();
		$this->assign('other',$other);
		//上下页
		/*$pre = $mod->where("id<$id and status=1 and cate_id=$info[cate_id]")->field("id,title")->find();
		$next = $mod->where("id>$id and status=1 and cate_id=$info[cate_id]")->field("id,title")->find();
		$this->assign("pre",$pre);
		$this->assign("next",$next);*/
		$this->_config_seo();
		//评论
		$this->assign('xid',2);
		$this->assign('itemid',$id);
		
    	$this->display();
    }
	
	//发布转让信息
	public function publish(){
		$user = $this->visitor->get();
		!$user && $this->redirect('user/login');
		($user['exp'] < 51) && $this->error('您的等级还不够，需要升到 2 级才能发布信息！');

		$cate_list = M("item_cate")->where("pid=0")->order("ordid asc,id asc")->select();
		$this->assign('cate_list',$cate_list);
		$this->_config_seo(array('title'=>'{user_title}','keywords' => '{user_keys}','description' => '{user_desc}' ), 
		array('user_title' => '发布闲置转让信息','user_keys' => '发布闲置转让信息','user_desc' => '发布闲置转让信息'));
		$this->display();
	}
	
	//发布转让信息
	public function insert(){
		$this->_mod = D('zr');
		$mod = D("zr");
		//过滤字符
		$kill_word = C("pin_kill_word");
		$kill_word = explode(",",$kill_word);
		if(in_array($_POST['content'],$kill_word)||in_array($_POST['title'],$kill_word)){
			$this->error('您发表的内容有非法字符');
		}
		if (false === $data = $mod->create()) {
			$this->error($mod->getError());
		}
		$user = $this->visitor->get();
		$data['uid']=$user['id'];
		$data['uname']=$user['username'];
		$data['content']=$_POST['content'];
		$data['intro']=msubstr(strip_tags($data['content']),0,250);
		$data['status']=0;
		if($data['tags']==""){
			$data['tags'] = D('tag')->get_tags_by_title($data['title']);
            $data['tags'] = implode(' ', $data['tags']);
		}
		$data['imgs'] = serialize($_POST['imgs']);
		$zr_id = $this->_mod->add($data);

        !$zr_id && $this->error(L('operation_failure'));

		//附加属性
		$attr = $this->_post('attr', ',');
		if( $attr ){
			foreach( $attr['name'] as $key=>$val ){
				if( $val&&$attr['value'][$key] ){
					$atr['zr_id'] = $zr_id;
					$atr['attr_name'] = $val;
					$atr['attr_value'] = $attr['value'][$key];
					M('zr_attr')->add($atr);
				}
			}
		}
		$jumpUrl=U('user/publish',array('t'=>'zr'));
		$this->success(L('operation_success'),$jumpUrl);
		
	}
	//上传图片
	public function uploadimg(){
		//上传图片
        if (!empty($_FILES['J_img']['name'])) {
            $art_add_time = date('ym/d');
            $result = $this->_upload($_FILES['J_img'], 'zr/' . $art_add_time);
            if ($result['error']) {
				$this->ajaxReturn(0, $result['info']);
            } else {
                $ext = array_pop(explode('.', $result['info'][0]['savename']));
                $data['J_img'] = get_rout_img($art_add_time .'/'. str_replace('.' . $ext, '.' . $ext, $result['info'][0]['savename']),'zr');
            }
        }
        $this->ajaxReturn(1, L('operation_success'),$data['J_img']);
	}

	//上传图片
	public function uploadimg1(){
		//上传图片
		if (!empty($_FILES['J_img1']['name'])) {
			$art_add_time = date('ym/d');
			$result = $this->_upload($_FILES['J_img1'], 'zr/' . $art_add_time);
			if ($result['error']) {
				$this->ajaxReturn(0, $result['info']);
			} else {
				$ext = array_pop(explode('.', $result['info'][0]['savename']));
				$data['J_img1'] = get_rout_img($art_add_time .'/'. str_replace('.' . $ext, '.' . $ext, $result['info'][0]['savename']),'zr');
			}
		}
		$this->ajaxReturn(1, L('operation_success'),$data['J_img1']);
	}
	
	public function edit(){
		if (IS_POST) {
			$this->_mod = D('zr');
			$mod = D("zr");
			if (false === $data = $mod->create()) {
				$this->error($mod->getError());
			}
			$data['content']=$_POST['content'];		
			$data['intro']=msubstr(strip_tags($data['content']),0,250);
			$data['status']=$this->_post("status",'intval');
			$data['imgs'] = serialize($_POST['imgs']);
			$r = $this->_mod->save($data);
			!$r && $this->error(L('operation_failure'));
			
			//附加属性
			$attr = $this->_post('attr', ',');
			if( $attr ){
				foreach( $attr['name'] as $key=>$val ){
					if( $val&&$attr['value'][$key] ){
						$atr['zr_id'] = $data["id"];
						$atr['attr_name'] = $val;
						$atr['attr_value'] = $attr['value'][$key];
						M('zr_attr')->add($atr);
					}
				}
			}	
			$this->success(L('operation_success'),U('user/publish',array('t'=>'zr')));
        }else{
			!$this->visitor->is_login && $this->redirect('user/login');
			$user = $this->visitor->get();
			$id = $this->_get("id","intval");		 
			$zr = M("zr")->where("id=$id")->find();
			!$id && $this->_404();
			//相册
			$zr['imgs'] = unserialize($zr['imgs']);
			$this->assign('zr',$zr);
			$cate_list = M("item_cate")->where("pid=0")->order("ordid asc,id asc")->select();
			$this->assign('cate_list',$cate_list);
			//属性名
			$attr = M("zr_attr")->where("zr_id=$id")->select();
			$this->assign('attr',$attr);
			$this->assign('page_seo',set_seo('编辑转让'));
			$this->display();
		}
	}
	//删除图片
	function delimg(){
		$url = $this->_get('url','trim');
		if($url){
			if(strpos($url,"/",0)==0){
				$url = substr($url,(strlen($url)-1)*-1);
			}
			unlink($url);
		}
		$this->ajaxReturn(1, '删除成功');
	}
	
	//删除属性
	function delattr(){
		$id=$this->_get("id","intval");
		$r = M("zr_attr")->where("id=$id")->delete();
		if($r){
			$this->ajaxReturn(1,"删除成功");
		}else{
			$this->ajaxReturn(0,"删除失败");
		}
	}
}