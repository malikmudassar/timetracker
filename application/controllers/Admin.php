<?php
/**
 * Created by PhpStorm.
 * User: sun rise
 * Date: 11/20/2016
 * Time: 2:37 PM
 */

class Admin extends CI_Controller {
    function __construct()
    {
        parent::__construct();
        $this->load->model('admin_model');
        $this->load->model('team_model');
        if(!$this->isLoggedIn())
        {
            redirect(base_url());
        }
    }

    public function index()
    {
        
        $data['menu']=$this->admin_model->getMenuItems();        
        $data['employees']=$this->admin_model->getAttendance();            
        //echo '<pre>';print_r($data['employees']);exit;
        if($_POST)
        {
            $dd=date('Y-m-d',strtotime($_POST['date']));
            $data['menu']=$this->admin_model->getMenuItems();
            $data['employees']=$this->admin_model->getAttendanceByDate($dd);

            $data['title']='Time Tracker';
            $this->load->view('static/head',$data);
            $this->load->view('static/header');
            $this->load->view('static/sidebar');
            $this->load->view('admin/dashboard');
            $this->load->view('static/footer');
        }
        else
        {
            $data['title']='Time Tracker';
            $this->load->view('static/head',$data);
            $this->load->view('static/header');
            $this->load->view('static/sidebar');
            $this->load->view('admin/dashboard');
            $this->load->view('static/footer');
        }        

    }

    public function userDetail()
    {
        
        $userId=$this->uri->segment(3);
        $date=$this->uri->segment(4);
        $data['menu']=$this->admin_model->getMenuItems();
        $data['attendance']=$this->admin_model->getUserAttendance($userId,$date);  
        $data['title']='Time Tracker';
        $this->load->view('static/head',$data);
        $this->load->view('static/header');
        $this->load->view('static/sidebar');
        $this->load->view('admin/userDetail');
        $this->load->view('static/footer');       
        
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect(base_url());
    }

    ///////////////////////////////////////
    ///                                 ///
    ///     Employees Section Starts    ///
    ///                                 ///
    ///////////////////////////////////////
    public function add_user()
    {
        
        $data['menu']=$this->admin_model->getMenuItems();
        $data['user_roles']=$this->admin_model->getAll('user_roles');
        //echo '<pre>';print_r($data);exit;
        if($_POST)
        {
            $config=array(
                array(
                    'field' =>  'name',
                    'label' =>  'Name',
                    'rules' =>  'trim|required'
                ),
                array(
                    'field' =>  'email',
                    'label' =>  'Email',
                    'rules' =>  'trim|required'
                ),
                array(
                    'field' =>  'password',
                    'label' =>  'Password',
                    'rules' =>  'trim|required'
                ),
                array(
                    'field' =>  'conf_password',
                    'label' =>  'Confirm Password',
                    'rules' =>  'trim|required|matches[password]'
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
                $this->load->view('admin/add_employee');
                $this->load->view('static/footer');
            }
            else
            {
                $this->admin_model->add_employee($_POST);
                $data['success']='Congratulations! User Added Successfully';
                $data['menu']=$this->admin_model->getMenuItems();
                $data['title']='Time Tracker';
                $this->load->view('static/head',$data);
                $this->load->view('static/header');
                $this->load->view('static/sidebar');
                $this->load->view('admin/add_employee');
                $this->load->view('static/footer');
            }
        }
        else
        {
            //echo '<pre>';print_r($data);exit;
            $data['title']='Time Tracker';
            $this->load->view('static/head',$data);
            $this->load->view('static/header');
            $this->load->view('static/sidebar');
            $this->load->view('admin/add_employee');
            $this->load->view('static/footer');
        }

    }
    public function edit_user()
    {
        
        $menuId=$this->uri->segment(3);
        $data['menu']=$this->admin_model->getMenuItems();
        $data['user']=$this->admin_model->getEmpById($menuId);
        $data['user_roles']=$this->admin_model->getAll('user_roles');
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
                $data['user']=$this->admin_model->getEmpById($menuId);
                $data['user_roles']=$this->admin_model->getAll('user_roles');
                $data['title']='Time Tracker';
                $this->load->view('static/head',$data);
                $this->load->view('static/header');
                $this->load->view('static/sidebar');
                $this->load->view('admin/edit_employee');
                $this->load->view('static/footer');
            }
            else
            {
                $this->admin_model->updateEmployee($_POST,$menuId);
                $data['success']='Congratulations! User Updated Successfully';
                $data['menu']=$this->admin_model->getMenuItems();
                $data['user']=$this->admin_model->getEmpById($menuId);
                $data['user_roles']=$this->admin_model->getAll('user_roles');
                $data['title']='Time Tracker';
                $this->load->view('static/head',$data);
                $this->load->view('static/header');
                $this->load->view('static/sidebar');
                $this->load->view('admin/edit_employee');
                $this->load->view('static/footer');
            }
        }
        else
        {
            //echo '<pre>';print_r($data);exit;
            $data['title']='Time Tracker';
            $this->load->view('static/head',$data);
            $this->load->view('static/header');
            $this->load->view('static/sidebar');
            $this->load->view('admin/edit_employee');
            $this->load->view('static/footer');
        }
        

    }
    
    public function manage_users()
    {
        
        $data['menu']=$this->admin_model->getMenuItems();
        $data['employees']=$this->admin_model->getAll('users');        
        $data['title']='Time Tracker';
        $this->load->view('static/head',$data);
        $this->load->view('static/header');
        $this->load->view('static/sidebar');
        $this->load->view('admin/manage_employees');
        $this->load->view('static/footer');
        
    }
    
    public function del_user()
    {
        $userId=$this->uri->segment(3);
        $this->admin_model->delDesById($userId);
        redirect(base_url().'admin/manage_users');
    }

    public function report_attendance()
    {
        
        if($_GET['q']==1)
        {
            $data['success']='Updated Successfully';
        }
        $data['menu']=$this->admin_model->getMenuItems();
        $data['employees']=$this->admin_model->getAttendance();        
        if($_POST)
        {
            $dd=date('Y-m-d',strtotime($_POST['date']));
            $data['menu']=$this->admin_model->getMenuItems();
            $data['employees']=$this->admin_model->getAttendanceByDate($dd);
            $data['title']='Time Tracker';
            $this->load->view('static/head',$data);
            $this->load->view('static/header');
            $this->load->view('static/sidebar');
            $this->load->view('admin/report_attendance');
            $this->load->view('static/footer');
        }
        else
        {
            $data['title']='Time Tracker';
            $this->load->view('static/head',$data);
            $this->load->view('static/header');
            $this->load->view('static/sidebar');
            $this->load->view('admin/report_attendance');
            $this->load->view('static/footer');
        }
        
    }

    public function monthly_attendance()
    {
        
        $data['menu']=$this->admin_model->getMenuItems();
        $data['users']=$this->admin_model->getEmployees();
        $data['employees']=array();
        if($_POST)
        {
            $data['employees']=$this->admin_model->getEmpAttn($_POST['user_id']);
            //echo '<pre>';print_r($data['employees']);exit;
            $data['title']='Time Tracker';
            $this->load->view('static/head',$data);
            $this->load->view('static/header');
            $this->load->view('static/sidebar');
            $this->load->view('admin/monthly_attendance');
            $this->load->view('static/footer');
        }
        else
        {
            $data['title']='Time Tracker';
            $this->load->view('static/head',$data);
            $this->load->view('static/header');
            $this->load->view('static/sidebar');
            $this->load->view('admin/monthly_attendance');
            $this->load->view('static/footer');
        }
        
    }

    public function previous_months()
    {
        
        $data['menu']=$this->admin_model->getMenuItems();
        $data['users']=$this->admin_model->getEmployees();
        $data['employees']=array();
        if($_POST)
        {
            //echo '<pre>';print_r($_POST);exit;
            $data['employees']=$this->admin_model->getEmpAttnByMonth($_POST);           
            $data['title']='Time Tracker';
            $this->load->view('static/head',$data);
            $this->load->view('static/header');
            $this->load->view('static/sidebar');
            $this->load->view('admin/previous_months');
            $this->load->view('static/footer');
        }
        else
        {
            $data['title']='Time Tracker';
            $this->load->view('static/head',$data);
            $this->load->view('static/header');
            $this->load->view('static/sidebar');
            $this->load->view('admin/previous_months');
            $this->load->view('static/footer');
        }
        
    }

    public function edit_attendance()
    {
        $userId=$this->uri->segment(3);
        $date=$this->uri->segment(4);
        $data['menu']=$this->admin_model->getMenuItems();
        $data['users']=$this->admin_model->getEmployees();
        $data['attendance']=$this->admin_model->getUserAttendance($userId,$date);  
        //echo '<pre>';print_r($data['attendance']);exit;
        if($_POST)
        {
            $this->admin_model->updateAttendance($userId,$date,$_POST);
            $data['success']='Attendance Updated Successfully';            
            $data['attendance']=$this->admin_model->getUserAttendance($userId,$date);  
            $data['title']='Time Tracker';
            $this->load->view('static/head',$data);
            $this->load->view('static/header');
            $this->load->view('static/sidebar');
            $this->load->view('admin/edit_attendance');
            $this->load->view('static/footer');
        }
        else
        {
            $data['title']='Time Tracker';
            $this->load->view('static/head',$data);
            $this->load->view('static/header');
            $this->load->view('static/sidebar');
            $this->load->view('admin/edit_attendance');
            $this->load->view('static/footer');
        }
        
    }

    public function edit_profile()
    {
        
        $email=$this->session->userdata['email'];
        $userId=$this->session->userdata['id'];
        $data['profile']=$this->team_model->getEmployeeData($email);
        $data['menu']=$this->admin_model->getMenuItems();
        $data['attendance']=$this->team_model->getMyAttendance($userId);       

        if($_POST)
        {
            $_POST['name']=$this->session->userdata['name'];
            $_POST['email']=$this->session->userdata['email'];
            $this->team_model->updateEmployee($userId,$_POST);
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
            $data['menu']=$this->admin_model->getMenuItems();
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
        $data['menu']=$this->admin_model->getMenuItems();
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
    public function technican_scopes()
    {
        $id = $this->uri->segment(3);

        $user = $this->admin_model->getById('users',$id);

        if(!isset($_GET['date'])) $date = date('Y-m-d');
        else $date = date('Y-m-d', strtotime($_GET['date']));

        $params = array('email' => $user['email'], 'date' => $date);
        $scopes = $this->admin_model->getScopes($params);

        $email=$this->session->userdata['email'];
        $data['profile']=$this->team_model->getEmployeeData($email);
        $data['menu']=$this->admin_model->getMenuItems();
        $data['attendance']=$this->team_model->getMyAttendance($data['profile'][0]['id']);
        $data['technician'] = $user;
        $data['scopes'] = $scopes;
        $data['search_date'] = date('m/d/Y', strtotime($date));
        $data['title']='Time Tracker';
        $this->load->view('static/head',$data);
        $this->load->view('static/header');
        $this->load->view('static/sidebar');
        $this->load->view('admin/technician_scopes');
        $this->load->view('static/footer');
    }
    public function isLoggedIn()
    {
        if(!empty($this->session->userdata['id'])&& $this->session->userdata['type']=='admin')
        {
            return true;
        }
        else
        {
            return false;
        }
    }

}