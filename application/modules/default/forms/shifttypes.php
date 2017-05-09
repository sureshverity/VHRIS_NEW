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
class Default_Form_shifttypes extends Zend_Form {
 public function init()
	{
		$this->setMethod('post');
		$this->setAttrib('id', 'formid');
		$this->setAttrib('name', 'shifttypes');
                
                 $id = new Zend_Form_Element_Hidden('id');
        
        $postid = Zend_Controller_Front::getInstance()->getRequest()->getParam('id');
        
        $shiftname=new Zend_Form_Element_Text("shift_name");
        $shiftname->setRequired(true);
        $shiftname->setLabel("Shift Name");

     $shortname=new Zend_Form_Element_Text("shortname");
     $shortname->setRequired(true);
     $shortname->setLabel("Short Name");

     $ondutytime=new Zend_Form_Element_Text("onduty_time");
     $ondutytime->setRequired(true);
     $ondutytime->setLabel("On Duty Time");

     $offdutytime=new Zend_Form_Element_Text("offduty_time");
     $offdutytime->setRequired(true);
     $offdutytime->setLabel("Off Duty Time");

     $late_mins=new Zend_Form_Element_Text("late_min");
     $late_mins->setRequired(true);
     $late_mins->setLabel("Late (min's)");

     $leave_early=new Zend_Form_Element_Text("leaveearly_min");
     $leave_early->setRequired(true);
     $leave_early->setLabel("Leave Early");

     $beginintime=new Zend_Form_Element_Text("begin_intime");
     $beginintime->setRequired(true);
     $beginintime->setLabel("Begin Intime");

     $endintime=new Zend_Form_Element_Text("end_intime");
     $endintime->setRequired(true);
     $endintime->setLabel("End Intime");

     $beginout=new Zend_Form_Element_Text("begin_outtime");
     $beginout->setRequired(true);
     $beginout->setLabel("Begin Out");

     $endout=new Zend_Form_Element_Text("end_outtime");
     $endout->setRequired(true);
     $endout->setLabel("End Out");

     $colorcode=new Zend_Form_Element_Text("color_code");
     $colorcode->setRequired(true);
     //$colorcode->setAttrib("readOnly", true);
     $colorcode->setLabel("Color");

     $isnightshift=new Zend_Form_Element_Checkbox("is_nightshift");
     $isnightshift->setRequired(true);
     $isnightshift->setLabel("Is Night Shift");

     $includecompanyholidays=new Zend_Form_Element_Checkbox("include_company_holidays");
     $includecompanyholidays->setRequired(true);
     $includecompanyholidays->setLabel("Include Company Holidays");

     $includeweekoffs=new Zend_Form_Element_Checkbox("include_weekoff");
     $includeweekoffs->setRequired(true);
     $includeweekoffs->setLabel("Include Week Off's");

    $submit = new Zend_Form_Element_Submit("submit");
	$submit->setAttrib("id", "submitbutton");
	$submit->setLabel("Save");
        
        $this->addElements(array($id,$shiftname, $shortname, $ondutytime,$offdutytime,$late_mins,$leave_early,$beginintime,$endintime,$beginout,$endout,$colorcode,$isnightshift,$includecompanyholidays,$includeweekoffs,$submit));
         $this->setElementDecorators(array('ViewHelper')); 
	}
}
