<?php
   
   if(!defined('BASEPATH'))
     exit('No direct script allowed');
	 
	/**
	  File home.php
	  @author IT-PJBS
	  
	  Controller untuk user dan menu
	*/
	
	class Home extends CI_Controller
	{
	   public function __construct()
	   {
	      parent::__construct();
	   }
	   //End of constructor
	   
	   public function index()
	   {
	     if($this->auth->is_logged_in() == false){
			//echo "Test";
		   $this->login();
		 }
		 else
		 {
		    //Load model 'usermodel'
			$this->load->model('monitoring_model');
			$this->load->model('appr_admin_model');
			$this->load->model('usermodel');
			$this->load->model('requestmodel');
			//Level untuk user ini
			$nid = $this->session->userdata('nid');
			$level = $this->session->userdata('level');
			$id_subdit = $this->session->userdata('subdit_id');
			$data = array(
				'kendaraan' => $this->monitoring_model->kendaraan(),
				'sopir'		=> $this->monitoring_model->sopir(),
				'reimburse'	=> $this->monitoring_model->reimburse(),
				'requestx'	=> $this->monitoring_model->request(),
				'approval'	=> $this->monitoring_model->pending(0),
				'transaksi'	=> $this->monitoring_model->pending(1),
				'pending' 	=> $this->appr_admin_model->show_approval($nid),
				'request'	=> $this->requestmodel->get_request($nid),
				'peminjaman'=> $this->requestmodel->show_operasional_user($nid),
				'terpakai'	=> $this->monitoring_model->kendaraan_chart(2),
				'tersedia'	=> $this->monitoring_model->kendaraan_chart(1),
				'info'		=> $this->monitoring_model->info($nid)
			);
		    //Set variabel $title
		    $this->template->set('title', 'Sistem Informasi Pengelolaan Kendaraan Dinas');
			//Sementara Hide
			if($level == 1)
		      $this->template->load('template_refresh', 'admin/index', $data);
			else if($level == 2)
			  $this->template->load('template_refresh', 'manajer/index', $data);
			else if($level == 3)
			  // $this->template->load('template_refresh', 'manajer/index', $data);
			  $this->template->load('template_refresh', 'user/index', $data);
			else
			  $this->template->load('template_refresh', 'admin/index', $data);  
			 //$this->load->view('admin/sukses');
		 } //End of else
	      
	   }
	   //End of function index
	   
	   public function login()
	   {
	      $this->load->library('form_validation');
		  $this->form_validation->set_rules('username', 'Username', 'trim|required');
		  $this->form_validation->set_rules('password', 'Password', 'trim|required');
		  $this->form_validation->set_error_delimiters('<span style="color:#FF0000">','</span>');
		  
		  if($this->form_validation->run() == FALSE)
		  {
		     $this->template->set('title', 'Sistem Informasi Pengelolaan Kendaraan Dinas');
			 $this->template->load('template_2','login_form');
		  } //End of if
		  else
		  {
		     $username = $this->input->post('username');
			 $password = $this->input->post('password');
			 $respon = $this->auth->do_login($username,$password);
			 //$respon = $this->auth->portalAuthenticate($username,$password);
			 
			 if($respon){
				redirect('home/index');   //Lemparkan ke halaman index user 
			 } 
			 else
			 {
			    $this->template->set('title', 'Welcome!!');
			    //$data['login_info'] = 'Maaf, username dan password salah ';
				echo "<nav class='navbar navbar-transparent navbar-color-on-scroll fixed-top'>";
				echo "<div class='alert alert-danger'>";
				echo "<div class='container'>";
				echo "<b>Error Alert, Username dan Password Salah!</b>";
				echo "</div>";
				echo "</div>";
				echo "</nav>";
				//$this->template->load('template_2','login_form', $data);
				$this->template->load('template_2','login_form');
			 } 
		  }//End of else
		  
	   }
	   
	   public function logout()
	   {
	      if($this->auth->is_logged_in() == true)
		  {
		  /* DESTROY ALL SESSION */
		  //$wsdl = 'http://portal.pjbservices.com/index.php/portal_login?wsdl';
		  //$cl = new SoapClient($wsdl);
		  //$rv = $cl->destroyToken($this->session->userdata('nid'));	
		  
		  $this->auth->do_logout();  //Jika sedang login, destroy session
		  }	
		  redirect('home'); //Larikan ke halaman utama
	   }
	   //End of function logout
	   
	}
	//End of class Home
	
	/**
	   End of file: home.php
	   Location: ./application/controllers/home.php
	*/