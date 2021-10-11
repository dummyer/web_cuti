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
				<h4>Permintaan Cuti</h4>
			</div>
		</div>
        <div class="row">
          <!-- /.col -->
          <div class="col-md-12 text-left">
            <div class="card">
               
                <div class="card-body">
                  <!-- the events -->
                  <div>
					<table class="table table-sm" id="tbRequestCuti">
						<thead>
							<tr>
								<th>Tanggal Request</th>
								<th>Nama/NIK</th>
								<th>Divisi</th>
								<th>Tipe Cuti</th>
								<th>Tanggal Cuti</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>
						<tbody>
							
								@for($i=0;$i<count($all_cuti_pending);$i++)
									<tr>
										<td>{{date('d F Y H:i', strtotime($all_cuti_pending[$i]['requested_date']))}}</td>
										<td>{{ $all_cuti_pending[$i]['nama_user'].'/'.$all_cuti_pending[$i]['nik_user'] }}</td>
										<td>{{ $all_cuti_pending[$i]['department']}}</td>
										<td>{{ $all_cuti_pending[$i]['nama_type_cuti']}}</td>
										<td><a href="#" onclick="actionListCuti('view_desc', '<?php echo $all_cuti_pending[$i]['desc_cuti']; ?>')">{{ $all_cuti_pending[$i]['tgl_mulai_cuti']}} ({{ $all_cuti_pending[$i]['jumlah_hari_cuti']}} Hari)</a></td>
										<td class="text-center">
											<button class="btn btn-danger btn-sm" onclick="actionListCuti('reject', '<?php echo $all_cuti_pending[$i]['id_cuti']; ?>')">Reject</button>
											<button class="btn btn-success btn-sm" onclick="actionListCuti('approve', '<?php echo $all_cuti_pending[$i]['id_cuti']; ?>')">Approve</button>
										</td>
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
	
	oTable = $('#tbRequestCuti').dataTable({
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
function actionListCuti(action, data_cuti){
	if(action == 'view_desc'){
		Swal.fire({
		  text: data_cuti,
		  allowOutsideClick: false,
		});
	}else if(action == 'reject'){
		Swal.fire({
		  title: 'Are you sure?',
		  text: "Request cuti yang dipilih akan ditolak!",
		  icon: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Confirm',
		  allowOutsideClick: false,
		}).then((result) => {
		  if (result.isConfirmed) {
			  updateCuti(data_cuti, action);
			  /*
			Swal.fire({
			  title: "Sukses",
			  icon: "success",
			  allowOutsideClick: false,
			}).then(() => {
				location.reload();
			});
			*/
		  }
		});
	}
	else if(action == 'approve'){
		Swal.fire({
		  title: 'Are you sure?',
		  text: "Request cuti yang dipilih akan disetujui!",
		  icon: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Confirm',
		  allowOutsideClick: false,
		}).then((result) => {
		  if (result.isConfirmed) {
			  updateCuti(data_cuti, action);
			
		  }
		});
	}
	
}

function updateCuti(id_cuti, action){
	Swal.fire({
			title: 'Please wait...',
			allowOutsideClick: false,
			showConfirmButton: false
		});
		swal.showLoading();
	$.ajax({
		url: "<?php echo url('/'); ?>/permintaan_cuti/updateCuti",
		data: {
			'id_cuti':id_cuti,
			'action':action
		},
		type: 'GET',
		complete: function(isi){
			//console.log(isi);
			if(isi.responseText == "1"){
				Swal.fire({
				  title: "Sukses",
				  icon: "success",
				  allowOutsideClick: false,
				}).then(() => {
					location.reload();
				});
			}
			else if(isi.responseText == "3"){
				Swal.fire({
				  title: "Jata cuti tidak mencukupi",
				  icon: "error",
				  allowOutsideClick: false,
				}).then(() => {
					location.reload();
				});
			}
			
			else if(isi.responseText == "4"){
				Swal.fire({
				  title: "Anda tidak dapat memproses data cuti NIK sendiri",
				  icon: "error",
				  allowOutsideClick: false,
				});
			}
			
			else{
				Swal.fire({
									  position: 'center',
									  icon: 'error',
									  title: 'Update gagal, silakan coba lagi',
										allowOutsideClick: false,
									});
			}
		},error: function(XMLHttpRequest, textStatus, errorThrown) { 
							Swal.fire({
									  position: 'center',
									  type: 'error',
									  title: 'Update gagal, silakan coba lagi',
									  showConfirmButton: false,
									  timer: 1500
									});
						}
	
	});
}
</script>
</body>
</html>
