@extends('partials.template')
@section('main')
    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-body">
                <div class="card">
                    <div class="card-header">
                        <div class="row breadcrumbs-top">
                            <div class="col-12">
                                <h3 class="float-left mb-0">Tracker</h3>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="leave-tab" data-toggle="tab" href="#leave_application"
                                    aria-controls="leave_application" role="tab" aria-selected="true">Most
                                    Attendance</a>
                            </li>
                        </ul>
                        <div class="tab-content">

                            <div class="tab-pane active" id="attendance" aria-labelledby="attendance" role="tabpanel">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card-datatable table-rounded-outline">
                                            <table class="table table-borderless nowrap">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Employee Name</th>
                                                    </tr>
                                                    <?php
                                                    $i = 1;
                                                    ?>
                                                    @foreach ($as[3][0] as $key => $a)
                                                        <tr>
                                                            <td>{{ $i++ }}</td>
                                                            <td>{{ $a }}</td>
                                                        </tr>
                                                    @endforeach
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- <div class="card-body">
                            @foreach ($data as $s)
                                <p>{{ $s }}</p>
                            @endforeach
                        </div> --}}

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
            $('#modal-form').on('hidden.bs.modal', function(event) {
                const reset_form = $('#form_data')[0];

                $("#form_data").trigger("reset");
                $(reset_form).removeClass('was-validated');
                $("#form_data").trigger("reset");

            });

            attendance_ajax();

            $('a[data-toggle="tab"]').on('shown.bs.tab', (e) => {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust()
                    .responsive.recalc();
            });
        });
    </script>
@endsection
