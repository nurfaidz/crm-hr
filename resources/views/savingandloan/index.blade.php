@extends('partials.template')
@section('main')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-body">
                <p class="d-flex align-items-center">
                    <img src="{{ url('./img/icons/dashboard.svg') }}" alt="Dashboard">
                    <a href="{{ url('dashboard') }}" class="dashboard-link">Dashboard</a>
                    <a href="{{ url('#') }}" class="this-page-link">/ Saving and Loan </a>
                </p>
                <div class="card">
                    <div class="card-body">
                        <span class="tracker-saving-and-loan">Tracker Saving and Loan</span>
                        <div class="card">
                            <div class="medical-reimbursement-your-statistic">
                                <div class="outside">
                                    <div class="card-header">
                                        <h4>YOUR STATISTIC</h4>
                                        {{-- <div>
                                            <input type="text" class="medical-year" readonly>
                                            <span><img src="{{ url('img/icons/dropdown-arrow.svg') }}"
                                                    alt="dropdown-arrow"></span>
                                        </div> --}}
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-12 d-flex">
                                                <img src="{{ url('img/icons/monetization-on-blue.svg') }}"
                                                    alt="monetization-on-blue">
                                                <div class="statistic">
                                                    <div id="balance"></div>
                                                    <div>Balance</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-12 d-flex">
                                                <img src="{{ url('img/icons/more-time-orange.svg') }}"
                                                    alt="more-time-orange">
                                                <div class="statistic">
                                                    <div id="total-expenses"></div>
                                                    <div>Total Expenses</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-12 d-flex justify-content-right">
                                                <a href="{{ url('saving-and-loan/laporan-pinjam') }}"><button type="button"
                                                        id="data-bayar" class="btn btn-primary">Laporan</button>
                                                </a>
                                            </div>
                                            {{-- <div class="col-lg-3 col-md-6 d-flex">
                                                <img src="{{ url('img/icons/download-done.svg') }}" alt="download-done">
                                                <div class="statistic">
                                                    <div id="number-of-approved"></div>
                                                    <div>Number of Approved</div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-6 d-flex">
                                                <img src="{{ url('img/icons/clear.svg') }}" alt="clear">
                                                <div class="statistic">
                                                    <div id="number-of-rejected"></div>
                                                    <div>Number of Rejected</div>
                                                </div>
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <section id="medical-reimbursement-pending-status">
                            <div class="d-flex align-items-center justify-content-between">
                                <h4>Pending Status</h4>
                                <button type="button" id="request-medical-reimbursement" class="btn btn-primary"
                                    data-toggle="modal" data-target="#modal-request-medical-reimbursement">+ Request
                                    Saving and Loan</button>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-borderless text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            {{-- <th>Type</th> --}}
                                            <th>Date</th>
                                            <th>Total Expenses</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
                                            <th class="text-center">Cancel</th>
                                        </tr>
                                    </thead>
                                    <tbody id="pending-status-data"></tbody>
                                </table>
                            </div>
                        </section>
                        <section id="saving-loan-history">
                            <h4>Saving and Loan History</h4>
                            <div class="table-responsive table-rounded-outline text-nowrap">
                                <table id="saving-loan-history-datatables" class="table table-borderless">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Saving Loan Date</th>
                                            <th>Total Expenses</th>
                                            <th>Total Saving Loan</th>
                                            {{-- <th class="text-center">Notes</th> --}}
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </section>
                        {{-- <div id="count-glasses-reimbursement" class="d-none">{{ $countGlassesReimbursement }}</div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-request-medical-reimbursement" tabindex="-1" role="dialog"
        aria-labelledby="modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-title">Request Saving and Loan</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-data" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" id="id">
                        {{-- <div class="form-group">
                            <label for="category">Category<span class="text-danger">*</span></label>
                            <select name="category" id="category" class="form-control" onchange="checkSelectShow(this)"
                                required>
                                <option hidden disabled selected value>Select Category</option>
                                <option value="0">Inpatient</option>
                                <option value="1">Outpatient</option>
                            </select>
                            <div class="invalid-feedback category_error"></div>
                        </div> --}}
                        {{-- <div class="form-group">
                            <label for="type">Type<span class="text-danger">*</span></label>
                            <textarea name="type" id="type" class="form-control" cols="30" rows="5" placeholder="Input Type"
                                required></textarea>
                            <div class="invalid-feedback notes_error"></div>
                        </div>
                        <div class="form-group" id="outpatient_type_select"></div> --}}
                        <div class="form-group">
                            <label for="amount">Amount<span class="text-danger">*</span></label>
                            <input type="text" name="amount" id="amount" class="form-control" placeholder="Rp."
                                required>
                            <div class="invalid-feedback amount_error"></div>
                        </div>
                        <div class="form-group">
                            <label for="attachment">Attachment<span class="text-danger">*</span></label>
                            <input type="file" name="attachment[]" id="attachment" class="form-control-file d-none"
                                onchange="getAttachmentData(this)" required multiple>
                            <button type="button" id="attachment-substitute" class="form-control btn btn-outline-primary">
                                <img src="{{ url('img/icons/cloud-upload.svg') }}" alt="cloud-upload">Click here to upload
                            </button>
                            <div class="invalid-feedback attachment_error"></div>
                        </div>
                        <div class="custom-input-image-container"></div>
                        <div class="form-group">
                            <label for="note">Notes<span class="text-danger">*</span></label>
                            <textarea name="note" id="note" class="form-control" cols="30" rows="5"
                                placeholder="Input Description" required></textarea>
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

    <div class="modal fade text-left" id="modal-notes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title-note">Notes Attendance</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body mb-2">
                    <div class="mb-1">
                        <span class="note_date"></span>
                    </div>
                    <label id="label_note">Note: </label>
                    <div class="form-group">
                        <textarea class="form-control" id="note" readonly></textarea>
                        <div class="invalid-feedback">Please enter appropriate value.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('page_js')
    <script src="{{ url('app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
    <script src="{{ url('app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
    <script src="{{ url('app-assets/js/scripts/components/components-popovers.js') }}"></script>
@endsection

@section('page_script')
    <script>
        const statistics = (year) => {
            $.ajax({
                url: "{{ url('saving-and-loan/statistic') }}" + '/' + year,
                method: 'GET',
                dataType: 'json',
                success: (result) => {
                    const {
                        savingLoanBalance,
                        totalExpenses,
                        // numberOfApproved,
                        // numberOfRejected
                    } = result.data;

                    $('#balance').html(`Rp${savingLoanBalance.toLocaleString('id-ID')}`);
                    $('#total-expenses').html(`Rp${totalExpenses.toLocaleString('id-ID')}`);
                    // $('#number-of-approved').html(numberOfApproved);
                    // $('#number-of-rejected').html(numberOfRejected);
                }
            });
        }

        const pendingStatusData = () => {
            $.ajax({
                url: "{{ url('saving-and-loan/data') }}",
                method: 'GET',
                dataType: 'json',
                success: (result) => {
                    let tr = '';

                    if (result.data.length == 0) {
                        tr += `
                            <tr>
                                <td colspan="7" id="no-data-available">No data available in table</td>
                            </tr>
                        `;
                    } else {
                        result.data.forEach((element, index) => {
                            tr += `
                                <tr class="data-row-${element.cooperative_id}">
                                    <td>${index + 1}</td>
                                    <td>${element.date}</td>
                                    <td>${element.total_expenses}</td>
                                    <td class="text-center">
                                        <span id="pending-status-status" class="badge badge-pill badge-light-warning">${element.status}</span>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" id="pending-status-details" data-id="${element.cooperative_id}" class="btn btn-outline-info round pending-status-details" onclick="location.href='{{ url('saving-and-loan/details/${element.cooperative_id}') }}'">Details</button>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" id="pending-status-cancel" data-id="${element.cooperative_id}" class="btn btn-outline-danger round pending-status-cancel">Cancel</button>
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

            $('#category').select2();
            $('#outpatient_type').select2();

            let status = 0;

            const dataTablesAjax = (status) => {
                const dt_ajax_table = $("#saving-loan-history-datatables");

                if (dt_ajax_table.length) {
                    const dt_ajax = dt_ajax_table.dataTable({
                        processing: true,
                        dom: '<"d-flex justify-content-between align-items-end mx-0 row"<"col"l><"col-md-auto p-0."f><"#leave-history-filter.btn btn-md btn-primary"><"#leave-history-export.btn btn-md btn-primary">>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                        ajax: `/saving-and-loan/list/${status}`,
                        ordering: false,
                        language: {
                            paginate: {
                                previous: '&nbsp;',
                                next: '&nbsp;'
                            },
                            search: '<span id="medical-reimbursement-history-datatables-search" class="d-flex"><img src="{{ url('img/icons/search-gray.svg') }}" alt="">Search</span>'
                        },
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex'
                            },
                            {
                                data: (data) => data.date.split('-').reverse().join(
                                    '/'),
                                name: 'Saving Loan Date'
                            },
                            {
                                data: (data) => 'Rp' + data.amount.toLocaleString('id-ID'),
                                name: 'Total Expenses'
                            },
                            {
                                data: (data) => {
                                    if (data.total_saving_loan == null) {
                                        return '-';
                                    } else {
                                        return 'Rp' + data.total_saving_loan.toLocaleString(
                                            'id-ID');
                                    }
                                },
                                name: 'Total Saving Loan'
                            },
                            // {
                            //     data: 'note',
                            //     name: 'Note'
                            // },
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
                            targets: [4, 5],
                            className: 'text-center'
                        }]
                    });
                }

                $("#leave-history-filter").html(
                    '<div>Filter</div><img src="{{ url('./img/icons/angle-down.svg') }}">');
                $("#leave-history-export").html(
                    '<div><img src="{{ url('./img/icons/open-in-new.svg') }}"><a href="{{ url('saving-and-loan/export') }}" style="color: inherit;">Export</a></div><img src="{{ url('./img/icons/angle-down.svg') }}" id="angle-down">'
                );
            }

            pendingStatusData();
            dataTablesAjax(status);
        });

        // const notes = (e) => {
        //     $('#modal-notes').modal('show');
        //     const id = e.attr('data-id');
        //     $.ajax({
        //         url: "{{ url('attendance-self-service') }}" + "/" + id,
        //         method: "GET",
        //         dataType: "json",
        //         success: function(result) {
        //             if (result.data.status === 'shy') {
        //                 $('#note').show();
        //                 $('#label_note').html('Note: ');
        //                 $('.note_date').html(`<b>${result.data.date}</b>`);
        //                 $('#note').html(result.data.note);
        //             } else {
        //                 $('#label_note').html('');
        //                 $('#note').attr('rows', 10);
        //                 $('.note_date').html(`<b>${result.data.date}</b>`);
        //                 $('#note').html(result.data.note);
        //             }

        //         }
        //     });
        // }

        function checkSelectShow(selectedOption) {
            if (selectedOption.value == 1) {
                $('#modal-request-medical-reimbursement #outpatient_type_select').show();

                let countGlassesReimbursement = $('#count-glasses-reimbursement').text();

                if (countGlassesReimbursement > 0) {
                    $('#outpatient_type_select').html('');
                    $('#outpatient_type_select').append(
                        '<label for="outpatient_type">Outpatient Type<span class="text-danger">*</span></label><select name="outpatient_type" id="outpatient_type" class="form-control" required><option hidden disabled="disabled" selected="selected" value>Select Outpatient Type</option><option value="0">Laboratory Examinations</option><option value="1">Doctor Consultation</option></select><div class="invalid-feedback outpatient_type_error"></div>'
                    );
                    $('#outpatient_type').select2();
                } else {
                    $('#outpatient_type_select').html('');
                    $('#outpatient_type_select').append(
                        '<label for="outpatient_type">Outpatient Type<span class="text-danger">*</span></label><select name="outpatient_type" id="outpatient_type" class="form-control" required><option hidden disabled="disabled" selected="selected" value>Select Outpatient Type</option><option value="0">Laboratory Examinations</option><option value="1">Doctor Consultation</option><option value="2">Glasses Replacement</option></select><div class="invalid-feedback outpatient_type_error"></div>'
                    );
                    $('#outpatient_type').select2();
                }
            } else {
                $('#modal-request-medical-reimbursement #outpatient_type_select').hide();
            }
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

            return prefix == undefined ? rupiah : (rupiah ? 'Rp' + rupiah : '');
        }

        $(document).on('keyup', '#amount', function() {
            let balance = $('#reimbursement-balance').text();
            let amount = $('#amount')[0].value;
            let selectedOption = $('#outpatient_type').find(':selected').text();

            balance = balance.split('Rp').join('');
            balance = parseInt(balance.split('.').join(''));
            amount = amount.split('Rp').join('');
            amount = parseInt(amount.split('.').join(''));

            if (balance - amount < 0) {
                alertify.error('Insufficient Balance');
                $('.modal-body input').val('');
            } else if (selectedOption == 'Glasses Replacement') {
                if (amount > 2000000) {
                    alertify.error('Maximum Rp2.000.000 for Glasses Replacement');
                    $('.modal-body input').val('');
                }
            } else {
                $('#amount')[0].value = rupiahFormat(this.value, 'Rp');
            }
        });

        $(document).on('click', '#attachment-substitute', function() {
            $('#attachment').click();
        });

        function getAttachmentData(attachment) {
            $('.custom-input-image-container').html('');
            let files = document.getElementById('attachment').files;

            for (let i = 0; i < files.length; i++) {
                $('.custom-input-image-container').append(
                    `<div class="form-group"><div class="input-group"><div class="input-group-prepend"><span class="input-group-text bg-white"><img src="{{ url('img/icons/file-copy.svg') }}" alt="file-copy"></span></div><input type="text" class="form-control bg-white custom-input-image-name" placeholder="${files[i].name}" disabled="disabled"><div class="input-group-append"><span class="input-group-text bg-white custom-input-image-size">${(files[i].size / Math.pow(1024, 1)).toString().split('.')[0]} Kb</span></div></div></div>`
                );
            }
        }

        $('#modal-request-medical-reimbursement').on('hidden.bs.modal', function() {
            let resetForm = $('#form-data')[0];
            $(resetForm).trigger('reset');
            $(resetForm).removeClass('was-validated');

            $('.modal-body select').val('').trigger('change.select2');
            $('.custom-input-image-container').html('');
        });

        $(document).on('click', '#request-medical-reimbursement', function() {
            const idForm = $('form#form_edit_data').attr('id', 'form-data');
            // const category = document.querySelector('#category');
            // const outpatientType = document.querySelector('#outpatient_type');
            // const type = document.querySelector('#type');
            const amount = document.querySelector('#amount');
            const attachment = document.querySelector('#attachment');
            const note = document.querySelector('#note');

            function checkSelectShow(selectedOption) {
                if (selectedOption.value == 0) {
                    // category.value = '';
                    // type.value = '';
                    amount.value = '';
                    attachment.value = '';
                    note.value = '';
                } else {
                    // category.value = '';
                    // type.value = '';
                    outpatientType.value = '';
                    amount.value = '';
                    attachment.value = '';
                    note.value = '';
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
                        url: "{{ url('saving-and-loan') }}",
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

                                let fields = ['type', 'amount',
                                    'attachment', 'note'
                                ];

                                for (let i = 0; i < fields.length; i++) {
                                    let error = response.error.hasOwnProperty(fields[i]) ?
                                        true : false;

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
                                    $('#count-glasses-reimbursement').load(location
                                        .href + ' #count-glasses-reimbursement');
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
                        url: 'medical-reimbursement/cancel/' + id,
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
                                    $('#medical-reimbursement-history-datatables')
                                        .DataTable().ajax.reload();
                                    $('#count-glasses-reimbursement').load(location
                                        .href + ' #count-glasses-reimbursement');
                                }, 0);

                                alertify.success(result.message);
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection
