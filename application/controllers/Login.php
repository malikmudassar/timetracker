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
    }

    public function index()
    {
        if(!$this->isLoggedIn())
        {
            $data['title']='PM Portal';
            if($_POST)
            {
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
                        if($user['role']==1)
                        {
                            $user['type']='admin';
                        }
                        elseif($user['role']==2)
                        {
                            $user['type']='team';
                        }
                        
                        $this->session->set_userdata($user);
                        redirect(base_url().$user['type']);
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