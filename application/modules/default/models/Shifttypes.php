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

class Default_Model_Shifttypes extends Zend_Db_Table_Abstract
{
    protected $_name = 'shift_types';
    protected $_primary = 'id';
	
	public function getShiftData($sort, $by, $pageNo, $perPage,$searchQuery)
	{
		$where = "s.isactive = 1";
		if($searchQuery)
			$where .= " AND ".$searchQuery;
		$db = Zend_Db_Table::getDefaultAdapter();		
		$getempquery = $this->select()
    					   ->setIntegrityCheck(false)	
                           ->from(array('s'=>'shift_types'),array('s.id','s.shift_name','s.shortname','s.onduty_time','s.offduty_time','s.late_min','s.leaveearly_min','s.begin_intime','s.end_intime','s.begin_outtime','s.end_outtime'))

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
			
		$objName = 'shifttypes';
		
		$tableFields = array('action'=>'Action','shift_name' => 'Shift Name','shortname' => 'Short Name','onduty_time' =>'On Duty','offduty_time' =>'Off Duty','begin_intime'=>'Begin In','end_intime'=>'End In','begin_outtime'=>'Begin Out','end_outtime'=>'End Out');
		
		$tablecontent = $this->getShiftData($sort, $by, $pageNo, $perPage,$searchQuery);
		
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
	public function getShiftDatabyID($id)
	{
                 $select = $this->select()
                            ->setIntegrityCheck(false)
                            ->from(array('sd'=>'shift_types'),array('sd.shift_name','sd.shortname','sd.onduty_time','sd.offduty_time','sd.late_min','sd.leaveearly_min','sd.begin_intime','sd.end_intime','sd.begin_outtime','sd.end_outtime','sd.is_nightshift','sd.include_company_holidays','sd.include_weekoff','sd.color_code'))
                            ->where('sd.isactive = 1 AND sd.id='.$id.' ');

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
	
	public function SaveorUpdateShiftData($data, $where)
	{

	    if($where != ''){
			$this->update($data, $where);
			return 'update';
		} else {
			 $this->insert($data);
            $id=$this->getAdapter()->lastInsertId($this->_name);
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