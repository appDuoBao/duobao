<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2014 www@ewangtx.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: ew_xiaoxiao <www@ewangtx.com>
// +----------------------------------------------------------------------

namespace Control\Model;
use Think\Model;
/**
 * 配置模型
 * @author ew_xiaoxiao <www@ewangtx.com>
 */

class OrderModel extends Model {
    protected $_validate = array(
        array('orderid','require','订单号必须填写'), 
        array('assistant','require','操作人必须填写'), 
		array('address','require','详细地址必须填写'), 
		array('phone','require','联系电话必须填写'), 
		array('realname','require','收货人姓名必须填写'), 
		array('vname','va_vname','请填写正确的会员账号','1','callback'), 
    );

    /* 自动完成规则 */
    protected $_auto = array(
        array('orderid', 'htmlspecialchars', self::MODEL_UPDATE, 'function'),
		array('orderid', 'htmlspecialchars', self::MODEL_UPDATE, 'function'),
		array('create_time', NOW_TIME, self::MODEL_INSERT),
		array('update_time', NOW_TIME, self::MODEL_UPDATE),
        //array('status', '1', self::MODEL_INSERT),
		array('uid', 'changeuid', self::MODEL_BOTH, 'callback'),
		array('ispay', 'changeispay', self::MODEL_BOTH, 'callback'),
		array('status', 'changestatus', self::MODEL_BOTH, 'callback'),
    );



	public function changeispay(){
		$ispay = $_POST['ispay'];
		$status = $_POST['status'];
		$id = $_POST['id'];
		if($ispay=='-1'){
			return $ispay;
		}else{
			if($status=='-1'){//待付款
				return '1';
			}elseif($status=='4'){	//已取消
				return $ispay;
			}else{
				$user = M('Member');
				$walletrecord = M('Walletrecord');
				$disbut = M('Disbut');
				$orderinfo = M('Order')->where(array('id' => $id))->find();
				$ishave = $disbut->getFieldByOrderid($orderinfo['orderid'],'id');
				$disbut_open = M('Config')->getFieldByName('DISTRIBUTION','value');
				if(!$ishave && $disbut_open){
					$oneuid = $user->getFieldByUid($orderinfo['uid'],'puid');
					if($oneuid){
						$oldmap['uid'] = $orderinfo['uid'];
						$oldmap['id'] = array('neq',$orderinfo['id']);
						$oldorder = M('Order')->where($oldmap)->find();

						$disbfb = M('Config')->getFieldByName('DISTRIBUTION_PTC','value')/100;//分销金额所占订单百分比


						$dismoney = sprintf("%.2f", $disbfb*$orderinfo['pricetotal']);//分销总金额
						$onelv = M('Config')->getFieldByName('DISTRIBUTOR_ONE','value')/100;//获取一级分销比
						$twolv = M('Config')->getFieldByName('DISTRIBUTOR_TWO','value')/100;//获取二级分销比
						$threelv = M('Config')->getFieldByName('DISTRIBUTOR_THREE','value')/100;//获取三级分销比

						$twouid = $user->getFieldByUid($oneuid,'puid');
						if($twouid){

							$threeuid = $user->getFieldByUid($twouid,'puid');

						}else{

							$threeuid = '';
						}

						//1级分销流程
						if($onelv>0){

							$onemoney = sprintf("%.2f", $dismoney*$onelv);
							$onedata['uid'] = $oneuid;
							$onedata['money'] = $onemoney;
							$onedata['status'] = 1;
							$onedata['cometype'] = 2;
							$onedata['comeorder'] = $orderinfo['orderid'];
							$onedata['changetime'] = NOW_TIME;
							$onedata['note'] = '分销佣金';
							$walletrecordid = $walletrecord->add($onedata);
							if($walletrecordid){
								$oldmoney =	$user->getFieldByUid($oneuid,'account');
								$newmoney = $oldmoney+$onemoney;
								$user->account = $newmoney;
								$user->where(array('uid' => $oneuid))->save();
							}
						}

						//2级分销流程
						if($twouid && $twolv>0){
							$twomoney = sprintf("%.2f", $dismoney*$twolv);
							$twodata['uid'] = $twouid;
							$twodata['money'] = $twomoney;
							$twodata['status'] = 1;
							$twodata['cometype'] = 2;
							$twodata['comeorder'] = $orderinfo['orderid'];
							$twodata['changetime'] = NOW_TIME;
							$twodata['note'] = '分销佣金';
							$walletrecordid = $walletrecord->add($twodata);
							if($walletrecordid){
								$oldmoney =	$user->getFieldByUid($twouid,'account');
								$newmoney = $oldmoney+$twomoney;
								$user->account = $newmoney;
								$user->where(array('uid' => $twouid))->save();
							}
						}
						//3级分销流程
						if($threeuid && $threelv>0){
							$threemoney = sprintf("%.2f", $dismoney*$threelv);
							$threedata['uid'] = $threeuid;
							$threedata['money'] = $threemoney;
							$threedata['status'] = 1;
							$threedata['cometype'] = 2;
							$threedata['comeorder'] = $orderinfo['orderid'];
							$threedata['changetime'] = NOW_TIME;
							$threedata['note'] = '分销佣金';
							$walletrecordid = $walletrecord->add($threedata);
							if($walletrecordid){
								$oldmoney =	$user->getFieldByUid($threeuid,'account');
								$newmoney = $oldmoney+$threemoney;
								$user->account = $newmoney;
								$user->where(array('uid' => $threeuid))->save();
							}
						}

						$disbutdata['uid']=$orderinfo['uid'];
						$disbutdata['oneuid']=$oneuid;
						$disbutdata['twouid']=$twouid;
						$disbutdata['threeuid']=$threeuid;
						$disbutdata['onemoney']=$onemoney;
						$disbutdata['twomoney']=$twomoney;
						$disbutdata['threemoney']=$threemoney;
						$disbutdata['createtime']=NOW_TIME;
						$disbutdata['orderid']=$orderinfo['orderid'];
						$disbutdata['pricetotal']=$orderinfo['pricetotal'];
						$disbutdata['total']=$orderinfo['total'];
						$ifdisbut = $disbut->add($disbutdata);

					}

				}
				return '2';
			}
		}

	}
	public function changestatus(){
      $ispay = $_POST['ispay'];
	  $status = $_POST['status'];
	  $id = $_POST['id'];
	  if($ispay=='-1'){
		  if($status=='-1'){
			  return '1';
		  }else{

			  return $status;
		  }
		
	  }else{

		return $status;
	  }
	
    }
	public function changeuid(){
      $vname = $_POST['vname'];
	  $user = M('Member');
	 $uid = $user->getFieldByNickname($vname,'uid');
	return $uid;
    }	
	
public function va_vname(){
      $vname = $_POST['vname'];
	  $user = M('Member');
	 $uid = $user->getFieldByNickname($vname,'uid');
	 if($uid){
		 return true;
		 }else{
		return false;	 
			 }
	
    }
  public function lists(){
        $map    = array('status' => 1);
        $data   = $this->where($map)->select();
        
        $config = array();
        if($data && is_array($data)){
            foreach ($data as $value) {
                $config[$value['name']] = $this->parse($value['type'], $value['value']);
            }
        }
        return $config;
    }
  private function parse($type, $value){
        switch ($type) {
            case 3: //解析数组
                $array = preg_split('/[,;\r\n]+/', trim($value, ",;\r\n"));
                if(strpos($value,':')){
                    $value  = array();
                    foreach ($array as $val) {
                        list($k, $v) = explode(':', $val);
                        $value[$k]   = $v;
                    }
                }else{
                    $value =    $array;
                }
                break;
        }
        return $value;
    }


}
