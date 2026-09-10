  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      {{-- &copy; Copyright <strong><span>NiceAdmin</span></strong>. All Rights Reserved --}}
    </div>
    <div class="credits">
      <!-- All the links in the footer should remain intact. -->
      <!-- You can delete the links only if you purchased the pro version. -->
      <!-- Licensing information: https://bootstrapmade.com/license/ -->
      <!-- Purchase the pro version with working PHP/AJAX contact form: https://bootstrapmade.com/nice-admin-bootstrap-admin-html-template/ -->
      {{-- Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a> --}}
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
  <script src="{{ asset('asset/admin/v1/assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
  <script src="{{ asset('asset/admin/v1/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('asset/admin/v1/assets/vendor/chart.js/chart.umd.js') }}"></script>
  <script src="{{ asset('asset/admin/v1/assets/vendor/echarts/echarts.min.js') }}"></script>
  <script src="{{ asset('asset/admin/v1/assets/vendor/quill/quill.min.js') }}"></script>
  {{-- <script src="{{ asset('asset/admin/v1/assets/vendor/simple-datatables/simple-datatables.js') }}"></script> --}}
  <script src="{{ asset('asset/admin/v1/assets/vendor/tinymce/tinymce.min.js') }}"></script>
  <script src="{{ asset('asset/admin/v1/assets/vendor/php-email-form/validate.js') }}"></script>

  <!-- Template Main JS File -->
  {{-- <script src="{{ asset('asset/admin/v1/assets/js/main.js') }}"></script> --}}
  <script src="{{ asset('asset/admin/plugins/jquery-validation/jquery.validate.js') }}"></script>
  <script src="{{ asset('asset/admin/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
  {{-- <script src="{{ asset('asset/admin/plugins/daterangepicker/daterangepicker.js') }}"></script>
  <script src="{{ asset('asset/admin/plugins/moment/moment.min.js') }}"></script> --}}

{{-- <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script> --}}
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/echarts@5.5.1/dist/echarts.min.js"></script>
</body>
<script>
  $('#loading__js').css('display', 'flex');
  $(document).ready(function(){
    setTimeout(() => {
      $('#loading__js').css('display', 'none');
    }, 1500);
  });

</script>
</html>
