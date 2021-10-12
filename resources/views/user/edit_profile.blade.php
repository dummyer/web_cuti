<!DOCTYPE html>
<html lang="en">
@include('_template/header')
<style>

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
				<h4>Profile</h4>
			</div>
		</div>
        <div class="row">
          <!-- /.col -->
          <div class="col-md-12 text-left">
            <div class="card">
               
                <div class="card-body">
                  <!-- the events -->
				 <div class="card card-primary card-outline card-outline-tabs">
              <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-four-profile-tab" data-toggle="pill" href="#custom-tabs-four-profile" role="tab" aria-controls="custom-tabs-four-profile" aria-selected="true">Profile</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-four-editpass-tab" data-toggle="pill" href="#custom-tabs-four-editpass" role="tab" aria-controls="custom-tabs-four-editpass" aria-selected="false">Edit Password</a>
                  </li>
               
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content" id="custom-tabs-four-tabContent">
                  <div class="tab-pane fade show active" id="custom-tabs-four-profile" role="tabpanel" aria-labelledby="custom-tabs-four-profile-tab">
                     <table class="table table-sm table-borderless" style="width:50%">
						 <tr>
							 <td style="width:20%">NIK</td>
							 <td style="width:1%">:</td>
							 <td>{{$getOneUser[0]['nik_user']}}</td>
						 </tr>
						 <tr>
							 <td>Nama</td>
							 <td style="width:1%">:</td>
							 <td>{{$getOneUser[0]['nama_user']}}</td>
						 </tr>
						 <tr>
							 <td>Divisi</td>
							 <td style="width:1%">:</td>
							 <td>{{$getOneUser[0]['department']}}</td>
						 </tr>
						 <tr>
							 <td>Tanggal Join</td>
							 <td style="width:1%">:</td>
							 <td>{{ date('d F Y', strtotime($getOneUser[0]['join_date'])) }}</td>
						 </tr>
					 </table>
                  </div>
                  <div class="tab-pane fade" id="custom-tabs-four-editpass" role="tabpanel" aria-labelledby="custom-tabs-four-editpass-tab">
					<form id="formEditPassword" action="#">
						<table class="table table-sm table-borderless" style="width:50%">
							 <tr>
								 <td style="width:30%">Old Password</td>
								 <td style="width:1%">:</td>
								 <td><input type="password" name="old_password" class="form-control" placeholder="Old Password" required /></td>
							 </tr>
							 <tr>
								 <td style="width:20%">New Password</td>
								 <td style="width:1%">:</td>
								 <td><input type="password" name="new_password" class="form-control" placeholder="New Password" required /></td>
							 </tr>
							 
							 <tr>
								 <td style="width:20%">Re-type New Password</td>
								 <td style="width:1%">:</td>
								 <td><input type="password" name="new_password2" class="form-control" placeholder="Re-type New Password" /></td>
							 </tr>
							 
							 <tr>
								 <td colspan="3" class="text-right"><input class="btn btn-primary" type="submit" class="form-control" value="Edit Password" required /></td>
							 </tr>
							 
						 </table>
					 </form>
                  </div>
                  
                </div>
              </div>
              <!-- /.card -->
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
$('#formEditPassword').submit(function (e) {
	e.preventDefault();
	Swal.fire({
		  title: 'Are you sure?',
		  text: "Password Anda akan diganti",
		  icon: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Confirm',
		  allowOutsideClick: false,
		}).then((result) => {
		  if (result.isConfirmed) {
			  var formData = new FormData(this);
			 submitEditPassword(formData);
			
		  }
		});
});


function submitEditPassword (formData){
	Swal.fire({
			title: 'Please wait...',
			allowOutsideClick: false,
			showConfirmButton: false
		});
		swal.showLoading();
	$.ajax({
		url: "<?php echo url('/'); ?>/profile/editPassword",
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
				  title: "Old password salah!",
				  icon: "error",
				  allowOutsideClick: false,
				})
			}else if(isi.responseText == "3"){
				Swal.fire({
				  title: "Password Not Match",
				  icon: "error",
				  allowOutsideClick: false,
				})
			}
			else{
				Swal.fire({
									  position: 'center',
									  icon: 'error',
									  title: 'Edit password gagal, silakan coba lagi',
										allowOutsideClick: false,
									});
			}
		},error: function(XMLHttpRequest, textStatus, errorThrown) { 
							Swal.fire({
									  position: 'center',
									  type: 'error',
									  title: 'Edit password gagal, silakan coba lagi',
									  showConfirmButton: false,
									  timer: 1500
									});
						}
	
	});
}
</script>
</body>
</html>
