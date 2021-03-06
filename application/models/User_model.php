<?php
class User_model extends CI_Model
{
    protected $tableUser = 'user';
    public function getAllUsers()
    {
        return $this->db
            ->join('resoudre', 'resoudre.user_id = user.user_id')
            ->order_by('user_admin DESC')
            ->order_by('user_name DESC')
            ->get($this->tableUser)
            ->result_array()
        ;
    }
    public function getAllEnigmes()
    {
        return $this->db->get('enigme')->result_array();
    }
    public function userLogin($login, $password)
    {
        return $this->db
            ->from($this->tableUser)
            ->where('user_name', $login)
            ->where('user_password', $password)
            ->get()
            ->result_array()
        ;
    }
    public function updatePassword($password)
    {
        $this->db->set('user_password', $password);
        $this->db->where('user_id', $_SESSION['user_infos'][0]['user_id']);
        $this->db->update($this->tableUser);
    }
    public function getUsers()
    {
        $this->db->get($this->tableUser);
    }

    public function getUserById($userId)
    {
        return $this->db
            ->where('user_id', $userId)
            ->get($this->tableUser)
            ->result_array()
        ;
    }
    public function updateMail($mail)
    {
        $this->db->set('user_mail', $mail);
        $this->db->where('user_id', $_SESSION['user_infos'][0]['user_id']);
        $this->db->update($this->tableUser);
    }
    public function insertUser($data)
    {
        $this->db->set('user_name', $data['user_name']);
        $this->db->set('user_password', $data['user_password']);
        $this->db->set('user_mail', $data['user_mail']);
        $this->db->set('user_blocked', 0);
        $this->db->set('user_bantil', NULL);
        $this->db->set('user_admin', 0);
        $this->db->insert($this->tableUser);
        return TRUE;
    }
    public function userExists($data)
    {
        $queryUser = $this->db
            ->select('user_name')
            ->where('user_name', $data['user_name'])
            ->get($this->tableUser)
            ->num_rows()
        ;
        $queryMail = $this->db
            ->select('user_mail')
            ->where('user_mail', $data['user_mail'])
            ->get($this->tableUser)
            ->num_rows()
        ;
        if($queryUser == 0 && $queryMail == 0)
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
    //send verification email to user's email id
    //activate user account
    function verifyEmailID($key)
    {
        $data = array('status' => 1);
        $this->db->where('user_mail', $key);
        return $this->db->update($this->tableUser, $data);
    }
    public function setAdminModel($userId, $status)
    {
        $this->db
            ->where('user_id', $userId)
            ->set('user_admin', $status)
            ->update($this->tableUser)
        ;
    }
    public function blockUserModel($userId)
    {
        $date = date('Y-m-d H:i:s', strtotime('+30 minutes'));
        $this->db
            ->set('user_blocked', 1)
            ->set('user_bantil', $date, TRUE)
            ->where('user_id', $userId)
            ->update($this->tableUser)
        ;
    }
    public function blockUserModel24h($userId)
    {
        $date = date('Y-m-d H:i:s', strtotime('+1 day'));
        $this->db
            ->set('user_blocked', 1)
            ->set('user_bantil', $date, TRUE)
            ->where('user_id', $userId)
            ->update($this->tableUser)
        ;
    }
    public function blockUserModelDef($userId)
    {
        $date = '2025-01-01 00:00:00';
        $this->db
            ->set('user_blocked', 1)
            ->set('user_bantil', $date, TRUE)
            ->where('user_id', $userId)
            ->update($this->tableUser)
        ;
    }
    public function unblockUserModel($userId)
    {
        $this->db
            ->set('user_blocked', 0)
            ->set('user_bantil', NULL)
            ->where('user_id', $userId)
            ->update($this->tableUser)
        ;
    }
    public function getTimeBan($userId)
    {
        $query = $this->db
            ->select('user_bantil')
            ->where('user_id', $userId)
            ->get($this->tableUser)
            ->result_array()
        ;
        return $query;
    }
    public function checkBan($userId)
    {
        $query = $this->db
            ->select('user_blocked')
            ->where('user_id', $userId)
            ->get($this->tableUser)
            ->result_array()
        ;
        return $query;
    }
}