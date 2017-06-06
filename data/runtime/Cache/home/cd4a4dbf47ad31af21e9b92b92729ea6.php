<?php if (!defined('THINK_PATH')) exit();?>		<?php if($dss == lb): if(is_array($item_list)): $i = 0; $__LIST__ = $item_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$r): $mod = ($i % 2 );++$i;?><li class="w_f2_nr1_1 hvr-glow">

          <a href="<?php echo U('item/index',array('id'=>$r['id']));?>" title="<?php echo ($r["title"]); ?>" target="_blank" class="zf_lista"><img src="<?php if($r['img']==''): ?>/images/nopic.jpg<?php else: ?> <?php if(preg_match('/img.baicaio.com/',$r['img'])): echo attach($r['img'],'item');?>!thumb208<?php else: echo attach($r['img'],'item'); endif; endif; ?>" title="<?php echo ($r["title"]); ?>" alt="<?php echo ($r["title"]); ?>"/></a>

          <div class="zf_listit">

              <h2><a href="<?php echo U('item/index',array('id'=>$r['id']));?>" title="<?php echo ($r["title"]); ?>" target="_blank"><?php echo ($r["title"]); ?><em style="color:red;"><?php echo ($r["price"]); ?></em></a></h2>

              <!--<p class="w_p1"><?php if($tab == 'isbao'): if($r['uid'] != 0): ?><span><a href="<?php echo U('space/index', array('uid'=>$r['uid']));?>" title="<?php echo ($r["uname"]); ?>" data-uid="<?php echo ($r['uid']); ?>" class="J_card"> <img src="<?php echo avatar($r['uid'],'');?>" title="<?php echo ($r["username"]); ?>" alt="<?php echo ($r["username"]); ?>" class="ava"/> <?php echo ($r["uname"]); ?></a></span><?php endif; endif; ?><span><?php echo (mdate($r['add_time'])); ?></span></p>-->

              <p class="w_p1"><a href="<?php echo U('orig/show',array('id'=>$r['orig_id']));?>" class="w_dj1"><?php echo getly($r['orig_id']);?></a><?php if($tab == 'isbao'): if($r['uid'] != 0): ?><span><a href="<?php echo U('space/index', array('uid'=>$r['uid']));?>" title="<?php echo ($r["uname"]); ?>" data-uid="<?php echo ($r['uid']); ?>" class="J_card"> <img src="<?php echo avatar($r['uid'],'');?>" title="<?php echo ($r["username"]); ?>" alt="<?php echo ($r["username"]); ?>" class="ava"/> <?php echo ($r["uname"]); ?></a></span><?php endif; endif; ?><span><?php echo (mdate($r['add_time'])); ?></span></p>

              <p><?php if($r['intro']): echo ($r["intro"]); else: echo (msubstr(strip_tags($r["content"]),0,130)); endif; ?></p>

              <div class="w_f2nr1_b">

              <div class="w_f2nr1_le"><a href="javascript:void(0)" onclick="jz_submit_click(this)" title="赞" class="w_z_1 Jz_submit" data="<?php echo ($r["id"]); ?>"  data-t="item"><?php echo ($r["zan"]); ?></a><a href="javascript:;" title="<?php echo ($r["title"]); ?>"  class="w_z_2 Jl_likes" data-id="<?php echo ($r["id"]); ?>" data-xid="1"><?php echo ($r["likes"]); ?></a><a href="<?php echo U('item/index', array('id'=>$r['id']));?>" title="<?php echo ($r["title"]); ?>"  class="w_z_3" target="_blank"><?php echo ($r["comments"]); ?></a></div><div class="w_f2nr1_rig">

              <div class="w_zzj_1">

                    <?php $llink = unserialize($r['go_link']);$lc = count($llink);?>

                    <?php if(is_array($llink)): $i = 0; $__LIST__ = $llink;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$rm): $mod = ($i % 2 );++$i; if($i == 1): ?><a href="__ROOT__/?m=item&a=tgo&to=<?php echo shortUrl($rm['link']);?>" title="<?php echo ($rm["title"]); ?>" class="w_zdlj_a" target="_blank" ><i class="<?php if($lc > 1): ?>w_down<?php else: ?>w_right<?php endif; ?>"></i><?php echo ($rm["name"]); ?></a><?php endif; endforeach; endif; else: echo "" ;endif; ?>

                    <?php if($lc > 1): ?><ul>

                    <?php if(is_array($llink)): $i = 0; $__LIST__ = $llink;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$rm): $mod = ($i % 2 );++$i;?><li><a href="__ROOT__/?m=item&a=tgo&to=<?php echo shortUrl($rm['link']);?>" target="_blank" rel="nofollow"  title="<?php echo ($rm["title"]); ?>"><?php echo ($rm["name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>

                  </ul><?php endif; ?>

                </div>

            </div></div>

            </div>

         </li><?php endforeach; endif; else: echo "" ;endif; ?>

      </ul>

       </div>       

	   <?php else: ?>

       <div class="w_nr3">

         <ul id="C_drc">

		   <?php if(is_array($item_list)): $i = 0; $__LIST__ = $item_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$r): $mod = ($i % 2 );++$i;?><li <?php if($i%3 == 1): ?>class="no_left"<?php endif; ?>>

             <a href="<?php echo U('item/index',array('id'=>$r['id']));?>" title="<?php echo ($r["title"]); ?>" target="_blank"><img src="<?php if($r['img']==''): ?>/images/nopic.jpg<?php else: echo attach($r['img'],'item'); endif; ?>" title="<?php echo ($r["title"]); ?>" alt="<?php echo ($r["title"]); ?>"/></a>

             <h2> <a href="<?php echo U('item/index',array('id'=>$r['id']));?>" title="<?php echo ($r["title"]); ?>" target="_blank"><?php echo (msubstr($r["title"],0,30,'')); ?><em style="color:red;"><?php echo ($r["price"]); ?></em></a></h2><div class="clear"></div>

			 

             <p class="w_p1"><?php if($tab == 'isbao'): if($r['uid'] != 0): ?><span><a href="<?php echo U('space/index', array('uid'=>$r['uid']));?>" title="<?php echo ($r["uname"]); ?>" data-uid="<?php echo ($r['uid']); ?>" class="J_card"> <img src="<?php echo avatar($r['uid'],'');?>" title="<?php echo ($r["username"]); ?>" alt="<?php echo ($r["username"]); ?>" class="ava"/> <?php echo ($r["uname"]); ?></a></span><?php endif; endif; ?><a href="<?php echo U('orig/show',array('id'=>$r['orig_id']));?>" ><?php echo getly($r['orig_id']);?>&nbsp;</a></p>

			 

			 

             <p  style="height:24px"><?php echo (mdate($r['add_time'])); ?></p>

             <div class="w_nr3_div">

             <div class="w_zzj_1">

				<?php $llink = unserialize($r['go_link']);$lc = count($llink);?>

				<?php if(is_array($llink)): $j = 0; $__LIST__ = $llink;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$rm): $mod = ($j % 2 );++$j; if($j == 1): ?><a href="__ROOT__/?m=item&a=tgo&to=<?php echo shortUrl($rm['link']);?>" title="<?php echo ($rm["title"]); ?>" class="w_zdlj_a" target="_blank" rel="nofollow" ><i class="<?php if($lc > 1): ?>w_down<?php else: ?>w_right<?php endif; ?>"></i><?php echo ($rm["name"]); ?></a><?php endif; endforeach; endif; else: echo "" ;endif; ?>

				<?php if($lc > 1): ?><ul>

				<?php if(is_array($llink)): $m = 0; $__LIST__ = $llink;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$rm): $mod = ($m % 2 );++$m;?><li><a href="__ROOT__/?m=item&a=tgo&to=<?php echo shortUrl($rm['link']);?>" target="_blank" rel="nofollow"  title="<?php echo ($rm["title"]); ?>" ><?php echo ($rm["name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>

              </ul><?php endif; ?>

			</div>

              <div class="w_f2nr1_le"><a href="javascript:void(0)" onclick="jz_submit_click(this)" title="赞" class="w_z_1 Jz_submit" data="<?php echo ($r["id"]); ?>"  data-t="item"><?php echo ($r["zan"]); ?></a><a href="javascript:;" title="<?php echo ($r["title"]); ?>"  class="w_z_2 Jl_likes" data-id="<?php echo ($r["id"]); ?>" data-xid="1"><?php echo ($r["likes"]); ?></a><a href="<?php echo U('item/index', array('id'=>$r['id']));?>" title="<?php echo ($r["title"]); ?>"  class="w_z_3" target="_blank"><?php echo ($r["comments"]); ?></a></div>

             </div>

           </li><?php endforeach; endif; else: echo "" ;endif; endif; ?>

	   <script>

	   $(document).ready(function(e) {

             $(".w_zzj_1").hover(

				  function () {

					$(this).children("ul").show();

					$(this).find("i").addClass("w_up").removeClass("w_down");

				  },

				  function () {

					$(this).children("ul").hide("");

					$(this).find("i").addClass("w_down").removeClass("w_up");

				  }

				); 



           }) 

	   </script>