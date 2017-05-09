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

class Default_AssignshiftController extends Zend_Controller_Action
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

        $callval = $this->getRequest()->getParam('call');
        if($callval == 'ajaxcall')
            $this->_helper->layout->disableLayout();
        $addform = new Default_Form_assignshift();
        // $addModel = new Default_Model_Shifttypes();

        $msgarray = array();
        $addform->setAttrib('action',BASE_URL.'assignshift');
        $this->view->form = $addform;

        if($this->getRequest()->getPost()){
            $result = $this->save($addform);
            $this->view->msgarray = $result;
        }
        $this->render('form');

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
        $addform = new Default_Form_assignshift();
       // $addModel = new Default_Model_Shifttypes();

        $msgarray = array();
        $addform->setAttrib('action',BASE_URL.'shifttypes/add');
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
        $objName = 'shifttypes';
        $form = new Default_Form_shifttypes();
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
                    $model = new Default_Model_Shifttypes();
                    $data = $model->getShiftDatabyID($id);
                    if(!empty($data))
                    {
                        $data = $data[0];
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

        $editform = new Default_Form_shifttypes();

        $editModel = new Default_Model_Shifttypes();


        $editform->submit->setLabel('Update');
        try
        {
            if($id)
            {

                if(is_numeric($id) && $id>0)
                {
                    $data = $editModel->getShiftDatabyID($id);
                   // print_r($data);exit;

                    if(!empty($data))
                    {
                        $data = $data[0];

                        $editform->populate($data);

                        $editform->setAttrib('action',BASE_URL.'shifttypes/edit/id/'.$id);
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
        $shiftmodel = new Default_Model_Shifttypes();
        $msgarray = array();

        if($empform->isValid($this->_request->getPost())){
            try{
                $id = $this->_request->getParam('id');
                $shiftname = $this->_request->getParam('shift_name');
                $shortname = $this->_request->getParam('shortname');
                $ondutytime = $this->_request->getParam('onduty_time');
                $offdutytime = $this->_request->getParam('offduty_time');
                $late_mins = $this->_request->getParam('late_min');
                $leave_early = $this->_request->getParam('leaveearly_min');
                $beginintime = $this->_request->getParam('begin_intime');
                $endintime = $this->_request->getParam('end_intime');
                $beginout = $this->_request->getParam('begin_outtime');
                $endout = $this->_request->getParam('end_outtime');
                $colorcode = $this->_request->getParam('color_code');
                $isnightshift = $this->_request->getParam('is_nightshift');
                $includecompanyholidays = $this->_request->getParam('include_company_holidays');
                $includeweekoffs = $this->_request->getParam('include_weekoff');

                $actionflag = '';
                $tableid  = '';
                $data = array('shift_name'=>$shiftname,
                    'shortname'=>$shortname,
                    'onduty_time'=>$ondutytime,
                    'offduty_time'=>$offdutytime,
                    'late_min'=>$late_mins,
                    'leaveearly_min'=>$leave_early,
                    'begin_intime'=>$beginintime,
                    'end_intime'=>$endintime,
                    'begin_outtime'=>$beginout,
                    'end_outtime'=>$endout,
                    'color_code'=>$colorcode,
                    'is_nightshift'=>$isnightshift,
                    'include_company_holidays'=>$includecompanyholidays,
                    'include_weekoff'=>$includeweekoffs,
                    'modifiedby'=>$loginUserId,
                    'modifieddate'=> gmdate("Y-m-d H:i:s")
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

                $Id = $shiftmodel->SaveorUpdateShiftData($data, $where);

                if($Id == 'update')
                {
                    $tableid = $id;
                    $this->_helper->getHelper("FlashMessenger")->addMessage(array("success"=>"Shift updated successfully."));
                }
                else
                {
                    $tableid = $Id;
                    $this->_helper->getHelper("FlashMessenger")->addMessage(array("success"=>"Shift added successfully."));
                }
                $menuID = SHIFTTYPES;
                $result = sapp_Global::logManager($menuID,$actionflag,$loginUserId,$tableid);
                $this->_redirect('shifttypes');
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




}
