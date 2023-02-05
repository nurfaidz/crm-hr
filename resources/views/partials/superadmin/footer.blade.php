<div class="sidenav-overlay"></div>
<!-- BEGIN: Footer-->
<footer class="footer footer-static footer-light">
    <p class="clearfix mb-0"><span class="float-md-left d-block d-md-inline-block mt-25">COPYRIGHT &copy; 2022
            <a class="ml-25" href="https://technoinfinity.co.id" target="_blank">Techno Infinity</a></span>
    </p>
</footer>
<button class="btn btn-primary btn-icon scroll-top" type="button"><i data-feather="arrow-up"></i></button>
<!-- END: Footer-->

<!-- BEGIN: Vendor JS-->
<script src="{{ url('app-assets/vendors/js/vendors.min.js') }}"></script>
<!-- BEGIN Vendor JS-->

<!-- BEGIN: Page Vendor JS-->
<script src="{{ url('app-assets/vendors/js/ui/jquery.sticky.js') }}"></script>
<script src="{{ url('app-assets/vendors/js/charts/chart.min.js') }}"></script>
<script src="{{ url('app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
<script src="{{ url('app-assets/vendors/js/charts/apexcharts.min.js') }}"></script>
<script src="{{ url('app-assets/vendors/js/tables/datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ url('app-assets/vendors/js/tables/datatable/datatables.bootstrap4.min.js') }}"></script>
<script src="{{ url('app-assets/vendors/js/tables/datatable/dataTables.responsive.min.js') }}"></script>
<script src="{{ url('app-assets/vendors/js/tables/datatable/responsive.bootstrap4.js') }}"></script>
<script src="{{ url('app-assets/vendors/js/tables/datatable/datatables.checkboxes.min.js') }}"></script>
<script src="{{ url('app-assets/vendors/js/tables/datatable/datatables.buttons.min.js') }}"></script>
<script src="{{ url('app-assets/vendors/js/tables/datatable/jszip.min.js') }}"></script>
<script src="{{ url('app-assets/vendors/js/tables/datatable/pdfmake.min.js') }}"></script>
<script src="{{ url('app-assets/vendors/js/tables/datatable/vfs_fonts.js') }}"></script>
<script src="{{ url('app-assets/vendors/js/tables/datatable/buttons.html5.min.js') }}"></script>
<script src="{{ url('app-assets/vendors/js/tables/datatable/buttons.print.min.js') }}"></script>
<script src="{{ url('app-assets/vendors/js/tables/datatable/dataTables.rowGroup.min.js') }}"></script>
<script src="{{ url('app-assets/vendors/js/pickers/pickadate/picker.js') }}"></script>
<script src="{{ url('app-assets/vendors/js/pickers/pickadate/picker.date.js') }}"></script>
<script src="{{ url('app-assets/vendors/js/pickers/pickadate/picker.time.js') }}"></script>
<script src="{{ url('app-assets/vendors/js/pickers/pickadate/legacy.js') }}"></script>
<script src="{{ url('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{ url('app-assets/vendors/js/calendar/fullcalendar.min.js') }}"></script>
<script src="../../../app-assets/vendors/js/forms/wizard/bs-stepper.min.js"></script>
<script src="{{ url('app-assets/vendors/js/extensions/dropzone.min.js') }}"></script>
<!-- END: Page Vendor JS-->

<!-- BEGIN: Theme JS-->
<script src="{{ url('app-assets/js/core/app-menu.js') }}"></script>
<script src="{{ url('app-assets/js/core/app.js') }}"></script>
<!-- END: Theme JS-->

<!-- BEGIN: Page JS-->
<script src="{{ url('app-assets/js/scripts/charts/chart-chartjs.js') }}"></script>
<script src="{{ url('app-assets/js/scripts/tables/table-datatables-basic.js') }}"></script>
<script src="{{ url('app-assets/js/scripts/tables/table-datatables-advanced.js') }}"></script>
<script src="{{ url('app-assets/js/scripts/forms/pickers/form-pickers.js') }}"></script>
<script src="{{ url('app-assets/js/scripts/components/components-modals.js') }}"></script>
<script src="../../../app-assets/js/scripts/forms/form-wizard.js"></script>
<script src="{{ url('app-assets/js/scripts/forms/form-file-uploader.js') }}"></script>
<script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
<!-- END: Page JS-->

{{-- Year Picker --}}
<link rel="stylesheet" href="{{ url('assets/css/yearpicker.css') }}" />
<script src="{{ url('assets/js/yearpicker.js') }}"></script>
<script src="{{ url('assets/js/scripts.js') }}"></script>
@yield('page_js')

<!-- Trix-->
<link rel="stylesheet" type="text/css" href="{{ url('assets/css/trix.css') }}">
<script type="text/javascript" src="{{ url('assets/js/trix.js') }}"></script>

<script>
    $(window).on('load', function() {
        if (feather) {
            feather.replace({
                width: 14,
                height: 14
            });
        }
    })
</script>

<script>
    $(document).ready(function() {
        $(".yearpicker").yearpicker({
            startYear: new Date().getFullYear() - 10,
            endYear: new Date().getFullYear() + 10,
        });

        $("#example2").yearpicker({
            startYear: new Date().getFullYear() - 10,
            endYear: new Date().getFullYear() + 10,
            onChange: function(value) {
                $('#OutputContainer').html(value);
            }
        });
    });

    $(function($) {
        let url = window.location.href;
        $('.main-menu-content ul li a').each(function() {
            if (this.href === url) {
                $(this).closest('li').addClass('active');
            }
        });

    });

    let oilCanvas = document.getElementById("oilChart");

    Chart.defaults.global.defaultFontFamily = "Montserrat";
    Chart.defaults.global.defaultFontSize = 13;

    let oilData = {
        labels: [
            "Presence",
            "On Leave",
            "Absent",
        ],
        datasets: [{
            data: [92, 5, 3],
            backgroundColor: [
                "#7367F0",
                "#FF9F43",
                "#EA5455",
            ],
        }]
    };

    let pieChart = new Chart(oilCanvas, {
        type: 'doughnut',
        data: oilData
    });

    document.addEventListener('DOMContentLoaded', function() {
        let calendarEl = document.getElementById('calendar');
        let calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth'
        });
        calendar.render();
    });

    var width = window.matchMedia("(max-width: 768px)")
    myFunction(width) // Call listener function at run time
    width.addListener(myFunction) // Attach listener function on state changes

    document.addEventListener('trix-file-accept', function(e) {
        e.preventDefault();
    })
</script>
@yield('page_script')
</body>
<!-- END: Body-->

</html>
