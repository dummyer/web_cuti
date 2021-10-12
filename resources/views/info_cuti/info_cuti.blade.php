<!DOCTYPE html>
<html lang="en">
@include('_template/header')
<style>
.timelines {
  overflow-x: hidden;
  padding: 20px 0;
}

.timelines ol {
  width: 100%;
  transition: all 1s;
  margin:0;
  padding:0;
  display:flex;
  justify-content: space-between;
}

.timelines ol li {
  list-style:none;
  position: relative;
  text-align:center;
  flex-grow: 1;
  flex-basis: 0;
  padding: 0 5px;
}

.timelines ol li.dotgreen:before  {
  content:"";
  width:10px;
  height:10px;
  display:block;
  border-radius:50%;
  background: green;
  margin:0 auto 5px auto;
}

.timelines ol li.dotred:before  {
  content:"";
  width:10px;
  height:10px;
  display:block;
  border-radius:50%;
  background: red;
  margin:0 auto 5px auto;
}

.timelines ol li.dotgrey:before  {
  content:"";
  width:10px;
  height:10px;
  display:block;
  border-radius:50%;
  background: #ccc;
  margin:0 auto 5px auto;
}

.timelines ol li.dotyellow:before  {
  content:"";
  width:10px;
  height:10px;
  display:block;
  border-radius:50%;
  background: orange;
  margin:0 auto 5px auto;
}

.timelines ol li:not(:last-child)::after {
    content: "";
    width: calc(100% - 14px);
    height: 2px;
    display: block;
    background: #ccc;
    margin: 0;
    position: absolute;
    top: 4px;
    left: calc(50% + 7px);
}

</style>
<body class="hold-transition sidebar-mini layout-navbar-fixed layout-fixed layout-footer-fixed">
<div class="wrapper">
  <!-- Navbar -->
  @include('_template/navbar')
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  @include('_template/sidebar')

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
 <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Calendar</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Calendar</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
			<div class="col">
				<h4>Info Cuti</h4>
			</div>
		</div>
        <div class="row">
          <!-- /.col -->
         
          <div class="col-md text-left">
            <div class="card">
               
                <div class="card-body">
                  <!-- the events -->
                   <div class="card card-primary card-outline card-outline-tabs">
					  <div class="card-header p-0 border-bottom-0">
						<ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
							@for($i=0;$i<count($getOneUser_cuti6BulanTerakhir);$i++)
								<li class="nav-item">
									<a class="nav-link <?php if($i == 0){ echo "active"; } ?>" id="tab_<?php echo $getOneUser_cuti6BulanTerakhir[$i]['id']; ?>" data-toggle="pill" href="#cust_tab_<?php echo $getOneUser_cuti6BulanTerakhir[$i]['id']; ?>" role="tab" aria-controls="cust_tab_<?php echo $getOneUser_cuti6BulanTerakhir[$i]['id']; ?>" aria-selected="true">{{$getOneUser_cuti6BulanTerakhir[$i]['date']}}</a>
								</li>
							@endfor
						 
						</ul>
					  </div>
					  <div class="card-body">
						<div class="tab-content" id="custom-tabs-four-tabContent">
							@for($i=0;$i<count($getOneUser_cuti6BulanTerakhir);$i++)
								
								<div class="tab-pane fade show <?php if($i == 0){ echo "active"; } ?>" id="cust_tab_<?php echo $getOneUser_cuti6BulanTerakhir[$i]['id']; ?>" role="tabpanel" aria-labelledby="tab_<?php echo $getOneUser_cuti6BulanTerakhir[$i]['id']; ?>">
									<h4>Summary Cuti (Hari)</h4>
									<table class="table table-sm table-borderless" style="width:30%">
										<tr>
											<td>Approved</td>
											<td style="width:1%">:</td>
											<td>
												{{$getOneUser_cuti6BulanTerakhir[$i]['status']['approved']}}
											</td>
										</tr>
										<tr>
											<td>Pending</td>
											<td style="width:1%">:</td>
											<td>
												{{$getOneUser_cuti6BulanTerakhir[$i]['status']['pending']}}
											</td>
										</tr>
										<tr>
											<td>Rejected</td>
											<td style="width:1%">:</td>
											<td>
												{{$getOneUser_cuti6BulanTerakhir[$i]['status']['rejected']}}
											</td>
										</tr>
										
									</table>
									<br>
									
								</div>
								
								
							@endfor
						</div>
					  </div>
					  <!-- /.card -->
					  
					</div>
					<hr>
					<hr>
					<h4>History Cuti</h4>
									<table class="table table-sm" id="tbHistoryCuti">
										<thead>
											<tr>
												<th>Tanggal Request</th>
												<th>Tipe Cuti</th>
												<th>Tanggal Cuti</th>
												<th>Status</th>
											</tr>
										</thead>
										<tbody>
											
												@for($i=0;$i<count($getDetailCuti6BulanTerakhir);$i++)
													<?php
													if($getDetailCuti6BulanTerakhir[$i]['status'] == 1){
														$color = '#A9D4A9';
													}else if($getDetailCuti6BulanTerakhir[$i]['status'] == 2){
														$color = '#FFC24E';
													}else if($getDetailCuti6BulanTerakhir[$i]['status'] == 3){
														$color = '#F07575';
													}else{
														$color = '';
													}
													?>
													<tr>
														<td>{{date('d F Y H:i', strtotime($getDetailCuti6BulanTerakhir[$i]['requested_date']))}}</td>
														<td>{{ $getDetailCuti6BulanTerakhir[$i]['nama_type_cuti']}}</td>
														<td><a href="#" onclick="actionListCuti('view_desc', '<?php echo $getDetailCuti6BulanTerakhir[$i]['desc_cuti']; ?>', '<?php echo $getDetailCuti6BulanTerakhir[$i]['attachment']; ?>', '<?php echo $getDetailCuti6BulanTerakhir[$i]['requested_user']; ?>')">{{ $getDetailCuti6BulanTerakhir[$i]['tgl_mulai_cuti']}} ({{ $getDetailCuti6BulanTerakhir[$i]['jumlah_hari_cuti']}} Hari)</a></td>
													
														<td><b  style="color:<?php echo $color; ?>">{{ $getDetailCuti6BulanTerakhir[$i]['judul_status']}}</b></td>
													</tr>
												@endfor
											
										</tbody>
									</table>
          </div>
                </div>
                <!-- /.card-body -->
              </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
 
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->
@include('_template/footer')
<script>
$(document).ready(function(){
	
	oTable = $('#tbHistoryCuti').dataTable({
		filter: true,
        "paging":   true,
        "ordering": true,
        "info":     false,
		"searching": true,
        "pageLength": 5,
		"lengthChange": false,
    "ordering": false
	});
});
$('#formReqCuti').submit(function (e) {
	e.preventDefault();
	Swal.fire({
		  title: 'Are you sure?',
		  text: "Request cuti yang dipilih akan disubmit!",
		  icon: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Confirm',
		  allowOutsideClick: false,
		}).then((result) => {
		  if (result.isConfirmed) {
			  var formData = new FormData(this);
			 submitReqCuti(formData);
			
		  }
		});
});

function submitReqCuti(formData){
	Swal.fire({
			title: 'Please wait...',
			allowOutsideClick: false,
			showConfirmButton: false
		});
		swal.showLoading();
	$.ajax({
		url: "<?php echo url('/'); ?>/request_cuti/requestCuti",
		data: formData,
		processData: false,
		contentType: false,
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		type: 'POST',
		complete: function(isi){
			console.log(isi);
			if(isi.responseText == "1"){
				Swal.fire({
				  title: "Sukses",
				  icon: "success",
				  allowOutsideClick: false,
				}).then(() => {
					location.reload();
				});
			}else if(isi.responseText == "2"){
				Swal.fire({
				  title: "Ukuran file tidak boleh lebih dari 1 MB",
				  icon: "error",
				  allowOutsideClick: false,
				}).then(() => {
					location.reload();
				});
			}
			else{
				Swal.fire({
									  position: 'center',
									  icon: 'error',
									  title: 'Request gagal, silakan coba lagi',
										allowOutsideClick: false,
									});
			}
		},error: function(XMLHttpRequest, textStatus, errorThrown) { 
							Swal.fire({
									  position: 'center',
									  type: 'error',
									  title: 'Request gagal, silakan coba lagi',
									  showConfirmButton: false,
									  timer: 1500
									});
						}
	
	});
}

function actionListCuti(action, data_cuti, attachment, requested_user){
	if(action == 'view_desc'){
		var donlot = "";
		if(attachment != ""){
			donlot = "<br>Attachment :"+"<a target='_blank' class='btn btn-link' href='<?php echo url('/'); ?>/"+attachment+"'>View</a>";
		}
		Swal.fire({
		  //text: data_cuti,
		  html: "Deskripsi Cuti : "+data_cuti+"<br>Request Oleh : "+requested_user+donlot,
		  allowOutsideClick: false,
		});
	}
	
}

</script>
</body>
</html>
