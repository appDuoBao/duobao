<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 www@ewangtx.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ew_xiaoxiao <www@ewangtx.com> <http://www.ewangtx.com>
// +----------------------------------------------------------------------
namespace Weixin\Controller;
/**
* 文档模型控制器
* 文档模型列表和详情
*/
class GoodsController extends HomeController {
	/* 频道封面页 */
 	public function index(){
        $cate    = M('Category');
        $catlist = $cate->where("pid=0 and model=5 and status=1")->order('id asc')->select();
        foreach ($catlist as $k => $v) {
            $catlist[$k]['child'] = $cate->where("pid='" . $v['id']."' and status ='1'")->order('id asc')->select();
            foreach ($catlist[$k]['child'] as $kk => $vv) {
                $catlist[$k]['child'][$kk]['child'] = $cate->where("pid='" . $vv['id']."' and status ='1'")->order('id asc')->select();
            }
        }
        $this->assign('catlist' , $catlist);
		$this->meta_title = '服务产品';
        $this->display($category['template_index']);
		
    }



	/* 商品频道封面页 */
	public function lists(){
		$cateid= $id ? $id : I('get.category', 0);//获取分类的英文名称		
		/* 分类信息 */
		$category = $this->category();
		$id=$category['id'];//当前栏目id
		
		$cid = D('Category')->getChildrenId($id);
		$map['category_id']=array("in",$cid);
		$map['status']=1;
		
        //产品列表
		$type=I('get.type');//筛选类型
		$sort=I('get.sort');//升序和降序
		$sort_res = $sort;//最初的排序
		if(isset($type)){
			if($type=="1"){  $listsort="view"." ".$sort;}  //综合搜索（浏览量）
			if($type=="2"){	 $listsort="id"." ".$sort;}    //新品	
			if($type=="3"){  $listsort="price"." ".$sort;} //价格
			if($type=="4"){  $listsort="sale"." ".$sort;}  //销量	
			if($type=="5"){  $listsort="comment"." ".$sort;}//评论数
		}
		//当筛选类型不存在时
		if(empty($type)){
			$sort_val="asc";
			$listsort='view desc';			
		}
		
        if($sort=="asc"){$sort_val="desc";}
        if($sort=="desc"){$sort_val="asc";}
		$this->assign('sort_res',$sort_res);
		$this->assign('sort_val',$sort_val);
		$this->assign('type',$type);
		$this->assign('sort',$sort);

		$count=M('Document')->where($map)->count();
		$per_page = 100;//每页显示的数量
		$Page= new \Think\Page($count,$per_page);
		$Page->lastSuffix = false;
		$Page->setConfig('prev','上一页');
		$Page->setConfig('next','下一页');
		$Page->setConfig('first','第一页');
		$Page->setConfig('last','尾页');
		$Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
		$show= $Page->show();
		$list= M('Document')->where($map)->order( $listsort)->limit($Page->firstRow.','.$Page->listRows)->select();
		foreach($list as $k => $v){
			$list[$k]['marketprice'] = M('document_product')->where("id = '{$v['id']}'")->getField('marketprice');//市场价
		}
		$total_page = ceil($count/$per_page);
		$this->assign('total_page', $total_page);//总页数		
		$this->assign('list',$list);// 赋值数据集
		$this->assign('page',$show);
		
		
		$pid=$category['id'];
	    $dqfl = M('Category')->where("pid='$pid' and ismenu=1 and status=1")->select();
		$this->assign('dqfl',$dqfl);//当前分类的子分类
		
		$zpid=$category['pid'];
		$zdqfl = M('Category')->where("pid='$zpid' and ismenu=1 and status=1")->select();
		$this->assign('zdqfl',$zdqfl);//当前分类的子分类
		/***********左侧分页begin**********/
		$cur_page = I('get.p') ? I('get.p') : 1;//当前页数
		//上一页
		if($cur_page != 1){
			$previous = $cur_page - 1;
		}
		//下一页
		if($cur_page != $total_page && $total_page !=0){
			$next = $cur_page + 1;
		}
		$this->assign('cur_page',$cur_page);//当前页数
		$this->assign('previous',$previous);//上一页
		$this->assign('next',$next);//下一页
		/***********左侧分页end**********/

		//获取分类的id
		$name=$category['name'];
		$child=M('Category')->where("pid='$id'")->select();
		$this->assign('childlist', $child);

		/*栏目页统计代码实现，tag=2*/
		if(1==C('IP_TONGJI')){
			$record=IpLookup("",2,$name);
		}		

		/*销量排行*/
		$sales=$this->ranks();
		$this->assign('sales', $sales);
		/*最近访问*/
		//$recent=view_recent();
		//$this->assign('recent', $recent);

		$this->assign('category', $category);// 栏目信息
		$this->assign('cate_name', $name);//栏目标识
		$this->assign('cate_title', $category['title']);//栏目标题
		
		$this->meta_title = $category['title'];		
		$this->display($category['template_lists']);
	}




/* 商品详情页 */
 public function detail($id = 0, $p = 1){

		/* 购物车调用*/
		$cart=R("Shopcart/usercart");
		$this->assign('usercart',$cart);
		if(!session('user_auth')){
			$usercart=$_SESSION['cart'];
			$this->assign('usercart',$usercart);
		}

		/* 浏览量排行前7个商品*/
		$view=M('Document')->where("display=1 and status=1")->order("view desc")->select();
		$this->assign('viewlist', $view);
		/* 标识正确性检测 */
		if(!($id && is_numeric($id))){
		$this->error('文档ID错误！');
		}	
		/* 获取详细信息 */
		$Document = D('Document');
		$info = $Document->detail($id);

		if(!$info){
			$this->error($Document->getError());
		}else{
			if($info['pics']){
				$dtpics = explode(',',$info['pics']);
				$this->assign('pics',$dtpics);
			}
		}
		/* 分类信息 */
		$category = $this->category($info['category_id']);
		/* 获取模板 */
		if(!empty($info['template'])){//已定制模板
			$tmpl = $info['template'];
		} elseif (!empty($category['template_detail'])){ 
			//分类已定制模板
			$tmpl = $category['template_detail'];
		} else { 
			//使用默认模板
			$tmpl = 'Goods/'. get_document_model($info['model_id'],'name') .'/detail';
		}
		/* 更新浏览数 */
		$map = array('id' => $id);
		$Document->where($map)->setInc('view');
		/*文章点击统计代码实现，tag=4*/
		if(1==C('IP_TONGJI')){
			$record=IpLookup("",4,$id);
		}
		/*获取商品所有评论*/
		$comment = M('comment');	
		$count = $comment->where("status='1' and goodid='$id'")->count(); //计算记录数
        $this->assign('count', $count);
		$haopingcount = $comment->where("status='1' and goodid='$id' and (serve=4 or serve=5)")->count(); //好评数
		$zhongpingcount = $comment->where("status='1' and goodid='$id' and (serve=2 or serve=3)")->count(); //中评数
		$chapingcount = $comment->where("status='1' and goodid='$id' and serve=1")->count(); //差评数
		$this->assign('haoping', $haopingcount);
		$this->assign('zhongping', $zhongpingcount);
		$this->assign('chaping', $chapingcount);

		$limitRows = 5; // 设置每页记录数
		$p = new \Think\AjaxPage($count, $limitRows,"comment"); //第三个参数是你需要调用换页的ajax函数名
		$limit_value = $p->firstRow . "," . $p->listRows;
		$data = $comment->where("status='1' and goodid='$id'")->order('id desc')->limit($limit_value)->select(); // 查询数据
	    foreach ($data as $k=>$v){
			$data[$k]['nickname']=M('Member')->where("uid=".$v['uid'])->getField('nickname');
		}
	    $page = $p->show(); // 产生分页信息，AJAX的连接在此处生成
		$this->assign('list',$data);
		$this->assign('page',$page);

         /* 咨询管理 */
		$message=M("message");
		$reply=M("reply");
		$countmessage=$message->where(" goodid='$id'")->count();
		$Pagequestion=new \Think\AjaxPage($countmessage,5,"quest");	
		$limitquestion = $Pagequestion->firstRow . "," . $Pagequestion->listRows;
		$showquestion= $Pagequestion->show();
		$listquestion=$message->where("goodid='$id'")->order('id desc')->limit($limitquestion)->select();
		foreach($listquestion as $n=> $val){
		$listquestion[$n]['id']=$reply->where('messageid=\''.$val['id'].'\'')->select();
		}
		$this->assign('listquestion',$listquestion);// 赋值数据集
		$this->assign('pagequestion',$showquestion);//
		
		
		$GoodsExtend = D('GoodsExtend');//实例化商品类型属性扩展表
		$GoodsData = D('GoodsData');
		$gwhere['goods_id']=$id;
		


		$GoodsDataList  = $GoodsData->where($gwhere)->select();//获取商品属性数据

	 if($GoodsDataList){//如果有子分类数据
		 $strmap = '';
		 $zongshu = 0;
		 foreach($GoodsDataList as $k =>$v){
			
				 if($strmap){
					 $strmap .= ",'".$v['sku']."'";//对应的分类商品
				 }else{
					 $strmap = "'".$v['sku']."'";//对应的分类商品
				 }
			 

			$zongshu += $v['num'];//所有分类商品的总数
		 }
			$info['stock'] = $zongshu;//所有分类商品的总数
		 $goodsextendclasslist = M()->query("SELECT * FROM ewshop_goods_sku WHERE sku IN({$strmap})");//获取此商品对应的分类商品的所有属性
		 $GoodsDataList = array();

		 foreach($goodsextendclasslist as $k=>$v){
			 $GoodsDataList[$v['eid']][] = $v['cid'];//获取对应的分类属性以及子属性（键值为属性ID，键名为属性对应的子属性数组）
		 }

		 $arr = array();
		 $i = 0;
		 foreach($GoodsDataList as $k=>$v){
			 $arr[$i]['name'] = M('GoodsExtend')->getFieldByExtend_id($k,'extend_name');//获取对应属性的名称
			 $arr[$i]['id'] = $k;//获取属性的id
			 $ii = 0;
			$m = array_unique($v);	//	去除重复多次出现的子分类
			 foreach($m as $k1=>$v1){
				 $arr[$i]['value'][$ii]['id'] = $v1;//获取子分类名称
				 $arr[$i]['value'][$ii]['extend_name'] = M('GoodsExtendClass')->getFieldById($v1,'name');//获取子分类id
				 $ii++;
			 }
			 $i++;
		 }
		$GoodsDataList = $arr;

	 }else{

		 //待完善
		 $nofenlei = 1;
		 $this->assign('nofenlei',      $nofenlei);//商品属性数据
		 
		
	 }
		if($info['stock']<=0){
			$this->error('此商品已售完');die;
		}

		$this->assign('GoodsDataList',      $GoodsDataList);//商品属性数据
		//判断是否收藏
		$uid=D("Member")->uid();
		$data["id"] = $id;
		$data["uid"]=$uid;
		$fav=M("favortable");
		$exsit=$fav->where("goodid='$id' and uid='$uid'")->getField("id");
		if(isset($exsit)){
			$isitfav = 1;
		}else{
			$isitfav = 0 ;
		}
		$this->assign('isitfav', $isitfav);
		/* 模板赋值并渲染模板 */
		$this->assign('category', $category);
		$this->assign('info', $info);
		$this->meta_title = $info["title"];
		$this->display($tmpl);
}

    /* ajax评论-所有评论 */
    public function comment(){	
	
		$curpage=$_POST["p"];//当前页数
	    if($_POST["goodid"]){ 
			 $goodid=$_POST["goodid"];	 	
			 $this->assign('goodid',$goodid);
		}	
		session('goodid',null);//ajax评论session
	    session('goodid',$goodid);

        $comment = M('comment');
		$count = $comment->where("status='1' and goodid='".$goodid."'")->count(); //计算记录数
		$limitRows = 5; // 设置每页记录数
		$p = new \Think\AjaxPage($count, $limitRows,"comment"); //第三个参数是你需要调用换页的ajax函数名
		$limit_value = $p->firstRow . "," . $p->listRows;
		$data = $comment->where("status='1' and goodid='".$goodid."'")->order('id desc')->limit($limit_value)->select(); // 查询数据
		$page = $p->show(); // 产生分页信息，AJAX的连接在此处生成
		$this->assign('list',$data);

		$this->assign('page',$page);
		$this->display(); 
	}		
		
		
/* ajax评论-好评 */
 public function commentgood(){
	  if($_POST["goodid"])
	 {  $goodid=$_POST["goodid"];	 	
		$this->assign('goodid',$goodid);
		}	
		 session('goodid',null);//ajax评论session
	     session('goodid',$goodid);
        $comment = M('comment');
		$count = $comment->where("status='1' and goodid='$goodid' and score='3'")->count(); //计算记录数
		$limitRows = 5; // 设置每页记录数
		$p = new \Think\AjaxPage($count, $limitRows,"commentgood"); //第三个参数是你需要调用换页的ajax函数名
		$limit_value = $p->firstRow . "," . $p->listRows;
		$data = $comment->where("status='1' and goodid='$goodid' and score='3'")->order('id desc')->limit($limit_value)->select(); // 查询数据
		$page = $p->show(); // 产生分页信息，AJAX的连接在此处生成
		$this->assign('list',$data);
		$this->assign('page',$page);
		$this->display(); 
        }
/* ajax评论-中评 */
 public function commentmiddle(){
	  if($_POST["goodid"])
	 {  $goodid=$_POST["goodid"];	 	
		$this->assign('goodid',$goodid);
		}	
		 session('goodid',null);//ajax评论session
	     session('goodid',$goodid);
        $comment = M('comment');
		$count = $comment->where("status='1' and goodid='$goodid' and score='2'")->count(); //计算记录数
		$limitRows = 5; // 设置每页记录数
		$p = new \Think\AjaxPage($count, $limitRows,"commentmiddle"); //第三个参数是你需要调用换页的ajax函数名
		$limit_value = $p->firstRow . "," . $p->listRows;
		$data = $comment->where("status='1' and goodid='$goodid' and score='2'")->order('id desc')->limit($limit_value)->select(); // 查询数据
		$page = $p->show(); // 产生分页信息，AJAX的连接在此处生成
		$this->assign('list',$data);
		$this->assign('page',$page);
		$this->display(); 
        }
  /* ajax评论-差评 */
 public function commentworse(){	
	  if($_POST["goodid"])
	 {  $goodid=$_POST["goodid"];	 	
		$this->assign('goodid',$goodid);
		}	
		 session('goodid',null);//ajax评论session
	     session('goodid',$goodid);
        $comment = M('comment');
		$count = $comment->where("status='1' and goodid='$goodid' and score='1'")->count(); //计算记录数
		$limitRows = 5; // 设置每页记录数
		$p = new \Think\AjaxPage($count, $limitRows,"commentworse"); //第三个参数是你需要调用换页的ajax函数名
		$limit_value = $p->firstRow . "," . $p->listRows;
		$data = $comment->where("status='1' and goodid='$goodid' and score='1'")->order('id desc')->limit($limit_value)->select(); // 查询数据
		$page = $p->show(); // 产生分页信息，AJAX的连接在此处生成
		$this->assign('list',$data);
		$this->assign('page',$page);
		$this->display(); 
        }
public function quest(){	
	  if($_POST["goodid"])
	 {  $goodid=$_POST["goodid"];	 	
		$this->assign('goodid',$goodid);
		}	
		 session('goodid',null);//ajax评论session
	     session('goodid',$goodid);
        $message=M("message");
		$reply=M("reply");
		$count=$message->where(" goodid='42'")->count();
		$p=new \Think\AjaxPage($count,5,"quest");	
		$limit= $p->firstRow . "," . $p->listRows;
		$page= $p->show();
		$list=$message->where("goodid='42'")->order('id desc')->limit($limit)->select();
		foreach($list as $n=> $val){
		$list[$n]['id']=$reply->where('messageid=\''.$val['id'].'\'')->select();
		}
		$this->assign('list',$list);
		$this->assign('page',$page);
		$this->display(); 
        }
/* 文档分类检测 */
 private function category($id = 0){
		/* 标识正确性检测 */
		$id = $id ? $id : I('get.category', 0);
		if(empty($id)){
		$this->error('没有指定文档分类！');
		}
		/* 获取分类信息 */
		$category = D('Category')->info($id);
		if($category && 1 == $category['status']){
		switch ($category['display']) {
		case 0:
		$this->error('该分类禁止显示！');
		break;
		//TODO: 更多分类显示状态判断
		default:
		return $category;
		}
		} else {
		$this->error('分类不存在或被禁用！');
		}
		}

	//销量排行
	public function ranks($name){
		//获取完整的url
		
		////获取商品访问来源来自url的商品数组，tag=3
		$list=M('document')->limit(5)->order("sale desc")->select();
		return $list;
	}

	//商品评论
	public function addcomment(){
	   if(IS_POST){
			$comment=M("Comment");
			$comment->create();
			$data["content"]=$_POST["content"];
			$data["goodid"]=$_POST["goodid"];
			$data["uid"]=D('member')->uid();
			if($data["uid"]){
				$member=M('Member')->where("uid =".$data['uid'])->find();
				$name = $member['nickname'];
				$ucmember=M('UcenterMember')->where("id =".$data['uid'])->find();
				$return["face"] = get_faceUrl($data["uid"]);
				if(empty($name)){
					$name = $ucmember['username'];
				}
				$return["name"] = $name;
			}else{
				$return["name"] = '匿名';
				$return["face"] = '/Public/static/images/noface.jpg';
			}			
			$data["create_time"]=NOW_TIME;
			$data["status"]=1;
			if($comment->add($data)){	
				$return["time"]=date("Y-m-d H:i:s",time());
				$this->ajaxreturn($return);	
			}
		}
	}
	
	//获取选择属性后的商品信息
	public function getnewprice(){
	if(IS_AJAX ){
		$lastselect = $_POST['last'];
		$id=$_POST["id"];
		$excarr = $_POST['shuxing'];
		$yixuannum = count($excarr);
		$excstr = implode(",",$excarr);
		$list = M('GoodsExtendClass')->field('id')->where(array('is_delete'=>1,'id'=>array('in',$excarr)))->select();//判断是否有被删除的子属性
		if($list){
			$data['list'] = $list;
			$data['status'] = 5; 
			$data['msg'] = '属性不存在或者已经被删除';
			$this->ajaxreturn($data);
			die;
		}
		$exarr = M('GoodsSku')->field('eid')->group('eid')->where(array('cid'=>array('in',$excarr)))->select();//获取子属性对应的父属性
		$exstr = '';//将父属性ID 组成字符串
		foreach($exarr as $v){
			if($exstr){
				$exstr .= ",'".$v['eid']."'";
			}else{
				$exstr = "'".$v['eid']."'";
			}
		}

		$list = M('GoodsExtend')->field('extend_id')->where(array('is_delete'=>1,'extend_id'=>array('in',$exstr)))->select();//判断是否有被删除的属性
		if($list){
			$data['status'] = 4; 
			$data['msg'] = '上级属性不存在或已被删除，请刷新后重新购买';
			$this->ajaxreturn($data);
			die;
		}
		
		$gdmap['goods_id'] = $id;
		$allsku = M('GoodsData')->field('sku,num')->where($gdmap)->select();//获取当前商品所有的sku
		$num = 0;
		$skustr = '';
		foreach($allsku as $v){
			if($skustr){
				$skustr .= ",'".$v['sku']."'";
			}else{
				$skustr = "'".$v['sku']."'";
			}
			$num += $v['num'];
		}
		if($num<=0){
			$data['status'] = 3; 
			$data['msg'] = '对不起，此商品已售完';
			$this->ajaxreturn($data);
			die;
		}		
		$sql = "SELECT eid FROM `ewshop_goods_sku` WHERE sku IN(".$skustr.") GROUP BY eid";//查询此产品所有分类的对应的分类属性id
		$allexs = M()->query($sql);
		$alleid = array();
		foreach($allexs as $k=>$v){
			$alleid[] = $v['eid'];
		}
		$alleid = array_unique($alleid);//去除重复的eid
		$alleidnum = count($alleid);
		if($alleidnum>$yixuannum){//如果所选属性小于总属性数量
			$thiseids = M('GoodsExtendClass')->field('pid')->where(array('id'=>array('in',$excarr)))->select();//当前选中的属性的父级属性
			$tseidarr = array();
			foreach($thiseids as $v){
				$tseidarr[] = $v['pid'];
			}
			$oeid = array_diff($alleid,$tseidarr);//剩余未选属性的eid
			$oeidstr = implode(',',$oeid);//剩余未选属性的eid转换成字符串
			$sql = "SELECT sku FROM `ewshop_goods_sku` WHERE cid IN (".$excstr.")  AND sku IN (".$skustr.") GROUP BY sku HAVING COUNT(sku)>=".$yixuannum;
			$list = M()->query($sql);
			if(!$list){
				
				if($lastselect){
					$this->changeselect($lastselect,$id);
				}
				
				$data['status'] = 6; 
				$data['msg'] = '商品属性选择有误或商品属性已做调整，请刷新页面后重新购买';
				$this->ajaxreturn($data);
				die;
			}
			$listskustr = '';// 已选属性对应的产品sku
			foreach($list as $v){
				if($listskustr){
					$listskustr .= ",'".$v['sku']."'";
				}else{
					$listskustr = "'".$v['sku']."'";
				}				
			} 
			$sql = "SELECT eid,cid FROM `ewshop_goods_sku` WHERE sku IN (".$listskustr.") AND eid IN (".$oeidstr.") GROUP BY cid";//获取剩余未选属性
			$exlist = M()->query($sql);
			if(!$exlist){
				$data['status'] = 6; 
				$data['msg'] = '商品属性选择有误或商品属性已做调整，请刷新页面后重新购买';
				$this->ajaxreturn($data);
				die;
			}
			$newexlist = array();//已选产品剩余可选属性
			foreach($exlist as $v){
				$newexlist[$v['eid']][] = $v['cid'];
			}
			
			$sql = "SELECT num,total FROM `ewshop_goods_data` WHERE sku IN (".$listskustr.")";
			$yixuaninfo = M()->query($sql);//获取已选产品价格和剩余数量
	
			$yuanjia = M('Document')->getFieldById($id,'price');
			if(count($yixuaninfo)>1){
				$num = 0;
				$total = array();
				foreach($yixuaninfo as $v){
					$num+= $v['num'];
					$total[] = $v['total'];
				}
				sort($total);
				$mintotal = $total[0]+$yuanjia;
				$maxtotal = end($total)+$yuanjia;
				if($mintotal==$maxtotal){
					$total = $mintotal;
				}else{
					$total = $mintotal."~".$maxtotal;
				}
				
			}else{
				$num = $yixuaninfo[0]['num'];
				$total = $yixuaninfo[0]['total']+$yuanjia;//需要额外增加的金额
			}
			$data['status'] = 1;//查询正确，属性未选择完全
			$data['num'] = $num;
			$data['total'] = $total;
			$data['value'] = $newexlist;
			$this->ajaxreturn($data);
			die;
		}elseif($alleidnum==$yixuannum){
							
			$sql = "SELECT sku FROM `ewshop_goods_sku` WHERE cid IN (".$excstr.") AND sku IN (".$skustr.") GROUP BY sku HAVING COUNT(sku)>=".$yixuannum;
			$proinfo = M()->query($sql);
			
			if(count($proinfo)==1){
				$yuanjia = M('Document')->getFieldById($id,'price');
				$proinfo = M('GoodsData')->field('num,total')->where(array('sku'=>$proinfo[0]['sku']))->find();
				$data['status'] = 2;//查询正确，属性选择完全
				$data['num'] = $proinfo['num'];
				$data['total'] = $yuanjia+$proinfo['total'];
				$this->ajaxreturn($data);
				die;
			}else{
				if($lastselect){
					$this->changeselect($lastselect,$id);
				}
				$data['status'] = 6;
				$data['msg'] = '商品属性选择有误或商品属性已做调整，请刷新页面后重新购买';
				$this->ajaxreturn($data);
				die;
			}
		}else{
				$data['status'] = 6; 
				$data['msg'] = '商品属性选择有误或商品属性已做调整，请刷新页面后重新购买';
				$this->ajaxreturn($data);
				die;
		}
	}
		
	}
	
	//获取选择属性后的商品信息
	public function changeselect($lastselect,$id){
		
		$id = $id;
		$excarr = $lastselect;
		$yixuannum = 1;
		$excstr = $excarr ;
		$list = M('GoodsExtendClass')->field('id')->where(array('is_delete'=>1,'id'=>array('in',$excarr)))->select();//判断是否有被删除的子属性
		if($list){
			$data['list'] = $list;
			$data['status'] = 5; 
			$data['msg'] = '属性不存在或者已经被删除';
			$this->ajaxreturn($data);
			die;
		}
		$exarr = M('GoodsSku')->field('eid')->group('eid')->where(array('cid'=>array('in',$excarr)))->select();//获取子属性对应的父属性
		$exstr = '';//将父属性ID 组成字符串
		foreach($exarr as $v){
			if($exstr){
				$exstr .= ",'".$v['eid']."'";
			}else{
				$exstr = "'".$v['eid']."'";
			}
		}

		$list = M('GoodsExtend')->field('extend_id')->where(array('is_delete'=>1,'extend_id'=>array('in',$exstr)))->select();//判断是否有被删除的属性
		if($list){
			$data['status'] = 4; 
			$data['msg'] = '上级属性不存在或已被删除，请刷新后重新购买';
			$this->ajaxreturn($data);
			die;
		}
		
		$gdmap['goods_id'] = $id;
		$allsku = M('GoodsData')->field('sku,num')->where($gdmap)->select();//获取当前商品所有的sku
		$num = 0;
		$skustr = '';
		foreach($allsku as $v){
			if($skustr){
				$skustr .= ",'".$v['sku']."'";
			}else{
				$skustr = "'".$v['sku']."'";
			}
			$num += $v['num'];
		}
		if($num<=0){
			$data['status'] = 3; 
			$data['msg'] = '对不起，此商品已售完';
			$this->ajaxreturn($data);
			die;
		}		
		$sql = "SELECT eid FROM `ewshop_goods_sku` WHERE sku IN(".$skustr.") GROUP BY eid";//查询此产品所有分类的对应的分类属性id
		$allexs = M()->query($sql);
		$alleid = array();
		foreach($allexs as $k=>$v){
			$alleid[] = $v['eid'];
		}
		$alleid = array_unique($alleid);//去除重复的eid
		$alleidnum = count($alleid);
		if($alleidnum>$yixuannum){//如果所选属性小于总属性数量
			$thiseids = M('GoodsExtendClass')->field('pid')->where(array('id'=>array('in',$excarr)))->select();//当前选中的属性的父级属性
			$tseidarr = array();
			foreach($thiseids as $v){
				$tseidarr[] = $v['pid'];
			}
			$oeid = array_diff($alleid,$tseidarr);//剩余未选属性的eid
			$oeidstr = implode(',',$oeid);//剩余未选属性的eid转换成字符串
			$sql = "SELECT sku FROM `ewshop_goods_sku` WHERE cid IN (".$excstr.")  AND sku IN (".$skustr.") GROUP BY sku HAVING COUNT(sku)>=".$yixuannum;
			$list = M()->query($sql);
			if(!$list){
		
				$data['status'] = 6; 
				$data['msg'] = '商品属性选择有误或商品属性已做调整，请刷新页面后重新购买';
				$this->ajaxreturn($data);
				die;
			}
			$listskustr = '';// 已选属性对应的产品sku
			foreach($list as $v){
				if($listskustr){
					$listskustr .= ",'".$v['sku']."'";
				}else{
					$listskustr = "'".$v['sku']."'";
				}				
			} 
			$sql = "SELECT eid,cid FROM `ewshop_goods_sku` WHERE sku IN (".$listskustr.") AND eid IN (".$oeidstr.") GROUP BY cid";//获取剩余未选属性
			$exlist = M()->query($sql);
			if(!$exlist){
				$data['status'] = 6; 
				$data['msg'] = '商品属性选择有误或商品属性已做调整，请刷新页面后重新购买';
				$this->ajaxreturn($data);
				die;
			}
			$newexlist = array();//已选产品剩余可选属性
			foreach($exlist as $v){
				$newexlist[$v['eid']][] = $v['cid'];
			}
			
			$sql = "SELECT num,total FROM `ewshop_goods_data` WHERE sku IN (".$listskustr.")";
			$yixuaninfo = M()->query($sql);//获取已选产品价格和剩余数量
	
			$yuanjia = M('Document')->getFieldById($id,'price');
			if(count($yixuaninfo)>1){
				$num = 0;
				$total = array();
				foreach($yixuaninfo as $v){
					$num+= $v['num'];
					$total[] = $v['total'];
				}
				sort($total);
				$mintotal = $total[0]+$yuanjia;
				$maxtotal = end($total)+$yuanjia;
				if($mintotal==$maxtotal){
					$total = $mintotal;
				}else{
					$total = $mintotal."~".$maxtotal;
				}
				
			}else{
				$num = $yixuaninfo[0]['num'];
				$total = $yixuaninfo[0]['total']+$yuanjia;//需要额外增加的金额
			}
			$data['status'] = 1;//查询正确，属性未选择完全
			$data['num'] = $num;
			$data['total'] = $total;
			$data['value'] = $newexlist;
			$this->ajaxreturn($data);
			die;
		}elseif($alleidnum==$yixuannum){
							
			$sql = "SELECT sku FROM `ewshop_goods_sku` WHERE cid IN (".$excstr.") AND sku IN (".$skustr.") GROUP BY sku HAVING COUNT(sku)>=".$yixuannum;
			$proinfo = M()->query($sql);
			
			if(count($proinfo)==1){
				$yuanjia = M('Document')->getFieldById($id,'price');
				$proinfo = M('GoodsData')->field('num,total')->where(array('sku'=>$proinfo[0]['sku']))->find();
				$data['status'] = 2;//查询正确，属性选择完全
				$data['num'] = $proinfo['num'];
				$data['total'] = $yuanjia+$proinfo['total'];
				$this->ajaxreturn($data);
				die;
			}else{
				$data['status'] = 6;
				$data['msg'] = '商品属性选择有误或商品属性已做调整，请刷新页面后重新购买';
				$this->ajaxreturn($data);
				die;
			}
		}else{
				$data['status'] = 6; 
				$data['msg'] = '商品属性选择有误或商品属性已做调整，请刷新页面后重新购买';
				$this->ajaxreturn($data);
				die;
		}
		
	}
}
