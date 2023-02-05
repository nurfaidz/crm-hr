@extends('partials.template')
@section('main')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-body">
                <div class="card">
                    <div class="card-body">
                        <section id="ajax-datatable">
                            <div class="row">
                                <div class="col-12">
                                    <div class="card">
                                        <h3>Employee Lists</h3>
                                        <div class="card-datatable table-responsive table-rounded-outline">
                                            <table class="table table-borderless" id="datatables-ajax">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Employee Name</th>
                                                        <th>Entity</th>
                                                        <th>SBU</th>
                                                        <th>Role</th>
                                                        <th>Job Level</th>
                                                        <th>Job Position</th>
                                                        <th>Entri Join</th>
                                                        <th>Email</th>
                                                        <th>Mobile Phone</th>
                                                        <th>Date of Birth</th>
                                                        <th>Address</th>
                                                        <th>Religion</th>
                                                        <th>Gender</th>
                                                        <th>Employment Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
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
@endsection

@section('page_script')
    <script>
        $(document).ready(function() {
            const dt_ajax_table = $("#datatables-ajax");
            if (dt_ajax_table.length) {
                const dt_ajax = dt_ajax_table.dataTable({
                    processing: true,
                    dom: '<"d-flex justify-content-between align-items-end mx-0 row"<"col"l><"col-md-auto"f><"col col-lg-2 p-0."<"#button.btn btn-md btn-primary">>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    ajax: "{{ url('employees') }}",
                    language: {
                        paginate: {
                            previous: '&nbsp;',
                            next: '&nbsp;'
                        },
                    },
                    ordering: false,
                    scrollX: true,
                    scrollCollapse: true,
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
                                            <a href="/employees/${data.employee_id}/edit">${data.first_name} ${data.last_name}</a><br>
                                            ${data.nip}
                                        </div>
                                    </div>
                                `;
                            },
                            name: 'employee_name'
                        },
                        {
                            data: 'branch_name',
                            name: 'entity'
                        },
                        {
                            data: 'department_name',
                            name: 'sbu'
                        },
                        {
                            data: 'name',
                            name: 'role'
                        },
                        {
                            data: 'job_class',
                            name: 'job_class'
                        },
                        {
                            data: 'job_position',
                            name: 'job_position'
                        },
                        {
                            data: 'date_of_joining',
                            name: 'date_of_joining'
                        },
                        {
                            data: 'email',
                            name: 'email'
                        },
                        {
                            data: 'phone',
                            name: 'phone'
                        },
                        {
                            data: 'date_of_birth',
                            name: 'date_of_birth'
                        },
                        {
                            data: 'address',
                            name: 'address'
                        },
                        {
                            data: 'religion',
                            name: 'religion'
                        },
                        {
                            data: 'gender',
                            name: 'gender'
                        },
                        {
                            data: 'employment_status',
                            name: 'employment_status'
                        },
                        {
                            data: 'action',
                            name: 'action'
                        },
                    ],
                });
            }

            $("#button").html('Add Employee');
            $("#button").attr('style', 'margin-bottom: 7px');
            $("#button").click(() => window.location = "{{ url('employees/create') }}");
        });

        function sweetConfirm(id) {
            event.preventDefault();
            const form = event.target.form;
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn btn-primary",
                confirmButtonText: "Yes, delete it!",
                cancelButtonClass: "btn btn-danger ml-1",
                cancelButtonText: "Cancel",
                buttonsStyling: false
            }).then((result) => {
                if (result.value) {
                    const request = new FormData(document.getElementById('form_delete_data'));
                    const data = {
                        _token: request.get('_token'),
                    };

                    fetch(`/employees/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify(data),
                        })
                        .then(response => response.json())
                        .then(data => {
                            setTimeout(function() {
                                $('#datatables-ajax').DataTable().ajax.reload();
                            }, 0);

                            Swal.fire({
                                type: 'success',
                                title: 'Success!',
                                text: data.message,
                                confirmButtonClass: 'btn btn-success',
                            });
                        })
                        .catch((error) => {
                            Swal.fire({
                                type: 'error',
                                title: 'Oops...',
                                text: error.message,
                                confirmButtonClass: 'btn btn-success',
                            });
                        });
                }
            });
        }
    </script>
@endsection
