<?php
/**
 * Created by PhpStorm.
 * User: sun rise
 * Date: 11/22/2016
 * Time: 1:30 PM
 */
class Team extends CI_Controller {
    function __construct()
    {
        parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper(array('form', 'url'));
        $this->load->model('admin_model');
        $this->load->model('team_model');
    }

    public function index()
    {
        if($this->isLoggedIn())
        {
            $data['menu']=$this->team_model->getMenuItems();
            
            $data['attendance']=$this->team_model->getMyAttendance($this->session->userdata['id']);
            $data['title']='PM Portal';
            $this->load->view('static/head',$data);
            $this->load->view('static/header');
            $this->load->view('static/sidebar');
            $this->load->view('team/dashboard');
            $this->load->view('static/footer');
        }
        else
        {
            redirect(base_url());
        }

    }
    public function mark_attendance()
    {
        $data['attendance']=$this->team_model->getMyMarkedAttendance();

        if($this->isLoggedIn())
        {
            
            if($_POST)
            {
                $userId=$this->session->userdata['id'];
                $this->team_model->clock_out($userId,$_POST);
                $data['menu']=$this->team_model->getMenuItems();
                $data['attendance']=$this->team_model->getMyMarkedAttendance();
                //echo '<pre>';print_r($data);exit;
                $data['title']='PM Portal';
                $this->load->view('static/head',$data);
                $this->load->view('static/header');
                $this->load->view('static/sidebar');
                $this->load->view('team/attendance');
                $this->load->view('static/footer');  
            }
            else
            {
                $data['menu']=$this->team_model->getMenuItems();
                //echo '<pre>';print_r($data);exit;
                $data['title']='PM Portal';
                $this->load->view('static/head',$data);
                $this->load->view('static/header');
                $this->load->view('static/sidebar');
                $this->load->view('team/attendance');
                $this->load->view('static/footer');    
            }
            
        }
        else
        {
            redirect(base_url());
        }
    }
    public function clockin()
    {
        $userId=$this->session->userdata['id'];
        $this->team_model->clock_in($userId);
        redirect(base_url().'team/mark_attendance');
    }
    public function clockout()
    {
        $userId=$this->session->userdata['id'];
        $this->team_model->clock_out($userId);
        redirect(base_url().'team/mark_attendance');
    }
    public function attendance_report()
    {
        if($this->isLoggedIn())
        {
            $data['menu']=$this->team_model->getMenuItems();
            $email=$this->session->userdata['email'];
            $data['profile']=$this->team_model->getEmployeeData($email);
            $data['attendance']=$this->team_model->getMyAttendance($data['profile'][0]['id']);
            $data['months']=$this->team_model->getMonths();
            if($_POST)
            {
                $email=$this->session->userdata['email'];
                $data['profile']=$this->team_model->getEmployeeData($email);
                $data['attendance']=$this->team_model->getMyAttendanceByMonth($_POST,$data['profile'][0]['id']);
                $data['title']='Time Tracker';
                $this->load->view('static/head',$data);
                $this->load->view('static/header');
                $this->load->view('static/sidebar');
                $this->load->view('team/attendance_report');
                $this->load->view('static/footer');
            }
            else
            {
                //echo '<pre>';print_r($data);exit;
                $data['title']='Time Tracker';
                $this->load->view('static/head',$data);
                $this->load->view('static/header');
                $this->load->view('static/sidebar');
                $this->load->view('team/attendance_report');
                $this->load->view('static/footer');
            }
        }
        else
        {
            redirect(base_url());
        }

    }
    ////////////////////////////////////
    ///  TASKS SECTION STARTS       ////
    ////////////////////////////////////
    public function new_tasks()
    {
        if($this->isLoggedIn())
        {
            $data['menu']=$this->team_model->getMenuItems();
            $data['tasks']=$this->team_model->getNewTasks();
            //echo '<pre>';print_r($data['tasks']);exit;
            $data['title']='Time Tracker';
            $this->load->view('static/head',$data);
            $this->load->view('static/header');
            $this->load->view('static/sidebar');
            $this->load->view('team/new_tasks');
            $this->load->view('static/footer');
        }
        else
        {
            redirect(base_url());
        }

    }
    public function completed_tasks()
    {
        if($this->isLoggedIn())
        {
            $data['menu']=$this->team_model->getMenuItems();
            $data['tasks']=$this->team_model->getCompletedTasks();
            //echo '<pre>';print_r($data['tasks']);exit;
            $data['title']='Time Tracker';
            $this->load->view('static/head',$data);
            $this->load->view('static/header');
            $this->load->view('static/sidebar');
            $this->load->view('team/completed_tasks');
            $this->load->view('static/footer');
        }
        else
        {
            redirect(base_url());
        }

    }
    public function all_tasks()
    {
        if($this->isLoggedIn())
        {
            $data['menu']=$this->team_model->getMenuItems();
            $data['tasks']=$this->team_model->getAllTasks();
            //echo '<pre>';print_r($data['tasks']);exit;
            $data['title']='Time Tracker';
            $this->load->view('static/head',$data);
            $this->load->view('static/header');
            $this->load->view('static/sidebar');
            $this->load->view('team/all_tasks');
            $this->load->view('static/footer');
        }
        else
        {
            redirect(base_url());
        }

    }
    public function markAsComplete()
    {
        $taskId=$this->uri->segment(3);
        $this->team_model->markAsComplete($taskId);
        redirect($_SERVER['HTTP_REFERER']);
    }
    public function taskCompletionEmail($id)
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
    public function finish_date()
    {
        if($this->isLoggedIn())
        {
            $assignId=$this->uri->segment(3);
            $data['menu']=$this->team_model->getMenuItems();
            $data['task']=$this->team_model->getAssignedTask($assignId);
            //echo '<pre>';print_r($data['tasks']);exit;
            if($_POST)
            {
                $this->team_model->updateTask($_POST,$assignId);
                redirect(base_url().'team/new_tasks');
            }
            else
            {
                $data['title']='Time Tracker';
                $this->load->view('static/head',$data);
                $this->load->view('static/header');
                $this->load->view('static/sidebar');
                $this->load->view('team/finish_date');
                $this->load->view('static/footer');
            }
        }
        else
        {
            redirect(base_url());
        }

    }
    public function deadlineEditEmail($id)
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
    ////////////////////////////////////
    ///     TASKS SECTION ENDS      ////
    ////////////////////////////////////



    /////////////////////////////////
    ///     FEEDBACK STARTS       ///
    /////////////////////////////////

    public function add_feedback()
    {
        if($this->isLoggedIn())
        {
			$user_id=$this->session->userdata['id'];
			if($_POST){
				$taskId=$this->team_model->insertTaskFeedback($user_id,$_POST);
				$this->addfeedbackEmail($taskId);
				$data['menu']=$this->team_model->getMenuItems();
				$data['success']='Congratulations! Feedback Added Successfully';
				$data['tasks']=$this->team_model->getinCompletedTasks();
				$data['title']='Time Tracker';
				$this->load->view('static/head',$data);
				$this->load->view('static/header');
				$this->load->view('static/sidebar');
				$this->load->view('team/add_feedback',$data);
				$this->load->view('static/footer');
			}else{
				$data['menu']=$this->team_model->getMenuItems();
				$data['tasks']=$this->team_model->getinCompletedTasks();
				$data['title']='Time Tracker';
				$this->load->view('static/head',$data);
				$this->load->view('static/header');
				$this->load->view('static/sidebar');
				$this->load->view('team/add_feedback',$data);
				$this->load->view('static/footer');
			}
        }
        else
        {
            redirect(base_url());
        }
    }
    public function manage_feedback()
    {
        if($this->isLoggedIn())
        {
            $data['menu']=$this->team_model->getMenuItems();
            $data['feedbacks']=$this->team_model->getAllFeedbacks();
            /* echo('<pre>');
            print_r($data['feedbacks']);
            die;  */
            $data['title']='Time Tracker';
            $this->load->view('static/head',$data);
            $this->load->view('static/header');
            $this->load->view('static/sidebar');
            $this->load->view('team/all_feedbacks');
            $this->load->view('static/footer');
        }
        else
        {
            redirect(base_url());
        }

    }
	public function addfeedbackEmail($taskId)
    {
        // Sending User a URL in the Email to Active his account by verifying his email address
        $data['feedback']=$this->admin_model->getById('task_feedbacks',$taskId);
        $data['task']=$this->admin_model->getById('project_tasks',$taskId);
        $data['member']=$this->admin_model->getById('employees',$data['task']['user_id']);
        $data['module']=$this->admin_model->getById('project_modules',$data['task']['module_id']);
        $data['project']=$this->admin_model->getProjectById($data['module']['proj_id']);
        $data['user']=$this->admin_model->getClientById($data['project']['client_id']);
        $settings=$this->admin_model->getEmailSettings();
		/* echo('<pre>');
		print_r($taskId);
		echo('<br>');
		print_r($data);
		echo('<br>');
		die; */ 
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
        $body = $this->load->view('emails/addFeedbackEmail', $data, true);
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
                $body = $this->load->view('emails/addFeedbackEmail', $data, true);
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
        $mail->Subject    = "New Task Feedback has been given for Project: ".$data['project']['title'];
        $mail->IsHTML(true);
        $body = $this->load->view('emails/addFeedbackEmail', $data, true);
        $mail->MsgHTML($body);
        $destination = $data['user']['email']; // Who is addressed the email to
        $mail->AddAddress($destination);
        if(!$mail->Send()) {
            $data['code']=300;
            $data["message"] = "Error: " . $mail->ErrorInfo;
        }
    }
    public function daily_update()
    {
        if($this->isLoggedIn())
        {
            $user_id=$this->session->userdata['id'];
            $taskId=$this->uri->segment(3);
            $data['task']=$this->team_model->getTaskById($taskId);
            if($_POST){
                $data['task']=$this->team_model->getTaskById($taskId);
                $taskId=$this->team_model->insertTaskFeedback($user_id,$_POST);
                //$this->addfeedbackEmail($taskId);
                $data['menu']=$this->team_model->getMenuItems();
                $data['success']='Congratulations! Feedback Added Successfully';
                $data['title']='Time Tracker';
                $this->load->view('static/head',$data);
                $this->load->view('static/header');
                $this->load->view('static/sidebar');
                $this->load->view('team/daily_update',$data);
                $this->load->view('static/footer');
            }else{
                $data['menu']=$this->team_model->getMenuItems();
                $data['title']='Time Tracker';
                $this->load->view('static/head',$data);
                $this->load->view('static/header');
                $this->load->view('static/sidebar');
                $this->load->view('team/daily_update',$data);
                $this->load->view('static/footer');
            }
        }
        else
        {
            redirect(base_url());
        }
    }

    /////////////////////////////////
    ///     FEEDBACK ENDS         ///
    /////////////////////////////////

    /////////////////////////////////
    ///     MESSAGES STARTS       ///
    /////////////////////////////////

    public function inbox()
    {
        if($this->isLoggedIn())
        {
            $data['menu']=$this->team_model->getMenuItems();
            //echo '<pre>';print_r($data);exit;
            $data['title']='Time Tracker';
            $this->load->view('static/head',$data);
            $this->load->view('static/header');
            $this->load->view('static/sidebar');
            //$this->load->view('admin/dashboard');
            $this->load->view('static/footer');
        }
        else
        {
            redirect(base_url());
        }

    }
    public function sent()
    {
        if($this->isLoggedIn())
        {
            $data['menu']=$this->team_model->getMenuItems();
            //echo '<pre>';print_r($data);exit;
            $data['title']='Time Tracker';
            $this->load->view('static/head',$data);
            $this->load->view('static/header');
            $this->load->view('static/sidebar');
            //$this->load->view('admin/dashboard');
            $this->load->view('static/footer');
        }
        else
        {
            redirect(base_url());
        }

    }
    public function compose()
    {
        if($this->isLoggedIn())
        {
            $data['menu']=$this->team_model->getMenuItems();
            //echo '<pre>';print_r($data);exit;
            $data['title']='Time Tracker';
            $this->load->view('static/head',$data);
            $this->load->view('static/header');
            $this->load->view('static/sidebar');
            //$this->load->view('admin/dashboard');
            $this->load->view('static/footer');
        }
        else
        {
            redirect(base_url());
        }

    }
    /////////////////////////////////
    ///     MESSAGES ENDS         ///
    /////////////////////////////////

    /////////////////////////////////
    ///     PROFILE STARTS        ///
    /////////////////////////////////
    public function edit_profile()
    {
        if($this->isLoggedIn())
        {
			$email=$this->session->userdata['email'];
			$data['profile']=$this->team_model->getEmployeeData($email);
			$data['attendance']=$this->team_model->getMyAttendance($data['profile'][0]['id']);
			//$data['designations']=$this->admin_model->getAll('designations');
            $data['menu']=$this->team_model->getMenuItems();
			
			if($_POST)
            {
				$_POST['name']=$this->session->userdata['name'];
				$_POST['email']=$this->session->userdata['email'];
				$this->team_model->updateEmployee($data['profile'][0]['id'],$_POST);
				$data['success']='Congratulations! Employee Updated Successfully';
				$data['title']='Time Tracker';
				unset($_SESSION['name']);
				$send_data['name']=$_POST['name'];
				$this->session->set_userdata($send_data);
				$data['profile']=$this->team_model->getEmployeeData($email);
				$this->load->view('static/head',$data);
				$this->load->view('static/header');
				$this->load->view('static/sidebar');
				$this->load->view('team/edit_profile',$data);
				$this->load->view('static/footer');
                
            }
            else
            {
				$data['menu']=$this->team_model->getMenuItems();
                $data['title']='Time Tracker';
				$this->load->view('static/head',$data);
				$this->load->view('static/header');
				$this->load->view('static/sidebar');
				$this->load->view('team/edit_profile',$data);
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
		$data['menu']=$this->team_model->getMenuItems();
		$email=$this->session->userdata['email'];
		$data['client_Id']=$this->team_model->getUserId($email);
		$data['pic']=$this->team_model->getImage($data['client_Id']['id']);
		/* echo('<pre>');
		print_r($email);
		print_r($data['client_Id']);
		print_r($data['pic']);
		die;  */
		$data['title']='Time Tracker';
		$this->load->view('static/head',$data);
		$this->load->view('static/header');
		$this->load->view('static/sidebar');
		$this->load->view('team/profile_image',$data);
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
			$data['menu']=$this->team_model->getMenuItems();
			$email=$this->session->userdata['email'];
			$data['client_Id']=$this->team_model->getUserId($email);
			$data['pic']=$this->team_model->getImage($data['client_Id']['id']);
			$data['title']='Time Tracker';
			$this->load->view('static/head',$data);
			$this->load->view('static/header');
			$this->load->view('static/sidebar');
			$this->load->view('team/profile_image',$data);
			$this->load->view('static/footer');
		}
		else
		{
			$upload_data = $this->upload->data(); 
			$file_name=$upload_data['file_name'];
			$email=$this->session->userdata['email'];
			$data['client_Id']=$this->team_model->getUserId($email);
			$data['pic']=$this->team_model->getImage($data['client_Id']['id']);
			if($data['pic']['image']!=''){
				$this->team_model->updateEmployeeImage($data['pic']['id'],$file_name);
				$data['success']='Congratulations! Image Updated Successfully';
			}else{
				$this->team_model->InsertEmployeeImage($data['client_Id']['id'],$file_name);
				$data = array('upload_data' => $this->upload->data());
				$data['success']='Congratulations! Image Uploaded Successfully';
			}
			$file_name=$upload_data['file_name'];
			$email=$this->session->userdata['email'];
			$data['client_Id']=$this->team_model->getUserId($email);
			$data['pic']=$this->team_model->getImage($data['client_Id']['id']);
			$data['menu']=$this->team_model->getMenuItems();
			$data['title']='Time Tracker';
			$this->load->view('static/head',$data);
			$this->load->view('static/header');
			$this->load->view('static/sidebar');
			$this->load->view('team/profile_image',$data);
			$this->load->view('static/footer');
		}
	}
    /////////////////////////////////
    ///     PROFILE ENDS          ///
    /////////////////////////////////



    /////////////////////////////////
    ///     Account Starts        ///
    /////////////////////////////////
    public function change_password()
    {
        if($this->isLoggedIn())
        {
            $data['menu']=$this->team_model->getMenuItems();
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
					$data['title']='Time Tracker';
					$this->load->view('static/head',$data);
					$this->load->view('static/header');
					$this->load->view('static/sidebar');
					$this->load->view('team/change_password',$data);
					$this->load->view('static/footer');
				}else{
					$encrpTPass=md5(sha1($_POST['old_pass']));
					$data['oldPass']=$this->team_model->checkOldPass($data['email'],$encrpTPass);
					if($data['oldPass']>0){
						$confirmPass=md5(sha1($_POST['confirm_pass']));
						$data['user_Id']=$this->team_model->getUserId($data['email']);
						$this->team_model->updatePass($data['user_Id']['id'],$confirmPass);
						//$this->passwordChangeEmail();
						$data['success']='Congratulations! Password Updated Successfully';
						$data['title']='Time Tracker';
						$this->load->view('static/head',$data);
						$this->load->view('static/header');
						$this->load->view('static/sidebar');
						$this->load->view('team/change_password',$data);
						$this->load->view('static/footer');
					}else{
						$data['errors']='Sorry Old Password does not Match';
						$data['title']='Time Tracker';
						$this->load->view('static/head',$data);
						$this->load->view('static/header');
						$this->load->view('static/sidebar');
						$this->load->view('team/change_password',$data);
						$this->load->view('static/footer');
					}
				}
			}else{
				$data['title']='Time Tracker';
				$this->load->view('static/head',$data);
				$this->load->view('static/header');
				$this->load->view('static/sidebar');
				$this->load->view('team/change_password',$data);
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
	
    public function email_notifications()
    {
        if($this->isLoggedIn())
        {
			$data['user']=$this->session->userdata;
			$data['email']=$this->team_model->getEmailNotification($data['user']['id']);
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
				$data['menu']=$this->team_model->getMenuItems();
				$this->team_model->updateEmailNotification($data['email']['id'],$_POST);
				$data['success']='Congratulations! Updated Successfully';
				$data['email']=$this->team_model->getEmailNotification($data['user']['id']);
				$data['title']='Time Tracker';
				$this->load->view('static/head',$data);
				$this->load->view('static/header');
				$this->load->view('static/sidebar');
				$this->load->view('client/email_notification',$data);
				$this->load->view('static/footer');
			}else{
				$data['menu']=$this->team_model->getMenuItems();
				//echo '<pre>';print_r($data);exit;
				$data['title']='Time Tracker';
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
    /////////////////////////////////
    ///     Account ENDS        ///
    /////////////////////////////////

    public function seo_projects()
    {
        if($this->isLoggedIn())
        {
            $data['menu']=$this->team_model->getMenuItems();
            $data['seo_projects']=$this->admin_model->getSeoProjects();
            $data['title']='Time Tracker';
            $this->load->view('static/head',$data);
            $this->load->view('static/header');
            $this->load->view('static/sidebar');
            $this->load->view('team/seo_projects');
            $this->load->view('static/footer');
        }
        else
        {
            redirect(base_url());
        }
    }

    public function add_seo_work_onpage()
    {
        if($this->isLoggedIn())
        {
            $data['menu']=$this->team_model->getMenuItems();
            $data['projects']=$this->admin_model->getSeoProjects();
            $data['category']=$this->admin_model->getAll('seo_onpage_categories');
            if($_POST)
            {
                $this->team_model->add_seo_submission('seo_onpage_submissions',$_POST);
                redirect(base_url().'team/manage_seo?q=1');
            }
            else
            {
                $data['title']='Time Tracker';
                $this->load->view('static/head',$data);
                $this->load->view('static/header');
                $this->load->view('static/sidebar');
                $this->load->view('team/add_seo_work_onpage');
                $this->load->view('static/footer');
            }

        }
        else
        {
            redirect(base_url());
        }
    }
    public function add_seo_work_offpage()
    {
        if($this->isLoggedIn())
        {
            $data['menu']=$this->team_model->getMenuItems();
            $data['projects']=$this->admin_model->getSeoProjects();
            $data['category']=$this->admin_model->getAll('seo_category');
            $data['title']='Time Tracker';
            if($_POST)
            {
                $this->team_model->add_seo_offpage_submission('seo_offpage_submission',$_POST);
                redirect(base_url().'team/manage_seo?q=1');
            }
            else
            {
                $this->load->view('static/head', $data);
                $this->load->view('static/header');
                $this->load->view('static/sidebar');
                $this->load->view('team/add_seo_work_offpage');
                $this->load->view('static/footer');
            }
        }
        else
        {
            redirect(base_url());
        }
    }
    public function manage_seo()
    {
        if($this->isLoggedIn())
        {
            $data['menu']=$this->team_model->getMenuItems();
            $data['feedbacks']['onpage']=$this->team_model->getAllSeoSubmissions('seo_onpage_submissions');
            $data['feedbacks']['offpage']=$this->team_model->getAllSeoSubmissions('seo_offpage_submission');
            $data['title']='Time Tracker';
            //echo '<pre>';print_r($data['feedback']);exit;
            if($_GET['q']==1)
            {
                $data['success']='Work Submitted Successfully';
            }
            $this->load->view('static/head', $data);
            $this->load->view('static/header');
            $this->load->view('static/sidebar');
            $this->load->view('team/manage_seo');
            $this->load->view('static/footer');

        }
        else
        {
            redirect(base_url());
        }
    }

    public function isLoggedIn()
    {
        if(!empty($this->session->userdata['id'])&& $this->session->userdata['type']=='team')
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    public function logout()
    {
        $this->session->sess_destroy();
        redirect(base_url());
    }
}