<?php

class userAction extends userbaseAction {
	
	public function index(){
		!$this->visitor->is_login && $this->redirect('index/index');
		$type = $this->_get('type','trim');
		$info = $this->visitor->get();
		$uid = $info['id'];
		//等级
		$exp=$info['exp'];
		$info['grade'] = M("grade")->where("min<=$exp and max>=$exp")->getField("grade");
		$info['s_a'] = M("share")->where("uid=$uid")->count();//分享的文章
		//获取关注好友
		$wsql = "select follow_uid from try_user_follow where uid=$uid";
		$hsql = "select id from try_item_orig where ismy=$";
		switch($type){
			case "jp":$tsql=" isbest=1 ";break;
			case "gn":$tsql=" orig_id in(select id from try_item_orig where ismy=0) ";break;
			case "ht":$tsql=" orig_id in(select id from try_item_orig where ismy=1) ";break;
			case "sd":$tsql=" cate_id=10 ";break;
			case "gl":$tsql=" cate_id=9 ";break;
			case "zr":$tsql=" ";break;
			case "":break;
		}
		if($type=="jp"||$type=="gn"||$type=="ht"){			
			$sqlc="select count(*) as num from try_item where $tsql and uid in($wsql) and status=1 ";
			$sql = "select * from try_item where $tsql and uid in($wsql) and status=1 order by isbest desc,id desc ";
		}elseif($type=="sd"||$type=="gl"){
			$sqlc="select count(*) as num from try_article where $tsql and uid in($wsql)  and status=1 ";
			$sql = "select * from try_article where $tsql and uid in($wsql)  and status=1 order by isbest desc,id desc ";
		}elseif($type=="zr"){//转让
			$sqlc="select count(*) as num from try_zr where uid in($wsql) and status=1";
			$sql = "select * from try_zr where uid in($wsql) and status=1 order by id desc";
		}else{
			$sqlc="select count(*) as num from try_item where uid in($wsql) and status=1 ";
			$sql="select * from try_item where uid in($wsql) and status=1 order by isbest desc,id desc ";
		}
		$mod = M();
		$pagesize=3;
		$allc = $mod->query($sqlc);
		$count = $allc[0]['num'];
		$pager=$this->_pager($count,$pagesize);
		$sql = $sql." limit ".$pager->firstRow.",".$pager->listRows;
		$list = $mod->query($sql);
		$this->assign('list',$list);
		$this->assign('pagebar',$pager->fshow());
		$this->assign('type',$type);	
		$this->assign('info',$info);	
		$user_list = M("user")->order("shares desc, id asc")->limit(4)->select();
		$arr = M("user_follow")->where("uid=$info[id]")->select();
		foreach($arr as $key=>$val){
			$follow_uid[$val['follow_uid']]=1;
		}
		foreach($user_list as $key=>$val){
			$user_list[$key]['follow']=$follow_uid[$val['id']];
		}
		$this->assign("page_seo",set_seo('个人中心'));
		$this->assign('user_list',$user_list);
		$this->display();
	}
    /**
     * 用户登陆
     */
    public function login() {
        $this->visitor->is_login && $this->redirect('user/index');
        if (IS_POST) {
			//封IP start
			$kill_ip=C('pin_kill_ip');
			$kill_ip = explode("\n",$kill_ip);
			$myip = get_client_ip();
			if(in_array($myip,$kill_ip)){
				$this->error('您的账户已被禁用');
			}
			//封IP end
            $username = $this->_post('username', 'trim');
            $password = $this->_post('password', 'trim');
            $remember = $this->_post('remember');
            if (empty($username)) {
                IS_AJAX && $this->ajaxReturn(0, L('please_input').L('password'));
                $this->error(L('please_input').L('username'));
            }
            if (empty($username)) {
                IS_AJAX && $this->ajaxReturn(0, L('please_input').L('password'));
                $this->error(L('please_input').L('password'));
            }
            //连接用户中心
            $passport = $this->_user_server();
            $uid = $passport->auth($username, $password);
            if (!$uid) {
                IS_AJAX && $this->ajaxReturn(0, $passport->get_error());
                $this->error($passport->get_error());
            }
            //登陆
            $this->visitor->login($uid, $remember);
            //登陆完成钩子
            $tag_arg = array('uid'=>$uid, 'uname'=>$username, 'action'=>'login');
            tag('login_end', $tag_arg);
            //同步登陆            
			$synlogin = $passport->synlogin($uid);			
            if (IS_AJAX) {
                $this->ajaxReturn(1, L('login_successe').$synlogin);
            }else {
                //跳转到登陆前页面（执行同步操作）
                $ret_url = $this->_post('ret_url', 'trim');
                $this->success(L('login_successe').$synlogin, $ret_url);
            }
        } else {
            /* 同步退出外部系统 */
            if (!empty($_GET['synlogout'])) {
                $passport = $this->_user_server();
                $synlogout = $passport->synlogout();
            }
            if (IS_AJAX) {
                $resp = $this->fetch('dialog:login');
                $this->ajaxReturn(1, '', $resp);
            } else {
                //来路
                $ret_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : __APP__;
                $this->assign('ret_url', $ret_url);
                $this->assign('synlogout', $synlogout);
				$this->assign("page_seo",set_seo('用户登录'));
                $this->display();
            }
        }
    }

    /**
     * 用户退出
     */
    public function logout() {
        $this->visitor->logout();
        //同步退出
        $passport = $this->_user_server();
        $synlogout = $passport->synlogout();
        //跳转到退出前页面（执行同步操作）
        $this->success(L('logout_successe').$synlogout, U('index/index'));
    }

    /**
     * 用户绑定
     */
    public function binding() {
        $user_bind_info = object_to_array(cookie('user_bind_info'));
        $this->assign('user_bind_info', $user_bind_info);
        $this->assign("page_seo",set_seo('用户绑定'));
        $this->display();
    }

    /**
    * 用户注册
    */
    public function register() {
        $this->visitor->is_login && $this->redirect('user/index');
        if (IS_POST) {
            //封IP start
            $kill_ip=C('pin_kill_ip');
            $kill_ip = explode("\n",$kill_ip);
            $myip = get_client_ip();
            if(in_array($myip,$kill_ip)){
                $this->error('您的IP已被禁用');
            }
            //封IP end
            //方式
            $type = $this->_post('type', 'trim', 'reg');
            if ($type == 'reg') {
                //验证
                $captcha = $this->_post('captcha', 'trim');
                if(session('captcha') != md5($captcha)){
                    $this->error(L('captcha_failed'));
                }
            }
            $username = $this->_post('username', 'trim');
            $email = $this->_post('email','trim');
            $password = $this->_post('password', 'trim');
            $repassword = $this->_post('repassword', 'trim');
            if ($password != $repassword) {
                $this->error(L('inconsistent_password')); //确认密码
            }
            $gender = $this->_post('gender','intval', '0');
            //用户禁止
            $ipban_mod = D('ipban');
            $ipban_mod->clear(); //清除过期数据
            $is_ban = $ipban_mod->where("(type='name' AND name='".$username."') OR (type='email' AND name='".$email."')")->count();
            $is_ban && $this->error(L('register_ban'));
            //连接用户中心
            $passport = $this->_user_server();
            //注册
            $uid = $passport->register($username, $password, $email, $gender);
            !$uid && $this->error($passport->get_error());
            //是否通过朋友分享注册的
            if(trim($_SESSION['tg'])!=''){
                $suid = M("user")->field('try_user.*')->join("try_share as s on s.uid=try_user.id")->where("s.dm='$_SESSION[tg]'")->find();
                //查找一天是否超过5次
                $time=time();
                $start=strtotime(date('Y-m-d',$time));
                $end = strtotime(date('Y-m-d',$time))+24*3600;
                $count = M("score_log")->where("add_time>$start and $end>add_time and uid=$suid[id] and action='share_register'")->count();
                if($count<5){
                    //给用户加积分
                    M("user")->where("id=$suid[id]")->setField(array("coin"=>$suid['coin']+5,"offer"=>$suid['offer']+5,'score'=>$suid['score']+5,'exp'=>$suid['exp']+5));
                    //积分日志
                    set_score_log(array('id'=>$suid['id'],'username'=>$suid['username']),'share_register',5,5,5,5);
                }
            }
            //第三方帐号绑定
            if (cookie('user_bind_info')) {
                $user_bind_info = object_to_array(cookie('user_bind_info'));
                $oauth = new oauth($user_bind_info['type']);
                $bind_info = array(
                    'pin_uid' => $uid,
                    'keyid' => $user_bind_info['keyid'],
                    'bind_info' => $user_bind_info['bind_info'],
                );
                $oauth->bindByData($bind_info);
                //临时头像转换
                $this->_save_avatar($uid, $user_bind_info['temp_avatar']);
                //清理绑定COOKIE
                cookie('user_bind_info', NULL);
            }
            //注册完成钩子
            $tag_arg = array('uid'=>$uid, 'uname'=>$username, 'action'=>'register');
            tag('register_end', $tag_arg);
            //登陆
            $this->visitor->login($uid);
            //登陆完成钩子
            $tag_arg = array('uid'=>$uid, 'uname'=>$username, 'action'=>'login');
            tag('login_end', $tag_arg);
            //同步登陆
            $synlogin = $passport->synlogin($uid);
            $this->success(L('register_successe').$synlogin, U('user/index'));
        } else {
            //关闭注册
            if (!C('pin_reg_status')) {
                $this->error(C('pin_reg_closed_reason'));
            }
            $this->assign("page_seo",set_seo('用户注册'));
            $this->display();
        }
    }
    
    public function register1() {
        $this->visitor->is_login && $this->redirect('user/index');
        if (IS_POST) {
			//封IP start
			$kill_ip=C('pin_kill_ip');
			$kill_ip = explode("\n",$kill_ip);
			$myip = get_client_ip();
			if(in_array($myip,$kill_ip)){
				$this->error('您的IP已被禁用');
			}
			//封IP end
            //方式
            $type = $this->_post('type', 'trim', 'reg');
            if ($type == 'reg') {
                //验证
                $agreement = $this->_post('agreement');
                !$agreement && $this->error(L('agreement_failed'));

                $captcha = $this->_post('captcha', 'trim');
                if(session('captcha') != md5($captcha)){
                    $this->error(L('captcha_failed'));
                }
            }
            $username = $this->_post('username', 'trim');
            $email = $this->_post('email','trim');
            $password = $this->_post('password', 'trim');
            $gender = $this->_post('gender','intval', '0');
            //用户禁止
            $ipban_mod = D('ipban');
            $ipban_mod->clear(); //清除过期数据
            $is_ban = $ipban_mod->where("(type='name' AND name='".$username."') OR (type='email' AND name='".$email."')")->count();
            $is_ban && $this->error(L('register_ban'));
            //连接用户中心
            $passport = $this->_user_server();
            //注册
            $uid = $passport->register($username, $password, $email, $gender);
            !$uid && $this->error($passport->get_error());
			//是否通过朋友分享注册的
			if(trim($_SESSION['tg'])!=''){				
				$suid = M("user")->field('try_user.*')->join("try_share as s on s.uid=try_user.id")->where("s.dm='$_SESSION[tg]'")->find();
				//查找一天是否超过5次
				$time=time();
				$start=strtotime(date('Y-m-d',$time));
				$end = strtotime(date('Y-m-d',$time))+24*3600;
				$count = M("score_log")->where("add_time>$start and $end>add_time and uid=$suid[id] and action='share_register'")->count();
				if($count<5){
					//给用户加积分
					M("user")->where("id=$suid[id]")->setField(array("coin"=>$suid['coin']+5,"offer"=>$suid['offer']+5,'score'=>$suid['score']+5,'exp'=>$suid['exp']+5));
					//积分日志
					set_score_log(array('id'=>$suid['id'],'username'=>$suid['username']),'share_register',5,5,5,5);
				}
			}
            //第三方帐号绑定
            if (cookie('user_bind_info')) {
                $user_bind_info = object_to_array(cookie('user_bind_info'));
                $oauth = new oauth($user_bind_info['type']);
                $bind_info = array(
                    'pin_uid' => $uid,
                    'keyid' => $user_bind_info['keyid'],
                    'bind_info' => $user_bind_info['bind_info'],
                );
                $oauth->bindByData($bind_info);
                //临时头像转换
                $this->_save_avatar($uid, $user_bind_info['temp_avatar']);
                //清理绑定COOKIE
                cookie('user_bind_info', NULL);
            }
            //注册完成钩子
            $tag_arg = array('uid'=>$uid, 'uname'=>$username, 'action'=>'register');
            tag('register_end', $tag_arg);
            //登陆
            $this->visitor->login($uid);
            //登陆完成钩子
            $tag_arg = array('uid'=>$uid, 'uname'=>$username, 'action'=>'login');
            tag('login_end', $tag_arg);
            //同步登陆
            $synlogin = $passport->synlogin($uid);
            $this->success(L('register_successe').$synlogin, U('user/index'));
        } else {
            //关闭注册
            if (!C('pin_reg_status')) {
                $this->error(C('pin_reg_closed_reason'));
            }
            $this->assign("page_seo",set_seo('用户注册'));
            $this->display();
        }
    }

    /**
     * 第三方头像保存
     */
    private function _save_avatar($uid, $img) {
        //获取后台头像规格设置
        $avatar_size = explode(',', C('pin_avatar_size'));
        //会员头像保存文件夹
        $avatar_dir = C('pin_attach_path') . 'avatar/' . avatar_dir($uid);
        !is_dir($avatar_dir) && mkdir($avatar_dir,0777,true);
        //生成缩略图
        $img = C('pin_attach_path') . 'avatar/temp/' . $img;
        foreach ($avatar_size as $size) {
            Image::thumb($img, $avatar_dir.md5($uid).'_'.$size.'.jpg', '', $size, $size, true);
        }
        @unlink($img);
    }
    
    /**
     * 用户消息提示 
     */
    public function msgtip() {
        $result = D('user_msgtip')->get_list($this->visitor->info['id']);
        $this->ajaxReturn(1, '', $result);
    }
	
    /**
    * 基本信息修改
    */
    public function profile() {
        if( IS_POST ){
            foreach ($_POST as $key=>$val) {
                $_POST[$key] = Input::deleteHtmlTags($val);
            }
            $data['gender'] = $this->_post('gender', 'intval');
            //$data['province'] = $this->_post('province', 'trim');
            //$data['city'] = $this->_post('city', 'trim');
            //$data['tags'] = $this->_post('tags', 'trim');
            //$data['intro'] = $this->_post('intro', 'trim');
            $birthday = $this->_post('birthday', 'trim');
            $birthday = explode('-', $birthday);
            $data['byear'] = $birthday[0];
            $data['bmonth'] = $birthday[1];
            $data['bday'] = $birthday[2];
			//$data['realname']=$this->_post('realname','trim');
			//$data['zipcode']=$this->_post('zipcode','trim');
			//$data['mobile']=$this->_post('mobile','trim');
			//$data['address']=$this->_post('address','trim');
            if (false !== M('user')->where(array('id'=>$this->visitor->info['id']))->save($data)) {
                $msg = array('status'=>1, 'info'=>L('edit_success'));
            }else{
                $msg = array('status'=>0, 'info'=>L('edit_failed'));
            }
            $this->assign('msg', $msg);
        }
        $info = $this->visitor->get();
        $this->assign('info', $info);
		$this->assign('title_h2', '我的账户');
        $this->assign("page_seo",set_seo('基本信息修改'));
        $this->display();
    }

    /**
     * 修改头像
     */
    public function upload_avatar() {
        if (!empty($_FILES['avatar']['name'])) {
            //会员头像规格
            $avatar_size = explode(',', C('pin_avatar_size'));
            //回去会员头像保存文件夹
            $uid = abs(intval($this->visitor->info['id']));	
            $suid = sprintf("%09d", $uid);
            $dir1 = substr($suid, 0, 3);
            $dir2 = substr($suid, 3, 2);
            $dir3 = substr($suid, 5, 2);
            $avatar_dir = $dir1.'/'.$dir2.'/'.$dir3.'/';
            //上传头像
            $suffix = '';
            foreach ($avatar_size as $size) {
                $suffix .= '_'.$size.',';
            }
            $result = $this->_upload($_FILES['avatar'], 'avatar/'.$avatar_dir, array(
                'width'=>C('pin_avatar_size'), 
                'height'=>C('pin_avatar_size'),
                'remove_origin'=>true, 
                'suffix'=>trim($suffix, ','),
                'ext' => 'jpg',
            ), md5($uid));
            if ($result['error']) {
                $this->ajaxReturn(0, $result['info']);
            } else {
				//是否首次上传图片
				$info = M("user")->where("id=$uid")->find();
				if(intval($info['is_avator'])==0){//未传过则加积分
					$data['score']=$info['score']+10;
					$data['is_avator']=1;
					M("user")->where("id=$info[id]")->save($data);
					//积分日志
					set_score_log(array('id'=>$info['id'],'username'=>$info['username']),'upload_avator',10,'','','');
				}
                $data = __ROOT__.'/data/upload/avatar/'.$avatar_dir.md5($uid).'_'.$size.'.jpg?'.time();
                $this->ajaxReturn(1, L('upload_success'), $data);
            }
        } else {
            $this->ajaxReturn(0, L('illegal_parameters'));
        }
    }

    /**
     * 修改密码
     */
    public function password() {
        if( IS_POST ){
            $oldpassword = $this->_post('oldpassword','trim');
            $password   = $this->_post('password','trim');
            $repassword = $this->_post('repassword','trim');
            !$password && $this->error(L('no_new_password'));
            $password != $repassword && $this->error(L('inconsistent_password'));
            $passlen = strlen($password);
			$captcha = $this->_post('captcha', 'trim');
			if(session('captcha')!= md5($captcha)){
				$this->error(L('captcha_failed'));
			}
            if ($passlen < 6 || $passlen > 20) {
                $this->error('password_length_error');
            }
            //连接用户中心
            $passport = $this->_user_server();
            $result = $passport->edit($this->visitor->info['id'], $oldpassword, array('password'=>$password));
            if ($result) {
                $msg = array('status'=>1, 'info'=>L('edit_password_success'));
				$this->success($msg['info'],U('wap/user/password'));
            } else {
                $msg = array('status'=>0, 'info'=>$passport->get_error());
				$this->error($msg['info']);
            }
            $this->assign('msg', $msg);
        }
        $this->assign("page_seo",set_seo('修改密码'));
		$this->assign("title_h2",'修改密码');
        $this->display();
    }

    /**
     * 帐号绑定
     */
    public function bind() {
        //获取已经绑定列表
        $bind_list = M('user_bind')->field('type')->where(array('uid'=>$this->visitor->info['id']))->select();
        $binds = array();
        if ($bind_list) {
            foreach ($bind_list as $val) {
                $binds[] = $val['type'];
            }
        }
        
        //获取网站支持列表
        $oauth_list = $this->oauth_list;
        foreach ($oauth_list as $type => $_oauth) {
            $oauth_list[$type]['isbind'] = '0';
            if (in_array($type, $binds)) {
                $oauth_list[$type]['isbind'] = '1';
            }
        }
        $this->assign('oauth_list', $oauth_list);
        $this->assign("page_seo",set_seo('帐号绑定'));
        $this->display();
    }

    /**
     * 个人空间banner背景设置
     */
    public function custom() {
        $cover = $this->visitor->get('cover');
        $this->assign('cover', $cover);
        $this->_config_seo();
        $this->display();
    }

    /**
     * 取消封面
     */
    public function cancle_cover() {
        $result = M('user')->where(array('id'=>$this->visitor->info['id']))->setField('cover', '');
        !$result && $this->ajaxReturn(0, L('illegal_parameters'));
        $this->ajaxReturn(1, L('edit_success'));
    }

    /**
     * 上传封面图片
     */
    public function upload_cover() {
        if (!empty($_FILES['cover']['name'])) {
            $data_dir = date('ym/d');
            $file_name = md5($this->visitor->info['id']);
            $result = $this->_upload($_FILES['cover'], 'cover/'.$data_dir, array('width'=>'900', 'height'=>'330', 'remove_origin'=>true), $file_name);
            if ($result['error']) {
                $this->ajaxReturn(0, $result['info']);
            } else {
                $ext = array_pop(explode('.', $result['info'][0]['savename']));
                $cover = $data_dir.'/'.$file_name.'_thumb.'.$ext;
                $data = '<img src="./data/upload/cover/'.$data_dir.'/'.$file_name.'_thumb.'.$ext.'?'.time().'">';
                //更新数据
                M('user')->where(array('id'=>$this->visitor->info['id']))->setField('cover', $cover);
                $this->ajaxReturn(1, L('upload_success'), $data);
            }
        } else {
            $this->ajaxReturn(0, L('illegal_parameters'));
        }
    }

    /**
     * 收货地址
     */
    public function address() {
        $user_address_mod = M('user_address');
        $id = $this->_get('id', 'intval');
        $type = $this->_get('type', 'trim', 'edit');
        if ($id) {
            if ($type == 'del') {
                $user_address_mod->where(array('id'=>$id, 'uid'=>$this->visitor->info['id']))->delete();
                $msg = array('status'=>1, 'info'=>L('delete_success'));
                $this->assign('msg', $msg);
            } else {
                $info = $user_address_mod->find($id);
                $this->assign('info', $info);
            }
        }
        if (IS_POST) {
            $consignee = $this->_post('consignee', 'trim');
            $address = $this->_post('address', 'trim');
            $zip = $this->_post('zip', 'trim');
            $mobile = $this->_post('mobile', 'trim');
            $id = $this->_post('id', 'intval');
            if ($id) {
                $result = $user_address_mod->where(array('id'=>$id, 'uid'=>$this->visitor->info['id']))->save(array(
                    'consignee' => $consignee,
                    'address' => $address,
                    'zip' => $zip,
                    'mobile' => $mobile,
                ));
                if ($result) {
                    $msg = array('status'=>1, 'info'=>L('edit_success'));
                } else {
                    $msg = array('status'=>0, 'info'=>L('edit_failed'));
                }
            } else {
                $result = $user_address_mod->add(array(
                    'uid' => $this->visitor->info['id'],
                    'consignee' => $consignee,
                    'address' => $address,
                    'zip' => $zip,
                    'mobile' => $mobile,
                ));
                if ($result) {
                    $msg = array('status'=>1, 'info'=>L('add_address_success'));
                } else {
                    $msg = array('status'=>0, 'info'=>L('add_address_failed'));
                }
            }
            $this->assign('msg', $msg);
        }
        $address_list = $user_address_mod->where(array('uid'=>$this->visitor->info['id']))->select();
        $this->assign('address_list', $address_list);
        $this->assign('page_seo',set_seo('收货地址'));
		$this->assign('title_h2','收货地址');
        $this->display();
    }

    /**
     * 检测用户
     */
    public function ajax_check() {
        $type = $this->_get('type', 'trim', 'email');
        $user_mod = D('user');
        switch ($type) {
            case 'email':
                $email = $this->_get('J_email', 'trim');
                $user_mod->email_exists($email) ? $this->ajaxReturn(0) : $this->ajaxReturn(1);
                break;
            
            case 'username':
                $username = $this->_get('J_username', 'trim');
                $user_mod->name_exists($username) ? $this->ajaxReturn(0) : $this->ajaxReturn(1);
                break;
        }
    }

    /**
     * 关注
     */
    public function follow() {
        $uid = $this->_get('uid', 'intval');
        !$uid && $this->ajaxReturn(0, L('follow_invalid_user'));
        $uid == $this->visitor->info['id'] && $this->ajaxReturn(0, L('follow_self_not_allow'));
        $user_mod = M('user');
        if (!$user_mod->where(array('id'=>$uid))->count('id')) {
            $this->ajaxReturn(0, L('follow_invalid_user'));
        }
        $user_follow_mod = M('user_follow');
        //已经关注？
        $is_follow = $user_follow_mod->where(array('uid'=>$this->visitor->info['id'], 'follow_uid'=>$uid))->count();
        $is_follow && $this->ajaxReturn(0, L('user_is_followed'));
        //关注动作
        $return = 1;
        //他是否已经关注我
        $map = array('uid'=>$uid, 'follow_uid'=>$this->visitor->info['id']);
        $isfollow_me = $user_follow_mod->where($map)->count();
        $data = array('uid'=>$this->visitor->info['id'], 'follow_uid'=>$uid, 'add_time'=>time());
        if ($isfollow_me) {
            $data['mutually'] = 1; //互相关注
            $user_follow_mod->where($map)->setField('mutually', 1); //更新他关注我的记录为互相关注
            $return = 2;
        }
        $result = $user_follow_mod->add($data);
        !$result && $this->ajaxReturn(0, L('follow_user_failed'));
        //增加我的关注人数
        $user_mod->where(array('id'=>$this->visitor->info['id']))->setInc('follows');
        //增加Ta的粉丝人数
        $user_mod->where(array('id'=>$uid))->setInc('fans');
        //提醒被关注的人
        D('user_msgtip')->add_tip($uid, 1);
        //把他的微薄推送给我
        //TODO...是否有必要？
        $this->ajaxReturn(1, L('follow_user_success'), $return);
    }

    /**
     * 取消关注
     */
    public function unfollow() {
        $uid = $this->_get('uid', 'intval');
        !$uid && $this->ajaxReturn(0, L('unfollow_invalid_user'));
        $user_follow_mod = M('user_follow');
        if ($user_follow_mod->where(array('uid'=>$this->visitor->info['id'], 'follow_uid'=>$uid))->delete()) {
            $user_mod = M('user');
            //他是否已经关注我
            $map = array('uid'=>$uid, 'follow_uid'=>$this->visitor->info['id']);
            $isfollow_me = $user_follow_mod->where($map)->count();
            if ($isfollow_me) {
                $user_follow_mod->where($map)->setField('mutually', 0); //更新他关注我的记录为互相关注
            }
            //减少我的关注人数
            $user_mod->where(array('id'=>$this->visitor->info['id']))->setDec('follows');
            //减少Ta的粉丝人数
            $user_mod->where(array('id'=>$uid))->setDec('fans');
            //删除我微薄中Ta的内容
            M('topic_index')->where(array('author_id'=>$uid, 'uid'=>$this->visitor->info['id']))->delete();
            $this->ajaxReturn(1, L('unfollow_user_success'));
        } else {
            $this->ajaxReturn(0, L('unfollow_user_failed'));
        }
    }

    /**
     * 移除粉丝
     */
    public function delfans() {
        $uid = $this->_get('uid', 'intval');
        !$uid && $this->ajaxReturn(0, L('delete_invalid_fans'));
        $user_follow_mod = M('user_follow');
        if ($user_follow_mod->where(array('follow_uid'=>$this->visitor->info['id'], 'uid'=>$uid))->delete()) {
            $user_mod = M('user');
            //减少我的粉丝人数
            $user_mod->where(array('id'=>$this->visitor->info['id']))->setDec('fans');
            //减少Ta的关注人数
            M('user')->where(array('id'=>$uid))->setDec('follows');
            //删除Ta微薄中我的内容
            M('topic_index')->where(array('author_id'=>$this->visitor->info['id'], 'uid'=>$uid))->delete();
            $this->ajaxReturn(1, L('delete_fans_success'));
        } else {
            $this->ajaxReturn(0, L('delete_fans_failed'));
        }
    }
	//爆料达人
	public function getu(){
		$count=M("user")->count();
		$pager=$this->_pager($count,4);
		$user_list = M("user")->order("shares desc, id asc")->limit($pager->firstRow.",".$pager->listRows)->select();
		$arr = M("user_follow")->where("uid=$info[id]")->select();
		foreach($arr as $key=>$val){
			$follow_uid[$val['follow_uid']]=1;
		}
		foreach($user_list as $key=>$val){
			$user_list[$key]['follow']=$follow_uid[$val['id']];
		}
		$this->assign('user_list',$user_list);
		$resp = $this->fetch('dialog:ulist');
        $this->ajaxReturn(1, '', $resp);
	}
	
	/**
     * 我关注的
     */
    public function myfollow() {		
		!$this->visitor->is_login && $this->redirect('user/login');
		$user = $this->visitor->get();
        $user_follow_mod = M('user_follow');
        $db_pre = C('DB_PREFIX');
        $uf_table = $db_pre . 'user_follow';
		$more = $this->_get('more','trim');
		$p=$this->_get('p','intval') ? $this->_get('p','intval') : 1;
        $pagesize = 8;
		$start=($p-1)*$pagesize;
        $count = $user_follow_mod->where(array('uid' => $user['id']))->count();
        //$pager = $this->_pager($count, $pagesize);
        $where = array($uf_table . '.uid' => $user['id']);
        $field = 'u.id,u.username,u.fans,u.last_time,' . $uf_table . '.add_time,' . $uf_table . '.mutually,u.score,u.shares';
        $join = $db_pre . 'user u ON u.id=' . $uf_table . '.follow_uid';
        $user_list = $user_follow_mod->field($field)->where($where)->join($join)->order($uf_table . '.add_time DESC')->limit($start. ',' . $pagesize)->select();
        if($more == 'more'){
			$more_arr="";
			foreach($user_list as $key=>$r){
				$more_arr.="<li><a href='javascript:;' title='取消关注' class='J_unfo qxgz' data-id='".$r['id']."'>取消关注</a>";
				$more_arr.="<a href='".U('wap/space/index', array('uid'=>$r['id']))."' title='".$r['username']."' >";
				$more_arr.="<img src='".avatar($r['id'], 48)."' title='".$r['username']."' alt='".$r['username']."'/>";
				$more_arr.="<h2>".$r['username']."</h2></a>";
				$more_arr.="<div><span>爆料：<i>".$r['shares']."</i></span><em>|</em><span>评论：<i>".$r['score']."</i></span></div></li>";
			}
			echo $more_arr;
			exit;
		}
		$this->assign('user_list', $user_list);
        //$this->assign('page_bar', $pager->fshow());
        $this->assign('tab_current', 'follow');
		$this->assign('pagesize', $pagesize);
		$this->assign('count', $count);
		$this->assign('title_h2','好友管理');
        $this->_config_seo(array(
            'title' => $user['username'] . L('space_follow_title') . '-' . C('pin_site_name'),
        ));
        $this->display();
    }
	/**
     * 粉丝
     */
    public function fans() {
		!$this->visitor->is_login && $this->redirect('user/login');
		$user = $this->visitor->get();
        $user_follow_mod = M('user_follow');
        $db_pre = C('DB_PREFIX');
        $uf_table = $db_pre . 'user_follow';
		$more = $this->_get('more','trim');
		$p=$this->_get('p','intval') ? $this->_get('p','intval') : 1;
        $pagesize = 8;
		$start=($p-1)*$pagesize;
        $count = $user_follow_mod->where(array('follow_uid' => $user['id']))->count();
        //$pager = $this->_pager($count, $pagesize);
        $where = array($uf_table . '.follow_uid' => $user['id']);
        $field = 'u.id,u.username,u.fans,u.last_time,' . $uf_table . '.add_time,' . $uf_table . '.mutually,u.score,u.shares';
        $join = $db_pre . 'user u ON u.id=' . $uf_table . '.uid';
        $user_list = $user_follow_mod->field($field)->where($where)->join($join)->order($uf_table . '.add_time DESC')->limit($start . ',' . $pagesize)->select();
        if ($this->visitor->is_login) {
            D('user_msgtip')->clear_tip($this->visitor->info['id'], 1);
        }
		//myfollow
		$arr = $user_follow_mod->where(array("uid"=>$user['id']))->select();
		foreach($arr as $key=>$val){
			$flist[$val['follow_uid']]=1;
		}
		foreach($user_list as $key=>$val){
			$user_list[$key]['follow']=$flist[$val['id']];
		}
		if($more == 'more'){
			$more_arr="";
			foreach($user_list as $key=>$r){
				$class=$r['follow']==1 ? "J_unfo" : "J_fo";
				$title=$r['follow']==1 ? "取消关注" : "关注";
				
				$more_arr.="<li><a href='javascript:;' title='$title' class='$class qxgz' data-id='".$r['id']."'>$title</a>";
				$more_arr.="<a href='".U('wap/space/index', array('uid'=>$r['id']))."' title='".$r['username']."' >";
				$more_arr.="<img src='".avatar($r['id'], 48)."' title='".$r['username']."' alt='".$r['username']."'/>";
				$more_arr.="<h2>".$r['username']."</h2></a>";
				$more_arr.="<div><span>爆料：<i>".$r['shares']."</i></span><em>|</em><span>评论：<i>".$r['score']."</i></span></div></li>";
			}
			echo $more_arr;
			exit;
		}
        $this->assign('user_list', $user_list);
		$this->assign('pagesize', $pagesize);
		$this->assign('count', $count);
        //$this->assign('page_bar', $pager->fshow());
        $this->assign('tab_current', 'follow');
		$this->assign('title_h2','好友管理');
		$this->_curr_menu('myfollow');
        $this->_config_seo(array(
            'title' => $user['username'] . L('space_fans_title') . '-' . C('pin_site_name'),
        ));
        $this->display();
    }
	//我的优惠券
	public function tick(){
		!$this->visitor->is_login && $this->redirect('user/login');
		$user = $this->visitor->get();
		$mod_tick = M('tk');
		$where =" 1=1 and uid=$user[id] ";
		$gq = $this->_get('gq','intval');
		$more = $this->_get('more','trim');
		$p=$this->_get('p','intval') ? $this->_get('p','intval') : 1;
		if($gq==1){$where .=" and t.end_time<NOW() ";$tab=1;}
		$pagesize=8;
		$start=($p-1)*$pagesize;
		$count = $mod_tick->where($where)->count();
		//$pager=$this->_pager($count,$pagesize);
		$join = " try_tick t ON t.id = try_tk.tick_id ";
		$field= "t.orig_id,t.name,t.start_time,t.end_time,t.id";
		$list = $mod_tick->where($where)->field($field)->join($join)->order("get_time desc, tk_id desc")->limit($start.",".$pagesize)->select();
		$this->assign('list',$list);
		if($more == 'more'){
			$more_arr="";
			foreach($list as $key=>$r){
				$more_arr.="<li><a href='".U('wap/tick/show',array('id'=>$r['id']))."' title='".$r['name']."'><div class='w_yhj_n_img'>";
				$more_arr.="<img src='".orig_img($r['orig_id'])."' title='".$r['name']."' alt='".$r['name']."' /></div></a>";
				$more_arr.="<h2>".$r['name']."</h2><p>有效时间：".$r['end_time']."</p></li>";
			}
			echo $more_arr;
			exit;
		}
		//$this->assign('page_bar',$pager->fshow());
		//全部
		$all = $mod_tick->where("uid=$user[id]")->count();
		$this->assign('all',$all);
		//已过期
		$gq = $mod_tick->where("uid=$user[id] and t.end_time<Now()")->join($join)->count();
		$this->assign('gq',$gq);
		$this->assign('tab',$tab);
		$this->assign('pagesize',$pagesize);
		$this->assign('count',$count);
		$this->assign('title_h2','我的优惠劵');
		$this->_config_seo(array(
            'title' => $user['username'] . L('space_fans_title') . '-' . C('pin_site_name'),
        ));
		$this->display();
	}
	//我的评论
	public function comments(){
		!$this->visitor->is_login && $this->redirect('user/login');
		$user = $this->visitor->get();
		$t = $this->_get("t","trim");
		$more = $this->_get('more','trim');
		$p=$this->_get('p','intval') ? $this->_get('p','intval') : 1;
		$mod_comment = M("comment");
		$time = time()-24*3600*10;
		$pagesize = 8;
		$start=($p-1)*$pagesize;
		$where =" 1=1 and uid=$user[id] ";
		if($t=="r"){$where .=" and add_time>$time ";}
		$count = $mod_comment->where($where)->count();
		$pager = $this->_pager($count,$pagesize);
		$list = $mod_comment->where($where)->order("add_time desc,id desc")->limit($start.",".$pagesize)->select();
		foreach($list as $key=>$val){
			$arr=array();
			switch($val['xid']){
				case "1":$mod=M('item');$path="item";$url=U('wap/item/index',array('id'=>$val['itemid']));break;
				case "2":$mod=M("zr");$path="zr";$url=U('wap/zr/show',array('id'=>$val['itemid']));break;
				case "3":$mod=M("article");$path="article";$url=U('wap/article/show',array('id'=>$val['itemid']));break;
			}
			$arr = $mod->where("id=$val[itemid]")->field("title,img")->find();
			$list[$key]['title']=$arr['title'];
			if($val['xid']=='1'){$list[$key]['img']=$arr['img'];}else{$list[$key]['img']=attach($arr['img'],$path);}
			$list[$key]['url']=$url;
		}
		if($more == 'more'){
			$more_arr="";
			foreach($list as $key=>$r){
				$more_arr.="<li><div class='w_p1nr_1'><a href='javascript:;' title='删除' class='J_del w_scsc' data-url='".U('wap/user/del_comment',array('id'=>$r['id']))."'>删除</a>";
				$more_arr.="<span>".fdate($r['addtime'])."</span>".$r['info']."</div><div class='w_p1nr_2'>";
				$more_arr.="<a href='".$r['url']."' title='".$r['title']."'><div class='w_p1nr_2_img'>";
				$more_arr.="<img src='".$r['img']."' title='".$r['title']."' alt='".$r['title']."'/></div>".$r['title']."</a></div></li>";
			}
			echo $more_arr;
			exit;
		}
		$this->assign('list',$list);
		$this->assign('count',$count);
		$this->assign('pagesize',$pagesize);
		//$this->assign('page_bar',$pager->fshow());
		$this->assign('t',$t);
		$this->assign('page_seo',set_seo('我的评论'));
		$this->assign('title_h2','我的评论');
		$this->display();
	}
	//删除评论
	public function del_comment(){
		$user = $this->visitor->get();
		$id = $this->_get('id','intval');
		$item = M("comment")->where("id=$id")->field('xid,itemid')->find();
		$r=M("comment")->where("uid=$user[id] and  id=$id")->delete();
		//减少对应项目的评论数量
		if($r){
			switch($item['xid']){
				case "1":$mod = M("item");break;
				case "2":$mod = M("zr");break;
				case "3":$mod = M("article");break;
			}
			$mod->where("id=$item[itemid]")->setDec("comments");
			$this->ajaxReturn(1, '');
		}else{
			$this->ajaxReturn(0,'删除失败！');
		}
	}
	public function publish(){
		$user = $this->visitor->get();
		$t = $this->_get('t','trim');
		$more = $this->_get('more','trim');
		$p=$this->_get('p','intval') ? $this->_get('p','intval') : 1;
		!$t&&$t='gn';
		$item_mod=M('item');
		$article_mod = M('article');
		$zr_mod=M("zr");
		/*$num['gn']=$item_mod->where("o.ismy=0 and try_item.uid='$user[id]' and try_item.status=1")->join("try_item_orig o ON o.id=try_item.orig_id")->count();
		$num['ht']=$item_mod->where("o.ismy=1 and try_item.uid='$user[id]' and try_item.status=1")->join("try_item_orig o ON o.id=try_item.orig_id")->count();
		$num['best']=$item_mod->where(" isbest=1 and uid='$user[id]' and try_item.status=1")->count();
		$num['zr']=$zr_mod->where("uid='$user[id]' and status=1")->count();
		$num['sd']=$article_mod->where("uid=$user[id] and cate_id=10 and status=1")->count();
		$num['gl']=$article_mod->where("uid=$user[id] and cate_id in(select id from try_article_cate where pid=9 or id=9) and status=1")->count();*/
		//$num['icg']=$item_mod->where("status=2 and uid='$user[id]'")->count();
		//$num['ith']=$item_mod->where("status=0 and uid='$user[id]'")->count();
		//$num['zcg']=$zr_mod->where("status=2 and uid='$user[id]'")->count();
		//$num['zth']=$zr_mod->where("status=0 and uid='$user[id]'")->count();
		//$num['scg']=$article_mod->where("status=2 and uid=$user[id] and cate_id=10")->count();
		//$num['sth']=$article_mod->where("status=0 and uid=$user[id] and cate_id=10")->count();
		//$num['gcg']=$article_mod->where("uid=$user[id] and cate_id in(select id from try_article_cate where pid=9 or id=9) and status=2")->count();
		//$num['gth']=$article_mod->where("uid=$user[id] and cate_id in(select id from try_article_cate where pid=9 or id=9) and status=0")->count();
		$pagesize=8;
		$start=($p-1)*$pagesize;
		//$pager=$this->_pager($num[$t],$pagesize);
		switch($t){
			case "gn":
				$list = $item_mod->where("o.ismy=0 and try_item.uid='$user[id]' and try_item.status=1")->join("try_item_orig o ON o.id=try_item.orig_id")->field("try_item.id,try_item.title,try_item.img,try_item.intro,try_item.price,try_item.orig_id,try_item.comments,try_item.add_time")->limit($start.",".$pagesize)->select();
				$cg="wap-user-publish-t-icg";
				$dsth="wap-user-publish-t-ith";
				break;
			case "ht":
				$list = $item_mod->where("o.ismy=1 and try_item.uid='$user[id]' and try_item.status=1")->join("try_item_orig o ON o.id=try_item.orig_id")->field("try_item.id,try_item.title,try_item.img,try_item.intro,try_item.price,try_item.orig_id,try_item.comments,try_item.add_time")->limit($start.",".$pagesize)->select();
				$cg="wap-user-publish-t-icg";
				$dsth="wap-user-publish-t-ith";
				break;
			case "best":
				$list = $item_mod->where("isbest=1 and uid='$user[id]' and try_item.status=1")->field("try_item.id,try_item.title,try_item.img,try_item.intro,try_item.price,try_item.orig_id,try_item.comments,try_item.add_time")->limit($start.",".$pagesize)->select();
				$cg="wap-user-publish-t-icg";
				$dsth="wap-user-publish-t-ith";
				break;
			case "zr":
				$list = $zr_mod->where("uid='$user[id]' and status=1")->limit($start.",".$pagesize)->select();
				$cg="wap-user-publish-t-zcg";
				$dsth="wap-user-publish-t-zth";
				break;
			case "sd":
				$list=$article_mod->where("uid=$user[id] and cate_id=10 and status=1")->field("id,title,comments,intro,img,sc,add_time")->limit($start.",".$pagesize)->select();
				$cg="wap-user-publish-t-scg";
				$dsth="wap-user-publish-t-sth";
				break;
			case "gl":
				$list=$article_mod->where("uid=$user[id] and cate_id in(select id from try_article_cate where pid=9 or id=9) and status=1")->field("id,title,comments,intro,img,sc,add_time")->limit($start.",".$pagesize)->select();
				$cg="wap-user-publish-t-gcg";
				$dsth="wap-user-publish-t-gth";
				break;
			case "icg"://草稿
				$list=$item_mod->where("status=2 and uid='$user[id]'")->limit($start.",".$pagesize)->select();
				$cg="wap-user-publish-t-icg";
				$dsth="wap-user-publish-t-ith";
				break;
			case "ith"://退回商品
				$list=$item_mod->where("status=0 and uid='$user[id]'")->limit($start.",".$pagesize)->select();
				$cg="wap-user-publish-t-icg";
				$dsth="wap-user-publish-t-ith";
				break;
			case "zcg"://转让草稿
				$list=$zr_mod->where("status=2 and uid='$user[id]'")->limit($start.",".$pagesize)->select();
				$cg="wap-user-publish-t-zcg";
				$dsth="wap-user-publish-t-zth";
				break;
			case "zth"://退回转让
				$list=$zr_mod->where("status=0 and uid='$user[id]'")->limit($start.",".$pagesize)->select();
				$cg="wap-user-publish-t-zcg";
				$dsth="wap-user-publish-t-zth";
				break;
			case "scg"://晒单草稿
				$list=$article_mod->where("status=2 and uid=$user[id] and cate_id=10")->field("id,title,comments,intro,img,add_time,sc")->limit($start.",".$pagesize)->select();
				$cg="wap-user-publish-t-scg";
				$dsth="wap-user-publish-t-sth";
				break;
			case "sth"://晒单退回
				$list=$article_mod->where("status=0 and uid=$user[id] and cate_id=10")->field("id,title,comments,intro,img,add_time,sc")->limit($start.",".$pagesize)->select();
				$cg="wap-user-publish-t-scg";
				$dsth="wap-user-publish-t-sth";
				break;
			case "gcg"://攻略草稿
				$list=$article_mod->where("uid=$user[id] and cate_id in(select id from try_article_cate where pid=9 or id=9) and status=2")->limit($start.",".$pagesize)->select();
				$cg="wap-user-publish-t-gcg";
				$dsth="wap-user-publish-t-gth";
				break;
			case "gth"://攻略退回
				$list=$article_mod->where("uid=$user[id] and cate_id in(select id from try_article_cate where pid=9 or id=9) and status=0")->limit($start.",".$pagesize)->select();
				$cg="wap-user-publish-t-gcg";
				$dsth="wap-user-publish-t-gth";
				break;
		}
		if($more == 'more'){
			$more_arr="";
			if($t == 'gn' || $t == 'ht' || $t == 'best' || $t == 'icg' || $t == 'ith'){
				foreach($list as $key=>$r){
					$url=($t == 'icg' || $t == 'ith') ? "edit" : "index";
					$more_arr.="<li><a href='".U('wap/item/'.$url,array('id'=>$r['id']))."' title='".$r['title']."'><div class='w_zk_img'>";
					$more_arr.="<img src='".attach($r['img'],'item')."' title='$r[title]' alt='$r[title]' /></div>";
					$more_arr.="<address><span>".fdate($r['add_time'])."</span>".getly($r['orig_id'])."</address><h2>".$r['title']."</h2>";
					$more_arr.="<div class='w_jg'><span><i class='icons icon_comment'></i>".$r['comments']."</span></div></a></li>";
				}
				echo $more_arr;
			    exit;
			}
			if($t == 'sd' || $t == 'scg' || $t == 'sth' || $t == 'gl' || $t == 'gcg' || $t == 'gth'){
				foreach($list as $key=>$r){
					if($t == 'scg' || $t == 'sth' || $t == 'gcg' || $t == 'gth'){
						$bianji="|<a href='".U('wap/article/edit',array('t'=>$t,'id'=>$r['id']))."' title='编辑'>编辑</a>";
					}
					$more_arr.="<li><a href='".U('wap/article/show',array('id'=>$r['id']))."' title='".$r['title']."'><div class='w_zk_img'>";
					$more_arr.="<img src='".attach($r['img'],'article')."' title='$r[title]' alt='$r[title]' /></div>";
					$more_arr.="<address><span>".fdate($r['add_time'])."</span>".$r['sc']."</address><h2>".$r['title']."</h2></a>";
					$more_arr.="<div class='w_jg'><em data-url='".U('wap/user/del_article',array('id'=>$r['id']))."' class='J_del_art'>";
					$more_arr.="删除</em>".$bianji."<span><i class='icons icon_comment'></i>".$r['comments']."</span></div></li>";
				}
				echo $more_arr;
			    exit;
			}
			if($t == 'zr' || $t == 'zcg' || $t == 'zth'){
				foreach($list as $key=>$r){
					$more_arr.="<li><a href='".U('wap/zr/show',array('id'=>$r['id']))."' title='".$r['title']."'><div class='w_zk_img'>";
					$more_arr.="<img src='".attach($r['img'],'zr')."' title='$r[title]' alt='$r[title]' /></div>";
					$more_arr.="<address><span>".fdate($r['add_time'])."</span>".$r['title']."</address><h2>".$r['intro']."</h2></a>";
					$more_arr.="<div class='w_jg'><em>￥".$r['price']."</em><div class='J_del_zr w_gbjy' data-id='".$r['id']."' style='margin-left:10px;'>";
					$more_arr.="删除</div><div class='w_gbjy' onClick='window.location=\"/wap-zr-edit-id-".$r['id']."\"'>编辑</div></div></li>";
				}
				echo $more_arr;
			    exit;
			}
			
		}
		$this->assign('list',$list);//print_r($list);exit;
		//$this->assign('page_bar',$pager->fshow());
		$this->assign('t',$t);
		$this->assign('num',$num);
		$this->assign('cg',$cg);
		$this->assign('dsth',$dsth);
		$this->assign('page_seo',set_seo('我的文章'));
		$this->assign('title_h2','我的文章');
		$this->display();
	}
	//我的收藏
	public function likes(){		
		$user = $this->visitor->get();
		$t = $this->_get('t','trim');
		$more = $this->_get('more','trim');
		$p=$this->_get('p','intval') ? $this->_get('p','intval') : 1;
		!$t&&$t='gn';
		$mod = M("likes");
		$num['gn']=$mod->where("try_likes.xid=1 and o.ismy=0")->join("try_item i on i.id=try_likes.itemid")->join("try_item_orig o on o.id=i.orig_id")->count();
		$num['ht']=$mod->where("try_likes.xid=1 and o.ismy=1")->join("try_item i on i.id=try_likes.itemid")->join("try_item_orig o on o.id=i.orig_id")->count();
		$num['best']=$mod->where("try_likes.xid=1 and i.isbest=1")->join("try_item i on i.id=try_likes.itemid")->count();
		$num['sd']=$mod->where("try_likes.xid=3 and a.cate_id=10")->join("try_article a on a.id=try_likes.itemid")->count();
		$num['gl']=$mod->where("try_likes.xid=3 and a.cate_id in(select id from try_article_cate where pid=9 or id=9)")->join("try_article a on a.id=try_likes.itemid")->count();
		$num['zr']=$mod->where("try_likes.xid=2")->count();
		$pagesize=8;
		$start=($p-1)*$pagesize;
		//$pager = $this->_pager($num[$t],$pagesize);
		switch($t){
			case "gn":
				$list = $mod->where("try_likes.xid=1 and o.ismy=0")->join("try_item i on i.id=try_likes.itemid")->join("try_item_orig o on o.id=i.orig_id")->field("i.id,i.title,i.img,i.comments,i.intro")->limit($start.",".$pagesize)->select();
				foreach($list as $key=>$val){
					$list[$key]['url'] = U('wap/item/index',array("id"=>$val['id']));
				}
				$xid = 1;
				break;
			case "ht":
				$list = $mod->where("try_likes.xid=1 and o.ismy=1")->join("try_item i on i.id=try_likes.itemid")->join("try_item_orig o on o.id=i.orig_id")->field("i.id,i.title,i.img,i.comments,i.intro")->limit($start.",".$pagesize)->select();
				foreach($list as $key=>$val){
					$list[$key]['url'] = U('wap/item/index',array("id"=>$val['id']));
				}
				$xid = 1;
				break;
			case "best":
				$list = $mod->where("try_likes.xid=1 and i.isbest=1")->join("try_item i on i.id=try_likes.itemid")->field("i.id,i.title,i.img,i.comments,i.intro")->limit($start.",".$pagesize)->select();
				foreach($list as $key=>$val){
					$list[$key]['url'] = U('wap/item/index',array("id"=>$val['id']));
				}
				$xid = 1;
				break;
			case "sd":
				$list = $mod->where("try_likes.xid=3 and a.cate_id=10")->join("try_article a on a.id=try_likes.itemid")->field("a.id,a.title,a.img,a.comments,a.intro")->limit($start.",".$pagesize)->select();
				foreach($list as $key=>$val){
					$list[$key]['img']=attach($val['img'],'article');
					$list[$key]['url'] = U('wap/article/show',array("id"=>$val['id']));
				}
				$xid = 3;
				break;
			case "gl":
				$list = $mod->where("try_likes.xid=3 and a.cate_id in(select id from try_article_cate where pid=9 or id=9)")->join("try_article a on a.id=try_likes.itemid")->field("a.id,a.title,a.img,a.comments,a.intro")->limit($start.",".$pagesize)->select();
				foreach($list as $key=>$val){
					$list[$key]['img']=attach($val['img'],'article');
					$list[$key]['url'] = U('wap/article/show',array("id"=>$val['id']));
				}
				$xid = 3;
				break;
			case "zr":
				$list = $mod->where("try_likes.xid=2")->join("try_zr z on z.id=try_likes.itemid")->field("z.id,z.title,z.img,z.comments,z.intro")->limit($start.",".$pagesize)->select();
				foreach($list as $key=>$val){
					$list[$key]['img']=attach($val['img'],'zr');
					$list[$key]['url'] = U('wap/zr/show',array("id"=>$val['id']));
				}
				$xid = 2;
				break;
		}
		
		if($more == 'more'){
			$more_arr="";
			foreach($list as $key=>$r){
				$more_arr.="<li><a href='".$r['url']."' title='".$r['title']."'><div class='w_zk_img'>";
				$more_arr.="<img src='".$r['img']."' title='$r[title]' alt='$r[title]' /></div>";
				$more_arr.="<address><span>".fdate($r['addtime'])."</span>".msubstr($r['title'],0,26,false)."</address><h2>".msubstr($r['intro'],0,26,false)."</h2>";
				$more_arr.="<div class='w_jg'><em data-url='".U('wap/user/del_likes',array('itemid'=>$r['id'],'xid'=>$xid,'uid'=>$user['id']))."' class='J_like_del'>";
				$more_arr.="删除</em><span><i class='icons icon_comment'></i>".$r['comments']."</span></div></a></li>";
			}
			echo $more_arr;
			exit;
		}
		$this->assign('list',$list);
		//$this->assign('page_bar',$pager->fshow());
		$this->assign('num',$num);
		$this->assign('pagesize',$pagesize);
		$this->assign('xid',$xid);
		$this->assign('cur',$t);
		$this->assign('page_seo',set_seo('我的收藏'));
		$this->assign('title_h2','我的收藏');
		$this->display();
	}
	//删除收藏
	public function del_likes(){
		$itemid = $this->_get('itemid','intval');
		$xid = $this->_get('xid','intval');
		$uid = $this->_get('uid','intval');
		$mod = M("likes");
		//查找是否已收藏
		$islike=$mod->where("uid=$uid and xid=$xid and itemid=$itemid")->find();
		if($islike){
			$r=$mod->where("uid=$uid and xid=$xid and itemid=$itemid")->delete();
			if($r){
				$i_mod = get_mod($xid);
				$i_mod->where("id=$itemid")->setDec("likes");
				$this->ajaxReturn(1, '取消收藏成功');
			}else{
				$this->ajaxReturn(0,'删除失败！');
			}
		}else{
			$this->ajaxReturn(0,'删除失败！');
		}
	}
	//我的分享
	public function share(){
		$user = $this->visitor->get();
		$t = $this->_get('t','trim');
		$more = $this->_get('more','trim');
		$p=$this->_get('p','intval') ? $this->_get('p','intval') : 1;
		!$t&&$t='gn';
		$mod=M("share");
		$num['gn']=$mod->where("o.ismy=0 and try_share.uid='$user[id]' and try_share.xid=1")->join(array("try_item i ON i.id=try_share.item_id ","try_item_orig o ON o.id=i.orig_id"))->count();
		$num['ht']=$mod->where("o.ismy=1 and try_share.uid='$user[id]' and try_share.xid=1")->join(array("try_item i ON i.id=try_share.item_id ","try_item_orig o ON o.id=i.orig_id"))->count();
		$num['best']=$mod->where(" i.isbest=1 and try_share.uid='$user[id]' and try_share.xid=1")->join("try_item i ON i.id=try_share.item_id")->count();
		$num['zr']=$mod->where("try_share.uid='$user[id]' and try_share.xid=2")->join("try_zr z ON z.id=try_share.item_id")->count();		
		$num['sd']=$mod->where("try_share.uid=$user[id] and try_share.xid=3 and a.cate_id=10")->join("try_article a ON a.id=try_share.item_id")->count();
		$num['gl']=$mod->where("try_share.uid=$user[id] and try_share.xid=3 and a.cate_id in(select id from try_article_cate where pid=9 or id=9)")->join("try_article a ON a.id=try_share.item_id")->count();
		$pagesize=8;
		$start=($p-1)*$pagesize;
		//$pager=$this->_pager($num[$t],$pagesize);
		switch($t){
			case "gn":
				$list = $mod->field("i.*,try_share.dm")->where("o.ismy=0 and try_share.uid='$user[id]' and try_share.xid=1")->join(array("try_item i ON i.id=try_share.item_id ","try_item_orig o ON o.id=i.orig_id"))->limit($start.",".$pagesize)->order("i.id desc")->select();
				break;
			case "ht":
				$list = $mod->field("i.*,try_share.dm")->where("o.ismy=1 and try_share.uid='$user[id]' and try_share.xid=1")->join(array("try_item i ON i.id=try_share.item_id ","try_item_orig o ON o.id=i.orig_id"))->limit($start.",".$pagesize)->order("i.id desc")->select();
				break;
			case "best":
				$list = $mod->field("i.*,try_share.dm")->where(" i.isbest=1 and try_share.uid='$user[id]' and try_share.xid=1")->join("try_item i ON i.id=try_share.item_id")->limit($start.",".$pagesize)->order("i.id desc")->select();
				break;
			case "zr":
				$list = $mod->field("z.*,try_share.dm")->where("try_share.uid=$user[id] and try_share.xid=2")->join("try_zr z ON z.id=try_share.item_id")->limit($start.",".$pagesize)->order("z.id desc")->select();
				break;
			case "sd":
				$list=$mod->field("a.*,try_share.dm")->where("try_share.uid=$user[id] and try_share.xid=3 and a.cate_id=10")->join("try_article a ON a.id=try_share.item_id")->limit($start.",".$pagesize)->order("a.id desc")->select();
				break;
			case "gl":
				$list=$mod->field("a.*,try_share.dm")->where("try_share.uid=$user[id] and try_share.xid=3 and a.cate_id in(select id from try_article_cate where pid=9 or id=9)")->join("try_article a ON a.id=try_share.item_id")->limit($start.",".$pagesize)->order("a.id desc")->select();
				break;
		}
		if($more == 'more'){
			$more_arr="";
			if($t == 'gn' || $t == 'ht' || $t == 'best'){
				foreach($list as $key=>$r){
					$more_arr.="<li><a href='".U('wap/item/index',array('id'=>$r['id']))."' title='".$r['title']."'><div class='w_zk_img'>";
					$more_arr.="<img src='".attach($r['img'],'item')."' title='$r[title]' alt='$r[title]' /></div>";
					$more_arr.="<address><span>".fdate($r['add_time'])."</span>".getly($r['orig_id'])."</address><h2>".$r['title']."</h2></a>";
					$more_arr.="<div class='w_jg'><em data-dm='".$r['dm']."' class='J_del_fx'>";
					$more_arr.="删除</em><span><i class='icons icon_comment'></i>".$r['comments']."</span></div></li>";
				}
				echo $more_arr;
			    exit;
			}
			if($t == 'sd' || $t == 'gl'){
				foreach($list as $key=>$r){
					$more_arr.="<li><a href='".U('wap/article/show',array('id'=>$r['id']))."' title='".$r['title']."'><div class='w_zk_img'>";
					$more_arr.="<img src='".attach($r['img'],'article')."' title='$r[title]' alt='$r[title]' /></div>";
					$more_arr.="<address><span>".fdate($r['add_time'])."</span>".$r['sc']."</address><h2>".$r['title']."</h2></a>";
					$more_arr.="<div class='w_jg'><em data-dm='".$r['dm']."' class='J_del_fx'>";
					$more_arr.="删除</em><span><i class='icons icon_comment'></i>".$r['comments']."</span></div></li>";
				}
				echo $more_arr;
			    exit;
			}
			if($t == 'zr'){
				foreach($list as $key=>$r){
					$more_arr.="<li><a href='".U('wap/zr/show',array('id'=>$r['id']))."' title='".$r['title']."'><div class='w_zk_img'>";
					$more_arr.="<img src='".attach($r['img'],'zr')."' title='$r[title]' alt='$r[title]' /></div>";
					$more_arr.="<address><span>".fdate($r['add_time'])."</span>".$r['title']."</address><h2>".$r['intro']."</h2></a>";
					$more_arr.="<div class='w_jg'><em>￥".$r['price']."</em><div class='J_del_fx w_gbjy' data-dm='".$r['dm']."' style='margin-left:10px;'>";
					$more_arr.="删除</div></div></li>";
				}
				echo $more_arr;
			    exit;
			}
			
		}
		
		$this->assign('list',$list);
		//$this->assign('page_bar',$pager->fshow());
		$this->assign('t',$t);
		$this->assign('num',$num);
		$this->assign('pagesize',$pagesize);
		$this->assign('page_seo',set_seo('我的分享'));
		$this->assign('title_h2','我的分享');
		$this->display();
	}
	//删除文章
	public function del_article(){
		!$this->visitor->is_login && $this->redirect('user/login');
		$user = $this->visitor->get();
		$id = $this->_get('id','intval');
		!id&&$this->ajaxReturn(0,'无效的文章信息');
		$mod=M("article");
		$r = $mod->where("uid=$user[id] and id=$id")->delete();
		if($r){
			//删除评论		
			M('comment')->where("uid=$user[id] and itemid=$id and xid=3")->delete();
			//删除收藏信息
			M("likes")->where("uid=$user[id] and itemid=$id and xid=3")->delete();
			$this->ajaxReturn(1,'成功删除分享信息');
		}else{
			$this->ajaxReturn(0,'删除分享信息失败');
		}
	}
	//删除转让
	public function del_zr(){
		$user = $this->visitor->get();
		$id = $this->_get('id','intval');
		!id&&$this->ajaxReturn(0,'无效的转让商品信息');
		$mod=M("zr");
		$r = $mod->where("uid=$user[id] and id=$id")->delete();
		if($r){
			//删除评论		
			M('comment')->where("uid=$user[id] and itemid=$id and xid=2")->delete();
			//删除收藏信息
			M("likes")->where("uid=$user[id] and itemid=$id and xid=2")->delete();
			$this->ajaxReturn(1,'成功删除闲置转让信息');
		}else{
			$this->ajaxReturn(0,'删除闲置转让信息失败');
		}
	}
	//删除分享
	public function del_fx(){
		$user = $this->visitor->get();
		$dm = $this->_get('dm','trim');
		!$dm&&$this->ajaxReturn(0,'无效的分享信息');
		$mod=M("share");
		$r = $mod->where("uid=$user[id] and dm='$dm'")->delete();
		if($r){
			$this->ajaxReturn(1,'成功删除分享信息');
		}else{
			$this->ajaxReturn(0,'删除分享信息失败');
		}
	}
	//签到
	public function sign(){
		$user = $this->visitor->get();
		$mod = M("user");
		//查询是否已签到
		$user = $mod->where("id=$user[id]")->find();
		$time = time();
		$signtime=$user['sign_date'];
		$ds=intval(($time-$signtime)/86400); //60s*60min*24h 
		$data['id']=$user['id'];
		$data['sign_date']=$time;
		if($ds>1){//如果大于1，则签到清零+1,积分+5
			$data['score']=$user['score']+5;
			$data['sign_num']=1;
			$data['all_sign']=$user['all_sign']+1;
			$mod->save($data);
			//积分日志
			set_score_log($user,'sign',5,'','',5);
			$this->ajaxReturn(1,'您已连续签到1天，成功获取5个积分！');
		}elseif($ds==0){//当天以签到
			$this->ajaxReturn(0,'您今天已签到！');
		}else{//否则在原基础上+1
			$max_score = $user['sign_num']+5;
			$data['sign_num']=$user['sign_num']+1;
			$data['all_sign']=$user['all_sign']+1;			
			if($max_score>15){$max_score=15;}
			$data['score']=$user['score']+$max_score;
			$mod->save($data);
			//积分日志
			set_score_log($user,'sign',$max_score,'','',$max_score);
			$this->ajaxReturn(1,'您已连续签到'.$user['sign_num'].'天，成功获取'.$max_score.'个积分！');
		}
	}
	//用户等级
	public function grade(){
		$t = $this->_get('t','trim');
		$more = $this->_get('more','trim');
		$p=$this->_get('p','intval') ? $this->_get('p','intval') : 1;
		!$t&&$t='exp';
		$pagesize=10;
		$start=($p-1)*$pagesize;
		//经验值、等级
		$user = $this->visitor->get();
		$grade_mod = M("grade");
		$log_mod = M("score_log");
		$lang=L('action');
		$user['grade'] = $grade_mod->where("min<=$user[exp] and max>=$user[exp]")->getField("grade");
		if($t=='score'){
			//积分变更
			$count=$log_mod->where("uid=$user[id] and score<>0")->count();
			$jf_list = $log_mod->where("uid=$user[id] and score<>0")->order("add_time desc")->limit($start.",".$pagesize)->select();
			if($more == 'more'){
			    $more_arr="";
				foreach($jf_list as $key=>$r){
					$more_arr.="<dl class='w_lf_2n'><dd>".$lang[$r['action']]."</dd><dd>".$r['score']."</dd><dd>".date("Y-m-d H:i:s",$r['add_time'])."</dd></dl>";
				}
				echo $more_arr;
			    exit;
			}
			$this->assign('jf_list',$jf_list);
		}elseif($t=='exp'){
			//查找下一等级
			$user['next_grade'] = $grade_mod->where("grade=$user[grade]+1")->find();
			$user['w']=($user['exp']*100)/$user['next_grade']['max'];
			$user['lft']=$user['next_grade']['max']-$user['exp'];	
			//经验变更
			$count=$log_mod->where("uid=$user[id] and exp<>0")->count();
			$jy_list = $log_mod->where("uid=$user[id] and exp<>0")->order("add_time desc")->limit($start.",".$pagesize)->select();
			if($more == 'more'){
			    $more_arr="";
				foreach($jy_list as $key=>$r){
					$more_arr.="<dl class='w_lf_2n'><dd>".$lang[$r['action']]."</dd><dd>".$r['exp']."</dd><dd>".date("Y-m-d H:i:s",$r['add_time'])."</dd></dl>";
				}
				echo $more_arr;
			    exit;
			}
			$this->assign('jy_list',$jy_list);
		}elseif($t=='offer'){		
			//贡献值变更
			$count=$log_mod->where("uid=$user[id] and offer<>0")->count();
			$of_list = $log_mod->where("uid=$user[id] and offer<>0")->order("add_time desc")->limit($start.",".$pagesize)->select();
			if($more == 'more'){
			    $more_arr="";
				foreach($of_list as $key=>$r){
					$more_arr.="<dl class='w_lf_2n'><dd>".$lang[$r['action']]."</dd><dd>".$r['offer']."</dd><dd>".date("Y-m-d H:i:s",$r['add_time'])."</dd></dl>";
				}
				echo $more_arr;
			    exit;
			}
			$this->assign("of_list",$of_list);
		}elseif($t=='coin'){
			//金币变更
			$count=$log_mod->where("uid=$user[id] and coin<>0")->count();
			$jb_list = $log_mod->where("uid=$user[id] and coin<>0")->order("add_time desc")->limit($start.",".$pagesize)->select();
			if($more == 'more'){
			    $more_arr="";
				foreach($jb_list as $key=>$r){
					$more_arr.="<dl class='w_lf_2n'><dd>".$lang[$r['action']]."</dd><dd>".$r['coin']."</dd><dd>".date("Y-m-d H:i:s",$r['add_time'])."</dd></dl>";
				}
				echo $more_arr;
			    exit;
			}
			$this->assign("jb_list",$jb_list);
		}elseif($t=='xz'){
			//勋章
			$xz['share_num'] = M("score_log")->where("action='share' and uid=$user[id]")->count();//分享
			$xz['bao_num'] = M("item")->where("uid=$user[id] and status=1")->count();//爆料达人
			$xz['sign_num'] = M("score_log")->where("action='sign' and uid=$user[id]")->count();//签到
			$xz['gl_num'] = M("article")->where("uid=$user[id] and status=1 and cate_id in(select id from try_article_cate where pid=9 or id=9)")->count();//攻略
			$xz['sd_num'] = M("article")->where("uid=$user[id] and status=1 and cate_id=10")->count();//晒单
			$xz['cm_num'] = M("comment")->where("uid=$user[id] and status=1")->count();
			$this->assign('xz',$xz);
		}
		
		$this->assign('t',$t);
		$this->assign('pagesize',$pagesize);
		$this->assign('count',$count);
		$this->assign('user',$user);
		$this->assign("lang",$lang);
		$this->assign('page_seo',set_seo('我的等级'));
		$this->assign('title_h2','用户等级');
		$this->display();
	}
}