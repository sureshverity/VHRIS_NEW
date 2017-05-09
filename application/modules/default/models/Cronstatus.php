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

class Default_Model_Cronstatus extends Zend_Db_Table_Abstract
{
    protected $_name = 'main_cronstatus';
    protected $_primary = 'id';
	
	
    public function SaveorUpdateCronStatusData($data, $where)
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
    
    public function getActiveCron($cron_type)
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $query = "select count(*) cnt from ".$this->_name." where cron_status = 1 and cron_type = '".$cron_type."'";
        $result = $db->query($query);
        $row = $result->fetch();
        if($row['cnt'] >0)
            $status = "no";
        else 
            $status = "yes";
        return $status;
    }

    public function getlastId()
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $query = "select ID from time_alarmlog order by ID desc LIMIT 1";
        $result = $db->query($query);
        $row = $result->fetch();
       
        if($row['ID'] >0)
            $status = $row['ID'];
        else 
            $status = -1;
        return $status;
    }
    public function mssqlconnection()
    {
        try {

            $params = array(
            'host'           => ATTENDANCE_HOST,
            'username'       => ATTENDANCE_USERNAME,
            'password'       => ATTENDANCE_PASSWORD,
            'dbname'         => ATTENDANCE_DBNAME
        );

            $lastId = $this->getlastId();
            $db = Zend_Db_Table::getDefaultAdapter();
            $dbsql = Zend_Db::factory('Sqlsrv', $params);
            $dbsql->getConnection();
            $query = "SELECT [ID]
		,[MessageID]
		,[MessageTime]
		,[ControllerID]
		,[InterfaceHardwareID]
		,[IDType]
		,[MapID]
		,[RelatedEmpID]
		,[RelatedCardID]
		,[BranchID]
		,[SystemDate]
		,[EmpType]
	    FROM AlarmLog WHERE ID > ".$lastId;
            $stmt = $dbsql->query($query);
            $reader = $stmt->fetchAll();
            $icount = count($reader);
            $rows = array();
            for ($i = 0; $i < $icount - 1; $i++)
            {
                $messageDate = $reader[$i][MessageTime]->format(ATTENDANCE_DATEFORMATE);
                $systemdate = $reader[$i][SystemDate]->format(ATTENDANCE_DATEFORMATE);
            	$rows[$i] = '('.$reader[$i][ID].','.$reader[$i][MessageID].',"'.$messageDate.'",'.$reader[$i][ControllerID].','.$reader[$i][InterfaceHardwareID].','.$reader[$i][IDType].','.$reader[$i][MapID].','.$reader[$i][RelatedEmpID].','.$reader[$i][RelatedCardID].','.$reader[$i][BranchID].',"'.$systemdate.'",'.$reader[$i][EmpType].')';
            }
            $insertQuery = 'INSERT INTO time_alarmlog(ID,MessageID,MessageTime,ControllerID,InterfaceHardwareID,IDType,MapID,RelatedEmpID,RelatedCardID,BranchID,SystemDate,EmpType) VALUES ';
            $noofRows = 1000;
            $loopCount =ceil($icount/$noofRows);
            
            for ($j = 0; $j < $loopCount; $j++)
            {
            	$looprows = array_slice($rows, $j * $noofRows, $noofRows);
                $insertValue = implode(",",$looprows);
                $mainQuery = $insertQuery . $insertValue;
                $stmt = $db->query($mainQuery);
            }
            
        } catch (Zend_Db_Adapter_Exception $e) {
            print_r($e);exit;
        
        } catch (Zend_Exception $e) {
            print_r($e);exit;
           
        }
    }
}
?>