<div class="sidenav-overlay"></div>
<div class="drag-target"></div>

<!-- BEGIN: Footer-->
<footer class="footer footer-static footer-light">
    <p class="clearfix mb-0"><span class="float-md-left d-block d-md-inline-block mt-25">COPYRIGHT &copy; 2022
            <a class="ml-25" href="https://technoinfinity.co.id" target="_blank">Techno Infinity</a></span>
    </p>
</footer>
<button class="btn btn-primary btn-icon scroll-top" type="button"><i data-feather="arrow-up"></i></button>
<!-- END: Footer-->

<!-- Notification Modal -->
<div class="modal fade" id="modal-notification" tabindex="-1" role="dialog" aria-labelledby="notificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="w-100 d-flex align-items-center justify-content-between">
                    <h4 class="modal-title">Notifications</h5>
                    <div class="d-flex flex-end w-100">
                        <h5 class="m-0 ml-auto mr-3" id="notif-clear-all"><a href="javascript:void(0)" class="text-danger"><u>Clear all</u></a></h5>
                        <h5 class="m-0 mr-2" id="notif-mark-all"><a style="" href="javascript:void(0)"><u>Mark as Read</u><a/></h5>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="media-list" id="modal-notification-body"></div>
            </div>
        </div>
    </div>
</div>

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

        navbarNotif();
    });

     $("#notif-clear-all").on("click", function() {
        $.ajax({
            url: `{{ url('notifications/clears') }}`,
            type: 'DELETE',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: (result) => {
                location.reload();
            },
            error: ()=> {
                console.error('oke');
            }
        });
    });

    $("#notif-mark-all").on("click", function() {
        $.ajax({
            url: `{{ url('notifications/marks') }}`,
            type: 'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: (result) => {
                location.reload();
            },
            error: ()=> {
                console.error('oke');
            }
        });
    });

    const navbarNotif = () => {
        $.ajax({
             url: `{{ url('notifications/employees') }}`,
             type: 'GET',
             headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
             success: (result) => {
                const {list: lists, countRead} = result;

                if(lists !== null){                    
                    $("#btn-read-notif-all")
                        .removeClass("disabled")
                        .attr("data-toggle", "modal")
                        .attr("data-target", "#exampleModal");

                    $("#list-notifications").empty();

                    $("#btn-read-notif-all").on("click", function() {
                        $('#modal-notification').modal('toggle');
                    });

                    lists.forEach(l => {
                        const {title, message, notif_type, notif_status, dateDiff, readStamp} = l;

                        const icon = iconNotif(notif_type, notif_status);
                        
                        const item = 
                        `<a class="d-flex" href="javascript:void(0)">
                            <div class="media d-flex align-items-start">
                                <div class="media-left">
                                    <div class="avatar">
                                        ${icon}
                                    </div>
                                </div>
                                <div class="media-body d-flex justify-content-between">
                                    <div>
                                        <p class="media-heading m-0">
                                            <span class="font-weight-bolder ${(readStamp === null)?"text-dark":"text-muted"}">${title}</span>
                                        </p>
                                        <small class="notification-text ${(readStamp === null)?"text-dark":"text-muted"}">${message}</small>
                                    </div>
                                    <small class="text-muted">${dateDiff}</small>
                                </div>
                            </div>
                        </a>`;

                        $("#list-notifications").append(item);
                        $("#modal-notification-body").append(item);
                    });
                }
            },
            error: ()=> {
                console.error('oke');
            }
            });
        }
        
    const iconNotif = (type, status) => {
        let icon = "";
        let color = "";

        switch (status) {
            case "Pending": color = "#C2C2C2"; break;
            case "Approve": color = "#00CFE8"; break;
            case "Reject": color ="#EA5455"; break;
        }
        
        switch (type) {
            case "Leave": 
            case "Overtime":
            case "Manual":
            case "Reimbursement":
                return `<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="40" height="40" rx="20" fill="${color}"/>
                <path d="M13.3334 23.3334V26.6667C13.3334 27.5834 14.0834 28.3334 15 28.3334H25C25.9167 28.3334 26.6667 27.5834 26.6667 26.6667V23.3334C26.6667 22.4167 25.9167 21.6667 25 21.6667H15C14.0834 21.6667 13.3334 22.4167 13.3334 23.3334ZM24.1667 25.0001H15.8334C15.375 25.0001 15 24.6251 15 24.1667C15 23.7084 15.375 23.3334 15.8334 23.3334H24.1667C24.625 23.3334 25 23.7084 25 24.1667C25 24.6251 24.625 25.0001 24.1667 25.0001ZM20 11.6667C17.95 11.6667 16.2334 13.1584 15.8917 15.1084C15.825 15.5417 15.9417 15.9834 16.1917 16.3334L19.325 20.7167C19.6584 21.1834 20.35 21.1834 20.6834 20.7167L23.8167 16.3334C24.0667 15.9834 24.1834 15.5417 24.1084 15.1084C23.7667 13.1584 22.05 11.6667 20 11.6667Z" fill="white"/>
                </svg>`;
            break;

            case "Birthday": { 
                return `<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="40" height="40" rx="20" fill="#B1B2F3"/>
                <path d="M20 15.8333C20.925 15.8333 21.6667 15.0833 21.6667 14.1666C21.6667 13.8499 21.5833 13.5583 21.425 13.3083L20 10.8333L18.575 13.3083C18.4167 13.5583 18.3333 13.8499 18.3333 14.1666C18.3333 15.0833 19.0833 15.8333 20 15.8333ZM23.8333 24.1583L22.9417 23.2666L22.0417 24.1583C20.9583 25.2416 19.0583 25.2499 17.9667 24.1583L17.075 23.2666L16.1667 24.1583C15.625 24.6999 14.9 24.9999 14.1333 24.9999C13.525 24.9999 12.9667 24.8083 12.5 24.4916V28.3333C12.5 28.7916 12.875 29.1666 13.3333 29.1666H26.6667C27.125 29.1666 27.5 28.7916 27.5 28.3333V24.4916C27.0333 24.8083 26.475 24.9999 25.8667 24.9999C25.1 24.9999 24.375 24.6999 23.8333 24.1583ZM25 18.3333H20.8333V16.6666H19.1667V18.3333H15C13.6167 18.3333 12.5 19.4499 12.5 20.8333V22.1166C12.5 23.0166 13.2333 23.7499 14.1333 23.7499C14.5667 23.7499 14.9833 23.5833 15.2833 23.2749L17.0667 21.4999L18.8417 23.2749C19.4583 23.8916 20.5333 23.8916 21.15 23.2749L22.9333 21.4999L24.7083 23.2749C25.0167 23.5833 25.425 23.7499 25.8583 23.7499C26.7583 23.7499 27.4917 23.0166 27.4917 22.1166V20.8333C27.5 19.4499 26.3833 18.3333 25 18.3333Z" fill="white"/>
                </svg>`;
            }
            break;
      
            case "Task": {
                return `<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="40" height="40" rx="20" fill="#B1B2F3"/>
                <path d="M23.3333 19.6084C23.0917 19.3667 22.6917 19.3667 22.45 19.6084L18.825 23.2334L17.5 21.9084C17.2583 21.6667 16.8583 21.6667 16.6167 21.9084C16.375 22.1501 16.375 22.5501 16.6167 22.7917L18.2333 24.4084C18.5583 24.7334 19.0833 24.7334 19.4083 24.4084L23.325 20.4917C23.575 20.2501 23.575 19.8501 23.3333 19.6084ZM25.8333 13.3334H25V12.5001C25 12.0417 24.625 11.6667 24.1667 11.6667C23.7083 11.6667 23.3333 12.0417 23.3333 12.5001V13.3334H16.6667V12.5001C16.6667 12.0417 16.2917 11.6667 15.8333 11.6667C15.375 11.6667 15 12.0417 15 12.5001V13.3334H14.1667C13.2417 13.3334 12.5083 14.0834 12.5083 15.0001L12.5 26.6667C12.5 27.5834 13.2417 28.3334 14.1667 28.3334H25.8333C26.75 28.3334 27.5 27.5834 27.5 26.6667V15.0001C27.5 14.0834 26.75 13.3334 25.8333 13.3334ZM25 26.6667H15C14.5417 26.6667 14.1667 26.2917 14.1667 25.8334V17.5001H25.8333V25.8334C25.8333 26.2917 25.4583 26.6667 25 26.6667Z" fill="white"/>
                </svg>`;
            }
            break;
            
            case "Attendance":{
                return `<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="40" height="40" rx="20" fill="#FF9F43"/>
                <path d="M23.3333 19.6084C23.0917 19.3667 22.6917 19.3667 22.45 19.6084L18.825 23.2334L17.5 21.9084C17.2583 21.6667 16.8583 21.6667 16.6167 21.9084C16.375 22.1501 16.375 22.5501 16.6167 22.7917L18.2333 24.4084C18.5583 24.7334 19.0833 24.7334 19.4083 24.4084L23.325 20.4917C23.575 20.2501 23.575 19.8501 23.3333 19.6084ZM25.8333 13.3334H25V12.5001C25 12.0417 24.625 11.6667 24.1667 11.6667C23.7083 11.6667 23.3333 12.0417 23.3333 12.5001V13.3334H16.6667V12.5001C16.6667 12.0417 16.2917 11.6667 15.8333 11.6667C15.375 11.6667 15 12.0417 15 12.5001V13.3334H14.1667C13.2417 13.3334 12.5083 14.0834 12.5083 15.0001L12.5 26.6667C12.5 27.5834 13.2417 28.3334 14.1667 28.3334H25.8333C26.75 28.3334 27.5 27.5834 27.5 26.6667V15.0001C27.5 14.0834 26.75 13.3334 25.8333 13.3334ZM25 26.6667H15C14.5417 26.6667 14.1667 26.2917 14.1667 25.8334V17.5001H25.8333V25.8334C25.8333 26.2917 25.4583 26.6667 25 26.6667Z" fill="white"/>
                </svg>`;
            }
            break;
            default : {
                return `<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="40" height="40" rx="20" fill="#CFD2CF"/>
                <path d="M16.3167 13.1917L15.125 12C13.125 13.525 11.8083 15.875 11.6917 18.5417H13.3583C13.4833 16.3333 14.6167 14.4 16.3167 13.1917ZM26.6416 18.5417H28.3083C28.1833 15.875 26.8667 13.525 24.875 12L23.6917 13.1917C25.375 14.4 26.5166 16.3333 26.6416 18.5417ZM25 18.9583C25 16.4 23.6333 14.2583 21.25 13.6917V13.125C21.25 12.4333 20.6917 11.875 20 11.875C19.3083 11.875 18.75 12.4333 18.75 13.125V13.6917C16.3583 14.2583 15 16.3917 15 18.9583V23.125L13.3333 24.7917V25.625H26.6666V24.7917L25 23.125V18.9583ZM20 28.125C20.1167 28.125 20.225 28.1167 20.3333 28.0917C20.875 27.975 21.3167 27.6083 21.5333 27.1083C21.6167 26.9083 21.6583 26.6917 21.6583 26.4583H18.325C18.3333 27.375 19.075 28.125 20 28.125Z" fill="white"/>
                </svg>`;
            }
        }
    }
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
