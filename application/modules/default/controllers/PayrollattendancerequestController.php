<?php
/********************************************************************************* 
 *  This file is part of Sentrifugo.
 *  Copyright (C) 2015 Sapplica
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

class Default_PayrollattendancerequestController extends Zend_Controller_Action
{

    private $options;
    public function preDispatch()
    {
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('getrequests', 'json')->initContext();		
    }
	
    public function init()
    {
        $this->_options= $this->getInvokeArg('bootstrap')->getOptions();
    }

    public function indexAction()
    {	
        $auth = Zend_Auth::getInstance();
     	   if($auth->hasIdentity()){
					$loginUserId = $auth->getStorage()->read()->id;
					$loginuserRole = $auth->getStorage()->read()->emprole;
					$loginuserGroup = $auth->getStorage()->read()->group_id;
		}
              if($loginUserId != '')
               {
                  $this->_redirect('payrollattendancerequest/edit/id/'.$loginUserId);
               }
                else{
		$servicedeskdepartmentmodel = new Default_Model_Payrollattendancerequest();	
                $call = $this->_getParam('call');
		if($call == 'ajaxcall')
				$this->_helper->layout->disableLayout();
		
		$view = Zend_Layout::getMvcInstance()->getView();		
		$objname = $this->_getParam('objname');
		$refresh = $this->_getParam('refresh');
		$dashboardcall = $this->_getParam('dashboardcall');
		
		$data = array();
		$searchQuery = '';
		$searchArray = array();
		$tablecontent='';
		
		if($refresh == 'refresh')
		{
		    if($dashboardcall == 'Yes')
				$perPage = DASHBOARD_PERPAGE;
			else	
				$perPage = PERPAGE;
			$sort = 'DESC';$by = 'e.modifieddate';$pageNo = 1;$searchData = '';$searchQuery = '';$searchArray='';
		}
		else 
		{
			$sort = ($this->_getParam('sort') !='')? $this->_getParam('sort'):'DESC';
			$by = ($this->_getParam('by')!='')? $this->_getParam('by'):'e.modifieddate';
			if($dashboardcall == 'Yes')
				$perPage = $this->_getParam('per_page',DASHBOARD_PERPAGE);
			else 
				$perPage = $this->_getParam('per_page',PERPAGE);
			$pageNo = $this->_getParam('page', 1);
			/** search from grid - START **/
			$searchData = $this->_getParam('searchData');	
			$searchData = rtrim($searchData,',');
			/** search from grid - END **/
		}
				
		$dataTmp = $servicedeskdepartmentmodel->getGrid($sort, $by, $perPage, $pageNo, $searchData,$call,$dashboardcall);		 		
					
		array_push($data,$dataTmp);
                //print_r($dataTmp); exit;
		$this->view->dataArray = $data;
		$this->view->call = $call ;
		$this->view->messages = $this->_helper->flashMessenger->getMessages();
		//$this->renderScript('commongrid/index.phtml');
               
		$this->render('payrollattendancerequest/index', null, true);
           }
    }

     public function addAction()
	{
                  
             $auth = Zend_Auth::getInstance();
     	   if($auth->hasIdentity()){
					$loginUserId = $auth->getStorage()->read()->id;
					$loginuserRole = $auth->getStorage()->read()->emprole;
					$loginuserGroup = $auth->getStorage()->read()->group_id;
		}
		$callval = $this->getRequest()->getParam('call');
		if($callval == 'ajaxcall')
			$this->_helper->layout->disableLayout();
		$addform = new Default_Form_payrollattendancerequest();
		$addModel = new Default_Model_Payrollattendancerequest();
                $reasontype = $addModel->getRequestTypes();

                foreach($reasontype as $res) 
					$addform->reason->addMultiOption($res['id'],utf8_encode($res['name']));
		$msgarray = array();
		$addform->setAttrib('action',BASE_URL.'payrollattendancerequest/add');
		$this->view->form = $addform; 
                
		if($this->getRequest()->getPost()){
      		$result = $this->save($addform);	
		    $this->view->msgarray = $result; 
		}
                $this->render('form');            
                      
	}

    public function viewAction()
	{	
		$id = $this->getRequest()->getParam('id');
		$callval = $this->getRequest()->getParam('call');
		if($callval == 'ajaxcall')
			$this->_helper->layout->disableLayout();
		$objName = 'payrollattendancerequest';
		$form = new Default_Form_payrollattendancereport();
		$form->removeElement("submit");
		$elements = $form->getElements();
		if(count($elements)>0)
		{
			foreach($elements as $key=>$element)
			{
				if(($key!="Cancel")&&($key!="Edit")&&($key!="Delete")&&($key!="Attachments")){
				$element->setAttrib("disabled", "disabled");
					}
        	}
        }
		try
		{
		    if($id)
			{
			    if(is_numeric($id) && $id>0)
				{
					$model = new Default_Model_Payrollattendancerequest();	
					$data = $model->getempattendancerequests($id);
                                        $empdata=$model->getEmployeeDatabyID($id);
                                       
					if(!empty($data))
					{
                                            $empName=$empdata[0]["userfullname"];
                                            //print_r($empName);exit;
						$data = $data[0]; 
                                                $data["Name"]=$empName;
						$form->populate($data);
					}else
					{
					   $this->view->ermsg = 'norecord';
					}
                } 
                else
				{
				   $this->view->ermsg = 'norecord';
				}				
			}
            else
			{
			   $this->view->ermsg = 'norecord';
			} 			
		}
		catch(Exception $e)
		{
			   $this->view->ermsg = 'nodata';
		}
		$this->view->controllername = $objName;
		$this->view->id = $id;
		$this->view->data = $data;
		$this->view->flag = 'view';
		$this->view->form = $form;
		$this->render('form');	
             
	}
	

        
  public function editAction()
            {

            $auth = Zend_Auth::getInstance();
                if($auth->hasIdentity()){
					$loginUserId = $auth->getStorage()->read()->id;
					$loginuserRole = $auth->getStorage()->read()->emprole;
					$loginuserGroup = $auth->getStorage()->read()->group_id;
		}
                $id = $this->getRequest()->getParam('id');
		$callval = $this->getRequest()->getParam('call');
		if($callval == 'ajaxcall')
                {
			$this->_helper->layout->disableLayout();
                }
		
                $editform = new Default_Form_payrollattendancerequest();
                
		$editModel = new Default_Model_Payrollattendancerequest();
               $reasontype = $editModel->getRequestTypes();

                foreach($reasontype as $res) 
					$editform->reason->addMultiOption($res['id'],utf8_encode($res['name']));
                
		$editform->submit->setLabel('Update');
	 	try
                {		
			if($id)
			{
                           
			    if(is_numeric($id) && $id>0)
				{
					$data = $editModel->getEmployeeDatabyID($id);
					
                                        if(!empty($data))
					{
						  $data = $data[0];
                                                  
						$editform->populate($data);
                                                
						$editform->setAttrib('action',BASE_URL.'payrollattendancerequest/edit/id/'.$id);
                        $this->view->data = $data;
					}else
					{
						$this->view->ermsg = 'norecord';
					}
				}
                else
				{
					$this->view->ermsg = 'norecord';
				}				
			}
			else
			{
				$this->view->ermsg = 'nodata';
			}
		}	
		catch(Exception $e)
		{
			   $this->view->ermsg = 'nodata';
		}	
		$this->view->form = $editform;
		if($this->getRequest()->getPost()){
      		$result = $this->save($editform);	
		    $this->view->msgarray = $result; 
		}
		$this->render('form');	 		
	}
	
	
	public function save($empform)
	{
	  $auth = Zend_Auth::getInstance();
     	if($auth->hasIdentity()){
					$loginUserId = $auth->getStorage()->read()->id;
                                      
		} 
	    $employeemodel = new Default_Model_Payrollattendancerequest();
            $usersmodel = new Default_Model_Users();
            $employeesmodel = new Default_Model_Employees();
		$msgarray = array();
	    
		  if($empform->isValid($this->_request->getPost())){
            try{
               
            $id = $this->_request->getParam('id');
            
            $loggedinEmpId = $usersmodel->getUserDetailsByID($id);
            $loggedInEmployeeDetails = $employeesmodel->getLoggedInEmployeeDetails($id);
           
            if(!empty($loggedInEmployeeDetails))
					{
                                        $reportingmanagerId = $loggedInEmployeeDetails[0]['reporting_manager'];
						$employeeDepartmentId = $loggedInEmployeeDetails[0]['department_id'];
						$employeeEmploymentStatusId = $loggedInEmployeeDetails[0]['emp_status_id'];
						$businessunitid = $loggedInEmployeeDetails[0]['businessunit_id'];
						$dateofjoining = $loggedInEmployeeDetails[0]['date_of_joining'];
						
						if($reportingmanagerId !='' && $reportingmanagerId != NULL)
						 $reportingManagerDetails = $usersmodel->getUserDetailsByID($reportingmanagerId);
                                           
						if($employeeDepartmentId !='' && $employeeDepartmentId != NULL)
						// $weekendDatailsArr = $leavemanagementmodel->getWeekendDetails($employeeDepartmentId);
                                                 $employeeemail = $loggedinEmpId[0]['emailaddress'];
						$userfullname = $loggedinEmpId[0]['userfullname'];
					
                        if(!empty($reportingManagerDetails))
						{
                            
							//$leaverequestform->rep_mang_id->setValue($reportingManagerDetails[0]['userfullname']); 
							$reportingManageremail = $reportingManagerDetails[0]['emailaddress'];
                                                    $reportingmanagerName = $reportingManagerDetails[0]['userfullname'];
							$rep_mang_id = $reportingManagerDetails[0]['id']; 
							$rMngr = 'Yes';
						}
						else
						{
						   $msgarray['rep_mang_id'] = 'Reporting manager is not assigned yet. Please contact your HR.';
						   $errorflag = 'false';
						}
						
//						if(!empty($weekendDatailsArr))
//						{
//							$week_startday = $weekendDatailsArr[0]['weekendstartday'];
//							$week_endday = $weekendDatailsArr[0]['weekendday'];
//							$ishalf_day = $weekendDatailsArr[0]['is_halfday'];
//							$isskip_holidays = $weekendDatailsArr[0]['is_skipholidays'];
//							
//                                                }
//                                                else
//						{
//						   $msgarray['from_date'] = 'Attendance management options are not configured yet.';
//						   $msgarray['to_date'] = 'Attendance management options are not configured yet.';
//						   $errorflag = 'false';
//						}
//						 
					}
                    else
					{
					   $errorflag = 'false';
					   $msgarray['rep_mang_id'] = 'Reporting manager is not assigned yet. Please contact your HR.';
					   $msgarray['from_date'] = 'Attendance management options are not configured yet.';
					   $msgarray['to_date'] = 'Attendance management options are not configured yet.';
					}   					
			
                                       
                        $reason = $this->_request->getParam('reason');			
                        $intime=$this->_request->getParam('in_time');
                        $outtime=$this->_request->getParam('out_time');
                        $fromdate = sapp_Global::change_date($this->_request->getParam('from_date'),'database');
                        $todate= sapp_Global::change_date($this->_request->getParam('to_date'),'database');
                        $payrollattendancedetails=$employeemodel->Checkattendancerequest($fromdate,$todate,$id);
                       
                        if(count($payrollattendancedetails)==0)
                        {
			$actionflag = '';
			$tableid  = ''; 
			   $data = array('reason'=>$reason,
                               'from_date'=>$fromdate, 
                                'to_date'=>$todate,
                               'in_time'=>$intime,
                               'out_time'=>$outtime,
                               'emp_id'=>$id,
							  'modifiedby'=>$loginUserId,
							  'modifieddate'=>gmdate("Y-m-d H:i:s")
					);
//				if($id!=''){
//					$where = array('id=?'=>$id);  
//					$actionflag = 2;
//				}
//				else
//				{
					$data['createdby'] = $loginUserId;
					$data['createddate'] = gmdate("Y-m-d H:i:s");
					$data['isactive'] = 1;
                                        $data['status']=0;
					$where = '';
					$actionflag = 1;
				//}
				$Id = $employeemodel->SaveorUpdateEmployeecomponentData($data, $where);
				if($Id == 'update')
				{
				   $tableid = $id;
				   $this->_helper->getHelper("FlashMessenger")->addMessage(array("success"=>"Attendance Request updated successfully."));
				}   
				else
				{
				   $tableid = $Id; 	
					$this->_helper->getHelper("FlashMessenger")->addMessage(array("success"=>"Attendance Request added successfully."));					   
				}   
				$menuID = PAYROLLATTENDANCEREPORT;
				$result = sapp_Global::logManager($menuID,$actionflag,$loginUserId,$tableid);
                                /** MAILING CODE **/
							//$hremail = explode(",",HREMAIL);
							/* Mail to Reporting manager */
							if($todate == '' || $todate == NULL)
							$todate = $fromdate;
							
							$toemailArr = $reportingManageremail; //$employeeemail
							if(!empty($toemailArr))
							{
								$options['subject'] = 'Attendance request for approval';
								$options['header'] = 'Attendance Request';
								$options['toEmail'] = $toemailArr;
								$options['toName'] = $reportingmanagerName;
								$options['message'] = '<div>
												<div>Dear '.$reportingmanagerName.',</div>
												<div>The Attendance of the below employee is pending for approval:</div>
												<div>
                <table width="100%" cellspacing="0" cellpadding="15" border="0" style="border:3px solid #BBBBBB; font-size:16px; font-family:Arial, Helvetica, sans-serif; margin:30px 0 30px 0;" bgcolor="#ffffff">
                      <tbody><tr>
                        <td width="28%" style="border-right:2px solid #BBBBBB;">Employee Name</td>
                        <td width="72%">'.$userfullname.'</td>
                      </tr>
                       <tr bgcolor="#e9f6fc">
                        <td style="border-right:2px solid #BBBBBB;">From</td>
                        <td>'.$fromdate.'</td>
                      </tr>
                      <tr>
                        <td style="border-right:2px solid #BBBBBB;">To</td>
                        <td>'.$todate.'</td>                  
                     <tr>
                      <tr bgcolor="#e9f6fc">
                        <td style="border-right:2px solid #BBBBBB;">In Time</td>
                        <td>'.$intime.'</td>
                      </tr>
                      <tr bgcolor="#e9f6fc">
                        <td style="border-right:2px solid #BBBBBB;">Out Time</td>
                        <td>'.$outtime.'</td>
                      </tr>
                     
                     
                        <td style="border-right:2px solid #BBBBBB;">Reporting Manager</td>
                        <td>'.$reportingmanagerName.'</td>
                  </tr>
                </tbody></table>

            </div>
            <div style="padding:20px 0 10px 0;">Please <a href="'.BASE_URL.'/index/popup" target="_blank" style="color:#b3512f;">click here</a> to login and check the Attendance details.</div>
            </div>';
                                $result = sapp_Global::_sendEmail($options);	
                                //print_r($result);exit;
							}		
							/* END */
							/* Mail to HR */
                            if (defined('LV_HR_'.$businessunitid) && $businessunitid !='')
						    {
							    $options['subject'] = 'Attendance request for approval';
								$options['header'] = 'Attendance Request ';
								$options['toEmail'] = constant('LV_HR_'.$businessunitid);
								$options['toName'] = 'Attendance management';
								$options['message'] = '<div>
												<div>Dear HR,</div>
												<div>The Attendance request of the below employee is pending for approval:</div>
<div>
                <table width="100%" cellspacing="0" cellpadding="15" border="0" style="border:3px solid #BBBBBB; font-size:16px; font-family:Arial, Helvetica, sans-serif; margin:30px 0 30px 0;" bgcolor="#ffffff">
                      <tbody><tr>
                        <td width="28%" style="border-right:2px solid #BBBBBB;">Employee Name</td>
                        <td width="72%">'.$userfullname.'</td>
                      </tr>
                       <tr bgcolor="#e9f6fc">
                        <td style="border-right:2px solid #BBBBBB;">From</td>
                        <td>'.$fromdate.'</td>
                      </tr>
                      <tr>
                        <td style="border-right:2px solid #BBBBBB;">To</td>
                        <td>'.$todate.'</td>
                     </tr>
                      <tr bgcolor="#e9f6fc">
                        <td style="border-right:2px solid #BBBBBB;">In Time</td>
                        <td>'.$intime.'</td>
                      </tr>
                      <tr>
                        <td style="border-right:2px solid #BBBBBB;">Out Time</td>
                        <td>'.$outtime.'</td>
                      </tr>
                     
                    
                  <tr>
                        <td style="border-right:2px solid #BBBBBB;">Reporting Manager</td>
                        <td>'.$reportingmanagerName.'</td>
                  </tr>
                </tbody></table>

            </div>
            <div style="padding:20px 0 10px 0;">Please <a href="'.BASE_URL.'/index/popup" target="_blank" style="color:#b3512f;">click here</a> to login and check the Attendance details.</div>
            </div>';	
								//$options['cron'] = 'yes';
                                $result = sapp_Global::_sendEmail($options);
							
							}
				$this->_redirect('payrollattendancerequest');
                        }
 else {
//    $msgarray = array();
	      $msgarray['from_date'] = "from_date already existing.";
              $msgarray['to_date'] = "to_date already existing.";
             return $msgarray;
 }
                  }
        catch(Exception $e)
          {
             $msgarray['service_desk_name'] = "Something went wrong, please try again.";
             return $msgarray;
          }
		}else
		{
			$messages = $empform->getMessages();
			foreach ($messages as $key => $val)
				{
					foreach($val as $key2 => $val2)
					 {
						$msgarray[$key] = $val2;
						break;
					 }
				}
			return $msgarray;	
		}
	
	}
	
	public function deleteAction()
	{
            $auth = Zend_Auth::getInstance();
            if($auth->hasIdentity())
            {
                $loginUserId = $auth->getStorage()->read()->id;
            }
            $id = $this->_request->getParam('objid');
            $messages['message'] = '';
            $messages['msgtype'] = '';
            $count = 0;
            $actionflag = 3;
            if($id)
            {
                $servicedeskdepartmentmodel = new Default_Model_Servicedeskdepartment();
                $servicedeskrequestmodel = new Default_Model_Servicedeskrequest();
                $servicedeskconfmodel = new Default_Model_Servicedeskconf();
                $pendingRequestdata = $servicedeskconfmodel->getServiceReqDeptCount($id,1);
                if(!empty($pendingRequestdata))
                    $count = $pendingRequestdata[0]['count'];
                $service_req_cnt = $servicedeskrequestmodel->getReqCnt($id);
                if($service_req_cnt > 0)
                    $count = $count + $service_req_cnt;
                if($count < 1)
                {	
                    $servicedeskdepartmentdata = $servicedeskdepartmentmodel->getServiceDeskDepartmentDatabyID($id);
                    $data = array('isactive'=>0,'modifieddate'=>gmdate("Y-m-d H:i:s"));
                    $where = array('id=?'=>$id);
                    $reqwhere = array('service_desk_id=?'=>$id);
                    $Id = $servicedeskdepartmentmodel->SaveorUpdateServiceDeskDepartmentData($data, $where);
                    $RId = $servicedeskrequestmodel->SaveorUpdateServiceDeskRequestData($data, $reqwhere);
                    if($Id == 'update')
                    {
						$menuID = SERVICEDESKDEPARTMENT;
                        $result = sapp_Global::logManager($menuID,$actionflag,$loginUserId,$id);
                        $configmail = sapp_Global::send_configuration_mail('Category',$servicedeskdepartmentdata[0]['service_desk_name']);				   
                        $messages['message'] = 'Category deleted successfully.';
                        $messages['msgtype'] = 'success';
                    }   
                    else
                    {
                        $messages['message'] = 'Category cannot be deleted.';
                        $messages['msgtype'] = 'error';
                    }
                }
                else
                {
                    $messages['message'] = 'Category cannot be deleted.';
                    $messages['msgtype'] = 'error';
                } 				   
            }
            else
            { 
                $messages['message'] = 'Category cannot be deleted.';
                $messages['msgtype'] = 'error';
            }
            $this->_helper->json($messages);		
	}
	
public function addpopupAction()
	{
		Zend_Layout::getMvcInstance()->setLayoutPath(APPLICATION_PATH."/layouts/scripts/popup/");
		$auth = Zend_Auth::getInstance();
		if($auth->hasIdentity()){
			$loginUserId = $auth->getStorage()->read()->id;
		}
		$id = $this->getRequest()->getParam('id');

		$msgarray = array();
		$controllername = 'employeecomponents';
		$form = new Default_Form_employeecomponents();
		$model = new Default_Model_Employeecomponents();
		$form->setAction(BASE_URL.'employeecomponents/addpopup');
		if($this->getRequest()->getPost()){
			if($form->isValid($this->_request->getPost())){
			$id = $this->_request->getParam('id');
            $componentId = $this->_request->getParam('ComponentId');	
			///$description = $this->_request->getParam('description');
			$actionflag = '';
			$tableid  = ''; 
			   $data = array('ComponentId'=>$componentId, 
							// 'description'=>$description,
							  'modifiedby'=>$loginUserId,
							  'modifieddate'=>gmdate("Y-m-d H:i:s")
					);
				if($id!=''){
					$where = array('id=?'=>$id);  
					$actionflag = 2;
				}
				else
				{
					$data['createdby'] = $loginUserId;
					$data['createddate'] = gmdate("Y-m-d H:i:s");
					$data['isactive'] = 1;
					$where = '';
					$actionflag = 1;
				}
				$Id = $model->SaveorUpdateEmpcomponentData($data, $where);
				$tableid = $Id;
				$menuID = EMPLOYEECOMPONENTS;
				$result = sapp_Global::logManager($menuID,$actionflag,$loginUserId,$tableid);

				$empData = $model->getEmpcomponentData();
				$opt ='';
				foreach($empData as $record){
					$opt .= sapp_Global::selectOptionBuilder($record['id'], utf8_encode($record['service_desk_name']));
				}
				$this->view->departmentData = $opt;
					
				$this->view->eventact = 'added';
				$close = 'close';
				$this->view->popup=$close;
			}else
			{
				$messages = $form->getMessages();
				foreach ($messages as $key => $val)
				{
					foreach($val as $key2 => $val2)
					{
						$msgarray[$key] = $val2;
                                                break;
					}
				}
				$this->view->msgarray = $msgarray;
			}
		}
		$this->view->controllername = $controllername;
		$this->view->form = $form;
		$this->view->ermsg = '';
		$this->render('form');	
	}
	
	
	public function getrequestsAction()
    {
        $service_desk_id = $this->_getParam('service_desk_id',null);
        $service_desk_conf_id = $this->_getParam('service_desk_conf_id',null);
        $request_for_flag = $this->_getParam('request_for_flag',null);
        $data = array();
        $options = sapp_Global::selectOptionBuilder('', 'Select request', '');
        if($service_desk_id != '')
        {
        	if($request_for_flag!='') {
        		$request_for_param = explode('_',$service_desk_id);
        		if($request_for_param[1]==1) {
	        		$sd_dept_model = new Default_Model_Servicedeskdepartment();
		            $data = $sd_dept_model->getRequestsById($request_for_param[0]);
		            if(count($data) > 0)
		            {
		                foreach($data as $opt)
		                {                    
		                    $options .= sapp_Global::selectOptionBuilder($opt['id'], utf8_encode($opt['service_request_name']), '');
		                }
		            }
        			
        		}else{
        			$assetsModel = new Assets_Model_Assets();
        			$data = $assetsModel->getAllAssetForCategory($request_for_param[0]);
        			if(count($data) > 0)
        		    {
		                foreach($data as $opt)
		                {                    
		                    $options .= sapp_Global::selectOptionBuilder($opt['id'], utf8_encode($opt['name']), '');
		                }
		            }
        		}
        	}
        	else
        	{	
	            $sd_dept_model = new Default_Model_Servicedeskdepartment();
	            $data = $sd_dept_model->getRequestsById($service_desk_id);
	            if(count($data) > 0)
	            {
	                foreach($data as $opt)
	                {                    
	                    $options .= sapp_Global::selectOptionBuilder($opt['id'], utf8_encode($opt['service_request_name']), '');
	                }
	            }
        	}            
        }
        $attachment = false;
        if($service_desk_conf_id != '')
        {
        	
        	$sd_conf_model = new Default_Model_Servicedeskconf();
        	$sd_conf_data = $sd_conf_model->getServiceDeskConfbyID($service_desk_conf_id);
        	if(count($sd_conf_data)>0){
        		if($sd_conf_data[0]['attachment'] == 1)
        			$attachment = true;	
        	}
        }
        $this->_helper->json(array('options' => $options,'datacount'=>count($data),'attachment'=>$attachment));
    }// end of getrequests action
    
	
}
