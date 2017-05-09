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
/**
 *
 * @model Client Model
 * @author sagarsoft
 *
 */
class Timemanagement_Model_Exporttimesheet extends Zend_Db_Table_Abstract
{
	protected $_name = 'timesheet_view';
	//protected $_primary = 'id';

	/**
	 * This will fetch all the client details based on the search paramerters passed with pagination.
	 *
	 * @param string $sort
	 * @param string $by
	 * @param number $perPage
	 * @param number $pageNo
	 * @param JSON $searchData
	 * @param string $call
	 * @param string $dashboardcall
	 * @param string $a
	 * @param string $b
	 * @param string $c
	 * @param string $d
	 *
	 * @return array
	 */
	public function getGrid($sort,$by,$perPage,$pageNo,$searchData,$call,$dashboardcall,$loginUserId,$a='',$b='',$c='',$d='')
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
		
		$objName = 'Export Timesheet';

		//email,phone_no,poc,address,country_id,state_id,created_by
		$tableFields = array(
					'action'=>'Action',
					'emp_id' => 'emp_id',
					'userfullname' => 'Full Name',
					'employeeid' => 'Employeeid',
					'project_name' => 'Project',
					'project_id' => 'project_id',
					'task' => 'Task',
					'ts_year' => 'ts_year',
					'ts_month' => 'ts_month',
					'ts_week' => 'ts_week',
					'reporting_manager' => 'reporting_manager',
					'Date1' => 'Date1',
					'DayName' => 'DayName',
					'Status' => 'Status'
		);
                $tablecontent = $this->getClientsData($sort, $by, $pageNo, $perPage,$searchQuery,$loginUserId);
              //  print_r($tablecontent); exit;
           // $db = Zend_Db_Table::getDefaultAdapter();
           
          // $qry = "select * from timesheet_view";

                  //  $res = $db->query($qry)->fetchAll();
                   // print_r($res); exit;
		//return $res;
                
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
			'call'=>$call,
			'dashboardcall'=>$dashboardcall,
			'menuName' => 'Export'
			);
                      //  print_r($dataTmp); exit;
			return $dataTmp;
	}

	/**
	 * This will fetch all the active client details.
	 *
	 * @param string $sort
	 * @param string $by
	 * @param number $pageNo
	 * @param number $perPage
	 * @param string $searchQuery
	 *
	 * @return array $clientsData
	 */
	public function getClientsData($sort, $by, $pageNo, $perPage,$searchQuery,$loginUserId)
	{
//		$where = "";
//              
//		if($searchQuery)
		$where = $searchQuery;
		$db = Zend_Db_Table::getDefaultAdapter();

//		echo $select = $this->select()
//		->setIntegrityCheck(false)
//		->from(array('c'=>$this->_name),array('c.id'));exit;
		//->where('c.is_active = 1 ')
		//->order('c.client_name');
                if($loginUserId == 1)
                {
                        $qry = "select * from timesheet_view ".$where." order by Date1 DESC";
                }
                else if($loginUserId !=1){
                  $qry = "select * from timesheet_view where reporting_manager=".$loginUserId."  or  emp_id=".$loginUserId ." ". $where." order by Date1 DESC" ;
                }
                return  $res = $db->query($qry)->fetchAll();
		//return $db->fetchAll($qry)->toArray();

		//return $clientsData;
	}

	/**
	 * This method will save or update the client details based on the client id.
	 *
	 * @param array $data
	 * @param string $where
	 */
	public function saveOrUpdateClientsData($data, $where){
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
	 * This method is used to fetch client details based on id.
	 * 
	 * @param number $id
	 */
	public function getClientDetailsById($id)
	{
		$select = $this->select()
						->setIntegrityCheck(false)
						->from(array('c'=>$this->_name),array('c.*','cn.country_name','s.state_name'))
						->joinLeft(array('cn'=>'tbl_countries'),"c.country_id = cn.id",array())
						->joinLeft(array('s'=>'tbl_states'),"s.id = c.state_id",array())
						->where('c.is_active = 1 AND c.id='.$id.' ');
						
		return $this->fetchAll($select)->toArray();
	}

	/**
	 * This method returns all active clients to show in projects screen 
	 *
	 * @return array 
	 */
	public function getActiveClientsData()
	{
		$select = $this->select()
		->setIntegrityCheck(false)
		->from(array('c'=>$this->_name),array('c.id','c.client_name'))
		->where('c.is_active = 1 ')
		->order('c.client_name');
		return $this->fetchAll($select)->toArray();
	}
	
	/**
	 * This method is used to check weather the client is associated in any project or not.
	 * 
	 * @param unknown_type $clientId
	 */
	public function checkProjectClients($clientId){
		$db = Zend_Db_Table::getDefaultAdapter();
		$query = "select count(*) as count from tm_projects where client_id = ".$clientId." AND is_active = 1";
		$result = $db->query($query)->fetch();
		return $result['count'];
		
	} 
}