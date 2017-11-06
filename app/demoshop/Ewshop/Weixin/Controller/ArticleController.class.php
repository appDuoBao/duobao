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
class ArticleController extends HomeController {
/* 频道封面页 */
public function index(){
		$cateid= $id ? $id : I('get.category', 0);//获取分类的英文名称

		//$category = D('Category')->info($cateid);
		$category = D('Category')->goodstreeinfo($cateid);//获取产品分类
		$id=$category['id'];
		if(!$id){
			$id = 0;
		}
		$class = M('Category')->where("pid='0' and model='5'")->select();
		//分类列表
			foreach($class as $k=> $val){
				$class[$k]['title']=$val['title'];
			}		
			$this->assign('class', $class);
		//两级子菜单获取
		$mapc['pid'] = $id;
		$mapc['ismenu'] = 1;
		$mapc['display'] = 1;
		$mapc['status'] = 1;
		$child = M('Category')->where($mapc)->select();
		if($child){//分类列表
			foreach($child as $k=> $val){
				$child[$k]['subcate']=M('Category')->where("pid='".$val['id']."'")->limit(3)->select();
			}		
			$this->assign('num', $count);
			$this->assign('cur_cateid', $id);
			$this->assign('childlist', $child);
		}else{//产品列表          		
			$cid = D('Category')->getChildrenId($id);
			$cid = (string)getalltreeid($id);//需要转换成字符串，不然可能会出现sql语句错误
			$map['category_id']=array("in",$cid);
			$map['status']=1;
			//推荐商品
			$key=I('get.order');
			$sort=I('get.sort');  
			if(isset($key)){
			
			if($key=="1"){$listsort="view"." ".$sort;}  
			if($key=="2"){$listsort="id"." ".$sort;} 
			if($key=="3"){$listsort="price"." ".$sort;} 
			if($key=="4"){$listsort="sale"." ".$sort;} 
			if($key=="5"){$listsort="comment"." ".$sort;} 	//comment 评论数			
			} 
			if(empty($key)){
				$key="1";$see="asc";
				$order="view";$sort="asc";
				$listsort=$order." ".$sort;			
			}
			
			if($sort=="asc"){$see="desc";}
			if($sort=="desc"){$see="asc";}
			$this->assign('see',$see);
			$this->assign('order',$key);
			$this->assign('value',$sort);
			
			$count=M('Document')->where($map)->count();
			$Page= new \Think\Page($count,4);
			$Page->setConfig('prev','上一页');
			$Page->setConfig('next','下一页');
			$Page->setConfig('first','第一页');
			$Page->setConfig('last','尾页');
			$Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
			$show= $Page->show();
			$list= M('Document')->where($map)->order( $listsort)->limit($Page->firstRow.','.$Page->listRows)->select();

	
			$this->assign('list',$list);// 赋值数据集
			$this->assign('page',$show);//
		}
		

		/*栏目页统计代码实现，tag=2*/
		if(1==C('IP_TONGJI')){
		$record=IpLookup("",2,$name);
		}
		if($category['title']){
			$this->meta_title = $category['title'];
		}else{
			$this->meta_title = '产品分类';
		}
		
		/* 模板赋值并渲染模板 */
		$this->assign('category', $category);
		$this->display($category['template_index']);
}



/* 列表页 */
public function lists($p = 1){

		$cateid= $id ? $id : I('get.category', 0);//获取分类的英文名称


		//$category = D('Category')->info($cateid);
		$category = D('Category')->goodstreeinfo($cateid);//获取产品分类
		$id=$category['id'];
	
		//两级子菜单获取
		$child = M('Category')->where("pid='$id'")->select();
		if($child && I('category')!='rexiao'){//分类列表
			foreach($child as $k=> $val){
				$child[$k]['subcate']=M('Category')->where("pid='".$val['id']."'")->limit(3)->select();
			}		
			$this->assign('num', $count);
			$this->assign('childlist', $child);			
		}else{//产品列表
			if(I('category')=='rexiao'){
				 $list = M('document')->table('ewshop_document a,  ewshop_document_product b')->where('a.id = b.id and b.mark =3')->order('a.level asc,a.id desc' )->limit("20")->select();
			}else{
				$cid = D('Category')->getChildrenId($id);
				$cid = (string)getalltreeid($id);//需要转换成字符串，不然可能会出现sql语句错误
				$map['category_id']=array("in",$cid);
				$map['status']=1;
				//推荐商品
				$key=I('get.order');
				$sort=I('get.sort');  
				if(isset($key)){
				
				if($key=="1"){$listsort="view"." ".$sort;}  
				if($key=="2"){$listsort="id"." ".$sort;} 
				if($key=="3"){$listsort="price"." ".$sort;} 
				if($key=="4"){$listsort="sale"." ".$sort;} 
				if($key=="5"){$listsort="comment"." ".$sort;} 	//comment 评论数			
				
				} 
				if(empty($key)){
					$key="1";$see="asc";
					$order="view";$sort="asc";
					$listsort=$order." ".$sort." ,level desc";			
				}
				
				if($sort=="asc"){$see="desc";}
				if($sort=="desc"){$see="asc";}
				$this->assign('see',$see);
				$this->assign('order',$key);
				$this->assign('value',$sort);
				
				$count=M('Document')->where($map)->count();
				$Page= new \Think\Page($count,4);
				$Page->setConfig('prev','上一页');
				$Page->setConfig('next','下一页');
				$Page->setConfig('first','第一页');
				$Page->setConfig('last','尾页');
				$Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
				$show= $Page->show();
				$list= M('Document')->where($map)->order($listsort)->limit($Page->firstRow.','.$Page->listRows)->select();

			}			
		
			$this->assign('list',$list);// 赋值数据集
			$this->assign('page',$show);//
		}
	
		/*栏目页统计代码实现，tag=2*/
		if(1==C('IP_TONGJI')){
		$record=IpLookup("",2,$name);
		}
		if($category['title']){
			$this->meta_title = $category['title'];
		}else{
			$this->meta_title = '产品分类';
		}		
		/* 模板赋值并渲染模板 */
		$this->assign('category', $category);
		if(I('category')=='rexiao'){
			$this->display('lists_rexiao');
		}else{
			$this->display($category['template_lists']);
		}
		
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
			$tmpl = 'Article/'. get_document_model($info['model_id'],'name') .'/detail';
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
		$limitRows = 5; // 设置每页记录数
		$p = new \Think\AjaxPage($count, $limitRows,"comment"); //第三个参数是你需要调用换页的ajax函数名
		$limit_value = $p->firstRow . "," . $p->listRows;
		$data = $comment->where("status='1' and goodid='$id'")->order('id desc')->limit($limit_value)->select(); // 查询数据
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
		$gwhere['good_id']=$id;
		$GoodsDataList  = $GoodsData->where($gwhere)->order('id asc')->group("extend_id")->select();//商品属性数据
		foreach($GoodsDataList as $k =>$v){
			$extendInfo = $GoodsExtend->info($v['extend_id']);//商品扩展属性
			if($extendInfo){
				$GoodsDataList[$k]['name'] = $extendInfo['extend_name'];
			}
			$gwhere['extend_id']=$v['extend_id'];
			$GoodsDataList[$k]['value'] = $GoodsData->where($gwhere)->order('id asc')->select();//商品属性数据
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
}
