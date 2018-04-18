<?php
class indexAction extends frontendAction {
    
     public function index1(){
     	$this->display();
     }
     public function details(){
     	$type = $this->_get('type','trim');
		$dss = $this->_get('dss','trim');
        //热门
		$mod=M("item");
		if($type==""||$type=='isnice'){
			$where=" and isnice=1 ";
			$order =" add_time desc";
			$tab = "isnice";
		}else{
			$where=" and isbao=1 ";
			$order =" add_time desc";
			$tab = "isbao";
		}	
		$time=time();		
		$pagesize=18;
		$count = 1000; //$mod->where("status=1 and add_time<$time ".$where)->count();
		$pager = $this->_pager($count,$pagesize);
		if($tab =="isnice"){
     	$list = $mod->field('id,title,img,zan,hits,content')->where("status=1 and add_time<$time and ds_time < $time ".$where)->limit($pager->firstRow.",".$pager->listRows)->order($order)->select();
     }

     foreach($list as $key=>$val){

     	$list[$key]['content'] = mb_substr(trim(strip_tags($list[$key]['content'])),0,100,"utf-8");
				
		$list[$key]['zan'] = rand(1,5);//$list[$key]['zan']   +intval($list[$key]['hits'] /10);
		$list[$key]['link'] = U('item/index',array('id'=>$list[$key]['id']));
			}
     $this->assign('item_list',json_encode($list));

     	$this->display();
     }
    public function index() {
		function is_mobile(){
			$user_agent = $_SERVER['HTTP_USER_AGENT'];
			$mobile_agents = Array("240x320","acer","acoon","acs-","abacho","ahong","airness","alcatel","amoi","android","anywhereyougo.com","applewebkit/525","applewebkit/532","asus","audio","au-mic","avantogo","becker","benq","bilbo","bird","blackberry","blazer","bleu","cdm-","compal","coolpad","danger","dbtel","dopod","elaine","eric","etouch","fly ","fly_","fly-","go.web","goodaccess","gradiente","grundig","haier","hedy","hitachi","htc","huawei","hutchison","inno","ipad","ipaq","ipod","jbrowser","kddi","kgt","kwc","lenovo","lg ","lg2","lg3","lg4","lg5","lg7","lg8","lg9","lg-","lge-","lge9","longcos","maemo","mercator","meridian","micromax","midp","mini","mitsu","mmm","mmp","mobi","mot-","moto","nec-","netfront","newgen","nexian","nf-browser","nintendo","nitro","nokia","nook","novarra","obigo","palm","panasonic","pantech","philips","phone","pg-","playstation","pocket","pt-","qc-","qtek","rover","sagem","sama","samu","sanyo","samsung","sch-","scooter","sec-","sendo","sgh-","sharp","siemens","sie-","softbank","sony","spice","sprint","spv","symbian","tablet","talkabout","tcl-","teleca","telit","tianyu","tim-","toshiba","tsm","up.browser","utec","utstar","verykool","virgin","vk-","voda","voxtel","vx","wap","wellco","wig browser","wii","windows ce","wireless","xda","xde","zte");
			$is_mobile = false;
			foreach ($mobile_agents as $device) {
				if (stristr($user_agent, $device)) {
					$is_mobile = true;
					break;
				}
			}
			return $is_mobile;
		}
		
		if(is_mobile()){ //跳转至wap分组
			header("Location: /index.php?g=wap");
		}
		
		
		$type = $this->_get('type','trim');
		$dss = $this->_get('dss','trim');
        //热门
		$mod=M("item");
		if($type==""||$type=='isnice'){
			$where=" and isnice=1 ";
			$order =" add_time desc";
			$tab = "isnice";
		}else{
			$where=" and isbao=1 ";
			$order =" add_time desc";
			$tab = "isbao";
		}	
		$time=time();		
		$pagesize=18;
		$count = 1000; //$mod->where("status=1 and add_time<$time ".$where)->count();
		$pager = $this->_pager($count,$pagesize);
		if($tab =="isnice"){
		$list = $mod->field('title,img')->where("status=1 and add_time<$time and ds_time < $time ".$where)->limit($pager->firstRow.",".$pager->listRows)->order($order)->select();
		}
		else{
		$list = M("item_diu")->where("status=1 and add_time<$time and ds_time < $time")->limit($pager->firstRow.",".$pager->listRows)->order($order)->select();	
		}
		/*
        foreach ($list as $key=>$val) {
			if($val["sh_time"]>$val["ds_time"]){
				$list[$key]['add_time']=$val["sh_time"];
			}else{
				$list[$key]['add_time']=$val["ds_time"];
			} 
        }	
		*/
		/*echo "<pre>";
		var_dump($list);*/
		foreach($list as $key=>$val){
				
		$list[$key]['zan'] = $list[$key]['zan']   +intval($list[$key]['hits'] /10);
			}
		$this->assign('item_list',json_encode($list));
		$this->assign('pagebar',$pager->fshow());
		$p = $this->_get("p",'intval');
		if($p<1){$p=1;}
		$this->assign('p',$p);
		//每天排名
		$time_s = strtotime(date('Y-m-d'),time());
		$time_m_s = strtotime(date('Y-m-1'),time());
		$time_m_e = time();
		$time_e =$time_s+24*60*60;
		$pm1 = M()->query("select distinct uid as id,uname,sum(score) as num from try_score_log where add_time>$time_s and add_time<$time_e group by uname order by num desc,uid asc limit 4");
		$pmm = M()->query("select distinct uid as id,uname,sum(score) as num from try_score_log where add_time>$time_m_s and add_time<$time_m_e group by uname order by num desc,uid asc limit 4");
		//全部排名
		$pma = M("user")->field("id,username,score")->order("score desc,id asc")->limit(4)->select();		
		//查询是否关注
		if($this->visitor->is_login){
			$user = $this->visitor->get();
			$follow_list = M("user_follow")->where("uid=$user[id]")->select();
			foreach($pm1 as $key=>$val){
				foreach($follow_list as $k=>$v){
					if($val['uid']==$v['follow_uid']){
						$pm1[$key]['follow']=1;
					}
				}
			}
			foreach($pmm as $key=>$val){
				foreach($follow_list as $k=>$v){
					if($val['uid']==$v['follow_uid']){
						$pmm[$key]['follow']=1;
					}
				}
			}
			foreach($pma as $key=>$val){
				foreach($follow_list as $k=>$v){
					if($val['id']==$v['follow_uid']){
						$pma[$key]['follow']=1;
					}
				}
			}
		}

		$this->assign('pm1',$pm1);
		$this->assign('pmm',$pmm);
		$this->assign('pma',$pma);
		//表现形式

        $dss =$this->_get("dss","trim");
	//	$dss = ($dss=="")?$_SESSION['dss']:$dss;
		$dss = ($dss=="")?"lb":$dss;
		$_SESSION['dss']=$dss;
		$this->assign("dss",$dss);
		$this->assign("tab",$tab);
		$this->assign("lb_url",U('index/index',array('type'=>$type,'tab'=>$tab,'dss'=>'lb')));
		$this->assign("cc_url",U('index/index',array('type'=>$type,'tab'=>$tab,'dss'=>'cc')));
        $this->_config_seo();
		$this->assign("bcid",0);
		//热门活动
		$hd = M("hd")->limit(8)->order("order_s asc,id desc")->select();
		$thd = M("item")->where('istop = 1')->limit(8)->order("id desc")->select();
		$this->assign('hd',$hd);
		$this->assign('thd',$thd);
		$time = time();
		$time_hour = $time - 3600;
		$time_day = $time - 86400;

		//小时榜和24小时榜
		$hour_list=M()->query("SELECT id,title,img,price from try_item  WHERE add_time between $time_hour and $time ORDER BY hits desc,add_time desc LIMIT 9");
		$day_list=M()->query("SELECT id,title,img,price from try_item  WHERE add_time between $time_day and $time ORDER BY hits desc,add_time desc LIMIT 9");
		$this->assign('hour_list',$hour_list);
		$this->assign('day_list',$day_list);

        $this->display();
    }
}
