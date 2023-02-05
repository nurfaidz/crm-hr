@extends('partials.template')
@section('main')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <p class="d-flex align-items-center"><img src="{{ url('./img/icons/dashboard.svg') }}" alt="">
                    <a href="{{ url('dashboard') }}" class="dashboard-link">Dashboard</a><a
                        href="{{ url('medical-reports') }}" class="leave-application-link">/ Medical Report</a>
                </p>
                <hr>
                <div class="card my-2">
                    <div class="card-body">
                        <h3>Medical Report Employee</h3>
                        <section>
                            <div class="row">
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Date</label>
                                        <div class="form-group">
                                            <input type="text" name="date" aria-controls="datatables-ajax"
                                                id="select-date" value="{{ request('date') }}" required
                                                class="form-control" placeholder="Select Date" />
                                            <div class="valid-feedback">Looks good!</div>
                                            <div class="invalid-feedback">Please select holiday start
                                                date.</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Reimbursement Types</label>
                                        <select class="form-control" name="category" id="category"
                                            onchange="selectFunction()">
                                            <option selected disabled value="">Select Reimbursement Type
                                            </option>
                                            <option value="0">Inpatient</option>
                                            <option value="1">Outpatient</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>Entity :</label>
                                        <select class="select2-data-ajax custom-select" name="branch" id="branch"
                                            onchange="selectFunction()">
                                            <option selected disabled value=''>Select Entity</option>
                                            @foreach ($branches as $branch)
                                                <option value='{{ $branch->branch_id }}'>
                                                    {{ $branch->branch_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>SBU
                                            :</label>
                                        <select class="form-control" name="department" id="department" disabled
                                            onchange="selectFunction()">
                                            <option selected disabled value="">Select SBU</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <hr>
                        <section>
                            <div class="row">
                                <div class="table-responsive col-12 table-rounded">
                                    <table class="table table-borderless" width="100%" id="ajax-datatables">
                                        <thead>
                                            <tr>
                                                <th scope="col">No</th>
                                                <th scope="col">Employee Name</th>
                                                <th scope="col">Entity</th>
                                                <th scope="col">SBU</th>
                                                <th scope="col">Reimbursement Type</th>
                                                <th scope="col">Transaction Date</th>
                                                <th scope="col">Total Expense</th>
                                                <th scope="col">Total Reimbursement</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_js')
    <script src="{{ url('app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
    <script src="{{ url('app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@latest/dist/plugins/monthSelect/index.js"></script>
    <script src="{{ url('app-assets/js/scripts/components/components-popovers.js') }}"></script>
@endsection

@section('page_script')
    <script>
        let date = new Date().toISOString().slice(0, 10);
        let category = null;
        let branch = null;
        let department = null;

        $(document).ready(() => {
            $('#category').select2();
            $('#branch').select2();
            $('#department').select2();
        });

        const dataTableAjax = (date, category, branch, department) => {
            console.log(date);
            const dt_ajax_table = $("#ajax-datatables");
            if (dt_ajax_table.length) {
                const dt_ajax = dt_ajax_table.dataTable({
                    retrieve: true,
                    processing: true,
                    dom: '<"d-flex justify-content-between align-items-end mx-0 row"<"col"l><"col-md-auto"f><"col col-lg-2 p-0."<"#button.btn btn-md btn-outline-primary">>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    ajax: `/medical-reports?date=${date}&category=${category}&branch=${branch}&department=${department}`,
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
                            data: (data) =>
                                `<div class="d-flex align-items-center">
                                        <img src="${data.employee_image}" alt="Employee Profile Picture" class="rounded-circle" width="32px" height="32px">
                                        <div class="p-1">
                                            <a href="/medical-reports/employee/${data.employee_id}">${data.first_name} ${data.last_name}</a><br>
                                            ${data.nip}
                                        </div>
                                    </div>`,
                            name: 'employee_name'
                        },
                        {
                            data: 'branch_name',
                            name: 'branch_name'
                        },
                        {
                            data: 'department_name',
                            name: 'sbu_name'
                        },
                        {
                            data: (data) => data.category == 0 ? 'Inpatient' : 'Outpatient',
                            name: 'category'
                        },
                        {
                            data: 'reimbursement_date',
                            name: 'reimbursement_date'
                        },
                        {
                            data: (data) => new Intl.NumberFormat("id-ID", {
                                style: "currency",
                                currency: "IDR"
                            }).format(data.expenses),
                            name: 'expenses'
                        },
                        {
                            data: (data) => new Intl.NumberFormat("id-ID", {
                                style: "currency",
                                currency: "IDR"
                            }).format(data.total_reimburse),
                            name: 'total_reimburse'
                        },
                        {
                            data: (data) => {
                                if (data.approve_by_finance > 0) {
                                    return '<span class="badge badge-pill badge-light-success">Approved</span>';
                                } else if (data.reject_by > 0) {
                                    return '<span class="badge badge-pill badge-light-danger">Rejected</span>';
                                } else if (data.cancel_by > 0) {
                                    return '<span class="badge badge-pill badge-light-secondary">Canceled</span>';
                                } else {
                                    return '<span class="badge badge-pill badge-light-warning">Pending</span>';
                                }
                            },
                            name: 'status'
                        },
                        {
                            data: (data) => {
                                return `<a class="btn btn-outline-info round waves-effect button-rounded" href="/medical-reports/${data.medical_reimbursement_id}"> Details </a>`
                            },
                            name: 'action',
                        }
                    ],
                });
            }

            $("#button").html('Export');
            $("#button").attr('style', 'margin-bottom: 7px');
            $("#button").click(() => exportReport());

            $('#select-date').flatpickr({
                altInput: true,
                dateFormat: "Y-m-d",
                defaultDate: date,
                maxDate: new Date().toISOString().slice(0, 10),
                onChange: function(selectedDates, dateStr) {
                    category = $('#category').val();
                    branch = $('#branch').val();
                    department = $('#department').val();
                    (category === null) ? category = null: category;
                    (branch === null) ? branch = null: branch;
                    (department === null) ? department = null: department;
                    $("#ajax-datatables").DataTable().destroy();
                    dataTableAjax(dateStr, category, branch, department);
                },
            });
        };

        dataTableAjax(date, category, branch, department);

        let entity = '';
        const selectFunction = () => {
            let dep = document.querySelector('#department');
            date = $('#select-date').val();
            category = $('#category').val();
            branch = $('#branch').val();
            department = $('#department').val();
            (category === null) ? category = null: category;
            (branch === null) ? branch = null: branch;
            (department === null) ? department = null: department;
            if (branch !== null) {
                fetch(`/leave-reports?branches=${branch}`)
                    .then(response => response.json())
                    .then(response => {
                        dep.removeAttribute('disabled');

                        if (dep == 0 || entity != branch) {
                            if (dep.length > 0) {
                                for (let i = 0; i < dep.length; i++) {
                                    $('#department')
                                        .find('option')
                                        .remove();
                                }
                            }

                            const opt = document.createElement('option');
                            opt.value = '';
                            opt.selected = 'selected';
                            opt.disabled = 'disabled';
                            opt.innerHTML = 'Select SBU';
                            dep.appendChild(opt);
                            if (response.departments.length > 0) {
                                response.departments.forEach((item, index) => {
                                    const opt = document.createElement('option');
                                    opt.value = item.department_id;
                                    opt.innerHTML = item.department_name;
                                    dep.appendChild(opt);
                                });
                            }

                            $(document).ready(() => {
                                $('#category').select2();
                                $('#branch').select2();
                                $('#department').select2();
                            });

                            entity = branch;
                        }
                    });
            }
            $("#ajax-datatables").DataTable().destroy();
            dataTableAjax(date, category, branch, department);
        };

        const exportReport = () => {
            date = $('#select-date').val();
            category = $('#category').val();
            branch = $('#branch').val();
            department = $('#department').val();
            (category === null) ? category = null: category;
            (branch === null) ? branch = null: branch;
            (department === null) ? department = null: department;

            const data = {
                date: date,
                category: category,
                branch: branch,
                department: department,
                export: 'export'
            };

            fetch(`/medical-reports?date=${date}&category=${category}&branch=${branch}&department=${department}&export=export`, {
                    method: 'GET'
                })
                .then(response => response.blob())
                .then(data => {
                    console.log('Success:', data);
                    const url = window.URL.createObjectURL(data);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = "medical_report.xlsx";
                    document.body.appendChild(
                        a); // we need to append the element to the dom -> otherwise it will not work in firefox
                    a.click();
                    a.remove(); //afterwards we remove the element again
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
        };
    </script>
@endsection
