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
 * @Name   Clients Controller
 *
 * @description
 *
 * This Client controller contain actions related to client.
 *
 * 1. Display all client details.
 * 2. Save or Update Client details.
 * 3. Delete Client.
 * 4. View Client details.
 *
 * @author sagarsoft
 * @version 1.0
 */
class Timemanagement_ExporttimesheetController extends Zend_Controller_Action
{
	private $options;

	/**
	 * The default action - show the home page
	 */
	public function preDispatch()
	{
		/*$userModel = new Timemanagement_Model_Users();
		$checkTmEnable = $userModel->checkTmEnable();

		if(!$checkTmEnable){
			$this->_redirect('error');
		}*/
		
		//check Time management module enable
		if(!sapp_Helper::checkTmEnable())
			$this->_redirect('error');

	}

	public function init()
	{
		$this->_options= $this->getInvokeArg('bootstrap')->getOptions();

	}

	/**
	 * This method will display all the client details in grid format.
	 */
	public function indexAction()
	{        $auth = Zend_Auth::getInstance();
               if($auth->hasIdentity()){
			 $loginUserId = $auth->getStorage()->read()->id;
		}
		$clientsModel = new Timemanagement_Model_Exporttimesheet();
		$call = $this->_getParam('call');

		if($call == 'ajaxcall')
		$this->_helper->layout->disableLayout();
		$view = Zend_Layout::getMvcInstance()->getView();
		$objname = $this->_getParam('objname');
		$refresh = $this->_getParam('refresh');
		$dashboardcall = $this->_getParam('dashboardcall');

		$data = array();
		$searchQuery = '';
		$searchArray = array();
		$tablecontent='';
               
		if($refresh == 'refresh')
		{
			if($dashboardcall == 'Yes')
			$perPage = DASHBOARD_PERPAGE;
			else
			$perPage = PERPAGE;
			$sort = 'DESC';$by = 'modified';$pageNo = 1;$searchData = '';$searchQuery = '';$searchArray='';
		}
		else
		{
                    
			$sort = ($this->_getParam('sort') !='')? $this->_getParam('sort'):'DESC';
			$by = ($this->_getParam('by')!='')? $this->_getParam('by'):'modified';
			if($dashboardcall == 'Yes')
			$perPage = $this->_getParam('per_page',DASHBOARD_PERPAGE);
			else
			$perPage = $this->_getParam('per_page',PERPAGE);

			$pageNo = $this->_getParam('page', 1);
			/** search from grid - START **/
			$searchData = $this->_getParam('searchData');
			$searchData = rtrim($searchData,',');
			/** search from grid - END **/
		}
              
		//$dataTmp = $clientsModel->getGrid($sort, $by, $perPage, $pageNo, $searchData,$call,$dashboardcall);
		$dataTmp = $clientsModel->getGrid($sort, $by, $perPage, $pageNo, $searchData,$call,$dashboardcall,$loginUserId);	
		array_push($data,$dataTmp);
		$this->view->dataArray = $data;
		$this->view->call = $call ;
		$this->view->messages = $this->_helper->flashMessenger->getMessages();
	}
}    