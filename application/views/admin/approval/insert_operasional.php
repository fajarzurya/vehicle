<script src="<?php echo base_url();?>asset/js/core/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(function(){
	var j;
	$(document).on('click','.zadd',function(){ //Dynamic Span Event Click
		j = document.getElementById("jenis").value;
		var a=this.id.split("^");
		// Tambahan Penyesuaian dari Select Option
		var ref;
		if(j==1){
			ref = document.getElementById('zdrop');
			var t = parseInt(document.getElementById('penumpang').value);
			var check = t+parseInt(a[3]);
			if(check>8){
				Swal.fire({
				  icon: 'error',
				  title: 'Oops...',
				  html: 'Kendaraan sudah <b>penuh</b>!'
				});
			}else{
				detail(ref,a,'zadd');
				$(this).fadeOut(100,function(){
					delRow(this,'ztable');
				});
				// sum();
				document.getElementById("penumpang").value = countx('zdrop');
			}
		}else if(j==2){
			ref = document.getElementById('zdrop2');
			if(ref.rows.length>2){
				Swal.fire({
				  icon: 'error',
				  title: 'Oops...',
				  html: '<b>Pemohon</b> tidak boleh <b>lebih dari 1</b>!'
				});
			}else{
				detail(ref,a,'zadd');
				$(this).fadeOut(500,function(){
					delRow(this,'ztable');
				});
			}
		}else{
			console.log(j);
		}
		// End Tambahan
	});
	$(document).on('click','.zdel',function(){ //Dynamic Span Event Click
		j = document.getElementById("jenis").value;
		var a=this.id.split("^");
		var ref = document.getElementById('ztable');
		detail(ref,a);
		if(j==1){
			$(this).fadeOut(100,function(){
				delRow(this,'zdrop');
				// sum();
				document.getElementById("penumpang").value = countx('zdrop');
			});
		}else{
			$(this).fadeOut(100,function(){
				delRow(this,'zdrop2');
			});
		}
	});
	// Penumpang Request Baru = 0
	$('button[type=submit]').click(function(e){
		j = document.getElementById("jenis").value;
		var count;
		if(j==1){
			count = countx('zdrop');
		}else{
			count = countx('zdrop2');
		}
		prevent(count,e);
	});
});
function prevent(c,e){
	if(c==0){
		Swal.fire({
			icon: 'error',
			title: 'Oops...',
			html: 'Data tidak bisa <b>disimpan</b>, <br> Harus ada <b>penumpang</b> pada transaksi!',
			showConfirmButton: false,
			timer: 5000
		});
		e.preventDefault();
	}
}
function countx(table){
	var ref = document.getElementById(table).rows;
	var p = 0;
	for(var r = 0; r<ref.length; r++){
		if(ref[r].id!='hidden'){
			var cell = parseInt(ref[r].cells[3].innerHTML);
			p += isNaN(cell) ? 0 : cell;
		}
	}
	return p;
}
// function detail(ref,a){
	// var row = ref.insertRow(-1);
	// var html = '<a href="" onclick="return false;"><i class="material-icons">delete_forever</i></a><input type="hidden" name="request[]" value=';
	// row.insertCell(0).innerHTML = html+''+a[0]+'>';
	// row.insertCell(1).innerHTML = a[1];
	// row.insertCell(2).innerHTML = a[2];
	// row.insertCell(3).innerHTML = a[3];
// }
function detail(ref,a,z){
	var row = ref.insertRow(-1);
	var spin, spout, icon, input, html;
	spout = '</span>';
	if(z=='zadd'){
		icon='delete_forever';
		spin = '<span class="zdel" id="'+a[0]+'^'+a[1]+'^'+a[2]+'^'+a[3]+'">';
		input='<input type="hidden" name="request[]" value="'+a[0]+'">';
		html = '<a href="" onclick="return false;"><i class="material-icons">'+icon+'</i></a>';
		row.insertCell(0).innerHTML = spin+html+input+spout;
	}else{
		icon='add_circle';
		spin = '<span class="zadd" id="'+a[0]+'^'+a[1]+'^'+a[2]+'^'+a[3]+'">';
		html = '<a href="" onclick="return false;"><i class="material-icons">'+icon+'</i></a>';
		row.insertCell(0).innerHTML = spin+html+spout;
	}
	row.insertCell(1).innerHTML = a[1];
	row.insertCell(2).innerHTML = a[2];
	row.insertCell(3).innerHTML = a[3];
}
// function sum(){
	// var ref = document.getElementById('zdrop');
	// var p = 0;
	// for(var r = 0; r<ref.rows.length; r++){
		// var cell = parseInt(ref.rows[r].cells[3].innerHTML);
		// p += isNaN(cell) ? 0 : cell;
	// }
	// document.getElementById('penumpang').value = p;
// }
function delRow(a,table){
	var i = a.parentNode.parentNode.rowIndex;
	document.getElementById(table).deleteRow(i);
}
function hideComp(){
	var o = document.getElementById("jenis").value;
	var php;
	if(o==1){
		$('#operasional').fadeIn(500);
		$('#reimburse').hide();
		php = '<?php echo $kode_k;?>';
		document.getElementById('no_trans').value = php;
	}else if(o==2){
		$('#operasional').hide();
		$('#reimburse').fadeIn(500);
		php = '<?php echo $kode_r;?>';
		document.getElementById('no_r').value = php;
	}else{
		$('#operasional').hide();
		$('#reimburse').hide();
	}
	$('#detail').fadeIn(500);
	// document.getElementById('no_trans').value = php;
}
</script>
<style type="text/css">
.file-input {
  position: absolute;
  opacity: 100;
}
</style>
<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
 <div class="content">	
 	<div class="container-fluid">
 		<div class="row">
 			<div class="col-md-12">
			  <div class="card">
				<div class="card-header card-header-rose card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">crop_rotate</i>
                  </div>
                  <h4 class="card-title">Transaksi Operasional</h4>
                </div>
				<!-- isi dengan table atau tampilan -->
                <div class="card-body">
					<div class="form-group">
                      <label class="bmd-label-floating">Jenis Operasional</label>
                      <select name="jenis" class="select2" style="width:80%" id="jenis" onchange="hideComp()">
						<option></option>
						<option value="1">Kendaraan</option>
						<option value="2">Reimburse</option>
					  </select>
                    </div>
				</div>
			  </div>
			</div>
			<div class="col-md-7">
			  <!------------------- INSERT OPERASIONAL -------------------->
			  <div class="card" id="operasional" style="display:none">
			    <div class="card-header card-header">
                  <h4 class="card-title">Operasional Kendaraan</h4>
                </div>
				<div class="card-body">
				<?php echo form_open('approval/insert_op/'); ?>
				 <div class="row">
                    <div class="col-lg-3 col-md-4">
                      <ul class="nav nav-pills nav-pills-primary flex-column" role="tablist">
						<li class="nav-item">
                          <a class="nav-link active" data-toggle="tab" href="#link_k" role="tablist">Kendaraan</a>
                        </li>
						<li class="nav-item">
                          <a class="nav-link" data-toggle="tab" href="#link_d1" role="tablist">Details</a>
                        </li>
                      </ul>
                    </div>
					<div class="col-md-8">
                      <div class="tab-content">
                        <div class="tab-pane active" id="link_k">
							<div class="row">
							  <label class="col-sm-3 col-form-label">Nomor</label>
							  <div class="col-sm-9">
								<div class="form-group">
								 <input type="text" class="form-control" name="no_trans" id="no_trans" readonly>
								</div>
							  </div>
							</div>
							<div class="row">
							  <label class="col-sm-3 col-form-label">Kendaraan</label>
							  <div class="col-sm-9">
								<div class="form-group">
								  <select name="kendaraan" class="select2" style="width:100%" required>
									<option></option>
									<?php 
									foreach($mobil_aktif->result() as $m){
										echo"<option value='".$m->NO_POLISI."'>".$m->NO_POLISI." - ".$m->NAMA_KENDARAAN."</option>";
									}
									?>
								  </select>
								</div>
							  </div>
							</div>
							<div class="row">
							  <label class="col-sm-3 col-form-label">Keterangan</label>
							  <div class="col-sm-9">
								<div class="form-group">
								 <input type="text" class="form-control" name="keterangan" id="keterangan">
								</div>
							  </div>
							</div>
						</div>
						<div class="tab-pane" id="link_d1">
						 <div class="table-responsive">
							<table class="table table-active" id="zdrop">
							 <thead>
							  <tr>
							   <th>#</th>
							   <th>Pemohon</th>
							   <th>Tujuan</th>
							   <th>Penumpang</th>
							  </tr>
							 </thead>
							</table>
						 </div>
						 <div class="row">
							<label class="col-sm-3 col-form-label">Penumpang</label>
							  <div class="col-sm-9">
								<div class="form-group">
								 <input type="text" class="form-control" name="penumpang" id="penumpang" placeholder="Jumlah Penumpang" readonly>
								</div>
							  </div>
						 </div>
						 <div class="form-group">
						  <button class="btn btn-primary" type="submit">
							  <span class="btn-label">
								<i class="material-icons">check</i>
							  </span>
							  Submit
						  </button>
						 </div>
						</div>
					  </div>
					</div>
				 </div>
				<?php echo form_close();?>
				</div>
			  </div>
			  
			  <!-------------------------------- INSERT REIMBURSE  --------------------------------->
			  <div class="card" id="reimburse" style="display:none">
			    <div class="card-header card-header">
                  <h4 class="card-title">Operasional Reimburse</h4>
                </div>
				<div class="card-body">
				<?php echo form_open_multipart('approval/insert_reimburse/'); ?>
				 <div class="row">
                    <div class="col-lg-3 col-md-4">
                      <ul class="nav nav-pills nav-pills-primary flex-column" role="tablist">
						<li class="nav-item">
                          <a class="nav-link active" data-toggle="tab" href="#link_r" role="tablist">Reimburse</a>
                        </li>
						<li class="nav-item">
                          <a class="nav-link" data-toggle="tab" href="#link_d2" role="tablist">Details</a>
                        </li>
                      </ul>
                    </div>
					<div class="col-md-8">
                      <div class="tab-content">
                        <div class="tab-pane active" id="link_r">
							<div class="row">
							  <label class="col-sm-3 col-form-label">Nomor</label>
							  <div class="col-sm-9">
								<div class="form-group">
								 <input type="text" class="form-control" name="no_reimburse" id="no_r" readonly>
								</div>
							  </div>
							</div>
							<div class="row">
							  <label class="col-sm-3 col-form-label">Keterangan</label>
							  <div class="col-sm-9">
								<div class="form-group">
								 <input type="text" class="form-control" name="keterangan" required>
								</div>
							  </div>
							</div>
							<div class="row">
							  <label class="col-sm-3 col-form-label">Nominal</label>
							  <div class="col-sm-9">
								<div class="form-group">
								 <input type="number" class="form-control" name="nominal" required>
								</div>
							  </div>
							</div>
							<div class="row">
							  <label class="col-sm-3 col-form-label">Tgl Pemberian</label>
							  <div class="col-sm-9">
								<div class="form-group">
								 <input type="text" class="form-control berangkatpicker" name="tgl_pemberian" value="<?php echo date('Y-m-d');?>">
								</div>
							  </div>
							</div>
							<div class="row">
							  <label class="col-sm-3 col-form-label">Lampiran</label>
							  <div class="col-sm-9">
								<div class="custom-file">
								 <input type="file" class="file-input" id="lampiran" name="lampiran" required>
								</div>
							  </div>
							</div>
						</div>
						<div class="tab-pane" id="link_d2">
						 <div class="table-responsive">
							<table class="table table-active" id="zdrop2">
							 <thead>
							  <tr>
							   <th>#</th>
							   <th>Pemohon</th>
							   <th>Tujuan</th>
							   <th>Penumpang</th>
							  </tr>
							 </thead>
							</table>
						 </div>
						 <div class="form-group">
						  <button class="btn btn-primary" type="submit">
							  <span class="btn-label">
								<i class="material-icons">check</i>
							  </span>
							  Submit
						  </button>
						 </div>
						</div>
					  </div>
					</div>
				 </div>
				<?php echo form_close();?>
				</div>
			  </div>
			</div>
			<!-------------------------------- DETAILS  --------------------------------->
			<div class="col-md-5" id="detail" style="display:none">
			  <div class="card">
				<div class="card-header card-header-info card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">loupe</i>
                  </div>
                  <h4 class="card-title">Details Overview</h4>
                </div>
				<div class="card-body">
				 <div class="table-responsive">
				  <table class="table table-striped table-sm" id="ztable">
					<thead>
						<tr>
						 <th>#</th>
						 <th>Pemohon</th>
						 <th>Tujuan</th>
						 <th>Penumpang</th>
						</tr>
					</thead>
					<tbody>
					<?php foreach($request->result() as $r){ ?>
						<tr>
						 <td>
							<span class="zadd" id="<?php echo $r->ID_REQUEST;?>^<?php echo $r->NAMA;?>^<?php echo $r->TUJUAN;?>^<?php echo $r->PENUMPANG;?>">
								<?php
									echo "<a href='' onclick='return false;'> <i class='material-icons'>add_circle</i></a>";
								?>
							</span>
						 </td>
						 <td><?php echo $r->NAMA;?></td>
						 <td><?php echo $r->TUJUAN;?></td>
						 <td><?php echo $r->PENUMPANG;?></td>
						</tr>
					<?php } ?>
					</tbody>
				  </table>
				 </div>
				</div>
			  </div>
			</div>
		</div>
	</div>