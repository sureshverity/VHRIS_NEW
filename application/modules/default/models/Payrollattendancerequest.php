<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Payrollattendancerequest
 *
 * @author kumarsar
 */
class Default_Model_Payrollattendancerequest extends Zend_Db_Table_Abstract {
     protected $_name = 'payroll_attendancerequest';
    protected $_primary = 'id';
	
	public function getServiceDeskDepartmentData($sort, $by, $pageNo, $perPage,$searchQuery)
	{
		$where = "e.isactive = 1";
		if($searchQuery)
			$where .= " AND ".$searchQuery;
		$db = Zend_Db_Table::getDefaultAdapter();		
		$servicedeskDepartmentData = $this->select()
    					   ->setIntegrityCheck(false)	
                           ->from(array('e'=>'main_employees'),array('u.id','date_of_joining'=>'DATE_FORMAT(date_of_joining,"'.DATEFORMAT_MYSQL.'")'))
                          ->join(array('u'=>'main_users'), 'u.id=e.user_id',array('employeeid' => 'u.employeeid','userfullname' => 'u.userfullname','emailaddress' => 'u.emailaddress'))  
                         
                        ->where($where)
    					   ->order("$by $sort") 
    					   ->limitPage($pageNo, $perPage);
		
      // echo $servicedeskDepartmentData;       exit;
		return $servicedeskDepartmentData;       		
	}
	
	public function getGrid($sort,$by,$perPage,$pageNo,$searchData,$call,$dashboardcall,$a='',$b='',$c='',$d='')
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
			
		$objName = 'payrollattendancerequest';
		
		$tableFields = array('action'=>'Action','employeeid' => 'Employee ID','userfullname' => 'User Name','emailaddress' =>'Email','date_of_joining' =>'Date Of Joining');
		
		$tablecontent = $this->getServiceDeskDepartmentData($sort, $by, $pageNo, $perPage,$searchQuery);     
		
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
	public function getEmployeeID($id)
	{
                $select = $this->select()
                            ->setIntegrityCheck(false)
                            ->from(array('sd'=>'main_employees'),array('sd.biometricId','sd.department_id'))
                            ->where('sd.isactive = 1 AND sd.user_id='.$id.' ');

               return $this->fetchAll($select)->toArray();

	}
        public function getRequestTypes() {
            $select = $this->select()
                            ->setIntegrityCheck(false)
                            ->from(array('sd'=>'payroll_attendancerequesttypes'),array('sd.id','sd.name'))
                            ->where('sd.isactive = 1');

               return $this->fetchAll($select)->toArray();
        }
         public function getRequestTypesbyId($id) {
            $select = $this->select()
                            ->setIntegrityCheck(false)
                            ->from(array('sd'=>'payroll_attendancerequesttypes'),array('sd.id','sd.name'))
                            ->where('sd.isactive = 1 AND sd.id='.$id.'');

               return $this->fetchAll($select)->toArray();
        }
	public function getEmployeeattendance($id,$startdate,$enddate)
	{
           
           $start= strtotime($startdate);
            $end = strtotime($enddate);
            $db = Zend_Db_Table::getDefaultAdapter();                          
            $query = "SELECT `nEventLogIdn`,DATE(FROM_UNIXTIME(`nDateTime`)) as Date,`nReaderIdn`,`nEventIdn`,`nUserID`,`nIsLog`,`nTNAEvent`,`nIsUseTA`,`nType` FROM `tb_event_log` Where nEventIdn = 55 and nUserID='$id' and nDateTime>='$start' and  nDateTime<='$end' group by DATE(FROM_UNIXTIME(`nDateTime`)),nUserID" ;              
            $result = $db->query($query);
            return $response = $result->fetchAll();
	}
        public function getEmployeeLeaves($id,$startdate,$enddate)
        {
            
             $select = $this->select()
						->setIntegrityCheck(false)
						->from(array('sd'=>'main_leaverequest'),array('sd.from_date','sd.to_date'))
					    ->where('sd.isactive = 1 AND sd.leavestatus="Approved" and sd.from_date>="'.$startdate.'" and sd.from_date<="'.$enddate.'" and sd.user_id='.$id);
					   
		return $this->fetchAll($select)->toArray();
        }
        public function getHolidaylist($startdate,$enddate)
        {
             $select = $this->select()
						->setIntegrityCheck(false)
						->from(array('sd'=>'main_holidaydates'),array('sd.holidaydate'))
					    ->where('sd.isactive = 1 and sd.holidaydate >="'.$startdate.'" and sd.holidaydate<="'.$enddate.'"');
					   
		return $this->fetchAll($select)->toArray();
        }
        public function getWeekend($department_id)
        {
             $select = $this->select()
						->setIntegrityCheck(false)
						->from(array('sd'=>'main_leavemanagement'),array('sd.weekend_startday','sd.weekend_endday'))
					    ->where('sd.department_id ="'.$department_id.'"');					   
		return $this->fetchAll($select)->toArray();
        }

        public function getServiceDeskDepartmentDatabyIDs($id)
	{
		
	    $select = $this->select()
						->setIntegrityCheck(false)
						->from(array('sd'=>'main_sd_depts'),array('sd.service_desk_name'))
					    ->where('sd.isactive = 1 AND sd.id IN ('.$id.') ');
					    
		return $this->fetchAll($select)->toArray();
	
	}
	
	public function getEmpcomponentData()
	{
	    $select = $this->select()
						->setIntegrityCheck(false)
						->from(array('sd'=>'payroll_empcomponents'),array('sd.*'));
					   // ->where('sd.isactive = 1');
		return $this->fetchAll($select)->toArray();
	
	}
	
	public function SaveorUpdateEmpcomponentData($data, $where)
	{
	    if($where != ''){
			$this->update($data, $where);
			return 'update';
		} else {
			$this->insert($data);
			$id=$this->getAdapter()->lastInsertId('payroll_empcomponents');
			return $id;
		}
		
	
	}
    
    /**
     * This function gives request types based on service_desk id.
     * @param integer $service_desk_id  = id of service desk.
     * @return array Array of service request types and their ids.
     */
    public function getRequestsById($service_desk_id)
    {
        $options = array();
        if($service_desk_id != '')
        {
            $db = Zend_Db_Table::getDefaultAdapter();
            $query = "select id,service_request_name from main_sd_reqtypes "
                    . "where isactive = 1 and service_desk_id = ".$service_desk_id." order by service_request_name asc";
            $result = $db->query($query);
            $options = $result->fetchAll();
        }
        return $options;
    }
    
	public function checkDuplicateCategoryName($categoryName)
	{
		$db = Zend_Db_Table::getDefaultAdapter();
		$qry = "select count(*) as count from main_sd_depts sd where sd.service_desk_name='".$categoryName."' AND sd.isactive=1 ";
		$res = $db->query($qry)->fetchAll();
		return $res;
	}
        public function getEmDatabyID($id)
	{
	    $select = $this->select()
						->setIntegrityCheck(false)
						->from(array('sd'=>'main_users'),array('sd.*'))
					    ->where('sd.isactive = 1 AND sd.id='.$id.' ');
					   
		return $this->fetchAll($select)->toArray();
	
	}
        public function getEmployeeDatabyID($id)
	{
	    $select = $this->select()
						->setIntegrityCheck(false)
						->from(array('sd'=>'main_users'),array('sd.*'))
					    ->where('sd.isactive = 1 AND sd.id='.$id.' ');
					   
		return $this->fetchAll($select)->toArray();
	
	}
        public function SaveorUpdateEmployeecomponentData($data, $where)
	{
	    if($where != ''){
			$this->update($data, $where);
			return 'update';
		} else {
			$this->insert($data);
			$id=$this->getAdapter()->lastInsertId('payroll_attendancerequest');
			return $id;
		}
		
	
	}
         public function saveEmployeeComponent($data)
        {       
                        $db = Zend_Db_Table::getDefaultAdapter();
                        $i=1;
                        foreach($data as $insert){                            
                        $db->insert('payroll_empcomponents', $insert);
                        $i++;
                        }
			
                      	return $i;
        }
        public function deleteEmployeeComponent($empid)
        {
            
            $db = Zend_Db_Table::getDefaultAdapter();
            $delete = $db->delete('payroll_empcomponents', 'EmployeeId = '.$empid);
            return 'delete';
        }
         public function getempattendancerequests($id) {
            
             $select = $this->select()
						->setIntegrityCheck(false)
						->from(array('sd'=>'payroll_attendancerequest'),array('sd.*'))
					    ->where('sd.isactive = 1 AND sd.emp_id='.$id.' ');
					   
		return $this->fetchAll($select)->toArray();
        }
        public function Checkattendancerequest($fromdate,$todate,$id)
        {
           
            $select = $this->select()
						->setIntegrityCheck(false)
						->from(array('sd'=>'payroll_attendancerequest'),array('sd.*'))
					    ->where('sd.from_date = "'.$fromdate.'" AND sd.to_date="'.$todate.'" AND sd.emp_id='.$id.' ');
            //echo $select;exit;
					   
		return $this->fetchAll($select)->toArray();
        }
}
