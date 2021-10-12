<!DOCTYPE html>
<html lang="en">
@include('_template/header')
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
				<h4>Create Cuti</h4>
			</div>
		</div>
        <div class="row">
          <!-- /.col -->
          <div class="col-md-12 text-left">
            <div class="card">
               
                <div class="card-body">
                  <!-- the events -->
                  <div>
					<form id="formCreateCuti" action="#" enctype='multipart/form-data'>
					
					<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
						<table class="table table-sm table-borderless" style="width:50%">
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
								<td>User Cuti</td>
								<td style="width:1%">:</td>
								<td>
									<select class="form-control" name="user_cuti" required>
										<option value="" selected disabled>--Pilih User Cuti--</option>
										<option value="99999">All User</option>
										@for($i=0;$i<count($getAllUser);$i++)
											<option value="{{ $getAllUser[$i]['nik_user'] }}">{{ $getAllUser[$i]['nik_user'].' - '.$getAllUser[$i]['nama_user'] }}</option>
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
$('#formCreateCuti').submit(function (e) {
	e.preventDefault();
	Swal.fire({
		  title: 'Are you sure?',
		  text: "Data cuti akan disubmit",
		  icon: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Confirm',
		  allowOutsideClick: false,
		}).then((result) => {
		  if (result.isConfirmed) {
			  var formData = new FormData(this);
			 submitCreateCuti(formData);
			
		  }
		});
});

function submitCreateCuti(formData){
	Swal.fire({
			title: 'Please wait...',
			allowOutsideClick: false,
			showConfirmButton: false
		});
		swal.showLoading();
	$.ajax({
		url: "<?php echo url('/'); ?>/create_cuti_hr/createCuti",
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
