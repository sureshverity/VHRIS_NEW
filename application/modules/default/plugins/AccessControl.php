<?php
class Default_Plugin_AccessControl extends Zend_Controller_Plugin_Abstract
{
  private $_acl,$id_param;
          
  public function preDispatch(Zend_Controller_Request_Abstract $request)
  {
	$storage = new Zend_Auth_Storage_Session();
	$data = $storage->read();
	$role = $data['emprole'];
	if($role == 1)
		$role = 'admin';
	else if($role == 2)
	 $role = 'management';
	else if($role == 3)
	 $role = 'manager';
	else if($role == 4)
	 $role = 'HRManager';
	else if($role == 5)
	 $role = 'employee';
	else if($role == 6)
	 $role = 'user';
	else if($role == 7)
	 $role = 'agency';
	else if($role == 8)
	 $role = 'sysadmin';
	else if($role == 9)
	 $role = 'lead';
	else if($role == 10)
	 $role = 'VicePresident';
	else if($role == 11)
	 $role = 'Managers';
	else if($role == 13)
	 $role = 'finance';
	else if($role == 14)
	 $role = 'OnBoarding';
	
  	$request->getModuleName();
        $request->getControllerName();
        $request->getActionName();
    	
        
        $module = $request->getModuleName();
	$resource = $request->getControllerName();
	$privilege = $request->getActionName();
	$this->id_param = $request->getParam('id');
	$allowed = false;
        $acl = $this->_getAcl();
	$moduleResource = "$module:$resource";
	
	if($resource == 'profile')
            $role = 'viewer';
		
	if($resource == 'services')
            $role = 'services';
		
	if($role != '') 
        {
            if ($acl->has($moduleResource)) 
            {						
                $allowed = $acl->isAllowed($role, $moduleResource, $privilege);	
			    	 
            }	 
            if (!$allowed)//  && $role !='admin') 
            {				
                $request->setControllerName('error');
	        $request->setActionName('error');
            }
	}
  }
  
protected function _getAcl()
{
    if ($this->_acl == null ) 
    {
	   $acl = new Zend_Acl();

	   $acl->addRole('admin');            
	   $acl->addRole('viewer');            
	   
	 $acl->addRole('management');
	 $acl->addRole('manager');
	 $acl->addRole('HRManager');
	 $acl->addRole('employee');
	 $acl->addRole('user');
	 $acl->addRole('agency');
	 $acl->addRole('sysadmin');
	 $acl->addRole('lead');
	 $acl->addRole('VicePresident');
	 $acl->addRole('Managers');
	 $acl->addRole('finance');
	 $acl->addRole('OnBoarding');
	   $storage = new Zend_Auth_Storage_Session();
	   $data = $storage->read();
	   $role = $data['emprole'];
		
	$auth = Zend_Auth::getInstance(); 
	$tmroleText=array();
	$tmroleText = array('1'=>'admin','2'=>'management','3'=>'manager','4'=>'HRManager','5'=>'employee','6'=>'user','7'=>'agency','8'=>'sysadmin','9'=>'lead','10'=>'VicePresident','11'=>'Managers','13'=>'finance','14'=>'OnBoarding');
	
		if($auth->hasIdentity())
		{
			$tm_role = Zend_Registry::get('tm_role');
			$timeManagementRole = new Zend_Session_Namespace('tm_role');
			if(empty($timeManagementRole->tmrole))
			{
				$tm_role = $timeManagementRole->tmrole;
			}				
		}
			if(!empty($tm_role) && $tm_role == 'Admin') { 
	if(!isset($role))
								$tmroleText[$role] = 'admin';
		 $acl->addResource(new Zend_Acl_Resource('timemanagement:index'));
									$acl->allow($tmroleText[$role], 'timemanagement:index', array('index','week','edit','view','getstates','converdate'));

		 $acl->addResource(new Zend_Acl_Resource('timemanagement:reports'));
									$acl->allow($tmroleText[$role], 'timemanagement:reports', array('index','employeereports','projectsreports','getempduration','getprojecttaskduration','tmreport'));

		 $acl->addResource(new Zend_Acl_Resource('timemanagement:clients'));
									$acl->allow($tmroleText[$role], 'timemanagement:clients', array('index','edit','view','delete','addpopup'));

		 $acl->addResource(new Zend_Acl_Resource('timemanagement:configuration'));
									$acl->allow($tmroleText[$role], 'timemanagement:configuration', array('index','add'));

		 $acl->addResource(new Zend_Acl_Resource('timemanagement:currency'));
									$acl->allow($tmroleText[$role], 'timemanagement:currency', array('index'));

		 $acl->addResource(new Zend_Acl_Resource('timemanagement:defaulttasks'));
									$acl->allow($tmroleText[$role], 'timemanagement:defaulttasks', array('index','edit','view','delete','checkduptask'));

		 $acl->addResource(new Zend_Acl_Resource('timemanagement:emptimesheets'));
									$acl->allow($tmroleText[$role], 'timemanagement:emptimesheets', array('index','displayweeks','getmonthlyspan','accordion','employeetimesheet','empdisplayweeks','emptimesheetmonthly','emptimesheetweekly','enabletimesheet','approvetimesheet','rejecttimesheet','approvedaytimesheet','rejectdaytimesheet','getweekstartenddates'));

		 $acl->addResource(new Zend_Acl_Resource('timemanagement:expenses'));
									$acl->allow($tmroleText[$role], 'timemanagement:expenses', array('index','edit','view','delete','download','uploadpreview','getprojectbyclientid','getfilename','submitexpense','expensereports','viewexpenses','viewexpensereports','updateexpensestatus','updateexpensestatus','updateexpensestatus','updateexpensestatus','updateexpensestatus','updateexpensestatus','updateexpensestatus','updateexpensestatus','updateexpensestatus'));

		 $acl->addResource(new Zend_Acl_Resource('timemanagement:expensecategory'));
									$acl->allow($tmroleText[$role], 'timemanagement:expensecategory', array('index','edit','view','delete'));

		 $acl->addResource(new Zend_Acl_Resource('timemanagement:projectresources'));
									$acl->allow($tmroleText[$role], 'timemanagement:projectresources', array('index','resources','view','addresourcesproject','viewemptasks','addresources','deleteprojectresource','assigntasktoresources','taskassign','resourcetaskdelete','resourcetaskassigndelete'));

		 $acl->addResource(new Zend_Acl_Resource('timemanagement:projects'));
									$acl->allow($tmroleText[$role], 'timemanagement:projects', array('index','edit','view','add','tasks','addtasksproject','addtasks','delete','checkempforprojects'));

		 $acl->addResource(new Zend_Acl_Resource('timemanagement:projecttasks'));
									$acl->allow($tmroleText[$role], 'timemanagement:projecttasks', array('index','viewtasksresources','deletetask','assignresourcestotask','saveresources','edittaskname'));
 } elseif(!empty($tm_role) && $tm_role == 'Manager') { 
		 $acl->addResource(new Zend_Acl_Resource('timemanagement:index'));
									$acl->allow($tmroleText[$role], 'timemanagement:index', array('index','week','save','submit','eraseweek','getstates','getapprovedtimesheet','closeapprovealert','converdate'));

		 $acl->addResource(new Zend_Acl_Resource('timemanagement:notifications'));
									$acl->allow($tmroleText[$role], 'timemanagement:notifications', array('pendingsubmissions','pendingsubmissionsweeklyview','weeklymonthlyview'));

		 $acl->addResource(new Zend_Acl_Resource('timemanagement:clients'));
									$acl->allow($tmroleText[$role], 'timemanagement:clients', array('index','edit','view','delete','addpopup'));

		 $acl->addResource(new Zend_Acl_Resource('timemanagement:defaulttasks'));
									$acl->allow($tmroleText[$role], 'timemanagement:defaulttasks', array('index','edit','view','delete','checkduptask'));

		 $acl->addResource(new Zend_Acl_Resource('timemanagement:projects'));
									$acl->allow($tmroleText[$role], 'timemanagement:projects', array('index','edit','view','add','tasks','addtasksproject','addtasks','delete','checkempforprojects'));

		 $acl->addResource(new Zend_Acl_Resource('timemanagement:projectresources'));
									$acl->allow($tmroleText[$role], 'timemanagement:projectresources', array('index','resources','view','addresourcesproject','viewemptasks','addresources','deleteprojectresource','assigntasktoresources','taskassign','resourcetaskdelete','resourcetaskassigndelete'));

		 $acl->addResource(new Zend_Acl_Resource('timemanagement:projecttasks'));
									$acl->allow($tmroleText[$role], 'timemanagement:projecttasks', array('index','viewtasksresources','deletetask','assignresourcestotask','saveresources','edittaskname'));

		 $acl->addResource(new Zend_Acl_Resource('timemanagement:reports'));
									$acl->allow($tmroleText[$role], 'timemanagement:reports', array('index','employeereports','projectsreports','getempduration','getprojecttaskduration','tmreport'));

		 $acl->addResource(new Zend_Acl_Resource('timemanagement:emptimesheets'));
									$acl->allow($tmroleText[$role], 'timemanagement:emptimesheets', array('index','displayweeks','getmonthlyspan','accordion','employeetimesheet','empdisplayweeks','emptimesheetmonthly','emptimesheetweekly','enabletimesheet','approvetimesheet','rejecttimesheet','approvedaytimesheet','rejectdaytimesheet','getweekstartenddates'));

		 $acl->addResource(new Zend_Acl_Resource('timemanagement:expenses'));
									$acl->allow($tmroleText[$role], 'timemanagement:expenses', array('index','edit','view','delete','download','uploadpreview','getprojectbyclientid','getfilename','submitexpense','expensereports','viewexpenses','viewexpensereports','updateexpensestatus','updateexpensestatus','updateexpensestatus','updateexpensestatus','updateexpensestatus','updateexpensestatus','updateexpensestatus','updateexpensestatus','updateexpensestatus'));
 } elseif(!empty($tm_role) && $tm_role == 'Employee') { 
		 $acl->addResource(new Zend_Acl_Resource('timemanagement:index'));
									$acl->allow($tmroleText[$role], 'timemanagement:index', array('index','week','save','submit','eraseweek','getstates','getapprovedtimesheet','closeapprovealert','converdate'));

		 $acl->addResource(new Zend_Acl_Resource('timemanagement:employeeprojects'));
									$acl->allow($tmroleText[$role], 'timemanagement:employeeprojects', array('index','view','emptasksgrid'));

		 $acl->addResource(new Zend_Acl_Resource('timemanagement:notifications'));
									$acl->allow($tmroleText[$role], 'timemanagement:notifications', array('getnotifications','index'));

		 $acl->addResource(new Zend_Acl_Resource('timemanagement:expenses'));
									$acl->allow($tmroleText[$role], 'timemanagement:expenses', array('index','edit','view','delete','download','uploadpreview','getprojectbyclientid','getfilename','submitexpense','expensereports','viewexpenses','viewexpensereports','updateexpensestatus','updateexpensestatus','updateexpensestatus','updateexpensestatus','updateexpensestatus','updateexpensestatus','updateexpensestatus','updateexpensestatus','updateexpensestatus'));

		 $acl->addResource(new Zend_Acl_Resource('timemanagement:reports'));
									$acl->allow($tmroleText[$role], 'timemanagement:reports', array('index','employeereports','projectsreports','getempduration','getprojecttaskduration','tmreport'));
 } 
		
	   $acl->addResource(new Zend_Acl_Resource('login:index'));	
	   $acl->allow('viewer', 'login:index', array('index','confirmlink','forgotpassword','forgotsuccess','login','pass','browserfailure','forcelogout','useractivation'));

	   if($role == 1 ) 
	   {				 		    	
			   
		 $acl->addResource(new Zend_Acl_Resource('default:accountclasstype'));
                    $acl->allow('admin', 'default:accountclasstype', array('index','view','edit','addpopup','saveupdate','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:addemployeeleaves'));
                    $acl->allow('admin', 'default:addemployeeleaves', array('index','add','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:agencylist'));
                    $acl->allow('admin', 'default:agencylist', array('index','add','view','edit','delete','deletepoc'));

		 $acl->addResource(new Zend_Acl_Resource('default:announcements'));
                    $acl->allow('admin', 'default:announcements', array('index','add','view','edit','getdepts','delete','uploadsave','uploaddelete'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalcategory'));
                    $acl->allow('admin', 'default:appraisalcategory', array('index','add','view','edit','delete','addpopup','getappraisalcategory'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalhistoryself'));
                    $acl->allow('admin', 'default:appraisalhistoryself', array('index','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalhistoryteam'));
                    $acl->allow('admin', 'default:appraisalhistoryteam', array('index','view','getsearchedempcontent'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalinit'));
                    $acl->allow('admin', 'default:appraisalinit', array('checkappadmin','getdepartmentsadmin','discardsteptwo','displayline','addlinemanager','displayreport','deletelinemanager','deletereportmanager','constructreportacc','constructacc','displayemployees','displaycontentreportacc','displaycontentacc','viewconfmanagers','confmanagers','displaymanagers','displayreportmanagers','getperiod','index','add','delete','view','edit','viewassigngroups','assigngroups','displaygroupedemployees','showgroupedemployees','viewgroupedemployees','savegroupedemployeesajax','changesettings','displaysettings','deletegroupedemployees','initializegroup','completeappraisal','checkemployeeresponse','getemployeeslinemanagers','savemngrorghierarchy','getconfiglinemanagers','validateconfig'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalmanager'));
                    $acl->allow('admin', 'default:appraisalmanager', array('submitmanager','deletemanagergroup','savemanagergroup','index','viewgroup','createnewgroup','showgroups','showviewgroups','view','edit'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalquestions'));
                    $acl->allow('admin', 'default:appraisalquestions', array('index','addpopup','add','view','edit','delete','savequestionpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalratings'));
                    $acl->allow('admin', 'default:appraisalratings', array('index','addratings','add','view','edit'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalself'));
                    $acl->allow('admin', 'default:appraisalself', array('index','edit','save'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalskills'));
                    $acl->allow('admin', 'default:appraisalskills', array('index','add','view','edit','delete','getappraisalskills','saveskillspopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalstatus'));
                    $acl->allow('admin', 'default:appraisalstatus', array('index','manager','managerstatus','checkappraisalimplementation','employee','employeestatus','employeeActi','addlinemanager','displaymanagers','updatelinemanager'));

		 $acl->addResource(new Zend_Acl_Resource('default:approvedrequisitions'));
                    $acl->allow('admin', 'default:approvedrequisitions', array('index','view','edit'));

		 $acl->addResource(new Zend_Acl_Resource('default:attendancestatuscode'));
                    $acl->allow('admin', 'default:attendancestatuscode', array('index','view','edit','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:bankaccounttype'));
                    $acl->allow('admin', 'default:bankaccounttype', array('index','view','edit','addpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:bgscreeningtype'));
                    $acl->allow('admin', 'default:bgscreeningtype', array('index','view','edit','add','delete','addpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:businessunits'));
                    $acl->allow('admin', 'default:businessunits', array('index','edit','view','delete','getdeptnames'));

		 $acl->addResource(new Zend_Acl_Resource('default:candidatedetails'));
                    $acl->allow('admin', 'default:candidatedetails', array('index','view','edit','add','delete','chkcandidate','uploadfile','deleteresume','download','multipleresume'));

		 $acl->addResource(new Zend_Acl_Resource('default:categories'));
                    $acl->allow('admin', 'default:categories', array('index','add','edit','view','delete','addnewcategory'));

		 $acl->addResource(new Zend_Acl_Resource('default:cities'));
                    $acl->allow('admin', 'default:cities', array('index','view','edit','delete','getcitiescand','addpopup','addnewcity'));

		 $acl->addResource(new Zend_Acl_Resource('default:competencylevel'));
                    $acl->allow('admin', 'default:competencylevel', array('index','view','edit','addpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:components'));
                    $acl->allow('admin', 'default:components', array('index','add','view','edit','delete','addpopup','getrequests'));

		 $acl->addResource(new Zend_Acl_Resource('default:componentsgroup'));
                    $acl->allow('admin', 'default:componentsgroup', array('index','add','view','edit','delete','addpopup','getrequests'));

		 $acl->addResource(new Zend_Acl_Resource('default:countries'));
                    $acl->allow('admin', 'default:countries', array('index','view','edit','saveupdate','delete','getcountrycode','addpopup','addnewcountry'));

		 $acl->addResource(new Zend_Acl_Resource('default:currency'));
                    $acl->allow('admin', 'default:currency', array('index','view','edit','addpopup','delete','gettargetcurrency'));

		 $acl->addResource(new Zend_Acl_Resource('default:currencyconverter'));
                    $acl->allow('admin', 'default:currencyconverter', array('index','add','view','edit','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:dashboard'));
                    $acl->allow('admin', 'default:dashboard', array('index','saveuserdashboard','getwidgtes','upgradeapplication','emailsettings','changepassword','editpassword','update','uploadpreview','viewprofile','viewsettings','savemenuwidgets','getmenuname','fetchmenuname','getnavids','getopeningpositondate','menuwork'));

		 $acl->addResource(new Zend_Acl_Resource('default:departments'));
                    $acl->allow('admin', 'default:departments', array('index','view','viewpopup','edit','editpopup','getdepartments','delete','getempnames'));

		 $acl->addResource(new Zend_Acl_Resource('default:downloadpayslip'));
                    $acl->allow('admin', 'default:downloadpayslip', array('index','add','view','edit','delete','addpopup','getrequests'));

		 $acl->addResource(new Zend_Acl_Resource('default:educationlevelcode'));
                    $acl->allow('admin', 'default:educationlevelcode', array('index','view','edit','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:eeoccategory'));
                    $acl->allow('admin', 'default:eeoccategory', array('index','view','edit','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:emailcontacts'));
                    $acl->allow('admin', 'default:emailcontacts', array('index','add','edit','getgroupoptions','view','delete','getmailcnt'));

		 $acl->addResource(new Zend_Acl_Resource('default:empconfiguration'));
                    $acl->allow('admin', 'default:empconfiguration', array('index','edit','add'));

		 $acl->addResource(new Zend_Acl_Resource('default:empleavesummary'));
                    $acl->allow('admin', 'default:empleavesummary', array('index','statusid','view','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:employee'));
                    $acl->allow('admin', 'default:employee', array('getemprequi','index','changeorghead','add','edit','view','getdepartments','getpositions','delete','getempreportingmanagers','makeactiveinactive','changereportingmanager','addemppopup','uploadexcel','getindividualempdetails'));

		 $acl->addResource(new Zend_Acl_Resource('default:employeeattendanceapprovals'));
                    $acl->allow('admin', 'default:employeeattendanceapprovals', array('index','view','edit','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:employeecomponents'));
                    $acl->allow('admin', 'default:employeecomponents', array('index','add','view','edit','delete','addpopup','getrequests'));

		 $acl->addResource(new Zend_Acl_Resource('default:employeeleavetypes'));
                    $acl->allow('admin', 'default:employeeleavetypes', array('index','view','edit','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:employmentstatus'));
                    $acl->allow('admin', 'default:employmentstatus', array('index','view','edit','delete','addpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:empscreening'));
                    $acl->allow('admin', 'default:empscreening', array('index','add','edit','view','getemployeedata','getagencylist','getpocdata','forcedfullupdate','delete','checkscreeningstatus','uploadfeedback','download','deletefeedback'));

		 $acl->addResource(new Zend_Acl_Resource('default:ethniccode'));
                    $acl->allow('admin', 'default:ethniccode', array('index','view','edit','saveupdate','delete','addpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:feedforwardemployee'));
                    $acl->allow('admin', 'default:feedforwardemployee', array('index','edit','save'));

		 $acl->addResource(new Zend_Acl_Resource('default:feedforwardinit'));
                    $acl->allow('admin', 'default:feedforwardinit', array('index','add','getappraisaldetails','edit','view','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:feedforwardmanager'));
                    $acl->allow('admin', 'default:feedforwardmanager', array('index','getmanagersratings','getdetailedratings','getdetailedratingsbyemp','getdetailedratingsbyques'));

		 $acl->addResource(new Zend_Acl_Resource('default:feedforwardquestions'));
                    $acl->allow('admin', 'default:feedforwardquestions', array('index','add','view','edit','delete','savepopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:feedforwardstatus'));
                    $acl->allow('admin', 'default:feedforwardstatus', array('index','getffstatusemps','getfeedforwardstatus'));

		 $acl->addResource(new Zend_Acl_Resource('default:formulafields'));
                    $acl->allow('admin', 'default:formulafields', array('index','add','view','edit','delete','addpopup','getrequests'));

		 $acl->addResource(new Zend_Acl_Resource('default:gender'));
                    $acl->allow('admin', 'default:gender', array('index','view','edit','saveupdate','delete','addpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:generatepayroll'));
                    $acl->allow('admin', 'default:generatepayroll', array('index','add','view','edit','delete','addpopup','getrequests'));

		 $acl->addResource(new Zend_Acl_Resource('default:geographygroup'));
                    $acl->allow('admin', 'default:geographygroup', array('index','view','edit','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:heirarchy'));
                    $acl->allow('admin', 'default:heirarchy', array('index','edit','addlist','editlist','saveadddata','saveeditdata','deletelist'));

		 $acl->addResource(new Zend_Acl_Resource('default:holidaydates'));
                    $acl->allow('admin', 'default:holidaydates', array('index','add','addpopup','view','viewpopup','edit','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:holidaygroups'));
                    $acl->allow('admin', 'default:holidaygroups', array('index','add','view','edit','delete','getempnames','getholidaynames','addpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:identitycodes'));
                    $acl->allow('admin', 'default:identitycodes', array('index','add','addpopup','edit'));

		 $acl->addResource(new Zend_Acl_Resource('default:identitydocuments'));
                    $acl->allow('admin', 'default:identitydocuments', array('index','add','view','edit','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:index'));
                    $acl->allow('admin', 'default:index', array('index','loginpopupsave','logout','clearsessionarray','forcelogout','browserfailure','sendpassword','updatecontactnumber','getstates','getstatesnormal','getcities','getcitiesnormal','getdepartments','getpositions','gettargetcurrency','calculatedays','calculatebusinessdays','calculatecalendardays','fromdatetodate','fromdatetodateorg','validateorgheadjoiningdate','medicalclaimdates','gettimeformat','chkcurrenttime','popup','createorremoveshortcut','sessiontour','getissuingauthority','setsessionval','checkisactivestatus','updatetheme','welcome','getmultidepts','getmultiemps','getpayrollcomponents','getpayrollattendance','getpayrollformulacomponents','getdownloadayslip','savepayrollcomponents','saveformulafields','getbunitdetails','getpayrollyeardetails','getpayrolldetails','generatepayroll','employeeleavesonleavetypes','generatepayslip','onboardingcandidates','getempsearch'));

		 $acl->addResource(new Zend_Acl_Resource('default:jobtitles'));
                    $acl->allow('admin', 'default:jobtitles', array('index','view','edit','addpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:language'));
                    $acl->allow('admin', 'default:language', array('index','view','edit','delete','addpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:leavemanagement'));
                    $acl->allow('admin', 'default:leavemanagement', array('index','add','view','edit','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:leaverequest'));
                    $acl->allow('admin', 'default:leaverequest', array('index','saveleaverequestdetails','gethalfdaydetails','editpopup','updateleavedetails'));

		 $acl->addResource(new Zend_Acl_Resource('default:licensetype'));
                    $acl->allow('admin', 'default:licensetype', array('index','view','edit','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:managemenus'));
                    $acl->allow('admin', 'default:managemenus', array('index','save'));

		 $acl->addResource(new Zend_Acl_Resource('default:manageremployeevacations'));
                    $acl->allow('admin', 'default:manageremployeevacations', array('index','view','edit','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:maritalstatus'));
                    $acl->allow('admin', 'default:maritalstatus', array('index','view','edit','saveupdate','delete','addpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:militaryservice'));
                    $acl->allow('admin', 'default:militaryservice', array('index','view','edit','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:mydetails'));
                    $acl->allow('admin', 'default:mydetails', array('index','personaldetailsview','personal','communicationdetailsview','communication','skills','education','experience','leaves','holidays','salarydetailsview','certification','creditcarddetailsview','creditcard','visadetailsview','visa','medicalclaims','disabilitydetailsview','disability','dependency','workeligibilitydetailsview','workeligibility','additionaldetailsedit','jobhistory','delete','documents','assetdetailsview'));

		 $acl->addResource(new Zend_Acl_Resource('default:myemployees'));
                    $acl->allow('admin', 'default:myemployees', array('index','view','perview','comview','skillsview','expview','eduview','trainingview','additionaldetailsview','jobhistoryview','add','edit','skillsedit','jobhistoryedit','expedit','eduedit','trainingedit','additionaldetailsedit','peredit','comedit','docview','docedit','employeereport','getempreportdata','empauto','emprptpdf','exportemployeereport','downloadreport'));

		 $acl->addResource(new Zend_Acl_Resource('default:myholidaycalendar'));
                    $acl->allow('admin', 'default:myholidaycalendar', array('index','view','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:myteamappraisal'));
                    $acl->allow('admin', 'default:myteamappraisal', array('savelineresponse','savemngresponse','getempcontent','index','getsearchedempcontent','getsearchedstatus','downloadpdf','downloadUploadedFile'));

		 $acl->addResource(new Zend_Acl_Resource('default:nationality'));
                    $acl->allow('admin', 'default:nationality', array('index','view','edit','delete','addpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:nationalitycontextcode'));
                    $acl->allow('admin', 'default:nationalitycontextcode', array('index','view','edit','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:numberformats'));
                    $acl->allow('admin', 'default:numberformats', array('index','add','view','edit','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:onboardingcandidates'));
                    $acl->allow('admin', 'default:onboardingcandidates', array('index','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:organisationinfo'));
                    $acl->allow('admin', 'default:organisationinfo', array('index','edit','edit_old','saveupdate','uploadpreview','validateorgstartdate','getcompleteorgdata','addorghead'));

		 $acl->addResource(new Zend_Acl_Resource('default:payfrequency'));
                    $acl->allow('admin', 'default:payfrequency', array('index','addpopup','view','edit','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:payrollattendancereport'));
                    $acl->allow('admin', 'default:payrollattendancereport', array('index','add','view','edit','delete','addpopup','getrequests'));

		 $acl->addResource(new Zend_Acl_Resource('default:payrollattendancerequest'));
                    $acl->allow('admin', 'default:payrollattendancerequest', array('index','add','view','edit','delete','addpopup','getrequests'));

		 $acl->addResource(new Zend_Acl_Resource('default:payrollemployeeadvances'));
                    $acl->allow('admin', 'default:payrollemployeeadvances', array('index','edit','delete','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:payrollmyadvances'));
                    $acl->allow('admin', 'default:payrollmyadvances', array('index'));

		 $acl->addResource(new Zend_Acl_Resource('default:payrollperiods'));
                    $acl->allow('admin', 'default:payrollperiods', array('index','add','view','edit','delete','addpopup','getrequests'));

		 $acl->addResource(new Zend_Acl_Resource('default:pendingleaves'));
                    $acl->allow('admin', 'default:pendingleaves', array('index','view','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:policydocuments'));
                    $acl->allow('admin', 'default:policydocuments', array('index','add','edit','view','delete','uploaddoc','deletedocument','addmultiple','uploadmultipledocs'));

		 $acl->addResource(new Zend_Acl_Resource('default:positions'));
                    $acl->allow('admin', 'default:positions', array('index','add','view','edit','addpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:prefix'));
                    $acl->allow('admin', 'default:prefix', array('index','view','edit','saveupdate','delete','addpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:racecode'));
                    $acl->allow('admin', 'default:racecode', array('index','view','edit','saveupdate','delete','addpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:rejectedrequisitions'));
                    $acl->allow('admin', 'default:rejectedrequisitions', array('index','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:remunerationbasis'));
                    $acl->allow('admin', 'default:remunerationbasis', array('index','view','edit','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:reports'));
                    $acl->allow('admin', 'default:reports', array('getrolepopup','emprolesgrouppopup','performancereport','previousappraisals','getselectedappraisaldata','getinterviewroundsdata','interviewrounds','rolesgroup','exportemprolesgroup','exportrolesgroupreport','exportinterviewrpt','exportactiveuserrpt','exportpayrollrpt','exportemployeereport','rolesgrouprptpdf','activeuserrptpdf','emprptpdf','interviewrptpdf','rolesgroupdata','emprolesgroup','emprolesgroupdata','activeuser','getactiveuserdata','getattendancereportsdata','getpayrollreportsdata','getempreportdata','empauto','servicedeskreport','getsddata','servicedeskpdf','servicedeskexcel','employeereport','getdeptsemp','index','holidaygroupreports','getpdfreportholiday','getexcelreportholiday','payrollreport','attendancereport','leavesreport','getpdfreportleaves','getexcelreportleaves','leavesreporttabheader','leavemanagementreport','getpdfreportleavemanagement','getexcelreportleavemanagement','bunitauto','bunitcodeauto','getexcelreportbusinessunit','getbusinessunitspdf','businessunits','userlogreport','departments','exportdepartmentpdf','getexcelreportdepartment','candidaterptexcel','candidaterptpdf','getcandidatesreportdata','candidatesreport','requisitionauto','requisitionrptexcel','requisitionrptpdf','getrequisitionsstatusreportdata','requisitionstatusreport','activitylogreport','downloadreport','agencylistreport','agencynameauto','agencysebsiteauto','empscreening','getspecimennames','getagencynames','getexcelreportempscreening','getempscreeningpdf'));

		 $acl->addResource(new Zend_Acl_Resource('default:requisition'));
                    $acl->allow('admin', 'default:requisition', array('index','add','edit','viewhr','approverequisition','addcandidate','interview','getdepartments','getpositions','viewpopup','view','delete','getapprreqdata','chkreqforclose','getempreportingmanagers','getemailcount','getapprovers'));

		 $acl->addResource(new Zend_Acl_Resource('default:roles'));
                    $acl->allow('admin', 'default:roles', array('index','view','edit','saveupdate','delete','getgroupmenu'));

		 $acl->addResource(new Zend_Acl_Resource('default:scheduleinterviews'));
                    $acl->allow('admin', 'default:scheduleinterviews', array('candidatepopup','index','view','add','downloadresume','edit','getcandidates','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:servicedeskconf'));
                    $acl->allow('admin', 'default:servicedeskconf', array('index','add','view','edit','delete','getemployees','getapprover','getbunitimplementation','getassets'));

		 $acl->addResource(new Zend_Acl_Resource('default:servicedeskdepartment'));
                    $acl->allow('admin', 'default:servicedeskdepartment', array('index','add','view','edit','delete','addpopup','getrequests'));

		 $acl->addResource(new Zend_Acl_Resource('default:servicedeskrequest'));
                    $acl->allow('admin', 'default:servicedeskrequest', array('index','add','view','edit','delete'));


		 $acl->addResource(new Zend_Acl_Resource('default:shortlistedcandidates'));
                    $acl->allow('admin', 'default:shortlistedcandidates', array('index','edit','view','add','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:sitepreference'));
                    $acl->allow('admin', 'default:sitepreference', array('index','add','view','edit','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:states'));
                    $acl->allow('admin', 'default:states', array('index','view','edit','delete','getstates','getstatescand','addpopup','addnewstate'));

		 $acl->addResource(new Zend_Acl_Resource('default:structure'));
                    $acl->allow('admin', 'default:structure', array('index'));

		 $acl->addResource(new Zend_Acl_Resource('default:timezone'));
                    $acl->allow('admin', 'default:timezone', array('index','view','edit','saveupdate','delete','addpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:usermanagement'));
                    $acl->allow('admin', 'default:usermanagement', array('index','view','edit','saveupdate','delete','getemailofuser'));

		 $acl->addResource(new Zend_Acl_Resource('default:veteranstatus'));
                    $acl->allow('admin', 'default:veteranstatus', array('index','view','edit','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:wizard'));
                    $acl->allow('admin', 'default:wizard', array('index','managemenu','savemenu','configuresite','configureorganisation','updatewizardcompletion','configureunitsanddepartments','savebusinessunit','configureservicerequest','savecategory'));

		 $acl->addResource(new Zend_Acl_Resource('default:workeligibilitydoctypes'));
                    $acl->allow('admin', 'default:workeligibilitydoctypes', array('index','view','edit','addpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('assets:assetcategories'));
                    $acl->allow('admin', 'assets:assetcategories', array('index','edit','view','delete','addpopup','addsubcatpopup','assetuserlog'));

		 $acl->addResource(new Zend_Acl_Resource('assets:assets'));
                    $acl->allow('admin', 'assets:assets', array('index','edit','delete','uploadsave','uploaddelete','view','getsubcategories','deleteimage','downloadimage','getemployeesdata'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:advances'));
                    $acl->allow('admin', 'expenses:advances', array('index','getprojects','myadvances','viewmoreadvances','clearadvancesdata','addreturnpopup'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:employeeadvances'));
                    $acl->allow('admin', 'expenses:employeeadvances', array('index','edit','delete','view'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:expensecategories'));
                    $acl->allow('admin', 'expenses:expensecategories', array('index','edit','delete'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:expenses'));
                    $acl->allow('admin', 'expenses:expenses', array('index','edit','clone','view','delete','addpopup','uploadsave','uploaddelete','displayreceipts','addtrippopup','submitexpense','addreceiptimage','expensestatus','listreportingmangers','viewmoremanagers','forwardexpenseto','downloadexpensepdf','bulkexpenses','getcategories','getprojects','getcurrency','uploadedfiles'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:myemployeeexpenses'));
                    $acl->allow('admin', 'expenses:myemployeeexpenses', array('index','view'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:paymentmode'));
                    $acl->allow('admin', 'expenses:paymentmode', array('index','edit','delete'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:receipts'));
                    $acl->allow('admin', 'expenses:receipts', array('index','downloadreceipt','downloadexpensereceipt','deletereceipt','uploadsave','displayreceipts','viewmorereceipts','listexpenses','addreceipttoexpense','viewmoreexpenses','cleardata','showreceiptspopup','listtrips','viewmoretrips','addexpensetotrip'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:trips'));
                    $acl->allow('admin', 'expenses:trips', array('index','edit','view','delete','addpopup','tripstatus','deleteexpense','downloadtrippdf'));

		 $acl->addResource(new Zend_Acl_Resource('default:processes'));
                    $acl->allow('admin', 'default:processes', array('index','addpopup','editpopup','viewpopup','delete','savecomments','displaycomments','savefeedback'));

		 $acl->addResource(new Zend_Acl_Resource('default:interviewrounds'));
                    $acl->allow('admin', 'default:interviewrounds', array('index','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:empperformanceappraisal'));
                    $acl->allow('admin', 'default:empperformanceappraisal', array('index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:emppayslips'));
                    $acl->allow('admin', 'default:emppayslips', array('index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empbenefits'));
                    $acl->allow('admin', 'default:empbenefits', array('index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:emprequisitiondetails'));
                    $acl->allow('admin', 'default:emprequisitiondetails', array('index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empremunerationdetails'));
                    $acl->allow('admin', 'default:empremunerationdetails', array('index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empsecuritycredentials'));
                    $acl->allow('admin', 'default:empsecuritycredentials', array('index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:apprreqcandidates'));
                    $acl->allow('admin', 'default:apprreqcandidates', array('index','viewpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:emppersonaldetails'));
                    $acl->allow('admin', 'default:emppersonaldetails', array('index','view','edit'));

		 $acl->addResource(new Zend_Acl_Resource('default:employeedocs'));
                    $acl->allow('admin', 'default:employeedocs', array('index','view','save','delete','edit','update','uploadsave','uploaddelete','downloadfiles'));

		 $acl->addResource(new Zend_Acl_Resource('default:empcommunicationdetails'));
                    $acl->allow('admin', 'default:empcommunicationdetails', array('index','view','edit'));

		 $acl->addResource(new Zend_Acl_Resource('default:trainingandcertificationdetails'));
                    $acl->allow('admin', 'default:trainingandcertificationdetails', array('index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:experiencedetails'));
                    $acl->allow('admin', 'default:experiencedetails', array('index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:educationdetails'));
                    $acl->allow('admin', 'default:educationdetails', array('index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:medicalclaims'));
                    $acl->allow('admin', 'default:medicalclaims', array('index','edit','addpopup','viewpopup','editpopup','delete','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empleaves'));
                    $acl->allow('admin', 'default:empleaves', array('index','edit','view','viewpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:empskills'));
                    $acl->allow('admin', 'default:empskills', array('index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:disabilitydetails'));
                    $acl->allow('admin', 'default:disabilitydetails', array('index','add','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:workeligibilitydetails'));
                    $acl->allow('admin', 'default:workeligibilitydetails', array('index','add','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empadditionaldetails'));
                    $acl->allow('admin', 'default:empadditionaldetails', array('index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:visaandimmigrationdetails'));
                    $acl->allow('admin', 'default:visaandimmigrationdetails', array('index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:creditcarddetails'));
                    $acl->allow('admin', 'default:creditcarddetails', array('index','add','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:dependencydetails'));
                    $acl->allow('admin', 'default:dependencydetails', array('index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:empholidays'));
                    $acl->allow('admin', 'default:empholidays', array('index','edit','view','viewpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:empjobhistory'));
                    $acl->allow('admin', 'default:empjobhistory', array('index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:assetdetails'));
                    $acl->allow('admin', 'default:assetdetails', array('index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empsalarydetails'));
                    $acl->allow('admin', 'default:empsalarydetails', array('index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:logmanager'));
                    $acl->allow('admin', 'default:logmanager', array('index','view','empnamewithidauto'));

		 $acl->addResource(new Zend_Acl_Resource('default:userloginlog'));
                    $acl->allow('admin', 'default:userloginlog', array('index','empnameauto','empidauto','empipaddressauto','empemailauto'));

         $acl->addResource(new Zend_Acl_Resource('default:shifttypes'));
                    $acl->allow('admin', 'default:shifttypes', array('index','add','view','edit','delete','addpopup','getrequests'));

         $acl->addResource(new Zend_Acl_Resource('default:assignshift'));
                    $acl->allow('admin', 'default:assignshift', array('index','add','view','edit','delete','addpopup','getrequests'));

       }
	   if($role == 2 )
           {
		 $acl->addResource(new Zend_Acl_Resource('default:accountclasstype'));
                            $acl->allow('management', 'default:accountclasstype', array('index','addpopup','saveupdate','add','edit','delete','view','Account Class Types'));

		 $acl->addResource(new Zend_Acl_Resource('default:addemployeeleaves'));
                            $acl->allow('management', 'default:addemployeeleaves', array('index','add','edit','view','Add Employee Leaves'));

		 $acl->addResource(new Zend_Acl_Resource('default:agencylist'));
                            $acl->allow('management', 'default:agencylist', array('index','deletepoc','add','edit','delete','view','Agencies'));

		 $acl->addResource(new Zend_Acl_Resource('default:announcements'));
                            $acl->allow('management', 'default:announcements', array('index','getdepts','uploadsave','uploaddelete','add','edit','delete','view','Announcements'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalcategory'));
                            $acl->allow('management', 'default:appraisalcategory', array('index','addpopup','getappraisalcategory','add','edit','delete','view','Parameters'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalhistoryself'));
                            $acl->allow('management', 'default:appraisalhistoryself', array('index','view','My Appraisal History'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalhistoryteam'));
                            $acl->allow('management', 'default:appraisalhistoryteam', array('index','getsearchedempcontent','view','Team Appraisal History'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalinit'));
                            $acl->allow('management', 'default:appraisalinit', array('checkappadmin','getdepartmentsadmin','discardsteptwo','displayline','addlinemanager','displayreport','deletelinemanager','deletereportmanager','constructreportacc','constructacc','displayemployees','displaycontentreportacc','displaycontentacc','viewconfmanagers','confmanagers','displaymanagers','displayreportmanagers','getperiod','index','viewassigngroups','assigngroups','displaygroupedemployees','showgroupedemployees','viewgroupedemployees','savegroupedemployeesajax','changesettings','displaysettings','deletegroupedemployees','initializegroup','completeappraisal','checkemployeeresponse','getemployeeslinemanagers','savemngrorghierarchy','getconfiglinemanagers','validateconfig','add','edit','view','Initialize Appraisal'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalmanager'));
                            $acl->allow('management', 'default:appraisalmanager', array('submitmanager','deletemanagergroup','savemanagergroup','index','viewgroup','createnewgroup','showgroups','showviewgroups','edit','view','Manager Appraisal'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalquestions'));
                            $acl->allow('management', 'default:appraisalquestions', array('index','addpopup','savequestionpopup','add','edit','delete','view','Questions'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalratings'));
                            $acl->allow('management', 'default:appraisalratings', array('index','addratings','add','edit','view','Ratings'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalself'));
                            $acl->allow('management', 'default:appraisalself', array('index','save','edit','view','Self Appraisal'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalskills'));
                            $appraisalskills_add = 'yes';
                                if($this->id_param == '' && $appraisalskills_add == 'yes')
                                    $acl->allow('management','default:appraisalskills', array('index','getappraisalskills','saveskillspopup','add','view','Skills','edit'));

                                else
                                    $acl->allow('management','default:appraisalskills', array('index','getappraisalskills','saveskillspopup','add','view','Skills'));

                                
		 $acl->addResource(new Zend_Acl_Resource('default:appraisalstatus'));
                            $acl->allow('management', 'default:appraisalstatus', array('index','manager','managerstatus','checkappraisalimplementation','employee','employeestatus','employeeActi','addlinemanager','displaymanagers','updatelinemanager','view','Employee Status'));

		 $acl->addResource(new Zend_Acl_Resource('default:approvedrequisitions'));
                            $acl->allow('management', 'default:approvedrequisitions', array('index','edit','view','Approved Requisitions'));

		 $acl->addResource(new Zend_Acl_Resource('default:attendancestatuscode'));
                            $acl->allow('management', 'default:attendancestatuscode', array('index','add','edit','delete','view','Attendance Status'));

		 $acl->addResource(new Zend_Acl_Resource('default:bankaccounttype'));
                            $acl->allow('management', 'default:bankaccounttype', array('index','addpopup','add','edit','delete','view','Bank Account Types'));

		 $acl->addResource(new Zend_Acl_Resource('default:bgscreeningtype'));
                            $acl->allow('management', 'default:bgscreeningtype', array('index','addpopup','add','edit','delete','view','Screening Types'));

		 $acl->addResource(new Zend_Acl_Resource('default:businessunits'));
                            $acl->allow('management', 'default:businessunits', array('index','getdeptnames','add','edit','delete','view','Business Units'));

		 $acl->addResource(new Zend_Acl_Resource('default:candidatedetails'));
                            $acl->allow('management', 'default:candidatedetails', array('index','chkcandidate','uploadfile','deleteresume','download','multipleresume','add','edit','delete','view','CV Management'));

		 $acl->addResource(new Zend_Acl_Resource('default:categories'));
                            $acl->allow('management', 'default:categories', array('index','addnewcategory','add','edit','delete','view','Categories'));

		 $acl->addResource(new Zend_Acl_Resource('default:cities'));
                            $cities_add = 'yes';
                                if($this->id_param == '' && $cities_add == 'yes')
                                    $acl->allow('management','default:cities', array('index','getcitiescand','addpopup','addnewcity','add','delete','view','Cities','edit'));

                                else
                                    $acl->allow('management','default:cities', array('index','getcitiescand','addpopup','addnewcity','add','delete','view','Cities'));

                                
		 $acl->addResource(new Zend_Acl_Resource('default:competencylevel'));
                            $acl->allow('management', 'default:competencylevel', array('index','addpopup','add','edit','delete','view','Competency Levels'));

		 $acl->addResource(new Zend_Acl_Resource('default:countries'));
                            $countries_add = 'yes';
                                if($this->id_param == '' && $countries_add == 'yes')
                                    $acl->allow('management','default:countries', array('index','saveupdate','getcountrycode','addpopup','addnewcountry','add','delete','view','Countries','edit'));

                                else
                                    $acl->allow('management','default:countries', array('index','saveupdate','getcountrycode','addpopup','addnewcountry','add','delete','view','Countries'));

                                
		 $acl->addResource(new Zend_Acl_Resource('default:currency'));
                            $acl->allow('management', 'default:currency', array('index','addpopup','gettargetcurrency','add','edit','delete','view','Currencies'));

		 $acl->addResource(new Zend_Acl_Resource('default:currencyconverter'));
                            $acl->allow('management', 'default:currencyconverter', array('index','add','edit','delete','view','Currency Conversions'));

		 $acl->addResource(new Zend_Acl_Resource('default:dashboard'));
                        $acl->allow('management', 'default:dashboard', array('index','saveuserdashboard','getwidgtes','upgradeapplication','emailsettings','changepassword','editpassword','update','uploadpreview','viewprofile','viewsettings','savemenuwidgets','getmenuname','fetchmenuname','getnavids','getopeningpositondate','menuwork'));

		 $acl->addResource(new Zend_Acl_Resource('default:departments'));
                            $acl->allow('management', 'default:departments', array('index','viewpopup','editpopup','getdepartments','getempnames','add','edit','delete','view','Departments'));

		 $acl->addResource(new Zend_Acl_Resource('default:educationlevelcode'));
                            $acl->allow('management', 'default:educationlevelcode', array('index','add','edit','delete','view','Education Levels'));

		 $acl->addResource(new Zend_Acl_Resource('default:eeoccategory'));
                            $acl->allow('management', 'default:eeoccategory', array('index','add','edit','delete','view','EEOC Categories'));

		 $acl->addResource(new Zend_Acl_Resource('default:emailcontacts'));
                            $acl->allow('management', 'default:emailcontacts', array('index','getgroupoptions','getmailcnt','add','edit','delete','view','Email Contacts'));

		 $acl->addResource(new Zend_Acl_Resource('default:empconfiguration'));
                            $acl->allow('management', 'default:empconfiguration', array('index','edit','Employee Tabs'));

		 $acl->addResource(new Zend_Acl_Resource('default:empleavesummary'));
                            $acl->allow('management', 'default:empleavesummary', array('index','statusid','view','Employee Leaves Summary'));

		 $acl->addResource(new Zend_Acl_Resource('default:employee'));
                            $acl->allow('management', 'default:employee', array('getemprequi','index','changeorghead','getdepartments','getpositions','getempreportingmanagers','makeactiveinactive','changereportingmanager','addemppopup','uploadexcel','getindividualempdetails','add','edit','view','Employees'));

		 $acl->addResource(new Zend_Acl_Resource('default:employeeattendanceapprovals'));
                            $acl->allow('management', 'default:employeeattendanceapprovals', array('index','add','edit','view','Manager Employee Requests'));

		 $acl->addResource(new Zend_Acl_Resource('default:employeeleavetypes'));
                            $acl->allow('management', 'default:employeeleavetypes', array('index','add','edit','delete','view','Leave Types'));

		 $acl->addResource(new Zend_Acl_Resource('default:employmentstatus'));
                            $acl->allow('management', 'default:employmentstatus', array('index','addpopup','add','edit','delete','view','Employment Status'));

		 $acl->addResource(new Zend_Acl_Resource('default:empscreening'));
                            $acl->allow('management', 'default:empscreening', array('index','getemployeedata','getagencylist','getpocdata','forcedfullupdate','checkscreeningstatus','uploadfeedback','download','deletefeedback','add','edit','delete','view','Employee/Candidate Screening'));

		 $acl->addResource(new Zend_Acl_Resource('default:ethniccode'));
                            $acl->allow('management', 'default:ethniccode', array('index','saveupdate','addpopup','add','edit','delete','view','Ethnic Codes'));

		 $acl->addResource(new Zend_Acl_Resource('default:feedforwardemployee'));
                            $acl->allow('management', 'default:feedforwardemployee', array('index','save','edit','view','Appraise Your Manager'));

		 $acl->addResource(new Zend_Acl_Resource('default:feedforwardinit'));
                            $acl->allow('management', 'default:feedforwardinit', array('index','getappraisaldetails','add','edit','view','Initialize Feedforward'));

		 $acl->addResource(new Zend_Acl_Resource('default:feedforwardmanager'));
                            $acl->allow('management', 'default:feedforwardmanager', array('index','getmanagersratings','getdetailedratings','getdetailedratingsbyemp','getdetailedratingsbyques','view','Manager Feedforward'));

		 $acl->addResource(new Zend_Acl_Resource('default:feedforwardquestions'));
                            $acl->allow('management', 'default:feedforwardquestions', array('index','savepopup','add','edit','delete','view','Questions'));

		 $acl->addResource(new Zend_Acl_Resource('default:feedforwardstatus'));
                            $acl->allow('management', 'default:feedforwardstatus', array('index','getffstatusemps','getfeedforwardstatus','view','Employee Status'));

		 $acl->addResource(new Zend_Acl_Resource('default:gender'));
                            $acl->allow('management', 'default:gender', array('index','saveupdate','addpopup','add','edit','delete','view','Gender'));

		 $acl->addResource(new Zend_Acl_Resource('default:geographygroup'));
                            $acl->allow('management', 'default:geographygroup', array('index','add','edit','delete','view','Geo Groups'));

		 $acl->addResource(new Zend_Acl_Resource('default:heirarchy'));
                            $acl->allow('management', 'default:heirarchy', array('index','addlist','editlist','saveadddata','saveeditdata','deletelist','Organization Hierarchy'));

		 $acl->addResource(new Zend_Acl_Resource('default:holidaydates'));
                            $acl->allow('management', 'default:holidaydates', array('index','addpopup','viewpopup','editpopup','add','edit','delete','view','Manage Holidays'));

		 $acl->addResource(new Zend_Acl_Resource('default:holidaygroups'));
                            $acl->allow('management', 'default:holidaygroups', array('index','getempnames','getholidaynames','addpopup','add','edit','delete','view','Manage Holiday Group'));

		 $acl->addResource(new Zend_Acl_Resource('default:identitycodes'));
                            $acl->allow('management', 'default:identitycodes', array('index','addpopup','edit','Identity Codes'));

		 $acl->addResource(new Zend_Acl_Resource('default:identitydocuments'));
                            $acl->allow('management', 'default:identitydocuments', array('index','add','edit','delete','view','Identity Documents'));

		 $acl->addResource(new Zend_Acl_Resource('default:index'));
                        $acl->allow('management', 'default:index', array('index','loginpopupsave','logout','clearsessionarray','forcelogout','browserfailure','sendpassword','updatecontactnumber','getstates','getstatesnormal','getcities','getcitiesnormal','getdepartments','getpositions','gettargetcurrency','calculatedays','calculatebusinessdays','calculatecalendardays','fromdatetodate','fromdatetodateorg','validateorgheadjoiningdate','medicalclaimdates','gettimeformat','chkcurrenttime','popup','createorremoveshortcut','sessiontour','getissuingauthority','setsessionval','checkisactivestatus','updatetheme','welcome','getmultidepts','getmultiemps','getpayrollcomponents','getpayrollattendance','getpayrollformulacomponents','getdownloadayslip','savepayrollcomponents','saveformulafields','getbunitdetails','getpayrollyeardetails','getpayrolldetails','generatepayroll','employeeleavesonleavetypes','generatepayslip','onboardingcandidates','getempsearch'));

		 $acl->addResource(new Zend_Acl_Resource('default:jobtitles'));
                            $acl->allow('management', 'default:jobtitles', array('index','addpopup','add','edit','delete','view','Job Titles'));

		 $acl->addResource(new Zend_Acl_Resource('default:language'));
                            $acl->allow('management', 'default:language', array('index','addpopup','add','edit','delete','view','Languages'));

		 $acl->addResource(new Zend_Acl_Resource('default:leavemanagement'));
                            $acl->allow('management', 'default:leavemanagement', array('index','add','edit','delete','view','Leave Management Options'));

		 $acl->addResource(new Zend_Acl_Resource('default:leaverequest'));
                            $leaverequest_add = 'yes';
                                if($this->id_param == '' && $leaverequest_add == 'yes')
                                    $acl->allow('management','default:leaverequest', array('index','saveleaverequestdetails','gethalfdaydetails','editpopup','updateleavedetails','add','Leave Request','edit'));

                                else
                                    $acl->allow('management','default:leaverequest', array('index','saveleaverequestdetails','gethalfdaydetails','editpopup','updateleavedetails','add','Leave Request'));

                                
		 $acl->addResource(new Zend_Acl_Resource('default:licensetype'));
                            $acl->allow('management', 'default:licensetype', array('index','add','edit','delete','view','License Types'));

		 $acl->addResource(new Zend_Acl_Resource('default:manageremployeevacations'));
                            $acl->allow('management', 'default:manageremployeevacations', array('index','edit','view','Manager Employee Leaves'));

		 $acl->addResource(new Zend_Acl_Resource('default:maritalstatus'));
                            $acl->allow('management', 'default:maritalstatus', array('index','saveupdate','addpopup','add','edit','delete','view','Marital Status'));

		 $acl->addResource(new Zend_Acl_Resource('default:militaryservice'));
                            $acl->allow('management', 'default:militaryservice', array('index','add','edit','delete','view','Military Service Types'));

		 $acl->addResource(new Zend_Acl_Resource('default:mydetails'));
                            $acl->allow('management', 'default:mydetails', array('index','personaldetailsview','personal','communicationdetailsview','communication','skills','education','experience','leaves','holidays','salarydetailsview','certification','creditcarddetailsview','creditcard','visadetailsview','visa','medicalclaims','disabilitydetailsview','disability','dependency','workeligibilitydetailsview','workeligibility','additionaldetailsedit','jobhistory','documents','assetdetailsview','add','edit','delete','view','My Details'));

		 $acl->addResource(new Zend_Acl_Resource('default:myemployees'));
                            $acl->allow('management', 'default:myemployees', array('index','perview','comview','skillsview','expview','eduview','trainingview','additionaldetailsview','jobhistoryview','skillsedit','jobhistoryedit','expedit','eduedit','trainingedit','additionaldetailsedit','peredit','comedit','docview','docedit','employeereport','getempreportdata','empauto','emprptpdf','exportemployeereport','downloadreport','view','My Team'));

		 $acl->addResource(new Zend_Acl_Resource('default:myholidaycalendar'));
                            $acl->allow('management', 'default:myholidaycalendar', array('index','view','My Holiday Calendar'));

		 $acl->addResource(new Zend_Acl_Resource('default:myteamappraisal'));
                            $acl->allow('management', 'default:myteamappraisal', array('savelineresponse','savemngresponse','getempcontent','index','getsearchedempcontent','getsearchedstatus','downloadpdf','downloadUploadedFile','My Team Appraisal'));

		 $acl->addResource(new Zend_Acl_Resource('default:nationality'));
                            $acl->allow('management', 'default:nationality', array('index','addpopup','add','edit','delete','view','Nationalities'));

		 $acl->addResource(new Zend_Acl_Resource('default:nationalitycontextcode'));
                            $acl->allow('management', 'default:nationalitycontextcode', array('index','add','edit','delete','view','Nationality Context Codes'));

		 $acl->addResource(new Zend_Acl_Resource('default:numberformats'));
                            $acl->allow('management', 'default:numberformats', array('index','add','edit','delete','view','Number Formats'));

		 $acl->addResource(new Zend_Acl_Resource('default:onboardingcandidates'));
                            $acl->allow('management', 'default:onboardingcandidates', array('index','add','edit','view','On Boarding Candidates'));

		 $acl->addResource(new Zend_Acl_Resource('default:organisationinfo'));
                            $acl->allow('management', 'default:organisationinfo', array('index','edit_old','saveupdate','uploadpreview','validateorgstartdate','getcompleteorgdata','addorghead','edit','view','Organization Info'));

		 $acl->addResource(new Zend_Acl_Resource('default:payfrequency'));
                            $acl->allow('management', 'default:payfrequency', array('index','addpopup','add','edit','delete','view','Pay Frequency'));

		 $acl->addResource(new Zend_Acl_Resource('default:payrollattendancereport'));
                            $acl->allow('management', 'default:payrollattendancereport', array('index','addpopup','getrequests','view','Attendance Report'));

		 $acl->addResource(new Zend_Acl_Resource('default:payrollattendancerequest'));
                            $acl->allow('management', 'default:payrollattendancerequest', array('index','addpopup','getrequests','add','edit','view','Attendance Request'));

		 $acl->addResource(new Zend_Acl_Resource('default:pendingleaves'));
                            $acl->allow('management', 'default:pendingleaves', array('index','delete','view','My Leaves'));

		 $acl->addResource(new Zend_Acl_Resource('default:policydocuments'));
                            $acl->allow('management', 'default:policydocuments', array('index','uploaddoc','deletedocument','addmultiple','uploadmultipledocs','add','edit','delete','view','View/Manage Policy Documents'));

		 $acl->addResource(new Zend_Acl_Resource('default:positions'));
                            $acl->allow('management', 'default:positions', array('index','addpopup','add','edit','delete','view','Positions'));

		 $acl->addResource(new Zend_Acl_Resource('default:prefix'));
                            $acl->allow('management', 'default:prefix', array('index','saveupdate','addpopup','add','edit','delete','view','Prefixes'));

		 $acl->addResource(new Zend_Acl_Resource('default:racecode'));
                            $acl->allow('management', 'default:racecode', array('index','saveupdate','addpopup','add','edit','delete','view','Race Codes'));

		 $acl->addResource(new Zend_Acl_Resource('default:rejectedrequisitions'));
                            $acl->allow('management', 'default:rejectedrequisitions', array('index','view','Rejected Requisitions'));

		 $acl->addResource(new Zend_Acl_Resource('default:remunerationbasis'));
                            $acl->allow('management', 'default:remunerationbasis', array('index','add','edit','delete','view','Remuneration Basis'));

		 $acl->addResource(new Zend_Acl_Resource('default:reports'));
                            $acl->allow('management', 'default:reports', array('getrolepopup','emprolesgrouppopup','performancereport','previousappraisals','getselectedappraisaldata','getinterviewroundsdata','interviewrounds','rolesgroup','exportemprolesgroup','exportrolesgroupreport','exportinterviewrpt','exportactiveuserrpt','exportpayrollrpt','exportemployeereport','rolesgrouprptpdf','activeuserrptpdf','emprptpdf','interviewrptpdf','rolesgroupdata','emprolesgroup','emprolesgroupdata','activeuser','getactiveuserdata','getattendancereportsdata','getpayrollreportsdata','getempreportdata','empauto','servicedeskreport','getsddata','servicedeskpdf','servicedeskexcel','employeereport','getdeptsemp','index','holidaygroupreports','getpdfreportholiday','getexcelreportholiday','payrollreport','attendancereport','leavesreport','getpdfreportleaves','getexcelreportleaves','leavesreporttabheader','leavemanagementreport','getpdfreportleavemanagement','getexcelreportleavemanagement','bunitauto','bunitcodeauto','getexcelreportbusinessunit','getbusinessunitspdf','businessunits','userlogreport','departments','exportdepartmentpdf','getexcelreportdepartment','candidaterptexcel','candidaterptpdf','getcandidatesreportdata','candidatesreport','requisitionauto','requisitionrptexcel','requisitionrptpdf','getrequisitionsstatusreportdata','requisitionstatusreport','activitylogreport','downloadreport','agencylistreport','agencynameauto','agencysebsiteauto','empscreening','getspecimennames','getagencynames','getexcelreportempscreening','getempscreeningpdf','Analytics'));

		 $acl->addResource(new Zend_Acl_Resource('default:requisition'));
                            $acl->allow('management', 'default:requisition', array('index','viewhr','approverequisition','addcandidate','interview','getdepartments','getpositions','viewpopup','getapprreqdata','chkreqforclose','getempreportingmanagers','getemailcount','getapprovers','add','edit','delete','view','Openings/Positions'));

		 $acl->addResource(new Zend_Acl_Resource('default:roles'));
                            $acl->allow('management', 'default:roles', array('index','saveupdate','getgroupmenu','add','edit','delete','view','Roles & Privileges'));

		 $acl->addResource(new Zend_Acl_Resource('default:scheduleinterviews'));
                            $acl->allow('management', 'default:scheduleinterviews', array('candidatepopup','index','downloadresume','getcandidates','add','edit','delete','view','Scheduled Interviews'));

		 $acl->addResource(new Zend_Acl_Resource('default:servicedeskconf'));
                            $acl->allow('management', 'default:servicedeskconf', array('index','getemployees','getapprover','getbunitimplementation','getassets','add','edit','delete','view','Settings'));

		 $acl->addResource(new Zend_Acl_Resource('default:servicedeskdepartment'));
                            $acl->allow('management', 'default:servicedeskdepartment', array('index','addpopup','getrequests','add','edit','delete','view','Categories'));

		 $acl->addResource(new Zend_Acl_Resource('default:servicedeskrequest'));
                            $acl->allow('management', 'default:servicedeskrequest', array('index','add','edit','delete','view','Request Types'));

		 $acl->addResource(new Zend_Acl_Resource('default:servicerequests'));
                            $servicerequests_add = 'yes';
                                if($this->id_param == '' && $servicerequests_add == 'yes')
                                    $acl->allow('management','default:servicerequests', array('index','uploadsave','uploaddelete','view','getrequests','changestatus','checkrequeststatus','getuserassets','index','add','uploadsave','uploaddelete','view','getrequests','changestatus','checkrequeststatus','getuserassets','edit'));

                                else
                                    $acl->allow('management','default:servicerequests', array('index','uploadsave','uploaddelete','view','getrequests','changestatus','checkrequeststatus','getuserassets','index','add','uploadsave','uploaddelete','view','getrequests','changestatus','checkrequeststatus','getuserassets'));

                                
		 $acl->addResource(new Zend_Acl_Resource('default:shortlistedcandidates'));
                            $acl->allow('management', 'default:shortlistedcandidates', array('index','edit','view','Shortlisted & Selected Candidates'));

		 $acl->addResource(new Zend_Acl_Resource('default:sitepreference'));
                            $acl->allow('management', 'default:sitepreference', array('index','view','add','edit','Site Preferences'));

		 $acl->addResource(new Zend_Acl_Resource('default:states'));
                            $states_add = 'yes';
                                if($this->id_param == '' && $states_add == 'yes')
                                    $acl->allow('management','default:states', array('index','getstates','getstatescand','addpopup','addnewstate','add','delete','view','States','edit'));

                                else
                                    $acl->allow('management','default:states', array('index','getstates','getstatescand','addpopup','addnewstate','add','delete','view','States'));

                                
		 $acl->addResource(new Zend_Acl_Resource('default:structure'));
                            $acl->allow('management', 'default:structure', array('index','Organization Structure'));

		 $acl->addResource(new Zend_Acl_Resource('default:timezone'));
                            $acl->allow('management', 'default:timezone', array('index','saveupdate','addpopup','add','edit','delete','view','Time Zones'));

		 $acl->addResource(new Zend_Acl_Resource('default:usermanagement'));
                            $acl->allow('management', 'default:usermanagement', array('index','saveupdate','getemailofuser','add','edit','view','Manage External Users'));

		 $acl->addResource(new Zend_Acl_Resource('default:veteranstatus'));
                            $acl->allow('management', 'default:veteranstatus', array('index','add','edit','delete','view','Veteran Status'));

		 $acl->addResource(new Zend_Acl_Resource('default:workeligibilitydoctypes'));
                            $acl->allow('management', 'default:workeligibilitydoctypes', array('index','addpopup','add','edit','delete','view','Work Eligibility Document Types'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:advances'));
                            $acl->allow('management', 'expenses:advances', array('index','getprojects','myadvances','viewmoreadvances','clearadvancesdata','addreturnpopup','add','edit','delete','view','Advances'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:employeeadvances'));
                            $acl->allow('management', 'expenses:employeeadvances', array('index','add','edit','delete','view','Employee Advances'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:expenses'));
                            $acl->allow('management', 'expenses:expenses', array('index','clone','addpopup','uploadsave','uploaddelete','displayreceipts','addtrippopup','submitexpense','addreceiptimage','expensestatus','listreportingmangers','viewmoremanagers','forwardexpenseto','downloadexpensepdf','bulkexpenses','getcategories','getprojects','getcurrency','uploadedfiles','add','edit','delete','view','Expenses'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:myemployeeexpenses'));
                            $acl->allow('management', 'expenses:myemployeeexpenses', array('index','add','edit','delete','view','My Employee Expenses'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:receipts'));
                            $acl->allow('management', 'expenses:receipts', array('index','downloadreceipt','downloadexpensereceipt','deletereceipt','uploadsave','displayreceipts','viewmorereceipts','listexpenses','addreceipttoexpense','viewmoreexpenses','cleardata','showreceiptspopup','listtrips','viewmoretrips','addexpensetotrip','add','edit','delete','view','Receipts'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:trips'));
                            $acl->allow('management', 'expenses:trips', array('index','addpopup','tripstatus','deleteexpense','downloadtrippdf','add','edit','delete','view','Trips'));

		 $acl->addResource(new Zend_Acl_Resource('default:processes'));
                            $acl->allow('management', 'default:processes', array('index','addpopup','editpopup','viewpopup','savecomments','displaycomments','savefeedback','index','addpopup','editpopup','viewpopup','delete','savecomments','displaycomments','savefeedback'));

		 $acl->addResource(new Zend_Acl_Resource('default:interviewrounds'));
                            $acl->allow('management', 'default:interviewrounds', array('index','addpopup','editpopup','viewpopup','index','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:empperformanceappraisal'));
                            $acl->allow('management', 'default:empperformanceappraisal', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:emppayslips'));
                            $acl->allow('management', 'default:emppayslips', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empbenefits'));
                            $acl->allow('management', 'default:empbenefits', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:emprequisitiondetails'));
                            $acl->allow('management', 'default:emprequisitiondetails', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empremunerationdetails'));
                            $acl->allow('management', 'default:empremunerationdetails', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empsecuritycredentials'));
                            $acl->allow('management', 'default:empsecuritycredentials', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:apprreqcandidates'));
                            $acl->allow('management', 'default:apprreqcandidates', array('index','viewpopup','index','viewpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:emppersonaldetails'));
                            $acl->allow('management', 'default:emppersonaldetails', array('index','view','index','view','edit'));

		 $acl->addResource(new Zend_Acl_Resource('default:employeedocs'));
                            $acl->allow('management', 'default:employeedocs', array('index','view','save','update','uploadsave','uploaddelete','downloadfiles','index','view','save','delete','edit','update','uploadsave','uploaddelete','downloadfiles'));

		 $acl->addResource(new Zend_Acl_Resource('default:empcommunicationdetails'));
                            $acl->allow('management', 'default:empcommunicationdetails', array('index','view','index','view','edit'));

		 $acl->addResource(new Zend_Acl_Resource('default:trainingandcertificationdetails'));
                            $acl->allow('management', 'default:trainingandcertificationdetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:experiencedetails'));
                            $acl->allow('management', 'default:experiencedetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:educationdetails'));
                            $acl->allow('management', 'default:educationdetails', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:medicalclaims'));
                            $acl->allow('management', 'default:medicalclaims', array('index','addpopup','viewpopup','editpopup','view','index','edit','addpopup','viewpopup','editpopup','delete','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empleaves'));
                            $acl->allow('management', 'default:empleaves', array('index','view','viewpopup','index','edit','view','viewpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:empskills'));
                            $acl->allow('management', 'default:empskills', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:disabilitydetails'));
                            $acl->allow('management', 'default:disabilitydetails', array('index','view','index','add','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:workeligibilitydetails'));
                            $acl->allow('management', 'default:workeligibilitydetails', array('index','view','index','add','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empadditionaldetails'));
                            $acl->allow('management', 'default:empadditionaldetails', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:visaandimmigrationdetails'));
                            $acl->allow('management', 'default:visaandimmigrationdetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:creditcarddetails'));
                            $acl->allow('management', 'default:creditcarddetails', array('index','view','index','add','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:dependencydetails'));
                            $acl->allow('management', 'default:dependencydetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:empholidays'));
                            $acl->allow('management', 'default:empholidays', array('index','view','viewpopup','index','edit','view','viewpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:empjobhistory'));
                            $acl->allow('management', 'default:empjobhistory', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:assetdetails'));
                            $acl->allow('management', 'default:assetdetails', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empsalarydetails'));
                            $acl->allow('management', 'default:empsalarydetails', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:logmanager'));
                            $acl->allow('management', 'default:logmanager', array('index','view','empnamewithidauto','index','view','empnamewithidauto'));

		 $acl->addResource(new Zend_Acl_Resource('default:userloginlog'));
                            $acl->allow('management', 'default:userloginlog', array('index','empnameauto','empidauto','empipaddressauto','empemailauto','index','empnameauto','empidauto','empipaddressauto','empemailauto'));
}if($role == 3 )
           {
		 $acl->addResource(new Zend_Acl_Resource('default:announcements'));
                            $acl->allow('manager', 'default:announcements', array('index','getdepts','uploadsave','uploaddelete','view','Announcements'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalcategory'));
                            $acl->allow('manager', 'default:appraisalcategory', array('index','addpopup','getappraisalcategory','view','Parameters'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalhistoryself'));
                            $acl->allow('manager', 'default:appraisalhistoryself', array('index','view','My Appraisal History'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalhistoryteam'));
                            $acl->allow('manager', 'default:appraisalhistoryteam', array('index','getsearchedempcontent','view','Team Appraisal History'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalmanager'));
                            $acl->allow('manager', 'default:appraisalmanager', array('submitmanager','deletemanagergroup','savemanagergroup','index','viewgroup','createnewgroup','showgroups','showviewgroups','edit','view','Manager Appraisal'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalquestions'));
                            $acl->allow('manager', 'default:appraisalquestions', array('index','addpopup','savequestionpopup','add','edit','delete','view','Questions'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalself'));
                            $acl->allow('manager', 'default:appraisalself', array('index','save','edit','view','Self Appraisal'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalskills'));
                            $appraisalskills_add = 'yes';
                                if($this->id_param == '' && $appraisalskills_add == 'yes')
                                    $acl->allow('manager','default:appraisalskills', array('index','getappraisalskills','saveskillspopup','add','view','Skills','edit'));

                                else
                                    $acl->allow('manager','default:appraisalskills', array('index','getappraisalskills','saveskillspopup','add','view','Skills'));

                                
		 $acl->addResource(new Zend_Acl_Resource('default:approvedrequisitions'));
                            $acl->allow('manager', 'default:approvedrequisitions', array('index','view','Approved Requisitions'));

		 $acl->addResource(new Zend_Acl_Resource('default:businessunits'));
                            $acl->allow('manager', 'default:businessunits', array('index','getdeptnames','view','Business Units'));

		 $acl->addResource(new Zend_Acl_Resource('default:candidatedetails'));
                            $acl->allow('manager', 'default:candidatedetails', array('index','chkcandidate','uploadfile','deleteresume','download','multipleresume','view','CV Management'));

		 $acl->addResource(new Zend_Acl_Resource('default:dashboard'));
                        $acl->allow('manager', 'default:dashboard', array('index','saveuserdashboard','getwidgtes','upgradeapplication','emailsettings','changepassword','editpassword','update','uploadpreview','viewprofile','viewsettings','savemenuwidgets','getmenuname','fetchmenuname','getnavids','getopeningpositondate','menuwork'));

		 $acl->addResource(new Zend_Acl_Resource('default:departments'));
                            $acl->allow('manager', 'default:departments', array('index','viewpopup','editpopup','getdepartments','getempnames','view','Departments'));

		 $acl->addResource(new Zend_Acl_Resource('default:employee'));
                            $acl->allow('manager', 'default:employee', array('getemprequi','index','changeorghead','getdepartments','getpositions','getempreportingmanagers','makeactiveinactive','changereportingmanager','addemppopup','uploadexcel','getindividualempdetails','view','Employees'));

		 $acl->addResource(new Zend_Acl_Resource('default:employeeattendanceapprovals'));
                            $acl->allow('manager', 'default:employeeattendanceapprovals', array('index','view','Manager Employee Requests'));

		 $acl->addResource(new Zend_Acl_Resource('default:feedforwardemployee'));
                            $acl->allow('manager', 'default:feedforwardemployee', array('index','save','edit','view','Appraise Your Manager'));

		 $acl->addResource(new Zend_Acl_Resource('default:index'));
                        $acl->allow('manager', 'default:index', array('index','loginpopupsave','logout','clearsessionarray','forcelogout','browserfailure','sendpassword','updatecontactnumber','getstates','getstatesnormal','getcities','getcitiesnormal','getdepartments','getpositions','gettargetcurrency','calculatedays','calculatebusinessdays','calculatecalendardays','fromdatetodate','fromdatetodateorg','validateorgheadjoiningdate','medicalclaimdates','gettimeformat','chkcurrenttime','popup','createorremoveshortcut','sessiontour','getissuingauthority','setsessionval','checkisactivestatus','updatetheme','welcome','getmultidepts','getmultiemps','getpayrollcomponents','getpayrollattendance','getpayrollformulacomponents','getdownloadayslip','savepayrollcomponents','saveformulafields','getbunitdetails','getpayrollyeardetails','getpayrolldetails','generatepayroll','employeeleavesonleavetypes','generatepayslip','onboardingcandidates','getempsearch'));

		 $acl->addResource(new Zend_Acl_Resource('default:leaverequest'));
                            $leaverequest_add = 'yes';
                                if($this->id_param == '' && $leaverequest_add == 'yes')
                                    $acl->allow('manager','default:leaverequest', array('index','saveleaverequestdetails','gethalfdaydetails','editpopup','updateleavedetails','add','Leave Request','edit'));

                                else
                                    $acl->allow('manager','default:leaverequest', array('index','saveleaverequestdetails','gethalfdaydetails','editpopup','updateleavedetails','add','Leave Request'));

                                
		 $acl->addResource(new Zend_Acl_Resource('default:manageremployeevacations'));
                            $acl->allow('manager', 'default:manageremployeevacations', array('index','edit','view','Manager Employee Leaves'));

		 $acl->addResource(new Zend_Acl_Resource('default:mydetails'));
                            $acl->allow('manager', 'default:mydetails', array('index','personaldetailsview','personal','communicationdetailsview','communication','skills','education','experience','leaves','holidays','salarydetailsview','certification','creditcarddetailsview','creditcard','visadetailsview','visa','medicalclaims','disabilitydetailsview','disability','dependency','workeligibilitydetailsview','workeligibility','additionaldetailsedit','jobhistory','documents','assetdetailsview','add','edit','delete','view','My Details'));

		 $acl->addResource(new Zend_Acl_Resource('default:myemployees'));
                            $acl->allow('manager', 'default:myemployees', array('index','perview','comview','skillsview','expview','eduview','trainingview','additionaldetailsview','jobhistoryview','skillsedit','jobhistoryedit','expedit','eduedit','trainingedit','additionaldetailsedit','peredit','comedit','docview','docedit','employeereport','getempreportdata','empauto','emprptpdf','exportemployeereport','downloadreport','add','edit','view','My Team'));

		 $acl->addResource(new Zend_Acl_Resource('default:myholidaycalendar'));
                            $acl->allow('manager', 'default:myholidaycalendar', array('index','view','My Holiday Calendar'));

		 $acl->addResource(new Zend_Acl_Resource('default:myteamappraisal'));
                            $acl->allow('manager', 'default:myteamappraisal', array('savelineresponse','savemngresponse','getempcontent','index','getsearchedempcontent','getsearchedstatus','downloadpdf','downloadUploadedFile','My Team Appraisal'));

		 $acl->addResource(new Zend_Acl_Resource('default:onboardingcandidates'));
                            $acl->allow('manager', 'default:onboardingcandidates', array('index','view','On Boarding Candidates'));

		 $acl->addResource(new Zend_Acl_Resource('default:organisationinfo'));
                            $acl->allow('manager', 'default:organisationinfo', array('index','edit_old','saveupdate','uploadpreview','validateorgstartdate','getcompleteorgdata','addorghead','Organization Info'));

		 $acl->addResource(new Zend_Acl_Resource('default:pendingleaves'));
                            $acl->allow('manager', 'default:pendingleaves', array('index','delete','view','My Leaves'));

		 $acl->addResource(new Zend_Acl_Resource('default:policydocuments'));
                            $acl->allow('manager', 'default:policydocuments', array('index','uploaddoc','deletedocument','addmultiple','uploadmultipledocs','view','View/Manage Policy Documents'));

		 $acl->addResource(new Zend_Acl_Resource('default:rejectedrequisitions'));
                            $acl->allow('manager', 'default:rejectedrequisitions', array('index','view','Rejected Requisitions'));

		 $acl->addResource(new Zend_Acl_Resource('default:requisition'));
                            $acl->allow('manager', 'default:requisition', array('index','viewhr','approverequisition','addcandidate','interview','getdepartments','getpositions','viewpopup','getapprreqdata','chkreqforclose','getempreportingmanagers','getemailcount','getapprovers','add','edit','delete','view','Openings/Positions'));

		 $acl->addResource(new Zend_Acl_Resource('default:scheduleinterviews'));
                            $acl->allow('manager', 'default:scheduleinterviews', array('candidatepopup','index','downloadresume','getcandidates','edit','view','Scheduled Interviews'));

		 $acl->addResource(new Zend_Acl_Resource('default:servicerequests'));
                            $servicerequests_add = 'yes';
                                if($this->id_param == '' && $servicerequests_add == 'yes')
                                    $acl->allow('manager','default:servicerequests', array('index','uploadsave','uploaddelete','view','getrequests','changestatus','checkrequeststatus','getuserassets','index','add','uploadsave','uploaddelete','view','getrequests','changestatus','checkrequeststatus','getuserassets','edit'));

                                else
                                    $acl->allow('manager','default:servicerequests', array('index','uploadsave','uploaddelete','view','getrequests','changestatus','checkrequeststatus','getuserassets','index','add','uploadsave','uploaddelete','view','getrequests','changestatus','checkrequeststatus','getuserassets'));

                                
		 $acl->addResource(new Zend_Acl_Resource('default:shortlistedcandidates'));
                            $acl->allow('manager', 'default:shortlistedcandidates', array('index','view','Shortlisted & Selected Candidates'));

		 $acl->addResource(new Zend_Acl_Resource('default:structure'));
                            $acl->allow('manager', 'default:structure', array('index','Organization Structure'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:advances'));
                            $acl->allow('manager', 'expenses:advances', array('index','getprojects','myadvances','viewmoreadvances','clearadvancesdata','addreturnpopup','add','edit','delete','view','Advances'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:employeeadvances'));
                            $acl->allow('manager', 'expenses:employeeadvances', array('index','add','edit','delete','view','Employee Advances'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:expenses'));
                            $acl->allow('manager', 'expenses:expenses', array('index','clone','addpopup','uploadsave','uploaddelete','displayreceipts','addtrippopup','submitexpense','addreceiptimage','expensestatus','listreportingmangers','viewmoremanagers','forwardexpenseto','downloadexpensepdf','bulkexpenses','getcategories','getprojects','getcurrency','uploadedfiles','add','edit','delete','view','Expenses'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:myemployeeexpenses'));
                            $acl->allow('manager', 'expenses:myemployeeexpenses', array('index','add','edit','delete','view','My Employee Expenses'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:receipts'));
                            $acl->allow('manager', 'expenses:receipts', array('index','downloadreceipt','downloadexpensereceipt','deletereceipt','uploadsave','displayreceipts','viewmorereceipts','listexpenses','addreceipttoexpense','viewmoreexpenses','cleardata','showreceiptspopup','listtrips','viewmoretrips','addexpensetotrip','add','edit','delete','view','Receipts'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:trips'));
                            $acl->allow('manager', 'expenses:trips', array('index','addpopup','tripstatus','deleteexpense','downloadtrippdf','add','edit','delete','view','Trips'));

		 $acl->addResource(new Zend_Acl_Resource('default:emppersonaldetails'));
                            $acl->allow('manager', 'default:emppersonaldetails', array('index','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:employeedocs'));
                            $acl->allow('manager', 'default:employeedocs', array('index','view','save','update','uploadsave','uploaddelete','downloadfiles','index','view','save','delete','edit','update','uploadsave','uploaddelete','downloadfiles'));

		 $acl->addResource(new Zend_Acl_Resource('default:empcommunicationdetails'));
                            $acl->allow('manager', 'default:empcommunicationdetails', array('index','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:trainingandcertificationdetails'));
                            $acl->allow('manager', 'default:trainingandcertificationdetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:experiencedetails'));
                            $acl->allow('manager', 'default:experiencedetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:educationdetails'));
                            $acl->allow('manager', 'default:educationdetails', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:medicalclaims'));
                            $acl->allow('manager', 'default:medicalclaims', array('index','addpopup','viewpopup','editpopup','view','index','edit','addpopup','viewpopup','editpopup','delete','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empleaves'));
                            $acl->allow('manager', 'default:empleaves', array('index','view','viewpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:empskills'));
                            $acl->allow('manager', 'default:empskills', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:disabilitydetails'));
                            $acl->allow('manager', 'default:disabilitydetails', array('index','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:workeligibilitydetails'));
                            $acl->allow('manager', 'default:workeligibilitydetails', array('index','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:visaandimmigrationdetails'));
                            $acl->allow('manager', 'default:visaandimmigrationdetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:creditcarddetails'));
                            $acl->allow('manager', 'default:creditcarddetails', array('index','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:dependencydetails'));
                            $acl->allow('manager', 'default:dependencydetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:empholidays'));
                            $acl->allow('manager', 'default:empholidays', array('index','view','viewpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:empjobhistory'));
                            $acl->allow('manager', 'default:empjobhistory', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:empadditionaldetails'));
                            $acl->allow('manager', 'default:empadditionaldetails', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:assetdetails'));
                            $acl->allow('manager', 'default:assetdetails', array('index','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:interviewrounds'));
                            $acl->allow('manager', 'default:interviewrounds', array('index','addpopup','editpopup','viewpopup','index','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:apprreqcandidates'));
                            $acl->allow('manager', 'default:apprreqcandidates', array('index','viewpopup'));
}if($role == 4 )
           {
		 $acl->addResource(new Zend_Acl_Resource('default:addemployeeleaves'));
                            $acl->allow('HRManager', 'default:addemployeeleaves', array('index','add','edit','view','Add Employee Leaves'));

		 $acl->addResource(new Zend_Acl_Resource('default:agencylist'));
                            $acl->allow('HRManager', 'default:agencylist', array('index','deletepoc','add','edit','delete','view','Agencies'));

		 $acl->addResource(new Zend_Acl_Resource('default:announcements'));
                            $acl->allow('HRManager', 'default:announcements', array('index','getdepts','uploadsave','uploaddelete','add','edit','delete','view','Announcements'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalcategory'));
                            $acl->allow('HRManager', 'default:appraisalcategory', array('index','addpopup','getappraisalcategory','add','edit','delete','view','Parameters'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalhistoryself'));
                            $acl->allow('HRManager', 'default:appraisalhistoryself', array('index','view','My Appraisal History'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalhistoryteam'));
                            $acl->allow('HRManager', 'default:appraisalhistoryteam', array('index','getsearchedempcontent','view','Team Appraisal History'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalinit'));
                            $acl->allow('HRManager', 'default:appraisalinit', array('checkappadmin','getdepartmentsadmin','discardsteptwo','displayline','addlinemanager','displayreport','deletelinemanager','deletereportmanager','constructreportacc','constructacc','displayemployees','displaycontentreportacc','displaycontentacc','viewconfmanagers','confmanagers','displaymanagers','displayreportmanagers','getperiod','index','viewassigngroups','assigngroups','displaygroupedemployees','showgroupedemployees','viewgroupedemployees','savegroupedemployeesajax','changesettings','displaysettings','deletegroupedemployees','initializegroup','completeappraisal','checkemployeeresponse','getemployeeslinemanagers','savemngrorghierarchy','getconfiglinemanagers','validateconfig','add','edit','view','Initialize Appraisal'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalmanager'));
                            $acl->allow('HRManager', 'default:appraisalmanager', array('submitmanager','deletemanagergroup','savemanagergroup','index','viewgroup','createnewgroup','showgroups','showviewgroups','edit','view','Manager Appraisal'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalquestions'));
                            $acl->allow('HRManager', 'default:appraisalquestions', array('index','addpopup','savequestionpopup','add','edit','delete','view','Questions'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalratings'));
                            $acl->allow('HRManager', 'default:appraisalratings', array('index','addratings','add','edit','view','Ratings'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalself'));
                            $acl->allow('HRManager', 'default:appraisalself', array('index','save','edit','view','Self Appraisal'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalskills'));
                            $appraisalskills_add = 'yes';
                                if($this->id_param == '' && $appraisalskills_add == 'yes')
                                    $acl->allow('HRManager','default:appraisalskills', array('index','getappraisalskills','saveskillspopup','add','view','Skills','edit'));

                                else
                                    $acl->allow('HRManager','default:appraisalskills', array('index','getappraisalskills','saveskillspopup','add','view','Skills'));

                                
		 $acl->addResource(new Zend_Acl_Resource('default:appraisalstatus'));
                            $acl->allow('HRManager', 'default:appraisalstatus', array('index','manager','managerstatus','checkappraisalimplementation','employee','employeestatus','employeeActi','addlinemanager','displaymanagers','updatelinemanager','view','Employee Status'));

		 $acl->addResource(new Zend_Acl_Resource('default:approvedrequisitions'));
                            $acl->allow('HRManager', 'default:approvedrequisitions', array('index','edit','view','Approved Requisitions'));

		 $acl->addResource(new Zend_Acl_Resource('default:attendancestatuscode'));
                            $acl->allow('HRManager', 'default:attendancestatuscode', array('index','add','edit','view','Attendance Status'));

		 $acl->addResource(new Zend_Acl_Resource('default:bankaccounttype'));
                            $acl->allow('HRManager', 'default:bankaccounttype', array('index','addpopup','add','edit','view','Bank Account Types'));

		 $acl->addResource(new Zend_Acl_Resource('default:bgscreeningtype'));
                            $acl->allow('HRManager', 'default:bgscreeningtype', array('index','addpopup','add','edit','delete','view','Screening Types'));

		 $acl->addResource(new Zend_Acl_Resource('default:businessunits'));
                            $acl->allow('HRManager', 'default:businessunits', array('index','getdeptnames','add','edit','delete','view','Business Units'));

		 $acl->addResource(new Zend_Acl_Resource('default:candidatedetails'));
                            $acl->allow('HRManager', 'default:candidatedetails', array('index','chkcandidate','uploadfile','deleteresume','download','multipleresume','add','edit','view','CV Management'));

		 $acl->addResource(new Zend_Acl_Resource('default:categories'));
                            $acl->allow('HRManager', 'default:categories', array('index','addnewcategory','add','edit','delete','view','Categories'));

		 $acl->addResource(new Zend_Acl_Resource('default:competencylevel'));
                            $acl->allow('HRManager', 'default:competencylevel', array('index','addpopup','add','edit','view','Competency Levels'));

		 $acl->addResource(new Zend_Acl_Resource('default:components'));
                            $acl->allow('HRManager', 'default:components', array('index','addpopup','getrequests','add','edit','delete','view','Components'));

		 $acl->addResource(new Zend_Acl_Resource('default:componentsgroup'));
                            $acl->allow('HRManager', 'default:componentsgroup', array('index','addpopup','getrequests','add','edit','delete','view','Components Group'));

		 $acl->addResource(new Zend_Acl_Resource('default:dashboard'));
                        $acl->allow('HRManager', 'default:dashboard', array('index','saveuserdashboard','getwidgtes','upgradeapplication','emailsettings','changepassword','editpassword','update','uploadpreview','viewprofile','viewsettings','savemenuwidgets','getmenuname','fetchmenuname','getnavids','getopeningpositondate','menuwork'));

		 $acl->addResource(new Zend_Acl_Resource('default:departments'));
                            $acl->allow('HRManager', 'default:departments', array('index','viewpopup','editpopup','getdepartments','getempnames','add','edit','delete','view','Departments'));

		 $acl->addResource(new Zend_Acl_Resource('default:downloadpayslip'));
                            $acl->allow('HRManager', 'default:downloadpayslip', array('index','addpopup','getrequests','add','edit','delete','view','Download Payslip'));

		 $acl->addResource(new Zend_Acl_Resource('default:educationlevelcode'));
                            $acl->allow('HRManager', 'default:educationlevelcode', array('index','add','edit','view','Education Levels'));

		 $acl->addResource(new Zend_Acl_Resource('default:eeoccategory'));
                            $acl->allow('HRManager', 'default:eeoccategory', array('index','add','edit','view','EEOC Categories'));

		 $acl->addResource(new Zend_Acl_Resource('default:empconfiguration'));
                            $acl->allow('HRManager', 'default:empconfiguration', array('index','edit','Employee Tabs'));

		 $acl->addResource(new Zend_Acl_Resource('default:empleavesummary'));
                            $acl->allow('HRManager', 'default:empleavesummary', array('index','statusid','view','Employee Leaves Summary'));

		 $acl->addResource(new Zend_Acl_Resource('default:employee'));
                            $acl->allow('HRManager', 'default:employee', array('getemprequi','index','changeorghead','getdepartments','getpositions','getempreportingmanagers','makeactiveinactive','changereportingmanager','addemppopup','uploadexcel','getindividualempdetails','add','edit','view','Employees'));

		 $acl->addResource(new Zend_Acl_Resource('default:employeecomponents'));
                            $acl->allow('HRManager', 'default:employeecomponents', array('index','addpopup','getrequests','add','edit','delete','view','Employee Components'));

		 $acl->addResource(new Zend_Acl_Resource('default:employeeleavetypes'));
                            $acl->allow('HRManager', 'default:employeeleavetypes', array('index','add','edit','view','Leave Types'));

		 $acl->addResource(new Zend_Acl_Resource('default:employmentstatus'));
                            $acl->allow('HRManager', 'default:employmentstatus', array('index','addpopup','add','edit','view','Employment Status'));

		 $acl->addResource(new Zend_Acl_Resource('default:empscreening'));
                            $acl->allow('HRManager', 'default:empscreening', array('index','getemployeedata','getagencylist','getpocdata','forcedfullupdate','checkscreeningstatus','uploadfeedback','download','deletefeedback','add','edit','view','Employee/Candidate Screening'));

		 $acl->addResource(new Zend_Acl_Resource('default:feedforwardemployee'));
                            $acl->allow('HRManager', 'default:feedforwardemployee', array('index','save','edit','view','Appraise Your Manager'));

		 $acl->addResource(new Zend_Acl_Resource('default:formulafields'));
                            $acl->allow('HRManager', 'default:formulafields', array('index','addpopup','getrequests','add','edit','delete','view','Formula Fields'));

		 $acl->addResource(new Zend_Acl_Resource('default:generatepayroll'));
                            $acl->allow('HRManager', 'default:generatepayroll', array('index','addpopup','getrequests','add','edit','delete','view','Generate Payroll'));

		 $acl->addResource(new Zend_Acl_Resource('default:heirarchy'));
                            $acl->allow('HRManager', 'default:heirarchy', array('index','addlist','editlist','saveadddata','saveeditdata','deletelist','Organization Hierarchy'));

		 $acl->addResource(new Zend_Acl_Resource('default:holidaydates'));
                            $acl->allow('HRManager', 'default:holidaydates', array('index','addpopup','viewpopup','editpopup','add','edit','view','Manage Holidays'));

		 $acl->addResource(new Zend_Acl_Resource('default:holidaygroups'));
                            $acl->allow('HRManager', 'default:holidaygroups', array('index','getempnames','getholidaynames','addpopup','add','edit','view','Manage Holiday Group'));

		 $acl->addResource(new Zend_Acl_Resource('default:hrwizard'));
                            $acl->allow('HRManager', 'default:hrwizard', array('index','configureleavetypes','configureholidays','saveholidaygroup','configureperformanceappraisal','savecategory','updatewizardcompletion','index','configureleavetypes','configureholidays','saveholidaygroup','configureperformanceappraisal','savecategory','updatewizardcompletion'));

		 $acl->addResource(new Zend_Acl_Resource('default:identitydocuments'));
                            $acl->allow('HRManager', 'default:identitydocuments', array('index','add','edit','view','Identity Documents'));

		 $acl->addResource(new Zend_Acl_Resource('default:index'));
                        $acl->allow('HRManager', 'default:index', array('index','loginpopupsave','logout','clearsessionarray','forcelogout','browserfailure','sendpassword','updatecontactnumber','getstates','getstatesnormal','getcities','getcitiesnormal','getdepartments','getpositions','gettargetcurrency','calculatedays','calculatebusinessdays','calculatecalendardays','fromdatetodate','fromdatetodateorg','validateorgheadjoiningdate','medicalclaimdates','gettimeformat','chkcurrenttime','popup','createorremoveshortcut','sessiontour','getissuingauthority','setsessionval','checkisactivestatus','updatetheme','welcome','getmultidepts','getmultiemps','getpayrollcomponents','getpayrollattendance','getpayrollformulacomponents','getdownloadayslip','savepayrollcomponents','saveformulafields','getbunitdetails','getpayrollyeardetails','getpayrolldetails','generatepayroll','employeeleavesonleavetypes','generatepayslip','onboardingcandidates','getempsearch'));

		 $acl->addResource(new Zend_Acl_Resource('default:jobtitles'));
                            $acl->allow('HRManager', 'default:jobtitles', array('index','addpopup','add','edit','view','Job Titles'));

		 $acl->addResource(new Zend_Acl_Resource('default:leavemanagement'));
                            $acl->allow('HRManager', 'default:leavemanagement', array('index','add','edit','view','Leave Management Options'));

		 $acl->addResource(new Zend_Acl_Resource('default:leaverequest'));
                            $leaverequest_add = 'yes';
                                if($this->id_param == '' && $leaverequest_add == 'yes')
                                    $acl->allow('HRManager','default:leaverequest', array('index','saveleaverequestdetails','gethalfdaydetails','editpopup','updateleavedetails','add','Leave Request','edit'));

                                else
                                    $acl->allow('HRManager','default:leaverequest', array('index','saveleaverequestdetails','gethalfdaydetails','editpopup','updateleavedetails','add','Leave Request'));

                                
		 $acl->addResource(new Zend_Acl_Resource('default:manageremployeevacations'));
                            $acl->allow('HRManager', 'default:manageremployeevacations', array('index','edit','view','Manager Employee Leaves'));

		 $acl->addResource(new Zend_Acl_Resource('default:mydetails'));
                            $acl->allow('HRManager', 'default:mydetails', array('index','personaldetailsview','personal','communicationdetailsview','communication','skills','education','experience','leaves','holidays','salarydetailsview','certification','creditcarddetailsview','creditcard','visadetailsview','visa','medicalclaims','disabilitydetailsview','disability','dependency','workeligibilitydetailsview','workeligibility','additionaldetailsedit','jobhistory','documents','assetdetailsview','add','edit','delete','view','My Details'));

		 $acl->addResource(new Zend_Acl_Resource('default:myemployees'));
                            $acl->allow('HRManager', 'default:myemployees', array('index','perview','comview','skillsview','expview','eduview','trainingview','additionaldetailsview','jobhistoryview','skillsedit','jobhistoryedit','expedit','eduedit','trainingedit','additionaldetailsedit','peredit','comedit','docview','docedit','employeereport','getempreportdata','empauto','emprptpdf','exportemployeereport','downloadreport','view','My Team'));

		 $acl->addResource(new Zend_Acl_Resource('default:myholidaycalendar'));
                            $acl->allow('HRManager', 'default:myholidaycalendar', array('index','view','My Holiday Calendar'));

		 $acl->addResource(new Zend_Acl_Resource('default:myteamappraisal'));
                            $acl->allow('HRManager', 'default:myteamappraisal', array('savelineresponse','savemngresponse','getempcontent','index','getsearchedempcontent','getsearchedstatus','downloadpdf','downloadUploadedFile','My Team Appraisal'));

		 $acl->addResource(new Zend_Acl_Resource('default:onboardingcandidates'));
                            $acl->allow('HRManager', 'default:onboardingcandidates', array('index','add','edit','delete','view','On Boarding Candidates'));

		 $acl->addResource(new Zend_Acl_Resource('default:organisationinfo'));
                            $acl->allow('HRManager', 'default:organisationinfo', array('index','edit_old','saveupdate','uploadpreview','validateorgstartdate','getcompleteorgdata','addorghead','edit','view','Organization Info'));

		 $acl->addResource(new Zend_Acl_Resource('default:payfrequency'));
                            $acl->allow('HRManager', 'default:payfrequency', array('index','addpopup','add','edit','view','Pay Frequency'));

		 $acl->addResource(new Zend_Acl_Resource('default:payrollattendancereport'));
                            $acl->allow('HRManager', 'default:payrollattendancereport', array('index','addpopup','getrequests','view','Attendance Report'));

		 $acl->addResource(new Zend_Acl_Resource('default:payrollattendancerequest'));
                            $acl->allow('HRManager', 'default:payrollattendancerequest', array('index','addpopup','getrequests','view','Attendance Request'));

		 $acl->addResource(new Zend_Acl_Resource('default:payrollemployeeadvances'));
                            $acl->allow('HRManager', 'default:payrollemployeeadvances', array('index','add','edit','delete','view','Employee Advances'));

		 $acl->addResource(new Zend_Acl_Resource('default:payrollmyadvances'));
                            $acl->allow('HRManager', 'default:payrollmyadvances', array('index','add','edit','delete','view','My Advances'));

		 $acl->addResource(new Zend_Acl_Resource('default:payrollperiods'));
                            $acl->allow('HRManager', 'default:payrollperiods', array('index','addpopup','getrequests','add','edit','delete','view','Payroll Periods'));

		 $acl->addResource(new Zend_Acl_Resource('default:pendingleaves'));
                            $acl->allow('HRManager', 'default:pendingleaves', array('index','delete','view','My Leaves'));

		 $acl->addResource(new Zend_Acl_Resource('default:policydocuments'));
                            $acl->allow('HRManager', 'default:policydocuments', array('index','uploaddoc','deletedocument','addmultiple','uploadmultipledocs','add','edit','delete','view','View/Manage Policy Documents'));

		 $acl->addResource(new Zend_Acl_Resource('default:positions'));
                            $acl->allow('HRManager', 'default:positions', array('index','addpopup','add','edit','view','Positions'));

		 $acl->addResource(new Zend_Acl_Resource('default:rejectedrequisitions'));
                            $acl->allow('HRManager', 'default:rejectedrequisitions', array('index','view','Rejected Requisitions'));

		 $acl->addResource(new Zend_Acl_Resource('default:remunerationbasis'));
                            $acl->allow('HRManager', 'default:remunerationbasis', array('index','add','edit','view','Remuneration Basis'));

		 $acl->addResource(new Zend_Acl_Resource('default:reports'));
                            $acl->allow('HRManager', 'default:reports', array('getrolepopup','emprolesgrouppopup','performancereport','previousappraisals','getselectedappraisaldata','getinterviewroundsdata','interviewrounds','rolesgroup','exportemprolesgroup','exportrolesgroupreport','exportinterviewrpt','exportactiveuserrpt','exportpayrollrpt','exportemployeereport','rolesgrouprptpdf','activeuserrptpdf','emprptpdf','interviewrptpdf','rolesgroupdata','emprolesgroup','emprolesgroupdata','activeuser','getactiveuserdata','getattendancereportsdata','getpayrollreportsdata','getempreportdata','empauto','servicedeskreport','getsddata','servicedeskpdf','servicedeskexcel','employeereport','getdeptsemp','index','holidaygroupreports','getpdfreportholiday','getexcelreportholiday','payrollreport','attendancereport','leavesreport','getpdfreportleaves','getexcelreportleaves','leavesreporttabheader','leavemanagementreport','getpdfreportleavemanagement','getexcelreportleavemanagement','bunitauto','bunitcodeauto','getexcelreportbusinessunit','getbusinessunitspdf','businessunits','userlogreport','departments','exportdepartmentpdf','getexcelreportdepartment','candidaterptexcel','candidaterptpdf','getcandidatesreportdata','candidatesreport','requisitionauto','requisitionrptexcel','requisitionrptpdf','getrequisitionsstatusreportdata','requisitionstatusreport','activitylogreport','downloadreport','agencylistreport','agencynameauto','agencysebsiteauto','empscreening','getspecimennames','getagencynames','getexcelreportempscreening','getempscreeningpdf','Analytics'));

		 $acl->addResource(new Zend_Acl_Resource('default:requisition'));
                            $acl->allow('HRManager', 'default:requisition', array('index','viewhr','approverequisition','addcandidate','interview','getdepartments','getpositions','viewpopup','getapprreqdata','chkreqforclose','getempreportingmanagers','getemailcount','getapprovers','add','edit','delete','view','Openings/Positions'));

		 $acl->addResource(new Zend_Acl_Resource('default:scheduleinterviews'));
                            $acl->allow('HRManager', 'default:scheduleinterviews', array('candidatepopup','index','downloadresume','getcandidates','add','edit','view','Scheduled Interviews'));

		 $acl->addResource(new Zend_Acl_Resource('default:servicedeskconf'));
                            $acl->allow('HRManager', 'default:servicedeskconf', array('index','getemployees','getapprover','getbunitimplementation','getassets','add','edit','delete','view','Settings'));

		 $acl->addResource(new Zend_Acl_Resource('default:servicedeskdepartment'));
                            $acl->allow('HRManager', 'default:servicedeskdepartment', array('index','addpopup','getrequests','add','edit','delete','view','Categories'));

		 $acl->addResource(new Zend_Acl_Resource('default:servicedeskrequest'));
                            $acl->allow('HRManager', 'default:servicedeskrequest', array('index','add','edit','delete','view','Request Types'));

		 $acl->addResource(new Zend_Acl_Resource('default:servicerequests'));
                            $servicerequests_add = 'yes';
                                if($this->id_param == '' && $servicerequests_add == 'yes')
                                    $acl->allow('HRManager','default:servicerequests', array('index','uploadsave','uploaddelete','view','getrequests','changestatus','checkrequeststatus','getuserassets','index','add','uploadsave','uploaddelete','view','getrequests','changestatus','checkrequeststatus','getuserassets','edit'));

                                else
                                    $acl->allow('HRManager','default:servicerequests', array('index','uploadsave','uploaddelete','view','getrequests','changestatus','checkrequeststatus','getuserassets','index','add','uploadsave','uploaddelete','view','getrequests','changestatus','checkrequeststatus','getuserassets'));

                                
		 $acl->addResource(new Zend_Acl_Resource('default:shortlistedcandidates'));
                            $acl->allow('HRManager', 'default:shortlistedcandidates', array('index','view','Shortlisted & Selected Candidates'));

		 $acl->addResource(new Zend_Acl_Resource('default:structure'));
                            $acl->allow('HRManager', 'default:structure', array('index','Organization Structure'));

		 $acl->addResource(new Zend_Acl_Resource('default:usermanagement'));
                            $acl->allow('HRManager', 'default:usermanagement', array('index','saveupdate','getemailofuser','add','edit','view','Manage External Users'));

		 $acl->addResource(new Zend_Acl_Resource('default:workeligibilitydoctypes'));
                            $acl->allow('HRManager', 'default:workeligibilitydoctypes', array('index','addpopup','add','edit','view','Work Eligibility Document Types'));

		 $acl->addResource(new Zend_Acl_Resource('assets:assetcategories'));
                            $acl->allow('HRManager', 'assets:assetcategories', array('index','addpopup','addsubcatpopup','assetuserlog','add','edit','delete','view','Asset Categories'));

		 $acl->addResource(new Zend_Acl_Resource('assets:assets'));
                            $acl->allow('HRManager', 'assets:assets', array('index','uploadsave','uploaddelete','getsubcategories','deleteimage','downloadimage','getemployeesdata','add','edit','delete','view','Assets'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:advances'));
                            $acl->allow('HRManager', 'expenses:advances', array('index','getprojects','myadvances','viewmoreadvances','clearadvancesdata','addreturnpopup','add','edit','delete','view','Advances'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:employeeadvances'));
                            $acl->allow('HRManager', 'expenses:employeeadvances', array('index','add','edit','delete','view','Employee Advances'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:expensecategories'));
                            $acl->allow('HRManager', 'expenses:expensecategories', array('index','add','edit','delete','view','Category'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:expenses'));
                            $acl->allow('HRManager', 'expenses:expenses', array('index','clone','addpopup','uploadsave','uploaddelete','displayreceipts','addtrippopup','submitexpense','addreceiptimage','expensestatus','listreportingmangers','viewmoremanagers','forwardexpenseto','downloadexpensepdf','bulkexpenses','getcategories','getprojects','getcurrency','uploadedfiles','add','edit','delete','view','Expenses'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:myemployeeexpenses'));
                            $acl->allow('HRManager', 'expenses:myemployeeexpenses', array('index','add','edit','delete','view','My Employee Expenses'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:paymentmode'));
                            $acl->allow('HRManager', 'expenses:paymentmode', array('index','add','edit','delete','view','Payment Mode'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:receipts'));
                            $acl->allow('HRManager', 'expenses:receipts', array('index','downloadreceipt','downloadexpensereceipt','deletereceipt','uploadsave','displayreceipts','viewmorereceipts','listexpenses','addreceipttoexpense','viewmoreexpenses','cleardata','showreceiptspopup','listtrips','viewmoretrips','addexpensetotrip','add','edit','delete','view','Receipts'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:trips'));
                            $acl->allow('HRManager', 'expenses:trips', array('index','addpopup','tripstatus','deleteexpense','downloadtrippdf','add','edit','delete','view','Trips'));

		 $acl->addResource(new Zend_Acl_Resource('default:processes'));
                            $acl->allow('HRManager', 'default:processes', array('index','addpopup','editpopup','viewpopup','savecomments','displaycomments','savefeedback','index','addpopup','editpopup','viewpopup','delete','savecomments','displaycomments','savefeedback'));

		 $acl->addResource(new Zend_Acl_Resource('default:interviewrounds'));
                            $acl->allow('HRManager', 'default:interviewrounds', array('index','addpopup','editpopup','viewpopup','index','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:empperformanceappraisal'));
                            $acl->allow('HRManager', 'default:empperformanceappraisal', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:emppayslips'));
                            $acl->allow('HRManager', 'default:emppayslips', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empbenefits'));
                            $acl->allow('HRManager', 'default:empbenefits', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:emprequisitiondetails'));
                            $acl->allow('HRManager', 'default:emprequisitiondetails', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empremunerationdetails'));
                            $acl->allow('HRManager', 'default:empremunerationdetails', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empsecuritycredentials'));
                            $acl->allow('HRManager', 'default:empsecuritycredentials', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:apprreqcandidates'));
                            $acl->allow('HRManager', 'default:apprreqcandidates', array('index','viewpopup','index','viewpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:emppersonaldetails'));
                            $acl->allow('HRManager', 'default:emppersonaldetails', array('index','view','index','view','edit'));

		 $acl->addResource(new Zend_Acl_Resource('default:employeedocs'));
                            $acl->allow('HRManager', 'default:employeedocs', array('index','view','save','update','uploadsave','uploaddelete','downloadfiles','index','view','save','delete','edit','update','uploadsave','uploaddelete','downloadfiles'));

		 $acl->addResource(new Zend_Acl_Resource('default:empcommunicationdetails'));
                            $acl->allow('HRManager', 'default:empcommunicationdetails', array('index','view','index','view','edit'));

		 $acl->addResource(new Zend_Acl_Resource('default:trainingandcertificationdetails'));
                            $acl->allow('HRManager', 'default:trainingandcertificationdetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:experiencedetails'));
                            $acl->allow('HRManager', 'default:experiencedetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:educationdetails'));
                            $acl->allow('HRManager', 'default:educationdetails', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:medicalclaims'));
                            $acl->allow('HRManager', 'default:medicalclaims', array('index','addpopup','viewpopup','editpopup','view','index','edit','addpopup','viewpopup','editpopup','delete','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empleaves'));
                            $acl->allow('HRManager', 'default:empleaves', array('index','view','viewpopup','index','edit','view','viewpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:empskills'));
                            $acl->allow('HRManager', 'default:empskills', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:disabilitydetails'));
                            $acl->allow('HRManager', 'default:disabilitydetails', array('index','view','index','add','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:workeligibilitydetails'));
                            $acl->allow('HRManager', 'default:workeligibilitydetails', array('index','view','index','add','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empadditionaldetails'));
                            $acl->allow('HRManager', 'default:empadditionaldetails', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:visaandimmigrationdetails'));
                            $acl->allow('HRManager', 'default:visaandimmigrationdetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:creditcarddetails'));
                            $acl->allow('HRManager', 'default:creditcarddetails', array('index','view','index','add','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:dependencydetails'));
                            $acl->allow('HRManager', 'default:dependencydetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:empholidays'));
                            $acl->allow('HRManager', 'default:empholidays', array('index','view','viewpopup','index','edit','view','viewpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:empjobhistory'));
                            $acl->allow('HRManager', 'default:empjobhistory', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:assetdetails'));
                            $acl->allow('HRManager', 'default:assetdetails', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empsalarydetails'));
                            $acl->allow('HRManager', 'default:empsalarydetails', array('index','view','index','edit','view'));
}if($role == 5 )
           {
		 $acl->addResource(new Zend_Acl_Resource('default:announcements'));
                            $acl->allow('employee', 'default:announcements', array('index','getdepts','uploadsave','uploaddelete','view','Announcements'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalhistoryself'));
                            $acl->allow('employee', 'default:appraisalhistoryself', array('index','view','My Appraisal History'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalmanager'));
                            $acl->allow('employee', 'default:appraisalmanager', array('submitmanager','deletemanagergroup','savemanagergroup','index','viewgroup','createnewgroup','showgroups','showviewgroups','edit','view','Manager Appraisal'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalself'));
                            $acl->allow('employee', 'default:appraisalself', array('index','save','edit','view','Self Appraisal'));

		 $acl->addResource(new Zend_Acl_Resource('default:businessunits'));
                            $acl->allow('employee', 'default:businessunits', array('index','getdeptnames','view','Business Units'));

		 $acl->addResource(new Zend_Acl_Resource('default:dashboard'));
                        $acl->allow('employee', 'default:dashboard', array('index','saveuserdashboard','getwidgtes','upgradeapplication','emailsettings','changepassword','editpassword','update','uploadpreview','viewprofile','viewsettings','savemenuwidgets','getmenuname','fetchmenuname','getnavids','getopeningpositondate','menuwork'));

		 $acl->addResource(new Zend_Acl_Resource('default:departments'));
                            $acl->allow('employee', 'default:departments', array('index','viewpopup','editpopup','getdepartments','getempnames','view','Departments'));

		 $acl->addResource(new Zend_Acl_Resource('default:employeeattendanceapprovals'));
                            $acl->allow('employee', 'default:employeeattendanceapprovals', array('index','view','Manager Employee Requests'));

		 $acl->addResource(new Zend_Acl_Resource('default:feedforwardemployee'));
                            $acl->allow('employee', 'default:feedforwardemployee', array('index','save','edit','view','Appraise Your Manager'));

		 $acl->addResource(new Zend_Acl_Resource('default:index'));
                        $acl->allow('employee', 'default:index', array('index','loginpopupsave','logout','clearsessionarray','forcelogout','browserfailure','sendpassword','updatecontactnumber','getstates','getstatesnormal','getcities','getcitiesnormal','getdepartments','getpositions','gettargetcurrency','calculatedays','calculatebusinessdays','calculatecalendardays','fromdatetodate','fromdatetodateorg','validateorgheadjoiningdate','medicalclaimdates','gettimeformat','chkcurrenttime','popup','createorremoveshortcut','sessiontour','getissuingauthority','setsessionval','checkisactivestatus','updatetheme','welcome','getmultidepts','getmultiemps','getpayrollcomponents','getpayrollattendance','getpayrollformulacomponents','getdownloadayslip','savepayrollcomponents','saveformulafields','getbunitdetails','getpayrollyeardetails','getpayrolldetails','generatepayroll','employeeleavesonleavetypes','generatepayslip','onboardingcandidates','getempsearch'));

		 $acl->addResource(new Zend_Acl_Resource('default:leaverequest'));
                            $leaverequest_add = 'yes';
                                if($this->id_param == '' && $leaverequest_add == 'yes')
                                    $acl->allow('employee','default:leaverequest', array('index','saveleaverequestdetails','gethalfdaydetails','editpopup','updateleavedetails','add','Leave Request','edit'));

                                else
                                    $acl->allow('employee','default:leaverequest', array('index','saveleaverequestdetails','gethalfdaydetails','editpopup','updateleavedetails','add','Leave Request'));

                                
		 $acl->addResource(new Zend_Acl_Resource('default:manageremployeevacations'));
                            $acl->allow('employee', 'default:manageremployeevacations', array('index','edit','view','Manager Employee Leaves'));

		 $acl->addResource(new Zend_Acl_Resource('default:mydetails'));
                            $acl->allow('employee', 'default:mydetails', array('index','personaldetailsview','personal','communicationdetailsview','communication','skills','education','experience','leaves','holidays','salarydetailsview','certification','creditcarddetailsview','creditcard','visadetailsview','visa','medicalclaims','disabilitydetailsview','disability','dependency','workeligibilitydetailsview','workeligibility','additionaldetailsedit','jobhistory','documents','assetdetailsview','add','edit','delete','view','My Details'));

		 $acl->addResource(new Zend_Acl_Resource('default:myemployees'));
                            $acl->allow('employee', 'default:myemployees', array('index','perview','comview','skillsview','expview','eduview','trainingview','additionaldetailsview','jobhistoryview','skillsedit','jobhistoryedit','expedit','eduedit','trainingedit','additionaldetailsedit','peredit','comedit','docview','docedit','employeereport','getempreportdata','empauto','emprptpdf','exportemployeereport','downloadreport','view','My Team'));

		 $acl->addResource(new Zend_Acl_Resource('default:myholidaycalendar'));
                            $acl->allow('employee', 'default:myholidaycalendar', array('index','view','My Holiday Calendar'));

		 $acl->addResource(new Zend_Acl_Resource('default:onboardingcandidates'));
                            $acl->allow('employee', 'default:onboardingcandidates', array('index','view','On Boarding Candidates'));

		 $acl->addResource(new Zend_Acl_Resource('default:organisationinfo'));
                            $acl->allow('employee', 'default:organisationinfo', array('index','edit_old','saveupdate','uploadpreview','validateorgstartdate','getcompleteorgdata','addorghead','Organization Info'));

		 $acl->addResource(new Zend_Acl_Resource('default:payrollattendancereport'));
                            $acl->allow('employee', 'default:payrollattendancereport', array('index','addpopup','getrequests','view','Attendance Report'));

		 $acl->addResource(new Zend_Acl_Resource('default:payrollattendancerequest'));
                            $acl->allow('employee', 'default:payrollattendancerequest', array('index','addpopup','getrequests','add','edit','view','Attendance Request'));

		 $acl->addResource(new Zend_Acl_Resource('default:pendingleaves'));
                            $acl->allow('employee', 'default:pendingleaves', array('index','delete','view','My Leaves'));

		 $acl->addResource(new Zend_Acl_Resource('default:policydocuments'));
                            $acl->allow('employee', 'default:policydocuments', array('index','uploaddoc','deletedocument','addmultiple','uploadmultipledocs','view','View/Manage Policy Documents'));

		 $acl->addResource(new Zend_Acl_Resource('default:servicerequests'));
                            $servicerequests_add = 'yes';
                                if($this->id_param == '' && $servicerequests_add == 'yes')
                                    $acl->allow('employee','default:servicerequests', array('index','uploadsave','uploaddelete','view','getrequests','changestatus','checkrequeststatus','getuserassets','index','add','uploadsave','uploaddelete','view','getrequests','changestatus','checkrequeststatus','getuserassets','edit'));

                                else
                                    $acl->allow('employee','default:servicerequests', array('index','uploadsave','uploaddelete','view','getrequests','changestatus','checkrequeststatus','getuserassets','index','add','uploadsave','uploaddelete','view','getrequests','changestatus','checkrequeststatus','getuserassets'));

                                
		 $acl->addResource(new Zend_Acl_Resource('expenses:advances'));
                            $acl->allow('employee', 'expenses:advances', array('index','getprojects','myadvances','viewmoreadvances','clearadvancesdata','addreturnpopup','add','edit','delete','view','Advances'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:employeeadvances'));
                            $acl->allow('employee', 'expenses:employeeadvances', array('index','add','edit','delete','view','Employee Advances'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:expenses'));
                            $acl->allow('employee', 'expenses:expenses', array('index','clone','addpopup','uploadsave','uploaddelete','displayreceipts','addtrippopup','submitexpense','addreceiptimage','expensestatus','listreportingmangers','viewmoremanagers','forwardexpenseto','downloadexpensepdf','bulkexpenses','getcategories','getprojects','getcurrency','uploadedfiles','add','edit','delete','view','Expenses'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:myemployeeexpenses'));
                            $acl->allow('employee', 'expenses:myemployeeexpenses', array('index','add','edit','delete','view','My Employee Expenses'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:receipts'));
                            $acl->allow('employee', 'expenses:receipts', array('index','downloadreceipt','downloadexpensereceipt','deletereceipt','uploadsave','displayreceipts','viewmorereceipts','listexpenses','addreceipttoexpense','viewmoreexpenses','cleardata','showreceiptspopup','listtrips','viewmoretrips','addexpensetotrip','add','edit','delete','view','Receipts'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:trips'));
                            $acl->allow('employee', 'expenses:trips', array('index','addpopup','tripstatus','deleteexpense','downloadtrippdf','add','edit','delete','view','Trips'));

		 $acl->addResource(new Zend_Acl_Resource('default:emppersonaldetails'));
                            $acl->allow('employee', 'default:emppersonaldetails', array('index','view','index','view','edit'));

		 $acl->addResource(new Zend_Acl_Resource('default:employeedocs'));
                            $acl->allow('employee', 'default:employeedocs', array('index','view','save','update','uploadsave','uploaddelete','downloadfiles','index','view','save','delete','edit','update','uploadsave','uploaddelete','downloadfiles'));

		 $acl->addResource(new Zend_Acl_Resource('default:empcommunicationdetails'));
                            $acl->allow('employee', 'default:empcommunicationdetails', array('index','view','index','view','edit'));

		 $acl->addResource(new Zend_Acl_Resource('default:trainingandcertificationdetails'));
                            $acl->allow('employee', 'default:trainingandcertificationdetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:experiencedetails'));
                            $acl->allow('employee', 'default:experiencedetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:educationdetails'));
                            $acl->allow('employee', 'default:educationdetails', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:medicalclaims'));
                            $acl->allow('employee', 'default:medicalclaims', array('index','addpopup','viewpopup','editpopup','view','index','edit','addpopup','viewpopup','editpopup','delete','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empleaves'));
                            $acl->allow('employee', 'default:empleaves', array('index','view','viewpopup','index','edit','view','viewpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:empskills'));
                            $acl->allow('employee', 'default:empskills', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:disabilitydetails'));
                            $acl->allow('employee', 'default:disabilitydetails', array('index','view','index','add','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:workeligibilitydetails'));
                            $acl->allow('employee', 'default:workeligibilitydetails', array('index','view','index','add','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empadditionaldetails'));
                            $acl->allow('employee', 'default:empadditionaldetails', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:visaandimmigrationdetails'));
                            $acl->allow('employee', 'default:visaandimmigrationdetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:creditcarddetails'));
                            $acl->allow('employee', 'default:creditcarddetails', array('index','view','index','add','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:dependencydetails'));
                            $acl->allow('employee', 'default:dependencydetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:empholidays'));
                            $acl->allow('employee', 'default:empholidays', array('index','view','viewpopup','index','edit','view','viewpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:empjobhistory'));
                            $acl->allow('employee', 'default:empjobhistory', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:assetdetails'));
                            $acl->allow('employee', 'default:assetdetails', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:interviewrounds'));
                            $acl->allow('employee', 'default:interviewrounds', array('index','addpopup','editpopup','viewpopup','index','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:apprreqcandidates'));
                            $acl->allow('employee', 'default:apprreqcandidates', array('index','viewpopup'));
}if($role == 6 )
           {
		 $acl->addResource(new Zend_Acl_Resource('default:announcements'));
                            $acl->allow('user', 'default:announcements', array('index','getdepts','uploadsave','uploaddelete','view','Announcements'));

		 $acl->addResource(new Zend_Acl_Resource('default:businessunits'));
                            $acl->allow('user', 'default:businessunits', array('index','getdeptnames','view','Business Units'));

		 $acl->addResource(new Zend_Acl_Resource('default:dashboard'));
                        $acl->allow('user', 'default:dashboard', array('index','saveuserdashboard','getwidgtes','upgradeapplication','emailsettings','changepassword','editpassword','update','uploadpreview','viewprofile','viewsettings','savemenuwidgets','getmenuname','fetchmenuname','getnavids','getopeningpositondate','menuwork'));

		 $acl->addResource(new Zend_Acl_Resource('default:departments'));
                            $acl->allow('user', 'default:departments', array('index','viewpopup','editpopup','getdepartments','getempnames','view','Departments'));

		 $acl->addResource(new Zend_Acl_Resource('default:heirarchy'));
                            $acl->allow('user', 'default:heirarchy', array('index','addlist','editlist','saveadddata','saveeditdata','deletelist','Organization Hierarchy'));

		 $acl->addResource(new Zend_Acl_Resource('default:index'));
                        $acl->allow('user', 'default:index', array('index','loginpopupsave','logout','clearsessionarray','forcelogout','browserfailure','sendpassword','updatecontactnumber','getstates','getstatesnormal','getcities','getcitiesnormal','getdepartments','getpositions','gettargetcurrency','calculatedays','calculatebusinessdays','calculatecalendardays','fromdatetodate','fromdatetodateorg','validateorgheadjoiningdate','medicalclaimdates','gettimeformat','chkcurrenttime','popup','createorremoveshortcut','sessiontour','getissuingauthority','setsessionval','checkisactivestatus','updatetheme','welcome','getmultidepts','getmultiemps','getpayrollcomponents','getpayrollattendance','getpayrollformulacomponents','getdownloadayslip','savepayrollcomponents','saveformulafields','getbunitdetails','getpayrollyeardetails','getpayrolldetails','generatepayroll','employeeleavesonleavetypes','generatepayslip','onboardingcandidates','getempsearch'));

		 $acl->addResource(new Zend_Acl_Resource('default:organisationinfo'));
                            $acl->allow('user', 'default:organisationinfo', array('index','edit_old','saveupdate','uploadpreview','validateorgstartdate','getcompleteorgdata','addorghead','Organization Info'));

		 $acl->addResource(new Zend_Acl_Resource('default:structure'));
                            $acl->allow('user', 'default:structure', array('index','Organization Structure'));

		 $acl->addResource(new Zend_Acl_Resource('default:processes'));
                            $acl->allow('user', 'default:processes', array('index','editpopup','viewpopup','savecomments','displaycomments','savefeedback'));
}if($role == 7 )
           {
		 $acl->addResource(new Zend_Acl_Resource('default:announcements'));
                            $acl->allow('agency', 'default:announcements', array('index','getdepts','uploadsave','uploaddelete','view','Announcements'));

		 $acl->addResource(new Zend_Acl_Resource('default:businessunits'));
                            $acl->allow('agency', 'default:businessunits', array('index','getdeptnames','view','Business Units'));

		 $acl->addResource(new Zend_Acl_Resource('default:dashboard'));
                        $acl->allow('agency', 'default:dashboard', array('index','saveuserdashboard','getwidgtes','upgradeapplication','emailsettings','changepassword','editpassword','update','uploadpreview','viewprofile','viewsettings','savemenuwidgets','getmenuname','fetchmenuname','getnavids','getopeningpositondate','menuwork'));

		 $acl->addResource(new Zend_Acl_Resource('default:departments'));
                            $acl->allow('agency', 'default:departments', array('index','viewpopup','editpopup','getdepartments','getempnames','view','Departments'));

		 $acl->addResource(new Zend_Acl_Resource('default:employee'));
                            $acl->allow('agency', 'default:employee', array('getemprequi','index','changeorghead','getdepartments','getpositions','getempreportingmanagers','makeactiveinactive','changereportingmanager','addemppopup','uploadexcel','getindividualempdetails','view','Employees'));

		 $acl->addResource(new Zend_Acl_Resource('default:empscreening'));
                            $acl->allow('agency', 'default:empscreening', array('index','getemployeedata','getagencylist','getpocdata','forcedfullupdate','checkscreeningstatus','uploadfeedback','download','deletefeedback','edit','view','Employee/Candidate Screening'));

		 $acl->addResource(new Zend_Acl_Resource('default:heirarchy'));
                            $acl->allow('agency', 'default:heirarchy', array('index','addlist','editlist','saveadddata','saveeditdata','deletelist','Organization Hierarchy'));

		 $acl->addResource(new Zend_Acl_Resource('default:index'));
                        $acl->allow('agency', 'default:index', array('index','loginpopupsave','logout','clearsessionarray','forcelogout','browserfailure','sendpassword','updatecontactnumber','getstates','getstatesnormal','getcities','getcitiesnormal','getdepartments','getpositions','gettargetcurrency','calculatedays','calculatebusinessdays','calculatecalendardays','fromdatetodate','fromdatetodateorg','validateorgheadjoiningdate','medicalclaimdates','gettimeformat','chkcurrenttime','popup','createorremoveshortcut','sessiontour','getissuingauthority','setsessionval','checkisactivestatus','updatetheme','welcome','getmultidepts','getmultiemps','getpayrollcomponents','getpayrollattendance','getpayrollformulacomponents','getdownloadayslip','savepayrollcomponents','saveformulafields','getbunitdetails','getpayrollyeardetails','getpayrolldetails','generatepayroll','employeeleavesonleavetypes','generatepayslip','onboardingcandidates','getempsearch'));

		 $acl->addResource(new Zend_Acl_Resource('default:organisationinfo'));
                            $acl->allow('agency', 'default:organisationinfo', array('index','edit_old','saveupdate','uploadpreview','validateorgstartdate','getcompleteorgdata','addorghead','Organization Info'));

		 $acl->addResource(new Zend_Acl_Resource('default:structure'));
                            $acl->allow('agency', 'default:structure', array('index','Organization Structure'));

		 $acl->addResource(new Zend_Acl_Resource('default:processes'));
                            $acl->allow('agency', 'default:processes', array('index','editpopup','viewpopup','savecomments','displaycomments','savefeedback'));
}if($role == 8 )
           {
		 $acl->addResource(new Zend_Acl_Resource('default:accountclasstype'));
                            $acl->allow('sysadmin', 'default:accountclasstype', array('index','addpopup','saveupdate','add','edit','delete','view','Account Class Types'));

		 $acl->addResource(new Zend_Acl_Resource('default:announcements'));
                            $acl->allow('sysadmin', 'default:announcements', array('index','getdepts','uploadsave','uploaddelete','view','Announcements'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalhistoryself'));
                            $acl->allow('sysadmin', 'default:appraisalhistoryself', array('index','view','My Appraisal History'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalhistoryteam'));
                            $acl->allow('sysadmin', 'default:appraisalhistoryteam', array('index','getsearchedempcontent','view','Team Appraisal History'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalmanager'));
                            $acl->allow('sysadmin', 'default:appraisalmanager', array('submitmanager','deletemanagergroup','savemanagergroup','index','viewgroup','createnewgroup','showgroups','showviewgroups','edit','view','Manager Appraisal'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalself'));
                            $acl->allow('sysadmin', 'default:appraisalself', array('index','save','edit','view','Self Appraisal'));

		 $acl->addResource(new Zend_Acl_Resource('default:businessunits'));
                            $acl->allow('sysadmin', 'default:businessunits', array('index','getdeptnames','view','Business Units'));

		 $acl->addResource(new Zend_Acl_Resource('default:categories'));
                            $acl->allow('sysadmin', 'default:categories', array('index','addnewcategory','add','edit','view','Categories'));

		 $acl->addResource(new Zend_Acl_Resource('default:cities'));
                            $cities_add = 'yes';
                                if($this->id_param == '' && $cities_add == 'yes')
                                    $acl->allow('sysadmin','default:cities', array('index','getcitiescand','addpopup','addnewcity','add','delete','view','Cities','edit'));

                                else
                                    $acl->allow('sysadmin','default:cities', array('index','getcitiescand','addpopup','addnewcity','add','delete','view','Cities'));

                                
		 $acl->addResource(new Zend_Acl_Resource('default:countries'));
                            $countries_add = 'yes';
                                if($this->id_param == '' && $countries_add == 'yes')
                                    $acl->allow('sysadmin','default:countries', array('index','saveupdate','getcountrycode','addpopup','addnewcountry','add','delete','view','Countries','edit'));

                                else
                                    $acl->allow('sysadmin','default:countries', array('index','saveupdate','getcountrycode','addpopup','addnewcountry','add','delete','view','Countries'));

                                
		 $acl->addResource(new Zend_Acl_Resource('default:currency'));
                            $acl->allow('sysadmin', 'default:currency', array('index','addpopup','gettargetcurrency','add','edit','delete','view','Currencies'));

		 $acl->addResource(new Zend_Acl_Resource('default:currencyconverter'));
                            $acl->allow('sysadmin', 'default:currencyconverter', array('index','add','edit','delete','view','Currency Conversions'));

		 $acl->addResource(new Zend_Acl_Resource('default:dashboard'));
                        $acl->allow('sysadmin', 'default:dashboard', array('index','saveuserdashboard','getwidgtes','upgradeapplication','emailsettings','changepassword','editpassword','update','uploadpreview','viewprofile','viewsettings','savemenuwidgets','getmenuname','fetchmenuname','getnavids','getopeningpositondate','menuwork'));

		 $acl->addResource(new Zend_Acl_Resource('default:departments'));
                            $acl->allow('sysadmin', 'default:departments', array('index','viewpopup','editpopup','getdepartments','getempnames','view','Departments'));

		 $acl->addResource(new Zend_Acl_Resource('default:emailcontacts'));
                            $acl->allow('sysadmin', 'default:emailcontacts', array('index','getgroupoptions','getmailcnt','add','edit','delete','view','Email Contacts'));

		 $acl->addResource(new Zend_Acl_Resource('default:employee'));
                            $acl->allow('sysadmin', 'default:employee', array('getemprequi','index','changeorghead','getdepartments','getpositions','getempreportingmanagers','makeactiveinactive','changereportingmanager','addemppopup','uploadexcel','getindividualempdetails','view','Employees'));

		 $acl->addResource(new Zend_Acl_Resource('default:employeeattendanceapprovals'));
                            $acl->allow('sysadmin', 'default:employeeattendanceapprovals', array('index','view','Manager Employee Requests'));

		 $acl->addResource(new Zend_Acl_Resource('default:ethniccode'));
                            $acl->allow('sysadmin', 'default:ethniccode', array('index','saveupdate','addpopup','add','edit','delete','view','Ethnic Codes'));

		 $acl->addResource(new Zend_Acl_Resource('default:feedforwardemployee'));
                            $acl->allow('sysadmin', 'default:feedforwardemployee', array('index','save','edit','view','Appraise Your Manager'));

		 $acl->addResource(new Zend_Acl_Resource('default:gender'));
                            $acl->allow('sysadmin', 'default:gender', array('index','saveupdate','addpopup','add','edit','delete','view','Gender'));

		 $acl->addResource(new Zend_Acl_Resource('default:geographygroup'));
                            $acl->allow('sysadmin', 'default:geographygroup', array('index','add','edit','delete','view','Geo Groups'));

		 $acl->addResource(new Zend_Acl_Resource('default:heirarchy'));
                            $acl->allow('sysadmin', 'default:heirarchy', array('index','addlist','editlist','saveadddata','saveeditdata','deletelist','Organization Hierarchy'));

		 $acl->addResource(new Zend_Acl_Resource('default:identitycodes'));
                            $acl->allow('sysadmin', 'default:identitycodes', array('index','addpopup','add','edit','Identity Codes'));

		 $acl->addResource(new Zend_Acl_Resource('default:index'));
                        $acl->allow('sysadmin', 'default:index', array('index','loginpopupsave','logout','clearsessionarray','forcelogout','browserfailure','sendpassword','updatecontactnumber','getstates','getstatesnormal','getcities','getcitiesnormal','getdepartments','getpositions','gettargetcurrency','calculatedays','calculatebusinessdays','calculatecalendardays','fromdatetodate','fromdatetodateorg','validateorgheadjoiningdate','medicalclaimdates','gettimeformat','chkcurrenttime','popup','createorremoveshortcut','sessiontour','getissuingauthority','setsessionval','checkisactivestatus','updatetheme','welcome','getmultidepts','getmultiemps','getpayrollcomponents','getpayrollattendance','getpayrollformulacomponents','getdownloadayslip','savepayrollcomponents','saveformulafields','getbunitdetails','getpayrollyeardetails','getpayrolldetails','generatepayroll','employeeleavesonleavetypes','generatepayslip','onboardingcandidates','getempsearch'));

		 $acl->addResource(new Zend_Acl_Resource('default:leaverequest'));
                            $leaverequest_add = 'yes';
                                if($this->id_param == '' && $leaverequest_add == 'yes')
                                    $acl->allow('sysadmin','default:leaverequest', array('index','saveleaverequestdetails','gethalfdaydetails','editpopup','updateleavedetails','add','Leave Request','edit'));

                                else
                                    $acl->allow('sysadmin','default:leaverequest', array('index','saveleaverequestdetails','gethalfdaydetails','editpopup','updateleavedetails','add','Leave Request'));

                                
		 $acl->addResource(new Zend_Acl_Resource('default:licensetype'));
                            $acl->allow('sysadmin', 'default:licensetype', array('index','add','edit','delete','view','License Types'));

		 $acl->addResource(new Zend_Acl_Resource('default:manageremployeevacations'));
                            $acl->allow('sysadmin', 'default:manageremployeevacations', array('index','edit','view','Manager Employee Leaves'));

		 $acl->addResource(new Zend_Acl_Resource('default:maritalstatus'));
                            $acl->allow('sysadmin', 'default:maritalstatus', array('index','saveupdate','addpopup','add','edit','delete','view','Marital Status'));

		 $acl->addResource(new Zend_Acl_Resource('default:mydetails'));
                            $acl->allow('sysadmin', 'default:mydetails', array('index','personaldetailsview','personal','communicationdetailsview','communication','skills','education','experience','leaves','holidays','salarydetailsview','certification','creditcarddetailsview','creditcard','visadetailsview','visa','medicalclaims','disabilitydetailsview','disability','dependency','workeligibilitydetailsview','workeligibility','additionaldetailsedit','jobhistory','documents','assetdetailsview','add','edit','delete','view','My Details'));

		 $acl->addResource(new Zend_Acl_Resource('default:myemployees'));
                            $acl->allow('sysadmin', 'default:myemployees', array('index','perview','comview','skillsview','expview','eduview','trainingview','additionaldetailsview','jobhistoryview','skillsedit','jobhistoryedit','expedit','eduedit','trainingedit','additionaldetailsedit','peredit','comedit','docview','docedit','employeereport','getempreportdata','empauto','emprptpdf','exportemployeereport','downloadreport','view','My Team'));

		 $acl->addResource(new Zend_Acl_Resource('default:myholidaycalendar'));
                            $acl->allow('sysadmin', 'default:myholidaycalendar', array('index','view','My Holiday Calendar'));

		 $acl->addResource(new Zend_Acl_Resource('default:myteamappraisal'));
                            $acl->allow('sysadmin', 'default:myteamappraisal', array('savelineresponse','savemngresponse','getempcontent','index','getsearchedempcontent','getsearchedstatus','downloadpdf','downloadUploadedFile','My Team Appraisal'));

		 $acl->addResource(new Zend_Acl_Resource('default:nationality'));
                            $acl->allow('sysadmin', 'default:nationality', array('index','addpopup','add','edit','delete','view','Nationalities'));

		 $acl->addResource(new Zend_Acl_Resource('default:nationalitycontextcode'));
                            $acl->allow('sysadmin', 'default:nationalitycontextcode', array('index','add','edit','delete','view','Nationality Context Codes'));

		 $acl->addResource(new Zend_Acl_Resource('default:numberformats'));
                            $acl->allow('sysadmin', 'default:numberformats', array('index','add','edit','delete','view','Number Formats'));

		 $acl->addResource(new Zend_Acl_Resource('default:onboardingcandidates'));
                            $acl->allow('sysadmin', 'default:onboardingcandidates', array('index','view','On Boarding Candidates'));

		 $acl->addResource(new Zend_Acl_Resource('default:organisationinfo'));
                            $acl->allow('sysadmin', 'default:organisationinfo', array('index','edit_old','saveupdate','uploadpreview','validateorgstartdate','getcompleteorgdata','addorghead','Organization Info'));

		 $acl->addResource(new Zend_Acl_Resource('default:pendingleaves'));
                            $acl->allow('sysadmin', 'default:pendingleaves', array('index','delete','view','My Leaves'));

		 $acl->addResource(new Zend_Acl_Resource('default:policydocuments'));
                            $acl->allow('sysadmin', 'default:policydocuments', array('index','uploaddoc','deletedocument','addmultiple','uploadmultipledocs','add','edit','view','View/Manage Policy Documents'));

		 $acl->addResource(new Zend_Acl_Resource('default:prefix'));
                            $acl->allow('sysadmin', 'default:prefix', array('index','saveupdate','addpopup','add','edit','delete','view','Prefixes'));

		 $acl->addResource(new Zend_Acl_Resource('default:racecode'));
                            $acl->allow('sysadmin', 'default:racecode', array('index','saveupdate','addpopup','add','edit','delete','view','Race Codes'));

		 $acl->addResource(new Zend_Acl_Resource('default:roles'));
                            $acl->allow('sysadmin', 'default:roles', array('index','saveupdate','getgroupmenu','add','edit','delete','view','Roles & Privileges'));

		 $acl->addResource(new Zend_Acl_Resource('default:scheduleinterviews'));
                            $acl->allow('sysadmin', 'default:scheduleinterviews', array('candidatepopup','index','downloadresume','getcandidates','edit','view','Scheduled Interviews'));

		 $acl->addResource(new Zend_Acl_Resource('default:servicerequests'));
                            $servicerequests_add = 'yes';
                                if($this->id_param == '' && $servicerequests_add == 'yes')
                                    $acl->allow('sysadmin','default:servicerequests', array('index','uploadsave','uploaddelete','view','getrequests','changestatus','checkrequeststatus','getuserassets','index','add','uploadsave','uploaddelete','view','getrequests','changestatus','checkrequeststatus','getuserassets','edit'));

                                else
                                    $acl->allow('sysadmin','default:servicerequests', array('index','uploadsave','uploaddelete','view','getrequests','changestatus','checkrequeststatus','getuserassets','index','add','uploadsave','uploaddelete','view','getrequests','changestatus','checkrequeststatus','getuserassets'));

                                
		 $acl->addResource(new Zend_Acl_Resource('default:sitepreference'));
                            $acl->allow('sysadmin', 'default:sitepreference', array('index','view','add','edit','Site Preferences'));

		 $acl->addResource(new Zend_Acl_Resource('default:states'));
                            $states_add = 'yes';
                                if($this->id_param == '' && $states_add == 'yes')
                                    $acl->allow('sysadmin','default:states', array('index','getstates','getstatescand','addpopup','addnewstate','add','delete','view','States','edit'));

                                else
                                    $acl->allow('sysadmin','default:states', array('index','getstates','getstatescand','addpopup','addnewstate','add','delete','view','States'));

                                
		 $acl->addResource(new Zend_Acl_Resource('default:structure'));
                            $acl->allow('sysadmin', 'default:structure', array('index','Organization Structure'));

		 $acl->addResource(new Zend_Acl_Resource('default:timezone'));
                            $acl->allow('sysadmin', 'default:timezone', array('index','saveupdate','addpopup','add','edit','delete','view','Time Zones'));

		 $acl->addResource(new Zend_Acl_Resource('default:usermanagement'));
                            $acl->allow('sysadmin', 'default:usermanagement', array('index','saveupdate','getemailofuser','add','edit','view','Manage External Users'));

		 $acl->addResource(new Zend_Acl_Resource('assets:assetcategories'));
                            $acl->allow('sysadmin', 'assets:assetcategories', array('index','addpopup','addsubcatpopup','assetuserlog','add','edit','delete','view','Asset Categories'));

		 $acl->addResource(new Zend_Acl_Resource('assets:assets'));
                            $acl->allow('sysadmin', 'assets:assets', array('index','uploadsave','uploaddelete','getsubcategories','deleteimage','downloadimage','getemployeesdata','add','edit','delete','view','Assets'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:advances'));
                            $acl->allow('sysadmin', 'expenses:advances', array('index','getprojects','myadvances','viewmoreadvances','clearadvancesdata','addreturnpopup','add','edit','delete','view','Advances'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:employeeadvances'));
                            $acl->allow('sysadmin', 'expenses:employeeadvances', array('index','add','edit','delete','view','Employee Advances'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:expenses'));
                            $acl->allow('sysadmin', 'expenses:expenses', array('index','clone','addpopup','uploadsave','uploaddelete','displayreceipts','addtrippopup','submitexpense','addreceiptimage','expensestatus','listreportingmangers','viewmoremanagers','forwardexpenseto','downloadexpensepdf','bulkexpenses','getcategories','getprojects','getcurrency','uploadedfiles','add','edit','delete','view','Expenses'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:myemployeeexpenses'));
                            $acl->allow('sysadmin', 'expenses:myemployeeexpenses', array('index','add','edit','delete','view','My Employee Expenses'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:receipts'));
                            $acl->allow('sysadmin', 'expenses:receipts', array('index','downloadreceipt','downloadexpensereceipt','deletereceipt','uploadsave','displayreceipts','viewmorereceipts','listexpenses','addreceipttoexpense','viewmoreexpenses','cleardata','showreceiptspopup','listtrips','viewmoretrips','addexpensetotrip','add','edit','delete','view','Receipts'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:trips'));
                            $acl->allow('sysadmin', 'expenses:trips', array('index','addpopup','tripstatus','deleteexpense','downloadtrippdf','add','edit','delete','view','Trips'));

		 $acl->addResource(new Zend_Acl_Resource('default:managemenus'));
                            $acl->allow('sysadmin', 'default:managemenus', array('index','save','index','save'));

		 $acl->addResource(new Zend_Acl_Resource('default:emppersonaldetails'));
                            $acl->allow('sysadmin', 'default:emppersonaldetails', array('index','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:employeedocs'));
                            $acl->allow('sysadmin', 'default:employeedocs', array('index','view','save','update','uploadsave','uploaddelete','downloadfiles','index','view','save','delete','edit','update','uploadsave','uploaddelete','downloadfiles'));

		 $acl->addResource(new Zend_Acl_Resource('default:empcommunicationdetails'));
                            $acl->allow('sysadmin', 'default:empcommunicationdetails', array('index','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:trainingandcertificationdetails'));
                            $acl->allow('sysadmin', 'default:trainingandcertificationdetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:experiencedetails'));
                            $acl->allow('sysadmin', 'default:experiencedetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:educationdetails'));
                            $acl->allow('sysadmin', 'default:educationdetails', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:medicalclaims'));
                            $acl->allow('sysadmin', 'default:medicalclaims', array('index','addpopup','viewpopup','editpopup','view','index','edit','addpopup','viewpopup','editpopup','delete','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empleaves'));
                            $acl->allow('sysadmin', 'default:empleaves', array('index','view','viewpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:empskills'));
                            $acl->allow('sysadmin', 'default:empskills', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:disabilitydetails'));
                            $acl->allow('sysadmin', 'default:disabilitydetails', array('index','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:workeligibilitydetails'));
                            $acl->allow('sysadmin', 'default:workeligibilitydetails', array('index','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:visaandimmigrationdetails'));
                            $acl->allow('sysadmin', 'default:visaandimmigrationdetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:creditcarddetails'));
                            $acl->allow('sysadmin', 'default:creditcarddetails', array('index','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:dependencydetails'));
                            $acl->allow('sysadmin', 'default:dependencydetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:empholidays'));
                            $acl->allow('sysadmin', 'default:empholidays', array('index','view','viewpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:empjobhistory'));
                            $acl->allow('sysadmin', 'default:empjobhistory', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:empadditionaldetails'));
                            $acl->allow('sysadmin', 'default:empadditionaldetails', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:assetdetails'));
                            $acl->allow('sysadmin', 'default:assetdetails', array('index','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:interviewrounds'));
                            $acl->allow('sysadmin', 'default:interviewrounds', array('index','addpopup','editpopup','viewpopup','index','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:apprreqcandidates'));
                            $acl->allow('sysadmin', 'default:apprreqcandidates', array('index','viewpopup'));
}if($role == 9 )
           {
		 $acl->addResource(new Zend_Acl_Resource('default:announcements'));
                            $acl->allow('lead', 'default:announcements', array('index','getdepts','uploadsave','uploaddelete','view','Announcements'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalhistoryself'));
                            $acl->allow('lead', 'default:appraisalhistoryself', array('index','view','My Appraisal History'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalhistoryteam'));
                            $acl->allow('lead', 'default:appraisalhistoryteam', array('index','getsearchedempcontent','view','Team Appraisal History'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalmanager'));
                            $acl->allow('lead', 'default:appraisalmanager', array('submitmanager','deletemanagergroup','savemanagergroup','index','viewgroup','createnewgroup','showgroups','showviewgroups','edit','view','Manager Appraisal'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalself'));
                            $acl->allow('lead', 'default:appraisalself', array('index','save','edit','view','Self Appraisal'));

		 $acl->addResource(new Zend_Acl_Resource('default:businessunits'));
                            $acl->allow('lead', 'default:businessunits', array('index','getdeptnames','view','Business Units'));

		 $acl->addResource(new Zend_Acl_Resource('default:dashboard'));
                        $acl->allow('lead', 'default:dashboard', array('index','saveuserdashboard','getwidgtes','upgradeapplication','emailsettings','changepassword','editpassword','update','uploadpreview','viewprofile','viewsettings','savemenuwidgets','getmenuname','fetchmenuname','getnavids','getopeningpositondate','menuwork'));

		 $acl->addResource(new Zend_Acl_Resource('default:departments'));
                            $acl->allow('lead', 'default:departments', array('index','viewpopup','editpopup','getdepartments','getempnames','view','Departments'));

		 $acl->addResource(new Zend_Acl_Resource('default:employee'));
                            $acl->allow('lead', 'default:employee', array('getemprequi','index','changeorghead','getdepartments','getpositions','getempreportingmanagers','makeactiveinactive','changereportingmanager','addemppopup','uploadexcel','getindividualempdetails','view','Employees'));

		 $acl->addResource(new Zend_Acl_Resource('default:employeeattendanceapprovals'));
                            $acl->allow('lead', 'default:employeeattendanceapprovals', array('index','view','Manager Employee Requests'));

		 $acl->addResource(new Zend_Acl_Resource('default:feedforwardemployee'));
                            $acl->allow('lead', 'default:feedforwardemployee', array('index','save','edit','view','Appraise Your Manager'));

		 $acl->addResource(new Zend_Acl_Resource('default:index'));
                        $acl->allow('lead', 'default:index', array('index','loginpopupsave','logout','clearsessionarray','forcelogout','browserfailure','sendpassword','updatecontactnumber','getstates','getstatesnormal','getcities','getcitiesnormal','getdepartments','getpositions','gettargetcurrency','calculatedays','calculatebusinessdays','calculatecalendardays','fromdatetodate','fromdatetodateorg','validateorgheadjoiningdate','medicalclaimdates','gettimeformat','chkcurrenttime','popup','createorremoveshortcut','sessiontour','getissuingauthority','setsessionval','checkisactivestatus','updatetheme','welcome','getmultidepts','getmultiemps','getpayrollcomponents','getpayrollattendance','getpayrollformulacomponents','getdownloadayslip','savepayrollcomponents','saveformulafields','getbunitdetails','getpayrollyeardetails','getpayrolldetails','generatepayroll','employeeleavesonleavetypes','generatepayslip','onboardingcandidates','getempsearch'));

		 $acl->addResource(new Zend_Acl_Resource('default:leaverequest'));
                            $leaverequest_add = 'yes';
                                if($this->id_param == '' && $leaverequest_add == 'yes')
                                    $acl->allow('lead','default:leaverequest', array('index','saveleaverequestdetails','gethalfdaydetails','editpopup','updateleavedetails','add','Leave Request','edit'));

                                else
                                    $acl->allow('lead','default:leaverequest', array('index','saveleaverequestdetails','gethalfdaydetails','editpopup','updateleavedetails','add','Leave Request'));

                                
		 $acl->addResource(new Zend_Acl_Resource('default:manageremployeevacations'));
                            $acl->allow('lead', 'default:manageremployeevacations', array('index','edit','view','Manager Employee Leaves'));

		 $acl->addResource(new Zend_Acl_Resource('default:mydetails'));
                            $acl->allow('lead', 'default:mydetails', array('index','personaldetailsview','personal','communicationdetailsview','communication','skills','education','experience','leaves','holidays','salarydetailsview','certification','creditcarddetailsview','creditcard','visadetailsview','visa','medicalclaims','disabilitydetailsview','disability','dependency','workeligibilitydetailsview','workeligibility','additionaldetailsedit','jobhistory','documents','assetdetailsview','add','edit','delete','view','My Details'));

		 $acl->addResource(new Zend_Acl_Resource('default:myemployees'));
                            $acl->allow('lead', 'default:myemployees', array('index','perview','comview','skillsview','expview','eduview','trainingview','additionaldetailsview','jobhistoryview','skillsedit','jobhistoryedit','expedit','eduedit','trainingedit','additionaldetailsedit','peredit','comedit','docview','docedit','employeereport','getempreportdata','empauto','emprptpdf','exportemployeereport','downloadreport','view','My Team'));

		 $acl->addResource(new Zend_Acl_Resource('default:myholidaycalendar'));
                            $acl->allow('lead', 'default:myholidaycalendar', array('index','view','My Holiday Calendar'));

		 $acl->addResource(new Zend_Acl_Resource('default:myteamappraisal'));
                            $acl->allow('lead', 'default:myteamappraisal', array('savelineresponse','savemngresponse','getempcontent','index','getsearchedempcontent','getsearchedstatus','downloadpdf','downloadUploadedFile','My Team Appraisal'));

		 $acl->addResource(new Zend_Acl_Resource('default:onboardingcandidates'));
                            $acl->allow('lead', 'default:onboardingcandidates', array('index','view','On Boarding Candidates'));

		 $acl->addResource(new Zend_Acl_Resource('default:organisationinfo'));
                            $acl->allow('lead', 'default:organisationinfo', array('index','edit_old','saveupdate','uploadpreview','validateorgstartdate','getcompleteorgdata','addorghead','Organization Info'));

		 $acl->addResource(new Zend_Acl_Resource('default:payrollattendancereport'));
                            $acl->allow('lead', 'default:payrollattendancereport', array('index','addpopup','getrequests','view','Attendance Report'));

		 $acl->addResource(new Zend_Acl_Resource('default:payrollattendancerequest'));
                            $acl->allow('lead', 'default:payrollattendancerequest', array('index','addpopup','getrequests','add','edit','view','Attendance Request'));

		 $acl->addResource(new Zend_Acl_Resource('default:pendingleaves'));
                            $acl->allow('lead', 'default:pendingleaves', array('index','delete','view','My Leaves'));

		 $acl->addResource(new Zend_Acl_Resource('default:policydocuments'));
                            $acl->allow('lead', 'default:policydocuments', array('index','uploaddoc','deletedocument','addmultiple','uploadmultipledocs','view','View/Manage Policy Documents'));

		 $acl->addResource(new Zend_Acl_Resource('default:scheduleinterviews'));
                            $acl->allow('lead', 'default:scheduleinterviews', array('candidatepopup','index','downloadresume','getcandidates','edit','view','Scheduled Interviews'));

		 $acl->addResource(new Zend_Acl_Resource('default:servicerequests'));
                            $servicerequests_add = 'yes';
                                if($this->id_param == '' && $servicerequests_add == 'yes')
                                    $acl->allow('lead','default:servicerequests', array('index','uploadsave','uploaddelete','view','getrequests','changestatus','checkrequeststatus','getuserassets','index','add','uploadsave','uploaddelete','view','getrequests','changestatus','checkrequeststatus','getuserassets','edit'));

                                else
                                    $acl->allow('lead','default:servicerequests', array('index','uploadsave','uploaddelete','view','getrequests','changestatus','checkrequeststatus','getuserassets','index','add','uploadsave','uploaddelete','view','getrequests','changestatus','checkrequeststatus','getuserassets'));

                                
		 $acl->addResource(new Zend_Acl_Resource('default:structure'));
                            $acl->allow('lead', 'default:structure', array('index','Organization Structure'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:advances'));
                            $acl->allow('lead', 'expenses:advances', array('index','getprojects','myadvances','viewmoreadvances','clearadvancesdata','addreturnpopup','add','edit','delete','view','Advances'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:employeeadvances'));
                            $acl->allow('lead', 'expenses:employeeadvances', array('index','add','edit','delete','view','Employee Advances'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:expenses'));
                            $acl->allow('lead', 'expenses:expenses', array('index','clone','addpopup','uploadsave','uploaddelete','displayreceipts','addtrippopup','submitexpense','addreceiptimage','expensestatus','listreportingmangers','viewmoremanagers','forwardexpenseto','downloadexpensepdf','bulkexpenses','getcategories','getprojects','getcurrency','uploadedfiles','add','edit','delete','view','Expenses'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:myemployeeexpenses'));
                            $acl->allow('lead', 'expenses:myemployeeexpenses', array('index','add','edit','delete','view','My Employee Expenses'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:receipts'));
                            $acl->allow('lead', 'expenses:receipts', array('index','downloadreceipt','downloadexpensereceipt','deletereceipt','uploadsave','displayreceipts','viewmorereceipts','listexpenses','addreceipttoexpense','viewmoreexpenses','cleardata','showreceiptspopup','listtrips','viewmoretrips','addexpensetotrip','add','edit','delete','view','Receipts'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:trips'));
                            $acl->allow('lead', 'expenses:trips', array('index','addpopup','tripstatus','deleteexpense','downloadtrippdf','add','edit','delete','view','Trips'));

		 $acl->addResource(new Zend_Acl_Resource('default:emppersonaldetails'));
                            $acl->allow('lead', 'default:emppersonaldetails', array('index','view','index','view','edit'));

		 $acl->addResource(new Zend_Acl_Resource('default:employeedocs'));
                            $acl->allow('lead', 'default:employeedocs', array('index','view','save','update','uploadsave','uploaddelete','downloadfiles','index','view','save','delete','edit','update','uploadsave','uploaddelete','downloadfiles'));

		 $acl->addResource(new Zend_Acl_Resource('default:empcommunicationdetails'));
                            $acl->allow('lead', 'default:empcommunicationdetails', array('index','view','index','view','edit'));

		 $acl->addResource(new Zend_Acl_Resource('default:trainingandcertificationdetails'));
                            $acl->allow('lead', 'default:trainingandcertificationdetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:experiencedetails'));
                            $acl->allow('lead', 'default:experiencedetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:educationdetails'));
                            $acl->allow('lead', 'default:educationdetails', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:medicalclaims'));
                            $acl->allow('lead', 'default:medicalclaims', array('index','addpopup','viewpopup','editpopup','view','index','edit','addpopup','viewpopup','editpopup','delete','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empleaves'));
                            $acl->allow('lead', 'default:empleaves', array('index','view','viewpopup','index','edit','view','viewpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:empskills'));
                            $acl->allow('lead', 'default:empskills', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:disabilitydetails'));
                            $acl->allow('lead', 'default:disabilitydetails', array('index','view','index','add','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:workeligibilitydetails'));
                            $acl->allow('lead', 'default:workeligibilitydetails', array('index','view','index','add','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empadditionaldetails'));
                            $acl->allow('lead', 'default:empadditionaldetails', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:visaandimmigrationdetails'));
                            $acl->allow('lead', 'default:visaandimmigrationdetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:creditcarddetails'));
                            $acl->allow('lead', 'default:creditcarddetails', array('index','view','index','add','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:dependencydetails'));
                            $acl->allow('lead', 'default:dependencydetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:empholidays'));
                            $acl->allow('lead', 'default:empholidays', array('index','view','viewpopup','index','edit','view','viewpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:empjobhistory'));
                            $acl->allow('lead', 'default:empjobhistory', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:assetdetails'));
                            $acl->allow('lead', 'default:assetdetails', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:interviewrounds'));
                            $acl->allow('lead', 'default:interviewrounds', array('index','addpopup','editpopup','viewpopup','index','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:apprreqcandidates'));
                            $acl->allow('lead', 'default:apprreqcandidates', array('index','viewpopup'));
}if($role == 10 )
           {
		 $acl->addResource(new Zend_Acl_Resource('default:dashboard'));
                        $acl->allow('VicePresident', 'default:dashboard', array('index','saveuserdashboard','getwidgtes','upgradeapplication','emailsettings','changepassword','editpassword','update','uploadpreview','viewprofile','viewsettings','savemenuwidgets','getmenuname','fetchmenuname','getnavids','getopeningpositondate','menuwork'));

		 $acl->addResource(new Zend_Acl_Resource('default:employeeattendanceapprovals'));
                            $acl->allow('VicePresident', 'default:employeeattendanceapprovals', array('index','add','edit','view','Manager Employee Requests'));

		 $acl->addResource(new Zend_Acl_Resource('default:index'));
                        $acl->allow('VicePresident', 'default:index', array('index','loginpopupsave','logout','clearsessionarray','forcelogout','browserfailure','sendpassword','updatecontactnumber','getstates','getstatesnormal','getcities','getcitiesnormal','getdepartments','getpositions','gettargetcurrency','calculatedays','calculatebusinessdays','calculatecalendardays','fromdatetodate','fromdatetodateorg','validateorgheadjoiningdate','medicalclaimdates','gettimeformat','chkcurrenttime','popup','createorremoveshortcut','sessiontour','getissuingauthority','setsessionval','checkisactivestatus','updatetheme','welcome','getmultidepts','getmultiemps','getpayrollcomponents','getpayrollattendance','getpayrollformulacomponents','getdownloadayslip','savepayrollcomponents','saveformulafields','getbunitdetails','getpayrollyeardetails','getpayrolldetails','generatepayroll','employeeleavesonleavetypes','generatepayslip','onboardingcandidates','getempsearch'));

		 $acl->addResource(new Zend_Acl_Resource('default:onboardingcandidates'));
                            $acl->allow('VicePresident', 'default:onboardingcandidates', array('index','add','edit','view','On Boarding Candidates'));

		 $acl->addResource(new Zend_Acl_Resource('default:processes'));
                            $acl->allow('VicePresident', 'default:processes', array('index','addpopup','editpopup','viewpopup','savecomments','displaycomments','savefeedback','index','addpopup','editpopup','viewpopup','delete','savecomments','displaycomments','savefeedback'));

		 $acl->addResource(new Zend_Acl_Resource('default:interviewrounds'));
                            $acl->allow('VicePresident', 'default:interviewrounds', array('index','addpopup','editpopup','viewpopup','index','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:empperformanceappraisal'));
                            $acl->allow('VicePresident', 'default:empperformanceappraisal', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:emppayslips'));
                            $acl->allow('VicePresident', 'default:emppayslips', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empbenefits'));
                            $acl->allow('VicePresident', 'default:empbenefits', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:emprequisitiondetails'));
                            $acl->allow('VicePresident', 'default:emprequisitiondetails', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empremunerationdetails'));
                            $acl->allow('VicePresident', 'default:empremunerationdetails', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empsecuritycredentials'));
                            $acl->allow('VicePresident', 'default:empsecuritycredentials', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:apprreqcandidates'));
                            $acl->allow('VicePresident', 'default:apprreqcandidates', array('index','viewpopup','index','viewpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:emppersonaldetails'));
                            $acl->allow('VicePresident', 'default:emppersonaldetails', array('index','view','index','view','edit'));

		 $acl->addResource(new Zend_Acl_Resource('default:employeedocs'));
                            $acl->allow('VicePresident', 'default:employeedocs', array('index','view','save','update','uploadsave','uploaddelete','downloadfiles','index','view','save','delete','edit','update','uploadsave','uploaddelete','downloadfiles'));

		 $acl->addResource(new Zend_Acl_Resource('default:empcommunicationdetails'));
                            $acl->allow('VicePresident', 'default:empcommunicationdetails', array('index','view','index','view','edit'));

		 $acl->addResource(new Zend_Acl_Resource('default:trainingandcertificationdetails'));
                            $acl->allow('VicePresident', 'default:trainingandcertificationdetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:experiencedetails'));
                            $acl->allow('VicePresident', 'default:experiencedetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:educationdetails'));
                            $acl->allow('VicePresident', 'default:educationdetails', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:medicalclaims'));
                            $acl->allow('VicePresident', 'default:medicalclaims', array('index','addpopup','viewpopup','editpopup','view','index','edit','addpopup','viewpopup','editpopup','delete','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empleaves'));
                            $acl->allow('VicePresident', 'default:empleaves', array('index','view','viewpopup','index','edit','view','viewpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:empskills'));
                            $acl->allow('VicePresident', 'default:empskills', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:disabilitydetails'));
                            $acl->allow('VicePresident', 'default:disabilitydetails', array('index','view','index','add','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:workeligibilitydetails'));
                            $acl->allow('VicePresident', 'default:workeligibilitydetails', array('index','view','index','add','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empadditionaldetails'));
                            $acl->allow('VicePresident', 'default:empadditionaldetails', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:visaandimmigrationdetails'));
                            $acl->allow('VicePresident', 'default:visaandimmigrationdetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:creditcarddetails'));
                            $acl->allow('VicePresident', 'default:creditcarddetails', array('index','view','index','add','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:dependencydetails'));
                            $acl->allow('VicePresident', 'default:dependencydetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:empholidays'));
                            $acl->allow('VicePresident', 'default:empholidays', array('index','view','viewpopup','index','edit','view','viewpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:empjobhistory'));
                            $acl->allow('VicePresident', 'default:empjobhistory', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:assetdetails'));
                            $acl->allow('VicePresident', 'default:assetdetails', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empsalarydetails'));
                            $acl->allow('VicePresident', 'default:empsalarydetails', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:logmanager'));
                            $acl->allow('VicePresident', 'default:logmanager', array('index','view','empnamewithidauto','index','view','empnamewithidauto'));

		 $acl->addResource(new Zend_Acl_Resource('default:userloginlog'));
                            $acl->allow('VicePresident', 'default:userloginlog', array('index','empnameauto','empidauto','empipaddressauto','empemailauto','index','empnameauto','empidauto','empipaddressauto','empemailauto'));
}if($role == 11 )
           {
		 $acl->addResource(new Zend_Acl_Resource('default:accountclasstype'));
                            $acl->allow('Managers', 'default:accountclasstype', array('index','addpopup','saveupdate','add','edit','delete','view','Account Class Types'));

		 $acl->addResource(new Zend_Acl_Resource('default:announcements'));
                            $acl->allow('Managers', 'default:announcements', array('index','getdepts','uploadsave','uploaddelete','view','Announcements'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalcategory'));
                            $acl->allow('Managers', 'default:appraisalcategory', array('index','addpopup','getappraisalcategory','add','edit','delete','view','Parameters'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalhistoryself'));
                            $acl->allow('Managers', 'default:appraisalhistoryself', array('index','view','My Appraisal History'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalhistoryteam'));
                            $acl->allow('Managers', 'default:appraisalhistoryteam', array('index','getsearchedempcontent','view','Team Appraisal History'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalinit'));
                            $acl->allow('Managers', 'default:appraisalinit', array('checkappadmin','getdepartmentsadmin','discardsteptwo','displayline','addlinemanager','displayreport','deletelinemanager','deletereportmanager','constructreportacc','constructacc','displayemployees','displaycontentreportacc','displaycontentacc','viewconfmanagers','confmanagers','displaymanagers','displayreportmanagers','getperiod','index','viewassigngroups','assigngroups','displaygroupedemployees','showgroupedemployees','viewgroupedemployees','savegroupedemployeesajax','changesettings','displaysettings','deletegroupedemployees','initializegroup','completeappraisal','checkemployeeresponse','getemployeeslinemanagers','savemngrorghierarchy','getconfiglinemanagers','validateconfig','add','edit','view','Initialize Appraisal'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalmanager'));
                            $acl->allow('Managers', 'default:appraisalmanager', array('submitmanager','deletemanagergroup','savemanagergroup','index','viewgroup','createnewgroup','showgroups','showviewgroups','edit','view','Manager Appraisal'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalquestions'));
                            $acl->allow('Managers', 'default:appraisalquestions', array('index','addpopup','savequestionpopup','add','edit','delete','view','Questions'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalratings'));
                            $acl->allow('Managers', 'default:appraisalratings', array('index','addratings','add','edit','view','Ratings'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalself'));
                            $acl->allow('Managers', 'default:appraisalself', array('index','save','edit','view','Self Appraisal'));

		 $acl->addResource(new Zend_Acl_Resource('default:appraisalskills'));
                            $appraisalskills_add = 'yes';
                                if($this->id_param == '' && $appraisalskills_add == 'yes')
                                    $acl->allow('Managers','default:appraisalskills', array('index','getappraisalskills','saveskillspopup','add','view','Skills','edit'));

                                else
                                    $acl->allow('Managers','default:appraisalskills', array('index','getappraisalskills','saveskillspopup','add','view','Skills'));

                                
		 $acl->addResource(new Zend_Acl_Resource('default:appraisalstatus'));
                            $acl->allow('Managers', 'default:appraisalstatus', array('index','manager','managerstatus','checkappraisalimplementation','employee','employeestatus','employeeActi','addlinemanager','displaymanagers','updatelinemanager','view','Employee Status'));

		 $acl->addResource(new Zend_Acl_Resource('default:approvedrequisitions'));
                            $acl->allow('Managers', 'default:approvedrequisitions', array('index','edit','view','Approved Requisitions'));

		 $acl->addResource(new Zend_Acl_Resource('default:businessunits'));
                            $acl->allow('Managers', 'default:businessunits', array('index','getdeptnames','view','Business Units'));

		 $acl->addResource(new Zend_Acl_Resource('default:candidatedetails'));
                            $acl->allow('Managers', 'default:candidatedetails', array('index','chkcandidate','uploadfile','deleteresume','download','multipleresume','add','edit','delete','view','CV Management'));

		 $acl->addResource(new Zend_Acl_Resource('default:categories'));
                            $acl->allow('Managers', 'default:categories', array('index','addnewcategory','view','Categories'));

		 $acl->addResource(new Zend_Acl_Resource('default:currency'));
                            $acl->allow('Managers', 'default:currency', array('index','addpopup','gettargetcurrency','add','edit','delete','view','Currencies'));

		 $acl->addResource(new Zend_Acl_Resource('default:currencyconverter'));
                            $acl->allow('Managers', 'default:currencyconverter', array('index','add','edit','delete','view','Currency Conversions'));

		 $acl->addResource(new Zend_Acl_Resource('default:dashboard'));
                        $acl->allow('Managers', 'default:dashboard', array('index','saveuserdashboard','getwidgtes','upgradeapplication','emailsettings','changepassword','editpassword','update','uploadpreview','viewprofile','viewsettings','savemenuwidgets','getmenuname','fetchmenuname','getnavids','getopeningpositondate','menuwork'));

		 $acl->addResource(new Zend_Acl_Resource('default:departments'));
                            $acl->allow('Managers', 'default:departments', array('index','viewpopup','editpopup','getdepartments','getempnames','view','Departments'));

		 $acl->addResource(new Zend_Acl_Resource('default:emailcontacts'));
                            $acl->allow('Managers', 'default:emailcontacts', array('index','getgroupoptions','getmailcnt','add','edit','delete','view','Email Contacts'));

		 $acl->addResource(new Zend_Acl_Resource('default:employee'));
                            $acl->allow('Managers', 'default:employee', array('getemprequi','index','changeorghead','getdepartments','getpositions','getempreportingmanagers','makeactiveinactive','changereportingmanager','addemppopup','uploadexcel','getindividualempdetails','view','Employees'));

		 $acl->addResource(new Zend_Acl_Resource('default:ethniccode'));
                            $acl->allow('Managers', 'default:ethniccode', array('index','saveupdate','addpopup','add','edit','delete','view','Ethnic Codes'));

		 $acl->addResource(new Zend_Acl_Resource('default:feedforwardemployee'));
                            $acl->allow('Managers', 'default:feedforwardemployee', array('index','save','edit','view','Appraise Your Manager'));

		 $acl->addResource(new Zend_Acl_Resource('default:feedforwardinit'));
                            $acl->allow('Managers', 'default:feedforwardinit', array('index','getappraisaldetails','add','edit','view','Initialize Feedforward'));

		 $acl->addResource(new Zend_Acl_Resource('default:feedforwardmanager'));
                            $acl->allow('Managers', 'default:feedforwardmanager', array('index','getmanagersratings','getdetailedratings','getdetailedratingsbyemp','getdetailedratingsbyques','view','Manager Feedforward'));

		 $acl->addResource(new Zend_Acl_Resource('default:feedforwardquestions'));
                            $acl->allow('Managers', 'default:feedforwardquestions', array('index','savepopup','add','edit','delete','view','Questions'));

		 $acl->addResource(new Zend_Acl_Resource('default:feedforwardstatus'));
                            $acl->allow('Managers', 'default:feedforwardstatus', array('index','getffstatusemps','getfeedforwardstatus','view','Employee Status'));

		 $acl->addResource(new Zend_Acl_Resource('default:gender'));
                            $acl->allow('Managers', 'default:gender', array('index','saveupdate','addpopup','add','edit','delete','view','Gender'));

		 $acl->addResource(new Zend_Acl_Resource('default:identitycodes'));
                            $acl->allow('Managers', 'default:identitycodes', array('index','addpopup','edit','Identity Codes'));

		 $acl->addResource(new Zend_Acl_Resource('default:index'));
                        $acl->allow('Managers', 'default:index', array('index','loginpopupsave','logout','clearsessionarray','forcelogout','browserfailure','sendpassword','updatecontactnumber','getstates','getstatesnormal','getcities','getcitiesnormal','getdepartments','getpositions','gettargetcurrency','calculatedays','calculatebusinessdays','calculatecalendardays','fromdatetodate','fromdatetodateorg','validateorgheadjoiningdate','medicalclaimdates','gettimeformat','chkcurrenttime','popup','createorremoveshortcut','sessiontour','getissuingauthority','setsessionval','checkisactivestatus','updatetheme','welcome','getmultidepts','getmultiemps','getpayrollcomponents','getpayrollattendance','getpayrollformulacomponents','getdownloadayslip','savepayrollcomponents','saveformulafields','getbunitdetails','getpayrollyeardetails','getpayrolldetails','generatepayroll','employeeleavesonleavetypes','generatepayslip','onboardingcandidates','getempsearch'));

		 $acl->addResource(new Zend_Acl_Resource('default:leaverequest'));
                            $leaverequest_add = 'yes';
                                if($this->id_param == '' && $leaverequest_add == 'yes')
                                    $acl->allow('Managers','default:leaverequest', array('index','saveleaverequestdetails','gethalfdaydetails','editpopup','updateleavedetails','add','Leave Request','edit'));

                                else
                                    $acl->allow('Managers','default:leaverequest', array('index','saveleaverequestdetails','gethalfdaydetails','editpopup','updateleavedetails','add','Leave Request'));

                                
		 $acl->addResource(new Zend_Acl_Resource('default:licensetype'));
                            $acl->allow('Managers', 'default:licensetype', array('index','add','edit','delete','view','License Types'));

		 $acl->addResource(new Zend_Acl_Resource('default:manageremployeevacations'));
                            $acl->allow('Managers', 'default:manageremployeevacations', array('index','edit','view','Manager Employee Leaves'));

		 $acl->addResource(new Zend_Acl_Resource('default:maritalstatus'));
                            $acl->allow('Managers', 'default:maritalstatus', array('index','saveupdate','addpopup','add','edit','delete','view','Marital Status'));

		 $acl->addResource(new Zend_Acl_Resource('default:mydetails'));
                            $acl->allow('Managers', 'default:mydetails', array('index','personaldetailsview','personal','communicationdetailsview','communication','skills','education','experience','leaves','holidays','salarydetailsview','certification','creditcarddetailsview','creditcard','visadetailsview','visa','medicalclaims','disabilitydetailsview','disability','dependency','workeligibilitydetailsview','workeligibility','additionaldetailsedit','jobhistory','documents','assetdetailsview','add','edit','delete','view','My Details'));

		 $acl->addResource(new Zend_Acl_Resource('default:myemployees'));
                            $acl->allow('Managers', 'default:myemployees', array('index','perview','comview','skillsview','expview','eduview','trainingview','additionaldetailsview','jobhistoryview','skillsedit','jobhistoryedit','expedit','eduedit','trainingedit','additionaldetailsedit','peredit','comedit','docview','docedit','employeereport','getempreportdata','empauto','emprptpdf','exportemployeereport','downloadreport','view','My Team'));

		 $acl->addResource(new Zend_Acl_Resource('default:myholidaycalendar'));
                            $acl->allow('Managers', 'default:myholidaycalendar', array('index','view','My Holiday Calendar'));

		 $acl->addResource(new Zend_Acl_Resource('default:myteamappraisal'));
                            $acl->allow('Managers', 'default:myteamappraisal', array('savelineresponse','savemngresponse','getempcontent','index','getsearchedempcontent','getsearchedstatus','downloadpdf','downloadUploadedFile','My Team Appraisal'));

		 $acl->addResource(new Zend_Acl_Resource('default:nationality'));
                            $acl->allow('Managers', 'default:nationality', array('index','addpopup','add','edit','delete','view','Nationalities'));

		 $acl->addResource(new Zend_Acl_Resource('default:nationalitycontextcode'));
                            $acl->allow('Managers', 'default:nationalitycontextcode', array('index','add','edit','delete','view','Nationality Context Codes'));

		 $acl->addResource(new Zend_Acl_Resource('default:numberformats'));
                            $acl->allow('Managers', 'default:numberformats', array('index','add','edit','delete','view','Number Formats'));

		 $acl->addResource(new Zend_Acl_Resource('default:organisationinfo'));
                            $acl->allow('Managers', 'default:organisationinfo', array('index','edit_old','saveupdate','uploadpreview','validateorgstartdate','getcompleteorgdata','addorghead','view','Organization Info'));

		 $acl->addResource(new Zend_Acl_Resource('default:payrollattendancereport'));
                            $acl->allow('Managers', 'default:payrollattendancereport', array('index','addpopup','getrequests','view','Attendance Report'));

		 $acl->addResource(new Zend_Acl_Resource('default:payrollattendancerequest'));
                            $payrollattendancerequest_add = 'yes';
                                if($this->id_param == '' && $payrollattendancerequest_add == 'yes')
                                    $acl->allow('Managers','default:payrollattendancerequest', array('index','addpopup','getrequests','add','view','Attendance Request','edit'));

                                else
                                    $acl->allow('Managers','default:payrollattendancerequest', array('index','addpopup','getrequests','add','view','Attendance Request'));

                                
		 $acl->addResource(new Zend_Acl_Resource('default:pendingleaves'));
                            $acl->allow('Managers', 'default:pendingleaves', array('index','delete','view','My Leaves'));

		 $acl->addResource(new Zend_Acl_Resource('default:policydocuments'));
                            $acl->allow('Managers', 'default:policydocuments', array('index','uploaddoc','deletedocument','addmultiple','uploadmultipledocs','view','View/Manage Policy Documents'));

		 $acl->addResource(new Zend_Acl_Resource('default:prefix'));
                            $acl->allow('Managers', 'default:prefix', array('index','saveupdate','addpopup','add','edit','delete','view','Prefixes'));

		 $acl->addResource(new Zend_Acl_Resource('default:racecode'));
                            $acl->allow('Managers', 'default:racecode', array('index','saveupdate','addpopup','add','edit','delete','view','Race Codes'));

		 $acl->addResource(new Zend_Acl_Resource('default:rejectedrequisitions'));
                            $acl->allow('Managers', 'default:rejectedrequisitions', array('index','view','Rejected Requisitions'));

		 $acl->addResource(new Zend_Acl_Resource('default:reports'));
                            $acl->allow('Managers', 'default:reports', array('getrolepopup','emprolesgrouppopup','performancereport','previousappraisals','getselectedappraisaldata','getinterviewroundsdata','interviewrounds','rolesgroup','exportemprolesgroup','exportrolesgroupreport','exportinterviewrpt','exportactiveuserrpt','exportpayrollrpt','exportemployeereport','rolesgrouprptpdf','activeuserrptpdf','emprptpdf','interviewrptpdf','rolesgroupdata','emprolesgroup','emprolesgroupdata','activeuser','getactiveuserdata','getattendancereportsdata','getpayrollreportsdata','getempreportdata','empauto','servicedeskreport','getsddata','servicedeskpdf','servicedeskexcel','employeereport','getdeptsemp','index','holidaygroupreports','getpdfreportholiday','getexcelreportholiday','payrollreport','attendancereport','leavesreport','getpdfreportleaves','getexcelreportleaves','leavesreporttabheader','leavemanagementreport','getpdfreportleavemanagement','getexcelreportleavemanagement','bunitauto','bunitcodeauto','getexcelreportbusinessunit','getbusinessunitspdf','businessunits','userlogreport','departments','exportdepartmentpdf','getexcelreportdepartment','candidaterptexcel','candidaterptpdf','getcandidatesreportdata','candidatesreport','requisitionauto','requisitionrptexcel','requisitionrptpdf','getrequisitionsstatusreportdata','requisitionstatusreport','activitylogreport','downloadreport','agencylistreport','agencynameauto','agencysebsiteauto','empscreening','getspecimennames','getagencynames','getexcelreportempscreening','getempscreeningpdf','Analytics'));

		 $acl->addResource(new Zend_Acl_Resource('default:requisition'));
                            $acl->allow('Managers', 'default:requisition', array('index','viewhr','approverequisition','addcandidate','interview','getdepartments','getpositions','viewpopup','getapprreqdata','chkreqforclose','getempreportingmanagers','getemailcount','getapprovers','add','edit','delete','view','Openings/Positions'));

		 $acl->addResource(new Zend_Acl_Resource('default:scheduleinterviews'));
                            $acl->allow('Managers', 'default:scheduleinterviews', array('candidatepopup','index','downloadresume','getcandidates','add','edit','delete','view','Scheduled Interviews'));

		 $acl->addResource(new Zend_Acl_Resource('default:servicedeskconf'));
                            $acl->allow('Managers', 'default:servicedeskconf', array('index','getemployees','getapprover','getbunitimplementation','getassets','add','edit','delete','view','Settings'));

		 $acl->addResource(new Zend_Acl_Resource('default:servicedeskdepartment'));
                            $acl->allow('Managers', 'default:servicedeskdepartment', array('index','addpopup','getrequests','add','edit','delete','view','Categories'));

		 $acl->addResource(new Zend_Acl_Resource('default:servicedeskrequest'));
                            $acl->allow('Managers', 'default:servicedeskrequest', array('index','add','edit','delete','view','Request Types'));

		 $acl->addResource(new Zend_Acl_Resource('default:shortlistedcandidates'));
                            $acl->allow('Managers', 'default:shortlistedcandidates', array('index','edit','view','Shortlisted & Selected Candidates'));

		 $acl->addResource(new Zend_Acl_Resource('default:timezone'));
                            $acl->allow('Managers', 'default:timezone', array('index','saveupdate','addpopup','add','edit','delete','view','Time Zones'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:advances'));
                            $acl->allow('Managers', 'expenses:advances', array('index','getprojects','myadvances','viewmoreadvances','clearadvancesdata','addreturnpopup','Advances'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:employeeadvances'));
                            $acl->allow('Managers', 'expenses:employeeadvances', array('index','view','Employee Advances'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:expenses'));
                            $acl->allow('Managers', 'expenses:expenses', array('index','clone','view','addpopup','uploadsave','uploaddelete','displayreceipts','addtrippopup','submitexpense','addreceiptimage','expensestatus','listreportingmangers','viewmoremanagers','forwardexpenseto','downloadexpensepdf','bulkexpenses','getcategories','getprojects','getcurrency','uploadedfiles','Expenses'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:myemployeeexpenses'));
                            $acl->allow('Managers', 'expenses:myemployeeexpenses', array('index','view','My Employee Expenses'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:receipts'));
                            $acl->allow('Managers', 'expenses:receipts', array('index','downloadreceipt','downloadexpensereceipt','deletereceipt','uploadsave','displayreceipts','viewmorereceipts','listexpenses','addreceipttoexpense','viewmoreexpenses','cleardata','showreceiptspopup','listtrips','viewmoretrips','addexpensetotrip','Receipts'));

		 $acl->addResource(new Zend_Acl_Resource('expenses:trips'));
                            $acl->allow('Managers', 'expenses:trips', array('index','view','addpopup','tripstatus','deleteexpense','downloadtrippdf','Trips'));

		 $acl->addResource(new Zend_Acl_Resource('default:processes'));
                            $acl->allow('Managers', 'default:processes', array('index','addpopup','editpopup','viewpopup','savecomments','displaycomments','savefeedback','index','addpopup','editpopup','viewpopup','delete','savecomments','displaycomments','savefeedback'));

		 $acl->addResource(new Zend_Acl_Resource('default:interviewrounds'));
                            $acl->allow('Managers', 'default:interviewrounds', array('index','addpopup','editpopup','viewpopup','index','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:empperformanceappraisal'));
                            $acl->allow('Managers', 'default:empperformanceappraisal', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:emppayslips'));
                            $acl->allow('Managers', 'default:emppayslips', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empbenefits'));
                            $acl->allow('Managers', 'default:empbenefits', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:emprequisitiondetails'));
                            $acl->allow('Managers', 'default:emprequisitiondetails', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empremunerationdetails'));
                            $acl->allow('Managers', 'default:empremunerationdetails', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empsecuritycredentials'));
                            $acl->allow('Managers', 'default:empsecuritycredentials', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:apprreqcandidates'));
                            $acl->allow('Managers', 'default:apprreqcandidates', array('index','viewpopup','index','viewpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:emppersonaldetails'));
                            $acl->allow('Managers', 'default:emppersonaldetails', array('index','view','index','view','edit'));

		 $acl->addResource(new Zend_Acl_Resource('default:employeedocs'));
                            $acl->allow('Managers', 'default:employeedocs', array('index','view','save','update','uploadsave','uploaddelete','downloadfiles','index','view','save','delete','edit','update','uploadsave','uploaddelete','downloadfiles'));

		 $acl->addResource(new Zend_Acl_Resource('default:empcommunicationdetails'));
                            $acl->allow('Managers', 'default:empcommunicationdetails', array('index','view','index','view','edit'));

		 $acl->addResource(new Zend_Acl_Resource('default:trainingandcertificationdetails'));
                            $acl->allow('Managers', 'default:trainingandcertificationdetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:experiencedetails'));
                            $acl->allow('Managers', 'default:experiencedetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:educationdetails'));
                            $acl->allow('Managers', 'default:educationdetails', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:medicalclaims'));
                            $acl->allow('Managers', 'default:medicalclaims', array('index','addpopup','viewpopup','editpopup','view','index','edit','addpopup','viewpopup','editpopup','delete','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empleaves'));
                            $acl->allow('Managers', 'default:empleaves', array('index','view','viewpopup','index','edit','view','viewpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:empskills'));
                            $acl->allow('Managers', 'default:empskills', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:disabilitydetails'));
                            $acl->allow('Managers', 'default:disabilitydetails', array('index','view','index','add','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:workeligibilitydetails'));
                            $acl->allow('Managers', 'default:workeligibilitydetails', array('index','view','index','add','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empadditionaldetails'));
                            $acl->allow('Managers', 'default:empadditionaldetails', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:visaandimmigrationdetails'));
                            $acl->allow('Managers', 'default:visaandimmigrationdetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:creditcarddetails'));
                            $acl->allow('Managers', 'default:creditcarddetails', array('index','view','index','add','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:dependencydetails'));
                            $acl->allow('Managers', 'default:dependencydetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:empholidays'));
                            $acl->allow('Managers', 'default:empholidays', array('index','view','viewpopup','index','edit','view','viewpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:empjobhistory'));
                            $acl->allow('Managers', 'default:empjobhistory', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:assetdetails'));
                            $acl->allow('Managers', 'default:assetdetails', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empsalarydetails'));
                            $acl->allow('Managers', 'default:empsalarydetails', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:logmanager'));
                            $acl->allow('Managers', 'default:logmanager', array('index','view','empnamewithidauto','index','view','empnamewithidauto'));

		 $acl->addResource(new Zend_Acl_Resource('default:userloginlog'));
                            $acl->allow('Managers', 'default:userloginlog', array('index','empnameauto','empidauto','empipaddressauto','empemailauto','index','empnameauto','empidauto','empipaddressauto','empemailauto'));
}if($role == 13 )
           {
		 $acl->addResource(new Zend_Acl_Resource('default:dashboard'));
                        $acl->allow('finance', 'default:dashboard', array('index','saveuserdashboard','getwidgtes','upgradeapplication','emailsettings','changepassword','editpassword','update','uploadpreview','viewprofile','viewsettings','savemenuwidgets','getmenuname','fetchmenuname','getnavids','getopeningpositondate','menuwork'));

		 $acl->addResource(new Zend_Acl_Resource('default:employeeattendanceapprovals'));
                            $acl->allow('finance', 'default:employeeattendanceapprovals', array('index','add','edit','view','Manager Employee Requests'));

		 $acl->addResource(new Zend_Acl_Resource('default:hrwizard'));
                            $acl->allow('finance', 'default:hrwizard', array('index','configureleavetypes','configureholidays','saveholidaygroup','configureperformanceappraisal','savecategory','updatewizardcompletion','index','configureleavetypes','configureholidays','saveholidaygroup','configureperformanceappraisal','savecategory','updatewizardcompletion'));

		 $acl->addResource(new Zend_Acl_Resource('default:index'));
                        $acl->allow('finance', 'default:index', array('index','loginpopupsave','logout','clearsessionarray','forcelogout','browserfailure','sendpassword','updatecontactnumber','getstates','getstatesnormal','getcities','getcitiesnormal','getdepartments','getpositions','gettargetcurrency','calculatedays','calculatebusinessdays','calculatecalendardays','fromdatetodate','fromdatetodateorg','validateorgheadjoiningdate','medicalclaimdates','gettimeformat','chkcurrenttime','popup','createorremoveshortcut','sessiontour','getissuingauthority','setsessionval','checkisactivestatus','updatetheme','welcome','getmultidepts','getmultiemps','getpayrollcomponents','getpayrollattendance','getpayrollformulacomponents','getdownloadayslip','savepayrollcomponents','saveformulafields','getbunitdetails','getpayrollyeardetails','getpayrolldetails','generatepayroll','employeeleavesonleavetypes','generatepayslip','onboardingcandidates','getempsearch'));

		 $acl->addResource(new Zend_Acl_Resource('default:onboardingcandidates'));
                            $acl->allow('finance', 'default:onboardingcandidates', array('index','add','edit','view','On Boarding Candidates'));

		 $acl->addResource(new Zend_Acl_Resource('default:processes'));
                            $acl->allow('finance', 'default:processes', array('index','addpopup','editpopup','viewpopup','savecomments','displaycomments','savefeedback','index','addpopup','editpopup','viewpopup','delete','savecomments','displaycomments','savefeedback'));

		 $acl->addResource(new Zend_Acl_Resource('default:interviewrounds'));
                            $acl->allow('finance', 'default:interviewrounds', array('index','addpopup','editpopup','viewpopup','index','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:empperformanceappraisal'));
                            $acl->allow('finance', 'default:empperformanceappraisal', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:emppayslips'));
                            $acl->allow('finance', 'default:emppayslips', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empbenefits'));
                            $acl->allow('finance', 'default:empbenefits', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:emprequisitiondetails'));
                            $acl->allow('finance', 'default:emprequisitiondetails', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empremunerationdetails'));
                            $acl->allow('finance', 'default:empremunerationdetails', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empsecuritycredentials'));
                            $acl->allow('finance', 'default:empsecuritycredentials', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:apprreqcandidates'));
                            $acl->allow('finance', 'default:apprreqcandidates', array('index','viewpopup','index','viewpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:emppersonaldetails'));
                            $acl->allow('finance', 'default:emppersonaldetails', array('index','view','index','view','edit'));

		 $acl->addResource(new Zend_Acl_Resource('default:employeedocs'));
                            $acl->allow('finance', 'default:employeedocs', array('index','view','save','update','uploadsave','uploaddelete','downloadfiles','index','view','save','delete','edit','update','uploadsave','uploaddelete','downloadfiles'));

		 $acl->addResource(new Zend_Acl_Resource('default:empcommunicationdetails'));
                            $acl->allow('finance', 'default:empcommunicationdetails', array('index','view','index','view','edit'));

		 $acl->addResource(new Zend_Acl_Resource('default:trainingandcertificationdetails'));
                            $acl->allow('finance', 'default:trainingandcertificationdetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:experiencedetails'));
                            $acl->allow('finance', 'default:experiencedetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:educationdetails'));
                            $acl->allow('finance', 'default:educationdetails', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:medicalclaims'));
                            $acl->allow('finance', 'default:medicalclaims', array('index','addpopup','viewpopup','editpopup','view','index','edit','addpopup','viewpopup','editpopup','delete','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empleaves'));
                            $acl->allow('finance', 'default:empleaves', array('index','view','viewpopup','index','edit','view','viewpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:empskills'));
                            $acl->allow('finance', 'default:empskills', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:disabilitydetails'));
                            $acl->allow('finance', 'default:disabilitydetails', array('index','view','index','add','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:workeligibilitydetails'));
                            $acl->allow('finance', 'default:workeligibilitydetails', array('index','view','index','add','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empadditionaldetails'));
                            $acl->allow('finance', 'default:empadditionaldetails', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:visaandimmigrationdetails'));
                            $acl->allow('finance', 'default:visaandimmigrationdetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:creditcarddetails'));
                            $acl->allow('finance', 'default:creditcarddetails', array('index','view','index','add','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:dependencydetails'));
                            $acl->allow('finance', 'default:dependencydetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:empholidays'));
                            $acl->allow('finance', 'default:empholidays', array('index','view','viewpopup','index','edit','view','viewpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:empjobhistory'));
                            $acl->allow('finance', 'default:empjobhistory', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:assetdetails'));
                            $acl->allow('finance', 'default:assetdetails', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empsalarydetails'));
                            $acl->allow('finance', 'default:empsalarydetails', array('index','view','index','edit','view'));
}if($role == 14 )
           {
		 $acl->addResource(new Zend_Acl_Resource('default:businessunits'));
                            $acl->allow('OnBoarding', 'default:businessunits', array('index','getdeptnames','add','edit','delete','view','Business Units'));

		 $acl->addResource(new Zend_Acl_Resource('default:dashboard'));
                        $acl->allow('OnBoarding', 'default:dashboard', array('index','saveuserdashboard','getwidgtes','upgradeapplication','emailsettings','changepassword','editpassword','update','uploadpreview','viewprofile','viewsettings','savemenuwidgets','getmenuname','fetchmenuname','getnavids','getopeningpositondate','menuwork'));

		 $acl->addResource(new Zend_Acl_Resource('default:index'));
                        $acl->allow('OnBoarding', 'default:index', array('index','loginpopupsave','logout','clearsessionarray','forcelogout','browserfailure','sendpassword','updatecontactnumber','getstates','getstatesnormal','getcities','getcitiesnormal','getdepartments','getpositions','gettargetcurrency','calculatedays','calculatebusinessdays','calculatecalendardays','fromdatetodate','fromdatetodateorg','validateorgheadjoiningdate','medicalclaimdates','gettimeformat','chkcurrenttime','popup','createorremoveshortcut','sessiontour','getissuingauthority','setsessionval','checkisactivestatus','updatetheme','welcome','getmultidepts','getmultiemps','getpayrollcomponents','getpayrollattendance','getpayrollformulacomponents','getdownloadayslip','savepayrollcomponents','saveformulafields','getbunitdetails','getpayrollyeardetails','getpayrolldetails','generatepayroll','employeeleavesonleavetypes','generatepayslip','onboardingcandidates','getempsearch'));

		 $acl->addResource(new Zend_Acl_Resource('default:mydetails'));
                            $acl->allow('OnBoarding', 'default:mydetails', array('index','personaldetailsview','personal','communicationdetailsview','communication','skills','education','experience','leaves','holidays','salarydetailsview','certification','creditcarddetailsview','creditcard','visadetailsview','visa','medicalclaims','disabilitydetailsview','disability','dependency','workeligibilitydetailsview','workeligibility','additionaldetailsedit','jobhistory','documents','assetdetailsview','add','edit','delete','view','My Details'));

		 $acl->addResource(new Zend_Acl_Resource('default:processes'));
                            $acl->allow('OnBoarding', 'default:processes', array('index','addpopup','editpopup','viewpopup','savecomments','displaycomments','savefeedback','index','addpopup','editpopup','viewpopup','delete','savecomments','displaycomments','savefeedback'));

		 $acl->addResource(new Zend_Acl_Resource('default:interviewrounds'));
                            $acl->allow('OnBoarding', 'default:interviewrounds', array('index','addpopup','editpopup','viewpopup','index','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:empperformanceappraisal'));
                            $acl->allow('OnBoarding', 'default:empperformanceappraisal', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:emppayslips'));
                            $acl->allow('OnBoarding', 'default:emppayslips', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empbenefits'));
                            $acl->allow('OnBoarding', 'default:empbenefits', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:emprequisitiondetails'));
                            $acl->allow('OnBoarding', 'default:emprequisitiondetails', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empremunerationdetails'));
                            $acl->allow('OnBoarding', 'default:empremunerationdetails', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empsecuritycredentials'));
                            $acl->allow('OnBoarding', 'default:empsecuritycredentials', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:apprreqcandidates'));
                            $acl->allow('OnBoarding', 'default:apprreqcandidates', array('index','viewpopup','index','viewpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:emppersonaldetails'));
                            $acl->allow('OnBoarding', 'default:emppersonaldetails', array('index','view','index','view','edit'));

		 $acl->addResource(new Zend_Acl_Resource('default:employeedocs'));
                            $acl->allow('OnBoarding', 'default:employeedocs', array('index','view','save','update','uploadsave','uploaddelete','downloadfiles','index','view','save','delete','edit','update','uploadsave','uploaddelete','downloadfiles'));

		 $acl->addResource(new Zend_Acl_Resource('default:empcommunicationdetails'));
                            $acl->allow('OnBoarding', 'default:empcommunicationdetails', array('index','view','index','view','edit'));

		 $acl->addResource(new Zend_Acl_Resource('default:trainingandcertificationdetails'));
                            $acl->allow('OnBoarding', 'default:trainingandcertificationdetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:experiencedetails'));
                            $acl->allow('OnBoarding', 'default:experiencedetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:educationdetails'));
                            $acl->allow('OnBoarding', 'default:educationdetails', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:medicalclaims'));
                            $acl->allow('OnBoarding', 'default:medicalclaims', array('index','addpopup','viewpopup','editpopup','view','index','edit','addpopup','viewpopup','editpopup','delete','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empleaves'));
                            $acl->allow('OnBoarding', 'default:empleaves', array('index','view','viewpopup','index','edit','view','viewpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:empskills'));
                            $acl->allow('OnBoarding', 'default:empskills', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:disabilitydetails'));
                            $acl->allow('OnBoarding', 'default:disabilitydetails', array('index','view','index','add','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:workeligibilitydetails'));
                            $acl->allow('OnBoarding', 'default:workeligibilitydetails', array('index','view','index','add','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empadditionaldetails'));
                            $acl->allow('OnBoarding', 'default:empadditionaldetails', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:visaandimmigrationdetails'));
                            $acl->allow('OnBoarding', 'default:visaandimmigrationdetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:creditcarddetails'));
                            $acl->allow('OnBoarding', 'default:creditcarddetails', array('index','view','index','add','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:dependencydetails'));
                            $acl->allow('OnBoarding', 'default:dependencydetails', array('index','view','addpopup','editpopup','viewpopup','index','edit','view','addpopup','editpopup','viewpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:empholidays'));
                            $acl->allow('OnBoarding', 'default:empholidays', array('index','view','viewpopup','index','edit','view','viewpopup'));

		 $acl->addResource(new Zend_Acl_Resource('default:empjobhistory'));
                            $acl->allow('OnBoarding', 'default:empjobhistory', array('index','view','addpopup','viewpopup','editpopup','index','edit','view','addpopup','viewpopup','editpopup','delete'));

		 $acl->addResource(new Zend_Acl_Resource('default:assetdetails'));
                            $acl->allow('OnBoarding', 'default:assetdetails', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:empsalarydetails'));
                            $acl->allow('OnBoarding', 'default:empsalarydetails', array('index','view','index','edit','view'));

		 $acl->addResource(new Zend_Acl_Resource('default:logmanager'));
                            $acl->allow('OnBoarding', 'default:logmanager', array('index','view','empnamewithidauto','index','view','empnamewithidauto'));

		 $acl->addResource(new Zend_Acl_Resource('default:userloginlog'));
                            $acl->allow('OnBoarding', 'default:userloginlog', array('index','empnameauto','empidauto','empipaddressauto','empemailauto','index','empnameauto','empidauto','empipaddressauto','empemailauto'));
}

     // setup acl in the registry for more
           Zend_Registry::set('acl', $acl);
           $this->_acl = $acl;
    }
   return $this->_acl;
}
  }
  
  ?>