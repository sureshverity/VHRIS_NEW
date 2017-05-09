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

class Default_OnboardingcandidatesController extends Zend_Controller_Action
{

    private $options;
	public function preDispatch()
	{
		
	}
	
    public function init()
    {
		$orgInfoModel = new Default_Model_Organisationinfo();	
        $this->_options= $this->getInvokeArg('bootstrap')->getOptions();		
    }
	
	public function indexAction()
	{
		$empscreeningModel = new Default_Model_Onboardingcandidates();	
		 $call = $this->_getParam('call');
		if($call == 'ajaxcall')
				$this->_helper->layout->disableLayout();
		
		$view = Zend_Layout::getMvcInstance()->getView();		
		$objname = $this->_getParam('objname');
		$refresh = $this->_getParam('refresh');
		$data = array();
		$searchQuery = '';
		$searchArray = array();
		$tablecontent = '';
		$dashboardcall = $this->_getParam('dashboardcall');
		$statusidstring = $this->_request->getParam('status');
                
		$unitId = '';
		if(!isset($statusidstring) || $statusidstring=='')
		{
			$unitId = $this->_request->getParam('unitId');
			$statusidstring = $unitId;
		}
		$formgrid = 'true';
		if(isset($unitId) && $unitId != '') $formgrid = 'true';
		$statusid =  sapp_Global::_decrypt($statusidstring);
                if($statusid != '1' && $statusid != '2')
		{
			$statusidstring = sapp_Global::_encrypt(1);
		}	
		$queryflag = '';
		unset($_SESSION['emp_status']);
		if($statusid !='')
		{
			$_SESSION['emp_status'] = $statusidstring;
			if($statusid == '1'){
				$queryflag = '1';
				$this->view->ermsg = '';
			}else if($statusid == '2'){
				$queryflag = '2';		
				$this->view->ermsg = '';
			}else {
				$this->view->ermsg = 'nodata';
				$queryflag = '1';
			}
        }else $queryflag = '1';
		
		
		if($refresh == 'refresh')
		{
			if($dashboardcall == 'Yes')
				$perPage = DASHBOARD_PERPAGE;
			else	
				$perPage = PERPAGE;
			$sort = 'DESC';$by = 'me.modifieddate';$pageNo = 1;$searchData = '';$searchQuery = '';$searchArray='';
		}
		else 
		{
			$sort = ($this->_getParam('sort') !='')? $this->_getParam('sort'):'DESC';
			$by = ($this->_getParam('by')!='')? $this->_getParam('by'):'me.modifieddate';
			if($dashboardcall == 'Yes')
				$perPage = $this->_getParam('per_page',DASHBOARD_PERPAGE);
			else $perPage = $this->_getParam('per_page',PERPAGE);
			$pageNo = $this->_getParam('page', 1);
			$searchData = $this->_getParam('searchData');	
			$searchData = rtrim($searchData,',');						
			$searchData = $this->_getParam('searchData');				
		}
		$dataTmp = $empscreeningModel->getGrid($sort, $by, $perPage, $pageNo, $searchData,$call,$dashboardcall, $queryflag,$statusidstring,$formgrid,$unitId);
		//print_r($dataTmp); exit;
                array_push($data,$dataTmp);
		$this->view->dataArray = $data;
                $this->view->call = $call ;
		$this->view->statusidstring = $statusidstring;
		$this->view->messages = $this->_helper->flashMessenger->getMessages();
	}
	public function viewAction()
	{
            $auth = Zend_Auth::getInstance();
     	if($auth->hasIdentity()){
			$loginUserId = $auth->getStorage()->read()->id;
		}
		$idData = $this->getRequest()->getParam('id');
		$empscreeningform = new Default_Form_empscreening();
	    $empscreeningModel = new Default_Model_Empscreening();
            $usermodel  = new Default_Model_Users();
		$processData = array();	
		$idArr = array();
		$idArr = explode('-',$idData);
		if(sizeof($idArr)>1)
		{
			$id = intVal($idArr[0]);
			$userflag = intVal($idArr[1]); 
			$idData = $id.'-'.$userflag;
		}else{
			$id ='';$userflag = '';$idData = '';
		}		
		if($userflag == 2) $flag = 'cand'; else $flag = 'emp';
		if($userflag == 1 || ($userflag == 2 && sapp_Global::_isactivemodule(RESOURCEREQUISITION)))  
		{			
			if($id && $id != $loginUserId)
			{
				$data = $empscreeningModel->getsingleEmpscreeningData($id,$userflag);
                               // print_r($data); exit;
				if(!empty($data) && $data != 'norows')
				{
                                        $candidatedata = $usermodel->getEmpDetailsByEmailAddress($personalData[0][emailid]);
                                        $userid = $candidatedata[0][id];
					$empscreeningform->setAttrib('action',BASE_URL.'employee/edit/id/'.$idData);			
					$empscreeningform->removeElement("employee");
					$empscreeningform->removeElement("checktype");
					$empscreeningform->removeElement("checkagency");
					$empscreeningform->populate($data);	
					$elements = $empscreeningform->getElements();
					if(count($elements)>0)
					{
						foreach($elements as $key=>$element)
						{
							if(($key!="Cancel")&&($key!="Edit")&&($key!="Delete")&&($key!="Attachments")){
							$element->setAttrib("disabled", "disabled");
								}
						}
					}
					$specimenId = $data['specimen_id'];
					$empData = array();			
					if(isset($specimenId) && isset($flag))
					{
						$personalData = $empscreeningModel->getEmpPersonalData($specimenId,$flag);
						$addressData = $empscreeningModel->getEmpAddressData($specimenId,$flag);
						$companyData = $empscreeningModel->getEmpCompanyData($specimenId,$flag);
					}
					$this->view->personalData = $personalData; 
					$this->view->addressData = $addressData;
					$this->view->companyData = $companyData;
					$this->view->ermsg = '';
					if($idData!='')
					$processData = $this->processesGrid($idData,$personalData[0]['ustatus']);
					$this->view->dataArray = $processData;
					$this->view->form = $empscreeningform;
				}else{					
					$this->view->ermsg = 'nodata';
				}
			}else{
				$this->view->ermsg = 'nodata';
			}			
		}else{
			$this->view->ermsg = 'nodata';
		}
	}
        
	
    
}