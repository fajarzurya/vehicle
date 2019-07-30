<?php
	/**
	  File home.php
	  author IT-PJBS
	*/
   if(!defined('BASEPATH'))
     exit('No direct script allowed');
	 
	class Request extends CI_Controller
	{
	   public function __construct()
	   {
	      parent::__construct();
	   }
	 
		public function index()
		{
		   //slider-------------
		   //$this->load->model('slidermodel');
		   //$data['dataslider'] = $this->slidermodel->get_all_slider();
		 
		   $this->load->model('usermodel');
		   $this->load->model('requestmodel');
		   
		   $level = $this->session->userdata('level');
		   $data['menu'] = $this->usermodel->get_menu_for_level($level);
		   $this->auth->restrict();
		   $this->auth->check_menu(3);  
		   
		   $this->load->library('form_validation');
		   //$this->form_validation->set_rules('nama', 'nama', 'trim|required');
		   $this->form_validation->set_rules('date', 'date', 'trim|required');
		   $this->form_validation->set_rules('jam_out', 'jam_out', 'trim|required');
		   $this->form_validation->set_rules('date1', 'date1', 'trim|required');
		   $this->form_validation->set_rules('jam_in', 'jam_in', 'trim|required');
   		   $this->form_validation->set_rules('id_tipe_spj', 'id_tipe_spj', 'trim|required');
		   $this->form_validation->set_rules('ket_tujuan', 'ket_tujuan', 'trim|required');
		   $this->form_validation->set_rules('jml_penumpang', 'jml_penumpang', 'trim|required');
		   $this->form_validation->set_rules('keperluan', 'keperluan', 'trim|required');
		   $this->form_validation->set_error_delimiters(' <span style="color:#FF0000">', '</span>');
		   
		   $waktu_berangkat = $this->input->post('date')." ".$this->input->post('jam_out');
		   $waktu_kembali = $this->input->post('date1')." ".$this->input->post('jam_in');
		   
		   /*if(strtotime($waktu_berangkat) > strtotime($waktu_kembali))
		      echo $waktu_berangkat." > ".$waktu_kembali."&nbsp; Tidak boleh!!<br/>";
		   else if(strtotime($waktu_berangkat) <= strtotime($waktu_kembali))
		      echo $waktu_berangkat." <= ".$waktu_kembali."&nbsp; Sip!<br/>";*/
		 
		   if (strtotime($waktu_berangkat) > strtotime($waktu_kembali) || $this->form_validation->run() == FALSE)
		   {
			  $level = $this->session->userdata('level');
		  	  $data['menu'] = $this->usermodel->get_menu_for_level($level);
			  $this->template->set('title','Input Form C | eFormC');
		   	  //$this->template->load('template','user/request/insert_request',$data);  
			  $this->template->load('template_refresh','user/request/insert_request',$data);  
			  
		   }
		   else
		   {
		   	  $cuap=$this->input->post('keperluan');
              $keperluan=nl2br($cuap);
			  $cuap1=$this->input->post('ket_tujuan');
			  $ket_tujuan=nl2br($cuap1);
		   	  $pemohon = $this->session->userdata('user_id');
			  $id = $this->session->userdata('nid');
		   	  $subdit = $this->requestmodel->ambil_nama($id);
			  
			  $this->load->model('datemodel');
			  //$tgl_berangkat = $this->datemodel->format_tanggal($this->input->post('date'));
			  //$tgl_kembali = $this->datemodel->format_tanggal($this->input->post('date1'));
			  $tgl_berangkat = $this->input->post('date');
			  $tgl_kembali =  $this->input->post('date1');
			  
			  //konversi 03 ke Mar
			  $tgl_berangkat = date('d-M-Y',strtotime($tgl_berangkat));
			  $tgl_kembali = date('d-M-Y',strtotime($tgl_kembali));
			  
			  
			  $data_req = array(
				 'ID_PEMOHON' =>$pemohon,
				 'ATAS_NAMA' =>$subdit,
				 'TGL_BERANGKAT'   =>$tgl_berangkat,
				 'TGL_KEMBALI'   =>$tgl_kembali,
				 'JAM_KELUAR'   =>$this->input->post('jam_out'),
				 'JAM_KEMBALI'   =>$this->input->post('jam_in'),
				 'KETERANGAN_TUJUAN'   =>$ket_tujuan,
				 'JML_PENUMPANG'   =>$this->input->post('jml_penumpang'),
				 'KEPERLUAN'   =>$keperluan,
				 'ID_TIPE_SPJ'   =>$this->input->post('id_tipe_spj'),
				 'SUBDIT' => $this->session->userdata('subdit')
				
			  );
			  $this->requestmodel->insert_data_request($data_req);
			  // kembalikan ke halaman manajemen user
			  redirect('request/success');
		   }
		}
		
		public function success()
		{
		  //slider-------------
		   //$this->load->model('slidermodel');
		   //$data['dataslider'] = $this->slidermodel->get_all_slider();
		 
		   $this->load->model('usermodel');
		   $this->load->model('requestmodel');
		   
		   $level = $this->session->userdata('level');
		   $data['menu'] = $this->usermodel->get_menu_for_level($level);
		   
		   $this->auth->restrict();
		   $this->auth->check_menu(3);

		   $this->template->set('title','Request Sukses | eFormC');
		   $this->template->load('template','user/request/request_success',$data);  		   
		
		}
		//End of function success
		
		public function daftar_request()
		{
		   //slider-------------
		   $this->load->model('slidermodel');
		   $data['dataslider'] = $this->slidermodel->get_all_slider();
		 
		   $this->load->model('usermodel');
		   $this->load->model('requestmodel');
		   
		   $level = $this->session->userdata('level');
		   $data['menu'] = $this->usermodel->get_menu_for_level($level);
		   
		   $this->auth->restrict();
		   $this->auth->check_menu(3);
		   
		   $id = $this->session->userdata('user_id');
		   $data['request'] = $this->requestmodel->get_request($id);
		   
		   $this->template->set('title','Daftar Request | eFormC');
		   $this->template->load('template','user/request/daftar_request',$data);
		}
		//End of function daftar_request
		
		public function daftar_operasional()
		{
           $this->load->model('slidermodel');
		   $data['dataslider'] = $this->slidermodel->get_all_slider();
		   $this->load->model('usermodel');
		   $this->load->model('appr_admin_model');
		   
		   $level = $this->session->userdata('level');
		   $data['menu'] = $this->usermodel->get_menu_for_level($level);
		   
		   $id = $this->session->userdata('user_id');
		   $data['approval'] = $this->appr_admin_model->show_operasional_user($id);
		   
		   $this->auth->restrict();
		   $this->auth->check_menu(3); 
		   $this->template->set('title','Daftar Operasional | Aplikasi Monitoring Kendaraan Dinas');
		   $this->template->load('template','user/request/daftar_operasional',$data);
		}
		//End of function daftar_operasional
		
		public function daftar_sewa()
		{
		   $this->load->model('slidermodel');
		   $data['dataslider'] = $this->slidermodel->get_all_slider();
		   $this->load->model('usermodel');
		   $this->load->model('appr_admin_model');
		   
		   $level = $this->session->userdata('level');
		   $data['menu'] = $this->usermodel->get_menu_for_level($level);
		   
		   $id = $this->session->userdata('user_id');
		   $data['sewa'] = $this->appr_admin_model->show_sewa_user($id);
		   
		   $this->auth->restrict();
		   $this->auth->check_menu(3); 
		   $this->template->set('title','Daftar Sewa Operasional | Aplikasi Monitoring Kendaraan Dinas');
		   $this->template->load('template','user/request/daftar_sewa',$data);
		
		}
		//End of function daftar_sewa
		
		public function daftar_voucher()
		{
		   $this->load->model('slidermodel');
		   $data['dataslider'] = $this->slidermodel->get_all_slider();
		   $this->load->model('usermodel');
		   $this->load->model('appr_admin_model');
		   
		   $level = $this->session->userdata('level');
		   $data['menu'] = $this->usermodel->get_menu_for_level($level);
		   
		   $id = $this->session->userdata('user_id');
		   $data['voucher'] = $this->appr_admin_model->show_voucher_user($id);
		   
		   $this->auth->restrict();
		   $this->auth->check_menu(3); 
		   $this->template->set('title','Daftar Operasional | Aplikasi Monitoring Kendaraan Dinas');
		   $this->template->load('template','user/request/daftar_voucher',$data);
		}
		//End of function daftar_voucher
		
		public function daftar_reimburse()
		{
		   $this->load->model('slidermodel');
		   $data['dataslider'] = $this->slidermodel->get_all_slider();
		   $this->load->model('usermodel');
		   $this->load->model('appr_admin_model');
		   
		   $level = $this->session->userdata('level');
		   $data['menu'] = $this->usermodel->get_menu_for_level($level);
		   
		   $id = $this->session->userdata('user_id');
		   $data['reimburse'] = $this->appr_admin_model->show_reimburse_user($id);
		   
		   $this->auth->restrict();
		   $this->auth->check_menu(3); 
		   $this->template->set('title','Daftar Operasional | Aplikasi Monitoring Kendaraan Dinas');
		   $this->template->load('template','user/request/daftar_reimburse',$data);
		}
	    //End of function daftar_reimburse
	}
	//End of class Home
	?>