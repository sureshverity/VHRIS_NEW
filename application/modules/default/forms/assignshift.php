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
class Default_Form_assignshift extends Zend_Form {
 public function init()
	{
		$this->setMethod('post');
		$this->setAttrib('id', 'formid');
		$this->setAttrib('name', 'assignshift');
                
                 $id = new Zend_Form_Element_Hidden('id');
        
        $postid = Zend_Controller_Front::getInstance()->getRequest()->getParam('id');
        
        $from_date=new Zend_Form_Element_Text("from_date");
        $from_date->setRequired(true);
        $from_date->setLabel("From Date");

        $to_date=new Zend_Form_Element_Text("to_date");
        $to_date->setRequired(true);
        $to_date->setLabel("To Date");

        $shift_id=new Zend_Form_Element_Select("shift_id");
        $shift_id->setRequired(true);
        $shift_id->setLabel("Shift Name");

     $emp_id=new Zend_Form_Element_Multiselect("emp_id");
        $emp_id->setRequired(true);
        $emp_id->setLabel("Select Employees");

    $submit = new Zend_Form_Element_Submit("submit");
	$submit->setAttrib("id", "submitbutton");
	$submit->setLabel("Save");
        
        $this->addElements(array($id,$from_date, $to_date,$shift_id,$emp_id ,$submit));
         $this->setElementDecorators(array('ViewHelper')); 
	}
}
