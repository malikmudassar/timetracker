<?php
/**
 * Created by PhpStorm.
 * User: sun rise
 * Date: 11/22/2016
 * Time: 1:31 PM
 */
class Manager extends CI_Controller {
    function __construct()
    {
        parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper(array('form', 'url'));
        $this->load->model('admin_model');
        $this->load->model('manager_model');
        $this->load->model('team_model');
		$this->load->library('My_PHPMailer');
    }

    public function index()
    {
        if($this->isLoggedIn())
        {
            $data['menu']=$this->manager_model->getMenuItems();
            $data['counters']=$this->admin_model->getCounters();
            $data['feedback']=$this->team_model->getFeedbacks();
            $data['manager_feedback']=$this->manager_model->getManagerFeedbacks();
            $data['task_logs']=$this->admin_model->getAllLogs();
            //echo '<pre>';print_r($data);exit;
            $data['title']='SmartBABA ERP';
            $this->load->view('static/head',$data);
            $this->load->view('static/header');
            $this->load->view('static/sidebar');
            $this->load->view('admin/dashboard');
            $this->load->view('static/footer');
        }
        else
        {
            redirect(base_url());
        }

    }

    public function isLoggedIn()
    {
        if(!empty($this->session->userdata['id'])&& $this->session->userdata['type']=='manager')
        {
            return true;
        }
        else
        {
            return false;
        }
    }
	    /////////////////////////////////
    ///     PROFILE STARTS        ///
    /////////////////////////////////
    public function edit_profile()
    {
        if($this->isLoggedIn())
        {
			$data['menu']=$this->manager_model->getMenuItems();
			$email=$this->session->userdata['email'];
			$data['profile']=$this->manager_model->getManagerData($email);
			if($_POST)
            {
				$_POST['name']=$this->session->userdata['name'];
				$_POST['email']=$this->session->userdata['email'];
				$this->manager_model->updateManager($data['profile'][0]['id'],$_POST);
				$data['success']='Congratulations! Manager Updated Successfully';
				$data['title']='SmartBABA ERP';
				unset($_SESSION['name']);
				$send_data['name']=$_POST['name'];
				$this->session->set_userdata($send_data);
				$data['profile']=$this->manager_model->getManagerData($email);
				$this->load->view('static/head',$data);
				$this->load->view('static/header');
				$this->load->view('static/sidebar');
				$this->load->view('manager/edit_profile',$data);
				$this->load->view('static/footer');
                
            }
            else
            {
                $data['title']='SmartBABA ERP';
				$this->load->view('static/head',$data);
				$this->load->view('static/header');
				$this->load->view('static/sidebar');
				$this->load->view('manager/edit_profile',$data);
				$this->load->view('static/footer');
            }
        }
        else
        {
            redirect(base_url());
        }

    }
    public function profile_image()
    { 
		$data['menu']=$this->manager_model->getMenuItems();
		$email=$this->session->userdata['email'];
		$data['client_Id']=$this->manager_model->getUserId($email);
		$data['pic']=$this->manager_model->getImage($data['client_Id']['id']);
		/*  echo('<pre>');
		print_r($email);
		print_r($data['client_Id']);
		print_r($data['pic']);
		die;   */
		$data['title']='SmartBABA ERP';
		$this->load->view('static/head',$data);
		$this->load->view('static/header');
		$this->load->view('static/sidebar');
		$this->load->view('manager/profile_image',$data);
		$this->load->view('static/footer');
		
    }
	/////////////////////////////////
    ///     IMAGE UPLOAD          ///
    /////////////////////////////////
	 public function do_upload()
	{
		$config['upload_path']          = './uploads/';
		$config['allowed_types']        = 'gif|jpg|png';
		$config['max_size']             = 100;
		$config['max_width']            = 1024;
		$config['max_height']           = 768;

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload('userfile1'))
		{
			$data['errors'] = array('error' => $this->upload->display_errors());
			$data['menu']=$this->manager_model->getMenuItems();
			$email=$this->session->userdata['email'];
			$data['client_Id']=$this->manager_model->getUserId($email);
			$data['pic']=$this->manager_model->getImage($data['client_Id']['id']);
			$data['title']='SmartBABA ERP';
			$this->load->view('static/head',$data);
			$this->load->view('static/header');
			$this->load->view('static/sidebar');
			$this->load->view('manager/profile_image',$data);
			$this->load->view('static/footer');
		}
		else
		{
			$upload_data = $this->upload->data(); 
			$file_name=$upload_data['file_name'];
			$email=$this->session->userdata['email'];
			$data['client_Id']=$this->manager_model->getUserId($email);
			$data['pic']=$this->manager_model->getImage($data['client_Id']['id']);
			if($data['pic']['image']!=''){
				$this->manager_model->updateManagerImage($data['pic']['id'],$file_name);
				$data['success']='Congratulations! Image Updated Successfully';
			}else{
				$this->manager_model->InsertManagerImage($data['client_Id']['id'],$file_name);
				$data = array('upload_data' => $this->upload->data());
				$data['success']='Congratulations! Image Uploaded Successfully';
			}
			$file_name=$upload_data['file_name'];
			$email=$this->session->userdata['email'];
			$data['client_Id']=$this->manager_model->getUserId($email);
			$data['pic']=$this->manager_model->getImage($data['client_Id']['id']);
			$data['menu']=$this->manager_model->getMenuItems();
			$data['title']='SmartBABA ERP';
			$this->load->view('static/head',$data);
			$this->load->view('static/header');
			$this->load->view('static/sidebar');
			$this->load->view('manager/profile_image',$data);
			$this->load->view('static/footer');
		}
	}
    /////////////////////////////////
    ///     PROFILE ENDS          ///
    /////////////////////////////////
  
	public function change_password()
    {
        if($this->isLoggedIn())
        {
            $data['menu']=$this->manager_model->getMenuItems();
			$data['email']=$this->session->userdata['email'];
            //echo '<pre>';print_r($data);exit;
            if($_POST){
				$config=array(
                    array(
                        'field' =>  'new_pass',
                        'label' =>  'New Password',
                        'rules' =>  'required|matches[confirm_pass]'
                    ),
                    array(
                        'field' =>  'confirm_pass',
                        'label' =>  'Confirm Password',
                        'rules' =>  'trim|required'
                    )
                );
                $this->form_validation->set_rules($config);
                if($this->form_validation->run()==false)
                {
                    $data['errors']=validation_errors();
					$data['title']='SmartBABA ERP';
					$this->load->view('static/head',$data);
					$this->load->view('static/header');
					$this->load->view('static/sidebar');
					$this->load->view('client/change_password',$data);
					$this->load->view('static/footer');
				}else{
					$encrpTPass=md5(sha1($_POST['old_pass']));
					$data['oldPass']=$this->manager_model->checkOldPass($data['email'],$encrpTPass);
					if($data['oldPass']>0){
						$confirmPass=md5(sha1($_POST['confirm_pass']));
						$data['user_Id']=$this->manager_model->getUserId($data['email']);
						$this->manager_model->updatePass($data['user_Id']['id'],$confirmPass);
						$this->passwordChangeEmail();
						$data['success']='Congratulations! Password Updated Successfully';
						$data['title']='SmartBABA ERP';
						$this->load->view('static/head',$data);
						$this->load->view('static/header');
						$this->load->view('static/sidebar');
						$this->load->view('client/change_password',$data);
						$this->load->view('static/footer');
					}else{
						$data['errors']='Sorry Old Password does not Match';
						$data['title']='SmartBABA ERP';
						$this->load->view('static/head',$data);
						$this->load->view('static/header');
						$this->load->view('static/sidebar');
						$this->load->view('client/change_password',$data);
						$this->load->view('static/footer');
					}
				}
			}else{
				$data['title']='SmartBABA ERP';
				$this->load->view('static/head',$data);
				$this->load->view('static/header');
				$this->load->view('static/sidebar');
				$this->load->view('client/change_password',$data);
				$this->load->view('static/footer');
			}
        }
        else
        {
            redirect(base_url());
        }
    }
	///////////////////////////////////////
    ///                                 ///
    ///  Clients Email Section Starts   ///
    ///                                 ///
    ///////////////////////////////////////
	public function passwordChangeEmail()
    {
        // Sending User a URL in the Email to Active his account by verifying his email address
        $settings=$this->admin_model->getEmailSettings();
        $data['user']=$this->session->userdata;
        $mail = new PHPMailer();
        $mail->IsSMTP(); // we are going to use SMTP
        $mail->SMTPAuth   = true; // enabled SMTP authentication
        $mail->SMTPSecure = "ssl";  // prefix for secure protocol to connect to the server
        $mail->Host       = $settings->host;                    // setting GMail as our SMTP server
        $mail->Port       = $settings->port;                    // SMTP port to connect to GMail
        $mail->Username   = $settings->email;                   // user email address
        $mail->Password   = $settings->password;                // password in GMail
        $mail->SetFrom($settings->sent_email, $settings->sent_title);       //Who is sending the email
        $mail->AddReplyTo($settings->reply_email,$settings->reply_email);   //email address that receives the response
        $mail->Subject    = "Congratulations! Your Password has been Changed";
        $mail->IsHTML(true);
        $body = $this->load->view('emails/passwordChangeEmail', $data, true);
        $mail->MsgHTML($body);
        $destination = $data['user']['email']; // Who is addressed the email to
        $mail->AddAddress($destination);
        if(!$mail->Send()) {
            $data['code']=300;
            $data["message"] = "Error: " . $mail->ErrorInfo;
        }
    }
    ///////////////////////////////////////
    ///                                 ///
    ///    Clients Email Section Ends   ///
    ///                                 ///
    ///////////////////////////////////////
	
	////////////////////////////////////////////
    ///  		                             ///
    ///  Clients Email Notification Starts   ///
    ///      		                         ///
    ////////////////////////////////////////////
	
    public function email_notifications()
    {
        if($this->isLoggedIn())
        {
			$data['user']=$this->session->userdata;
			$data['email']=$this->manager_model->getEmailNotification($data['user']['id']);
			if($_POST){
				if(!isset($_POST['profile_update'])){
					$_POST['profile_update']='No';
				}else{
					$_POST['profile_update']='Yes';
				} 
				if(!isset($_POST['image_update'])){
					$_POST['image_update']='No';
				}else{
					$_POST['image_update']='Yes';
				} 
				if(!isset($_POST['password_change'])){
					$_POST['password_change']='No';
				}else{
					$_POST['password_change']='Yes';
				} 
				if(!isset($_POST['profile_AF'])){
					$_POST['profile_AF']='No';
				}else{
					$_POST['profile_AF']='Yes';
				} 
				if(!isset($_POST['module_AF'])){
					$_POST['module_AF']='No';
				}else{
					$_POST['module_AF']='Yes';
				} 
				if(!isset($_POST['task_AF'])){
					$_POST['task_AF']='No';
				}else{
					$_POST['task_AF']='Yes';
				} 
				if(!isset($_POST['daily_updates'])){
					$_POST['daily_updates']='No';
				}else{
					$_POST['daily_updates']='Yes';
				}
				$data['menu']=$this->manager_model->getMenuItems();
				$this->manager_model->updateEmailNotification($data['email']['id'],$_POST);
				$data['success']='Congratulations! Updated Successfully';
				$data['email']=$this->manager_model->getEmailNotification($data['user']['id']);
				$data['title']='SmartBABA ERP';
				$this->load->view('static/head',$data);
				$this->load->view('static/header');
				$this->load->view('static/sidebar');
				$this->load->view('client/email_notification',$data);
				$this->load->view('static/footer');
			}else{
				$data['menu']=$this->manager_model->getMenuItems();
				//echo '<pre>';print_r($data);exit;
				$data['title']='SmartBABA ERP';
				$this->load->view('static/head',$data);
				$this->load->view('static/header');
				$this->load->view('static/sidebar');
				$this->load->view('client/email_notification',$data);
				$this->load->view('static/footer');
			}
        }
        else
        {
            redirect(base_url());
        }

    }

    ////////////////////////////////////////////
    //////
    //////  Project Section Starts
    //////
    ////////////////////////////////////////////

    public function manage_projects()
    {
        if($this->isLoggedIn())
        {
            $data['menu']=$this->manager_model->getMenuItems();
            $data['projects']=$this->admin_model->getAllProjects();
            //echo '<pre>';print_r($data);exit;
            $data['title']='SmartBABA ERP';
            $this->load->view('static/head',$data);
            $this->load->view('static/header');
            $this->load->view('static/sidebar');
            $this->load->view('manager/manage_projects');
            $this->load->view('static/footer');
        }
        else
        {
            redirect(base_url());
        }
    }
    public function add_project()
    {
        if($this->isLoggedIn())
        {
            $data['menu']=$this->manager_model->getMenuItems();
            $data['clients']=$this->admin_model->getAll('clients');
            $data['categories']=$this->admin_model->getAll('categories');
            //echo '<pre>';print_r($data);exit;
            if($_POST)
            {
                $projId=$this->admin_model->addProject($_POST);
                $this->addProjectEmail($projId);
                $msg='Project :<strong>'.$_POST['title'].'</strong> has been added';
                $this->admin_model->projectLog($msg);
                $data['success']='Congratulations! Project Added Successfully';
                $data['menu']=$this->manager_model->getMenuItems();
                $data['title']='SmartBABA ERP';
                $data['clients']=$this->admin_model->getAll('clients');
                $data['categories']=$this->admin_model->getAll('categories');
                $this->load->view('static/head',$data);
                $this->load->view('static/header');
                $this->load->view('static/sidebar');
                $this->load->view('admin/add_project');
                $this->load->view('static/footer');
            }
            else
            {
                //echo '<pre>';print_r($data);exit;
                $data['title']='SmartBABA ERP';
                $this->load->view('static/head',$data);
                $this->load->view('static/header');
                $this->load->view('static/sidebar');
                $this->load->view('admin/add_project');
                $this->load->view('static/footer');
            }
        }
        else
        {
            redirect(base_url().'admin/login');
        }

    }
    public function addProjectEmail($id)
    {
        // Sending User a URL in the Email to Active his account by verifying his email address
        $data['project']=$this->admin_model->getProjectById($id);
        $data['user']=$this->admin_model->getClientById($data['project']['client_id']);
        $settings=$this->admin_model->getEmailSettings();
        $mail = new PHPMailer();
        $mail->IsSMTP(); // we are going to use SMTP
        $mail->SMTPAuth   = true; // enabled SMTP authentication
        $mail->SMTPSecure = "ssl";  // prefix for secure protocol to connect to the server
        $mail->Host       = $settings->host;                    // setting GMail as our SMTP server
        $mail->Port       = $settings->port;                    // SMTP port to connect to GMail
        $mail->Username   = $settings->email;                   // user email address
        $mail->Password   = $settings->password;                // password in GMail
        $mail->SetFrom($settings->sent_email, $settings->sent_title);       //Who is sending the email
        $mail->AddReplyTo($settings->reply_email,$settings->reply_email);   //email address that receives the response
        //////////////Email to Client////////////////
        $mail->Subject    = "Your Project has been Added to Project Management Application";
        $mail->IsHTML(true);
        $body = $this->load->view('emails/addProjectEmail', $data, true);
        $mail->MsgHTML($body);
        $destination = $data['user']['email']; // Who is addressed the email to
        $mail->AddAddress($destination);
        if(!$mail->Send()) {
            $data['code']=300;
            $data["message"] = "Error: " . $mail->ErrorInfo;
        }
        //////////////Email to Managers //////////////////
        $managers=$this->admin_model->getAll('manager');
        if(count($managers)>0)
        {
            for($i=0;$i<count($managers);$i++)
            {
                $data['manager']=$managers[$i];
                $mail->Subject    = "New Project has been Added to Project Management Application";
                $mail->IsHTML(true);
                $body = $this->load->view('emails/addProjectEmailForManager', $data, true);
                $mail->MsgHTML($body);
                $destination = $managers[$i]['email']; // Who is addressed the email to
                $mail->AddAddress($destination);
                if(!$mail->Send()) {
                    $data['code']=300;
                    $data["message"] = "Error: " . $mail->ErrorInfo;
                }
            }
        }
    }
    public function edit_project()
    {
        if($this->isLoggedIn())
        {
            $menuId=$this->uri->segment(3);
            $data['menu']=$this->manager_model->getMenuItems();
            $data['project']=$this->admin_model->getProjectById($menuId);
            $data['clients']=$this->admin_model->getAll('clients');
            $data['categories']=$this->admin_model->getAll('categories');
            //echo '<pre>';print_r($data);exit;
            if($_POST)
            {
                $this->admin_model->updateProject($_POST,$menuId);
                $data['success']='Congratulations! Project Updated Successfully';
                $data['menu']=$this->manager_model->getMenuItems();
                $data['title']='SmartBABA ERP';
                $data['project']=$this->admin_model->getProjectById($menuId);
                $data['clients']=$this->admin_model->getAll('clients');
                $data['categories']=$this->admin_model->getAll('categories');
                $this->load->view('static/head',$data);
                $this->load->view('static/header');
                $this->load->view('static/sidebar');
                $this->load->view('admin/edit_project');
                $this->load->view('static/footer');
            }
            else
            {
                //echo '<pre>';print_r($data);exit;
                $data['title']='SmartBABA ERP';
                $this->load->view('static/head',$data);
                $this->load->view('static/header');
                $this->load->view('static/sidebar');
                $this->load->view('admin/edit_project');
                $this->load->view('static/footer');
            }
        }
        else
        {
            redirect(base_url().'admin/login');
        }

    }
    public function editProjectEmail($id)
    {
        // Sending User a URL in the Email to Active his account by verifying his email address
        $data['masjid']=$this->mosque_model->getMasjidDetailsById($id);
        $data['user']=$this->user_model->getUserById($data['masjid']['posted_by']);
        $mail = new PHPMailer();
        $mail->IsSMTP(); // we are going to use SMTP
        $mail->SMTPAuth   = true; // enabled SMTP authentication
        $mail->SMTPSecure = "ssl";  // prefix for secure protocol to connect to the server
        $mail->Host       = "server148.web-hosting.com";      // setting GMail as our SMTP server
        $mail->Port       = 465;                   // SMTP port to connect to GMail
        $mail->Username   = "we@hayyaalalfalah.net";  // user email address
        $mail->Password   = "ParasBilla@123";            // password in GMail
        $mail->SetFrom('we@hayyaalalfalah.net', 'Hayya Alal Falaah');  //Who is sending the email
        $mail->AddReplyTo("we@hayyaalalfalah.net","Accounts");  //email address that receives the response
        $mail->Subject    = "Congratulations! Your Mosque has been Approved";
        $mail->IsHTML(true);
        $body = $this->load->view('admin/emails/approved_email', $data, true);
        $mail->MsgHTML($body);
        $destination = $data['user']['email']; // Who is addressed the email to
        $mail->AddAddress($destination);
        if(!$mail->Send()) {
            $data['code']=300;
            $data["message"] = "Error: " . $mail->ErrorInfo;
        }
    }
    public function project_detail()
    {
        if($this->isLoggedIn())
        {
            $menuId=$this->uri->segment(3);
            $data['menu']=$this->manager_model->getMenuItems();
            $data['project']=$this->manager_model->getProject($menuId);

            $data['title']='SmartBABA ERP';
            $this->load->view('static/head',$data);
            $this->load->view('static/header');
            $this->load->view('static/sidebar');
            $this->load->view('manager/project_detail');
            $this->load->view('static/footer');

        }
        else
        {
            redirect(base_url());
        }
    }
    public function project_modules()
    {
        if($this->isLoggedIn())
        {
            $menuId=$this->uri->segment(3);
            $data['menu']=$this->manager_model->getMenuItems();
            $data['project']=$this->admin_model->getProjectById($menuId);
            $data['modules']=$this->admin_model->getProjectModulesById($menuId);

            $data['title']='SmartBABA ERP';
            if($_POST)
            {
                $moduleId=$this->admin_model->addProjectModule($_POST,$menuId);
                $this->addProjectModuleEmail($menuId,$moduleId);
                $data['success']='Congratulations! Module Added Successfully';
                $data['menu']=$this->manager_model->getMenuItems();
                $data['project']=$this->admin_model->getProjectById($menuId);
                $data['modules']=$this->admin_model->getProjectModulesById($menuId);
                $this->load->view('static/head',$data);
                $this->load->view('static/header');
                $this->load->view('static/sidebar');
                $this->load->view('manager/project_modules');
                $this->load->view('static/footer');
            }
            else
            {
                $this->load->view('static/head',$data);
                $this->load->view('static/header');
                $this->load->view('static/sidebar');
                $this->load->view('manager/project_modules');
                $this->load->view('static/footer');
            }
        }
        else
        {
            redirect(base_url());
        }
    }
    public function addProjectModuleEmail($id,$moduleId)
    {
        // Sending User a URL in the Email to Active his account by verifying his email address
        $data['project']=$this->admin_model->getProjectById($id);
        $data['module']=$this->admin_model->getById('project_modules',$moduleId);
        $data['user']=$this->admin_model->getClientById($data['project']['client_id']);
        $settings=$this->admin_model->getEmailSettings();
        $mail = new PHPMailer();
        $mail->IsSMTP(); // we are going to use SMTP
        $mail->SMTPAuth   = true; // enabled SMTP authentication
        $mail->SMTPSecure = "ssl";  // prefix for secure protocol to connect to the server
        $mail->Host       = $settings->host;                    // setting GMail as our SMTP server
        $mail->Port       = $settings->port;                    // SMTP port to connect to GMail
        $mail->Username   = $settings->email;                   // user email address
        $mail->Password   = $settings->password;                // password in GMail
        $mail->SetFrom($settings->sent_email, $settings->sent_title);       //Who is sending the email
        $mail->AddReplyTo($settings->reply_email,$settings->reply_email);   //email address that receives the response
        /////////////Email to Client ///////////////
        $mail->Subject    = "A New Module has been Added to Your Project: ".$data['project']['title'];
        $mail->IsHTML(true);
        $body = $this->load->view('emails/addProjectModuleEmail', $data, true);
        $mail->MsgHTML($body);
        $destination = $data['user']['email']; // Who is addressed the email to
        $mail->AddAddress($destination);
        if(!$mail->Send()) {
            $data['code']=300;
            $data["message"] = "Error: " . $mail->ErrorInfo;
        }
        /////////////Email to Managers ///////////////
        $managers=$this->admin_model->getAll('manager');
        if(count($managers)>0)
        {
            for($i=0;$i<count($managers);$i++)
            {
                $data['manager']=$managers[$i];
                $mail->Subject    = "New Module has been Added for Project:".$data['project']['title'];
                $mail->IsHTML(true);
                $body = $this->load->view('emails/addProjectModuleEmailForManager', $data, true);
                $mail->MsgHTML($body);
                $destination = $managers[$i]['email']; // Who is addressed the email to
                $mail->AddAddress($destination);
                if(!$mail->Send()) {
                    $data['code']=300;
                    $data["message"] = "Error: " . $mail->ErrorInfo;
                }
            }
        }
    }
    public function module_tasks()
    {
        if($this->isLoggedIn())
        {
            $menuId=$this->uri->segment(3);
            $data['menu']=$this->manager_model->getMenuItems();
            $data['proj_id']=$this->admin_model->getProjectId($menuId);
            $data['project']=$this->admin_model->getProjectById($data['proj_id']);
            $data['tasks']=$this->admin_model->getProjectTasksByModuleId($menuId);
            //echo '<pre>';print_r($data['tasks']);exit;
            $data['title']='SmartBABA ERP';
            if($_POST)
            {
                $taskId=$this->admin_model->addProjectTask($_POST,$menuId);
                $this->addTaskEmail($menuId,$data['proj_id'],$taskId);
                $data['success']='Congratulations! Module Added Successfully';
                $data['menu']=$this->manager_model->getMenuItems();
                $data['project']=$this->admin_model->getProjectById($menuId);
                $data['tasks']=$this->admin_model->getProjectTasksByModuleId($menuId);
                $this->load->view('static/head',$data);
                $this->load->view('static/header');
                $this->load->view('static/sidebar');
                $this->load->view('manager/module_tasks');
                $this->load->view('static/footer');
            }
            else
            {
                $this->load->view('static/head',$data);
                $this->load->view('static/header');
                $this->load->view('static/sidebar');
                $this->load->view('manager/module_tasks');
                $this->load->view('static/footer');
            }
        }
        else
        {
            redirect(base_url());
        }
    }
    public function addTaskEmail($moduleId, $projId, $taskId)
    {
        // Sending User a URL in the Email to Active his account by verifying his email address
        $data['project']=$this->admin_model->getProjectById($projId);
        $data['module']=$this->admin_model->getById('project_modules',$moduleId);
        $data['task']=$this->admin_model->getById('project_tasks',$taskId);
        $data['user']=$this->admin_model->getClientById($data['project']['client_id']);
        $settings=$this->admin_model->getEmailSettings();
        $mail = new PHPMailer();
        $mail->IsSMTP(); // we are going to use SMTP
        $mail->SMTPAuth   = true; // enabled SMTP authentication
        $mail->SMTPSecure = "ssl";  // prefix for secure protocol to connect to the server
        $mail->Host       = $settings->host;                    // setting GMail as our SMTP server
        $mail->Port       = $settings->port;                    // SMTP port to connect to GMail
        $mail->Username   = $settings->email;                   // user email address
        $mail->Password   = $settings->password;                // password in GMail
        $mail->SetFrom($settings->sent_email, $settings->sent_title);       //Who is sending the email
        $mail->AddReplyTo($settings->reply_email,$settings->reply_email);   //email address that receives the response
        //////////////Email to Client////////////////
        $mail->Subject    = "New Task has been Added to Project: ".$data['project']['title'];
        $mail->IsHTML(true);
        $body = $this->load->view('emails/addTaskEmail', $data, true);
        $mail->MsgHTML($body);
        $destination = $data['user']['email']; // Who is addressed the email to
        $mail->AddAddress($destination);
        if(!$mail->Send()) {
            $data['code']=300;
            $data["message"] = "Error: " . $mail->ErrorInfo;
        }
        //////////////Email to Managers //////////////////
        $managers=$this->admin_model->getAll('manager');
        if(count($managers)>0)
        {
            for($i=0;$i<count($managers);$i++)
            {
                $data['user']=$managers[$i];
                $mail->Subject    = "New Task has been Added to Project: ".$data['project']['title'];
                $mail->IsHTML(true);
                $body = $this->load->view('emails/addTaskEmail', $data, true);
                $mail->MsgHTML($body);
                $destination = $managers[$i]['email']; // Who is addressed the email to
                $mail->AddAddress($destination);
                if(!$mail->Send()) {
                    $data['code']=300;
                    $data["message"] = "Error: " . $mail->ErrorInfo;
                }
            }
        }
    }
    public function assign_member()
    {
        if($this->isLoggedIn())
        {
            $menuId=$this->uri->segment(3);
            $data['menu']=$this->manager_model->getMenuItems();
            $data['task']=$this->admin_model->getProjectTaskById($menuId);
            $data['task_members']=$this->admin_model->getMembersByTaskId($menuId);
            $data['members']=$this->admin_model->getTeamMembers();
            //echo '<pre>';print_r($data['tasks']);exit;
            $data['title']='SmartBABA ERP';
            if($_POST)
            {
                $this->admin_model->assignTask($_POST,$menuId);
                $data['success']='Congratulations! Member Added Successfully';
                $data['menu']=$this->manager_model->getMenuItems();
                $data['task']=$this->admin_model->getProjectTaskById($menuId);
                $data['task_members']=$this->admin_model->getMembersByTaskId($menuId);
                $data['members']=$this->admin_model->getTeamMembers();
                $this->load->view('static/head',$data);
                $this->load->view('static/header');
                $this->load->view('static/sidebar');
                $this->load->view('manager/assign_member');
                $this->load->view('static/footer');
            }
            else
            {
                $this->load->view('static/head',$data);
                $this->load->view('static/header');
                $this->load->view('static/sidebar');
                $this->load->view('manager/assign_member');
                $this->load->view('static/footer');
            }
        }
        else
        {
            redirect(base_url());
        }
    }
    public function assignMemberEmail($taskId)
    {
        // Sending User a URL in the Email to Active his account by verifying his email address
        $data['task']=$this->admin_model->getById('project_tasks',$taskId);
        $data['member']=$this->admin_model->getById('employees',$data['task']['user_id']);
        $data['module']=$this->admin_model->getById('project_modules',$data['task']['module_id']);
        $data['project']=$this->admin_model->getProjectById($data['module']['proj_id']);
        $data['user']=$this->admin_model->getClientById($data['project']['client_id']);
        $settings=$this->admin_model->getEmailSettings();
        $mail = new PHPMailer();
        $mail->IsSMTP(); // we are going to use SMTP
        $mail->SMTPAuth   = true; // enabled SMTP authentication
        $mail->SMTPSecure = "ssl";  // prefix for secure protocol to connect to the server
        $mail->Host       = $settings->host;                    // setting GMail as our SMTP server
        $mail->Port       = $settings->port;                    // SMTP port to connect to GMail
        $mail->Username   = $settings->email;                   // user email address
        $mail->Password   = $settings->password;                // password in GMail
        $mail->SetFrom($settings->sent_email, $settings->sent_title);       //Who is sending the email
        $mail->AddReplyTo($settings->reply_email,$settings->reply_email);   //email address that receives the response
        //////////////Email to Client////////////////
        $mail->Subject    = "Task Assignment for Project: ".$data['project']['title'];
        $mail->IsHTML(true);
        $body = $this->load->view('emails/assignMemberEmail', $data, true);
        $mail->MsgHTML($body);
        $destination = $data['user']['email']; // Who is addressed the email to
        $mail->AddAddress($destination);
        if(!$mail->Send()) {
            $data['code']=300;
            $data["message"] = "Error: " . $mail->ErrorInfo;
        }
        //////////////Email to Managers //////////////////
        $managers=$this->admin_model->getAll('manager');
        if(count($managers)>0)
        {
            for($i=0;$i<count($managers);$i++)
            {
                $data['user']=$managers[$i];
                $mail->Subject    = "Task Assignment for Project: ".$data['project']['title'];
                $mail->IsHTML(true);
                $body = $this->load->view('emails/assignMemberEmail', $data, true);
                $mail->MsgHTML($body);
                $destination = $managers[$i]['email']; // Who is addressed the email to
                $mail->AddAddress($destination);
                if(!$mail->Send()) {
                    $data['code']=300;
                    $data["message"] = "Error: " . $mail->ErrorInfo;
                }
            }
        }
        //////////////Email to Member////////////////
        $mail->Subject    = "New Task has been assigned to you for Project: ".$data['project']['title'];
        $mail->IsHTML(true);
        $body = $this->load->view('emails/assignMemberEmailForMember', $data, true);
        $mail->MsgHTML($body);
        $destination = $data['user']['email']; // Who is addressed the email to
        $mail->AddAddress($destination);
        if(!$mail->Send()) {
            $data['code']=300;
            $data["message"] = "Error: " . $mail->ErrorInfo;
        }
    }
    public function project_feedback()
    {
        if($this->isLoggedIn())
        {
            $user_id=$this->session->userdata['id'];
            if($_POST){
                $this->manager_model->insertTaskFeedback($user_id,$_POST);
                $data['menu']=$this->manager_model->getMenuItems();
                $data['success']='Congratulations! Feedback Added Successfully';
                $data['projects']=$this->admin_model->getAll('projects');
                $data['title']='SmartBABA ERP';
                $this->load->view('static/head',$data);
                $this->load->view('static/header');
                $this->load->view('static/sidebar');
                $this->load->view('manager/add_feedback',$data);
                $this->load->view('static/footer');
            }else{
                $data['menu']=$this->manager_model->getMenuItems();
                $data['projects']=$this->admin_model->getAll('projects');
                $data['title']='SmartBABA ERP';
                $this->load->view('static/head',$data);
                $this->load->view('static/header');
                $this->load->view('static/sidebar');
                $this->load->view('manager/add_feedback',$data);
                $this->load->view('static/footer');
            }
        }
        else
        {
            redirect(base_url());
        }
    }

    ///////////////////////////////////////////////
    ///
    ///     Employees Section Starts
    ///
    ///////////////////////////////////////////////
    public function new_employee()
    {
        if($this->isLoggedIn())
        {
            $data['menu']=$this->manager_model->getMenuItems();
            $data['designations']=$this->admin_model->getAll('designations');
            //echo '<pre>';print_r($data);exit;
            if($_POST)
            {
                $config=array(
                    array(
                        'field' =>  'name',
                        'label' =>  'Name',
                        'rules' =>  'trim|required'
                    )
                );
                $this->form_validation->set_rules($config);
                if($this->form_validation->run()==false)
                {
                    $data['errors']=validation_errors();
                    $data['title']='SmartBABA ERP';
                    $this->load->view('static/head',$data);
                    $this->load->view('static/header');
                    $this->load->view('static/sidebar');
                    $this->load->view('manager/new_employee');
                    $this->load->view('static/footer');
                }
                else
                {
                    $this->admin_model->add_employee($_POST);
                    $data['success']='Congratulations! Employee Added Successfully';
                    $data['menu']=$this->manager_model->getMenuItems();
                    $data['title']='SmartBABA ERP';
                    $this->load->view('static/head',$data);
                    $this->load->view('static/header');
                    $this->load->view('static/sidebar');
                    $this->load->view('manager/new_employee');
                    $this->load->view('static/footer');
                }
            }
            else
            {
                //echo '<pre>';print_r($data);exit;
                $data['title']='SmartBABA ERP';
                $this->load->view('static/head',$data);
                $this->load->view('static/header');
                $this->load->view('static/sidebar');
                $this->load->view('manager/new_employee');
                $this->load->view('static/footer');
            }
        }
        else
        {
            redirect(base_url());
        }

    }
    public function manage_employees()
    {
        if($this->isLoggedIn())
        {
            $data['menu']=$this->manager_model->getMenuItems();
            $data['employees']=$this->admin_model->getAllEmployees();
            //echo '<pre>';print_r($data['employees']);exit;
            $data['title']='SmartBABA ERP';
            $this->load->view('static/head',$data);
            $this->load->view('static/header');
            $this->load->view('static/sidebar');
            $this->load->view('manager/manage_employees');
            $this->load->view('static/footer');
        }
        else
        {
            redirect(base_url());
        }
    }
    public function emp_detail()
    {
        if($this->isLoggedIn())
        {
            $menuId=$this->uri->segment(3);
            $data['menu']=$this->manager_model->getMenuItems();
            $data['employee']=$this->admin_model->getEmpById($menuId);
            $data['designations']=$this->admin_model->getAll('designations');
            //echo '<pre>';print_r($data);exit;
            if($_POST)
            {
                $data['menu']=$this->manager_model->getMenuItems();
                $check=$this->admin_model->addAccount($_POST);
                if($check)
                {
                    $data['success']='Account Successfully Created';
                    $data['employee']=$this->admin_model->getEmpById($menuId);
                    $data['designations']=$this->admin_model->getAll('designations');
                    $this->load->view('static/head',$data);
                    $this->load->view('static/header');
                    $this->load->view('static/sidebar');
                    $this->load->view('manager/emp_detail');
                    $this->load->view('static/footer');
                }
                else
                {
                    $data['errors']='Account Already Exists';
                    $data['employee']=$this->admin_model->getEmpById($menuId);
                    $data['designations']=$this->admin_model->getAll('designations');
                    $this->load->view('static/head',$data);
                    $this->load->view('static/header');
                    $this->load->view('static/sidebar');
                    $this->load->view('manager/emp_detail');
                    $this->load->view('static/footer');
                }
            }
            else
            {
                //echo '<pre>';print_r($data);exit;
                $data['title']='SmartBABA ERP';
                $this->load->view('static/head',$data);
                $this->load->view('static/header');
                $this->load->view('static/sidebar');
                $this->load->view('manager/emp_detail');
                $this->load->view('static/footer');
            }
        }
        else
        {
            redirect(base_url());
        }
    }
    public function edit_employee()
    {
        if($this->isLoggedIn())
        {
            $menuId=$this->uri->segment(3);
            $data['menu']=$this->manager_model->getMenuItems();
            $data['employee']=$this->admin_model->getEmpById($menuId);
            $data['designations']=$this->admin_model->getAll('designations');
            //echo '<pre>';print_r($data);exit;
            if($_POST)
            {
                $config=array(
                    array(
                        'field' =>  'name',
                        'label' =>  'Name',
                        'rules' =>  'trim|required'
                    )
                );
                $this->form_validation->set_rules($config);
                if($this->form_validation->run()==false)
                {
                    $data['errors']=validation_errors();
                    $data['employee']=$this->admin_model->getEmpById($menuId);
                    $data['designations']=$this->admin_model->getAll('designations');
                    $data['title']='SmartBABA ERP';
                    $this->load->view('static/head',$data);
                    $this->load->view('static/header');
                    $this->load->view('static/sidebar');
                    $this->load->view('manager/edit_employee');
                    $this->load->view('static/footer');
                }
                else
                {
                    $this->admin_model->updateEmployee($_POST,$menuId);
                    $data['success']='Congratulations! Employee Updated Successfully';
                    $data['menu']=$this->manager_model->getMenuItems();
                    $data['employee']=$this->admin_model->getEmpById($menuId);
                    $data['designations']=$this->admin_model->getAll('designations');
                    $data['title']='SmartBABA ERP';
                    $this->load->view('static/head',$data);
                    $this->load->view('static/header');
                    $this->load->view('static/sidebar');
                    $this->load->view('manager/edit_employee');
                    $this->load->view('static/footer');
                }
            }
            else
            {
                //echo '<pre>';print_r($data);exit;
                $data['title']='SmartBABA ERP';
                $this->load->view('static/head',$data);
                $this->load->view('static/header');
                $this->load->view('static/sidebar');
                $this->load->view('manager/edit_employee');
                $this->load->view('static/footer');
            }
        }
        else
        {
            redirect(base_url());
        }

    }

    ///////////////////////////////////////////////
    ////
    ////    Client Section Starts
    ////
    ///////////////////////////////////////////////

    public function manage_clients()
    {
        if($this->isLoggedIn())
        {
            $data['menu']=$this->manager_model->getMenuItems();
            $data['clients']=$this->admin_model->getAll('clients');
            //echo '<pre>';print_r($data);exit;
            $data['title']='SmartBABA ERP';
            $this->load->view('static/head',$data);
            $this->load->view('static/header');
            $this->load->view('static/sidebar');
            $this->load->view('manager/manage_clients');
            $this->load->view('static/footer');
        }
        else
        {
            redirect(base_url());
        }
    }
    public function edit_client()
    {
        if($this->isLoggedIn())
        {
            $menuId=$this->uri->segment(3);
            $data['menu']=$this->manager_model->getMenuItems();
            $data['client']=$this->admin_model->getClientById($menuId);
            //echo '<pre>';print_r($data);exit;
            if($_POST)
            {
                $config=array(
                    array(
                        'field' =>  'email',
                        'label' =>  'Email',
                        'rules' =>  'trim|required'
                    ),
                    array(
                        'field' =>  'name',
                        'label' =>  'Name',
                        'rules' =>  'trim|required'
                    )
                );
                $this->form_validation->set_rules($config);
                if($this->form_validation->run()==false)
                {
                    $data['errors']=validation_errors();
                    $data['client']=$this->admin_model->getClientById($menuId);
                    $data['title']='SmartBABA ERP';
                    $this->load->view('static/head',$data);
                    $this->load->view('static/header');
                    $this->load->view('static/sidebar');
                    $this->load->view('manager/edit_client');
                    $this->load->view('static/footer');
                }
                else
                {
                    $this->admin_model->updateClient($_POST,$menuId);
                    $data['success']='Congratulations! Client Updated Successfully';
                    $data['menu']=$this->manager_model->getMenuItems();
                    $data['client']=$this->admin_model->getClientById($menuId);
                    $data['title']='SmartBABA ERP';
                    $this->load->view('static/head',$data);
                    $this->load->view('static/header');
                    $this->load->view('static/sidebar');
                    $this->load->view('manager/edit_client');
                    $this->load->view('static/footer');
                }
            }
            else
            {
                //echo '<pre>';print_r($data);exit;
                $data['title']='SmartBABA ERP';
                $this->load->view('static/head',$data);
                $this->load->view('static/header');
                $this->load->view('static/sidebar');
                $this->load->view('manager/edit_client');
                $this->load->view('static/footer');
            }
        }
        else
        {
            redirect(base_url().'admin/login');
        }

    }
    public function client_detail()
    {
        if($this->isLoggedIn())
        {
            $menuId=$this->uri->segment(3);
            $data['menu']=$this->manager_model->getMenuItems();
            $data['client']=$this->admin_model->getClientById($menuId);
            //echo '<pre>';print_r($data);exit;
            if($_POST)
            {
                $check=$this->admin_model->addAccount($_POST);
                $data['menu']=$this->manager_model->getMenuItems();
                if($check)
                {

                    $data['success']='Account Successfully Created';
                    $this->clientAccountEmail($menuId);
                    $data['client']=$this->admin_model->getClientById($menuId);
                    $this->load->view('static/head',$data);
                    $this->load->view('static/header');
                    $this->load->view('static/sidebar');
                    $this->load->view('manager/client_detail');
                    $this->load->view('static/footer');
                }
                else
                {
                    $data['errors']='Account Already Exists';
                    $data['client']=$this->admin_model->getClientById($menuId);
                    $this->load->view('static/head',$data);
                    $this->load->view('static/header');
                    $this->load->view('static/sidebar');
                    $this->load->view('manager/client_detail');
                    $this->load->view('static/footer');
                }
            }
            else
            {
                //echo '<pre>';print_r($data);exit;
                $data['title']='SmartBABA ERP';
                $this->load->view('static/head',$data);
                $this->load->view('static/header');
                $this->load->view('static/sidebar');
                $this->load->view('manager/client_detail');
                $this->load->view('static/footer');
            }
        }
        else
        {
            redirect(base_url().'admin/login');
        }
    }
    public function add_client()
    {
        if($this->isLoggedIn())
        {
            $data['menu']=$this->manager_model->getMenuItems();
            //echo '<pre>';print_r($data);exit;
            if($_POST)
            {
                $config=array(
                    array(
                        'field' =>  'email',
                        'label' =>  'Email',
                        'rules' =>  'trim|required|is_unique[clients.email]|valid_email'
                    ),
                    array(
                        'field' =>  'name',
                        'label' =>  'Name',
                        'rules' =>  'trim|required'
                    )
                );
                $this->form_validation->set_rules($config);
                if($this->form_validation->run()==false)
                {
                    $data['errors']=validation_errors();
                    $data['title']='SmartBABA ERP';
                    $this->load->view('static/head',$data);
                    $this->load->view('static/header');
                    $this->load->view('static/sidebar');
                    $this->load->view('manager/add_client');
                    $this->load->view('static/footer');
                }
                else
                {
                    $clientId=$this->admin_model->addClient($_POST);
                    $data['success']='Congratulations! Client Added Successfully';
                    $this->clientWelcomeEmail($clientId);
                    $data['menu']=$this->manager_model->getMenuItems();
                    $data['title']='SmartBABA ERP';
                    $this->load->view('static/head',$data);
                    $this->load->view('static/header');
                    $this->load->view('static/sidebar');
                    $this->load->view('manager/add_client');
                    $this->load->view('static/footer');
                }
            }
            else
            {
                //echo '<pre>';print_r($data);exit;
                $data['title']='SmartBABA ERP';
                $this->load->view('static/head',$data);
                $this->load->view('static/header');
                $this->load->view('static/sidebar');
                $this->load->view('manager/add_client');
                $this->load->view('static/footer');
            }
        }
        else
        {
            redirect(base_url());
        }

    }
    public function clientWelcomeEmail($id)
    {
        // Sending User a URL in the Email to Active his account by verifying his email address
        $data['user']=$this->admin_model->getClientById($id);
        $settings=$this->admin_model->getEmailSettings();
        $mail = new PHPMailer();
        $mail->IsSMTP(); // we are going to use SMTP
        $mail->SMTPAuth   = true; // enabled SMTP authentication
        $mail->SMTPSecure = "ssl";  // prefix for secure protocol to connect to the server
        $mail->Host       = $settings->host;                    // setting GMail as our SMTP server
        $mail->Port       = $settings->port;                    // SMTP port to connect to GMail
        $mail->Username   = $settings->email;                   // user email address
        $mail->Password   = $settings->password;                // password in GMail
        $mail->SetFrom($settings->sent_email, $settings->sent_title);       //Who is sending the email
        $mail->AddReplyTo($settings->reply_email,$settings->reply_email);   //email address that receives the response
        $mail->Subject    = "Welcome to Smart BABA's Project Management Application";
        $mail->IsHTML(true);
        $body = $this->load->view('emails/clientWelcomeEmail', $data, true);
        $mail->MsgHTML($body);
        $destination = $data['user']['email']; // Who is addressed the email to
        $mail->AddAddress($destination);
        if(!$mail->Send()) {
            $data['code']=300;
            $data["message"] = "Error: " . $mail->ErrorInfo;
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect(base_url());
    }
}