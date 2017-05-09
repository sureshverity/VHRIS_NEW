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

class Default_Model_Payrollreports extends Zend_Db_Table_Abstract
{
	protected $_name = 'payroll_emppaystucture';
	protected $_primary = 'SNo';

	/**
	 * This function gives data for grid view.
	 * @parameters
	 * @sort          = ascending or descending
	 * @by            = name of field which to be sort
	 * @pageNo        = page number
	 * @perPage       = no.of records per page
	 * @searchQuery   = search string
	 *
	 * @return  ResultSet;
	 */
	public function getPayrollData($sort, $by, $pageNo, $perPage,$searchQuery)
	{
		$where = "r.isactive = 1";

		if($searchQuery)
		$where .= " AND ".$searchQuery;

		$payrollData = $this->select()
		->setIntegrityCheck(false)
		->from(array('r'=>$this->_name),array('r.*',))
		//->joinInner(array('g'=>'main_groups'), "g.id = r.group_id and g.isactive = 1",array('group_name'=>'g.group_name'))
		//->where($where)
		->order("$by $sort")
		->limitPage($pageNo, $perPage);
		
		return $payrollData;
	}
        public function getpayrollreportdata($search_arr,$per_page,$page_no,$sort_name,$sort_type)
        {
                $db = Zend_Db_Table::getDefaultAdapter();
                $query = "select me.employeeId,me.userfullname,me.emprole_name,pe.ComponentId,pe.PeriodId,pe.Amount,pe.Operator from payroll_emppaystucture  as pe 
	inner join main_employees_summary as me on me.id = pe.EmployeeId where pe.Amount != 0";
                $result = $db->query($query);
                $row = $result->fetchAll();
                $page_cnt =10;
                $data = array();
                $data['rows'] = $row;
                $data['page_cnt'] = $page_cnt;
                return $data;
            
        }
        
        public function getComponentByPeriod($search_arr)
        {
            $db = Zend_Db_Table::getDefaultAdapter();
               $query = "select pc.id , pc.name from payroll_emppaystucture as pe
	inner join payroll_components as pc on pc.id = pe.ComponentId
	where pe.buid = 1 and pe.PeriodId = ".$search_arr['period']." and pe.Amount != 0";
                
                $result = $db->query($query);
                $row = $result->fetchAll();
                
                return $row;
        }
}//end of class