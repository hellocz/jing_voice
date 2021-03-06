<?php

/**
 * 搜索页面
 */
class searchAction extends frontendAction {

    public function index() {
        $q = $this->_get('q', 'trim');
        $t = $this->_get('t', 'trim', 'item');
		$tp = $this->_get('tp', 'trim');
		$isbest = $this->_get('isbest', 'trim');
        $action = '_search_' . $t;
        $this->$action($q);
        //搜索记录
        $search_history = (array) cookie('search_history');
        if (!$search_history) {
            $q && $search_history = array(array('q' => urlencode($q), 't' => $t));
        } else {
            foreach ($search_history as $key => $val) {
                $search_history[$key] = $val = (array) $val;
                if ($val['q'] == urlencode($q) && $val['t'] == $t) {
                    unset($search_history[$key]);
                }
            }
            $q && array_unshift($search_history, array('q' => urlencode($q), 't' => $t));
            $search_history = array_slice($search_history, 0, 10, true);
        }
        cookie('search_history', $search_history);
        if($q){
            $ss_cate = M('item_cate')->field('id,name')->where("name like '%".$q."%' or seo_title like '%".$q."%' or seo_keys like '%".$q."%'")->select();;
            print_r($ss_cate);
            $this->assign('ss_cate', $ss_cate);
        }	
        $this->assign('q', $q);
        $this->assign('t', $t);
		
		$this->assign('tp', $tp);
		$this->assign('isbest', $isbest);
        $this->assign('search_history', $search_history);
		$this->assign('strpos1',$q);
        $this->display($t);
    }

    public function clear_history() {
        cookie('search_history', NULL);
        $this->redirect('search/index');
    }

    /**
     * 搜宝贝
     */
    private function _search_item($q) {
        $sort = $this->_get('sort', 'trim', 'new'); //排序
		$isbest = $this->_get('isbest', 'trim');
		$tp = $this->_get('tp', 'trim');
        $day = $this->_get('day', 'trim');
        $day_w=$day > 0 ? (time()-($day*86400)) : 0;
        $q_original = $q;
        if ($q) {
            if($tp == ''){
				$where = array('status' => '1');
			}
			if($isbest == 1){
				$where = array('isbest' => '1');
			}
            $item_list=M('tag')->where(array('id'=>array('gt', 0)))->select();
			
			
            //$insteadStr ="";
            foreach ($item_list as $val) {
              if(strpos($q, $val['name'])!== false){
              //  $insteadStr= $val['name'];
                $q = str_replace($val['name'], " ".$val['name']." ", $q);
              }
            }
            $q = trim($q);
            while (strpos($q, "  ")!==false){
                $q = str_replace("  ", " ", $q);
            }
         //   if($insteadStr != ""){
         //        $strArray = split($insteadStr,$q);
         //      $q=trim(join(" ",$strArray))." ".$insteadStr;
         //   }

          //  $strArray=preg_split('/(?<!^)(?!$)/u', $q);
           // $q=join(" ",$strArray);
           $q_list=explode(" ",$q);
            $q_info="";
            foreach($q_list as $key=>$r){
                $or=$key == 0 ? "" : " or ";
                $q_info.="$or title like '%".$r."%'  ";//or intro like '%".$r."%'
            }

            $where['intro'] = array('like', '%' . $q . '%');
			$where['_string'] =" add_time > ".$day_w."";
            switch ($sort) {
                case 'hot':
                    $order = 'likes DESC,zan DESC,hits DESC,id DESC';
                    break;
                case 'new':
                    $order = 'add_time DESC';
                    break;
            }
            //没使用瀑布流 可以注释掉
            IS_AJAX && $this->wall_ajax($where, $order);
            $q_info1="";
            foreach($q_list as $key=>$r){
                $or=$key == 0 ? "" : " or ";
                $q_info1.="$or i.title like '%".$r."%'  ";/*or i.intro like '%".$r."%'*/
            }
            $q_infos=array();
            foreach($q_list as $key=>$r){
                $q_infos[]="%".$r."%";/*or i.intro like '%".$r."%'*/
            }
            $where=array();
            $where['i.title'] = array('like',$q_infos,'AND');
            $where['i.content'] = array('like',$q_infos,'AND');
            $where['i.go_link'] = array('like',$q_infos,'AND');
            $where['_logic'] = 'or';
            $map['_complex'] = $where;
            $map['i.status'] = 1;
            $map['add_time'] = array('gt',$day_w);
            $where1=array();
            $where1['title'] = array('like',$q_infos,'AND');
            $where1['info'] = array('like',$q_infos,'AND');
            $where1['_logic'] = 'or';
            $map1['_complex'] = $where1;
            $map1['status'] = 1;
            $map1['add_time'] = array('gt',$day_w);
            $this->waterfall_tp($map, $order,$tp,$isbest,$map1);
			
        }
        $this->assign('day', $day);
        $this->assign('sort', $sort);
        $this->assign('nav_curr', 'book');
        $q = $q_original;
        $this->_config_seo(array(
            'title' => sprintf(L('search_item_title'), $q) . '-' . C('pin_site_name'),
        ));
    }

    /**
     * 搜专辑
     */
    private function _search_album($q) {
        $sort = $this->_get('sort', 'trim', 'hot'); //排序
        if ($q) {
            $album_mod = M('album');
            $pagesize = 39;
            $where = array('status' => '1');
            $where['title'] = array('like', '%' . $q . '%');
            switch ($sort) {
                case 'hot':
                    $order = 'follows DESC,id DESC';
                    break;
                case 'new':
                    $order = 'id DESC';
                    break;
            }
            $count = $album_mod->where($where)->count('id');
            $pager = $this->_pager($count, $pagesize);
            $album_list = $album_mod->field('id,uid,uname,title,cover_cache')->where($where)->order($order)->limit($pager->firstRow . ',' . $pager->listRows)->select();
            foreach ($album_list as $key => $val) {
                $album_list[$key]['cover'] = unserialize($val['cover_cache']);
            }
            $this->assign('album_list', $album_list);
            $this->assign('page_bar', $pager->fshow());
        }
        $this->assign('sort', $sort);
        $this->assign('nav_curr', 'album');
        $this->_config_seo(array(
            'title' => sprintf(L('search_album_title'), $q) . '-' . C('pin_site_name'),
        ));
    }

    /**
     * 搜用户
     */
    private function _search_user($q) {
        if ($q) {
            $user_mod = M('user');
            $where = array('status' => '1');
            $where['username'] = array('like', '%' . $q . '%');
            $count = $user_mod->where($where)->count('id');
            $pager = $this->_pager($count, $pagesize);
            $user_list = $user_mod->field('id,username,province,city,fans,tags,intro')->where($where)->order('id DESC')->limit($pager->firstRow . ',' . $pager->listRows)->select();
            $this->assign('count', $count);
            $this->assign('user_list', $user_list);
            $this->assign('page_bar', $pager->fshow());
        }
         $this->_config_seo(array(
            'title' => sprintf(L('search_user_title'), $q) . '-' . C('pin_site_name'),
        ));
    }
    private function _search_article($q){
        $article = M('article');
        $tp = $this->_get('tp', 'trim');
        $sort = $this->_get('sort', 'trim', 'new'); //排序
        $day = $this->_get('day', 'trim');
        $isbest = $this->_get('isbest', 'trim');
        $day_w=$day > 0 ? (time()-($day*86400)) : 0;
        $where = array('status' => '1');

        $q_list=explode(" ",$q);
        $q_infos=array();
        foreach($q_list as $key=>$r){
            $q_infos[]="%".$r."%";/*or i.intro like '%".$r."%'*/
        }
        $where=array();
        $where['title'] = array('like',$q_infos,'AND');
        $where['info'] = array('like',$q_infos,'AND');
        $where['_logic'] = 'or';
        $map['_complex'] = $where;
        $map['status'] = 1;
        $map['cate_id'] =$tp;
        $map['add_time'] = array('gt',$day_w);
        $count = $article->where($map)->count('id');
        $pager = $this->_pager($count, $pagesize);
        switch ($sort) {
            case 'hot':
                $order = 'likes DESC,zan DESC,hits DESC,id DESC';
                break;
            case 'new':
                $order = 'add_time DESC';
                break;
        }
        $article_list = $article->where($map)->order($order)->limit($pager->firstRow . ',' . $pager->listRows)->select();
       // echo $article->getLastSql();
        if ($q) {
            if($tp == ''){
                $where = array('status' => '1');
            }
            if($isbest == 1){
                $where = array('isbest' => '1');
            }
            if (preg_match('/^[\x{4e00}-\x{9fa5}]+$/u',$q)) {
                $q_list= str_split($q,3);
            } else {
                $q_list=explode(" ",$q);
            }
            $q_info="";
            foreach($q_list as $key=>$r){
                $or=$key == 0 ? "" : " or ";
                $q_info.="$or title like '%".$r."%'  ";//or intro like '%".$r."%'
            }
            $q_infos=array();
            foreach($q_list as $key=>$r){
                $q_infos[]="%".$r."%";/*or i.intro like '%".$r."%'*/
            }
            $where=array();
            $where['i.title'] = array('like',$q_infos,'AND');
            $where['i.content'] = array('like',$q_infos,'AND');
            $where['_logic'] = 'or';
            $map['_complex'] = $where;
            $map['i.status'] = 1;
            $map['add_time'] = array('gt',$day_w);
            $where1=array();
            $where1['title'] = array('like',$q_infos,'AND');
            $where1['info'] = array('like',$q_infos,'AND');
            $where1['_logic'] = 'or';
            $map1['_complex'] = $where1;
            $map1['status'] = 1;
            $map1['add_time'] = array('gt',$day_w);

            $this->waterfall_tp($map, $order,$tp,$isbest,$map1);

        }
        $this->assign('count', $count);
        $this->assign('day', $day);
        $this->assign('sort', $sort);
        $this->assign('article_list', $article_list);
        $this->assign('page_bar', $pager->fshow());
        $this->_config_seo(array(
            'title' => sprintf(L('search_item_title'), $q) . '-' . C('pin_site_name'),
        ));
    }

}