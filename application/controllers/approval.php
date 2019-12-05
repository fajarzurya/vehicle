<?php
	/**
	  File home.php
	  author IT-PJBS
	*/
   if(!defined('BASEPATH'))
     exit('No direct script allowed');
	 
	class Approval extends CI_Controller
	{
	   public function __construct()
	   {
	      parent::__construct();
	   }
		public function index()
		{
		   $this->load->model('usermodel');
		   $this->load->model('appr_admin_model');
		   // $data['menu'] = $this->usermodel->get_menu_for_level($level);
		   $data['approval'] = $this->appr_admin_model->show_all_operasional();
		   $data['reimburse'] = $this->appr_admin_model->show_all_reimburse();
		   $data['voucher'] = $this->appr_admin_model->show_all_voucher();
		   $data['pending'] = $this->appr_admin_model->show_request();
		   
		   $this->auth->restrict();
		   $this->auth->check_menu(2); 
		   $this->template->set('title','List Transaksi');
		   $this->template->load('template_refresh','admin/approval/transaksi',$data);
		}
		
		public function print_form()
		{
		   $this->load->model('usermodel');
		   $this->load->model('requestmodel');
		   $this->load->model('appr_admin_model');
		   
		   $level = $this->session->userdata('level');
		   $id = $this->session->userdata('nid');
		   
		   $data['menu'] = $this->usermodel->get_menu_for_level($level);
		   
		   $this->auth->restrict();
		   $this->auth->check_menu(2); 
		   
		   $id = $this->uri->segment(3);
		   $data['surat'] = $this->appr_admin_model->get_data_surat($id);
		   
		   $level = $this->session->userdata('level');
		  
		   $this->load->view('admin/approval/print_admin',$data);
		}
		//End of function print_form
		
		function add_trans()
		{
		  $this->load->model('appr_admin_model');
	      $this->auth->restrict();
		  $this->auth->check_menu(2);
		  
		  $data['mobil_aktif'] = $this->appr_admin_model->get_mobil_aktif();
		  $data['request'] = $this->appr_admin_model->request();
		  $data['kode_k'] = 'K'.$this->appr_admin_model->generate_code('k');
		  $data['kode_r'] = 'R'.$this->appr_admin_model->generate_code('r');
		  $data['kode_v'] = 'V'.$this->appr_admin_model->generate_code('v');
		  
		  $this->template->set('title', 'Transaksi Operasional');
		  $this->template->load('template_refresh','admin/approval/insert_operasional', $data);
		}
		
		function insert_op()
		{
		  $this->load->model('usermodel');
		  $level = $this->session->userdata('level');
		  
		  $this->load->model('appr_admin_model');
		  
	      $this->auth->restrict();
		  $this->auth->check_menu(2);
		  $data = array(
			'ID_PEMINJAMAN' => $this->input->post('no_trans'),
			'NO_POLISI' => $this->input->post('kendaraan'),
			'STATUS' => '5',
			'KETERANGAN' => $this->input->post('keterangan')
			);
		  
		  $id_m	= $this->input->post('kendaraan');
		  $data_m = array(
			'STATUS' => '2' //Update Mobil Digunakan
		  );
		  
		  $this->appr_admin_model->insert_operasional($data);
		  $this->insert_detail();
		  $this->update_request();
		  $this->appr_admin_model->update_mobil($data_m,$id_m);
		  redirect('approval/');
		  
		}
		//End of function insert_op
		
		function insert_detail(){
			$no_trans = $this->input->post('no_trans');
			$id_req = $this->input->post('request');
			$data = array();
			$index = 0;
			foreach($id_req as $req){
				array_push($data,array(
					'ID_TRANS'=>$no_trans,
					'ID_REQUEST'=>$req,
					'TIPE'=>'PEMINJAMAN'
				));
				$index++;
			}
			$this->appr_admin_model->insert_detail($data);
		}
		
		function update_request(){
			$id_req = $this->input->post('request');
			$data = array();
			$i = 0;
			foreach($id_req as $req){
				array_push($data,array(
					'ID_REQUEST'=>$req,
					'STATUS'=>'3'
				));
				$i++;
			}
			$this->appr_admin_model->update_request($data);
		}
		
	   function update_op($id)
	   {  
		 $this->auth->restrict();
		 $this->auth->check_menu(1);
		 
		 $this->load->model('appr_admin_model');
		 
		 $param = $this->uri->segment(3);
		 $extension = explode("-", $param);
		 $id = $extension[0];
		 $id_s = $extension[1];
		 $id_m = $extension[2];
		 
		 $this->load->model('datemodel');
		 $tgl_kembali = $this->datemodel->format_tanggal($this->input->post('tgl_kembali')); 
		 
		 $tgl_kembali = date('d-M-Y',strtotime($tgl_kembali));
		 
		 $data2 = array(
		     'TGL_KEMBALI' => $tgl_kembali,
			 'JAM_KEMBALI'=> $this->input->post('jam_kembali'),
			 'ID_STATUS_OPERASIONAL' => '1'
			 );
			 
		 $data3 = array(
			 'ID_STATUS_SOPIR' => '1'
			 );
			 
		 $data4 = array(
			 'ID_STATUS_KENDARAAN' => '1'
			 );
			 
		 $this->appr_admin_model->update_operasional($data2,$id);
		 $this->appr_admin_model->update_sopir_2($data3,$id_s);
		 $this->appr_admin_model->update_mobil_2($data4,$id_m);
		 
		 //echo $id."-".$id_s."-".$id_m;
		 
		 redirect('approval/semua_approval');
	   }
	   //End of function semua_approval
		
	    function edit_operasional()
		{
		 $this->load->model('usermodel');
		 $this->load->model('appr_admin_model');
		   
		 $level = $this->session->userdata('level');
	       
		 $this->auth->restrict();
		 $this->auth->check_menu(2);
		 
		 $this->load->library('form_validation');
		 $this->form_validation->set_rules('id_sopir', 'id_sopir', 'trim|required');
		 $this->form_validation->set_rules('id_kendaraan', 'id_kendaraan', 'trim|required');
		  
		 $this->form_validation->set_error_delimiters('<span style="color:#FF0000">', '</span>');
		 
		 $ids = $this->uri->segment(3);
		 $extension = explode("-", $ids);
		 $id = $extension[0];
		 $id_s = $extension[1];
		 $id_m = $extension[2];
		 
		 //echo $extension[1]." ".$extension[2];
		 
		 $id_req = $this->appr_admin_model->get_id_request($id);
         $waktu_kembali = $this->appr_admin_model->get_waktu_kembali($id_req);
         $waktu_berangkat = $this->appr_admin_model->get_waktu_berangkat($id_req);
		  
		  if($this->form_validation->run() == FALSE)
           {
		      $data['menu'] = $this->usermodel->get_menu_for_level($level);
	          $data['op'] = $this->appr_admin_model->get_operasional_by_id($id);
			  //$data['status'] = $this->appr_admin_model->get_status_operasional();
			  
			  $data['current_sopir'] = $this->appr_admin_model->get_current_sopir($id_s);
			  $data['current_mobil'] = $this->appr_admin_model->get_current_mobil($id_m);
			  
              $data['driver_aktif'] = $this->appr_admin_model->get_driver_aktif();
              $data['mobil_aktif'] = $this->appr_admin_model->get_mobil_aktif();

              $data['mobil'] = $this->appr_admin_model->get_mobil_booked2($waktu_kembali);
              $data['sopir'] = $this->appr_admin_model->get_sopir_booked2($waktu_kembali);
			 
              $data['mobil2'] = $this->appr_admin_model->get_mobil_booked3($waktu_berangkat);
              $data['sopir2'] = $this->appr_admin_model->get_sopir_booked3($waktu_berangkat);
			  
		      $this->template->set('title', 'Form Edit Operasional | Aplikasi Monitoring Kendaraan Dinas');
			  $this->template->load('template','admin/approval/edit_operasional', $data);
		   } //End of if
           else
           {
		      //$status_op = $this->input->post('ID_STATUS_OPERASIONAL');
			  $id_sopir = $this->input->post('id_sopir');
			  $id_kendaraan = $this->input->post('id_kendaraan');
			  
			  /*if($status_op == 3)
			    $status_op = 1;*/
		 
			  $data2 = array(
				 'ID_SOPIR' => $id_sopir,
			     'ID_KENDARAAN' => $id_kendaraan
			  );
		  
			/*--------------------------------------------------------------------------*/
			//Mengubah status sopir dan kendaraan (lama) yang diganti
		    $cek_sopir = $this->appr_admin_model->check_sopir_booked($id_s, $id);
					 
		    if($cek_sopir) //Jika sopir masih dipesan pada operasional lain yg msh blum berangkat
			{
			   $data3 = array(
				  'ID_STATUS_SOPIR' => '5' //Mengupdate status Sopir dari 'Sedang Bertugas' ke 'Dipesan'
				);
			}
			else
			{
			   $data3 = array(
				  'ID_STATUS_SOPIR' => '1'  //Mengupdate status Sopir dari 'Sedang Bertugas' ke 'Tersedia'
			   );
			}		
					 
		    $cek_kendaraan = $this->appr_admin_model->check_kendaraan_booked($id_m,$id);
					 
			if($cek_kendaraan) //Jika kendaraan masih dipesan pada operasional lain yg msh blum berangkat
		    {
			    $data4 = array(
					'ID_STATUS_KENDARAAN' => '5'  //Mengupdate status Kendaraan dari 'Sedang digunakan' ke 'Dipesan'
				 );
		     }
			else
			{
			    $data4 = array(
				   'ID_STATUS_KENDARAAN' => '1'  //Mengupdate status Kendaraan dari 'Sedang digunakan' ke 'Tersedia'
				 );
			 } 
			 
			 if($id_s == 0)
			 {
			     $data3 = array(
			       'ID_STATUS_SOPIR' => '1'
			      );
			 }
			
			/*--------------------------------------------------------------------------*/
			
			//Mengubah status sopir dan kendaraan (baru) yang diganti
			 //Mengupdate status Sopir menjadi 'Stand by' 
			  $data5 = array(
				   'ID_STATUS_SOPIR' => '5'
			  );
			  
			  //Mengupdate status Kendaraan menjadi 'Stand by'
			  $data6 = array(
				  'ID_STATUS_KENDARAAN' => '5'
			  );		

				if($id_sopir == 0)
				 {
					 $data5 = array(
					   'ID_STATUS_SOPIR' => '1'
					  );
				 }		  
			
			/*--------------------------------------------------------------------------*/
			
			//echo "ID_SOPIR: ".$id_s." -> ".$id_sopir."<br/>";
			//echo "ID_KENDARAAN: ".$id_m." -> ".$id_kendaraan."<br/>";
				 
			$this->appr_admin_model->update_operasional($data2,$id);
			$this->appr_admin_model->update_sopir_2($data3,$id_s);
			$this->appr_admin_model->update_mobil_2($data4,$id_m);
			$this->appr_admin_model->update_sopir_2($data5,$id_sopir);
			$this->appr_admin_model->update_mobil_2($data6,$id_kendaraan);
				 
			redirect('approval/lihat_operasional');
			   
		   } //End of else		    	
		 
		}
		// End of function edit_operasional
		
	   function berangkat()
	   {
		 $this->load->model('usermodel');
		 $this->load->model('appr_admin_model');
		   
		 $level = $this->session->userdata('level');
	       
		 $this->auth->restrict();
		 $this->auth->check_menu(2);
		 
		 $ids = $this->uri->segment(3);
		 $extension = explode("-", $ids);
		 $id = $extension[0];
		 $id_s = $extension[1];
		 $id_m = $extension[2];
		
		//Cek apakah kendaraan dan sopir telah kembali apa belum
		$cek_kendaraan = $this->appr_admin_model->check_kendaraan_berangkat($id_m);
		$cek_sopir = $this->appr_admin_model->check_sopir_berangkat($id_s);

        //Jika kendaraan dan sopir sama2 telah kembali
        if(!$cek_kendaraan && !$cek_sopir)
        {
			  //Mendapatkan waktu saat ini
			  date_default_timezone_set('Asia/Bangkok');
			  $tgl_berangkat = date('d-m-Y');
			  $jam_keluar = date('H:i:s');
			  
			  $tgl_berangkat = date('d-M-Y',strtotime($tgl_berangkat));
			
			  //Mengupdate status operasional dari 'Stand by' ke 'Berangkat'
			  $data2 = array(
				  'ID_STATUS_OPERASIONAL' => '4',
				  'TGL_BERANGKAT' => $tgl_berangkat,
				  'JAM_KELUAR' => $jam_keluar
			  );
			  
			  //Mengupdate status Sopir dari 'Stand by' ke 'Sedang Bertugas'
			  $data3 = array(
				   'ID_STATUS_SOPIR' => '2'
			  );
			  
			  //Mengupdate status Kendaraan dari 'Stand by' ke 'Sedang Digunakan'
			  $data4 = array(
				  'ID_STATUS_KENDARAAN' => '2'
			  );		

				if($id_s == 0)
				 {
					 $data3 = array(
					   'ID_STATUS_SOPIR' => '1'
					  );
				 }		  
					   
			   $this->appr_admin_model->update_operasional($data2,$id);
			   $this->appr_admin_model->update_sopir_2($data3,$id_s);
			   $this->appr_admin_model->update_mobil_2($data4,$id_m);
     
        }
		else
		  echo '<script type="text/javascript">alert("Sopir/Kendaraan masih belum kembali pada Operasional lainnya!");</script>'; 
		  
		  //End of if cek_kendaraan && cek_sopir
		  
		  redirect('approval/lihat_operasional');	   		
		}
		// End of function berangkat
		
	   function kembali()
	   {
		 $this->load->model('usermodel');
		 $this->load->model('appr_admin_model');
		   
		 $level = $this->session->userdata('level');
	       
		 $this->auth->restrict();
		 $this->auth->check_menu(2);
		 
		 $ids = $this->uri->segment(3);
		 $extension = explode("-", $ids);
		 $id = $extension[0];
		 $id_s = $extension[1];
		 $id_m = $extension[2];
		
		  //Mendapatkan waktu saat ini
		  date_default_timezone_set('Asia/Bangkok');
          $tgl_kembali = date('d-m-Y');
          $jam_kembali = date('H:i:s');
		  
		  $tgl_kembali = date('d-M-Y',strtotime($tgl_kembali));
		
		  //Mengupdate status operasional dari 'Berangkat' ke 'Kembali'
		  $data2 = array(
			  'ID_STATUS_OPERASIONAL' => '1',
			  'TGL_KEMBALI' => $tgl_kembali,
              'JAM_KEMBALI' => $jam_kembali
		  );
		  
		   $cek_sopir = $this->appr_admin_model->check_sopir_booked($id_s, $id);
					 
		    if($cek_sopir) //Jika sopir masih dipesan pada operasional lain yg msh blum berangkat
			{
			   $data3 = array(
				  'ID_STATUS_SOPIR' => '5' //Mengupdate status Sopir dari 'Sedang Bertugas' ke 'Dipesan'
				);
			}
			else
			{
			   $data3 = array(
				  'ID_STATUS_SOPIR' => '1'  //Mengupdate status Sopir dari 'Sedang Bertugas' ke 'Tersedia'
			   );
			}		
					 
		    $cek_kendaraan = $this->appr_admin_model->check_kendaraan_booked($id_m,$id);
					 
			if($cek_kendaraan) //Jika kendaraan masih dipesan pada operasional lain yg msh blum berangkat
		    {
			    $data4 = array(
					'ID_STATUS_KENDARAAN' => '5'  //Mengupdate status Kendaraan dari 'Sedang digunakan' ke 'Dipesan'
				 );
		     }
			else
			{
			    $data4 = array(
				   'ID_STATUS_KENDARAAN' => '1'  //Mengupdate status Kendaraan dari 'Sedang digunakan' ke 'Tersedia'
				 );
			 } 
			 
			 if($id_s == 0)
			 {
			     $data3 = array(
			       'ID_STATUS_SOPIR' => '1'
			      );
			 }
				   
		   $this->appr_admin_model->update_operasional($data2,$id);
		   $this->appr_admin_model->update_sopir_2($data3,$id_s);
		   $this->appr_admin_model->update_mobil_2($data4,$id_m);
				 
		   redirect('approval/lihat_operasional');
			   		
		}
		// End of function kembali
		
		
		//-------------- Tambahan fungsi untuk Sewa, Voucher dan Reimburse -----------------------
		
		function insert_voucher()
		{
		  $this->load->model('usermodel');
		  $level = $this->session->userdata('level');
		  
		  $this->load->model('appr_admin_model');
		  
	      $this->auth->restrict();
		  $this->auth->check_menu(2);
		  
		  $this->load->library('form_validation');
		  $this->form_validation->set_rules('id_request', 'id_request', 'trim|required');
		  //$this->form_validation->set_rules('kode_voucher', 'kode_voucher', 'trim|required');
		  $this->form_validation->set_rules('tgl_pemberian', 'tgl_pemberian', 'trim|required');
		  
		  $this->form_validation->set_error_delimiters('<span style="color:#FF0000">', '</span>');
		  
		  $id_req = $this->uri->segment(3);
	      $data['approval'] = $this->appr_admin_model->show_request1($id_req);
		  
		  //echo $this->input->post('kode_voucher')." ".$this->input->post('id_request')
		  
		  if($this->form_validation->run() == FALSE)
		  { 			 
		     $data['menu'] = $this->usermodel->get_menu_for_level($level);
			 $data['jenis_operasional'] = $this->appr_admin_model->tampil_jenis_operasional();

		     $this->template->set('title', 'Form Tambah Subdit Baru Aplikasi Monitoring Kendaraan Dinas');
			 $this->template->load('template','admin/approval/insert_operasional', $data);
		  }
		  else
		  {
             $tgl_pemberian = $this->input->post('tgl_pemberian');		
             $tgl_pemberian = date('d-M-Y',strtotime($tgl_pemberian));
			  
		     $data = array(
			    'KODE_VOUCHER' => $this->input->post('kode_voucher'),
			 	'TGL_PEMBERIAN' => $tgl_pemberian,
			 	//'NILAI_VOUCHER' => $this->input->post('nilai_voucher'),
				'ID_REQUEST' => $this->input->post('id_request'),
				'KETERANGAN' => $this->input->post('keterangan')
			 );
			 
			 $id =  $this->input->post('id_request');
			 
			 $data2 = array(
			 'ID_STATUS_REQUEST' => '3'
			 );
			 
			 $this->appr_admin_model->insert_data_voucher($data);
			 $this->appr_admin_model->update_request_op($data2,$id);
			 
			 redirect('approval/lihat_voucher');
		  } //End of else
		   
		}
		//End of function insert_voucher
		
		function lihat_voucher()
		{
		   $this->load->model('usermodel');
		   $this->load->model('appr_admin_model');
		   
		   // $level = $this->session->userdata('level');
		   // $data['menu'] = $this->usermodel->get_menu_for_level($level);
		   $data['voucher'] = $this->appr_admin_model->show_all_voucher();
		   
		   $this->auth->restrict();
		   $this->auth->check_menu(2); 
		   $this->template->set('title','Daftar Voucher');
		   $this->template->load('template_refresh','admin/approval/data_voucher',$data);
		}
		// End of function lihat_voucher
		
		function insert_reimburse()
		{
		  $this->load->model('usermodel');
		  $level = $this->session->userdata('level');
		  
		  $this->load->model('appr_admin_model');
		  
	      $this->auth->restrict();
		  $this->auth->check_menu(2);
		  
		  $this->load->library('form_validation');
		  $this->form_validation->set_rules('id_request', 'id_request', 'trim|required');
		  $this->form_validation->set_rules('tgl_pemberian', 'tgl_pemberian', 'trim|required');
		  
		  $this->form_validation->set_error_delimiters('<span style="color:#FF0000">', '</span>');
		  
		  $id_req = $this->uri->segment(3);
	      $data['approval'] = $this->appr_admin_model->show_request1($id_req);
		  
		  if($this->form_validation->run() == FALSE)
		  { 			 
		     $data['menu'] = $this->usermodel->get_menu_for_level($level);
			 $data['jenis_operasional'] = $this->appr_admin_model->tampil_jenis_operasional();
			 $data['jenis_reimburse'] = $this->appr_admin_model->get_jenis_reimburse();

		     $this->template->set('title', 'Form Tambah Subdit Baru Aplikasi Monitoring Kendaraan Dinas');
			 $this->template->load('template','admin/approval/insert_operasional', $data);
		  }
		  else
		  {
             $tgl_pemberian = $this->input->post('tgl_pemberian');	
             $tgl_pemberian = date('d-M-Y',strtotime($tgl_pemberian));		 
			  
		     $data = array(
			    'ID_JENIS_REIMBURSE' => $this->input->post('id_jenis_reimburse'),
				'ID_REQUEST' => $this->input->post('id_request'),
				'KETERANGAN' => $this->input->post('keterangan'),
			 	'TGL_PEMBERIAN' => $tgl_pemberian			
			 );
			 
			 $id =  $this->input->post('id_request');
			 
			 $data2 = array(
			 'ID_STATUS_REQUEST' => '3'
			 );
			 
			 $this->appr_admin_model->insert_data_reimburse($data);
			 $this->appr_admin_model->update_request_op($data2,$id);
			 
			 redirect('approval/lihat_reimburse');
		  } //End of else
		  
		}
		//End of function insert_reimburse
		
		function lihat_reimburse()
		{
		   $this->load->model('usermodel');
		   $this->load->model('appr_admin_model');
		   
		   // $level = $this->session->userdata('level');
		   // $data['menu'] = $this->usermodel->get_menu_for_level($level);
		   $data['reimburse'] = $this->appr_admin_model->show_all_reimburse();
		   
		   $this->auth->restrict();
		   $this->auth->check_menu(2); 
		   $this->template->set('title','Daftar Reimburse');
		   $this->template->load('template_refresh','admin/approval/data_reimburse',$data);
		}
		// End of function lihat_reimburse
		

	}
	//End of class Approval
	?>