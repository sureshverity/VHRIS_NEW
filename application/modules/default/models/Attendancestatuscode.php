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

class Default_Model_Attendancestatuscode extends Zend_Db_Table_Abstract
{
    protected $_name = 'main_attendancestatuscode';
    protected $_primary = 'id';
	
	public function getAttendanceStatusData($sort, $by, $pageNo, $perPage,$searchQuery)
	{
		$where = "isactive = 1";
		
		if($searchQuery)
			$where .= " AND ".$searchQuery;
		$db = Zend_Db_Table::getDefaultAdapter();		
		
		$attendanceStatusData = $this->select()
    					   ->setIntegrityCheck(false)	    					
						   ->where($where)
    					   ->order("$by $sort") 
    					   ->limitPage($pageNo, $perPage);
		
		return $attendanceStatusData;       		
	}
	public function getsingleAttendanceStatusData($id)
	{
		
		
		$db = Zend_Db_Table::getDefaultAdapter();
		$attendanceData = $db->query("SELECT * FROM main_attendancestatuscode WHERE id = ".$id." AND isactive=1");
		$res = $attendanceData->fetchAll();
		if (isset($res) && !empty($res)) 
		{	
			return $res;
		}
		else
			return 'norows';
	}
	
	public function SaveorUpdateAttendanceStatusData($data, $where)
	{
	    if($where != ''){
			$this->update($data, $where);
			return 'update';
		} else {
			$this->insert($data);
			$id=$this->getAdapter()->lastInsertId('main_attendancestatuscode');
			return $id;
		}
	}
	public function getGrid($sort,$by,$perPage,$pageNo,$searchData,$call,$dashboardcall,$exParam1='',$exParam2='',$exParam3='',$exParam4='')
	{		
        $searchQuery = '';$tablecontent = '';  $searchArray = array();$data = array();$id='';
        $dataTmp = array();
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

		/** search from grid - END **/
		$objName = 'attendancestatuscode';
		
		$tableFields = array('action'=>'Action','attendancestatuscode' => 'Attendance Status','description' => 'Description');
		
			
		$tablecontent = $this->getAttendanceStatusData($sort, $by, $pageNo, $perPage,$searchQuery);
		
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
			'call'=>$call,'dashboardcall'=>$dashboardcall,
		);		
			
		return $dataTmp;
	}
        public function getattendancereportdata()
        {
            $db = Zend_Db_Table::getDefaultAdapter();
            // $offset = ($per_page*$page_no) - $per_page;
            // $limit_str = " limit ".$per_page." offset ".$offset;
                $query = "SELECT 
                    m.nEventLogIdn,
                    m.nDateTime,DATE(CONVERT_TZ(from_unixtime(m.nDateTime), @@session.time_zone, '+00:00')) as Date,
                    (SELECT TIME_FORMAT(TIME(CONVERT_TZ(from_unixtime(u.nDateTime), @@session.time_zone, '+00:00')),'%H:%i')
                    FROM tb_event_log u where u.nTNAEvent = 0 and DATE(FROM_UNIXTIME(u.nDateTime)) = DATE(FROM_UNIXTIME(m.nDateTime))   ORDER BY u.nEventLogIdn ASC  LIMIT 1) as INTIME,
                    (SELECT TIME_FORMAT(TIME(CONVERT_TZ(from_unixtime(u.nDateTime), @@session.time_zone, '+00:00')),'%H:%i')
                    FROM tb_event_log u where u.nTNAEvent = 1 and DATE(FROM_UNIXTIME(u.nDateTime)) = DATE(FROM_UNIXTIME(m.nDateTime))   ORDER BY u.nEventLogIdn DESC  LIMIT 1) as OUTTIME,
                    m.nReaderIdn,m.nEventIdn,m.nUserID,m.nIsLog,m.nTNAEvent,
                    m.nIsUseTA,m.nType FROM tb_event_log as m Where m.nEventIdn = 55  and m.nTNAEvent IN(0,1)
                     group by DATE(CONVERT_TZ(from_unixtime(m.nDateTime), @@session.time_zone, '+00:00')),m.nUserID";
              
                $tot_result = $db->query($query);
//                $count = $tot_result->rowCount();        
//                $page_cnt = ceil($count/$per_page);
                $row = $tot_result->fetchAll();
                $data = array();
                $data['rows'] = $row;
                $data['page_cnt'] = $page_cnt;
                return $data;
        }
}