<?php
/********************************************************************************* 
 *  This file is part of Sentrifugo.
 *  Copyright (C) 2014 Sapplica
 *   
 *  Sentrifugo is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  Sentrifugo is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with Sentrifugo.  If not, see <http://www.gnu.org/licenses/>.
 *
 *  Sentrifugo Support <support@sentrifugo.com>
 ********************************************************************************/

class Default_Model_Payrollattendancereport extends Zend_Db_Table_Abstract
{
    protected $_name = 'payroll_attendancereport';
    protected $_primary = 'id';
	
	public function getServiceDeskDepartmentData($sort, $by, $pageNo, $perPage,$searchQuery)
	{
		$where = "e.isactive = 1 AND e.emp_status_id != 11";
		if($searchQuery)
			$where .= " AND ".$searchQuery;
		$db = Zend_Db_Table::getDefaultAdapter();		
		$getempquery = $this->select()
    					   ->setIntegrityCheck(false)	
                           ->from(array('e'=>'main_employees'),array('u.id','date_of_joining'=>'DATE_FORMAT(date_of_joining,"'.DATEFORMAT_MYSQL.'")'))
                          ->join(array('u'=>'main_users'), 'u.id=e.user_id',array('employeeid' => 'u.employeeid','userfullname' => 'u.userfullname','emailaddress' => 'u.emailaddress'))  
                         
                        ->where($where)
    					   ->order("$by $sort") 
    					   ->limitPage($pageNo, $perPage);
		
      // echo $servicedeskDepartmentData;       exit;
		return $getempquery;       		
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
			
		$objName = 'payrollattendancereport';
		
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
	public function getEmployeeattendance($id,$startdate,$enddate)
	{
           
          
                $db = Zend_Db_Table::getDefaultAdapter();
                $stored_proc_stmt = "call  sp_getattendancebyid ($id,'$startdate','$enddate')";
                $stmt = $db->prepare($stored_proc_stmt);                
                $stmt->execute();
                $results = $stmt->fetchAll();
//              $query = "select  Date(alog.MessageTime) as Date,
//                            (
//                            SELECT TIME_FORMAT(u.MessageTime,'%H:%i') FROM time_alarmlog u 
//                            inner join time_messagelogrule as r on r.IDCMessageID=u.MessageID
//                            inner join time_readerhardware as re on re.ID=u.MapID 
//                            where u.RelatedEmpID=$id  and r.SubCategoryID = 1 and re.Position = 0 and DATE(u.MessageTime) = DATE(alog.MessageTime)
//                             ORDER BY u.ID ASC  LIMIT 1
//                            ) as INTIME,
//                            (SELECT TIME_FORMAT(u.MessageTime,'%H:%i') FROM time_alarmlog u 
//                            inner join time_messagelogrule as r on r.IDCMessageID=u.MessageID
//                            inner join time_readerhardware as re on re.ID=u.MapID 
//                            where u.RelatedEmpID=$id  and r.SubCategoryID = 1 and re.Position = 1 and DATE(u.MessageTime) = DATE(alog.MessageTime)
//                             ORDER BY u.ID DESC  LIMIT 1
//                            ) as OUTTIME
//                            from time_alarmlog as alog
//                            where RelatedEmpID = $id and alog.MessageTime >='$startdate' and  alog.MessageTime <='$enddate'
//                            group by Date(alog.MessageTime)"; 
//            $result = $db->query($query);
            return $response = $results;
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
			$id=$this->getAdapter()->lastInsertId('main_users');
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
}