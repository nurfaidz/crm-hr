@extends('partials.template')
@section('main')
    <style>
        .form-control.is-valid, .was-validated .form-control:valid,
        .form-control.is-invalid, .was-validated .form-control:invalid {
            background-image: none;
        }
    </style>
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-body">
                <p class="d-flex align-items-center">
                    <img src="{{ url('./img/icons/dashboard.svg') }}" alt="Dashboard">
                    <a href="{{ url('dashboard') }}" class="dashboard-link">Dashboard</a>
                    <a href="{{ url('self-leave-application') }}" class="this-page-link">/ Leave Tracker</a>
                </p>
                <div class="card self-leave-application">
                    <div class="card-body" style="padding: 27px 42px 21px 32px">
                        <span class="medical-tracker-reimbursement">Leave Tracker</span>
                        <div class="row holiday-and-statistic">
                            <div class="col-md-6 col-sm-12">
                                <div class="card">
                                    <div class="public-holiday">
                                        <div class="card-header">
                                            <h4>PUBLIC HOLIDAY</h4>
                                        </div>
                                        <div class="card-body">
                                            @php
                                                function indonesianDate($date)
                                                {
                                                    $month = array(
                                                        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                                                    );
                                                    $splitDate = explode('-', $date);
                                                    
                                                    return $splitDate[2] . ' ' . $month[(int)$splitDate[1]] . ' ' . $splitDate[0];
                                                }
                                            @endphp
                                            @foreach ($publicHolidays as $publicHoliday)
                                                <div class="d-flex justify-content-between inside">
                                                    <p>{{ $publicHoliday->holiday_occasion }}</p>
                                                    <p>{{ indonesianDate($publicHoliday->start_date) }}</p>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="card">
                                    <div class="your-statistic">
                                        <div class="card-header">
                                            <h4>YOUR STATISTIC</h4>
                                            <div>
                                                <input type="text" class="medical-year" readonly>
                                                <span><img src="{{ url('img/icons/dropdown-arrow.svg') }}" alt="dropdown-arrow"></span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6 col-sm-12 d-flex">
                                                    <img src="{{ url('img/icons/more-time.svg') }}" alt="more-time">
                                                    <div class="statistic">
                                                        <div class="d-flex">
                                                            <div>Leave Balance</div>
                                                            <button id="btn-details">Details</button>
                                                        </div>
                                                        <div id="leave-balance"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12 d-flex">
                                                    <img src="{{ url('img/icons/not-interested.svg') }}" alt="not-interested">
                                                    <div class="statistic">
                                                        <div>Number of Canceled</div>
                                                        <div id="number-of-canceled"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 col-sm-12 d-flex">
                                                    <img src="{{ url('img/icons/download-done.svg') }}" alt="download-done">
                                                    <div class="statistic">
                                                        <div>Number of Approved</div>
                                                        <div id="number-of-approved"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-sm-12 d-flex">
                                                    <img src="{{ url('img/icons/clear.svg') }}" alt="clear">
                                                    <div class="statistic">
                                                        <div>Number of Rejected</div>
                                                        <div id="number-of-rejected"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <section id="leave-application-pending-status">
                            <div class="d-flex align-items-center justify-content-between">
                                <h4>Pending Status</h4>
                                <button type="button" id="request-medical-reimbursement" class="btn btn-primary" data-toggle="modal" data-target="#modal-request-medical-reimbursement">+ Request Leave</button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-borderless text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Type of Leave</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Total</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
                                            <th class="text-center">Cancel</th>
                                        </tr>
                                    </thead>
                                    <tbody id="pending-status-data"></tbody>
                                </table>
                            </div>
                        </section>
                        <section id="medical-reimbursement-history">
                            <h4>Leave History</h4>
                            <div class="table-responsive table-rounded-outline text-nowrap">
                                <table id="leave-history-datatables" class="table table-borderless">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Type of Leave</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </section>
                        <div id="join-date" class="d-none">{{ $joinDate }}</div>
                        <div id="join-date-duration" class="d-none">{{ $joinDateDuration }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-request-medical-reimbursement" tabindex="-1" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-title">Request Leave</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-data" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <label for="leave_type_id">Type of Leave<span class="text-danger">*</span></label>
                            <select name="leave_type_id" id="leave_type_id" class="form-control" onchange="checkSelectShow(this.value)" required>
                                <option hidden disabled selected value>Select Category</option>
                                @foreach ($leaveTypes as $leaveType)
                                    <option value="{{ $leaveType->leave_type_id }}">{{ $leaveType->leave_type_name }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback leave_type_id_error"></div>
                        </div>
                        <div class="form-group" id="option-request-select">
                            <label for="option_request">Option Request<span class="text-danger">*</span></label>
                            <select name="option_request" id="option_request" class="form-control" required>
                                <option hidden disabled selected value>Select Option Request</option>
                                <option value="0">Half-day</option>
                                <option value="1">Full Day</option>
                            </select>
                            <div class="invalid-feedback option_request_error"></div>
                        </div>
                        <div class="form-group" id="option-leave-select">
                            <label for="option_leave_id">Option Leave<span class="text-danger">*</span></label>
                            <select name="option_leave_id" id="option_leave_id" class="form-control" required>
                                <option hidden disabled selected value>Select Option Leave</option>
                                @foreach ($optionLeaves as $optionLeave)
                                    <option value="{{ $optionLeave->option_leave_id }}">{{ $optionLeave->option_leave }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback option_leave_id_error"></div>
                        </div>
                        <div class="form-group">
                            <label for="application_from_date">Start Date<span class="text-danger">*</span></label>
                            <div class="input-group custom-start-date">
                                <input type="text" name="application_from_date" id="application_from_date" class="form-control border-right-0" placeholder="Select Date" required>
                                <div class="invalid-feedback application_from_date_error order-last"></div>
                                <div class="input-group-append">
                                    <span class="input-group-text" id="basic-addon2">
                                        <img src="{{ url('img/icons/date-range.svg') }}" alt="date-range">
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="application_to_date">End Date<span class="text-danger">*</span></label>
                            <div class="input-group custom-end-date">
                                <input type="text" name="application_to_date" id="application_to_date" class="form-control border-right-0" placeholder="Select Date" required>
                                <div class="invalid-feedback application_to_date_error order-last"></div>
                                <div class="input-group-append">
                                    <span class="input-group-text" id="basic-addon2">
                                        <img src="{{ url('img/icons/date-range.svg') }}" alt="date-range">
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="attachment">Attachment<span class="text-danger">*</span></label>
                            <input type="file" name="attachment[]" id="attachment" class="form-control-file d-none" onchange="getAttachmentData(this)" required multiple>
                            <button type="button" id="attachment-substitute" class="form-control btn btn-outline-primary">
                                <img src="{{ url('img/icons/cloud-upload.svg') }}" alt="cloud-upload">Click here to upload
                            </button>
                            <div class="invalid-feedback attachment_error"></div>
                        </div>
                        <div class="custom-input-image-container"></div>
                        <div class="form-group">
                            <label for="notes">Reason for Leave<span class="text-danger">*</span></label>
                            <textarea name="notes" id="notes" class="form-control" cols="30" rows="5" placeholder="Input Description" required></textarea>
                            <div class="invalid-feedback notes_error"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="submit" class="btn btn-primary">Request</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-details" tabindex="-1" role="dialog" aria-labelledby="modal-details" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-title">Leave Balance Information</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="d-flex">
                        <div id="leave-balance-chart"></div>
                    </div>
                    <div class="table-responsive border">
                        <table class="table table-borderless text-nowrap">
                            <thead>
                                <tr>
                                    <th>Period</th>
                                    <th>Total</th>
                                    <th>Used</th>
                                    <th>Balance</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="leave-balance-history"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_js')
    <script src="{{ url('app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
    <script src="{{ url('app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@endsection

@section('page_script')
    <script>
        let publicHolidayChildren = $('.self-leave-application .public-holiday .card-body').children().length;

        if (publicHolidayChildren > 4) {
            $('.self-leave-application .public-holiday .card-body').scroll(function() {
                let y = $(this).scrollTop();

                if (y > 10) {
                    $('.self-leave-application .inside:nth-child(4)').css('border-bottom', '1px solid #babfc7');
                } else {
                    $('.self-leave-application .inside:nth-child(4)').css('border-bottom', '0');
                }
            });
        }

        const statistics = (year) => {
            $.ajax({
                url: "{{ url('self-leave-application/statistic') }}" + '/' + year,
                method: 'GET',
                dataType: 'json',
                success: (result) => {
                    const {
                        leaveBalance,
                        numberOfCanceled,
                        numberOfApproved,
                        numberOfRejected
                    } = result.data;

                    $('#leave-balance').html(leaveBalance);
                    $('#number-of-canceled').html(numberOfCanceled);
                    $('#number-of-approved').html(numberOfApproved);
                    $('#number-of-rejected').html(numberOfRejected);
                }
            });
        }

        const pendingStatusData = () => {
            $.ajax({
                url: "{{ url('self-leave-application/data') }}",
                method: 'GET',
                dataType: 'json',
                success: (result) => {
                    let tr = '';

                    if (result.data.length == 0) {
                        tr += `
                            <tr>
                                <td colspan="8" id="no-data-available">No data available in table</td>
                            </tr>
                        `;
                    } else {
                        result.data.forEach((element, index) => {
                            tr += `
                                <tr class="data-row-${element.leave_application_id}">
                                    <td>${index + 1}</td>
                                    <td>${element.leave_type_name}</td>
                                    <td>${element.application_from_date}</td>
                                    <td>${element.application_to_date}</td>
                                    <td>${element.total}</td>
                                    <td class="text-center">
                                        <span id="pending-status-status" class="badge badge-pill badge-light-warning">${element.status}</span>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" id="pending-status-details" data-id="${element.leave_application_id}" class="btn btn-outline-info round pending-status-details" onclick="location.href='{{ url('self-leave-application/details/${element.leave_application_id}') }}'">Details</button>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" id="pending-status-cancel" data-id="${element.leave_application_id}" class="btn btn-outline-danger round pending-status-cancel">Cancel</button>
                                    </td>
                                </tr>
                            `;
                        });
                    }
                    
                    $('#pending-status-data').html(tr);
                }
            });
        }

        $(document).ready(function() {
            let year = new Date().getFullYear();
            statistics(year);
            $('.medical-year').yearpicker({
                year: year,
                endYear: year,
                onChange: function(value) {
                    statistics(value);
                }
            });

            $('#leave_type_id').select2();
            $('#option_request').select2();
            $('#option_leave_id').select2();

            let type = 0;
            let status = 0;

            const dataTablesAjax = (type, status) => {
                const dt_ajax_table = $("#leave-history-datatables");

                if (dt_ajax_table.length) {
                    const dt_ajax = dt_ajax_table.dataTable({
                        processing: true,
                        dom: '<"d-flex justify-content-between align-items-end mx-0 row"<"col"l><"col-md-auto p-0."f><"#leave-history-filter.btn btn-md btn-primary"><"#leave-history-export.btn btn-md btn-primary">>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                        ajax: `self-leave-application/list/${type}/${status}`,
                        ordering: false,
                        language: {
                            paginate: {
                                previous: '&nbsp;',
                                next: '&nbsp;'
                            },
                            search: '<span id="leave-history-datatables-search" class="d-flex"><img src="{{ url('img/icons/search-gray.svg') }}" alt="">Search</span>'
                        },
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex'
                            },
                            {
                                data: 'leave_type_name',
                                name: 'Type of Leave'
                            },
                            {
                                data: (data) => data.application_from_date.split('-').reverse().join('/'),
                                name: 'Start Date'
                            },
                            {
                                data: (data) => data.application_to_date.split('-').reverse().join('/'),
                                name: 'End Date'
                            },
                            {
                                data: (data) => {
                                    let from = data.application_from_date;
                                    let to = data.application_to_date;
                                    let diffInDays = Math.floor((new Date(to) - new Date(from)) / (1000 * 60 * 60 * 24)) + 1;

                                    return `${diffInDays} Days`;
                                },
                                name: 'Total'
                            },
                            {
                                data: (data) => {
                                    if (data.short === 'Approved') {
                                        return `<span class="badge badge-pill badge-light-success" style="width: 79px">${data.short}</span>`;
                                    } else if (data.short === 'Rejected') {
                                        return `<span class="badge badge-pill badge-light-danger" style="width: 73px">${data.short}</span>`;
                                    } else {
                                        return `<span class="badge badge-pill badge-light-secondary" style="width: 76px">${data.short}</span>`;
                                    }
                                },
                                name: 'Status'
                            },
                            {
                                data: 'action',
                                name: 'action'
                            },
                        ],
                        columnDefs: [{
                            targets: [5, 6],
                            className: 'text-center'
                        }]
                    });
                }
                
                $("#leave-history-filter").html('<div>Filter</div><img src="{{ url('./img/icons/angle-down.svg') }}">');
                $("#leave-history-export").html('<div><img src="{{ url('./img/icons/open-in-new.svg') }}"><a href="{{ url('self-leave-application/export') }}" style="color: inherit;">Export</a></div><img src="{{ url('./img/icons/angle-down.svg') }}" id="angle-down">');
            }

            pendingStatusData();
            dataTablesAjax(type, status);
        });

        $('#modal-request-medical-reimbursement').on('shown.bs.modal', function() {
            let leaveBalance = $('#leave-balance').text();
            let statisticYear = $('.medical-year').val();
            let joinDateDuration = $('#join-date-duration').text();
            let joinDate = $('#join-date').text();
            joinDate = joinDate.split('-');

            let joinDateYear = parseInt(joinDate[0]);
            let firstArrayYearBatch = [];
            let secondArrayYearBatch = [];

            for (let i = 0; i < 10; i++) {
                joinDateYear += 6;
                firstArrayYearBatch.push(joinDateYear);
            }

            firstArrayYearBatch.forEach(element => {
                element += 1;
                secondArrayYearBatch.push(element);
                element += 1;
                secondArrayYearBatch.push(element);
            });

            if (leaveBalance == 0 && statisticYear == new Date().getFullYear()) {
                $("#leave_type_id option[value='1']").remove();
            }

            if (joinDateDuration < 6 || firstArrayYearBatch.includes(new Date().getFullYear()) || !(secondArrayYearBatch.includes(new Date().getFullYear()))) {
                $("#leave_type_id option[value='2']").remove();
            }

            let applicationFromDate = flatpickr('#application_from_date', {
                allowInput: true,
                altInput: true,
                altFormat: 'd/m/Y',
                minDate: new Date(new Date().getFullYear(), 0, 1),
                maxDate: new Date(new Date().getFullYear(), 12, 0),
                onOpen: function(selectedDates, dateStr, instance) {
                    $(instance.altInput).prop('readonly', true);
                },
                onChange: function(selectedDates, dateStr, instance) {
                    let select = $('#leave_type_id')[0];
                    let selectedValue = select.options[select.selectedIndex].text;
                    let optionRequest = $('#option_request')[0];
                    let optionRequestValue = optionRequest.options[optionRequest.selectedIndex].text;
                    let fromDate = $('#application_from_date').val();
                    let toDate = $('#application_to_date').val();

                    if (selectedValue == 'Annual Leave') {
                        if (optionRequestValue == 'Half-day') {
                            if (toDate && (new Date(toDate) >= new Date(fromDate))) {
                                let range = Math.floor(new Date(toDate) / 1000) - Math.floor(new Date(fromDate) / 1000);
                                range = Math.ceil(range / 86400) + 1;

                                if (range > 1) {
                                    alert('You cannot choose half-day leave when the leave duration is more than a day.');
                                    applicationFromDate.clear();
                                } else {
                                    $('#number_of_day').val(range);
                                }
                            } else if (toDate && (new Date(toDate) <= new Date(fromDate))) {
                                alert('The end date must be greater than the start date.');
                                applicationFromDate.clear();
                            }
                        } else {
                            if (toDate && (new Date(toDate) >= new Date(fromDate))) {
                                let range = Math.floor(new Date(toDate) / 1000) - Math.floor(new Date(fromDate) / 1000);
                                range = Math.ceil(range / 86400) + 1;

                                if (range > leaveBalance) {
                                    alert('Your leave balance is insufficient.');
                                    applicationFromDate.clear();
                                } else {
                                    $('#number_of_day').val(range);
                                }
                            } else if (toDate && (new Date(toDate) <= new Date(fromDate))) {
                                alert('The end date must be greater than the start date.');
                                applicationFromDate.clear();
                            }
                        }
                    } else if (selectedValue == 'Big Leave') {
                        if (toDate && (new Date(toDate) >= new Date(fromDate))) {
                            let range = Math.floor(new Date(toDate) / 1000) - Math.floor(new Date(fromDate) / 1000);
                            range = Math.ceil(range / 86400) + 1;

                            if (range < 30) {
                                alert('You must take a month of Big Leave at once.');
                                applicationFromDate.clear();
                            } else if (range > 30) {
                                alert('The maximum big leave allowance is a month.');
                                applicationFromDate.clear();
                            } else {
                                $('#number_of_day').val(range);
                            }
                        } else if (toDate && (new Date(toDate) <= new Date(fromDate))) {
                            alert('The end date must be greater than the start date.');
                            applicationFromDate.clear();
                        }
                    } else if (selectedValue == 'Maternity Leave') {
                        if (toDate && (new Date(toDate) >= new Date(fromDate))) {
                            let range = Math.floor(new Date(toDate) / 1000) - Math.floor(new Date(fromDate) / 1000);
                            range = Math.ceil(range / 86400) + 1;

                            if (range < 91) {
                                alert('You must take three months of Maternity Leave at once.');
                                applicationFromDate.clear();
                            } else if (range > 91) {
                                alert('The maximum maternity leave allowance is three months.');
                                applicationFromDate.clear();
                            } else {
                                $('#number_of_day').val(range);
                            }
                        } else if (toDate && (new Date(toDate) <= new Date(fromDate))) {
                            alert('The end date must be greater than the start date.');
                            applicationFromDate.clear();
                        }
                    } else {
                        if (toDate && (new Date(toDate) >= new Date(fromDate))) {
                            let range = Math.floor(new Date(toDate) / 1000) - Math.floor(new Date(fromDate) / 1000);
                            range = Math.ceil(range / 86400) + 1;
                            
                            $('#number_of_day').val(range);
                        } else if (toDate && (new Date(toDate) <= new Date(fromDate))) {
                            alert('The end date must be greater than the start date.');
                            applicationFromDate.clear();
                        }
                    }
                },
                onClose: function(selectedDates, dateStr, instance) {
                    $(instance.altInput).prop('readonly', false);
                    $(instance.altInput).blur();
                }
            });

            let applicationToDate = flatpickr('#application_to_date', {
                allowInput: true,
                altInput: true,
                altFormat: 'd/m/Y',
                minDate: new Date(new Date().getFullYear(), 0, 1),
                maxDate: new Date(new Date().getFullYear(), 12, 0),
                onOpen: function(selectedDates, dateStr, instance) {
                    $(instance.altInput).prop('readonly', true);
                },
                onChange: function(selectedDates, dateStr, instance) {
                    let select = $('#leave_type_id')[0];
                    let selectedValue = select.options[select.selectedIndex].text;
                    let optionRequest = $('#option_request')[0];
                    let optionRequestValue = optionRequest.options[optionRequest.selectedIndex].text;
                    let fromDate = $('#application_from_date').val();
                    let toDate = $('#application_to_date').val();

                    if (selectedValue == 'Annual Leave') {
                        if (optionRequestValue == 'Half-day') {
                            if (fromDate && (new Date(fromDate) <= new Date(toDate))) {
                                let range = Math.floor(new Date(toDate) / 1000) - Math.floor(new Date(fromDate) / 1000);
                                range = Math.ceil(range / 86400) + 1;

                                if (range > 1) {
                                    alert('You cannot choose half-day leave when the leave duration is more than a day.');
                                    applicationToDate.clear();
                                } else {
                                    $('#number_of_day').val(range);
                                }
                            } else if (fromDate && (new Date(fromDate) >= new Date(toDate))) {
                                alert('The end date must be greater than the start date.');
                                applicationToDate.clear();
                            }
                        } else {
                            if (fromDate && (new Date(fromDate) <= new Date(toDate))) {
                                let range = Math.floor(new Date(toDate) / 1000) - Math.floor(new Date(fromDate) / 1000);
                                range = Math.ceil(range / 86400) + 1;

                                if (range > leaveBalance) {
                                    alert('Your leave balance is insufficient.');
                                    applicationToDate.clear();
                                } else {
                                    $('#number_of_day').val(range);
                                }
                            } else if (fromDate && (new Date(fromDate) >= new Date(toDate))) {
                                alert('The end date must be greater than the start date.');
                                applicationToDate.clear();
                            }
                        }
                    } else if (selectedValue == 'Big Leave') {
                        if (fromDate && (new Date(fromDate) <= new Date(toDate))) {
                            let range = Math.floor(new Date(toDate) / 1000) - Math.floor(new Date(fromDate) / 1000);
                            range = Math.ceil(range / 86400) + 1;

                            if (range < 30) {
                                alert('You must take a month of Big Leave at once.');
                                applicationToDate.clear();
                            } else if (range > 30) {
                                alert('The maximum big leave allowance is a month.');
                                applicationToDate.clear();
                            } else {
                                $('#number_of_day').val(range);
                            }
                        } else if (fromDate && (new Date(fromDate) >= new Date(toDate))) {
                            alert('The end date must be greater than the start date.');
                            applicationToDate.clear();
                        }
                    } else if (selectedValue == 'Maternity Leave') {
                        if (fromDate && (new Date(fromDate) <= new Date(toDate))) {
                            let range = Math.floor(new Date(toDate) / 1000) - Math.floor(new Date(fromDate) / 1000);
                            range = Math.ceil(range / 86400) + 1;

                            if (range < 91) {
                                alert('You must take three months of Maternity Leave at once.');
                                applicationToDate.clear();
                            } else if (range > 91) {
                                alert('The maximum maternity leave allowance is three months.');
                                applicationToDate.clear();
                            } else {
                                $('#number_of_day').val(range);
                            }
                        } else if (fromDate && (new Date(fromDate) >= new Date(toDate))) {
                            alert('The end date must be greater than the start date.');
                            applicationToDate.clear();
                        }
                    }
                },
                onClose: function(selectedDates, dateStr, instance) {
                    $(instance.altInput).prop('readonly', false);
                    $(instance.altInput).blur();
                }
            });
        });

        $('#modal-request-medical-reimbursement').on('hidden.bs.modal', function() {
            let resetForm = $('#form-data')[0];
            $(resetForm).trigger('reset');
            $(resetForm).removeClass('was-validated');

            $('.modal-body select').val('').trigger('change.select2');
            $('.custom-input-image-container').html('');

            $('#leave_type_id').html('');
            $('#leave_type_id').append('<option hidden disabled="disabled" selected="selected" value>Select Category</option>@foreach ($leaveTypes as $leaveType)<option value="{{ $leaveType->leave_type_id }}">{{ $leaveType->leave_type_name }}</option>@endforeach');
        });

        $(document).on('change', '#option_request', function() {
            let fromDate = $('#application_from_date').val();
            let toDate = $('#application_to_date').val();

            if ($('#option_request').val() != null && fromDate != '' && toDate != '') {
                let range = Math.floor(new Date(toDate) / 1000) - Math.floor(new Date(fromDate) / 1000);
                range = Math.ceil(range / 86400) + 1;

                let select = $('#option_request')[0];
                let selectedValue = select.options[select.selectedIndex].text;

                if (range > 1 && selectedValue == 'Half-day') {
                    alert('You cannot choose half-day leave because the day difference between the start date and the end date is more than a day.');
                    $('#option_request').val('').change();
                }
            }
        });

        function checkSelectShow(selectedOption) {
            if (selectedOption == 1) {
                $('#modal-request-medical-reimbursement #option-request-select').show();
            } else {
                $('#modal-request-medical-reimbursement #option-request-select').hide();
            }

            if (selectedOption == 5) {
                $('#modal-request-medical-reimbursement #option-leave-select').show();
            } else {
                $('#modal-request-medical-reimbursement #option-leave-select').hide();
            }
        }

        $(document).on('click', '#attachment-substitute', function() {
            $('#attachment').click();
        });

        function getAttachmentData(attachment) {
            $('.custom-input-image-container').html('');
            let files = document.getElementById('attachment').files;

            for (let i = 0; i < files.length; i++) {
                $('.custom-input-image-container').append(`<div class="form-group"><div class="input-group"><div class="input-group-prepend"><span class="input-group-text bg-white"><img src="{{ url('img/icons/file-copy.svg') }}" alt="file-copy"></span></div><input type="text" class="form-control bg-white custom-input-image-name" placeholder="${files[i].name}" disabled="disabled"><div class="input-group-append"><span class="input-group-text bg-white custom-input-image-size">${(files[i].size / Math.pow(1024, 1)).toString().split('.')[0]} Kb</span></div></div></div>`);
            }
        }

        $(document).on('click', '#submit', function() {
            let startDate = $('#application_from_date').val();
            let endDate = $('#application_to_date').val();

            if (startDate == '') {
                $('.custom-start-date .input-group-text').css('border-color', '#ea5455');
            } else {
                $('.custom-start-date .input-group-text').css('border-color', '#28c76f');
            }

            if (endDate == '') {
                $('.custom-end-date .input-group-text').css('border-color', '#ea5455');
            } else {
                $('.custom-end-date .input-group-text').css('border-color', '#28c76f');
            }
        });

        $(document).on('click', '#request-medical-reimbursement', function() {
            const idForm = $('form#form_edit_data').attr('id', 'form-data');
            const leaveType = document.querySelector('#leave_type_id');
            const optionRequest = document.querySelector('#option_request');
            const optionLeave = document.querySelector('#option_leave_id');
            const startDate = document.querySelector('#application_from_date');
            const endDate = document.querySelector('#application_to_date');
            const attachment = document.querySelector('#attachment');
            const notes = document.querySelector('#notes');

            function checkSelectShow(selectedOption) {
                if (selectedOption == 1) {
                    leaveType.value = '';
                    optionRequest.value = '';
                    startDate.value = '';
                    endDate.value = '';
                    attachment.value = '';
                    notes.value = '';
                } else if (selectedOption == 5) {
                    leaveType.value = '';
                    optionLeave.value = '';
                    startDate.value = '';
                    endDate.value = '';
                    attachment.value = '';
                    notes.value = '';
                } else {
                    leaveType.value = '';
                    startDate.value = '';
                    endDate.value = '';
                    attachment.value = '';
                    notes.value = '';
                }
            }

            submit();
        });

        function submit() {
            Array.prototype.filter.call($('#form-data'), function(form) {
                $('#submit').unbind().on('click', function(e) {
                    if (form.checkValidity() === false) {
                        form.classList.add('invalid');
                    }

                    form.classList.add('was-validated');
                    e.preventDefault();

                    const data = new FormData($('#form-data')[0]);

                    $.ajax({
                        url: "{{ url('self-leave-application') }}",
                        data: data,
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        contentType: false,
                        processData: false,
                        success: (data) => {
                            if (data.error) {
                                $.each(data.error, (prefix, val) => {
                                    $('div.' + prefix + '_error').text(val[0]);
                                });

                                let fields = ['leave_type_id', 'option_request', 'option_leave_id', 'application_from_date', 'application_to_date', 'attachment', 'notes'];

                                for (let i = 0; i < fields.length; i++) {
                                    let error = response.error.hasOwnProperty(fields[i]) ? true : false;

                                    if (error) {
                                        $('#' + fields[i]).removeClass('was-validated');
                                        $('#' + fields[i]).addClass('is-invalid');
                                        $('#' + fields[i]).addClass('invalid-more');
                                    } else {
                                        $('#' + fields[i]).removeClass('is-invalid');
                                        $('#' + fields[i]).removeClass('invalid-more');
                                    }
                                }
                            } else {
                                setTimeout(() => {
                                    pendingStatusData();
                                    $('#datatables-ajax').DataTable().ajax.reload();
                                }, 0);

                                alertify.success(data.message);
                                
                                $('#modal-request-medical-reimbursement').modal('hide');
                            }
                        }
                    });
                });
            });
        }

        $(document).on('click', '.pending-status-cancel', function(e) {
            e.preventDefault();

            let id = $(this).data('id');
            
            Swal.fire({
                type: 'warning',
                icon: 'warning',
                title: 'Are you sure?',
                confirmButtonText: 'Yes, cancel it!',
                confirmButtonClass: 'btn btn-primary',
                cancelButtonText: 'No',
                cancelButtonClass: 'btn btn-danger',
                html: '<p class="pending-status-text">You won\'t be able to revert this!</p>',
                focusConfirm: false,
                showCancelButton: true,
                showCloseButton: true,
                customClass: {
                    title: 'pending-status-title',
                    cancelButton: 'pending-status-cancel-button',
                    closeButton: 'pending-status-close-button',
                    confirmButton: 'pending-status-confirm-button'
                }
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: 'self-leave-application/cancel/' + id,
                        type: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: (result) => {
                            if (result.error) {
                                alertify.error(result.message);
                            } else {
                                setTimeout(() => {
                                    pendingStatusData();
                                    $('#leave-history-datatables').DataTable().ajax.reload();
                                }, 0);

                                alertify.success(result.message);
                            }
                        }
                    });
                }
            });
        });

$("#btn-details").click(()=>{
            let statisticYear = $('.medical-year').val();

            $("#modal-details").modal('toggle');
            
            $.ajax({
                url: `self-leave-application/information/${year}`,
                type: "GET",
                success: (result) => {
                    const {leaveBalance, data, leavePeriods} = result;

                    if(leaveBalance.length != 0){
                        let options = {
                            chart: {
                                width: 380,
                                type: 'pie'
                            },
                            series: [],
                            labels: [],
                            dataLabels: {
                                enabled: false
                            },
                        }
    
                        Object.entries(leaveBalance).forEach(([key, val]) => {
                            options.labels.push(key);
                            options.series.push(val);
                        });
                          
                        let chart = new ApexCharts(document.querySelector("#leave-balance-chart"), options);
                        
                        chart.render();
                    }

                    let tr = '';

                    if (result.data.length == 0) {
                        tr += `
                            <tr>
                                <td colspan="5" id="no-data-available">No data available in table</td>
                            </tr>
                        `;
                    } else {
                        let fd = leavePeriods.from_date.split("-").reverse().join("-");
                        let td = leavePeriods.to_date.split("-").reverse().join("-");
                        tr =`
                        <tr>
                            <td>${fd} - ${td}</td>
                            <td>${leavePeriods.limit}</td>
                            <td>${leavePeriods.limit - data.balance}</td>
                            <td>${data.balance}</td>
                            <td><span class="badge badge-light-success">Active</span></td>
                        </tr>`;
                    }
                    
                    $('#leave-balance-history').html(tr);
                },
                error: (result) => {
                    console.error(result);
                }
            })
        });
    </script>
@endsection