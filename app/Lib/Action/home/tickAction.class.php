<?php
class tickAction extends frontendAction {

    public function index() {
		$tp = $this->_get('tp','trim');
		$mod_orig=M("item_orig");
		$mod = M('tick');
		//获取所有有优惠券的购物平台
		$arr_list = $mod_orig->where("id in(select distinct orig_id from try_tick where DATEDIFF(now() ,start_time)>0 and DATEDIFF(end_time,now())>0 )")->order("ordid asc,id asc")->select();
		$orig_list=array();
		foreach($arr_list as $key=>$val){
			$orig_list[$val['id']]=$val;
		}
		$this->assign('orig_list',$orig_list);
		//获取所有的优惠券
		$pagesize=8;
		if($tp){
			$num = M()->query("select count(*) as num from try_tick as t,try_item_orig as o,try_cosler c where CONV( HEX( LEFT( CONVERT( o.name  USING gbk ) , 1 ) ) , 16, 10 ) BETWEEN c.cBegin AND c.cEnd AND c.fPY = '$tp' and t.orig_id=o.id and DATEDIFF(now() ,t.start_time)>0 and DATEDIFF(t.end_time,now())>0");
			$count=$num[0]['num'];
		}else{
			$count = $mod->where("DATEDIFF(now() ,start_time)>0 and DATEDIFF(end_time,now())>0")->count();
		}
		$pager = $this->_pager($count,$pagesize);
		if($tp){
			$list = M()->query("select t.* from try_tick as t,try_item_orig as o,try_cosler c where CONV( HEX( LEFT( CONVERT( o.name  USING gbk ) , 1 ) ) , 16, 10 ) BETWEEN c.cBegin AND c.cEnd AND c.fPY = '$tp' and t.orig_id=o.id and DATEDIFF(now() ,t.start_time)>0 and DATEDIFF(t.end_time,now())>0 order by ordid asc,id asc limit ".$pager->firstRow.",".$pager->listRows);
		}else{
			$list = $mod->where("DATEDIFF(now() ,start_time)>0 and DATEDIFF(end_time,now())>0")->order("ordid asc,id asc")->limit($pager->firstRow.",".$pager->listRows)->select();
		}
		foreach($list as $key=>$val){
			$list[$key]['days']=round(abs(strtotime($val['end_time'])-time())/3600/24);
			$list[$key]['img']=$orig_list[$val['orig_id']]['img_url'];
		}
		$this->assign('list',$list);
		$this->assign('pagebar',$pager->fshow());
		$this->_config_seo();
		$this->assign('tp',$tp);
        $this->display();
    }

    public function show() {
        $id = $this->_get("id","intval");
		!$id && $this->_404();
		$mod_orig = M("item_orig");
		$mod = M("tick");	
		$mod_tk = M('tk');
		$info=$mod->where("id=$id")->find();		
		!info && $this->_404();		
		$info['zj']=intval($info['sy'])+intval($info['yl']);
		$info['intro'] = str_replace(chr(13),'<br>',$info['intro']);
		$this->assign("info",$info);
		
		$orig_info = $mod_orig->where("id=$info[orig_id]")->find();
		$this->assign('orig',$orig_info);
		//领取记录
		$pagesize=20;
		$count = $mod_tk->where("tick_id=$id and status=1")->count();
		$pager = $this->_pager($count,$pagesize);
		$lq = $mod_tk->where("tick_id=$id and status=1")->limit($pager->firstRow.",".$pager->listRows)->select();
		foreach($lq as $key=>$val){
			$lq[$key]['gk']= ((time()-$val['get_time'])>3600*24)?1:0;
			$lq[$key]['uname']=str_pad(substr(get_uname($val['uid']),-3),6,'*',STR_PAD_LEFT);
		}
		$this->assign('pagebar',$pager->fshow());
		$this->assign('lq',$lq);
		
        $this->_config_seo();		
    	$this->display();
    }
	//商城优惠券
	public function orig(){
		$id = $this->_get('id','intval');
		!$id&&$this->_404();
		$mod_orig=M("item_orig");
		$mod=M('tick');
		$info = $mod_orig->where("id=$id")->find();
		$this->assign('info',$info);
		!info && $this->_404();
		$pagesize=8;
		$count = $mod->where("orig_id=$id and DATEDIFF(now() ,start_time)>0 and DATEDIFF(end_time,now())>0")->count();
		$pager=$this->_pager($count,$pagesize);
		$list = $mod->where("orig_id=$id and DATEDIFF(now() ,start_time)>0 and DATEDIFF(end_time,now())>0")->limit($pager->firstRow.",".$pager->listRows)->select();
		foreach($list as $key=>$val){
			$list[$key]['days']=round(abs(strtotime($val['end_time'])-time())/3600/24);
		}
		$this->assign('pagebar',$pager->fshow());
		$this->assign('list',$list);
		$this->_config_seo();
		$this->display();
	}
	//兑换优惠券
	public function tkdh(){
		!$this->visitor->is_login&&$this->ajaxReturn(0,'');//未登录
		$info = $this->visitor->get();
		$id = $this->_get('id','intval');
		//查询用户积分是否足够
		$yhq = M("tick")->where("id=$id and sy>0")->find();
		!$yhq && $this->ajaxReturn(1,'该优惠券已领完');//优惠券已领完
		if(intval($info['score'])<intval($yhq['dhjf'])){
			$this->ajaxReturn(1,'您的积分不够！');//积分不够
		}
		if($yhq['xl'] > 0){
			$x=M('tk')->where("tick_id=$id and status=1 and uid=".$info['id'])->count();
			if($x>=$yhq['xl'])$this->ajaxReturn(1,'很抱歉，该优惠券每个账户仅限领取'.$x.'次哦，请让些机会给其他菜油吧！');
		}

		//兑换
		$qid = M('tk')->where("tick_id=$id and status=0")->getField('tk_id');	
		$data['uid']=$info['id'];
		$data['get_time']=time();
		$data['status']=1;
		M('tk')->where("tk_id=$qid")->save($data);//更新优惠券信息
		$yl = M('tk')->where("tick_id=$id and status='1'")->count();//已领
		$sy = M('tk')->where("tick_id=$id and status='0'")->count();//未领
		M("tick")->where("id=$id")->save(array('yl'=>"$yl",'sy'=>"$sy"));
		M()->query("update try_user set score=score-$yhq[dhjf] where id=$info[id] limit 1");//更新用户积分
		//积分日志
		$score_log_mod = D('score_log');
		$score_log_mod->create(array(
			'uid' => $info['id'],
			'uname' => $info['username'],
			'action' => 'exchange',
			'score' => -$yhq['dhjf'],
		));
		$score_log_mod->add();
		$xc = array();
		$xc['ftid']=$info['id'];
		$xc['to_id']=$info['id'];
		$xc['to_name']=$info['username'];
		$xc['from_id']=0;
		$xc['from_name']='tryine';
		$xc['add_time']=time();
		$xc['info'] ='领取优惠券：'. M('tick')->where("id=$id")->getField('name');

		M('message')->add($xc);
		$_SESSION['user_info']['message']=M('message')->where("to_id='".$info['id']."' and ck_status=0")->count();
		$this->ajaxReturn(2,'兑换成功!快去个人中心-我的优惠卷看看吧！');
	}
	//显示券号
	public function gettk(){
		$tk_id=$this->_get('tk_id','intval');
		$tk_code = M("tk")->where("tk_id=$tk_id")->getField("tk_code");
		$this->ajaxReturn(1,'',$tk_code);
	}
}