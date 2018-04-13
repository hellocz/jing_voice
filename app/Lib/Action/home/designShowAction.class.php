<?php
class designShowAction extends frontendAction {
    
     public function index(){
     	$model = $this->_get('model','trim');
		$temp = $this->_get('temp','trim');
     	$this->display($model . ":" . $temp);
     }
 }