<?php
/**
 * Created by PhpStorm.
 * User: sun rise
 * Date: 11/22/2016
 * Time: 1:10 PM
 */

class Login extends CI_Controller {
    function __construct()
    {
        parent::__construct();
        $this->load->model('Admin_model');
        // $this->load->library('My_PHPMailer');
    }

    public function index()
    {
        if(!$this->isLoggedIn())
        {
            $data['title']='PM Portal';
            if($_POST)
            {
                // $code=$this->admin_model->generateCode();
                $config=array(
                    array(
                        'field' => 'email',
                        'label' => 'Email',
                        'rules' => 'trim|required|valid_email',
                    ),
                    array(
                        'field' => 'password',
                        'label' => 'Password',
                        'rules' => 'trim|required',
                    ),
                );
                $this->form_validation->set_rules($config);
                if($this->form_validation->run()==false)
                {
                    $data['errors']=validation_errors();
                    $this->load->view('static/head', $data);
                    $this->load->view('admin/login');
                }
                else
                {
                    $user=$this->Admin_model->checkUser($_POST);

                    if(!empty($user))
                    {
                        // Generate a code to send to user
                        $data['user_id']=$user['id'];
                        $this->Admin_model->expireCode($user['id']);
                        $data['code']=$this->Admin_model->generateCode($user['id']);
                        // Send this code in Email here 

                        // Compose and Send

                        // Email block ends 
                        $this->load->view('static/head', $data);
                        $this->load->view('admin/authorize');
                        
                    }
                    else
                    {
                        $data['errors']='Sorry! Wrong Credentials.';
                        $this->load->view('static/head', $data);
                        $this->load->view('admin/login');
                    }
                }
            }
            else
            {
                $this->load->view('static/head', $data);
                $this->load->view('admin/login');
            }
        }
        else
        {
            redirect(base_url().$this->session->userdata['type']);
        }

    }
    public function authorize()
    {
        if($_POST)
        {
            $user_id=$_POST['user_id'];
            $code=$this->input->post('code');
            if($this->Admin_model->authenticateCode($user_id, $code))
            {
                $user=$this->Admin_model->getAllById('users',$user_id);
                $user['type']='admin';
                $this->session->set_userdata($user);
                $this->Admin_model->expireCode($user_id);
                redirect(base_url().'admin');
            }
            else
            {
                $data['errors']='Sorry, the code you have entered is expired, please use the new code';
                // print_r($_POST);exit;
                $data['user_id']=$_POST['user_id'];
                $this->load->view('static/head', $data);
                $this->load->view('admin/authorize');
            }
        }
        else
        {
            echo 'nothing posted' ;exit;
            return false;
        }
    }

    public function isLoggedIn()
    {
        if(!empty($this->session->userdata['id'])&& !empty($this->session->userdata['type']))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}