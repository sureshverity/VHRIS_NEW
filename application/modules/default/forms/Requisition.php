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
 * This form is used in Requisition screen.
 * @author K.Rama Krishna 
 */
class Default_Form_Requisition extends Zend_Form
{
    public function init()
    {
        $this->setMethod('post');
        $this->setAttrib('id', 'formid');
        $this->setAttrib('name', 'requisition');
        $auth = Zend_Auth::getInstance();
        if($auth->hasIdentity())
        {
            $loginUserId = $auth->getStorage()->read()->id;
            $loginuserRole = $auth->getStorage()->read()->emprole;
            $loginuserGroup = $auth->getStorage()->read()->group_id;
        }
        $id = new Zend_Form_Element_Hidden('id');
        
        $requisition_code = new Zend_Form_Element_Text('requisition_code');
        $requisition_code->setAttrib('title', 'Requisition Code.'); 
        $requisition_code->setAttrib("class", "form-control");
        $requisition_code->setAttrib('readonly', 'readonly');
        $requisition_code->setAttrib('onfocus', 'this.blur()'); 
        $requisition_code->setRequired(true);        
        $requisition_code->addValidator('NotEmpty', false, array('messages' => 'Identity codes are not configured yet.'));
        
        $onboard_date = new Zend_Form_Element_Text('onboard_date');        
        $onboard_date->setAttrib('title', 'Due Date.'); 
        $onboard_date->setAttrib("class", "form-control");
        $onboard_date->setAttrib('maxLength', 10);
        $onboard_date->setAttrib('readonly', 'readonly');
        $onboard_date->setAttrib('onfocus', 'this.blur()');  		
       
        
        $business_unit = new Zend_Form_Element_Select("business_unit");
        $business_unit->setAttrib("class", "form-control select");
        $business_unit->setAttrib("onchange", "getdepts_req(this,'department','position_id');");
        $business_unit->setAttrib('title', 'Business Unit.');  
        
        $department = new Zend_Form_Element_Select("department");
        $department->setAttrib("class", "form-control select");
       
        $department->setAttrib('title', 'Department.');  
        $department->addMultiOptions(array(''=>'Select Department'));
		
		$department->setAttrib('onchange', 'displayEmpReportingmanagers(this,"reporting_id","req")');
		
        $jobtitle = new Zend_Form_Element_Select("jobtitle");
        $jobtitle->setAttrib("class", "form-control select");
        $jobtitle->setAttrib("onchange", "getpositions_req('department','business_unit','position_id','jobtitle');");
        $jobtitle->setAttrib('title', 'Job Title.');  
       
        
        $reporting_id = new Zend_Form_Element_Select("reporting_id");
        $reporting_id->setAttrib("class", "form-control select");
        $reporting_id->setAttrib('title', 'Reporting Manager.');         
	$reporting_id->setRegisterInArrayValidator(false);  
        $reporting_id->addMultiOptions(array(''=>'Select Reporting Manager'));
        
        $position_id = new Zend_Form_Element_Select("position_id");
        $position_id->setAttrib("class", "form-control select");
        $position_id->setAttrib('title', 'Position.');  
        $position_id->addMultiOptions(array(''=>'Select Position'));
        
        $req_no_positions = new Zend_Form_Element_Text('req_no_positions');
        $req_no_positions->setAttrib('maxLength', 4);
        $req_no_positions->setAttrib("class", "form-control");
        $req_no_positions->setAttrib('title', 'Required no.of positions.');        
        $req_no_positions->addFilter(new Zend_Filter_StringTrim());
       
        
        $jobdescription = new Zend_Form_Element_Textarea('jobdescription');
        $jobdescription->setAttrib('rows', 4);
        $jobdescription->setAttrib('cols', 30);
        $jobdescription->setAttrib("class", "form-control");
        
        $jobdescription->setAttrib('title', 'Job description.');
        
        $req_skills = new Zend_Form_Element_Textarea('req_skills');
       $req_skills->setAttrib('rows', 4);
        $req_skills->setAttrib('cols', 30);
        $req_skills->setAttrib("class", "form-control");
        $req_skills->setAttrib('maxlength', 400);
        $req_skills->setAttrib('title', 'Required Skills.');
        
        
		$req_qualification = new Zend_Form_Element_Text('req_qualification');
        $req_qualification->setAttrib('maxLength', 100);
        $req_qualification->setAttrib("class", "form-control");
        $req_qualification->setAttrib('title', 'Required Qualification.');        
        $req_qualification->addFilter(new Zend_Filter_StringTrim());
       
        
        $req_exp_years = new Zend_Form_Element_Text('req_exp_years');
        $req_exp_years->setAttrib('maxLength', 5);
        $req_exp_years->setAttrib("class", "form-control");
        $req_exp_years->setAttrib('title', 'Required Experience.');        
        $req_exp_years->addFilter(new Zend_Filter_StringTrim());
                                       
        
        $emp_type = new Zend_Form_Element_Select("emp_type");
        $emp_type->setAttrib("class", "form-control");
        $emp_type->setAttrib('title', 'Employment Status.');  
        
        $req_priority = new Zend_Form_Element_Select("req_priority");
        $req_priority->setAttrib("class", "form-control select");
        $req_priority->setAttrib('title', 'Priority.'); 
        $req_priority->addMultiOptions(array('' => 'Select Priority',1 => 'High',
                                            3 => 'Low',2 => 'Medium'));	
       
      
        
        $additional_info = new Zend_Form_Element_Textarea('additional_info');
        $additional_info->setAttrib('rows', 4);
        $additional_info->setAttrib('cols', 30);
        $additional_info->setAttrib("class", "form-control");
        $additional_info->setAttrib('maxlength', 400);
        $additional_info->setAttrib('title', 'Additional Information.');   
			
        $req_status = new Zend_Form_Element_Select('req_status');
        $req_status->setLabel('Requisition Status');
	 $req_status->setAttrib("class", "form-control select");	
        
        if($loginuserGroup == HR_GROUP || $loginuserGroup == '' || $loginuserGroup == MANAGEMENT_GROUP)
        {
                  
            $reporting_id->setAttrib("class", "form-control");
            $reporting_id->setRequired(true);        
            $reporting_id->addValidator('NotEmpty', false, array('messages' => 'Please select reporting manager.'));
        }
		        
        $onboard_date->setRequired(true);
        $onboard_date->addValidator('NotEmpty', false, array('messages' => 'Please select due date.'));  
       
        $department->setRegisterInArrayValidator(false);        
        $department->setRequired(true);
        $department->addValidator('NotEmpty', false, array('messages' => 'Please select department.')); 
        $jobtitle->setRegisterInArrayValidator(false);        
        $jobtitle->setRequired(true);
        $jobtitle->addValidator('NotEmpty', false, array('messages' => 'Please select job title.')); 
        $position_id->setRegisterInArrayValidator(false);        
        $position_id->setRequired(true);
        $position_id->addValidator('NotEmpty', false, array('messages' => 'Please select position.'));
        $req_no_positions->setRequired(true);
        $req_no_positions->addValidator('NotEmpty', false, array('messages' => 'Please enter required no.of positions.'));                                  
        $req_no_positions->addValidator("regex",true,array(
                           'pattern'=>'/^([0-9]+?)+$/',
                           
                           'messages'=>array(
                                   'regexNotMatch'=>'Please enter only numbers.'
                           )
        ));
        $req_no_positions->addValidator("greaterThan",true,array(
                           'min'=>0,                                           
                           'messages'=>array(
                                   'notGreaterThan'=>'No.of positions cannot be zero.'
                           )
        ));
        
        $req_skills->setRequired(true);
        $req_skills->addValidator('NotEmpty', false, array('messages' => 'Please enter required skills.')); 
        $req_qualification->setRequired(true);
        $req_qualification->addValidator('NotEmpty', false, array('messages' => 'Please enter required qualification.'));  
        $req_qualification->addValidator("regex",true,array(                           
                           'pattern'=>'/^[a-zA-Z][a-zA-Z\/\-\. ?]+$/',
                           'messages'=>array(
                                   'regexNotMatch'=>'Please enter valid qualification.'
                           )
        ));
        $req_exp_years->setRequired(true);
        $req_exp_years->addValidator('NotEmpty', false, array('messages' => 'Please enter required experience range.'));  
        $req_exp_years->addValidator("regex",true,array(                           
                           
                            'pattern'=>'/^([0-9]{1,2}\-[0-9]{1,2})+$/',
                            'messages'=>array(
                                   'regexNotMatch'=>'Please enter valid experience range.'
                           )
        )); 
        $emp_type->setRegisterInArrayValidator(false);        
        $emp_type->setRequired(true);
        $emp_type->addValidator('NotEmpty', false, array('messages' => 'Please select employment status.'));
        $req_priority->setRequired(true);
        $req_priority->addValidator('NotEmpty', false, array('messages' => 'Please select priority.'));
        
		
		
        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setAttrib('id', 'submitbutton');
        $submit->setAttrib("class", "btn btn-primary pull-right");
        $submit->setLabel('Save');

        $email_cnt = new Zend_Form_Element_Hidden('email_cnt');
        $idval = Zend_Controller_Front::getInstance()->getRequest()->getParam('id',null);
        $bunit_val = Zend_Controller_Front::getInstance()->getRequest()->getParam('business_unit',null);
        
        
        $approver1 = new Zend_Form_Element_Select("approver1"); 
        $approver1->setAttrib("class", "form-control select");
	$approver1->setRegisterInArrayValidator(false);          
        $approver1->addMultiOptions(array(''=>'Select Approver -1'));
        $approver1->setRequired(true);
        $approver1->addValidator('NotEmpty', false, array('messages' => 'Please select approver-1.'));
        
        $approver2 = new Zend_Form_Element_Select("approver2");
        $approver2->setAttrib("class", "form-control select");
	$approver2->setRegisterInArrayValidator(false);  
        $approver2->addMultiOptions(array(''=>'Select Approver -2'));
        
        $approver3 = new Zend_Form_Element_Select("approver3"); 
        $approver3->setAttrib("class", "form-control select");
	$approver3->setRegisterInArrayValidator(false);  
        $approver3->addMultiOptions(array(''=>'Select Approver -3'));
        
        $this->addElements(array($id,$submit,$requisition_code,$onboard_date,$business_unit,$department,$jobtitle,
                                 $reporting_id,$position_id,$req_no_positions,$jobdescription,$req_skills,
                                 $req_qualification,$req_exp_years,$emp_type,$req_priority,$additional_info,
                                 $req_status,$email_cnt,$approver1,$approver2,$approver3));
        $this->setElementDecorators(array('ViewHelper')); 
    }
}