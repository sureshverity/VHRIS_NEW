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
 * This gives employee report form.
 */
class Default_Form_Payrollreport extends Zend_Form
{
    public function init()
    {
        $this->setMethod('post');
        $this->setAttrib('id', 'formid');
        $this->setAttrib('name', 'frm_auser_report');

        $period = new Zend_Form_Element_Select("period");        
        $period->setLabel("Payroll Period");
        
        
	$year = new Zend_Form_Element_Select("year");
        $year->setRegisterInArrayValidator(false);
        $year->setLabel("Year");                     
        
        $bunit = new Zend_Form_Element_Select("bunit");
        $bunit->setRegisterInArrayValidator(false);
        $bunit->setLabel("Buiness Unit");
        
        $department = new Zend_Form_Element_Select("department");
        $department->setLabel("Department");
        
        
        $submit = new Zend_Form_Element_Button('submit');        
        $submit->setAttrib('id', 'idsubmitbutton');
        $submit->setLabel('Report'); 
        
        $this->addElements(array($submit,$period,$year,$bunit,$department));
        $this->setElementDecorators(array('ViewHelper')); 
    }
}