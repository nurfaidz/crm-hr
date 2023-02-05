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
                        <section>
                            <div class="row">
                                <div class="col-lg-10 col-md-12 col-sm-12">
                                    <div class="row">
                                        <div class="col-lg-3 col-md-12 col-sm-12">
                                            <div class="form-group">
                                                <label>Entity :</label>
                                                <select class="select2-data-ajax custom-select" name="branch"
                                                    id="branch" onchange="selectFunction()">
                                                    <option selected disabled value=''>Select Entity</option>
                                                    @foreach ($branches as $branch)
                                                        <option value='{{ $branch->branch_id }}'>
                                                            {{ $branch->branch_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-3 col-md-12 col-sm-12">
                                            <div class="form-group">
                                                <label>SBU
                                                    :</label>
                                                <select class="form-control" name="sbu" id="sbu" disabled
                                                    onchange="selectFunction()">
                                                    <option selected disabled value="">Select SBU</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <hr>
                        <section>
                            <div class="row">
                                <div class="table-responsive col-12 table-rounded">
                                    <table class="table table-borderless" width="100%" id="datatables-ajax">
                                        <thead>
                                            <tr>
                                                <th scope="col">Employee Name</th>
                                                <th scope="col">Entity</th>
                                                <th scope="col">SBU</th>
                                                <th scope="col">Job Position</th>
                                                <th scope="col">Total Balance</th>
                                                <th scope="col">Remaining Balance</th>
                                                <th scope="col">Used Balance</th>
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
                                    <input type="url" name="remaining_balance" id="remaining_balance"
                                        placeholder="Rp." required class="form-control" />
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
        let entity = 0;
        let sbu = 0;
        $(document).ready(() => {
            $('#branch').select2();
        });

        const dataTable_Ajax = (entity, sbu) => {
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
                        url: `employee-balance-list?entity=${entity}&sbu=${sbu}`,
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
                            data: (data) => {
                                if (data.total_balance > 0) {
                                    return new Intl.NumberFormat("id-ID", {
                                        style: "currency",
                                        currency: "IDR"
                                    }).format(data.total_balance);
                                }

                                return 'Rp0';
                            },
                            name: 'Total Balance'
                        },
                        {
                            data: (data) => {
                                if (data.remaining_balance > 0) {
                                    return new Intl.NumberFormat("id-ID", {
                                        style: "currency",
                                        currency: "IDR"
                                    }).format(data.remaining_balance);
                                }

                                return 'Rp0';
                            },
                            name: 'Remaining Balance'
                        },
                        {
                            data: (data) => {
                                if (data.used_balance > 0) {
                                    return new Intl.NumberFormat("id-ID", {
                                        style: "currency",
                                        currency: "IDR"
                                    }).format(data.used_balance);
                                }

                                return 'Rp0';
                            },
                            name: 'Used Balance'
                        },
                        // {
                        //     data: 'action',
                        //     name: 'action'
                        // },
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
            $("#button").click(() => exportEmployeeBalance());
        }

        $(document).on('click', '#edit', async function(event) {
            $('#modal-form').modal('show');
            const btnEdit = $('#submit').attr('id', 'btnEdit');
            const id = $(this).data('id');
            const balanceId = document.querySelector('#id');
            const name = document.querySelector('#employee_name');
            const entity = document.querySelector('#entity');
            const job_position = document.querySelector('#job_position');
            const department = document.querySelector('#department');
            const remaining_balance = document.querySelector('#remaining_balance');
            const nip = document.querySelector('#nip');
            $('#modal-title').text('Edit Employee Balance');
            const idForm = $('form#form_data').attr('id', 'form_edit_data');

            await fetch(`/employee-balance/${id}/edit`)
                .then(response => response.json())
                .then(response => {
                    balanceId.value = id;
                    name.value = response.name;
                    entity.value = response.entity;
                    job_position.value = response.job_position;
                    department.value = response.department;
                    remaining_balance.value = response.remaining_balance;
                    nip.value = response.nip;
                });
            submitEdit();
        });

        function submitEdit() {
            Array.prototype.filter.call($('#form_edit_data'), function(form) {
                $('#btnEdit').unbind().on('click', function(event) {
                    if (form.checkValidity() === false) {
                        form.classList.add('invalid');
                    }
                    form.classList.add('was-validated');
                    event.preventDefault();
                    const formEditData = document.querySelector('#form_edit_data');
                    if (formEditData) {
                        const request = new FormData(formEditData);

                        const data = {
                            _token: request.get('_token'),
                            remaining_balance: request.get('remaining_balance')
                        };

                        const id = $('#id').val();

                        fetch(`/employee-balance/${id}`, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify(data),
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.error) {
                                    $.each(data.error, (prefix, val) => {
                                        $('div.' + prefix + '_error').text(val[0]);
                                    });
                                } else {
                                    setTimeout(() => {
                                        $('#datatables-ajax').DataTable().ajax.reload();
                                    }, 0);

                                    Swal.fire({
                                        type: 'success',
                                        title: 'Success!',
                                        text: data.message,
                                        confirmButtonClass: 'btn btn-success'
                                    });

                                    $('#modal-form').modal('hide');
                                }
                            });
                    } else {
                        submit();
                    }
                });
            });
        }

        function rupiahFormat(num, prefix) {
            let num_string = num.replace(/[^,\d]/g, '').toString();
            let split = num_string.split(',');
            let left = split[0].length % 3;
            let rupiah = split[0].substr(0, left);
            let thousand = split[0].substr(left).match(/\d{3}/gi);

            if (thousand) {
                let separator = left ? '.' : '';
                rupiah += separator + thousand.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;

            return prefix == undefined ? rupiah : (rupiah ? rupiah : '');
        }

        $(document).on('keyup', '#remaining_balance', function() {
            let amount = $('#remaining_balance')[0].value;

            amount = amount.split('Rp').join('');
            amount = parseInt(amount.split('.').join(''));

            if (amount < 0) {
                alertify.error('Insufficient Balance');
                $('.modal-body input').val('');
            } else {
                $('#remaining_balance')[0].value = rupiahFormat(this.value, 'Rp');
            }
        });

        const exportEmployeeBalance = () => {
            sbu = $('#sbu').val();
            entity = $('#branch').val();
            (sbu === null) ? sbu = 0: sbu;
            (entity === null) ? entity = 0: entity;

            const data = {
                sbu: sbu,
                branch: entity,
                export: 'export'
            };

            fetch(`/employee-balance-list?entity=${entity}&sbu=${sbu}&export=export`, {
                    method: 'GET'
                })
                .then(response => response.blob())
                .then(data => {
                    console.log('Success:', data);
                    const url = window.URL.createObjectURL(data);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = "Employee_Balance.xlsx";
                    document.body.appendChild(
                        a); // we need to append the element to the dom -> otherwise it will not work in firefox
                    a.click();
                    a.remove(); //afterwards we remove the element again  
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
        };

        let branchId = '';

        const selectFunction = () => {
            let branch = document.querySelector('#branch');
            let department = document.querySelector('#sbu');
            sbu = $('#sbu').val();
            entity = $('#branch').val();
            (sbu === null) ? sbu = 0: sbu;
            (entity === null) ? entity = 0: entity;

            if (entity != 0) {
                fetch(`employee-balance?branch=${entity}`)
                    .then(response => response.json())
                    .then(response => {
                        department.removeAttribute('disabled');

                        if (sbu == 0 || branchId != entity) {
                            if (department.length > 0) {
                                for (let i = 0; i < department.length; i++) {
                                    $('#sbu')
                                        .find('option')
                                        .remove();
                                }
                            }

                            const opt = document.createElement('option');
                            opt.value = '';
                            opt.selected = 'selected';
                            opt.disabled = 'disabled';
                            opt.innerHTML = 'Select SBU';
                            department.appendChild(opt);
                            if (response.departments.length > 0) {
                                response.departments.forEach((item, index) => {
                                    const opt = document.createElement('option');
                                    opt.value = item.department_id;
                                    opt.innerHTML = item.department_name;
                                    department.appendChild(opt);
                                });
                            }

                            $(document).ready(() => {
                                $('#branch').select2();
                                $('#sbu').select2();
                            });

                            branchId = entity;
                        }
                    });
            }

            if (sbu != 0) {
                department.value = sbu;
                $(document).ready(() => {
                    $('#branch').select2();
                    $('#sbu').select2();
                });
            }

            $("#datatables-ajax").DataTable().destroy();
            dataTable_Ajax(entity, sbu);
        };
        dataTable_Ajax(entity, sbu);
    </script>
@endsection
