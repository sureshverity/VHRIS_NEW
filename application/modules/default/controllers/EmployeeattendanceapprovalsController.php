<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EmployeeattendanceapprovalsrovalsController
 *
 * @author suresh
 */
class Default_EmployeeattendanceapprovalsController extends Zend_Controller_Action {
 private $options;
	public function preDispatch()
	{
		 
		
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
		}
		$leaverequestmodel = new Default_Model_Employeeattendanceapprovals();
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
			$sort = 'DESC';$by = 'createddate';$pageNo = 1;$searchData = '';
		}
		else 
		{
			$sort = ($this->_getParam('sort') !='')? $this->_getParam('sort'):'DESC';
			$by = ($this->_getParam('by')!='')? $this->_getParam('by'):'createddate';
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
		
							
		$objName = 'employeeattendanceapprovals';
		$queryflag = '';
		$dataTmp = $leaverequestmodel->getGrid($sort, $by, $perPage, $pageNo, $searchData,$call,$dashboardcall,$objName,$queryflag);
		
		array_push($data,$dataTmp);
		$this->view->dataArray = $data;
		$this->view->call = $call ;
		$this->view->messages = $this->_helper->flashMessenger->getMessages();
    }
	
     public function viewAction()
	{	
	    $auth = Zend_Auth::getInstance();
     		if($auth->hasIdentity()){
					$loginUserId = $auth->getStorage()->read()->id;
			}
		$id = $this->getRequest()->getParam('id');

		$callval = $this->getRequest()->getParam('call');
		if($callval == 'ajaxcall')
			$this->_helper->layout->disableLayout();
                
		$objName = 'employeeattendanceapprovals';
		$managerleaverequestform = new Default_Form_employeeattendanceapprovals();
		$managerleaverequestform->removeElement("submit");
		$elements = $managerleaverequestform->getElements();
                //print_r($elements);exit;
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
			    if($id && is_numeric($id) && $id>0)
                {
                	//$this->_redirect('employeeattendanceapprovals/edit/id/'.$id);				
					$leaverequestmodel = new Default_Model_Employeeattendanceapprovals();
					$usersmodel= new Default_Model_Users();
					$flag = 'true'; 
					
					$userid = $leaverequestmodel->getUserID($id);
                                        
//					$getreportingManagerArr = $leaverequestmodel->getReportingManagerId($id);
//					$reportingManager = $getreportingManagerArr[0]['repmanager'];
//					if($reportingManager != $loginUserId)
//					   $flag = 'false';
					if(!empty($userid))
					 $isactiveuser = $usersmodel->getUserDetailsByID($userid[0]['emp_id']);
                                     
					else
					 $this->view->rowexist = "rows"; 
                                        
					
					if(!empty($userid) && !empty($isactiveuser) && $flag == 'true')
					{ 
						$data = $leaverequestmodel->getLeaveRequestDetails($id);
                                                //print_r($data);exit;
                                                 //print_r($data[0]['status']);exit;
						
                                                
								$data = $data[0];
								$employeeleavetypemodel = new Default_Model_Payrollattendancerequest();
								$usersmodel = new Default_Model_Users();
										
								$employeeleavetypeArr = $employeeleavetypemodel->getRequestTypesbyId($data['reason']);
                                                                
								if($employeeleavetypeArr !='norows')
								{
                                                                   
									$managerleaverequestform->leavetypeid->addMultiOption($employeeleavetypeArr[0]['id'],utf8_encode($employeeleavetypeArr[0]['name']));		   
                                                                         //print_r($employeeleavetypeArr);exit;
                                                                        
								}
								  
//								if($data['leaveday'] == 1)
//								{
//								  $managerleaverequestform->leaveday->addMultiOption($data['leaveday'],'Full Day');		   
//								}
//								else 
//								{
//								  $managerleaverequestform->leaveday->addMultiOption($data['leaveday'],'Half Day');
//								}					
                        
								$employeenameArr = $usersmodel->getUserDetailsByID($data['emp_id']);	
                                                                if($data["status"]==0)
                                                                {
                                                                $status="Pending";
                                                                }
                                                                elseif ($data["status"]==2) {
                                                                $status="Approved";
                                                            }
                                                              elseif ($data["status"]==3) {
                                                                $status="Rejected";
                                                            }
                                                                elseif ($data["status"]==4) {
                                                                $status="Cancelled";
                                                            }
                                                            else{
                                                                $status="";
                                                            }
                                                            $data["status"]=$status;
                                                                //print_r($employeenameArr);exit;
								$managerleaverequestform->populate($data);							
															
															$from_date = sapp_Global::change_date($data['from_date'], 'view');
															$to_date = sapp_Global::change_date($data['to_date'], 'view');
                                                                                                                        $intime=$data["in_time"];
                                                                                                                               $outtime=$data["out_time"];
															$appliedon = sapp_Global::change_date($data['createddate'], 'view');
															
								$managerleaverequestform->from_date->setValue($from_date);
								$managerleaverequestform->to_date->setValue($to_date);
                                                                $managerleaverequestform->in_time->setValue($intime);
                                                                $managerleaverequestform->out_time->setValue($outtime);
								$managerleaverequestform->createddate->setValue($appliedon);
								$managerleaverequestform->appliedleavesdaycount->setValue($data['appliedleavescount']);
								$managerleaverequestform->employeename->setValue($employeenameArr[0]['userfullname']);
								$managerleaverequestform->setDefault('leavetypeid',$data['leavetypeid']);
								$managerleaverequestform->setDefault('leaveday',$data['leaveday']);
								$this->view->controllername = $objName;
								$this->view->id = $id;
								$this->view->form = $managerleaverequestform;
								$this->view->data = $data;


					}
					else
					{
						   $this->view->rowexist = "rows";
					}
				}
				else
				{
					   $this->view->rowexist = "rows";
				}

        }
        catch(Exception $e)
		{
			 $this->view->rowexist = 'norows';
		}

	}


	public function editAction()
	{
	    $auth = Zend_Auth::getInstance();
            $reporting = array();
		if($auth->hasIdentity()){
				$loginUserId = $auth->getStorage()->read()->id;
		}
		$id = $this->getRequest()->getParam('id');
		$callval = $this->getRequest()->getParam('call');
		if($callval == 'ajaxcall')
			$this->_helper->layout->disableLayout();


		try
		{
		        if($id && is_numeric($id) && $id>0)
                {                          $usermodel = new Default_Model_Users();
                                        $managerleaverequestform = new Default_Form_employeeattendanceapprovals();
					$leaverequestmodel = new Default_Model_Employeeattendanceapprovals();
					$usersmodel= new Default_Model_Users();
					$flag = 'true';
					$userid = $leaverequestmodel->getUserID($id);

					$getreportingManagerArr = $leaverequestmodel->getReportingManagerId($userid[0][emp_id]);
					$reportingManagerid = $getreportingManagerArr[0]['repmanager'];
                                        $reportingmgremail = $usermodel->getUserDetails($reportingManagerid);
                                        $reporting['reportmgremail'] = $reportingmgremail[0][emailaddress];
                                        $reporting['reportmgrname'] = $reportingmgremail[0][userfullname];
                                        //print_r($reportingmgremail); exit;
//					if($reportingManager != $loginUserId)
//					   $flag = 'false';
					if(!empty($userid))
					 $isactiveuser = $usersmodel->getUserDetailsByID($userid[0]['emp_id']);
					else
					 $this->view->rowexist = "rows";

					if(!empty($userid) && !empty($isactiveuser) && $flag=='true')
					{
						$data = $leaverequestmodel->getLeaveRequestDetails($id);
						if($data[0]['status'] == 0 || $data[0]['status'] == 1)
							{
								$data = $data[0];
                                                                //print_r($data);exit;
								$reason = $data['reason'];
								//$appliedleavescount = $data['appliedleavescount'];
								$employeeid = $data['emp_id'];
								//$leavetypeid = $data['leavetypeid'];
								$employeeleavetypemodel = new Default_Model_Payrollattendancerequest();
								$usersmodel = new Default_Model_Users();
								$employeesmodel = new Default_Model_Employees();
								$businessunitid = '';
								$loggedInEmployeeDetails = $employeesmodel->getLoggedInEmployeeDetails($employeeid);
								 if($loggedInEmployeeDetails[0]['businessunit_id'] != '')
									$businessunitid = $loggedInEmployeeDetails[0]['businessunit_id'];

							$employeeleavetypeArr = $employeeleavetypemodel->getRequestTypesbyId($data['reason']);

								if($employeeleavetypeArr != 'norows')
								{
									$managerleaverequestform->leavetypeid->addMultiOption($employeeleavetypeArr[0]['id'],utf8_encode($employeeleavetypeArr[0]['name']));
									$data['leavetypeid']=$employeeleavetypeArr[0]['name'];
								}
								else
                                                        {
								   $data['leavetypeid'] ="...";
						        }

//								if($data['leaveday'] == 1)
//								{
//								  $managerleaverequestform->leaveday->addMultiOption($data['leaveday'],'Full Day');
//								  $data['leaveday']=	'Full Day';
//								}
//								else
//								{
//								  $managerleaverequestform->leaveday->addMultiOption($data['leaveday'],'Half Day');
//								  $data['leaveday']='Half Day';
//								}

								$employeenameArr = $usersmodel->getUserDetailsByID($data['emp_id']);
								$employeeemail = $employeenameArr[0]['emailaddress'];
								$employeename = $employeenameArr[0]['userfullname'];
								$managerleaverequestform->populate($data);

								if($data['status'] == '1') {
									$managerleaverequestform->managerstatus->setLabel("Cancel");
									$managerleaverequestform->managerstatus->clearMultiOptions();
                                                                        $managerleaverequestform->managerstatus->addMultiOption(3,utf8_encode("Cancel"));
								}

															$from_date = sapp_Global::change_date($data['from_date'], 'view');
															$to_date = sapp_Global::change_date($data['to_date'], 'view');
                                                                                                                       $intime=$data["in_time"];
                                                                                                                               $outtime=$data["out_time"];
															$appliedon = sapp_Global::change_date($data['createddate'], 'view');
								//$managerleaverequestform->leavetypeid->setValue($data['leavetypeid']);
								$managerleaverequestform->from_date->setValue($from_date);
								$managerleaverequestform->to_date->setValue($to_date);
                                                                $managerleaverequestform->in_time->setValue($intime);
                                                                $managerleaverequestform->out_time->setValue($outtime);
								$managerleaverequestform->createddate->setValue($appliedon);
								//$managerleaverequestform->appliedleavesdaycount->setValue($data['appliedleavescount']);
								$managerleaverequestform->employeename->setValue($employeenameArr[0]['userfullname']);
								$managerleaverequestform->setDefault('leavetypeid',$data['leavetypeid']);
								//$managerleaverequestform->setDefault('leaveday',$data['leaveday']);
								$this->view->id = $id;
								$this->view->form = $managerleaverequestform;
								$this->view->data = $data;
								$managerleaverequestform->setAttrib('action',BASE_URL.'employeeattendanceapprovals/edit/id/'.$id);
							}
							else
							{
								   $this->view->rowexist = "rows";
							}
					}
					else
					{
						   $this->view->rowexist = "rows";
					}
                }
				else
				{
					   $this->view->rowexist = "rows";
				}

		}
		catch(Exception $e)
		{
			 $this->view->rowexist = 'norows';
		}
		if($this->getRequest()->getPost()){
      		$result = $this->save($managerleaverequestform,$appliedleavescount,$employeeemail,$employeeid,$employeename,$from_date,$to_date,$reason,$businessunitid,$leavetypeid,$data,$reporting);
		    $this->view->msgarray = $result;
		}
	}

	public function save($managerleaverequestform,$appliedleavescount,$employeeemail,$employeeid,$userfullname,$from_date,$to_date,$reason,$businessunitid,$leavetypeid,$leavereqdata,$reporting)
	{

		$auth = Zend_Auth::getInstance();
     	if($auth->hasIdentity()){
					$loginUserId = $auth->getStorage()->read()->id;
		}
     		if($managerleaverequestform->isValid($this->_request->getPost())){
			    $id = $this->_request->getParam('id');
                            $managerstatus = $this->_request->getParam('managerstatus');
				$date = new Zend_Date();
				$leaverequestmodel = new Default_Model_Employeeattendanceapprovals();
                                $leaveData  = $leaverequestmodel -> getLeaveRequestDetails($id);

                                $from_date = $leaveData[0]["from_date"];
                                $to_date = $leaveData[0]["to_date"];

                                $in_time = $leaveData[0]["in_time"];
                                $out_time = $leaveData[0]["out_time"];
                                $repMgrEmail = $reporting[reportmgremail];
                                $repMgrName = $reporting[reportmgrname];
				$employeeleavetypesmodel = new Default_Model_Employeeleavetypes();
                                $employeesmodel = new Default_Model_Employees();
                                $loggedInEmployeeDetails = $employeesmodel->getLoggedInEmployeeDetails($employeeid);

				$usersmodel = new Default_Model_Users();
				$actionflag = '';
				$tableid  = '';
				$status = '';
				$messagestr = '';
				$reason_attendance = $leavereqdata[leavetypeid];
				if($managerstatus == 1 )
				{
				  		$addattendance=$leaverequestmodel->addtbeventlog($id,$loggedInEmployeeDetails[0]["biometricId"],$employeeid,$from_date,$to_date,$in_time,$out_time);
				  	//$updateemployeeleave = $leaverequestmodel->updateemployeeattendancerequest($employeeid,$managerstatus);

				  $status = 2;
				  $messagestr = "Attendance request approved.";

				  //$leavetypetext = $leavetypeArr[0]['leavetype'];
				}else if($managerstatus == 2)
				{
				  $status = 3;
				  $messagestr = "Attendance request rejected.";
				}else if($managerstatus == 3 && !empty($leavetypeArr))
				{
					$status = 4;
				  	$messagestr = "Attendance request cancelled.";
				}

				  if($managerstatus == 1 || $managerstatus == 2 || $managerstatus == 3)
				  {
				   $data = array( 'status'=>$status,

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
						$Id = $leaverequestmodel->SaveorUpdateLeaveRequest($data, $where);
						    if($Id == 'update')
							{
							   $tableid = $id;
							   $this->_helper->getHelper("FlashMessenger")->addMessage($messagestr);
							}   
							else
							{
							   $tableid = $Id; 	
								$this->_helper->getHelper("FlashMessenger")->addMessage($messagestr);					   
							}
								
									
					
                            /** MAILING CODE **/
							
							if($to_date == '' || $to_date == NULL)
								$to_date = $from_date;
								
							
							/* Mail to Employee */
								$options['header'] = 'Attendance Request';
								$options['toEmail'] = $employeeemail;
								$options['toName'] = $userfullname;
								if($messagestr == 'Attendance request approved.'){
									$options['subject'] = $messagestr;
									$options['message'] = '<div>Hi,</div><div>The below Attendance(s) has been approved.</div>';
								}elseif($messagestr == 'Attendance request rejected.'){ 
									$options['subject'] = $messagestr;
									$options['message'] = '<div>Hi,</div><div>The below Attendance(s) has been rejected. </div>';
								}else{
									$options['subject'] = $messagestr;
									$options['message'] = '<div>Hi,</div><div>The below Attendance(s) has been cancelled. </div>';
								}	
								$options['message'] .= '<div>
                <table width="100%" cellspacing="0" cellpadding="15" border="0" style="border:3px solid #BBBBBB; font-size:16px; font-family:Arial, Helvetica, sans-serif; margin:30px 0 30px 0;" bgcolor="#ffffff">
                      <tbody><tr bgcolor="#e9f6fc">
                        <td width="28%" style="border-right:2px solid #BBBBBB;">Employee Name</td>
                        <td width="72%">'.$userfullname.'</td>
                      </tr>
                     
                      <tr>
                        <td style="border-right:2px solid #BBBBBB;">From</td>
                        <td>'.$from_date.'</td>
                      </tr>
                      <tr bgcolor="#e9f6fc">
                        <td style="border-right:2px solid #BBBBBB;">To</td>
                        <td>'.$to_date.'</td>
            	     </tr>
                      <tr>
                        <td style="border-right:2px solid #BBBBBB;">In Time</td>
                        <td>'.$in_time.'</td>
                      </tr>
                      
                      <tr bgcolor="#e9f6fc">
                        <td style="border-right:2px solid #BBBBBB;">Out Time</td>
                        <td>'.$out_time.'</td>
            	     </tr>
                      <tr >
                        <td style="border-right:2px solid #BBBBBB;">Reason</td>
                        <td>'.$reason_attendance.'</td>
                  </tr>
                </tbody></table>

            </div>
            <div style="padding:20px 0 10px 0;">Please <a href="'.BASE_URL.'/index/popup" target="_blank" style="color:#b3512f;">click here</a> to login and check the Attendance details.</div>';	
                                $result = sapp_Global::_sendEmail($options);
							/* END */	
                                
                                
		/* Mail to Reporting Manager */
            if(!empty($repMgrEmail) && !empty($repMgrName)) {                    
								$options['header'] = 'Attendance Request';
								$options['toEmail'] = $repMgrEmail;
								$options['toName'] = $repMgrName;
								if($messagestr == 'Attendance request approved.'){
									$options['subject'] = $messagestr;
									$options['message'] = '<div>Hello '.$repMgrName.',</div><div>The below Attendance(s) has been approved.</div>';
								}elseif($messagestr == 'Attendance request rejected.'){ 
									$options['subject'] = $messagestr;
									$options['message'] = '<div>Hello '.$repMgrName.',</div><div>The below Attendance(s) has been rejected. </div>';
								}else{
									$options['subject'] = $messagestr;
									$options['message'] = '<div>Hello '.$repMgrName.',</div><div>The below Attendance(s) has been cancelled. </div>';
								}	
								$options['message'] .= '<div>
                <table width="100%" cellspacing="0" cellpadding="15" border="0" style="border:3px solid #BBBBBB; font-size:16px; font-family:Arial, Helvetica, sans-serif; margin:30px 0 30px 0;" bgcolor="#ffffff">
                      <tbody><tr bgcolor="#e9f6fc">
                        <td width="28%" style="border-right:2px solid #BBBBBB;">Employee Name</td>
                        <td width="72%">'.$userfullname.'</td>
                      </tr>
                     
                      <tr>
                        <td style="border-right:2px solid #BBBBBB;">From</td>
                        <td>'.$from_date.'</td>
                      </tr>
                       <tr bgcolor="#e9f6fc">
                        <td style="border-right:2px solid #BBBBBB;">To</td>
                        <td>'.$to_date.'</td>
            	     </tr>
                      <tr>
                        <td style="border-right:2px solid #BBBBBB;">In Time</td>
                        <td>'.$in_time.'</td>
                      </tr>
                     
                      <tr bgcolor="#e9f6fc">
                        <td style="border-right:2px solid #BBBBBB;">Out Time</td>
                        <td>'.$out_time.'</td>
            	     </tr>
                      <tr>
                        <td style="border-right:2px solid #BBBBBB;">Reason for Leave</td>
                        <td>'.$reason_attendance.'</td>
                  </tr>
                </tbody></table>

            </div>
            <div style="padding:20px 0 10px 0;">Please <a href="'.BASE_URL.'/index/popup" target="_blank" style="color:#b3512f;">click here</a> to login and check the Attendance details.</div>';	
                                $result = sapp_Global::_sendEmail($options);
            }                    
							/* END */                                
							
							/* Mail to HR */	
//								if (defined('LV_HR_'.$businessunitid) && $businessunitid !='')
//								{
//								$options['header'] = 'Attendance Request';
//								$options['toEmail'] = constant('LV_HR_'.$businessunitid);
//								$options['toName'] = 'Attendance Management';
//								if($messagestr == 'Attendance request approved.'){
//									$options['subject'] = $messagestr;
//									$options['message'] = '<div>Hi,</div><div>The below Attendance(s) has been approved.</div>';
//								}elseif($messagestr == 'Attendance request rejected.'){ 
//									$options['subject'] = $messagestr;
//									$options['message'] = '<div>Hi,</div><div>The below Attendance(s) has been rejected. </div>';
//								}else{
//									$options['subject'] = $messagestr;
//									$options['message'] = '<div>Hi,</div><div>The below Attendance(s) has been cancelled. </div>';
//								}	
//								$options['message'] .= '<div>
//                <table width="100%" cellspacing="0" cellpadding="15" border="0" style="border:3px solid #BBBBBB; font-size:16px; font-family:Arial, Helvetica, sans-serif; margin:30px 0 30px 0;" bgcolor="#ffffff">
//                      <tbody><tr>
//                        <td width="28%" style="border-right:2px solid #BBBBBB;">Employee Name</td>
//                        <td width="72%">'.$userfullname.'</td>
//                      </tr>
//                     
//                      <tr>
//                        <td style="border-right:2px solid #BBBBBB;">From</td>
//                        <td>'.$from_date.'</td>
//                      </tr>
//                       <tr bgcolor="#e9f6fc">
//                        <td style="border-right:2px solid #BBBBBB;">To</td>
//                        <td>'.$to_date.'</td>
//            	     </tr>
//                      <tr>
//                        <td style="border-right:2px solid #BBBBBB;">In Time</td>
//                        <td>'.$in_time.'</td>
//                      </tr>
//                     
//                      <tr bgcolor="#e9f6fc">
//                        <td style="border-right:2px solid #BBBBBB;">Out Time</td>
//                        <td>'.$out_time.'</td>
//            	     </tr>
//                      <tr bgcolor="#e9f6fc">
//                        <td style="border-right:2px solid #BBBBBB;">Reason for Leave</td>
//                        <td>'.$reason_attendance.'</td>
//                  </tr>
//                </tbody></table>
//
//            </div>
//            <div style="padding:20px 0 10px 0;">Please <a href="'.BASE_URL.'/index/popup" target="_blank" style="color:#b3512f;">click here</a> to login and check the leave details.</div>';	
//                                $result = sapp_Global::_sendEmail($options);	
//								}
							/* END */	
						$menuID = EMPLOYEELEAVEAPPROVALS;
						$result = sapp_Global::logManager($menuID,$actionflag,$loginUserId,$tableid);
	    			    $this->_redirect('employeeattendanceapprovals');
					}	
							
			}else
			{
     			$messages = $managerleaverequestform->getMessages();
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
     		if($auth->hasIdentity()){
					$loginUserId = $auth->getStorage()->read()->id;
				}
		 $id = $this->_request->getParam('objid');
		 $messages['message'] = '';
		 $actionflag = 3;
		    if($id)
			{
			$holidaygroupsmodel = new Default_Model_Holidaygroups(); 
			  $data = array('isactive'=>0);
			  $where = array('id=?'=>$id);
			  $Id = $holidaygroupsmodel->SaveorUpdateGroupData($data, $where);
			    if($Id == 'update')
				{
				   $menuID = HOLIDAYGROUPS;
				   $result = sapp_Global::logManager($menuID,$actionflag,$loginUserId,$id); 
				   $messages['message'] = 'Holiday group deleted successfully.';
				}   
				else
                   $messages['message'] = 'Holiday group cannot be deleted.';				
			}
			else
			{ 
			 $messages['message'] = 'Holiday group cannot be deleted.';
			}
			$this->_helper->json($messages);
		
	}
	

}
