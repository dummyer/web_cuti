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
				<h4>Request Cuti</h4>
			</div>
		</div>
        <div class="row">
          <!-- /.col -->
          <div class="col-md-6 text-left">
            <div class="card">
               
                <div class="card-body">
                  <!-- the events -->
                  <div>
					<form id="formReqCuti" action="#" enctype='multipart/form-data'>
					
					<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
						<table class="table table-sm table-borderless" style="width:100%">
							<tr>
								<td>Kategori Cuti</td>
								<td style="width:1%">:</td>
								<td>
									<select class="form-control" name="type_cuti" required>
										<option value="" selected disabled>--Pilih Kategori Cuti--</option>
										@for($i=0;$i<count($getKategoriCuti);$i++)
											<option value="{{ $getKategoriCuti[$i]['id_type_cuti'] }}">{{ $getKategoriCuti[$i]['nama_type_cuti'] }}</option>
										@endfor
									</select>
								</td>
							</tr>
							<tr>
								<td>Tanggal Cuti</td>
								<td style="width:1%">:</td>
								<td>
									<div class="row">
										<div class="col col-sm-5">
											<input type="date" class="form-control" name="first_date" min="<?php echo Date('Y-m-d'); ?>" required />
										</div>
										<div class="col col-sm-2 text-center">
											<i class="fas fa-angle-double-right"></i>
										</div>
										<div class="col col-sm-5">
											<input type="date" class="form-control" name="last_date" min="<?php echo Date('Y-m-d'); ?>" required />
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td>Deskripsi cuti (Optional)</td>
								<td style="width:1%">:</td>
								<td>
									<textarea class="form-control" name="desc_cuti"></textarea>
								</td>
							</tr>
							<tr>
								<td>Upload Dokumen (Optional)<br><font color="red">Maks. size 1 MB</font></td>
								<td style="width:1%">:</td>
								<td>
									<input type="file" id="uploadedFile" name="uploadedFile" class="form-control" />
								</td>
							</tr>
							<tr>
								<td class="text-right" colspan="3">
									<input type="submit" class="btn btn-primary" />
								</td>
							</tr>
						</table>
					</form>
                  </div>
                </div>
                <!-- /.card-body -->
              </div>
            <!-- /.card -->
          </div><!-- /.col -->
          <div class="col-md-6 text-left">
            <div class="card">
               
                <div class="card-body">
                  <!-- the events -->
                   <div class="card card-primary card-outline card-outline-tabs">
					  <div class="card-header p-0 border-bottom-0">
						<ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
							@for($i=0;$i<count($getReqCuti_OneUser);$i++)
								<li class="nav-item">
									<a class="nav-link <?php if($i == 0){ echo "active"; } ?>" id="tab_<?php echo $getReqCuti_OneUser[$i]['id_cuti']; ?>" data-toggle="pill" href="#cust_tab_<?php echo $getReqCuti_OneUser[$i]['id_cuti']; ?>" role="tab" aria-controls="cust_tab_<?php echo $getReqCuti_OneUser[$i]['id_cuti']; ?>" aria-selected="true">{{$getReqCuti_OneUser[$i]['nama_type_cuti'].' - '.$getReqCuti_OneUser[$i]['tgl_mulai_cuti']}}</a>
								</li>
							@endfor
						 
						</ul>
					  </div>
					  <div class="card-body">
						<div class="tab-content" id="custom-tabs-four-tabContent">
							@for($i=0;$i<count($getReqCuti_OneUser);$i++)
								
								  <div class="tab-pane fade show <?php if($i == 0){ echo "active"; } ?>" id="cust_tab_<?php echo $getReqCuti_OneUser[$i]['id_cuti']; ?>" role="tabpanel" aria-labelledby="tab_<?php echo $getReqCuti_OneUser[$i]['id_cuti']; ?>">
									 <table>
										<tr>
											<td>Kategori Cuti</td>
											<td style="width:1%">:</td>
											<td>{{$getReqCuti_OneUser[$i]['nama_type_cuti']}}</td>
										</tr>
										<tr>
											<td>Tanggal Request</td>
											<td style="width:1%">:</td>
											<td>{{ date('d F Y H:i', strtotime($getReqCuti_OneUser[$i]['requested_date'])) }}</td>
										</tr>
										<tr>
											<td>Tanggal Cuti</td>
											<td style="width:1%">:</td>
											<td>
												{{ date('d F Y', strtotime($getReqCuti_OneUser[$i]['tgl_mulai_cuti'])) }}
												-
												{{ date('d F Y', strtotime($getReqCuti_OneUser[$i]['tgl_mulai_cuti'].' +'.($getReqCuti_OneUser[$i]['jumlah_hari_cuti']-1).' days')) }}
											</td>
										</tr>
										<tr>
											<td>Jumlah Hari Cuti</td>
											<td style="width:1%">:</td>
											<td>{{$getReqCuti_OneUser[$i]['jumlah_hari_cuti']}} Hari</td>
										</tr>
										<tr>
											<td>Deskripsi Cuti</td>
											<td style="width:1%">:</td>
											<td>{{$getReqCuti_OneUser[$i]['desc_cuti']}}</td>
										</tr>
										<tr>
											<td>Approved By</td>
											<td style="width:1%">:</td>
											<td>{{$getReqCuti_OneUser[$i]['hr_nik_approve']}}</td>
										</tr>
									 </table>
									 
									 <div class="timelines">
										<ol>
										  <li class="dotgreen">Requested</li>
										  <li class="<?php 
											if($getReqCuti_OneUser[$i]['status'] != 1 && $getReqCuti_OneUser[$i]['status'] != 3){
												echo "dotyellow";
											}
											else{
												echo "dotgreen";	
											} 
											?>">Review-ing</li>
										  <li class="<?php 
											if($getReqCuti_OneUser[$i]['status'] == 1){
												echo "dotgreen";
											}
											else if($getReqCuti_OneUser[$i]['status'] == 3){
												echo "dotred";
											}
											else{
												echo "dotgrey";	
											} 
											?>">
											
											<?php 
											if($getReqCuti_OneUser[$i]['status'] == 1){
												echo "Approved";
											}
											else if($getReqCuti_OneUser[$i]['status'] == 3){
												echo "Rejected";
											}
											else{
												echo "";	
											} 
											?>
											
											
											</li>
										</ol>
									</div>
								  </div>
								
								
							@endfor
						</div>
					  </div>
					  <!-- /.card -->
					</div>
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
</script>
</body>
</html>
