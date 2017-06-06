<?php
class ajaxAction extends frontendAction {
	public function _initialize() {
        parent::_initialize();
    }
    public function zan() {//对商品点赞
		$id = $_REQUEST['id'];
		$t = $_REQUEST['t'];
		$ip = trim(getip());
		$myip = trim(cookie("ip"));
		$zan = trim(cookie("zan".$t.$id));
		if($ip==$myip&&$zan==1){//已点赞
			$this->ajaxReturn(0, '您已点过赞！');
		}else{
			cookie("ip",$ip);
			cookie("zan".$t.$id,1);
			$data['zan']=M($t)->where("id=$id")->getField("zan");
			$data['zan']+=1;
			$data['id']=$id;
			$r=M($t)->save($data);
			$this->ajaxReturn(1, '',$data['zan']);
		}
    }
	public function setlikes(){//收藏商品
		!$this->visitor->is_login && $this->ajaxReturn(0, '请登录！');
		$user = $this->visitor->get();
		$id = $this->_post('id','intval');
		$xid = $this->_post('xid','intval');
		!($id&&$xid)&&$this->ajaxReturn(0, '收藏对象错误');
		$i_mod = get_mod($xid);
		//验证对象
		$item = $i_mod->where("id=$id")->find();
		!$item&&$this->ajaxReturn(0, '收藏对象错误');
		
		$mod = D("likes");
		//查找是否已收藏
		$islike=$mod->where("uid=$user[id] and xid=$xid and itemid=$id")->find();
		if($islike){//如果已经收藏则取消收藏
			$r=$mod->where("uid=$user[id] and xid=$xid and itemid=$id")->delete();
			if($r){
				$i_mod->where("id=$id")->setDec("likes");
				$res['likes']=intval($item['likes'])-1;
				$res['t']='qx';
				$this->ajaxReturn(1, '您已成功取消收藏',$res);
			}else{
				$this->ajaxReturn(0, '操作失败');
			}
		}else{
			$r=$mod->add(array('itemid'=>$id,'xid'=>$xid,'addtime'=>time(),'uid'=>$user['id']));
			if($r){
				$i_mod->where("id=$id")->setInc("likes");
				$res['likes']=intval($item['likes'])+1;
				$res['t']='sc';
				$this->ajaxReturn(1, '收藏成功',$res);
			}else{
				$this->ajaxReturn(0, '操作失败');
			}
		}
	}
	//评论
	public function comment(){
		foreach ($_POST as $key=>$val) {
            $_POST[$key] = Input::deleteHtmlTags($val);
        }
		$st = strtotime("today");
		$ed = strtotime(date('Y-m-d',strtotime('+1 day')));
		//查询当天评论次数
		$num = M("comment")->where("uid=".$this->visitor->info['id']." and add_time>$st and add_time<$ed ")->count();
		if($num>49){
			$this->ajaxReturn(0, '您今天评论的次数已达上限');
		}
        $data = array();
		$data['xid'] = $this->_post('xid','intval');
		!$data['xid'] && $this->ajaxReturn(0, L('invalid_object'));
        $data['itemid'] = $this->_post('itemid', 'intval');
        !$data['itemid'] && $this->ajaxReturn(0, L('invalid_object'));
        $data['info'] = $this->_post('content', 'trim');
		//过滤字符
		$kill_word = C("pin_kill_word");
		$kill_word = explode(",",$kill_word);
		if(in_array($data['info'],$kill_word)){
			$this->ajaxReturn(0,'您发表的内容有非法字符');
		}
        !$data['info'] && $this->ajaxReturn(0, L('please_input') . '评论内容');
        //敏感词处理
        $check_result = D('badword')->check($data['info']);
        switch ($check_result['code']) {
            case 1: //禁用。直接返回
                $this->ajaxReturn(0, L('has_badword'));
                break;
            case 3: //需要审核
                $data['status'] = 0;
                break;
        }
        $data['info'] = $check_result['content'];
        $data['uid'] = $this->visitor->info['id'];
        $data['uname'] = $this->visitor->info['username'];
		$data['add_time'] = time();
        //验证评论对象
		switch($data['xid']){
			case "1":$item_mod=M("item");break;
			case "2":$item_mod=M("zr");break;
			case "3":$item_mod=M("article");break;
		}
        $item = $item_mod->where(array('id' => $data['itemid'], 'status' => '1'))->find();
        !$item && $this->ajaxReturn(0, L('invalid_object'));
		$data['lc']=intval($item['comments'])+1;
        //写入评论
        $comment_mod = D('comment');
        if (false === $comment_mod->create($data)) {
            $this->ajaxReturn(0, $comment_mod->getError());
        }
        $comment_id = $comment_mod->add();
        if ($comment_id) {
			$item_mod->where(array('id'=>$data['itemid']))->setInc('comments');//评论数量加1
			M("user")->where("id=$data[uid]")->setInc("score");
			//积分日志
			set_score_log(array('id'=>$data['uid'],'username'=>$data['uname']),'comment',1,'','',1);
			/*$xc = array();
			$xc['ftid']=$data['uid'];
			$xc['to_id']=$data['uid'];
			$xc['to_name']=$data['uname'];
			$xc['from_id']=0;
			$xc['from_name']='tryine';
			$xc['add_time']=time();
			
			$xc['info'] ='感谢您对<a href="'.U('home/item/index',array('id'=>$item['id'])).'">'.$item['title'].'</a>的评论,系统给您奖励积分：1，经验：1.';
			M('message')->add($xc);*/
            $this->assign('cmt_list', array(
                array(
					'id' => $comment_id,
                    'uid' => $data['uid'],
                    'uname' => $data['uname'],
                    'info' => $data['info'],
                    'add_time' => time(),
					'zan'=>0,
					'lc'=>$data['lc']
                )
            ));
            $resp = $this->fetch('comment');
            $this->ajaxReturn(1, L('comment_success'), $resp);
        } else {
            $this->ajaxReturn(0, L('comment_failed'));
        }
	}
	//回复
	public function hf(){
		foreach ($_POST as $key=>$val) {
            $_POST[$key] = Input::deleteHtmlTags($val);
        }
        $data = array();
		$id = $this->_post('id','intval');
		!$id && $this->ajaxReturn(0, L('invalid_object'));
		//查找上级评论xid 和itemid
		$data=M("comment")->where("id=$id")->field("xid,itemid")->find();
        $data['info'] = $this->_post('content', 'trim');
        !$data['info'] && $this->ajaxReturn(0, L('please_input') . '评论内容');
        //敏感词处理
        $check_result = D('badword')->check($data['info']);
        switch ($check_result['code']) {
            case 1: //禁用。直接返回
                $this->ajaxReturn(0, L('has_badword'));
                break;
            case 3: //需要审核
                $data['status'] = 0;
                break;
        }
        $data['info'] = $check_result['content'];
        $data['uid'] = $this->visitor->info['id'];
        $data['uname'] = $this->visitor->info['username'];
		$data['add_time'] = time();
        //验证评论对象
		switch($data['xid']){
			case "1":$item_mod=M("item");break;
			case "2":$item_mod=M("zr");break;
			case "3":$item_mod=M("article");break;
		}
        $item = $item_mod->where(array('id' => $data['itemid'], 'status' => '1'))->find();
        !$item && $this->ajaxReturn(0, L('invalid_object'));
		$data['lc']=intval($item['comments'])+1;
		$data['pid']=$id;
        //写入评论
        $comment_mod = D('comment');
        if (false === $comment_mod->create($data)) {
            $this->ajaxReturn(0, $comment_mod->getError());
        }
        $comment_id = $comment_mod->add();
        if ($comment_id) {
			$item_mod->where(array('id'=>$data['itemid']))->setInc('comments');//评论数量加1
			M("user")->where("id=$data[uid]")->setInc("score");
			//积分日志

			set_score_log(array('id'=>$data['uid'],'username'=>$data['uname']),'comment',1,'','',1);
			$xc = array();
			$xc['ftid']=$data['uid'];
			$xc['to_id']=$data['uid'];
			$xc['to_name']=$data['uname'];
			$xc['from_id']=0;
			$xc['from_name']='tryine';
			$xc['add_time']=time();
			$xc['info'] ='感谢您的回复,系统给您奖励积分：1，经验：1.';
			M('message')->add($xc);
            $this->assign('data', array(
                    'uid' => $data['uid'],
                    'uname' => $data['uname'],
                    'info' => $data['info'],
                    'add_time' => time(),
					'zan'=>0,
					'lc'=>$data['lc'],
					'pid'=>$id

            ));
				$resp = $this->fetch('comment_lc');
			$resp = "<div class=\"lh_a1\">
    <div class=\"hf_zr\"> <img src=\"".avatar($data['uid'], 48)."\" /><span>".$data['uname']."</span></div> <p class=\"J_pl_i\">".$data['info']."</p>
    <div class=\"lrhf\"><div><span>".fdate($data['add_time'])."</span><a href=\"javascript:;\" class=\"J_hf\" data-id=\"".$comment_id."\"  psid=\"".$_POST['psid']."\" title=\"回复\">回复</a></div></div>
</div>";

            $this->ajaxReturn(1, L('comment_success').$data['pid'], $resp,$data['pid']);
        } else {
            $this->ajaxReturn(0, L('comment_failed'));
        }
	}
	/**
     * AJAX获取评论列表
     */
    public function comment_list() {
        $xid = $this->_get('xid', 'intval');
        !$xid && $this->ajaxReturn(0, L('invalid_object'));
		$itemid = $this->_get('itemid', 'intval');
        !$itemid && $this->ajaxReturn(0, L('invalid_object'));
        //验证评论对象
		switch($xid){
			case "1":$item_mod=M("item");break;
			case "2":$item_mod=M("zr");break;
			case "3":$item_mod=M("article");break;
		}
		if($this->_get('order')){
			$order = 'zan desc,id desc';
			$by ='zan';
		}else{
			$order ='id desc';
			$by ='';
		}
        $item = $item_mod->where(array('id' => $itemid, 'status' => '1'))->count('id');
        !$item && $this->ajaxReturn(0, L('invalid_object'));
        $comment_mod = M('comment');
        $pagesize = 5;
        $map = array('itemid' => $itemid,'xid'=>$xid,'status'=>1,'pid'=>0);
        $count = $comment_mod->where($map)->count('id');
        $pager = $this->_pager($count, $pagesize);
        $pager->path = 'ajax/comment_list';
		$pager->parameter ="itemid=$itemid&xid=$xid&order=$by";
		$sql="select * from try_comment where itemid=$itemid and xid=$xid and status=1 and pid=0  order by $order  limit $pager->firstRow , $pager->listRows ";
        $cmt_list = M()->query($sql);
		foreach($cmt_list as $key=>$v){
			$cmt_list[$key]['list']=M()->query("select * from try_comment where status=1 and pid='".$v['id']."' order by id asc");
		}
        $this->assign('cmt_list', $cmt_list);
        $data = array();
        $data['list'] = $this->fetch('comment');
        $data['page'] = $pager->jshow();
        $this->ajaxReturn(1, '', $data);
    }
	//赞评论
	public function comment_zan(){
		$id = $this->_post('id','intval');
		$ip = get_client_ip();
		if(cookie("ip".$id)==$ip){
			$this->ajaxReturn(0,'你已赞过');
		}
		$mod = M("comment");
		$r=$mod->where(array('id'=>$id))->setInc('zan');
		$data = $mod->where(array('id'=>$id))->getField('zan');
		if($r){
			cookie("ip".$id,$ip);
			$this->ajaxReturn(1, '', $data);
		}else{
			$this->ajaxReturn(0,'操作失败');
		}
	}
	//举报
	public function jb(){
		!$this->visitor->is_login && $this->ajaxReturn(0, '请登录！');
		$user = $this->visitor->get();
		$itemid=$this->_get('itemid','intval');
		$xid = $this->_get('xid','intval');
		//查询是否已举报过
		$c = M("jb")->where("uid=$user[id] and xid=$xid and itemid=$itemid")->find();
		if($c){//举报过
			$this->ajaxReturn(0, '您已举报过这条信息！');
		}
		$data['uid']=$user['id'];
		$data['uname']=$user['username'];
		$data['itemid']=$itemid;
		$data['xid']=$xid;
		$data['addtime']=time();		
		$r=M('jb')->add($data);
		if($r){			
			$this->ajaxReturn(1, '举报成功！');
		}else{
			$this->ajaxReturn(0, '举报失败！');
		}
	}
	//瀑布流
	public function getpbl(){
		
		$pagesize = $this->_get('pagesize','intval');
		$para = $this->_get('para','trim');
		$item_mod = M("item");
		if($para == "isbao"){
		   $item_mod = M("item_diu");
		}
		$time=time();
		$count=$item_mod->where("$para=1 and status=1 and add_time<$time")->count();
		$pager=$this->_pager($count,$pagesize);
		if($para == "isbao"){
			$pager->url="bao/index?type=$para" . "&dss=". $_SESSION['dss'];
		}
		else{
			$pager->url="index/index?type=$para" . "&dss=". $_SESSION['dss'];
		}
		$item_list = $item_mod->where("$para=1 and status=1 and add_time<$time")->limit($pager->firstRow.",".$pager->listRows)->order("add_time desc,id desc")->select();
		foreach($item_list as $key=>$val){
			$item_list[$key]['zan'] = $item_list[$key]['zan']   +intval($item_list[$key]['hits'] /10);
		}
		if($item_list){
			$dss=$_SESSION['dss'];
			$this->assign("dss",$dss);
			$this->assign("tab",$para);
			$this->assign('item_list',$item_list);
			$resp = $this->fetch('pbl_item');
			if($para == "isbao"){
				$resp = $this->fetch('pbl_item_bao');
			}
			$J_page = $pager->fshow();
			$this->ajaxReturn(1,'',array('resp'=>$resp,'pagebar'=>$J_page));
		}else{
			$this->ajaxReturn(0,'');
		}
	}
	//分享
	public function share(){
		$id=$this->_get('id','intval');
		$t = $this->_get("t","trim");
		$mod=M($t);
		$item = $mod->where("id=$id")->find();
		$item['img']=attach($item['img'],"$t");
		$site_url = C('pin_site_url');
		if(false === strpos($item['img'], 'http://')){
			$item['img']=$site_url.$item['img'];
		}
		$this->assign('item',$item);		
		switch($t){
			case "item":$xid=1;break;
			case "zr":$xid=2;break;
			case "article":$xid=3;break;
		}
		//添加到分享表
		if($this->visitor->is_login){ 
			$user = $this->visitor->get();
			if($s_info = M('share')->where("xid=$xid and item_id=$id and uid=$user[id]")->find()){
				$dm = $s_info['dm'];
			}else{
				$time=time();
				$dm =$this->getRandomString(10);		
				$data['dm']=$dm;
				$data['uid']=$user['id'];
				$data['xid']=$xid;
				$data['item_id']=$id;
				$data['add_time']=$time;
				//插入
				M('share')->add($data);
				//最高奖励3次
				$start=strtotime(date('Y-m-d',$time));
				$end = strtotime(date('Y-m-d',$time))+24*3600;
				$count = M("share")->where("add_time>$start and $end>add_time and uid=$user[id]")->count();
				if($count<=3){
					M("user")->where("id=$user[id]")->setField(array("score"=>$user['score']+10,"coin"=>$user['coin']+1,"offer"=>$user['offer']+1,"exp"=>$user['exp']+10));
					//积分日志
					set_score_log(array('id'=>$user['id'],'username'=>$user['username']),'share',10,1,1,10);
				}
			}
			$this->assign('url',$site_url.U("ajax/g_share",array('tg'=>$dm)));
			$this->assign('islogin','y');
		}else{
			$this->assign('url',$site_url."/$t/$id.html");
			$this->assign('islogin','n');
		}		
		$this->display();
	}
	public function g_share(){
		$tg = $this->_get('tg','trim');
		$info = M('share')->where("dm='$tg'")->find();
		!$info&&$this->_404();
		switch($info['xid']){
			case "1":$m="item";$a='index';break;
			case "2":$m="zr";$a='show';break;
			case "3":$m="article";$a='show';break;
		}
		$ip = getip();
		$myip = cookie("share_".$m."_".$info['item_id']);
		if($myip!=$ip){//不相同IP则加1
			cookie("share_".$m."_".$info['item_id'],$ip);
			M("share")->where("dm='$tg'")->setInc("hits");
			//查询一天贡献值
			$time=time();
			$start=strtotime(date('Y-m-d',$time));
			$end = strtotime(date('Y-m-d',$time))+24*3600;
			$count = M("score_log")->where("add_time>$start and $end>add_time and uid=$info[uid] and action='hit_share'")->count();
			if($count<100){
				$user = M("user")->where("id=$info[uid]")->find();
				M("user")->where("id=$info[uid]")->setField(array("coin"=>$user['coin']+1,"offer"=>$user['offer']+1));
				//积分日志
				set_score_log(array('id'=>$info['uid'],'username'=>get_uname($info['uid'])),'hit_share','',1,1,'');
			}
		}
		$_SESSION['tg']=$tg;
		$this->redirect("$m/$a",array('id'=>$info['item_id']));
	}
	function getRandomString($len, $chars=null)
	{
		if (is_null($chars)){
			$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		}  
		mt_srand(10000000*(double)microtime());
		for ($i = 0, $str = '', $lc = strlen($chars)-1; $i < $len; $i++){
			$str .= $chars[mt_rand(0, $lc)];
		}
		//检查是否重复
		if(M("share")->where("dm='$str'")->count()>0){
			$str = $this->getRandomString(10);
		}
		return $str;
	}
	//ajax保存草稿
	function save_item_cg(){
		$user = $this->visitor->get();		
        $item_mod = D('item');
		//过滤字符
		$kill_word = C("pin_kill_word");
		$kill_word = explode(",",$kill_word);
		if(in_array($_POST['content'],$kill_word)||in_array($_POST['title'],$kill_word)){
			$this->ajaxReturn(0,'您发表的内容有非法字符');
		}
        $item = $item_mod->create();
		$id = $this->_post("id","intval");
		if($id==0){//添加
			$item['intro'] = $this->_post('title', 'trim');
			$item['info'] = Input::deleteHtmlTags($item['info']);
			$item['uid'] = $this->visitor->info['id'];
			$item['uname'] = $this->visitor->info['username'];
			$item['isbao'] = '1';
			$item['status'] = 2;
			//添加凑单品，活动入口链接
			$arr[] = array('name'=>'直达链接','link'=>$this->_post('url',"trim"));
			$link_type=explode("|||",$this->_post("link_type"));
			$link_url =explode("|||",$this->_post("link_url"));
			foreach($link_type as $key=>$val){
				if($val!=""){
					$arr[]=array('name'=>$val,'link'=>$link_url[$key]); 
				}
			}
			$item['go_link']=serialize($arr);
			$imgs = explode("|||",$_POST['imgs']);
			foreach($imgs as $key=>$val){
				$item['imgs'][$key]['url']=$val;
			}	
			//添加商品
			$result = $item_mod->publish($item);
			if ($result) {
				//发布商品钩子
				$tag_arg = array('uid' => $item['uid'], 'uname' => $item['uname'], 'action' => 'pubitem');
				tag('pubitem_end', $tag_arg);
				$this->ajaxReturn(1,'保存草稿成功',array('id'=>$result,'aurl'=>U('item/edit')));
			} else {
				$this->ajaxReturn(0,'保存草稿失败');
			}
		}else{//编辑
			$item_mod = D('item');
			$item = $item_mod->create();
			$item['intro'] = $this->_post('title', 'trim');
			$item['info'] = Input::deleteHtmlTags($item['info']);
			$item['status'] = $this->_post("status",'intval');
			$item['status'] = 2;
			//添加凑单品，活动入口链接
			$arr = array();
			$link_type=explode("|||",$this->_post("link_type"));
			$link_url =explode("|||",$this->_post("link_url"));
			foreach($link_type as $key=>$val){
				if($val!=""){
					$arr[]=array('name'=>$val,'link'=>$link_url[$key]); 
				}
			}
			$item['go_link']=serialize($arr);
			$imgs = explode("|||",$_POST['imgs']);
			foreach($imgs as $key=>$val){
				$item['imgs'][$key]['url']=$val;
			}
			//编辑相册
			$imgs = M("item_img")->where("item_id=$item[id]")->select();
			$item_img_mod = D('item_img');
			foreach($item['imgs'] as $key=>$_img){
				$f=false;
				foreach($imgs as $k=>$v){
					if($_img['url']==$v['url']){
						$f=true;
					}
				}
				if($f==false){
					$_img['item_id'] = $item['id'];
					$item_img_mod->create($_img);
                    $item_img_mod->add();
				}
			}
			//编辑商品
			$result=$item_mod->where(array('id'=>$item['id']))->save($item);
			if ($result) {
				$this->ajaxReturn(1,'保存草稿成功',array('id'=>$item['id'],'aurl'=>U('item/edit')));
			} else {
				$this->ajaxReturn(0,'编辑失败');
			}
		}        
	}
	
	public function item_acg(){
		$id = $this->_post("id","intval");
		if($id==0){//新增
			//获取数据
			$item_mod=D('item');
            if (false === $data = $item_mod->create()) {
				$this->ajaxReturn(0,$item_mod->getError());
            }
            $data['uid'] ="0";
            $data['uname'] =$_SESSION['admin']['username'];
			$data['isnice'] ="1";
			$data['add_time']=strtotime($_POST['add_time']);
			$data['status'] ="2";
			$link_type = explode("|||",$_POST['link_type']);
			$link_url = explode("|||",$_POST['link_url']);
			foreach($link_type as $key=>$val){
				if($link_url[$key]!=''){
					$arr[$key]=array('name'=>$val,'link'=>$_POST['link_url'][$key]);
				}
			}
			$data['url']=$arr[0]['link'];
			$data['go_link']=serialize($arr);
            $item_id = $item_mod->publish($data);
            !$item_id && $this->ajaxReturn(0,L('operation_failure'));
            $this->ajaxReturn(1,'保存草稿成功',array('id'=>$item_id,'aurl'=>U('admin/item/edit')));
		}else{//修改
			//获取数据
			$item_mod=D('item');
            if (false === $data = $item_mod->create()) {
				$this->ajaxReturn(0,$item_mod->getError());
            }
			$data['add_time']=strtotime($_POST['add_time']);
			$link_type = explode("|||",$_POST['link_type']);
			$link_url = explode("|||",$_POST['link_url']);
			foreach($link_type as $key=>$val){
				if($link_url[$key]!=''){
					$arr[$key]=array('name'=>$val,'link'=>$link_url[$key]);
				}
			}
			$data['url']=$arr[0]['link'];
			$data['go_link']=serialize($arr);
            $result=$item_mod->where(array('id'=>$id))->save($data);
            !$result && $this->ajaxReturn(0,L('operation_failure'));
            $this->ajaxReturn(1,'保存草稿成功',array('id'=>$item_id,'aurl'=>U('admin/item/edit')));
		}
	}
	public function bj_comment(){
		$id = $this->_post("id","intval");
		$g_id = $this->_post("g_id","intval");
		$uid = $this->_post("uid","intval");
		$jf = $this->_post("jf","intval");
		$offer = $this->_post("gx","intval");
		$coin = $this->_post("cf","intval");

	
		$title =$this->_post("title","trim");
		$pingyu = $this->_post("pingyu","trim");
		if($id){
			M()->query("update try_comment set jf=jf+$jf,pingyu='".$pingyu."' where id=$id");
			M()->query("update try_user set score=score+$jf,offer=offer+$offer,coin=coin+$coin where id=$uid");
			$user = M('user')->where('id='.$uid)->find();
			$msg="操作成功";
			$xc = array();
			$xc['ftid']=$id;
			$xc['to_id']=$user['id'];
			$xc['to_name']=$user['username'];;
			$xc['from_id']=$this->visitor->info['id'];
			$xc['from_name']=$this->visitor->info['username'];
			$xc['add_time']=time();
			
			$xc['info'] ="您对 >> <a href='".U('item/index',array('id'=>$g_id))."'>".$_POST['title']."</a> 评论被管理员评论了，感谢您的支持,系统给您奖励积分：".$jf.',金币'.$coin.',贡献值：'.$offer;
		   
			M('message')->add($xc);
			set_score_log(array('id'=>$user['id'],'username'=>$user['username']),'comment1',$jf,$coin,$offer,'');
		}else{
			$msg="操作失败";
		}
		$msg1=iconv('UTF-8','GBK',$msg);
		echo "<script>javascript:alert('".$msg1."');self.location=document.referrer;</script>";
		exit;
	}

	public function sc_comment(){
		$id = $this->_get("id","intval");
		$uid = $this->_get("uid","intval");

		if($id){
			M()->query("update try_comment set status=0 where id=$id");
			M()->query("update try_user set score='score-10' where id=$uid");
			$user = M('user')->where('id='.$uid)->find();

			set_score_log(array('id'=>$user['id'],'username'=>$user['username']),'comment1','-10','','','');
			$xc = array();
			$xc['ftid']=$id;
			$xc['to_id']=$user['id'];
			$xc['to_name']=$user['username'];
			$xc['from_id']=$this->visitor->info['id'];
			$xc['from_name']=$this->visitor->info['username'];
			$xc['add_time']=time();
			$xc['info'] ='您的评论被管理员删除，扣除积分：10.';
			M('message')->add($xc);
			$msg="操作成功";
		}else{
			$msg="操作失败";
		}
		$msg1=iconv('UTF-8','GBK',$msg);
		echo "<script>javascript:alert('".$msg1."');self.location=document.referrer;</script>";
		exit;
	}
}