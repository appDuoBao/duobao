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
class ServiceController extends HomeController {
    /* 频道封面页 */
  public function index(){
   
	/* 左侧菜单 */
	$menu=R('index/menulist');
	$this->assign('categoryq', $menu);
	/**
	* 购物车调用
	*/
	$cart=R("shopcart/usercart");
	$this->assign('usercart',$cart);
	if(!session('user_auth')){$usercart=$_SESSION['cart'];
		$this->assign('usercart',$usercart);
	} 
	/*栏目页统计代码实现，tag=2*/
	if(1==C('IP_TONGJI')){
	$record=IpLookup("",2,$name); 
	}
	/* 热词调用*/
	$hotsearch=R("Index/getHotsearch");
	$this->assign('hotsearch',$hotsearch);  
	/* 分类信息id */
	$id= I('get.id');
	//分类一维数组
	$category=M("category")->where("id='$id'")->find();
	//获取最大的文章id
	$info=M("document")->where("category_id='$id'and model_id='2'")->order("id desc")->limit(1)->find();
	
	/*获取文章明细*/
	if(!empty($info)){
	$data = D('Document')->detail($info['id']);
	}
	/*设置网站标题，一维数组*/
	$pid=$category['pid'];
	$pcategory=M("category")->where("id='$pid'")->find();
	
	$this->meta_title = $category['title']."-".$pcategory['title'];
	$position="<p class='red fwb'>".$pcategory['title']."</p>><p class='red fwb'>".$category['title']."</p>";
	$this->assign('position',$position);
	
	$this->assign('info',$data);
	$menulist=$this->AllMenu();
	$this->assign('footermenu',$menulist);
	
	
	
	$this->display();
  }

	/**
     * 获取售后服务列表
     * @param  integer  $category 分类ID
     * @param  string   $order    排序规则
     * @param  integer  $status   状态
     * @param  boolean  $count    是否返回总数
     * @param  string   $field    字段 true-所有字段
     * @return array              文档列表
     */
	public function AllMenu(){
	/* 一级分类信息 */
	$menu=M("category")->where("ismenu='2' and pid='0'")->order("id asc")->select();
	$sonmenu=M("category");
	foreach($menu as $n=> $val){
		  $menu[$n]['id']=$sonmenu->where('pid=\''.$val['id'].'\'')->select();
	 
		 }
	return $menu;
	
	}
	
	public function add(){
		$data=M("member")->limit(1)->find();
		 $this->assign('info',$data);
	$this->display();
	}

	public function addmessage(){
	   if(IS_POST){
			$message=M("message");
			$message->create();
			$data["content"]=$_POST["content"];
			$data["goodid"]=$_POST["goodid"];
			$data["uid"]=D('member')->uid();
			$data["create_time"]=NOW_TIME;
			$data["time"]=date("Y-m-d H:i:s",time());
			$data["status"]=1;
			if($message->add($data)){	
				$this->ajaxreturn($data);	
			}
		}
	}
	  
	/**单页模板**/
    public function page(){
		/* 左侧菜单 */
		$menu=R('index/menulist');
		$this->assign('categoryq', $menu);

		/*栏目页统计代码实现，tag=2*/
		if(1==C('IP_TONGJI')){
		$record=IpLookup("",2,$name); 
		}

		/* 分类信息id */
		$catid= I('get.catid');
		//分类一维数组
		$category=M("category")->where("id='$catid'")->find();

		/*设置网站标题，一维数组*/
		$pid=$category['pid'];
		$pcategory=M("category")->where("id='$pid'")->find();
		
		$this->meta_title = $category['title']."-".$pcategory['title'];
		$position="<p class='red fwb'>".$pcategory['title']."</p>><p class='red fwb'>".$category['title']."</p>";
		$this->assign('position',$position);

		$menulist=$this->AllMenu();
		$this->assign('footermenu',$menulist);
		
		/*调取“关于”的二级分类*/
		$category2=M("channel")->where("pid='10'")->select();
		$this->assign('category2',$category2);

		//联系我们-banner图
		$ad_banner=get_ad(8);
		$this->assign('ad_banner',$ad_banner);  
		
		//联系我们-微信二维码图1
		$ad_weixin1=get_ad(9);
		$this->assign('ad_weixin1',$ad_weixin1); 		
		
		//联系我们-微信二维码图2
		$ad_weixin2=get_ad(10);
		$this->assign('ad_weixin2',$ad_weixin2); 
		
		$this->assign('category',$category);
		$this->display($category['template_index']);
		//$this->display();
		
	}	

		  
	/**文章模板(频道模板)**/
   public function category(){
		/* 左侧菜单 */
		$menu=R('index/menulist');
		$this->assign('categoryq', $menu);

		/*栏目页统计代码实现，tag=2*/
		if(1==C('IP_TONGJI')){
		$record=IpLookup("",2,$name); 
		}

		/* 分类信息id */
		$catid= I('get.catid');
		//分类一维数组
		$category=M("category")->where("id='$catid'")->find();

		/*设置网站标题，一维数组*/
		$pid=$category['pid'];
		$pcategory=M("category")->where("id='$pid'")->find();
		
		$this->meta_title = $category['title']."-".$pcategory['title'];
		$position="<p class='red fwb'>".$pcategory['title']."</p>><p class='red fwb'>".$category['title']."</p>";
		$this->assign('position',$position);

		$menulist=$this->AllMenu();
		$this->assign('footermenu',$menulist);

		//获取文章列表
		$listinfos=M("article")->where("category_id='".$catid."' and status ='1'")->order("id desc")->select();
		$this->assign('listinfos',$listinfos);
		
		$category2=M("channel")->where("pid='7'")->select();
		$this->assign('category2',$category2);
		
		$joinbanner=get_ad(13);
		$this->assign('joinbanner',$joinbanner);//加入我们-banner图	
	
		//翻页
		$count= M("article")->where("category_id='".$catid."' and status ='1'")->count();
		$Page= new \Think\Page($count,15);
		$Page->setConfig('prev','上一页');
		$Page->setConfig('next','下一页');
		$Page->setConfig('first','第一页');
		$Page->setConfig('last','尾页');
		$Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
		$show= $Page->show_diy();
		$listinfos=M("article")->where("category_id='".$catid."' and status ='1'")->order("level ASC,id desc")->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('listinfos',$listinfos);// 赋值数据集
		$this->assign('page',$show);    
		
			
		$this->display($category['template_index']);
		
	}	
	/**文章模板(列表模板)**/
   public function lists(){
		/*栏目页统计代码实现，tag=2*/
		if(1==C('IP_TONGJI')){
		$record=IpLookup("",2,$name); 
		}

		$catid= I('get.catid');/* 文章所属栏目catid*/
		$this->assign('catid',$catid);	//当前栏目id		
		//分类一维数组
		$category=M("category")->where("id='$catid'")->find();
		
		/*设置网站标题，一维数组*/
		$pid=$category['pid'];
		$pcategory=M("category")->where("id='$pid'")->find();
		$this->meta_title = $category['title']."-".$pcategory['title'];
		$position="<p class='red fwb'>".$pcategory['title']."</p>><p class='red fwb'>".$category['title']."</p>";
		$this->assign('position',$position);

		$menulist=$this->AllMenu();
		$this->assign('footermenu',$menulist);
		//父级id下的子id
		$sonid=M('category')->where("pid='$pid' and id!='$catid'")->getField('id');
		$this->assign('sonid',$sonid);
		//获取文章列表
		//$listinfos=M("article")->where("category_id='".$catid."' and status ='1'")->order("level ASC,id desc")->select();
		//$this->assign('listinfos',$listinfos);
		
		$category2=M("channel")->where("pid='7'")->select();
		$this->assign('category2',$category2);
		
		//翻页
		$count= M("article")->where("category_id='".$catid."' and status ='1'")->count();
		$Page= new \Think\Page($count,15);
		$Page->setConfig('prev','上一页');
		$Page->setConfig('next','下一页');
		$Page->setConfig('first','第一页');
		$Page->setConfig('last','尾页');
		$Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
		$show= $Page->show_diy();
		$listinfos=M("article")->where("category_id='".$catid."' and status ='1'")->order("level ASC,id desc")->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('listinfos',$listinfos);// 赋值数据集
		$this->assign('page',$show);    
		//翻页
		$count= M("article")->where("category_id='".$sonid."' and status ='1'")->count();
		$Page= new \Think\Page($count,15);
		$Page->setConfig('prev','上一页');
		$Page->setConfig('next','下一页');
		$Page->setConfig('first','第一页');
		$Page->setConfig('last','尾页');
		$Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
		$show= $Page->show_diy();
		$nextlistinfos=M("article")->where("category_id='".$sonid."' and status ='1'")->order("level ASC,id desc")->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('nextlistinfos',$nextlistinfos);// 赋值数据集
		$this->assign('page',$show);
		
		if($catid==197){
			$this->display('Service/lists_video');
		}else{
			$this->display($category['template_lists']);
		}
	}	

	/**文章模板(详情模板)**/
   public function shows(){

		/*栏目页统计代码实现，tag=2*/
		if(1==C('IP_TONGJI')){
		$record=IpLookup("",2,$name); 
		}

		$id= I('get.id');/* 文章id */
		$catid= I('get.catid');/* 文章所属栏目id */
		$this->assign('id',$id);	//当前文章id
		$this->assign('catid',$catid);	//当前栏目id

		//分类一维数组
		$category=M("category")->where("id='$catid'")->find();

		/*设置网站标题，一维数组*/
		$pid=$category['pid'];
		$pcategory=M("category")->where("id='$pid'")->find();
		//获取文章信息
		$infos=M("article")->where("id='".$id."' and status ='1'")->order("id desc")->find();
		$this->assign('infos',$infos);
		
		$this->meta_title = $infos['title'].'-'.$category['title']."-".$pcategory['title'];
		$position="<p class='red fwb'>".$pcategory['title']."</p>><p class='red fwb'>".$category['title']."</p>";
		$this->assign('position',$position);

		$menulist=$this->AllMenu();
		$this->assign('footermenu',$menulist);


		

		if($catid==197){
			$this->display('Service/show_video');
			
		}else{
			$this->display($category['template_lists']);
		}
		
	}	
	
	
	/**
	 * 表单提交
	 * 我要加盟
	 */
    public function form_join(){
		   
		if(isset($_POST['dosubmit'])){
			$FormJoin=M("FormJoin");
			$data = $_POST["info"];
			$data["create_time"]=NOW_TIME;
			if($FormJoin->add($data)){
				$this->assign("waitSecond","3");	
				$this->success('新增成功','/Weixin/service/page/catid/178');
			}else{
				$this->error('新增失败');
			}
		}	
		$this->display();
	
    }	

	/**
	 * 表单提交
	 * 我要购买
	 */
    public function form_buy(){
		   
		if(isset($_POST['dosubmit'])){
			$FormBuy=M("FormBuy");
			$data = $_POST["info"];
			$data["create_time"]=NOW_TIME;
			if($FormBuy->add($data)){
				$this->assign("waitSecond","3");	
				$this->success('新增成功','/Weixin/service/page/catid/182');
			}else{
				$this->error('新增失败');
			}
		}	
		$this->display();
	
    }	


}
