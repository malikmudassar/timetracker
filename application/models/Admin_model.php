<?php
/**
 * Created by PhpStorm.
 * User: sun rise
 * Date: 8/2/2016
 * Time: 3:48 PM
 */
class Admin_model extends CI_Model {
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    public function checkUser($data)
    {
        $st=$this->db->select('*')->from('users')
            ->WHERE('email',$data['email'])
            ->WHERE('password',md5(sha1($data['password'])))
            ->get()->result_array();
        if(count($st)>0)
        {
            //print_r($st); exit;
         return $st[0];
        }
        else
        {
            return false;
        }
    }
    public function getAll($table)
    {
        return $this->db->select('*')->from($table)->get()->result_array();
    }
    public function getAllById($table,$id)
    {
        $st= $this->db->select('*')->from($table)->WHERE('id',$id)->get()->result_array();
        return $st[0];
    }
    public function getMenuItems()
    {
        $st=$this->db->select('*')->from('admin_menu')->where('parent',0)->get()->result_array();
        if(count($st)>0)
        {
            for($i=0;$i<count($st);$i++)
            {
                $st[$i]['child']=$this->db->select('*')->from('admin_menu')->where('parent',$st[$i]['id'])->get()->result_array();
            }
        }
        else
        {
            return false;
        }

        return $st;
    }

    ///////////////////////////////////////
    ///                                 ///
    ///     Employees Section Starts    ///
    ///                                 ///
    ///////////////////////////////////////

    public function add_employee($data)
    {
        $post=array(
            'designation'       =>      $data['designation'],
            'role'              =>      $data['user_role'],
            'location'          =>      $data['location'],
            'name'              =>      $data['name'],
            'email'             =>      $data['email'],
            'mobile'            =>      $data['mobile'],
            'password'          =>      md5(sha1($data['password']))
        );
        $this->db->insert('users',$post);
        return $this->db->insert_id();
    }
    public function delEmpById($id)
    {
        $this->db->query('DELETE from users WHERE id='.$id);
    }
    public function updateEmployee($data,$id)
    {
        $post=array(
            'designation'       =>      $data['designation'],
            'role'              =>      $data['user_role'],
            'location'          =>      $data['location'],
            'name'              =>      $data['name'],
            'email'             =>      $data['email'],
            'mobile'            =>      $data['mobile'],
        );
        $this->db->WHERE('id',$id)->update('users',$post);

    }
    public function getEmpById($id)
    {
        $st=$this->db->query('SELECT users.* from users WHERE users.id='.$id)->result_array();
        return $st[0];
    }
    public function getAllEmployees()
    {
        return $this->db->select('*')->from('users')->get()->result_array();
    }
    public function addAccount($data)
    {
        $st=$this->db->select('*')->from('users')->WHERE('email',$data['email'])->get()->result_array();
        if(count($st)>0)
        {
            return false;
        }
        else
        {
            $user=array(
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => md5(sha1($data['password'])),
                'role' => $data['role'],
                'status'=>'approved'
            );
            $this->db->insert('users',$user);
            $user_id=$this->db->insert_id();
            $notification=array(
              'user_id'=> $user_id
            );
            $this->db->insert('email_notification',$notification);
            return $user_id;
        }
    }
    

    public function getMarkedAttendance()
    {
        date_default_timezone_set('US/Pacific');
        $st=$this->db->query('SELECT employees.* ,attendance.check_in, attendance.check_out from employees
                              left join attendance on attendance.employee_id=employees.id
                              WHERE DATE(attendance.date)=\''.date('Y-m-d').'\'
                              LIMIT 0,9 ')->result_array();
        return $st;
    }
    
    public function getAttendance()
    {
        date_default_timezone_set('US/Pacific');
        $users=$this->db->query("SELECT DISTINCT(user_id) as id FROM attendance WHERE date='".date('Y-m-d')."'")->result_array();
        
        $st=array();
        for($i=0;$i<count($users);$i++)
        {
            $times=$this->db->query("SELECT attendance.*, users.name as user from attendance
                                    inner join users on users.id=attendance.user_id
                                    WHERE attendance.date='".date('Y-m-d')."' AND 
                            user_id=".$users[$i]['id'])->result_array();
            $st[$i]['user_id']=$times[0]['user_id']; 
            $st[$i]['user']=$times[0]['user']; 
            $st[$i]['date']=$times[0]['date']; 
            $st[$i]['check_in']=$times[0]['check_in']; 
            $seconds=0;          
            for($j=0;$j<count($times);$j++)
            {
                if(!empty($times[$j]['check_out']))
                {
                    $seconds+=strtotime($times[$j]['check_out'])-strtotime($times[$j]['check_in']);
                    $st[$i]['check_out']=$times[$j]['check_out'];
                    $st[$i]['seconds']=$seconds;
                }
                else
                {
                    $st[$i]['check_out']='';
                }
            }
        }
        //echo '<pre>';print_r($st); exit;
        return $st;
    }
    public function getAttendanceByDate($dd)
    {
        date_default_timezone_set('US/Pacific');
        $users=$this->db->query("SELECT DISTINCT(user_id) as id FROM attendance WHERE date='".$dd."'")->result_array();
        
        $st=array();
        for($i=0;$i<count($users);$i++)
        {
            $times=$this->db->query("SELECT attendance.*, users.name as user from attendance
                                    inner join users on users.id=attendance.user_id
                                    WHERE attendance.date='".$dd."' AND 
                            user_id=".$users[$i]['id'])->result_array();
            $st[$i]['user_id']=$times[0]['user_id']; 
            $st[$i]['user']=$times[0]['user']; 
            $st[$i]['date']=$times[0]['date']; 
            $st[$i]['check_in']=$times[0]['check_in']; 
            $seconds=0;          
            for($j=0;$j<count($times);$j++)
            {
                if(!empty($times[$j]['check_out']))
                {
                    $seconds+=strtotime($times[$j]['check_out'])-strtotime($times[$j]['check_in']);
                    $st[$i]['check_out']=$times[$j]['check_out'];
                    $st[$i]['seconds']=$seconds;
                }
                else
                {
                    $st[$i]['check_out']='';
                }
            }
        }
        //echo '<pre>';print_r($st); exit;
        return $st;
    }

    public function getUserAttendance($userId,$date)
    {
        return $this->db->select('*')
                        ->from('attendance')
                        ->WHERE('user_id',$userId)
                        ->WHERE('date',$date)
                        ->get()
                        ->result_array();
    }
   


    public function getById($table,$id)
    {
        $st=$this->db->select('*')->from($table)->WHERE('id',$id)->get()->result_array();
        return $st[0];
    }
    public function getByFieldValue($table,$field,$id)
    {
        $st=$this->db->select('*')->from($table)->WHERE($field,$id)->get()->result_array();
        return $st[0];
    }
}