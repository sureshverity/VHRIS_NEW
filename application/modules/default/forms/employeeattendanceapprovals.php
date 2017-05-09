<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Em
 *
 * @author kumarsar
 */
class Default_Form_employeeattendanceapprovals extends Zend_Form {
  public function init()
	{
		$this->setMethod('post');
		$this->setAttrib('id', 'formid');
		$this->setAttrib('name', 'employeeleaveapprovals');


        $id = new Zend_Form_Element_Hidden('id');
			
		$appliedleavesdaycount = new Zend_Form_Element_Text('appliedleavesdaycount');
        $appliedleavesdaycount->setAttrib('readonly', 'true');
		$appliedleavesdaycount->setAttrib('onfocus', 'this.blur()');
		
		$employeename = new Zend_Form_Element_Text('employeename');
        $employeename->setAttrib('readonly', 'true');
		$employeename->setAttrib('onfocus', 'this.blur()');
		
		$managerstatus = new Zend_Form_Element_Select('managerstatus');
		$managerstatus->setLabel("Approve or Reject or Cancel");
        $managerstatus->setRegisterInArrayValidator(false);
        $managerstatus->setMultiOptions(array(							
							'1'=>'Approve' ,
							'2'=>'Reject',
        					'3'=>'Cancel',
							));
				
		$comments = new Zend_Form_Element_Textarea('comments');
		$comments->setLabel("Comments");
        $comments->setAttrib('rows', 10);
        $comments->setAttrib('cols', 50);
		$comments ->setAttrib('maxlength', '50');
							
		$leavetypeid = new Zend_Form_Element_Select('leavetypeid');
        $leavetypeid->setAttrib('class', 'selectoption');
        $leavetypeid->setRegisterInArrayValidator(false);
		$leavetypeid->setAttrib('readonly', 'true');
		$leavetypeid->setAttrib('onfocus', 'this.blur()');
               
       	
							
        $from_date = new Zend_Form_Element_Text('from_date');
        $from_date->setAttrib('readonly', 'true');
		$from_date->setAttrib('onfocus', 'this.blur()');
        
        $to_date = new Zend_Form_Element_Text('to_date');
        $to_date->setAttrib('readonly', 'true'); 
        $to_date->setAttrib('onfocus', 'this.blur()'); 
        
$in_time = new Zend_Form_Element_Text('in_time');
        $in_time->setAttrib('readonly', 'true'); 
        $in_time->setAttrib('onfocus', 'this.blur()');
        
$out_time = new Zend_Form_Element_Text('out_time');
        $out_time->setAttrib('readonly', 'true'); 
        $out_time->setAttrib('onfocus', 'this.blur()');        
        
		$status = new Zend_Form_Element_Text('status');
        $status->setAttrib('readonly', 'true');
		$status->setAttrib('onfocus', 'this.blur()');
		
		
		$leavestatus = new Zend_Form_Element_Text('leavestatus');
        $leavestatus->setAttrib('readonly', 'true');
		$leavestatus->setAttrib('onfocus', 'this.blur()');
		
		$createddate = new Zend_Form_Element_Text('createddate');
        $createddate->setAttrib('readonly', 'true');
		$createddate->setAttrib('onfocus', 'this.blur()');

        $submit = new Zend_Form_Element_Submit('submit');
		$submit->setAttrib('id', 'submitbutton');
		$submit->setLabel('Save');

		$this->addElements(array($id,$employeename,$managerstatus,$comments,$reason,$status,$leaveday,$from_date,$to_date,$in_time,$out_time,$leavetypeid,$appliedleavesdaycount,$leavestatus,$createddate,$submit));
        $this->setElementDecorators(array('ViewHelper'));
      	 
	}
}
