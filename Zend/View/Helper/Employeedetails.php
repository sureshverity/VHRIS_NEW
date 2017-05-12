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
 * Breadcrumbs View Helper
 *
 * A View Helper that creates the menu
 *
 *
 */
class Zend_View_Helper_Employeedetails extends Zend_View_Helper_Abstract {


	public  function employeedetails($emparr,$conText,$userId)
	{
		
		
		$session=new Zend_Auth_Storage_Session();
		$data=$session->read();
		$loggedinuser = $data['id'];
		$group_id = $data['group_id'];
                $userrole = $data['emprole'];
		$empdata = '';
		$employeetabsStr = '';

		$empdata  ='<div class="ml-alert-1-success" id="empdetailsmsgdiv" style="display:none;">';
		$empdata .='<div class="style-1-icon success" style="display:block;"></div>';
		$empdata .='<div id="successtext"></div>';
		$empdata .='</div>';
		$empdata .= '<div class="col-md-3">';
        $empdata .= '<div class="panel panel-default">';
        
		$employeeModal = new Default_Model_Employee();
		$employessunderEmpId = $employeeModal->getEmployeesUnderRM($userId);
		if(!empty($employessunderEmpId))
		$empdata .= '<input type="hidden" value="true" id="hasteam" name="hasteam" />';
		else
		$empdata .= '<input type="hidden" value="false" id="hasteam" name="hasteam" />';
		if($conText == 'edit' || $conText == 'view')
		{
		 
			//If the user has BG status as "Yet to start" then we should enable the link....
			$usersModel = new Default_Model_Users();
			$bgstatusArr = $usersModel->getBGstatus($userId);
			if(!empty($bgstatusArr)&& isset($bgstatusArr) && $bgstatusArr[0]['group_id'] != MANAGEMENT_GROUP)
			{
				if($bgstatusArr[0]['isactive'] == 1)
				$empdata .= '<div id="hrbgchecklink" style="display:none;" class="action-to-page"><a href="'.BASE_URL.'/empscreening/checkscreeningstatus/empid/'.$userId.'">Send for Background Check</a></div>';
			}
		
		}
		$empdata .= '<div class="panel-body profile music4">';
		if($emparr['profileimg']!=''){
			$empdata .=	'<div class="profile-image" > <img id="userImage" src="'.DOMAIN.("public/uploads/profile/").$emparr['profileimg'].'" onerror="this.src=\''.DOMAIN.'public/media/images/default-profile-pic.jpg\'"/></div>';
		}
		else{
			$empdata .=	'<div class="profile-image" ><img id="userImage" src="'.DOMAIN.'public/media/images/employee-deafult-pic.jpg" /> </div>';
		}
		/**
		** Active/inactve buttons 18-03-2015
		** should not be available in my details page
		** should not be available for org head inHR > Emp page
		** should be available for all employee, Manager, HR, Sys Adm employees, for Super Admin, Management and HR
		**/
        //if($conText != 'mydetails' && $emparr['is_orghead'] != 1)
        //{
        //    if($group_id == HR_GROUP || $loggedinuser == SUPERADMIN || $group_id == MANAGEMENT_GROUP)//for activate inactivate user
        //    {
        //        $sel_act = $sel_dact = "";
        //        if($emparr['isactive'] < 2 && $emparr['emptemplock'] == 0)
        //        {
        //            if($emparr['isactive'] == 1)
        //            {
        //                $sel_act = "selected";
        //            }
        //            else if($emparr['isactive'] == 0)
        //            {
        //                $sel_dact = "selected";
        //            }
        //            /** disable the buttons for organization head **/

        //            $empdata .= '<p class="field switch"><label class="cb-enable  '.$sel_act.'"><span>Active</span></label><label class="cb-disable '.$sel_dact.'"><span>Inactive</span></label> </p>';
        //            if($sel_act == "selected")
        //            {
        //                $empdata .= "
        //                            <script type='text/javascript' language='javascript'>
        //                                $('.cb-disable').click(function(){              
        //                                    makeActiveInactive('inactive','".$emparr['id']."');
        //                                });
        //                            </script> ";
        //            }
        //            else if($sel_dact == "selected")
        //            {
        //                $empdata .= "
        //                            <script type='text/javascript' language='javascript'>
        //                                $('.cb-enable').click(function(){                
        //                                    makeActiveInactive('active','".$emparr['id']."');
        //                                });
        //                            </script> ";
        //            }
					
        //        }
        //        else if($emparr['isactive'] < 2 && $emparr['emptemplock'] == 1)
        //        {
        //            $sel_dact = "selected";$sel_act = "";
        //            $empdata .= '<p class="field switch"><label class="cb-enable  '.$sel_act.'"><span>Active</span></label><label class="cb-disable '.$sel_dact.'"><span>Inactive</span></label> </p>';
        //            $empdata .= "
        //                        <script type='text/javascript' language='javascript'>
        //                            $('.cb-enable').click(function(){                
        //                                makeActiveInactive('active','".$emparr['id']."');
        //                            });
									
        //                        </script>   
        //                        ";
        //        }
        //        else
        //        {
        //            $sel_dact = "selected";$sel_act = "";
        //            $empdata .= '<p class="field switch"><label class="cb-enable  '.$sel_act.'"><span>Active</span></label><label class="cb-disable '.$sel_dact.'"><span>Inactive</span></label> </p>';
        //            $empdata .= "
        //                        <script type='text/javascript' language='javascript'>
        //                            $('.cb-enable,.cb-disable').click(function(){                
        //                                makeActiveInactive('other','".$emparr['isactive']."');
        //                            });
									
        //                        </script>   
        //                        ";
        //        }
        //    }
        //}
		
		$empdata .= '<div class="profile-data">';

		/**
		** 18-03-2015
		** Change organization head should not be available in my details page
		**/
		if($conText != 'mydetails' && $emparr['is_orghead'] == '1') 
		{
			if($group_id == HR_GROUP || $loggedinuser == SUPERADMIN || $group_id == MANAGEMENT_GROUP)//To see Change Line Manager link
			{
				$headicon = '<span class="org-head-div"><img title="Organization head" src="'.DOMAIN.("public/media/images/org-head.png").'" class="org-head-icon" /><a class="change_orgn_head" href="'.BASE_URL.("employee/changeorghead/orgid/".$emparr['user_id']).'">Change Organization Head</a></span>';
				//$changeorghead = '<a href="'.BASE_URL.("/employee/changeorghead/orgid/".$emparr['user_id']).'">Change Organization head</a>';
			}
			else 
			{
				$headicon = '';
			}
		}	
		else 
		{
			$headicon = '';
			//$changeorghead = '';
		}	
		if(isset($emparr['active_prefix']) && isset($emparr['prefix']) && $emparr['active_prefix'] == 1 && $emparr['prefix'] !='')
		$empdata .=	'<div class="profile-data-name">'.$emparr['prefix'].'.&nbsp;'.$emparr['userfullname'].'</div>';
		else
		$empdata .=	'<div class="profile-data-name">'.$emparr['prefix'].'.&nbsp;'.$emparr['userfullname'].'</div>';
        $empdata .=	'<div class="profile-data-title" style="color: #FFF;">'.$emparr['employeeId'].'</div>';
		$empdata .=	'<div class="profile-data-title" style="color: #FFF;">'.$emparr['emailaddress'].'</div>';
        $empdata .=	'<div class="profile-data-title" style="color: #FFF;">'.$emparr['contactnumber'].'</div>';
        $empdata .= '</div>';
        if($conText != 'mydetails')
		{
			$empdata .= '<div class="profile-controls" ><a href="#" class="profile-control-left" onclick="gobacktocontroller(\''.$conText.'\');" ><span class="fa fa-arrow-left"></span></a></div>';
		}
        $empdata .= '</div>';


        // Profile End

        $empdata .= '<div class="panel-body">';
        $empdata .= '<div class="row">';
        $empdata .= '<div class="col-md-6"><label class="switch"> <input checked="checked" value="1" type="checkbox"><span></span></label>';
        $empdata .= '</div>';
        $empdata .= '<div class="col-md-6">';
        $empdata .= '</div>';
        $empdata .= '</div>';
        $empdata .= '</div>';
        // Employee Tab
        $empdata .= $this->newemployeetabs($conText,$userId);
        // Employee Tab End

        // Freinds and Photo Tab
        $empdata .= '<div class="panel-body" ><h4 class="text-title">Friends</h4>';
        $empdata .= '<div class="row">';
        $empdata .= '<div class="col-md-4 col-xs-4"><a href="#" class="friend"><img src="'.DOMAIN."public/media_new/assets/images/users/user.jpg".'"><span>Dmitry Ivaniuk</span> </a></div>';
        $empdata .= '<div class="col-md-4 col-xs-4"><a href="#" class="friend"><img src="'.DOMAIN."public/media_new/assets/images/users/user2.jpg".'"><span>John Doe</span> </a></div>';
        $empdata .= '<div class="col-md-4 col-xs-4"><a href="#" class="friend"><img src="'.DOMAIN."public/media_new/assets/images/users/user4.jpg".'"><span>Brad Pit</span> </a></div>';
        $empdata .= '</div>';
        $empdata .= '<div class="row">';
        $empdata .= '<div class="col-md-4 col-xs-4"><a href="#" class="friend"><img src="'.DOMAIN."public/media_new/assets/images/users/user5.jpg".'"><span>John Travolta</span> </a></div>';
        $empdata .= '<div class="col-md-4 col-xs-4"><a href="#" class="friend"><img src="'.DOMAIN."public/media_new/assets/images/users/user6.jpg".'"><span>Darth Vader</span> </a></div>';
        $empdata .= '<div class="col-md-4 col-xs-4"><a href="#" class="friend"><img src="'.DOMAIN."public/media_new/assets/images/users/user7.jpg".'"><span>Samuel Leroy Jackson</span> </a></div>';
        $empdata .= '</div>';

         $empdata .= '</div>';
        // Freinds and Photo Tab End
        $empdata .= '</div>';
        $empdata .= '</div>';
		$empdata .= '<div id="employeeContainer"  style="display: none; overflow: auto;">
						<div class="heading">
							<a href="javascript:void(0)">
								<img src="'. DOMAIN.'public/media/images/close.png" name="" align="right" border="0" hspace="3" vspace="5" class="closeAttachPopup" style="margin: -24px 8px 0 0;"> 
							</a>
						</div>
						<iframe id="employeeCont" name="employeeCont" class="business_units_iframe" frameborder="0"></iframe>
					</div>';
		

		echo $empdata;
		$empdata .= $this->employeetabs($conText,$userId);
        $empdata .= '</div>';
		echo $employeetabsStr;
	}
	public  function employeetabsold($conText,$userId)
	{
		$tabHeightClass="";
		$session=new Zend_Auth_Storage_Session();
		$data=$session->read();
		

		if(defined('EMPTABCONFIGS')) 
		 $empOrganizationTabs = explode(",",EMPTABCONFIGS);

		$loggedinuser = $data['id'];		$group_id = $data['group_id'];
                $isBoarding = false;
                if($data['emprole'] == 14)
                {
                    $isBoarding = true;
                }
		if ($conText == "mydetails")
		{
			$tabHeightClass="mydetails-height";
		}
		else if ($conText == "edit" || $conText == "view")
		{
			if($group_id == HR_GROUP || $loggedinuser == SUPERADMIN || $group_id == MANAGEMENT_GROUP )
			{
				$tabHeightClass="hr-employee-height";
			}
			else if($group_id == MANAGER_GROUP ||$group_id == EMPLOYEE_GROUP||$group_id == SYSTEMADMIN_GROUP)
			{
				$tabHeightClass="mydetails-height";
			}
		}

		$tabsHtml = '<div class="poc-ui-data-control" id="'.$tabHeightClass.'"><div class="left-block-ui-data"><div class="agency-ui"><ul>';
		if($conText == "edit")
		{
			
			//View all tabs with all privileges....	onclick - changeempeditscreen...
			
			$tabsHtml .= '<li  id="empdetails" onclick="changeeditscreen(\'employee\','.$userId .');">
				'.TAB_EMP_OFFICIAL.'</li>';
			
			if(!empty($empOrganizationTabs) && in_array("employeedocs", $empOrganizationTabs))
			$tabsHtml .= '<li id = "employeedocs" onclick="changemyempviewscreen(\'employeedocs\',\'index\','.$userId .');">'.TAB_EMP_DOCUMENTS.'<span class="beta_menu"></span></li>';
			
			if(!empty($empOrganizationTabs) && in_array("emp_leaves", $empOrganizationTabs))
			$tabsHtml .= '<li id = "emp_leaves" onclick="changeempeditscreen(\'empleaves\','.$userId .');">'.TAB_EMP_LEAVES.'</li>';

			if(!empty($empOrganizationTabs) && in_array("emp_holidays", $empOrganizationTabs))
			$tabsHtml .= '<li id = "emp_holidays" onclick="changeempeditscreen(\'empholidays\','.$userId .');">'.TAB_EMP_HOLIDAYS.'</li>';

			if(!empty($empOrganizationTabs) && in_array("emp_salary", $empOrganizationTabs))
			$tabsHtml .= '<li id = "emp_salary" onclick="changeempeditscreen(\'empsalarydetails\','.$userId .');">'.TAB_EMP_SALARY.'</li>';
			
			if(!empty($empOrganizationTabs) && in_array("emppersonaldetails", $empOrganizationTabs))
			$tabsHtml .= '<li id = "emppersonaldetails" onclick="changeempeditscreen(\'emppersonaldetails\','.$userId .');">'.TAB_EMP_PERSONAL.'</li>';

			if(!empty($empOrganizationTabs) && in_array("empcommunicationdetails", $empOrganizationTabs))
			$tabsHtml .= '<li id = "empcommunicationdetails" onclick="changeempeditscreen(\'empcommunicationdetails\','. $userId .');">'.TAB_EMP_CONTACT.'</li>
				';

			if(!empty($empOrganizationTabs) && in_array("emp_skills", $empOrganizationTabs))
			$tabsHtml .= '<li id = "emp_skills" onclick="changeempeditscreen(\'empskills\','.$userId .');">'.TAB_EMP_SKILLS.'</li>';

			if(!empty($empOrganizationTabs) && in_array("emp_jobhistory", $empOrganizationTabs))
			$tabsHtml .= '<li id = "emp_jobhistory" onclick="changeempeditscreen(\'empjobhistory\','.$userId .');">'.TAB_EMP_JOB_HISTORY.'</li>';

			if(!empty($empOrganizationTabs) && in_array("experience_details", $empOrganizationTabs))
			$tabsHtml .= '<li id= "experience_details" onclick="changeempeditscreen(\'experiencedetails\','.$userId .');">'.TAB_EMP_EXPERIENCE.'</li>';

			if(!empty($empOrganizationTabs) && in_array("education_details", $empOrganizationTabs))
			$tabsHtml .= '<li id = "education_details" onclick="changeempeditscreen(\'educationdetails\','.$userId .');">'.TAB_EMP_EDUCATION.'</li>';

			if(!empty($empOrganizationTabs) && in_array("trainingandcertification_details", $empOrganizationTabs))
			$tabsHtml .= '<li id = "trainingandcertification_details" onclick="changeempeditscreen(\'trainingandcertificationdetails\','.$userId .');">'.TAB_EMP_TRAINING_CERTIFY.'</li>';

			if(!empty($empOrganizationTabs) && in_array("medical_claims", $empOrganizationTabs))
			$tabsHtml .= '<li id = "medical_claims" onclick="changeempeditscreen(\'medicalclaims\','. $userId .');">'.TAB_EMP_MEDICAL_CLAIMS.'</li>';

			if(!empty($empOrganizationTabs) && in_array("disabilitydetails", $empOrganizationTabs))
			$tabsHtml .= '<li id = "disabilitydetails" onclick="changeempeditscreen(\'disabilitydetails\','.$userId .');">'.TAB_EMP_DISABILITY.'</li>';

			if(!empty($empOrganizationTabs) && in_array("dependency_details", $empOrganizationTabs))
			$tabsHtml .= '<li id = "dependency_details" onclick="changeempeditscreen(\'dependencydetails\','.$userId .');">'.TAB_EMP_DEPENDENCY.'</li>';

			if(!empty($empOrganizationTabs) && in_array("visadetails", $empOrganizationTabs))
			$tabsHtml .= '<li id="visadetails"onclick="changeempeditscreen(\'visaandimmigrationdetails\','.$userId .');">'.TAB_EMP_VISA_EMIGRATION.'</li>';

			if(!empty($empOrganizationTabs) && in_array("creditcarddetails", $empOrganizationTabs))
			$tabsHtml .= '<li id= "creditcarddetails" onclick="changeempeditscreen(\'creditcarddetails\','.$userId.');">'.TAB_EMP_CORPORATE_CARD.'</li>';

			if(!empty($empOrganizationTabs) && in_array("workeligibilitydetails", $empOrganizationTabs))
			$tabsHtml	.= '<li id="workeligibilitydetails" onclick="changeempeditscreen(\'workeligibilitydetails\','. $userId .');">'.TAB_EMP_WORK_ELIGIBILITY.'</li>';

			if(!empty($empOrganizationTabs) && in_array("emp_additional", $empOrganizationTabs))
			$tabsHtml .= '<li id = "emp_additional" onclick="changeempeditscreen(\'empadditionaldetails\','.$userId .');">'.TAB_EMP_ADDITIONAL.'</li>';
			
			//if(!empty($empOrganizationTabs) && in_array("emp_performanceappraisal", $empOrganizationTabs))
			//$tabsHtml .= '<li id = "emp_performanceappraisal" onclick="changeempeditscreen(\'empperformanceappraisal\','.$userId .');">'.TAB_EMP_PERFORMANCE_APPRAISAL.'</li>';
			
			if(!empty($empOrganizationTabs) && in_array("emp_payslips", $empOrganizationTabs))
			$tabsHtml .= '<li id = "emp_payslips" onclick="changeempeditscreen(\'emppayslips\','.$userId .');">'.TAB_EMP_PAY_SLIPS.'</li>';
			
			if(!empty($empOrganizationTabs) && in_array("emp_benifits", $empOrganizationTabs))
			$tabsHtml .= '<li id = "emp_benifits" onclick="changeempeditscreen(\'empbenefits\','.$userId .');">'.TAB_EMP_BENEFITS.'</li>';
			
			if(!empty($empOrganizationTabs) && in_array("emp_renumeration", $empOrganizationTabs))
			$tabsHtml .= '<li id = "emp_renumeration" onclick="changeempeditscreen(\'empremunerationdetails\','.$userId .');">'.TAB_EMP_REMUNERATION.'</li>';

			if(!empty($empOrganizationTabs) && in_array("emp_security", $empOrganizationTabs))
			$tabsHtml .= '<li id = "emp_security" onclick="changeempeditscreen(\'empsecuritycredentials\','.$userId .');">'.TAB_EMP_SECURITY_CREDENTIALS.'</li>';
			
			if(!empty($empOrganizationTabs) && in_array("assetdetails", $empOrganizationTabs))
			$tabsHtml .= '<li id= "assetdetails" onclick="changeempeditscreen(\'assetdetails\','.$userId.');">'.TAB_EMP_ASSETS.'</li>';
					
			
		}
		else if($conText == "view")
		{
		
			if($group_id == HR_GROUP ||$group_id == MANAGEMENT_GROUP || $loggedinuser == SUPERADMIN)
			{
				
				$tabsHtml .= '<li id="empdetails" onclick="changeviewscreen(\'employee\','.$userId .');">
				'.TAB_EMP_OFFICIAL.'</li>';
				
				if(!empty($empOrganizationTabs) && in_array("employeedocs", $empOrganizationTabs))
				$tabsHtml .= '<li id = "employeedocs" onclick="changemyempviewscreen(\'employeedocs\',\'view\','.$userId .');">'.TAB_EMP_DOCUMENTS.'<span class="beta_menu"></span></li>';

				if(!empty($empOrganizationTabs) && in_array("emp_leaves", $empOrganizationTabs))
				$tabsHtml .= '<li id = "emp_leaves" onclick="changeempviewscreen(\'empleaves\','.$userId .');">'.TAB_EMP_LEAVES.'</li>';

				if(!empty($empOrganizationTabs) && in_array("emp_holidays", $empOrganizationTabs))
				$tabsHtml .= '<li id = "emp_holidays" onclick="changeempviewscreen(\'empholidays\','.$userId .');">'.TAB_EMP_HOLIDAYS.'</li>';

				if(!empty($empOrganizationTabs) && in_array("emp_salary", $empOrganizationTabs))
				$tabsHtml .= '<li id = "emp_salary" onclick="changeempviewscreen(\'empsalarydetails\','.$userId .');">'.TAB_EMP_SALARY.'</li>';
								
				if(!empty($empOrganizationTabs) && in_array("emppersonaldetails", $empOrganizationTabs))
				$tabsHtml .= '<li id = "emppersonaldetails" onclick="changeempviewscreen(\'emppersonaldetails\','.$userId .');">'.TAB_EMP_PERSONAL.'</li>';

				if(!empty($empOrganizationTabs) && in_array("empcommunicationdetails", $empOrganizationTabs))
				$tabsHtml .= '<li id = "empcommunicationdetails" onclick="changeempviewscreen(\'empcommunicationdetails\','. $userId .');">'.TAB_EMP_CONTACT.'</li>
				';

				if(!empty($empOrganizationTabs) && in_array("emp_skills", $empOrganizationTabs))
				$tabsHtml .= '<li id = "emp_skills" onclick="changeempviewscreen(\'empskills\','.$userId .');">'.TAB_EMP_SKILLS.'</li>';

				if(!empty($empOrganizationTabs) && in_array("emp_jobhistory", $empOrganizationTabs))
				$tabsHtml .= '<li id = "emp_jobhistory" onclick="changeempviewscreen(\'empjobhistory\','.$userId .');">'.TAB_EMP_JOB_HISTORY.'</li>';

				if(!empty($empOrganizationTabs) && in_array("experience_details", $empOrganizationTabs))
				$tabsHtml .= '<li id= "experience_details" onclick="changeempviewscreen(\'experiencedetails\','.$userId .');">'.TAB_EMP_EXPERIENCE.'</li>';

				if(!empty($empOrganizationTabs) && in_array("education_details", $empOrganizationTabs))
				$tabsHtml .= '<li id = "education_details" onclick="changeempviewscreen(\'educationdetails\','.$userId .');">'.TAB_EMP_EDUCATION.'</li>';

				if(!empty($empOrganizationTabs) && in_array("trainingandcertification_details", $empOrganizationTabs))
				$tabsHtml .= '<li id = "trainingandcertification_details" onclick="changeempviewscreen(\'trainingandcertificationdetails\','.$userId .');">'.TAB_EMP_TRAINING_CERTIFY.'</li>';

				if(!empty($empOrganizationTabs) && in_array("medical_claims", $empOrganizationTabs))
				$tabsHtml .= '<li id = "medical_claims" onclick="changeempviewscreen(\'medicalclaims\','. $userId .');">'.TAB_EMP_MEDICAL_CLAIMS.'</li>';

				if(!empty($empOrganizationTabs) && in_array("disabilitydetails", $empOrganizationTabs))
				$tabsHtml .= '<li id = "disabilitydetails" onclick="changeempviewscreen(\'disabilitydetails\','.$userId .');">'.TAB_EMP_DISABILITY.'</li>';

				if(!empty($empOrganizationTabs) && in_array("dependency_details", $empOrganizationTabs))
				$tabsHtml .= '<li id = "dependency_details" onclick="changeempviewscreen(\'dependencydetails\','.$userId .');">'.TAB_EMP_DEPENDENCY.'</li>';

				if(!empty($empOrganizationTabs) && in_array("visadetails", $empOrganizationTabs))
				$tabsHtml .= '<li id="visadetails" onclick="changeempviewscreen(\'visaandimmigrationdetails\','.$userId .');">'.TAB_EMP_VISA_EMIGRATION.'</li>';

				if(!empty($empOrganizationTabs) && in_array("creditcarddetails", $empOrganizationTabs))
				$tabsHtml .= '<li id= "creditcarddetails" onclick="changeempviewscreen(\'creditcarddetails\','.$userId.');">'.TAB_EMP_CORPORATE_CARD.'</li>';

				if(!empty($empOrganizationTabs) && in_array("workeligibilitydetails", $empOrganizationTabs))
				$tabsHtml	.= '<li id="workeligibilitydetails" onclick="changeempviewscreen(\'workeligibilitydetails\','. $userId .');">'.TAB_EMP_WORK_ELIGIBILITY.'</li>';

				if(!empty($empOrganizationTabs) && in_array("emp_additional", $empOrganizationTabs))
				$tabsHtml .= '<li id = "emp_additional" onclick="changeempviewscreen(\'empadditionaldetails\','.$userId .');">'.TAB_EMP_ADDITIONAL.'</li>';
				
				//if(!empty($empOrganizationTabs) && in_array("emp_performanceappraisal", $empOrganizationTabs))
				//$tabsHtml .= '<li id = "emp_performanceappraisal" onclick="changeempviewscreen(\'empperformanceappraisal\','.$userId .');">'.TAB_EMP_PERFORMANCE_APPRAISAL.'</li>';

				if(!empty($empOrganizationTabs) && in_array("emp_payslips", $empOrganizationTabs))
				$tabsHtml .= '<li id = "emp_payslips" onclick="changeempviewscreen(\'emppayslips\','.$userId .');">'.TAB_EMP_PAY_SLIPS.'</li>';
				
				if(!empty($empOrganizationTabs) && in_array("emp_benifits", $empOrganizationTabs))
				$tabsHtml .= '<li id = "emp_benifits" onclick="changeempviewscreen(\'empbenefits\','.$userId .');">'.TAB_EMP_BENEFITS.'</li>';
				
				if(!empty($empOrganizationTabs) && in_array("emp_renumeration", $empOrganizationTabs))
				$tabsHtml .= '<li id = "emp_renumeration" onclick="changeempviewscreen(\'empremunerationdetails\','.$userId .');">'.TAB_EMP_REMUNERATION.'</li>';

				if(!empty($empOrganizationTabs) && in_array("emp_security", $empOrganizationTabs))
				$tabsHtml .= '<li id = "emp_security" onclick="changeempviewscreen(\'empsecuritycredentials\','.$userId .');">'.TAB_EMP_SECURITY_CREDENTIALS.'</li>';
				
				if(!empty($empOrganizationTabs) && in_array("assetdetails", $empOrganizationTabs))
					$tabsHtml .= '<li id= "assetdetails" onclick="changeempviewscreen(\'assetdetails\','.$userId.');">'.TAB_EMP_ASSETS.'</li>';
				
			}
			else if($group_id == MANAGER_GROUP ||$group_id == EMPLOYEE_GROUP||$group_id == SYSTEMADMIN_GROUP )
			{
				//View only 7 tabs with view privilege....	General Tabs...
				
				$tabsHtml .= '<li id="empdetails" onclick="changeviewscreen(\'employee\','.$userId .');">
				'.TAB_EMP_OFFICIAL.'</li>';
					
				if(!empty($empOrganizationTabs) && in_array("emppersonaldetails", $empOrganizationTabs))
				$tabsHtml .= '<li id = "emppersonaldetails" onclick="changeempviewscreen(\'emppersonaldetails\','.$userId .');">'.TAB_EMP_PERSONAL.'</li>';

				if(!empty($empOrganizationTabs) && in_array("empcommunicationdetails", $empOrganizationTabs))
				$tabsHtml .= '<li id = "empcommunicationdetails" onclick="changeempviewscreen(\'empcommunicationdetails\','. $userId .');">'.TAB_EMP_CONTACT.'</li>
				';

				if(!empty($empOrganizationTabs) && in_array("emp_skills", $empOrganizationTabs))
				$tabsHtml .= '<li id = "emp_skills" onclick="changeempviewscreen(\'empskills\','.$userId .');">'.TAB_EMP_SKILLS.'</li>';

				if(!empty($empOrganizationTabs) && in_array("emp_jobhistory", $empOrganizationTabs))
				$tabsHtml .= '<li id = "emp_jobhistory" onclick="changeempviewscreen(\'empjobhistory\','.$userId .');">'.TAB_EMP_JOB_HISTORY.'</li>';

				if(!empty($empOrganizationTabs) && in_array("experience_details", $empOrganizationTabs))
				$tabsHtml .= '<li id= "experience_details" onclick="changeempviewscreen(\'experiencedetails\','.$userId .');">'.TAB_EMP_EXPERIENCE.'</li>';

				if(!empty($empOrganizationTabs) && in_array("education_details", $empOrganizationTabs))
				$tabsHtml .= '<li id = "education_details" onclick="changeempviewscreen(\'educationdetails\','.$userId .');">'.TAB_EMP_EDUCATION.'</li>';
				

				if(!empty($empOrganizationTabs) && in_array("trainingandcertification_details", $empOrganizationTabs))
				$tabsHtml .= '<li id = "trainingandcertification_details" onclick="changeempviewscreen(\'trainingandcertificationdetails\','.$userId .');">'.TAB_EMP_TRAINING_CERTIFY.'</li>';

			}
                        else if($group_id == USERS_GROUP)
                        {
                            $tabsHtml .= '<li id="empdetails" onclick="changeviewscreen(\'employee\','.$userId .');">
				'.TAB_EMP_OFFICIAL.'</li>';
				if(!empty($empOrganizationTabs) && in_array("employeedocs", $empOrganizationTabs))
				$tabsHtml .= '<li id = "employeedocs" onclick="changemyempviewscreen(\'employeedocs\',\'view\','.$userId .');">'.TAB_EMP_DOCUMENTS.'<span class="beta_menu"></span></li>';
                                
				if(!empty($empOrganizationTabs) && in_array("emppersonaldetails", $empOrganizationTabs))
				$tabsHtml .= '<li id = "emppersonaldetails" onclick="changeempviewscreen(\'emppersonaldetails\','.$userId .');">'.TAB_EMP_PERSONAL.'</li>';

				if(!empty($empOrganizationTabs) && in_array("empcommunicationdetails", $empOrganizationTabs))
				$tabsHtml .= '<li id = "empcommunicationdetails" onclick="changeempviewscreen(\'empcommunicationdetails\','. $userId .');">'.TAB_EMP_CONTACT.'</li>
				';

				if(!empty($empOrganizationTabs) && in_array("experience_details", $empOrganizationTabs))
				$tabsHtml .= '<li id= "experience_details" onclick="changeempviewscreen(\'experiencedetails\','.$userId .');">'.TAB_EMP_EXPERIENCE.'</li>';

				if(!empty($empOrganizationTabs) && in_array("education_details", $empOrganizationTabs))
				$tabsHtml .= '<li id = "education_details" onclick="changeempviewscreen(\'educationdetails\','.$userId .');">'.TAB_EMP_EDUCATION.'</li>';
				

				if(!empty($empOrganizationTabs) && in_array("trainingandcertification_details", $empOrganizationTabs))
				$tabsHtml .= '<li id = "trainingandcertification_details" onclick="changeempviewscreen(\'trainingandcertificationdetails\','.$userId .');">'.TAB_EMP_TRAINING_CERTIFY.'</li>';
                        }
				
				
		}
		else if($conText == "mydetails" && $isBoarding == false)
		{
			
			$tabsHtml .= '<li id="empdetails"><a href="'.BASE_URL.'mydetails">'.TAB_EMP_OFFICIAL.'</a></li>';
			
			if(!empty($empOrganizationTabs) && in_array("employeedocs", $empOrganizationTabs))
			$tabsHtml .= '<li id = "employeedocs"><a href="'.BASE_URL.'mydetails/documents">'.TAB_EMP_DOCUMENTS.'</a><span class="beta_menu"></span></li>';

			if(!empty($empOrganizationTabs) && in_array("emp_leaves", $empOrganizationTabs))
			$tabsHtml .='<li id = "emp_leaves"><a href="'.BASE_URL.'mydetails/leaves">'.TAB_EMP_LEAVES.'</a></li>';
			
			if(!empty($empOrganizationTabs) && in_array("emp_salary", $empOrganizationTabs))
			$tabsHtml .= '<li id = "emp_salary"><a href="'.BASE_URL .'mydetails/salarydetailsview">'.TAB_EMP_SALARY.'</a></li>';
			
			if(!empty($empOrganizationTabs) && in_array("emppersonaldetails", $empOrganizationTabs))
			$tabsHtml .='<li id = "emppersonaldetails"><a href="'.BASE_URL.'mydetails/personaldetailsview">'.TAB_EMP_PERSONAL.'</a></li>';
				
			if(!empty($empOrganizationTabs) && in_array("empcommunicationdetails", $empOrganizationTabs))
			$tabsHtml .='<li id = "empcommunicationdetails" ><a href="'.BASE_URL.'mydetails/communicationdetailsview">'.TAB_EMP_CONTACT.'</a></li>';

			if(!empty($empOrganizationTabs) && in_array("emp_skills", $empOrganizationTabs))
			$tabsHtml .='<li id = "emp_skills"><a href="'.BASE_URL.'mydetails/skills">'.TAB_EMP_SKILLS.'</a></li>';
				
			if(!empty($empOrganizationTabs) && in_array("emp_jobhistory", $empOrganizationTabs))
			$tabsHtml .= '<li id = "emp_jobhistory"><a href="'.BASE_URL.'mydetails/jobhistory">'.TAB_EMP_JOB_HISTORY.'</a></li>';
				
			if(!empty($empOrganizationTabs) && in_array("experience_details", $empOrganizationTabs))
			$tabsHtml .='<li id= "experience_details"><a href="'.BASE_URL.'mydetails/experience">'.TAB_EMP_EXPERIENCE.'</a></li>';

			if(!empty($empOrganizationTabs) && in_array("education_details", $empOrganizationTabs))
			$tabsHtml .='<li id = "education_details"><a href="'.BASE_URL.'mydetails/education">'.TAB_EMP_EDUCATION.'</a></li>';

			if(!empty($empOrganizationTabs) && in_array("trainingandcertification_details", $empOrganizationTabs))
			$tabsHtml .='<li id = "trainingandcertification_details"><a href="'.BASE_URL.'mydetails/certification">'.TAB_EMP_TRAINING_CERTIFY.'</a></li>';

			if(!empty($empOrganizationTabs) && in_array("medical_claims", $empOrganizationTabs))
			$tabsHtml .='<li id = "medical_claims"><a href="'.BASE_URL.'mydetails/medicalclaims">'.TAB_EMP_MEDICAL_CLAIMS.'</a></li>';

			if(!empty($empOrganizationTabs) && in_array("disabilitydetails", $empOrganizationTabs))
			$tabsHtml .='<li id = "disabilitydetails"><a href="'.BASE_URL.'mydetails/disabilitydetailsview">'.TAB_EMP_DISABILITY.'</a></li>';

			if(!empty($empOrganizationTabs) && in_array("dependency_details", $empOrganizationTabs))
			$tabsHtml .='<li id = "dependency_details"><a href="'.BASE_URL.'mydetails/dependency">'.TAB_EMP_DEPENDENCY.'</a></li>';

			if(!empty($empOrganizationTabs) && in_array("visadetails", $empOrganizationTabs))
			$tabsHtml .='<li id="visadetails"><a href="'.BASE_URL.'mydetails/visadetailsview">'.TAB_EMP_VISA_EMIGRATION.'</a></li>';

			if(!empty($empOrganizationTabs) && in_array("creditcarddetails", $empOrganizationTabs))
			$tabsHtml .='<li id= "creditcarddetails"><a href="'.BASE_URL.'mydetails/creditcarddetailsview">'.TAB_EMP_CORPORATE_CARD.'</a></li>';

			if(!empty($empOrganizationTabs) && in_array("workeligibilitydetails", $empOrganizationTabs))
			$tabsHtml .='<li id="workeligibilitydetails"><a href="'.BASE_URL.'mydetails/workeligibilitydetailsview">'.TAB_EMP_WORK_ELIGIBILITY.'</a></li>';

			if(!empty($empOrganizationTabs) && in_array("emp_additional", $empOrganizationTabs))
			$tabsHtml .= '<li id = "emp_additional"><a href="'.BASE_URL.'mydetails/additionaldetailsedit">'.TAB_EMP_ADDITIONAL.'</a></li>';
			
			if(!empty($empOrganizationTabs) && in_array("assetdetails", $empOrganizationTabs))
			$tabsHtml .= '<li id = "assetdetails"><a href="'.BASE_URL.'mydetails/assetdetailsview">'.TAB_EMP_ASSETS.'</a></li>';
					
			
		}
                else if($conText == "mydetails" && $isBoarding == true)
                {
                        if(!empty($empOrganizationTabs) && in_array("emppersonaldetails", $empOrganizationTabs))
			$tabsHtml .='<li id = "emppersonaldetails"><a href="'.BASE_URL.'mydetails/personaldetailsview">'.TAB_EMP_PERSONAL.'</a></li>';
			if(!empty($empOrganizationTabs) && in_array("employeedocs", $empOrganizationTabs))
			$tabsHtml .= '<li id = "employeedocs"><a href="'.BASE_URL.'mydetails/documents">'.TAB_EMP_DOCUMENTS.'</a><span class="beta_menu"></span></li>';
			
				
			if(!empty($empOrganizationTabs) && in_array("empcommunicationdetails", $empOrganizationTabs))
			$tabsHtml .='<li id = "empcommunicationdetails" ><a href="'.BASE_URL.'mydetails/communicationdetailsview">'.TAB_EMP_CONTACT.'</a></li>';

			if(!empty($empOrganizationTabs) && in_array("experience_details", $empOrganizationTabs))
			$tabsHtml .='<li id= "experience_details"><a href="'.BASE_URL.'mydetails/experience">'.TAB_EMP_EXPERIENCE.'</a></li>';

			if(!empty($empOrganizationTabs) && in_array("education_details", $empOrganizationTabs))
			$tabsHtml .='<li id = "education_details"><a href="'.BASE_URL.'mydetails/education">'.TAB_EMP_EDUCATION.'</a></li>';

			if(!empty($empOrganizationTabs) && in_array("trainingandcertification_details", $empOrganizationTabs))
			$tabsHtml .='<li id = "trainingandcertification_details"><a href="'.BASE_URL.'mydetails/certification">'.TAB_EMP_TRAINING_CERTIFY.'</a></li>';

			
                }
		else if($conText == "myemployees")
		{
			$tabsHtml .= '<li id="empdetails" onclick="changeviewscreen(\'myemployees\','.$userId .');">'.TAB_EMP_OFFICIAL.'</li>';
			
			if(!empty($empOrganizationTabs) && in_array("employeedocs", $empOrganizationTabs))
			$tabsHtml .= '<li id = "employeedocs" onclick="changemyempviewscreen(\'myemployees\',\'docview\','.$userId .');">'.TAB_EMP_DOCUMENTS.'<span class="beta_menu"></span></li>';
				
			if(!empty($empOrganizationTabs) && in_array("emppersonaldetails", $empOrganizationTabs))
			$tabsHtml .= '<li id = "emppersonaldetails" onclick="changemyempviewscreen(\'myemployees\',\'perview\','.$userId .');">'.TAB_EMP_PERSONAL.'</li>';
				
			if(!empty($empOrganizationTabs) && in_array("empcommunicationdetails", $empOrganizationTabs))
			$tabsHtml .= '<li id = "empcommunicationdetails" onclick="changemyempviewscreen(\'myemployees\',\'comview\','.$userId .');">'.TAB_EMP_CONTACT.'</li>';
				
			if(!empty($empOrganizationTabs) && in_array("emp_skills", $empOrganizationTabs))
			$tabsHtml .= '<li id = "emp_skills" onclick="changemyempviewscreen(\'myemployees\',\'skillsview\','.$userId .');">'.TAB_EMP_SKILLS.'</li>';
				
			if(!empty($empOrganizationTabs) && in_array("emp_jobhistory", $empOrganizationTabs))
			$tabsHtml .= '<li id = "emp_jobhistory" onclick="changemyempviewscreen(\'myemployees\',\'jobhistoryview\','.$userId .');">'.TAB_EMP_JOB_HISTORY.'</li>';
				
			if(!empty($empOrganizationTabs) && in_array("experience_details", $empOrganizationTabs))
			$tabsHtml .= '<li id = "experience_details" onclick="changemyempviewscreen(\'myemployees\',\'expview\','.$userId .');">'.TAB_EMP_EXPERIENCE.'</li>';
				
			if(!empty($empOrganizationTabs) && in_array("education_details", $empOrganizationTabs))
			$tabsHtml .= '<li id = "education_details" onclick="changemyempviewscreen(\'myemployees\',\'eduview\','.$userId .');">'.TAB_EMP_EDUCATION.'</li>';
				
			if(!empty($empOrganizationTabs) && in_array("trainingandcertification_details", $empOrganizationTabs))
			$tabsHtml .= '<li id = "trainingandcertification_details" onclick="changemyempviewscreen(\'myemployees\',\'trainingview\','.$userId .');">'.TAB_EMP_TRAINING_CERTIFY.'</li>';
				
			if(!empty($empOrganizationTabs) && in_array("emp_additional", $empOrganizationTabs))
			$tabsHtml .= '<li id = "emp_additional" onclick="changemyempviewscreen(\'myemployees\',\'additionaldetailsview\','.$userId .');">'.TAB_EMP_ADDITIONAL.'</li>';
		}
		else if($conText == "myemployeesedit")
		{
			$tabsHtml .= '<li id="empdetails" onclick="changeeditscreen(\'myemployees\','.$userId .');">'.TAB_EMP_OFFICIAL.'</li>';
			
			if(!empty($empOrganizationTabs) && in_array("employeedocs", $empOrganizationTabs))
			$tabsHtml .= '<li id = "employeedocs" onclick="changemyempviewscreen(\'myemployees\',\'docedit\','.$userId .');">'.TAB_EMP_DOCUMENTS.'<span class="beta_menu"></span></li>';
				
			if(!empty($empOrganizationTabs) && in_array("emppersonaldetails", $empOrganizationTabs))
			$tabsHtml .= '<li id = "emppersonaldetails" onclick="changemyempviewscreen(\'myemployees\',\'peredit\','.$userId .');">'.TAB_EMP_PERSONAL.'</li>';
				
			if(!empty($empOrganizationTabs) && in_array("empcommunicationdetails", $empOrganizationTabs))
			$tabsHtml .= '<li id = "empcommunicationdetails" onclick="changemyempviewscreen(\'myemployees\',\'comedit\','.$userId .');">'.TAB_EMP_CONTACT.'</li>';
				
			if(!empty($empOrganizationTabs) && in_array("emp_skills", $empOrganizationTabs))
			$tabsHtml .= '<li id = "emp_skills" onclick="changemyempviewscreen(\'myemployees\',\'skillsedit\','.$userId .');">'.TAB_EMP_SKILLS.'</li>';
				
			if(!empty($empOrganizationTabs) && in_array("emp_jobhistory", $empOrganizationTabs))
			$tabsHtml .= '<li id = "emp_jobhistory" onclick="changemyempviewscreen(\'myemployees\',\'jobhistoryedit\','.$userId .');">'.TAB_EMP_JOB_HISTORY.'</li>';
				
			if(!empty($empOrganizationTabs) && in_array("experience_details", $empOrganizationTabs))
			$tabsHtml .= '<li id = "experience_details" onclick="changemyempviewscreen(\'myemployees\',\'expedit\','.$userId .');">'.TAB_EMP_EXPERIENCE.'</li>';
				
			if(!empty($empOrganizationTabs) && in_array("education_details", $empOrganizationTabs))
			$tabsHtml .= '<li id = "education_details" onclick="changemyempviewscreen(\'myemployees\',\'eduedit\','.$userId .');">'.TAB_EMP_EDUCATION.'</li>';
				
			if(!empty($empOrganizationTabs) && in_array("trainingandcertification_details", $empOrganizationTabs))
			$tabsHtml .= '<li id = "trainingandcertification_details" onclick="changemyempviewscreen(\'myemployees\',\'trainingedit\','.$userId .');">'.TAB_EMP_TRAINING_CERTIFY.'</li>';
				
			if(!empty($empOrganizationTabs) && in_array("emp_additional", $empOrganizationTabs))
			$tabsHtml .= '<li id = "emp_additional" onclick="changemyempviewscreen(\'myemployees\',\'additionaldetailsedit\','.$userId .');">'.TAB_EMP_ADDITIONAL.'</li>';
		}
		$tabsHtml .= '</ul></div></div>';
		echo $tabsHtml;
	}

    public function employeetabs($conText,$userId)
    {
    $tabHeightClass="";
		$session=new Zend_Auth_Storage_Session();
		$data=$session->read();
		

		if(defined('EMPTABCONFIGS')) 
		 $empOrganizationTabs = explode(",",EMPTABCONFIGS);

		$loggedinuser = $data['id'];		$group_id = $data['group_id'];
                $isBoarding = false;
                if($data['emprole'] == 14)
                {
                    $isBoarding = true;
                }
		if ($conText == "mydetails")
		{
			$tabHeightClass="mydetails-height";
		}
		else if ($conText == "edit" || $conText == "view")
		{
			if($group_id == HR_GROUP || $loggedinuser == SUPERADMIN || $group_id == MANAGEMENT_GROUP )
			{
				$tabHeightClass="hr-employee-height";
			}
			else if($group_id == MANAGER_GROUP ||$group_id == EMPLOYEE_GROUP||$group_id == SYSTEMADMIN_GROUP)
			{
				$tabHeightClass="mydetails-height";
			}
		}
        $tabsHtml = '<div class="col-md-9" id="'.$tabHeightClass.'">';
        echo $tabsHtml;

    }
    public function newemployeetabs($conText,$userId)
    {
    $tabHeightClass="";
		$session=new Zend_Auth_Storage_Session();
		$data=$session->read();
		

		if(defined('EMPTABCONFIGS')) 
		 $empOrganizationTabs = explode(",",EMPTABCONFIGS);

		$loggedinuser = $data['id'];		$group_id = $data['group_id'];
                $isBoarding = false;
                if($data['emprole'] == 14)
                {
                    $isBoarding = true;
                }
		if ($conText == "mydetails")
		{
			$tabHeightClass="mydetails-height";
		}
		else if ($conText == "edit" || $conText == "view")
		{
			if($group_id == HR_GROUP || $loggedinuser == SUPERADMIN || $group_id == MANAGEMENT_GROUP )
			{
				$tabHeightClass="hr-employee-height";
			}
			else if($group_id == MANAGER_GROUP ||$group_id == EMPLOYEE_GROUP||$group_id == SYSTEMADMIN_GROUP)
			{
				$tabHeightClass="mydetails-height";
			}
		}
            
		    $tabsHtml = '<div class="panel-body list-group border-bottom " >';
            $tabsHtml .='<a href="#" class="list-group-item"><span class="fa fa-bar-chart-o"></span>Activity</a>';
            $tabsHtml .='<a href="#" class="list-group-item"><span class="fa fa-coffee"></span>Groups <span class="badge badge-default">18</span></a>';
            $tabsHtml .='<li class="list-group-item"><span class="fa fa-sitemap"></span>Navigation</li>';
            $tabsHtml .='<ul class="x-navigation scrollinner" style="color: Black;">';
            //$tabsHtml .='<li ><a href="#" class=""><span class="fa fa-bar-chart-o"></span>Activity</a></li>';
            //$tabsHtml .='<li><a href="#" class=""><span class="fa fa-coffee"></span>Groups<span class="badge badge-default"> 18</sp</a></li>';
            //$tabsHtml .='<li><a href="#" class=""><span class="fa fa-users"></span>Friends <span class="badge badge-danger">+7</span></a></li>';
            //$tabsHtml .='<li class="xn-openable"><a href="#"><span class="fa fa-files-o"></span><span class="xn-text">Details</span></a>';
            //$tabsHtml .='<ul>';
            if($conText == "edit")
		{
			
			//View all tabs with all privileges....	onclick - changeempeditscreen...
			
			$tabsHtml .= '<li  id="empdetails" ><a href="#" onclick="changeeditscreen(\'employee\','.$userId .');">
				'.TAB_EMP_OFFICIAL.'</a></li>';
			
			if(!empty($empOrganizationTabs) && in_array("employeedocs", $empOrganizationTabs))
			$tabsHtml .= '<li id="employeedocs"><a href="#" onclick="changemyempviewscreen(\'employeedocs\',\'index\','.$userId .');" >  '.TAB_EMP_DOCUMENTS.'<span class="beta_menu"></span></a></li>';
			
			if(!empty($empOrganizationTabs) && in_array("emp_leaves", $empOrganizationTabs))
			$tabsHtml .= '<li id = "emp_leaves" ><a href="#" onclick="changeempeditscreen(\'empleaves\','.$userId .');">'.TAB_EMP_LEAVES.'</a></li>';

			if(!empty($empOrganizationTabs) && in_array("emp_holidays", $empOrganizationTabs))
			$tabsHtml .= '<li id = "emp_holidays" ><a href="#" onclick="changeempeditscreen(\'empholidays\','.$userId .');">'.TAB_EMP_HOLIDAYS.'</a></li>';

			if(!empty($empOrganizationTabs) && in_array("emp_salary", $empOrganizationTabs))
			$tabsHtml .= '<li id = "emp_salary" ><a href="#" onclick="changeempeditscreen(\'empsalarydetails\','.$userId .');">'.TAB_EMP_SALARY.'</a></li>';
			
			if(!empty($empOrganizationTabs) && in_array("emppersonaldetails", $empOrganizationTabs))
			$tabsHtml .= '<li id = "emppersonaldetails" ><a href="#" onclick="changeempeditscreen(\'emppersonaldetails\','.$userId .');">'.TAB_EMP_PERSONAL.'</a></li>';

			if(!empty($empOrganizationTabs) && in_array("empcommunicationdetails", $empOrganizationTabs))
			$tabsHtml .= '<li id = "empcommunicationdetails" ><a href="#" onclick="changeempeditscreen(\'empcommunicationdetails\','. $userId .');">'.TAB_EMP_CONTACT.'</a></li>
				';

			if(!empty($empOrganizationTabs) && in_array("emp_skills", $empOrganizationTabs))
			$tabsHtml .= '<li id = "emp_skills" ><a href="#" onclick="changeempeditscreen(\'empskills\','.$userId .');">'.TAB_EMP_SKILLS.'</a></li>';

			if(!empty($empOrganizationTabs) && in_array("emp_jobhistory", $empOrganizationTabs))
			$tabsHtml .= '<li id = "emp_jobhistory" ><a href="#" onclick="changeempeditscreen(\'empjobhistory\','.$userId .');">'.TAB_EMP_JOB_HISTORY.'</a></li>';

			if(!empty($empOrganizationTabs) && in_array("experience_details", $empOrganizationTabs))
			$tabsHtml .= '<li id= "experience_details" ><a href="#" onclick="changeempeditscreen(\'experiencedetails\','.$userId .');">'.TAB_EMP_EXPERIENCE.'</a></li>';

			if(!empty($empOrganizationTabs) && in_array("education_details", $empOrganizationTabs))
			$tabsHtml .= '<li id = "education_details" ><a href="#" onclick="changeempeditscreen(\'educationdetails\','.$userId .');">'.TAB_EMP_EDUCATION.'</a></li>';

			if(!empty($empOrganizationTabs) && in_array("trainingandcertification_details", $empOrganizationTabs))
			$tabsHtml .= '<li id = "trainingandcertification_details" ><a href="#" onclick="changeempeditscreen(\'trainingandcertificationdetails\','.$userId .');">'.TAB_EMP_TRAINING_CERTIFY.'</a></li>';

			if(!empty($empOrganizationTabs) && in_array("medical_claims", $empOrganizationTabs))
			$tabsHtml .= '<li id = "medical_claims" ><a href="#" onclick="changeempeditscreen(\'medicalclaims\','. $userId .');">'.TAB_EMP_MEDICAL_CLAIMS.'</a></li>';

			if(!empty($empOrganizationTabs) && in_array("disabilitydetails", $empOrganizationTabs))
			$tabsHtml .= '<li id = "disabilitydetails" ><a href="#" onclick="changeempeditscreen(\'disabilitydetails\','.$userId .');">'.TAB_EMP_DISABILITY.'</a></li>';

			if(!empty($empOrganizationTabs) && in_array("dependency_details", $empOrganizationTabs))
			$tabsHtml .= '<li id = "dependency_details" ><a href="#" onclick="changeempeditscreen(\'dependencydetails\','.$userId .');">'.TAB_EMP_DEPENDENCY.'</a></li>';

			if(!empty($empOrganizationTabs) && in_array("visadetails", $empOrganizationTabs))
			$tabsHtml .= '<li id="visadetails" ><a href="#" onclick="changeempeditscreen(\'visaandimmigrationdetails\','.$userId .');">'.TAB_EMP_VISA_EMIGRATION.'</a></li>';

			if(!empty($empOrganizationTabs) && in_array("creditcarddetails", $empOrganizationTabs))
			$tabsHtml .= '<li id= "creditcarddetails" ><a href="#" onclick="changeempeditscreen(\'creditcarddetails\','.$userId.');">'.TAB_EMP_CORPORATE_CARD.'</a></li>';

			if(!empty($empOrganizationTabs) && in_array("workeligibilitydetails", $empOrganizationTabs))
			$tabsHtml	.= '<li id="workeligibilitydetails" ><a href="#" onclick="changeempeditscreen(\'workeligibilitydetails\','. $userId .');">'.TAB_EMP_WORK_ELIGIBILITY.'</a></li>';

			if(!empty($empOrganizationTabs) && in_array("emp_additional", $empOrganizationTabs))
			$tabsHtml .= '<li id = "emp_additional" ><a href="#" onclick="changeempeditscreen(\'empadditionaldetails\','.$userId .');">'.TAB_EMP_ADDITIONAL.'</a></li>';
			
			//if(!empty($empOrganizationTabs) && in_array("emp_performanceappraisal", $empOrganizationTabs))
			//$tabsHtml .= '<li id = "emp_performanceappraisal" onclick="changeempeditscreen(\'empperformanceappraisal\','.$userId .');">'.TAB_EMP_PERFORMANCE_APPRAISAL.'</li>';
			
			if(!empty($empOrganizationTabs) && in_array("emp_payslips", $empOrganizationTabs))
			$tabsHtml .= '<li id = "emp_payslips" ><a href="#" onclick="changeempeditscreen(\'emppayslips\','.$userId .');">'.TAB_EMP_PAY_SLIPS.'</a></li>';
			
			if(!empty($empOrganizationTabs) && in_array("emp_benifits", $empOrganizationTabs))
			$tabsHtml .= '<li id = "emp_benifits" ><a href="#" onclick="changeempeditscreen(\'empbenefits\','.$userId .');">'.TAB_EMP_BENEFITS.'</a></li>';
			
			if(!empty($empOrganizationTabs) && in_array("emp_renumeration", $empOrganizationTabs))
			$tabsHtml .= '<li id = "emp_renumeration" ><a href="#" onclick="changeempeditscreen(\'empremunerationdetails\','.$userId .');">'.TAB_EMP_REMUNERATION.'</a></li>';

			if(!empty($empOrganizationTabs) && in_array("emp_security", $empOrganizationTabs))
			$tabsHtml .= '<li id = "emp_security" ><a href="#" onclick="changeempeditscreen(\'empsecuritycredentials\','.$userId .');">'.TAB_EMP_SECURITY_CREDENTIALS.'</a></li>';
			
			if(!empty($empOrganizationTabs) && in_array("assetdetails", $empOrganizationTabs))
			$tabsHtml .= '<li id= "assetdetails" ><a href="#" onclick="changeempeditscreen(\'assetdetails\','.$userId.');">'.TAB_EMP_ASSETS.'</a></li>';
					
			
		}
		else if($conText == "view")
		{
		
			if($group_id == HR_GROUP ||$group_id == MANAGEMENT_GROUP || $loggedinuser == SUPERADMIN)
			{
				
				$tabsHtml .= '<li id="empdetails" ><a href="#" onclick="changemyempviewscreen(\'employee\','.$userId .');" >
				'.TAB_EMP_OFFICIAL.'</a></li>';
				
				if(!empty($empOrganizationTabs) && in_array("employeedocs", $empOrganizationTabs))
				$tabsHtml .= '<li id = "employeedocs" ><a href="#" onclick="changemyempviewscreen(\'employeedocs\',\'view\','.$userId .');">'.TAB_EMP_DOCUMENTS.'<span class="beta_menu"></span></a></li>';

				if(!empty($empOrganizationTabs) && in_array("emp_leaves", $empOrganizationTabs))
				$tabsHtml .= '<li id = "emp_leaves" ><a href="#" onclick="changeempviewscreen(\'empleaves\','.$userId .');">'.TAB_EMP_LEAVES.'</a></li>';

				if(!empty($empOrganizationTabs) && in_array("emp_holidays", $empOrganizationTabs))
				$tabsHtml .= '<li id = "emp_holidays" ><a href="#" onclick="changeempviewscreen(\'empholidays\','.$userId .');">'.TAB_EMP_HOLIDAYS.'</a></li>';

				if(!empty($empOrganizationTabs) && in_array("emp_salary", $empOrganizationTabs))
				$tabsHtml .= '<li id = "emp_salary" ><a href="#" onclick="changeempviewscreen(\'empsalarydetails\','.$userId .');">'.TAB_EMP_SALARY.'</a></li>';
								
				if(!empty($empOrganizationTabs) && in_array("emppersonaldetails", $empOrganizationTabs))
				$tabsHtml .= '<li id = "emppersonaldetails" ><a href="#" onclick="changeempviewscreen(\'emppersonaldetails\','.$userId .');">'.TAB_EMP_PERSONAL.'</a></li>';

				if(!empty($empOrganizationTabs) && in_array("empcommunicationdetails", $empOrganizationTabs))
				$tabsHtml .= '<li id = "empcommunicationdetails" ><a href="#" onclick="changeempviewscreen(\'empcommunicationdetails\','. $userId .');">'.TAB_EMP_CONTACT.'</a></li>
				';

				if(!empty($empOrganizationTabs) && in_array("emp_skills", $empOrganizationTabs))
				$tabsHtml .= '<li id = "emp_skills" ><a href="#" onclick="changeempviewscreen(\'empskills\','.$userId .');">'.TAB_EMP_SKILLS.'</a></li>';

				if(!empty($empOrganizationTabs) && in_array("emp_jobhistory", $empOrganizationTabs))
				$tabsHtml .= '<li id = "emp_jobhistory" ><a href="#" onclick="changeempviewscreen(\'empjobhistory\','.$userId .');">'.TAB_EMP_JOB_HISTORY.'</a></li>';

				if(!empty($empOrganizationTabs) && in_array("experience_details", $empOrganizationTabs))
				$tabsHtml .= '<li id= "experience_details" ><a href="#" onclick="changeempviewscreen(\'experiencedetails\','.$userId .');">'.TAB_EMP_EXPERIENCE.'</a></li>';

				if(!empty($empOrganizationTabs) && in_array("education_details", $empOrganizationTabs))
				$tabsHtml .= '<li id = "education_details" ><a href="#" onclick="changeempviewscreen(\'educationdetails\','.$userId .');">'.TAB_EMP_EDUCATION.'</a></li>';

				if(!empty($empOrganizationTabs) && in_array("trainingandcertification_details", $empOrganizationTabs))
				$tabsHtml .= '<li id = "trainingandcertification_details" ><a href="#" onclick="changeempviewscreen(\'trainingandcertificationdetails\','.$userId .');">'.TAB_EMP_TRAINING_CERTIFY.'</a></li>';

				if(!empty($empOrganizationTabs) && in_array("medical_claims", $empOrganizationTabs))
				$tabsHtml .= '<li id = "medical_claims" ><a href="#" onclick="changeempviewscreen(\'medicalclaims\','. $userId .');">'.TAB_EMP_MEDICAL_CLAIMS.'</a></li>';

				if(!empty($empOrganizationTabs) && in_array("disabilitydetails", $empOrganizationTabs))
				$tabsHtml .= '<li id = "disabilitydetails" ><a href="#" onclick="changeempviewscreen(\'disabilitydetails\','.$userId .');">'.TAB_EMP_DISABILITY.'</a></li>';

				if(!empty($empOrganizationTabs) && in_array("dependency_details", $empOrganizationTabs))
				$tabsHtml .= '<li id = "dependency_details" ><a href="#" onclick="changeempviewscreen(\'dependencydetails\','.$userId .');">'.TAB_EMP_DEPENDENCY.'</a></li>';

				if(!empty($empOrganizationTabs) && in_array("visadetails", $empOrganizationTabs))
				$tabsHtml .= '<li id="visadetails" ><a href="#" onclick="changeempviewscreen(\'visaandimmigrationdetails\','.$userId .');">'.TAB_EMP_VISA_EMIGRATION.'</a></li>';

				if(!empty($empOrganizationTabs) && in_array("creditcarddetails", $empOrganizationTabs))
				$tabsHtml .= '<li id= "creditcarddetails" ><a href="#" onclick="changeempviewscreen(\'creditcarddetails\','.$userId.');">'.TAB_EMP_CORPORATE_CARD.'</a></li>';

				if(!empty($empOrganizationTabs) && in_array("workeligibilitydetails", $empOrganizationTabs))
				$tabsHtml	.= '<li id="workeligibilitydetails" ><a href="#" onclick="changeempviewscreen(\'workeligibilitydetails\','. $userId .');">'.TAB_EMP_WORK_ELIGIBILITY.'</a></li>';

				if(!empty($empOrganizationTabs) && in_array("emp_additional", $empOrganizationTabs))
				$tabsHtml .= '<li id = "emp_additional" ><a href="#" onclick="changeempviewscreen(\'empadditionaldetails\','.$userId .');">'.TAB_EMP_ADDITIONAL.'</a></li>';
				
				if(!empty($empOrganizationTabs) && in_array("emp_payslips", $empOrganizationTabs))
				$tabsHtml .= '<li id = "emp_payslips" ><a href="#" onclick="changeempviewscreen(\'emppayslips\','.$userId .');">'.TAB_EMP_PAY_SLIPS.'</a></li>';
				
				if(!empty($empOrganizationTabs) && in_array("emp_benifits", $empOrganizationTabs))
				$tabsHtml .= '<li id = "emp_benifits" ><a href="#" onclick="changeempviewscreen(\'empbenefits\','.$userId .');">'.TAB_EMP_BENEFITS.'</a></li>';
				
				if(!empty($empOrganizationTabs) && in_array("emp_renumeration", $empOrganizationTabs))
				$tabsHtml .= '<li id = "emp_renumeration" ><a href="#" onclick="changeempviewscreen(\'empremunerationdetails\','.$userId .');">'.TAB_EMP_REMUNERATION.'</a></li>';

				if(!empty($empOrganizationTabs) && in_array("emp_security", $empOrganizationTabs))
				$tabsHtml .= '<li id = "emp_security" ><a href="#" onclick="changeempviewscreen(\'empsecuritycredentials\','.$userId .');">'.TAB_EMP_SECURITY_CREDENTIALS.'</a></li>';
				
				if(!empty($empOrganizationTabs) && in_array("assetdetails", $empOrganizationTabs))
					$tabsHtml .= '<li id= "assetdetails" ><a href="#" onclick="changeempviewscreen(\'assetdetails\','.$userId.');">'.TAB_EMP_ASSETS.'</a></li>';
				
			}
			else if($group_id == MANAGER_GROUP ||$group_id == EMPLOYEE_GROUP||$group_id == SYSTEMADMIN_GROUP )
			{
				//View only 7 tabs with view privilege....	General Tabs...
				
				$tabsHtml .= '<li id="empdetails" onclick="changeviewscreen(\'employee\','.$userId .');">
				'.TAB_EMP_OFFICIAL.'</li>';
					
				if(!empty($empOrganizationTabs) && in_array("emppersonaldetails", $empOrganizationTabs))
				$tabsHtml .= '<li id = "emppersonaldetails" onclick="changeempviewscreen(\'emppersonaldetails\','.$userId .');">'.TAB_EMP_PERSONAL.'</li>';

				if(!empty($empOrganizationTabs) && in_array("empcommunicationdetails", $empOrganizationTabs))
				$tabsHtml .= '<li id = "empcommunicationdetails" onclick="changeempviewscreen(\'empcommunicationdetails\','. $userId .');">'.TAB_EMP_CONTACT.'</li>
				';

				if(!empty($empOrganizationTabs) && in_array("emp_skills", $empOrganizationTabs))
				$tabsHtml .= '<li id = "emp_skills" onclick="changeempviewscreen(\'empskills\','.$userId .');">'.TAB_EMP_SKILLS.'</li>';

				if(!empty($empOrganizationTabs) && in_array("emp_jobhistory", $empOrganizationTabs))
				$tabsHtml .= '<li id = "emp_jobhistory" onclick="changeempviewscreen(\'empjobhistory\','.$userId .');">'.TAB_EMP_JOB_HISTORY.'</li>';

				if(!empty($empOrganizationTabs) && in_array("experience_details", $empOrganizationTabs))
				$tabsHtml .= '<li id= "experience_details" onclick="changeempviewscreen(\'experiencedetails\','.$userId .');">'.TAB_EMP_EXPERIENCE.'</li>';

				if(!empty($empOrganizationTabs) && in_array("education_details", $empOrganizationTabs))
				$tabsHtml .= '<li id = "education_details" onclick="changeempviewscreen(\'educationdetails\','.$userId .');">'.TAB_EMP_EDUCATION.'</li>';
				

				if(!empty($empOrganizationTabs) && in_array("trainingandcertification_details", $empOrganizationTabs))
				$tabsHtml .= '<li id = "trainingandcertification_details" onclick="changeempviewscreen(\'trainingandcertificationdetails\','.$userId .');">'.TAB_EMP_TRAINING_CERTIFY.'</li>';

			}
                        else if($group_id == USERS_GROUP)
                        {
                            $tabsHtml .= '<li id="empdetails" onclick="changeviewscreen(\'employee\','.$userId .');">
				'.TAB_EMP_OFFICIAL.'</li>';
				if(!empty($empOrganizationTabs) && in_array("employeedocs", $empOrganizationTabs))
				$tabsHtml .= '<li id = "employeedocs" onclick="changemyempviewscreen(\'employeedocs\',\'view\','.$userId .');">'.TAB_EMP_DOCUMENTS.'<span class="beta_menu"></span></li>';
                                
				if(!empty($empOrganizationTabs) && in_array("emppersonaldetails", $empOrganizationTabs))
				$tabsHtml .= '<li id = "emppersonaldetails" onclick="changeempviewscreen(\'emppersonaldetails\','.$userId .');">'.TAB_EMP_PERSONAL.'</li>';

				if(!empty($empOrganizationTabs) && in_array("empcommunicationdetails", $empOrganizationTabs))
				$tabsHtml .= '<li id = "empcommunicationdetails" onclick="changeempviewscreen(\'empcommunicationdetails\','. $userId .');">'.TAB_EMP_CONTACT.'</li>
				';

				if(!empty($empOrganizationTabs) && in_array("experience_details", $empOrganizationTabs))
				$tabsHtml .= '<li id= "experience_details" onclick="changeempviewscreen(\'experiencedetails\','.$userId .');">'.TAB_EMP_EXPERIENCE.'</li>';

				if(!empty($empOrganizationTabs) && in_array("education_details", $empOrganizationTabs))
				$tabsHtml .= '<li id = "education_details" onclick="changeempviewscreen(\'educationdetails\','.$userId .');">'.TAB_EMP_EDUCATION.'</li>';
				

				if(!empty($empOrganizationTabs) && in_array("trainingandcertification_details", $empOrganizationTabs))
				$tabsHtml .= '<li id = "trainingandcertification_details" onclick="changeempviewscreen(\'trainingandcertificationdetails\','.$userId .');">'.TAB_EMP_TRAINING_CERTIFY.'</li>';
                        }
				
				
		}
		else if($conText == "mydetails" && $isBoarding == false)
		{
			
			$tabsHtml .= '<li id="empdetails"><a href="'.BASE_URL.'mydetails">'.TAB_EMP_OFFICIAL.'</a></li>';
			
			if(!empty($empOrganizationTabs) && in_array("employeedocs", $empOrganizationTabs))
			$tabsHtml .= '<li id = "employeedocs"><a href="'.BASE_URL.'mydetails/documents">'.TAB_EMP_DOCUMENTS.'</a><span class="beta_menu"></span></li>';

			if(!empty($empOrganizationTabs) && in_array("emp_leaves", $empOrganizationTabs))
			$tabsHtml .='<li id = "emp_leaves"><a href="'.BASE_URL.'mydetails/leaves">'.TAB_EMP_LEAVES.'</a></li>';
			
			if(!empty($empOrganizationTabs) && in_array("emp_salary", $empOrganizationTabs))
			$tabsHtml .= '<li id = "emp_salary"><a href="'.BASE_URL .'mydetails/salarydetailsview">'.TAB_EMP_SALARY.'</a></li>';
			
			if(!empty($empOrganizationTabs) && in_array("emppersonaldetails", $empOrganizationTabs))
			$tabsHtml .='<li id = "emppersonaldetails"><a href="'.BASE_URL.'mydetails/personaldetailsview">'.TAB_EMP_PERSONAL.'</a></li>';
				
			if(!empty($empOrganizationTabs) && in_array("empcommunicationdetails", $empOrganizationTabs))
			$tabsHtml .='<li id = "empcommunicationdetails" ><a href="'.BASE_URL.'mydetails/communicationdetailsview">'.TAB_EMP_CONTACT.'</a></li>';

			if(!empty($empOrganizationTabs) && in_array("emp_skills", $empOrganizationTabs))
			$tabsHtml .='<li id = "emp_skills"><a href="'.BASE_URL.'mydetails/skills">'.TAB_EMP_SKILLS.'</a></li>';
				
			if(!empty($empOrganizationTabs) && in_array("emp_jobhistory", $empOrganizationTabs))
			$tabsHtml .= '<li id = "emp_jobhistory"><a href="'.BASE_URL.'mydetails/jobhistory">'.TAB_EMP_JOB_HISTORY.'</a></li>';
				
			if(!empty($empOrganizationTabs) && in_array("experience_details", $empOrganizationTabs))
			$tabsHtml .='<li id= "experience_details"><a href="'.BASE_URL.'mydetails/experience">'.TAB_EMP_EXPERIENCE.'</a></li>';

			if(!empty($empOrganizationTabs) && in_array("education_details", $empOrganizationTabs))
			$tabsHtml .='<li id = "education_details"><a href="'.BASE_URL.'mydetails/education">'.TAB_EMP_EDUCATION.'</a></li>';

			if(!empty($empOrganizationTabs) && in_array("trainingandcertification_details", $empOrganizationTabs))
			$tabsHtml .='<li id = "trainingandcertification_details"><a href="'.BASE_URL.'mydetails/certification">'.TAB_EMP_TRAINING_CERTIFY.'</a></li>';

			if(!empty($empOrganizationTabs) && in_array("medical_claims", $empOrganizationTabs))
			$tabsHtml .='<li id = "medical_claims"><a href="'.BASE_URL.'mydetails/medicalclaims">'.TAB_EMP_MEDICAL_CLAIMS.'</a></li>';

			if(!empty($empOrganizationTabs) && in_array("disabilitydetails", $empOrganizationTabs))
			$tabsHtml .='<li id = "disabilitydetails"><a href="'.BASE_URL.'mydetails/disabilitydetailsview">'.TAB_EMP_DISABILITY.'</a></li>';

			if(!empty($empOrganizationTabs) && in_array("dependency_details", $empOrganizationTabs))
			$tabsHtml .='<li id = "dependency_details"><a href="'.BASE_URL.'mydetails/dependency">'.TAB_EMP_DEPENDENCY.'</a></li>';

			if(!empty($empOrganizationTabs) && in_array("visadetails", $empOrganizationTabs))
			$tabsHtml .='<li id="visadetails"><a href="'.BASE_URL.'mydetails/visadetailsview">'.TAB_EMP_VISA_EMIGRATION.'</a></li>';

			if(!empty($empOrganizationTabs) && in_array("creditcarddetails", $empOrganizationTabs))
			$tabsHtml .='<li id= "creditcarddetails"><a href="'.BASE_URL.'mydetails/creditcarddetailsview">'.TAB_EMP_CORPORATE_CARD.'</a></li>';

			if(!empty($empOrganizationTabs) && in_array("workeligibilitydetails", $empOrganizationTabs))
			$tabsHtml .='<li id="workeligibilitydetails"><a href="'.BASE_URL.'mydetails/workeligibilitydetailsview">'.TAB_EMP_WORK_ELIGIBILITY.'</a></li>';

			if(!empty($empOrganizationTabs) && in_array("emp_additional", $empOrganizationTabs))
			$tabsHtml .= '<li id = "emp_additional"><a href="'.BASE_URL.'mydetails/additionaldetailsedit">'.TAB_EMP_ADDITIONAL.'</a></li>';
			
			if(!empty($empOrganizationTabs) && in_array("assetdetails", $empOrganizationTabs))
			$tabsHtml .= '<li id = "assetdetails"><a href="'.BASE_URL.'mydetails/assetdetailsview">'.TAB_EMP_ASSETS.'</a></li>';
					
			
		}
                else if($conText == "mydetails" && $isBoarding == true)
                {
                        if(!empty($empOrganizationTabs) && in_array("emppersonaldetails", $empOrganizationTabs))
			$tabsHtml .='<li id = "emppersonaldetails"><a href="'.BASE_URL.'mydetails/personaldetailsview">'.TAB_EMP_PERSONAL.'</a></li>';
			if(!empty($empOrganizationTabs) && in_array("employeedocs", $empOrganizationTabs))
			$tabsHtml .= '<li id = "employeedocs"><a href="'.BASE_URL.'mydetails/documents">'.TAB_EMP_DOCUMENTS.'</a><span class="beta_menu"></span></li>';
			
				
			if(!empty($empOrganizationTabs) && in_array("empcommunicationdetails", $empOrganizationTabs))
			$tabsHtml .='<li id = "empcommunicationdetails" ><a href="'.BASE_URL.'mydetails/communicationdetailsview">'.TAB_EMP_CONTACT.'</a></li>';

			if(!empty($empOrganizationTabs) && in_array("experience_details", $empOrganizationTabs))
			$tabsHtml .='<li id= "experience_details"><a href="'.BASE_URL.'mydetails/experience">'.TAB_EMP_EXPERIENCE.'</a></li>';

			if(!empty($empOrganizationTabs) && in_array("education_details", $empOrganizationTabs))
			$tabsHtml .='<li id = "education_details"><a href="'.BASE_URL.'mydetails/education">'.TAB_EMP_EDUCATION.'</a></li>';

			if(!empty($empOrganizationTabs) && in_array("trainingandcertification_details", $empOrganizationTabs))
			$tabsHtml .='<li id = "trainingandcertification_details"><a href="'.BASE_URL.'mydetails/certification">'.TAB_EMP_TRAINING_CERTIFY.'</a></li>';

			
                }
		else if($conText == "myemployees")
		{
			$tabsHtml .= '<li id="empdetails" onclick="changeviewscreen(\'myemployees\','.$userId .');">'.TAB_EMP_OFFICIAL.'</li>';
			
			if(!empty($empOrganizationTabs) && in_array("employeedocs", $empOrganizationTabs))
			$tabsHtml .= '<li id = "employeedocs" onclick="changemyempviewscreen(\'myemployees\',\'docview\','.$userId .');">'.TAB_EMP_DOCUMENTS.'<span class="beta_menu"></span></li>';
				
			if(!empty($empOrganizationTabs) && in_array("emppersonaldetails", $empOrganizationTabs))
			$tabsHtml .= '<li id = "emppersonaldetails" onclick="changemyempviewscreen(\'myemployees\',\'perview\','.$userId .');">'.TAB_EMP_PERSONAL.'</li>';
				
			if(!empty($empOrganizationTabs) && in_array("empcommunicationdetails", $empOrganizationTabs))
			$tabsHtml .= '<li id = "empcommunicationdetails" onclick="changemyempviewscreen(\'myemployees\',\'comview\','.$userId .');">'.TAB_EMP_CONTACT.'</li>';
				
			if(!empty($empOrganizationTabs) && in_array("emp_skills", $empOrganizationTabs))
			$tabsHtml .= '<li id = "emp_skills" onclick="changemyempviewscreen(\'myemployees\',\'skillsview\','.$userId .');">'.TAB_EMP_SKILLS.'</li>';
				
			if(!empty($empOrganizationTabs) && in_array("emp_jobhistory", $empOrganizationTabs))
			$tabsHtml .= '<li id = "emp_jobhistory" onclick="changemyempviewscreen(\'myemployees\',\'jobhistoryview\','.$userId .');">'.TAB_EMP_JOB_HISTORY.'</li>';
				
			if(!empty($empOrganizationTabs) && in_array("experience_details", $empOrganizationTabs))
			$tabsHtml .= '<li id = "experience_details" onclick="changemyempviewscreen(\'myemployees\',\'expview\','.$userId .');">'.TAB_EMP_EXPERIENCE.'</li>';
				
			if(!empty($empOrganizationTabs) && in_array("education_details", $empOrganizationTabs))
			$tabsHtml .= '<li id = "education_details" onclick="changemyempviewscreen(\'myemployees\',\'eduview\','.$userId .');">'.TAB_EMP_EDUCATION.'</li>';
				
			if(!empty($empOrganizationTabs) && in_array("trainingandcertification_details", $empOrganizationTabs))
			$tabsHtml .= '<li id = "trainingandcertification_details" onclick="changemyempviewscreen(\'myemployees\',\'trainingview\','.$userId .');">'.TAB_EMP_TRAINING_CERTIFY.'</li>';
				
			if(!empty($empOrganizationTabs) && in_array("emp_additional", $empOrganizationTabs))
			$tabsHtml .= '<li id = "emp_additional" onclick="changemyempviewscreen(\'myemployees\',\'additionaldetailsview\','.$userId .');">'.TAB_EMP_ADDITIONAL.'</li>';
		}
		else if($conText == "myemployeesedit")
		{
			$tabsHtml .= '<li id="empdetails" onclick="changeeditscreen(\'myemployees\','.$userId .');">'.TAB_EMP_OFFICIAL.'</li>';
			
			if(!empty($empOrganizationTabs) && in_array("employeedocs", $empOrganizationTabs))
			$tabsHtml .= '<li id = "employeedocs" onclick="changemyempviewscreen(\'myemployees\',\'docedit\','.$userId .');">'.TAB_EMP_DOCUMENTS.'<span class="beta_menu"></span></li>';
				
			if(!empty($empOrganizationTabs) && in_array("emppersonaldetails", $empOrganizationTabs))
			$tabsHtml .= '<li id = "emppersonaldetails" onclick="changemyempviewscreen(\'myemployees\',\'peredit\','.$userId .');">'.TAB_EMP_PERSONAL.'</li>';
				
			if(!empty($empOrganizationTabs) && in_array("empcommunicationdetails", $empOrganizationTabs))
			$tabsHtml .= '<li id = "empcommunicationdetails" onclick="changemyempviewscreen(\'myemployees\',\'comedit\','.$userId .');">'.TAB_EMP_CONTACT.'</li>';
				
			if(!empty($empOrganizationTabs) && in_array("emp_skills", $empOrganizationTabs))
			$tabsHtml .= '<li id = "emp_skills" onclick="changemyempviewscreen(\'myemployees\',\'skillsedit\','.$userId .');">'.TAB_EMP_SKILLS.'</li>';
				
			if(!empty($empOrganizationTabs) && in_array("emp_jobhistory", $empOrganizationTabs))
			$tabsHtml .= '<li id = "emp_jobhistory" onclick="changemyempviewscreen(\'myemployees\',\'jobhistoryedit\','.$userId .');">'.TAB_EMP_JOB_HISTORY.'</li>';
				
			if(!empty($empOrganizationTabs) && in_array("experience_details", $empOrganizationTabs))
			$tabsHtml .= '<li id = "experience_details" onclick="changemyempviewscreen(\'myemployees\',\'expedit\','.$userId .');">'.TAB_EMP_EXPERIENCE.'</li>';
				
			if(!empty($empOrganizationTabs) && in_array("education_details", $empOrganizationTabs))
			$tabsHtml .= '<li id = "education_details" onclick="changemyempviewscreen(\'myemployees\',\'eduedit\','.$userId .');">'.TAB_EMP_EDUCATION.'</li>';
				
			if(!empty($empOrganizationTabs) && in_array("trainingandcertification_details", $empOrganizationTabs))
			$tabsHtml .= '<li id = "trainingandcertification_details" onclick="changemyempviewscreen(\'myemployees\',\'trainingedit\','.$userId .');">'.TAB_EMP_TRAINING_CERTIFY.'</li>';
				
			if(!empty($empOrganizationTabs) && in_array("emp_additional", $empOrganizationTabs))
			$tabsHtml .= '<li id = "emp_additional" onclick="changemyempviewscreen(\'myemployees\',\'additionaldetailsedit\','.$userId .');">'.TAB_EMP_ADDITIONAL.'</li>';
		}
            //$tabsHtml .='</ul>';
            $tabsHtml .='</li></ul>';
            $tabsHtml .='<script type="text/javascript">';
            $tabsHtml .='$(document).ready(function(){callscrollInner();});';
            $tabsHtml .='</script>';
            //tabs Completed
            $tabsHtml .='</div>';
            return $tabsHtml;
    }
}
?>