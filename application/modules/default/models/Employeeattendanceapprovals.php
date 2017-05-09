<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Employeeattendanceapprovals
 *
 * @author kumarsar
 */
class Default_Model_Employeeattendanceapprovals extends Zend_Db_Table_Abstract {
    protected $_name = 'payroll_attendancerequest';
    
	
	
	public function getAvailableLeaves($loginUserId)
	{
	 	$select = $this->select()
    					   ->setIntegrityCheck(false)	
                           ->from(array('e'=>'main_employeeleaves'),array('leavelimit'=>'e.emp_leave_limit','remainingleaves'=>new Zend_Db_Expr('e.emp_leave_limit - e.used_leaves')))
						   ->where('e.user_id='.$loginUserId.' AND e.alloted_year = now() AND e.isactive = 1');  		   					   				
		return $this->fetchAll($select)->toArray();   
	
	}
	
	
	public function getsinglePendingLeavesData($id)
	{
		$result =  $this->select()
    				->setIntegrityCheck(false) 	
    				->from(array('l'=>'main_leaverequest'),array('l.*'))
 	  				->where("l.isactive = 1 AND l.id = ".$id);
	
    	return $this->fetchAll($result)->toArray();
	}
	
	public function getUserLeavesData($id)
	{
		$result =  $this->select()
    				->setIntegrityCheck(false) 	
    				->from(array('l'=>'main_leaverequest'),array('l.*'))
 	  				->where("l.isactive = 1 AND l.user_id = ".$id);
		
    	return $this->fetchAll($result)->toArray();
	}
	
	public function getUserApprovedOrPendingLeavesData($id)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
       
		
		$query = "SELECT `l`.*,IF(l.leavestatus = 'Approved', 'A', 'P') as status FROM `main_leaverequest` AS `l` WHERE (l.isactive = 1 AND l.user_id = '$id' and l.leavestatus IN(1,2))";
		
        $result = $db->query($query)->fetchAll();
	    return $result;
	}
	
	public function getManagerApprovedOrPendingLeavesData($id)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
       
		
		$query = "SELECT `l`.*,IF(l.leavestatus = 'Approved', 'A', 'P') as status,u.userfullname
				 FROM `main_leaverequest` AS `l` left join main_users u on u.id=l.user_id 
				 WHERE (l.isactive = 1 AND l.rep_mang_id = '$id' and l.leavestatus IN(1,2))";
		
        $result = $db->query($query)->fetchAll();
	    return $result;
	}
	
	public function getReportingManagerId($id)
	{
	    $result =  $this->select()
    				->setIntegrityCheck(false) 	
    				->from(array('l'=>'main_employees_summary'),array('repmanager'=>'l.reporting_manager','managername'=>'l.reporting_manager_name'))
 	  				->where("l.isactive = 1 AND l.user_id = ".$id);
	
    	return $this->fetchAll($result)->toArray();
	}
	public function updateemployeeattendancerequest($emp_id,$status){
            $db = Zend_Db_Table::getDefaultAdapter();
		$db->query("update payroll_attendancerequest  set status = ".$status." where emp_id = ".$emp_id."  AND isactive = 1 ");		
        }
	public function SaveorUpdateLeaveRequest($data, $where)
	{
	    if($where != '')
		{
			$this->update($data, $where);
			return 'update';
		}
		else
		{
			$this->insert($data);
			$id=$this->getAdapter()->lastInsertId('main_leaverequest');
			return $id;
		}
	}
	public function addtbeventlog($id,$biometricid,$employeeid,$from_date,$to_date, $intime , $outtime)
        {
              $startTime = strtotime($from_date);
              $endTime = strtotime($to_date);
                $db = Zend_Db_Table::getDefaultAdapter();
             $sql="INSERT INTO `tb_event_log` (`nEventLogIdn`, `nDateTime`, `nReaderIdn`, `nEventIdn`, `nUserID`, `nIsLog`, `nTNAEvent`, `nIsUseTA`, `nType`) VALUES ";
             
             while($startTime <= $endTime) {
                    $actualinTime = strtotime(date("Y-m-d",$startTime) ." ". $intime);
                    $actualoutTime = strtotime(date("Y-m-d",$startTime) ." ". $outtime);
                    $sql .= "(-1,".$actualinTime.",59517,55,'".$biometricid."',1,0,'".$id."',0),";
                    $sql .= "(-1,".$actualoutTime.",59517,55,'".$biometricid."',1,1,'".$id."',0),";
                   $startTime = strtotime('+1 day', $startTime);
                }  
                
                $sql = rtrim($sql, ",");
                //print_r($sql);exit;
                $result=$db->query($sql);
            
return $result;
        }

        public function getLeaveStatusHistory($sort, $by, $pageNo, $perPage,$searchQuery,$queryflag='',$loggedinuser,$managerstring='')
	{	
	    $auth = Zend_Auth::getInstance();
			if($auth->hasIdentity()){
				$loginUserId = $auth->getStorage()->read()->id;
		}  
		if($loggedinuser == '') 
		 $loggedinuser = $loginUserId;
		 
		/* Removing isactive checking from configuration table */ 
		if($managerstring !='')
		{
		  
		  $where = "l.isactive = 1 ";
		}  
		else 
        {		
	      
		  $where = "l.isactive = 1 AND l.user_id = ".$loggedinuser." ";
		}  
		if($queryflag !='')
		{
		   if($queryflag == 'pending')
		   {
		     $where .=" AND l.leavestatus = 1 ";
		   }
		   else if($queryflag == 'approved')
		   {
		     $where .=" AND l.leavestatus = 2 ";
		   }
		   else if($queryflag == 'cancel')
		   {
		     $where .=" AND l.leavestatus = 4 ";
		   }
		   else if($queryflag == 'rejected')
		   {
		     $where .=" AND l.leavestatus = 3 ";
		   }
		
		}else
		{
		  $where .=" AND l.leavestatus = 2 ";
		}
		
			
		if($searchQuery)
			$where .= " AND ".$searchQuery;
		$db = Zend_Db_Table::getDefaultAdapter();		
		
		$leaveStatusData = $this->select()
    					   ->setIntegrityCheck(false)	
                           ->from(array('l'=>'payroll_attendancerequest'),
						          array( 'l.*','from_date'=>'DATE_FORMAT(l.from_date,"'.DATEFORMAT_MYSQL.'")',
								         'to_date'=>'DATE_FORMAT(l.to_date,"'.DATEFORMAT_MYSQL.'")',
										 'applieddate'=>'DATE_FORMAT(l.createddate,"'.DATEFORMAT_MYSQL.'")',
                                         'leaveday'=>'if(l.leaveday = 1,"Full Day","Half Day")',
						          		 new Zend_Db_Expr("CASE WHEN l.leavestatus=2 and CURDATE()>=l.from_date THEN 'no' WHEN l.leavestatus=1 THEN 'yes' WHEN l.leavestatus IN (3,4) THEN 'no' ELSE 'yes' END as approved_cancel_flag "), 										 
								       ))
						   ->joinLeft(array('et'=>'main_employeeleavetypes'), 'et.id=l.leavetypeid',array('leavetype'=>'et.leavetype'))	
                           ->joinLeft(array('u'=>'main_users'), 'u.id=l.rep_mang_id',array('reportingmanagername'=>'u.userfullname'))
                           ->joinLeft(array('mu'=>'main_users'), 'mu.id=l.user_id',array('employeename'=>'mu.userfullname'))						                 			   						   
						   ->where($where)
    					   ->order("$by $sort") 
    					   ->limitPage($pageNo, $perPage);
		
                                      
		return $leaveStatusData;
		
	}
	
	
	public function getEmployeeLeaveRequest($sort, $by, $pageNo, $perPage,$searchQuery,$loginUserId)
	{	
		//$where = "l.isactive = 1 AND l.leavestatus=1 AND u.isactive=1 AND l.rep_mang_id=".$loginUserId." ";
		$where = "l.status=0 AND l.isactive = 1  AND u.isactive=1";
		//AND l.rep_mang_id=".$loginUserId."
		if($searchQuery)
			$where .= " AND ".$searchQuery;
		$db = Zend_Db_Table::getDefaultAdapter();		
		
		$employeeleaveData = $this->select()
    					   ->setIntegrityCheck(false)
                           ->from(array('l'=>'payroll_attendancerequest'),
						          array( 'l.*',
										 'applieddate'=>'DATE_FORMAT(l.createddate,"'.DATEFORMAT_MYSQL.'")',
                                         										 
								       ))
						   
						   ->joinLeft(array('u'=>'main_users'), 'u.id=l.emp_id ',array('userfullname'=>'u.userfullname'))
                        ->joinLeft(array('pa'=>'payroll_attendancerequesttypes'), 'pa.id=l.reason ',array('reason'=>'pa.name'))
						   ->where($where)
    					   ->order("$by $sort") 
    					   ->limitPage($pageNo, $perPage);
		 //echo $employeeleaveData;exit;
		return $employeeleaveData;       		
	}
//	'from_date'=>'DATE_FORMAT(l.from_date,"'.DATEFORMAT_MYSQL.'")',
//								         'to_date'=>'DATE_FORMAT(l.to_date,"'.DATEFORMAT_MYSQL.'")',
	public function updateemployeeleaves($appliedleavescount,$employeeid)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		$db->query("update main_employeeleaves  set used_leaves = used_leaves+".$appliedleavescount." where user_id = ".$employeeid." AND alloted_year = year(now()) AND isactive = 1 ");		
	
	}
	
	public function updatecancelledemployeeleaves($appliedleavescount,$employeeid)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		$db->query("update main_employeeleaves  set used_leaves = used_leaves-".$appliedleavescount." where user_id = ".$employeeid." AND alloted_year = year(now()) AND isactive = 1 ");		
	
	}
	
	public function getUserID($id)
    {
    	$result =  $this->select()
    				->setIntegrityCheck(false) 	
    				->from(array('l'=>'payroll_attendancerequest'),array('l.emp_id'))
 	  				->where("l.isactive = 1 AND l.id = ".$id);
	
    	return $this->fetchAll($result)->toArray();
    }
	
	public function getLeaveRequestDetails($id)
    {
    	$result =  $this->select()
    				->setIntegrityCheck(false) 	
    				->from(array('l'=>'payroll_attendancerequest'),array('l.*'))
 	  				->where("l.isactive = 1 AND l.id = ".$id);
	
    	return $this->fetchAll($result)->toArray();
    }
	
	public function checkdateexists($from_date, $to_date,$loginUserId)
	{
	    $db = Zend_Db_Table::getDefaultAdapter();
        
		
		$query = "select count(l.id) as dateexist from main_leaverequest l where l.user_id=".$loginUserId." and l.leavestatus IN(1,2) and l.isactive = 1
        and (l.from_date between '".$from_date."' and '".$to_date."' OR l.to_date between '".$from_date."' and '".$to_date."' )";
		
        $result = $db->query($query)->fetchAll();
	    return $result;
	
	}
	
	/* This function is common to manager employee leaves, employee leaves , approved,cancel,pending and rejected leaves
       Here differentiation is done based on objname. 
    */	   
	public function getGrid($sort,$by,$perPage,$pageNo,$searchData,$call,$dashboardcall,$objName,$queryflag,$unitId='',$statusidstring='')
	{	
        $searchQuery = '';
        $searchArray = array();
        $data = array();
		
		if($searchData != '' && $searchData!='undefined')
			{
				$searchValues = json_decode($searchData);
				foreach($searchValues as $key => $val)
				{
							$searchQuery .= " ".$key." like '%".$val."%' AND ";
                           $searchArray[$key] = $val;
				}
				$searchQuery = rtrim($searchQuery," AND");					
			}
			
		$objName = 'employeeattendanceapprovals';
		
		$tableFields = array('action'=>'Action','userfullname' => 'Employee','reason' => 'Request Type',
                    'from_date' => 'From','to_date' => 'To','in_time'=>'In Time','out_time'=>'Out Time','applieddate' => 'Applied On');
		
		$tablecontent = $this->getEmployeeLeaveRequest($sort, $by, $pageNo, $perPage,$searchQuery);
		
		$dataTmp = array(
			'sort' => $sort,
			'by' => $by,
			'pageNo' => $pageNo,
			'perPage' => $perPage,				
			'tablecontent' => $tablecontent,
			'objectname' => $objName,
			'extra' => array(),
			'tableheader' => $tableFields,
			'jsGridFnName' => 'getAjaxgridData',
			'jsFillFnName' => '',
			'searchArray' => $searchArray,
			'add' =>'add',
			'call'=>$call,
			'dashboardcall'=>$dashboardcall,
		);
		return $dataTmp;
	}
	
	public function getUsersAppliedLeaves($userId)
	{
		$result =  $this->select()
    				->setIntegrityCheck(false) 	
    				->from(array('l'=>'main_leaverequest'),array('l.from_date','l.to_date'))
 	  				->where("l.isactive = 1 AND l.user_id = ".$userId." AND l.leavestatus IN(1,2)");
		
    	return $this->fetchAll($result)->toArray();
	}
	
	public function checkLeaveExists($applied_from_date,$applied_to_date,$from_date, $to_date,$loginUserId)
	{
	    $db = Zend_Db_Table::getDefaultAdapter();
        
		
		$query = "select count(l.id) as leaveexist from main_leaverequest l where l.user_id=".$loginUserId." and l.leavestatus IN(1,2) and l.isactive = 1
        and ('".$from_date."' between '".$applied_from_date."' and '".$applied_to_date."' OR '".$to_date."' between '".$applied_from_date."' and '".$applied_to_date."' )";
		
        $result = $db->query($query)->fetchAll();
	    return $result;
	
	}
	
public function getLeaveDetails($id)
	{
		$result =  $this->select()
    				->setIntegrityCheck(false) 	
    				->from(array('l'=>'main_leaverequest_summary'),array('l.*'))
 	  				->where("l.isactive = 1 AND l.leave_req_id = ".$id." ");
					
    	return $this->fetchAll($result)->toArray();
	}
	
	public function getLeavesCount($userid,$status='') {
		$db = Zend_Db_Table::getDefaultAdapter();
        $leavestatus = "";
        if($status != '')
            $leavestatus = " and l.leavestatus = $status ";
        
        $query = "select count(*) cnt from main_leaverequest l 
                  where l.isactive = 1 and l.user_id = $userid ".$leavestatus;
        $result = $db->query($query)->fetch();
        return $result['cnt'];
		
	}
}
