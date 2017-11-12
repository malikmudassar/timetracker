<?php
/**
 * Created by PhpStorm.
 * User: sun rise
 * Date: 11/26/2016
 * Time: 11:39 AM
 */

class Team_model extends CI_Model {
    function __construct(){
        parent::__construct();
    }
    public function getMenuItems()
    {
        $st=$this->db->select('*')->from('team_menu')->where('parent',0)->get()->result_array();
        if(count($st)>0)
        {
            for($i=0;$i<count($st);$i++)
            {
                $st[$i]['child']=$this->db->select('*')->from('team_menu')->where('parent',$st[$i]['id'])->get()->result_array();
            }
        }
        else
        {
            return false;
        }
        return $st;
    }
	
	public function getEmployeeData($email){
		$st=$this->db->select('*')->from('users')->WHERE('email',$email)->get()->result_array();
        return $st;
	}
	public function updateEmployee($id,$data){
		 $employees=array(
            'name'             	=> $data['name'],
            'email'             => $data['email'],
            'designation'     	=> $data['designation'],
            'location'          => $data['location'],
            'mobile'           	=> $data['mobile'],
            
        );
        $this->db->WHERE('id',$id)->update('users',$employees);
	}
	
	public function checkOldPass($oldPass,$userId){
		$st=$this->db->select('*')->from('users')
                    ->WHERE('id',$userId)
                    ->WHERE('password',md5(sha1($oldPass)))
                    ->get()
                    ->result_array();
        if(empty($st))
        {
            return false;
        }
        else
        {
            return true;
        }
	}
	public function updatePass($id,$data){
		$update=array(
            'password'    => md5(sha1($data['new_pass']))
        );
        $this->db->WHERE('id',$id)->update('users',$update);
	}
	
	public function getUserId($email){
		$st=$this->db->select('id')->from('users')->WHERE('email',$email)->get()->result_array();
        return $st[0];
	}
	

    public function getMyAttendance($id)
    {
        return $this->db->select('*')
                ->from('attendance')
                ->WHERE('user_id',$id)
                ->WHERE('MONTH(attendance.check_in)',date('m'))
                ->WHERE('YEAR(attendance.check_in)',date('Y'))
                ->get()
                ->result_array();
        //echo $this->db->last_query();exit;
    }
    public function getMyMarkedAttendance()
    {
        date_default_timezone_set('US/Pacific');
        $st=$this->db->query('SELECT * from attendance
                              WHERE DATE(attendance.date)=\''.date('Y-m-d').'\'
                              AND attendance.user_id='.$this->session->userdata['id'])->result_array();
        return $st;
    }

    public function clockIn($userId)
    {
        date_default_timezone_set('US/Pacific');
        $att=array(
                'user_id'=>$userId,
                'date'=>date('Y-m-d'),
                'check_in'=>date('Y-m-d H:i:s'),
                'hours'=>0,
                'remarks'=>''
            );
        $this->db->insert('attendance',$att);
    }
    public function clockOut($userId,$data)
    {
        //echo '<pre>';print_r($data);exit;
        date_default_timezone_set('US/Pacific');
        $att=array(
                'check_out'=>date('Y-m-d H:i:s'),
                'remarks'  =>$data['remarks']
            );
        $this->db->WHERE('id',$data['id'])                 
                 ->UPDATE('attendance',$att);

        $this->db->query('UPDATE attendance SET hours=HOUR((TIMEDIFF(check_out,check_in))) WHERE  id='.$data['id']);
    }

    public function getMyAttendanceByMonth($data,$id)
    {
        $mths=explode('-',$data['month']);
        $year=$mths[0];
        $month=$mths[1];
        return $this->db->select('*')
            ->from('attendance')
            ->WHERE('employee_id',$id)
            ->WHERE('MONTH(attendance.check_in)',$month)
            ->WHERE('YEAR(attendance.check_in)',$year)
            ->get()
            ->result_array();
    }

    public function getMonths()
    {
        return $this->db->query('SELECT DISTINCT(CONCAT(YEAR(attendance.check_in),\'-\',Month(attendance.check_in))) as months FROM `attendance` order by months desc')->result_array();
    }
}