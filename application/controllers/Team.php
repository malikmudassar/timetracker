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
        if(!$this->isLoggedIn())
        {
            redirect(base_url());
        }
    }

    public function index()
    {
        $userId=$this->session->userdata['id'];
        $data['menu']=$this->team_model->getMenuItems();        
        $data['attendance']=$this->team_model->getMyAttendance($userId);
        $data['title']='Wadic Time tracker';
        $this->load->view('static/head',$data);
        $this->load->view('static/header');
        $this->load->view('static/sidebar');
        $this->load->view('team/dashboard');
        $this->load->view('static/footer');        

    }
    public function mark_attendance()
    {
        $data['attendance']=$this->team_model->getMyMarkedAttendance();      
        if($_POST)
        {
            $userId=$this->session->userdata['id'];
            $this->team_model->clockOut($userId,$_POST);
            $data['menu']=$this->team_model->getMenuItems();
            $data['attendance']=$this->team_model->getMyMarkedAttendance();            
            $data['title']='Wadic Time tracker';
            $this->load->view('static/head',$data);
            $this->load->view('static/header');
            $this->load->view('static/sidebar');
            $this->load->view('team/attendance');
            $this->load->view('static/footer');  
        }
        else
        {
            $data['menu']=$this->team_model->getMenuItems();            
            $data['title']='Wadic Time tracker';
            $this->load->view('static/head',$data);
            $this->load->view('static/header');
            $this->load->view('static/sidebar');
            $this->load->view('team/attendance');
            $this->load->view('static/footer');    
        }       
        
    }
    public function clockin()
    {
        $userId=$this->session->userdata['id'];
        $this->team_model->clockIn($userId);
        redirect(base_url().'team/mark_attendance');
    }
    public function clockout()
    {
        $userId=$this->session->userdata['id'];
        $this->team_model->clockOut($userId);
        redirect(base_url().'team/mark_attendance');
    }
    public function attendance_report()
    {
        $userId=$this->session->userdata['id'];
        $data['menu']=$this->team_model->getMenuItems();
        $email=$this->session->userdata['email'];
        $data['profile']=$this->team_model->getEmployeeData($email);
        $data['attendance']=$this->team_model->getMyAttendance($userId);
        $data['months']=$this->team_model->getMonths();
        if($_POST)
        {
            $email=$this->session->userdata['email'];
            $data['profile']=$this->team_model->getEmployeeData($email);
            $data['attendance']=$this->team_model->getMyAttendanceByMonth($_POST,$userId);
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

    public function edit_profile()
    {
        
		$email=$this->session->userdata['email'];
		$data['profile']=$this->team_model->getEmployeeData($email);		
        $data['menu']=$this->team_model->getMenuItems();
		
		if($_POST)
        {
			$_POST['name']=$this->session->userdata['name'];
			$_POST['email']=$this->session->userdata['email'];
			$this->team_model->updateEmployee($data['profile'][0]['id'],$_POST);
			$data['success']='Congratulations! Record Updated Successfully';
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
    
    
    public function change_password()
    {
        $userId=$this->session->userdata['id'];
        $data['menu']=$this->team_model->getMenuItems();
		$data['email']=$this->session->userdata['email'];
        //echo '<pre>';print_r($data);exit;
        if($_POST){
			$config=array(
                array(
                    'field' =>  'old_pass',
                    'label' =>  'New Password',
                    'rules' =>  'trim|required|callback_checkOldPass'
                ),
                array(
                    'field' =>  'new_pass',
                    'label' =>  'New Password',
                    'rules' =>  'required|matches[conf_pass]'
                ),
                array(
                    'field' =>  'conf_pass',
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
			}
            else
            {
                $this->team_model->updatePass($userId,$_POST);
                $data['success']='Password Changed Successfully';
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
			$data['title']='Time Tracker';
			$this->load->view('static/head',$data);
			$this->load->view('static/header');
			$this->load->view('static/sidebar');
			$this->load->view('team/change_password',$data);
			$this->load->view('static/footer');
		}
        
    }

    public function checkOldPass($str)
    {
        $userId=$this->session->userdata['id'];
        $check=$this->team_model->checkOldPass($str,$userId);
        if($check)
        {
            return true;
        }
        else
        {
            $this->form_validation->set_message('checkOldPass', 'The Current Password you have provided is incorrect');
            return false;
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