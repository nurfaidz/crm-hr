@extends('partials.template')
@section('main')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <p class="d-flex align-items-center"><img src="{{ url('./img/icons/dashboard.svg') }}" alt=""><a
                        href="{{ url('dashboard') }}" class="dashboard-link">Dashboard</a><a
                        href="{{ url('employee-balance') }}" class="leave-application-link">/ Employee Balance</a>
                </p>
                <div class="card">
                    <div class="card-body">
                        <h3>Employee Balance</h3>
                        <hr>
                        <section>
                            <div class="row">
                                <div class="table-responsive col-12 table-rounded">
                                    <table class="table table-borderless" width="100%" id="datatables-ajax">
                                        <thead>
                                            <tr>
                                                <th scope="col">No</th>
                                                <th scope="col">Employee Name</th>
                                                <th scope="col">Entity</th>
                                                <th scope="col">SBU</th>
                                                <th scope="col">Job Position</th>
                                                <th scope="col">Type Of Leave</th>
                                                <th scope="col">Used Balance</th>
                                                <th scope="col">Remaining Leave</th>
                                                {{-- <th scope="col">Action</th> --}}
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
            <div class="modal fade text-left" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-title"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="modal-title">Edit Employee Balance</h4>
                            <button type="button" class="close mr-0 mt-0" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="form_data" class="form-data-validate" novalidate>
                            @csrf
                            <input type="hidden" name="id" id="id" value="">
                            <div class="modal-body">
                                <label>Employee Name:</label>
                                <div class="form-group">
                                    <input type="text" name="employee_name" disabled id="employee_name" required
                                        class="form-control" />
                                    <div class="invalid-feedback employee_name_error"></div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-6">
                                            <label>Employee ID:</label>
                                            <input type="text" name="nip" id="nip" required disabled
                                                class="form-control" />
                                            <div class="invalid-feedback company_address_error"></div>
                                        </div>
                                        <div class="col-6">
                                            <label>Job Position:</label>
                                            <input type="text" name="job_position" disabled id="job_position" required
                                                class="form-control" />
                                            <div class="invalid-feedback company_phone_error"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-6">
                                            <label>Entity:</label>
                                            <input type="email" name="entity" id="entity" disabled required
                                                class="form-control" />
                                            <div class="invalid-feedback company_email_error"></div>
                                        </div>
                                        <div class="col-6">
                                            <label>SBU:</label>
                                            <input type="url" name="department" id="department" disabled required
                                                class="form-control" />
                                            <div class="invalid-feedback company_website_error"></div>
                                        </div>
                                    </div>
                                </div>
                                <label>Remaining Balance:</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addon1">Rp.</span>
                                    <input type="url" name="remaining_balance" id="remaining_balance" placeholder="Rp."
                                        required class="form-control" />
                                    <div class="invalid-feedback remaining_balance_error"></div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" id="submit" class="btn btn-primary">Submit</button>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('page_js')
    <script src="{{ url('app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
    <script src="{{ url('app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
@endsection

@section('page_script')
    <script>
        const dataTable_Ajax = () => {
            var dt_ajax_table = $("#datatables-ajax");
            console.log(dt_ajax_table.length);
            if (dt_ajax_table.length) {
                const dt_ajax = dt_ajax_table.dataTable({
                    processing: true,
                    ordering: false,
                    dom: '<"d-flex justify-content-between align-items-end mx-0 row"<"col"l><"col-md-auto"f><"col col-lg-2 p-0."<"#button.btn btn-md btn-outline-primary">>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    // drawCallback: function() {
                    //     $(this.api().table().header()).hide();
                    // },
                    ajax: {
                        url: `leave-balance-list`,
                        method: 'GET'
                    },
                    scrollX: true,
                    language: {
                        paginate: {
                            // remove previous & next text from pagination 
                            previous: '&nbsp;',
                            next: '&nbsp;'
                        },
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: (data) => {
                                return `
                                    <div class="d-flex align-items-center">
                                        <img src="${data.employee_image}" alt="Employee Profile Picture" class="rounded-circle" width="32px" height="32px">
                                        <div class="p-1">
                                            <b>${data.first_name} ${data.last_name}</b><br>
                                            ${data.nip}
                                        </div>
                                    </div>
                                `;
                            },
                            name: 'Employee Name'
                        },
                        {
                            data: 'branch_name',
                            name: 'Entity'
                        },
                        {
                            data: 'department_name',
                            name: 'SBU'
                        },
                        {
                            data: 'job_position',
                            name: 'Job Position'
                        },
                        {
                            data: 'leave_type_name',
                            name: 'Leave Type'
                        },
                        {
                            data: (data) => {
                                if (data.used > 0) {
                                    return `${data.used} Hari `;
                                }

                                return '0 Hari';
                            },
                            name: 'Used Balance'
                        },
                        {
                            data: (data) => {
                                if (data.balance > data.used) {
                                    let count = data.balance - data.used;
                                    return `${count} Hari `;
                                }

                                return '0 Hari';
                            },
                            name: 'Remaining Balance'
                        },
                    ],
                    columnDefs: [{
                        targets: 6,
                        className: 'text-center',
                    }],
                    rowCallback: function(hlRow, hlData, hlIndex) {

                        if (hlData.status_code == 'aab' || hlData.status_code == 'arj') {
                            $('td', hlRow).css('background', '#ea54551f');
                        } else if (hlData.status_code == 'ado' || hlData.status_code == 'asc') {
                            $('td', hlRow).css('background', '#6c757d1f');
                        } else if (hlData.status_code == 'apm') {
                            $('td', hlRow).css('background', '#00CFE81F');
                        }
                    }
                });
            }
            $("#button").html('Export');
            $("#button").attr('style', 'margin-bottom: 7px');
            $("#button").click(() => exportLeaveBalance());
        }
        const exportLeaveBalance = () => {

            const data = {
                export: 'export'
            };

            fetch(`/leave-balance-list?export=export`, {
                    method: 'GET'
                })
                .then(response => response.blob())
                .then(data => {
                    console.log('Success:', data);
                    const url = window.URL.createObjectURL(data);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = "Leave_Balance.xlsx";
                    document.body.appendChild(
                        a); // we need to append the element to the dom -> otherwise it will not work in firefox
                    a.click();
                    a.remove(); //afterwards we remove the element again  
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
        };
        dataTable_Ajax();
    </script>
@endsection
