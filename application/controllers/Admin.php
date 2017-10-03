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
    }

    public function index()
    {
        if($this->isLoggedIn())
        {
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
        else
        {
            redirect(base_url());
        }

    }

    public function userDetail()
    {
        if($this->isLoggedIn())
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
        else
        {
            redirect(base_url());
        }

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
        if($this->isLoggedIn())
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
        else
        {
            redirect(base_url());
        }

    }
    public function edit_user()
    {
        if($this->isLoggedIn())
        {
            $menuId=$this->uri->segment(3);
            $data['menu']=$this->admin_model->getMenuItems();
            $data['user']=$this->admin_model->getEmpById($menuId);
            $data['user_roles']=$this->admin_model->getAll('user_roles');
            //$data['designations']=$this->admin_model->getAll('designations');
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
        else
        {
            redirect(base_url());
        }

    }
    public function del_user()
    {
        $menuId=$this->uri->segment(3);
        $this->admin_model->delDesById($menuId);
        redirect(base_url().'admin/manage_designations');
    }
    public function manage_users()
    {
        if($this->isLoggedIn())
        {
            $data['menu']=$this->admin_model->getMenuItems();
            $data['employees']=$this->admin_model->getAll('users');
            //echo '<pre>';print_r($data['employees']);exit;
            $data['title']='Time Tracker';
            $this->load->view('static/head',$data);
            $this->load->view('static/header');
            $this->load->view('static/sidebar');
            $this->load->view('admin/manage_employees');
            $this->load->view('static/footer');
        }
        else
        {
            redirect(base_url());
        }
    }
    
    public function report_attendance()
    {
        if($this->isLoggedIn())
        {
            if($_GET['q']==1)
            {
                $data['success']='Updated Successfully';
            }
            $data['menu']=$this->admin_model->getMenuItems();
            $data['employees']=$this->admin_model->getAttendance();
            //echo '<pre>';print_r($data['task_logs']);exit;
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
        else
        {
            redirect(base_url());
        }
    }
    
    ///////////////////////////////////////
    ///                                 ///
    ///     Employees Section Ends      ///
    ///                                 ///
    ///////////////////////////////////////

    public function edit_profile()
    {
        if($this->isLoggedIn())
        {
            $email=$this->session->userdata['email'];
            $data['profile']=$this->team_model->getEmployeeData($email);
            $data['menu']=$this->admin_model->getMenuItems();
            $data['attendance']=$this->team_model->getMyAttendance($data['profile'][0]['id']);
            //$data['designations']=$this->admin_model->getAll('designations');

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
                $data['menu']=$this->admin_model->getMenuItems();
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
        $data['menu']=$this->admin_model->getMenuItems();
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
            $data['menu']=$this->admin_model->getMenuItems();
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
            $data['menu']=$this->admin_model->getMenuItems();
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