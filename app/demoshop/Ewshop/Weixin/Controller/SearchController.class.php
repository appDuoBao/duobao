<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 www@ewangtx.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ew_xiaoxiao <www@ewangtx.com> <http://www.ewangtx.com>
// +----------------------------------------------------------------------

namespace Weixin\Controller;

/**
 * 产品搜索控制器
 * 产品模型搜索
 */
class SearchController extends HomeController {

	/* 产品搜索 */
	public function index(){
		$keyword= I('words');//获取分类的英文名称
		$this->assign('keyword',$keyword);//关键词

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

		//关键词筛选
		$map['title|name|description']  = array('like','%'.$keyword.'%');

		$map1 = $map;

		//商品分类
		$category_list=M('Document')->field('category_id')->group('category_id')->where($map1)->select();//商品分类
		foreach($category_list as $k=>$v){
			$category_list[$k]['title'] = M('Category')->where("id = '{$v['category_id']}'")->getField('title');
			$category_list[$k]['name'] = M('Category')->where("id = '{$v['category_id']}'")->getField('name');
		}
		$this->assign('category_list',$category_list);//商品分类


		//分类筛选
		$cur_category = I('get.category');
		$this->assign('cur_category',$cur_category);//当前分类

		$category = M('Category')->where("name = '$cur_category'")->find();//当前栏目信息
		if($category){
			$map['category_id'] = $category['id'];
			$map['status']=1;
			$this->assign('category', $category);// 栏目信息
		}

		$count=M('Document')->where($map)->count();
		$per_page = 15;//每页显示的数量
		$Page= new \Think\Page($count,$per_page);
		$Page->lastSuffix = false;
		$Page->setConfig('prev','上一页');
		$Page->setConfig('next','下一页');
		$Page->setConfig('first','首页');
		$Page->setConfig('last','尾页');
		$Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
		$show= $Page->show();
		$list=M('Document')->where($map)->order($listsort)->limit($Page->firstRow.','.$Page->listRows)->select();
		foreach($list as $k => $v){
			$list[$k]['marketprice'] = M('document_product')->where("id = '{$v['id']}'")->getField('marketprice');//市场价
		}
		$total_page = ceil($count/$per_page);
		$this->assign('total_page', $total_page);//总页数
		$this->assign('list',$list);// 赋值数据集
		$this->assign('page',$show);

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

		$this->meta_title = $keyword.'的搜索结果';
		$this->display();
	}


		/* 产品搜索页 */
	public function lists_video(){
		$keyword= I('words');//获取分类的英文名称
		$key=I('get.order');
		$sort=I('get.sort');  
		if(isset($key)){
			if($key=="1"){ $listsort="view"." ".$sort;}  
			if($key=="2"){ $listsort="id"." ".$sort;} 
			if($key=="3"){  $listsort="price"." ".$sort;} 
			if($key=="4"){  $listsort="sale"." ".$sort;}  	
		} 
		if(empty($key)){$key="1";
			$see="asc";
			$order="view";
			$sort="asc";
			$listsort=$order." ".$sort;			
		}
		
		if($sort=="asc"){$see="desc";}
		if($sort=="desc"){$see="asc";}
		$this->assign('see',$see);
		$this->assign('order',$key);
		$this->assign('value',$sort);
		if($keyword){			
			$map = " and `title` like '%".$keyword."%'";
		}
		$count=M('Article')->where("category_id=197 ".$map)->count();
		$Page= new \Think\Page($count,12);
		$Page->setConfig('prev','上一页');
		$Page->setConfig('next','下一页');
		$Page->setConfig('first','第一页');
		$Page->setConfig('last','尾页');
		$Page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% %HEADER%');
		$show= $Page->show();    
		$list=M('Article')->where("category_id=197 ".$map)->order($listsort)->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('listinfos',$list);// 赋值数据集
		$this->assign('page',$show);
	
		$this->assign('searchlist', $list);
		$this->assign('keyword', $keyword);
	
		/* 底部分类调用*/
		$menulist=R('Service/AllMenu');
		$this->assign('footermenu',$menulist);
		
		$this->meta_title = $keyword.'的搜索结果';
		$this->display(Search/lists_video);
	}

}