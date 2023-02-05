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
                    <a href="{{ url('#') }}" class="this-page-link">/ Laporan </a>
                </p>
                <div class="card">
                    <div class="card-body">
                        <span class="tracker-saving-and-loan">Laporan Pinjam</span>
                        <div class="card">
                            <div class="outside">
                            </div>
                        </div>
                        <section id="saving-loan-history">
                            <div class="table-responsive table-rounded-outline text-nowrap">
                                <table id="saving-loan-history-datatables" class="table table-borderless">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Saving Loan Date</th>
                                            <th>Balance</th>
                                            <th>Total Expenses</th>
                                            <th>Amount</th>
                                            <th>Total Saving Loan</th>
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


            let status = 0;

            const dataTablesAjax = (status) => {
                const dt_ajax_table = $("#saving-loan-history-datatables");

                if (dt_ajax_table.length) {
                    const dt_ajax = dt_ajax_table.dataTable({
                        processing: true,
                        dom: '<"d-flex justify-content-between align-items-end mx-0 row"<"col"l><"col-md-auto p-0."f><"#leave-history-filter.btn btn-md btn-primary"><"#leave-history-export.btn btn-md btn-primary">>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                        ajax: `/saving-and-loan/list2/${status}`,
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
                                data: (data) => 'Rp' + data.balance.toLocaleString('id-ID'),
                                name: 'Balance'
                            },
                            {
                                data: (data) => 'Rp' + data.expenses.toLocaleString('id-ID'),
                                name: 'Total Expenses'
                            },
                            {
                                data: (data) => 'Rp' + data.amount.toLocaleString('id-ID'),
                                name: 'Amount'
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
                        ],
                        // columnDefs: [{
                        //     targets: [4, 5],
                        //     className: 'text-center'
                        // }]
                    });
                }

                $("#leave-history-filter").html(
                    '<div>Filter</div><img src="{{ url('./img/icons/angle-down.svg') }}">');
                $("#leave-history-export").html(
                    '<div><img src="{{ url('./img/icons/open-in-new.svg') }}"><a href="{{ url('saving-and-loan/export') }}" style="color: inherit;">Export</a></div><img src="{{ url('./img/icons/angle-down.svg') }}" id="angle-down">'
                );
            }

            dataTablesAjax(status);
        });


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
    </script>
@endsection
