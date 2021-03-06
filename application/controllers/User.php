<?php
class User extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function connexion()
    {
        if(!empty($this->session->userdata('user_infos'))) {
            redirect('compte');
        }
        $login = $this->input->post('login');
        $password = md5($this->input->post('password'));
        $this->form_validation->set_rules('login', '"Identifiant"', 'trim|required');
        $this->form_validation->set_rules('password', '"Mot de passe"', 'trim|required');
        $result = $this->User_model->userLogin($login,$password);
        if($this->form_validation->run() === true && !empty($result))
        {
            $this->session->set_userdata('user_infos', $result);
            $this->session->set_flashdata('change', 'Connecté en tant que '.$_SESSION['user_infos'][0]['user_name']);
            redirect('/compte');
        }
        elseif($this->form_validation->run() == true && empty($result))
        {
            $this->session->set_flashdata('change', 'Aucun compte ne correspond à tes identifiants ');
            $this->load->view('user/login');
        }
        else
        {
            $this->load->view('user/login');
        }
    }
    public function account()
    {
        if(!empty($this->session->userdata('user_infos')))
        {

            $userId = $_SESSION['user_infos'][0]['user_id'];
            $enigmeArray = $this->Enigme_model->getEnigmeEnCours($userId);
            if(isset($enigmeArray[0]))
            {
                $data['enigme'] = $enigmeArray[0]['enigme_id'];
            }
            else{
                $data['enigme'] = '1';
            }
            $this->load->view('user/account', $data);
        }
        else {
            redirect('/connexion');
        }
    }
    public function ranking()
    {
        $data['ranking'] = $this->Enigme_model->getRanking();
        $this->load->view('ranking', $data);
    }
    public function cgPassword()
    {
        $curpaswd = md5($this->input->post('curpaswd'));
        $newpaswd = md5($this->input->post('newpaswd'));
        $rpnewpaswd = md5($this->input->post('rpnewpaswd'));
        $this->form_validation->set_rules('curpaswd', '"Mot de passe actuel"', 'trim|required');
        $this->form_validation->set_rules('newpaswd', '"Nouveau mot de passe"', 'trim|required');
        $this->form_validation->set_rules('rpnewpaswd', '"Répéter nouveau mot de passe"', 'trim|required');
        if ($curpaswd == $_SESSION['user_infos'][0]['user_password'] && $newpaswd == $rpnewpaswd)
        {
            $data = [
                'user_name' => $_SESSION['user_infos'][0]['user_name'],
                'user_mail' => $_SESSION['user_infos'][0]['user_mail']
            ];
            $this->User_model->updatePassword($newpaswd);
            $this->cgPasswordMail($data);
            $this->session->set_flashdata('change', 'Ca a fonctionné ! reconnecte-toi maintenant.');
            $this->logout();
        }
        else
        {
            $this->session->set_flashdata('change', 'Tu n\'as pas rempli correctement les champs');
            redirect('/compte');
        }
    }
    public function cgMail()
    {
        $curmail = $this->input->post('curmail');
        $newmail = $this->input->post('newmail');
        $this->form_validation->set_rules('curmail', '"Email actuel"', 'trim|required');
        $this->form_validation->set_rules('newmail', '"Nouvel email"', 'trim|required');
        if ($curmail == $_SESSION['user_infos'][0]['user_mail'])
        {
            $data = [
                'user_name' => $_SESSION[0]['user_name'],
                'curmail' => $curmail,
                'newmail' => $newmail
            ];
            $this->User_model->updateMail($newmail);
            $this->cgMailMail($data);
            $this->session->set_flashdata('change', 'Ca a fonctionné ! Déconnecte-toi pour voir les changements appliqués.');
            $this->account();
        }
        else
        {
            $this->session->set_flashdata('change', 'Tu n\'as pas rempli correctement les champs');
            redirect('/compte');
        }
    }

    protected function cgPasswordMail($data)
    {
        require 'asset/PHPMailer-master/PHPMailerAutoload.php';
        $mail = new PHPMailer;
        //$mail->SMTPDebug = 3;                               // Enable verbose debug output
        $mail->CharSet = 'UTF-8';
        $mail->setFrom('lafermededidier@gmail.com', 'La ferme de Didier');
        $mail->addAddress($data['user_mail'], $data['user_name']);     // Add a recipient
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Changement de mot de passe';
        $mail->Body    = $data['user_name'].', Tu as changé ton mot de passe. Si tu n\'es pas à l\origine de cette action, fais-le savoir en répondant à ce mail';
        $mail->AltBody = $data['user_name'].', Tu as changé ton mot de passe. Si tu n\'es pas à l\origine de cette action, fais-le savoir en répondant à ce mail';
        if($mail->send())
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
    protected function cgMailMail($data)
    {
        require 'asset/PHPMailer-master/PHPMailerAutoload.php';
        $mail = new PHPMailer;
        //$mail->SMTPDebug = 3;                               // Enable verbose debug output
        $mail->CharSet = 'UTF-8';
        $mail->setFrom('lafermededidier@gmail.com', 'La ferme de Didier');
        $mail->addAddress($data['curmail'], $data['user_name']);     // Add a recipient
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Changement d\'e-mail';
        $mail->Body    = $data['user_name'].', tu as changé ton e-mail. Celui-ci est maintenant <b>'.$data['newmail'].'</b>. <br/><br/> Si tu n\'es pas à l\origine de cette action, fais-le savoir en répondant à ce mail';
        $mail->AltBody = $data['user_name'].', tu as changé ton e-mail. Celui-ci est maintenant <b>'.$data['newmail'].'</b>. <br/><br/> Si tu n\'es pas à l\origine de cette action, fais-le savoir en répondant à ce mail';
        if($mail->send())
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
    public function logout()
    {
        $this->session->sess_destroy();
        redirect('/connexion');
    }
    public function manageUsers()
    {
        if($_SESSION['user_infos'][0]['user_admin'] >= 1)
        {
            $data['users'] = $this->User_model->getAllUsers();
            $data['enigmes'] = $this->User_model->getAllEnigmes();
            $this->load->view('user/admin', $data);
        }
        else
        {
            show_404();
        }
    }
    public function setAdmin($userId, $status)
    {
        if($_SESSION['user_infos'][0]['user_admin'] >= 1)
        {
            $this->User_model->setAdminModel($userId, $status);
            redirect('/admin');
        }
        else
        {
            show_404();
        }
    }
    public function registration()
    {
        $this->load->view('user/registration');
    }
    public function registrationHandler()
    {
        $this->form_validation->set_rules('login', 'Pseudo', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required|md5');
        $this->form_validation->set_rules('cpassword', 'Password Confirmation', 'required|md5');
        $this->form_validation->set_rules('email', 'Email', 'required');
        if ($this->form_validation->run() == TRUE)
        {
            $data = array(
                'user_name' => $this->input->post('login'),
                'user_password' => $this->input->post('password'),
                'user_mail' => $this->input->post('email')
            );
            if($this->User_model->userExists($data))
            {
                if($this->input->post('password') == $this->input->post('cpassword'))
                {
                    if ($this->User_model->insertUser($data) == TRUE)
                    {
                        $this->sendEmail($data);
                        $this->session->set_flashdata('change', '<div class="alert alert-success text-center">Tu es maintenant inscrit ! pour commencer à jouer, <a href="' . base_url() . 'connexion">connecte toi</a>.</div>');
                        redirect('/inscription');
                    }
                    else
                    {
                        $this->session->set_flashdata('change', '<div class="alert alert-danger text-center">L\'inscription n\'a pas marché !</div>');
                        redirect('/inscription');
                    }
                }
                else
                {
                    $this->session->set_flashdata('change', '<div class="alert alert-danger text-center">Les mots de passe doivent être identiques !</div>');
                    redirect('/inscription');
                }
            }
            else
            {
                $this->session->set_flashdata('change', 'Cet e-mail ou ce pseudo est déjà utilisé');
                redirect('/inscription');
            }
        }
        else
        {
            $this->load->view('user/registration');
            //insert the user registration details into database
            // insert form data into database
        }
    }
    public function sendEmail($data)
    {
        require 'asset/PHPMailer-master/PHPMailerAutoload.php';
        $mail = new PHPMailer;
        //$mail->SMTPDebug = 3;                               // Enable verbose debug output
        $mail->CharSet = 'UTF-8';
        $mail->setFrom('lafermededidier@gmail.com', 'La ferme de Didier');
        $mail->addAddress($data['user_mail'], $data['user_name']);     // Add a recipient
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Inscrption sur La Ferme de Didier !';
        $mail->Body    = $data['user_name'].', Bienvenue sur la ferme de didier ! <br/> Pour commencer à jouer, <a href="'.base_url().'/connexion">connecte-toi</a>';
        $mail->AltBody = $data['user_name'].'Bienvenue sur la ferme de didier ! <br/> Pour commencer à jouer, <a href="\'.base_url().\'/connexion">connecte-toi</a>';
        if($mail->send())
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
    private function verify($hash=NULL)
    {
        if ($this->user_model->verifyEmailID($hash))
        {
            $this->session->set_flashdata('verify_msg','<div class="alert alert-success text-center">Your Email Address is successfully verified! Please login to access your account!</div>');
            redirect('/inscription');
        }
        else
        {
            $this->session->set_flashdata('verify_msg','<div class="alert alert-danger text-center">Sorry! There is error verifying your Email Address!</div>');
            redirect('/inscription');
        }
    }
}