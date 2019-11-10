<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<!-------------------------------------------------------------------------------------------------------------------------------->
<div class="content">	
 	<div class="container-fluid">
 		<div class="row">
		<a href="<?php echo base_url().''.$this->uri->segment(0).'approval/pending_request/';?>" class="made-with-mk">
			<div class="brand"><i class="material-icons">local_parking</i></div>
			<div class="made-with">Request <strong>Pending</strong></div>
		</a>
 			<div class="col-md-12">
			  <div class="card card-nav-tabs">
				<!-- Header -->
			  	<?php include "header.php";?>					    
				<!-- isi dengan table atau tampilan -->
				<div class="card-body">
				<div class="table-responsive">
					<table class="table table-hover" id="dataTables-id">
						<thead class=" text-primary">
							<tr>
								<th>No</th>
								<th>Pemohon</th>
								<th>Waktu Keluar</th>
								<th>Waktu Kembali</th>
								<th>Waktu Request</th>
								<th>Tujuan</th>
								<th>Keperluan</th>
								<th>Opsi</th>
							</tr>
						</thead>
						<tbody>
						<?php
						echo form_open('approval/insert_op/');
						$no = 1;
						foreach($approval->result() as $row){ 
						?>
							<tr>
								<td><?php echo $no; ?></td>
								<td><?php echo $row->NAMA; ?></td>
								<td><?php echo $row->TGL_BERANGKAT; ?></td>
								<td><?php echo $row->TGL_KEMBALI; ?></td>
								<td><?php echo $row->WAKTU_REQUEST; ?></td>
								<td><?php echo $row->TUJUAN; ?></td>
								<td><?php echo $row->KEPERLUAN; ?></td>
								<td>
									<a href="<?php echo base_url().''.$this->uri->segment(0).'approval/insert_op/'.$row->ID_REQUEST;?>" class="btn btn-success btn-fab btn-round btn-sm">
										<i class="material-icons">spellcheck</i>
									</a>
								</td>
							</tr>
							<?php 
								$no++;
								} 
								echo form_close();
							?>
						</tbody>
					</table>
				  </div>
				</div>
			  </div>
			</div>
		</div>
	</div>
	
	<!-- End List -->