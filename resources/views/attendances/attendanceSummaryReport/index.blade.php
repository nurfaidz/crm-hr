@extends('partials.template')
@section('meta_header')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@latest/dist/plugins/monthSelect/style.css">
@endsection
@section('main')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">

                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-left mb-0">Overall Recap</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <div class="card">
                    <div class="card-body">
                        <section>
                            <form id="form_data" action="" method="post">
                                @method('get')
                                @csrf
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label>SBU:</label>
                                            <select class="form-control" name="department" id="department" required>
                                                <option selected disabled value=""> -- Select --</option>
                                                @foreach ($departments as $department)
                                                    <option value="{{ $department->department_id }}"
                                                        {{ $department->department_id === request('department') }}
                                                        {{ $de == $department->department_id ? 'selected' : '' }}>
                                                        {{ $department->department_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label>Entity:</label>
                                            <select class="form-control" name="branch" id="branch" required>
                                                <option selected disabled value=""> -- Select --</option>
                                                @foreach ($branches as $item)
                                                    <option value="{{ $item->branch_id }}"
                                                        {{ $item->branch_id === request('branch') }}
                                                        {{ $br == $item->branch_id ? 'selected' : '' }}>
                                                        {{ $item->branch_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label>Date:</label>
                                            <div class="form-group">
                                                <input type="text" name="date" id="date"
                                                    value="{{ request('date') }}" class="form-control" />
                                                <div class="valid-feedback">Looks good!</div>
                                                <div class="invalid-feedback">Please select Date Range.</div>
                                                <input type="hidden" name="attendance_from_date" id="attendance_from_date"
                                                    value="">
                                                <input type="hidden" name="attendance_to_date" id="attendance_to_date"
                                                    value="">

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="form-group">
                                            <div class="btn-group">
                                                <button type="submit" id="filter"
                                                    class="btn btn-primary mt-lg-2">Filter</button>
                                                @if (!isset($month) && count($attendances) == 0)
                                                    <button type="submit" id="export" name="export"
                                                        class="btn btn-success mt-lg-2" disabled>Export</button>
                                                @elseif (isset($month) && count($attendances) >= 1)
                                                    <a target="_blank" type="submit" id="export"
                                                        class="btn btn-success mt-lg-2"
                                                        href="{{ url('download-attendance-summary-report/' . $month . '/' . $de . '/' . $br) }}">Export</a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </section>
                        <section>
                            <div class="content-body">
                                <div class="card">
                                    <div class="card-body">
                                        <section id="ajax-datatable">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="card">
                                                        <div class="card-datatable" style="overflow-x: scroll">
                                                            <table class="table" id="datatables-ajax">
                                                                <thead>
                                                                    <tr>
                                                                        <th scope="col">No</th>
                                                                        <th scope="col">Date</th>
                                                                        <th scope="col">Employee's Name</th>
                                                                        <th scope="col">Check In</th>
                                                                        <th scope="col">Check Out</th>
                                                                        <th scope="col">Work Time</th>
                                                                        <th scope="col">Late Start</th>
                                                                        <th scope="col">Late Time</th>
                                                                        <th scope="col">Overtime</th>
                                                                        <th scope="col">Status</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @if (!isset($month) || count($attendances) == 0)
                                                                        <tr>
                                                                            <td colspan="10"
                                                                                class="text-center font-weight-bold">Tidak
                                                                                Ada Data
                                                                            </td>
                                                                        </tr>
                                                                    @elseif (isset($month) && count($attendances) >= 1)
                                                                        @foreach ($attendances as $item)
                                                                            <tr>
                                                                                <th scope="row">{{ $loop->iteration }}
                                                                                </th>
                                                                                <td>{{ date('Y-m-d', strtotime($item['check_in'])) }}
                                                                                </td>
                                                                                <td>{{ $item->user->name }}</td>
                                                                                <td>{{ date('H:i', strtotime($item['check_in'])) }}
                                                                                </td>
                                                                                <td>{{ date('H:i', strtotime($item['check_out'])) }}
                                                                                </td>
                                                                                <td>{{ $item->work_time }}</td>
                                                                                <td>08:00</td>
                                                                                <td>{{ $item->late_time }}</td>
                                                                                <td>0</td>
                                                                                <td>Hadir</td>
                                                                            </tr>
                                                                        @endforeach
                                                                    @endif
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
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
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@latest/dist/plugins/monthSelect/index.js"></script>
@endsection

@section('page_script')
    @if (session()->has('error'))
        <script>
            $(document).ready(function() {
                Swal.fire({
                    title: "{{ session('error') }}",
                    text: "Dont Worry be Happy",
                    icon: "danger",
                    button: "Ok",
                    timer: 2000
                });
            });
        </script>
    @endif
    <script type="module">
        flatpickr("#date", {
            plugins: [
                new monthSelectPlugin({
                    dateFormat: "Y-m", //defaults to "F Y"
                    shorthand: false, //defaults to false
                    theme: "light" // defaults to "light"
                })
            ]
        });
    </script>
@endsection
