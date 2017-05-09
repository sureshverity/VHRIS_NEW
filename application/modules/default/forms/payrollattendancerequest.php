<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of payrollattendancerequest
 *
 * @author kumarsar
 */
class Default_Form_payrollattendancerequest extends Zend_Form {
 public function init()
	{
		$this->setMethod('post');
		$this->setAttrib('id', 'formid');
		$this->setAttrib('name', 'payrollattendancerequest');
                
                 $id = new Zend_Form_Element_Hidden('id');
        
        $postid = Zend_Controller_Front::getInstance()->getRequest()->getParam('id');
        
        $reason=new Zend_Form_Element_Select("reason");
        $reason->setRequired(true);
        $reason->setRegisterInArrayValidator(false);
        //$reason->addMultiOption(0, 'Select Reason');
//        $reason->addMultiOption(1, 'Client Meeting');
//        $reason->addMultiOption(2, 'Forgot Access Card');
//        $reason->addMultiOption(3, 'On Site');
        $reason->setLabel("Reason");
        $from_date = new Zend_Form_Element_Text("from_date");
        $from_date->setRequired(true);
        $from_date->setLabel("From Date");
        
        $to_date = new Zend_Form_Element_Text("to_date");
        $to_date->setRequired(true);
        $to_date->setLabel("To Date");        
              $in_time=new Zend_Form_Element_Text("in_time");
              $in_time->setRequired(true);
              $in_time->setAttrib("readOnly", true);
              $in_time->setLabel("In Time");
              $out_time=new Zend_Form_Element_Text("out_time");
              $out_time->setRequired(true);
              $out_time->setAttrib("readOnly", true);
              $out_time->setLabel("Out Time");
        $submit = new Zend_Form_Element_Submit("submit");
	$submit->setAttrib("id", "submitbutton");
	$submit->setLabel("Save");
        
        $this->addElements(array($id,$reason, $from_date, $to_date,$in_time,$out_time, $submit));
         $this->setElementDecorators(array('ViewHelper')); 
	}
}
