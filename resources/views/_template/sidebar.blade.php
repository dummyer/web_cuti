<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?php echo url('/');?>/home" class="brand-link text-sm">
      <img src="<?php echo url('/'); ?>/assets/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Dashboard Cuti</span>
    </a>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->


          <li class="nav-item menu-open">
            <a href="#" class="nav-link active">
              <i class="nav-icon fas fa-table"></i>
              <p>
                
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
          </li>
          <li class="nav-header">Data HR</li>
          <li class="nav-item">
            <a href="<?php echo url('/'); ?>/kalender_cuti" class="nav-link <?php if(Request::segment(1) == 'kalender_cuti'){ echo "active"; } ?>">
              <i class="nav-icon far fa-calendar-alt"></i>
              <p>
                Kalender Cuti
               
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="../gallery.html" class="nav-link">
              <i class="nav-icon fas fa-mail-bulk"></i>
              <p>
                Permintaan Cuti
				 <span class="badge badge-info right">2</span>
              </p>
            </a>
          </li>
        
        
         
          
          
        
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>