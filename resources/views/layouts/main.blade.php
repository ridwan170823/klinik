<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Klinik Gigi</title>

  <!-- Custom fonts for this template-->
  <link href="{{ asset('css/all.min.css') }}" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/fontawesome.min.css" rel="stylesheet">
  <!-- Custom styles for this template-->
  <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
  <link href="{{ asset('css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">

</head>

<body id="page-top">
  <!-- Page Wrapper -->
  <div id="wrapper">
    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('home') }}">
        <div class="sidebar-brand-icon">
          <i class="fas fa-hospital-user"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Klinik Gigi<sup></sup></div>
      </a>
      <!-- Divider -->
      <hr class="sidebar-divider my-0">
      <!-- Nav Item - Dashboard -->
      <li class="nav-item active">
        <a class="nav-link" href="{{ route('home') }}">
          <i class="fas fa-chart-line"></i>
          <span>Dashboard</span></a>
      </li>
      <!-- Divider -->
      <hr class="sidebar-divider">
      <!-- Heading -->
      <div class="sidebar-heading">
        Menu
      </div>
      @if (Auth::user()->role == 'dokter')
      <!-- Nav Item - Pages Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true"
          aria-controls="collapseTwo">
          <i class="fas fa-user-injured"></i>
          <span>Pasien</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Action</h6>
            <a class="collapse-item" href="{{ route('pasien.index') }}">Daftar Pasien</a>
            <a class="collapse-item" href="{{ route('pasien.create') }}">Tambah Pasien</a>
          </div>
        </div>
      </li>
      <!-- Nav Item - Utilities Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
          aria-expanded="true" aria-controls="collapseUtilities">
          <i class="fas fa-tablets"></i>
          <span>Obat</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Action</h6>
            <a class="collapse-item" href="{{ route('obat.index') }}">Daftar Obat</a>
          </div>
        </div>
      </li>
      @endif

      @if (Auth::user()->role == 'pasien')

      @php
          $incomplete = empty(Auth::user()->alamat) || empty(Auth::user()->no_telp);
      @endphp
      @if ($incomplete)
      <li class="nav-item">
        <a class="nav-link" href="{{ route('profile.edit') }}">
          <i class="fas fa-user-edit"></i>
          <span>Lengkapi Profil</span>
        </a>
      </li>
      @else

      <li class="nav-item">
        <a class="nav-link" href="{{ route('antrian.index') }}">
          <i class="fas fa-list-ol"></i>
          <span>Antrian</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('antrian.history') }}">
          <i class="fas fa-history"></i>
          <span>Riwayat Antrian</span>
        </a>
      </li>
      @endif
      @endif

      @if (Auth::user()->role == 'admin')
      <!-- Nav Item - Pages Collapse Menu -->
      <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('antrian.index') }}" >
         
          <i class="fas fa-user-plus"></i></fas>
          <span>Daftar Antrian</span>
        </a>
        <!-- Add this block -->
        <a class="nav-link" href="{{ route('admin.antrian.history') }}">
            <i class="fas fa-history"></i>
            <span>Riwayat Antrian</span>
        </a>
        <!-- end add -->
        <!-- Manajemen Dokter -->
        <a class="nav-link" href="{{ route('dokter.index') }}">
            <i class="fas fa-user-md"></i>
            <span>Manajemen Dokter</span>
        </a>
         <a class="nav-link" href="{{ route('pasien.index') }}">
            <i class="fas fa-user-md"></i>
            <span>Data User</span>
        </a>

        <!-- Jadwal Dokter -->
        <a class="nav-link" href="{{ route('jadwal.index') }}">
            <i class="fas fa-calendar-alt"></i>
            <span>Jadwal Dokter</span>
        </a>
        <!-- Jadwal Dokter -->
        <a class="nav-link" href="{{ route('layanan.index') }}">
            <i class="fas fa-calendar-alt"></i>
            <span>Manajemen Layanan</span>
        </a>
        <a class="nav-link" href="{{ route('dokter_layanan.index') }}">
            <i class="fas fa-link"></i>
            <span>Relasi Dokter-Layanan</span>
        </a>
      </li>
      
      @endif

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle">
        </button>
      </div>
    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      @yield('content')
      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy;  2025</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->
    </div>
    <!-- End of Content Wrapper -->
  </div>
  <!-- End of Page Wrapper -->
  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>
  <!-- Bootstrap core JavaScript-->
  <script src="{{ asset('js/jquery.min.js') }}"></script>
  <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
  <!-- Core plugin JavaScript-->
  <script src="{{ asset('js/jquery.easing.min.js') }}"></script>
  <!-- Custom scripts for all pages-->
  <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
  <!-- Page level plugins -->
  <script src="{{ asset('js/Chart.min.js') }}"></script>
  <!-- Page level plugins -->
  <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>
  <!-- Page level custom scripts -->
  <script src="{{ asset('js/datatables-demo.js') }}"></script>
</body>

</html>