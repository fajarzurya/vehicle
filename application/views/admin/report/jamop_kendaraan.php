<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="content">	
 	<div class="container-fluid">
 		<div class="row">
 			<div class="col-lg-4 col-md-12">
			  <div class="card">
				<div class="card-header card-header-info text-center">
					<h4 class="card-title">Report</h4>
				</div>
				<div class="card-body">
				<?php echo form_open('report/kendaraan');?>
					<div class="form-group">
						<label class="bmd-label-floating">Tanggal Awal</label>
						<input type="text" class="form-control berangkatpicker" value="<?php echo date('01-m-Y');?>" name="date">
					</div>
					<div class="form-group">
						<label class="bmd-label-floating">Tanggal Akhir</label>
						<input type="text" class="form-control kembalipicker" value="<?php echo date('d-m-Y');?>" name="date1">
					</div>
				</div>
				<div class="card-footer justify-content-center">
						<button type="submit" class="btn btn-info btn-round">OK</button>&nbsp;&nbsp;
						<button type="reset" class="btn btn-round">Reset</button>
				</div>
                <?php echo form_close();?> 
              </div>
			</div>
			<div class="col-lg-8 col-md-12" id="table">
			<div class="card">
				<div class="card-header card-header-info text-center">
					<h4 class="card-title">List Kendaraan</h4>
					<?php $tanggal = explode('|', $tgl); ?>
					<p class="card-category">Data Kendaraan pada
					<?php echo $tanggal[0]." sampai ".$tanggal[1]; ?>
					</p>
				</div>
				<div class="card-body">
					<div class="card-body table-responsive">
						<table class="table table-hover">
							<thead class="text-info">
								<tr>
									<th>No</th>
									<th>No Polisi</th>
									<th>Kendaraan</th>
									<th>Waktu Operasional</th>
								</tr>
							</thead>
							<tbody>
								<?php
									$no = 1;
									foreach($op as $row){  
										$data = explode('|', $row);
								?>
								<tr>
									<td> <?php echo $no; $no++; ?></td>
									<td> <?php echo $data[0]; ?></td>
									<td> <?php echo $data[1]; ?></td>
									<td> <?php echo $data[2]; ?></td>
								</tr>
								<?php } ?>   
							</tbody>
						</table>
					</div>
				</div>
			</div>
			</div>
			<!-- ini adalah graph jon -->
			<div class="block">
				<div class="block-content collapse in">
					<div class="span12 chart">
						<h5><?php $tanggal = explode('|', $tgl);
						echo $tanggal[0]." s/d ".$tanggal[1]; ?>
						</h5>
					<div id="hero-bar" style="height: 250px;"></div>
					</div>
				</div>
            </div>
			<!-- akhir graph -->    
        </div>
	</div>
<script src="<?php echo base_url();?>asset/js/jquery.easy-pie-chart.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/moris-min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>asset/js/raphael-min.js"></script>
<script>
        // Morris Bar Chart
        Morris.Bar({
            element: 'hero-bar',
            data:[
			<?php foreach($op as $rows){  $datas = explode('|', $rows); ?>
                {device: '<?php echo $datas[0]; ?>' , sells: <?php echo $datas[2]; ?>},
                
           		
            <?php }?>],
            xkey: 'device',
            ykeys: ['sells'],
            labels: ['Total Jam'],
            barRatio: 0.4,
            xLabelMargin: 10,
            hideHover: 'auto',
            barColors: ["#3d88ba"]
        });

</script>
