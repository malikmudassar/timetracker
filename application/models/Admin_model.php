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

    public function getEmployees()
    {
        return $this->db->select()->from('users')
                     ->where('role',2)
                     ->get()
                     ->result_array();
    }

    public function delDesById($userId)
    {
        $this->db->query('DELETE from attendance WHERE user_id='.$userId);
        $this->db->query('DELETE from users WHERE id='.$userId);
        
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
    public function getEmpAttn($dd)
    {
        date_default_timezone_set('US/Pacific');
        $dates=$this->db->SELECT('DISTINCT(date) as dates')
                        ->from('attendance')
                        ->WHERE('user_id',$dd)
                        ->WHERE('MONTH(date)',date('m'))
                        ->WHERE('YEAR(date)',date('Y'))
                        ->get()
                        ->result_array();
        for($i=0;$i<count($dates);$i++)
        {
            $times=$this->db->query("SELECT attendance.*, users.name as user from attendance
                                    inner join users on users.id=attendance.user_id
                                    WHERE attendance.date='".$dates[$i]['dates']."' AND 
                            user_id=".$dd)->result_array();
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
    public function getEmpAttnByMonth($data)
    {
        date_default_timezone_set('US/Pacific');
        $dates=$this->db->SELECT('DISTINCT(date) as dates')
                        ->from('attendance')
                        ->WHERE('user_id',$data['user_id'])
                        ->WHERE('MONTH(date)',$data['month'])
                        ->WHERE('YEAR(date)',date('Y'))
                        ->get()
                        ->result_array();
        for($i=0;$i<count($dates);$i++)
        {
            $times=$this->db->query("SELECT attendance.*, users.name as user from attendance
                                    inner join users on users.id=attendance.user_id
                                    WHERE attendance.date='".$dates[$i]['dates']."' AND 
                            user_id=".$data['user_id'])->result_array();
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
   
    public function updateAttendance($userId,$date,$data)
    {
        //echo '<pre>';print_r($data);exit;
        $attendance=$this->db->select('*')
                        ->from('attendance')
                        ->WHERE('user_id',$userId)
                        ->WHERE('date',$date)
                        ->get()
                        ->result_array();
        for($i=0;$i<count($attendance);$i++)
        {
            $attArray=array(
                'check_in'  => $date.' '.date('H:i:s',strtotime($data[$attendance[$i]['id'].'-check_in'])),
                'check_out' => $date.' '.date('H:i:s',strtotime($data[$attendance[$i]['id'].'-check_out'])),
                'remarks'   => $data[$attendance[$i]['id'].'-remarks']
            );
            $this->db->WHERE('id',$attendance[$i]['id'])->update('attendance',$attArray);
        }
    }

    public function getScopes($params)
    {        
        $this->load->library('CSVReader');
        $csvData = $this->csvreader->parse_file('/var/www/html/scopes.csv'); //path to 
        $url = $csvData[0]['url'];
        $username = $csvData[0]['username'];
        $password = $csvData[0]['password'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($ch);
        curl_close($ch);
        $scopes = json_decode($result, true);

        return $scopes;
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

    public function generateCode($user_id)
    {
        $code=rand(1000,9999);
        $item=array(
            'user_id'   => $user_id,
            'code'      =>$code
        );
        $this->db->insert('user_code', $item);
        return $code;
    }

    public function authenticateCode($user_id, $code)
    {
        $code=$this->db->select('*')
                    ->from('user_code')
                    ->where('user_id', $user_id)
                    ->where('code', $code)
                    ->where('is_expire','no')
                    ->get()
                    ->result_array();
        if($code)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function expireCode($user_id)
    {
        $this->db->query("update user_code set is_expire='yes' where user_id=".$user_id);
    }
}