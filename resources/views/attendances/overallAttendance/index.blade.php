@extends('partials.template')
@section('main')
    <style>
        .second-tr>th {
            background-color: white !important;
            /* position: fixed; */
            font-weight: 400;
            font-size: larger;
        }

        td {
            height: 40px;
            text-align: center;
        }

        tr {
            height: 60px;
        }

        .present {
            color: #7ace4c;
            font-weight: 700;
            cursor: pointer;
        }

        .absence {
            color: #f33155;
            font-weight: 700;
            cursor: pointer;
        }

        .leave {
            color: #41b3f9;
            font-weight: 700;
            cursor: pointer;
        }

        .bolt {
            font-weight: 700;
        }

        .myTable {
            overflow: scroll;
        }

        .myTable th {
            position: sticky;
            top: 0;
        }

        .myTable th:nth-child(1),
        .myTable td:nth-child(1) {
            position: sticky;
            left: 0;
        }

        .myTable th:nth-child(2),
        .myTable td:nth-child(2) {
            position: sticky;
            /* 1st cell left/right padding + 1st cell width + 1st cell left/right border width */
            /* 0 + 5 + 150 + 5 + 1 */
            left: 60px;
            min-width: 250px;
        }

        .myTable th:nth-child(5),
        .myTable td:nth-child(5) {
            position: sticky;
            /* 1st cell left/right padding + 1st cell width + 1st cell left/right border width */
            /* 0 + 5 + 150 + 5 + 1 */
            left: 300px;
        }

        .myTable td:nth-child(1),
        .myTable td:nth-child(2),
        .myTable td:nth-child(5) {
            background: white;
        }

        .myTable th:nth-child(1),
        .myTable th:nth-child(2),
        .myTable th:nth-child(5) {
            z-index: 2;
        }
    </style>

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
                            <div class="row">
                                <div class="col-lg-8 col-md-12 col-sm-12">
                                </div>
                            </div>

                        </section>
                        <section>
                            <div class="row">
                                <div class="card-datatable table-responsive table-rounded col-12 myTable">
                                    <table style="white-space: nowrap"
                                        class="table table-borderless table-striped table-hover" id="tableId">
                                        <thead id='thead' class="tableizer-firstrow">
                                            <tr>
                                                <th>#</th>
                                                <th>Year</th>
                                                <th>Month</th>
                                                @php
                                                    $total_col = 0;
                                                    foreach ($monthToDate as $key => $val) {
                                                        $total_col++;
                                                    }
                                                    $total_col += 9;
                                                    $total_day = $workday;
                                                @endphp
                                                <th colspan="{{ $total_col }}" class="totalCol"></th>
                                            </tr>
                                            <tr class="second-tr">
                                                <th>#</th>
                                                <th>
                                                    @if (isset($month))
                                                        @php
                                                            
                                                            $exp = explode('-', $month);
                                                            echo $exp[0];
                                                        @endphp
                                                    @else
                                                        {{ date('Y') }}
                                                    @endif
                                                </th>
                                                <th>{{ $monthName }}</th>
                                                <th></th>
                                                <th></th>
                                                @foreach ($monthToDate as $head)
                                                    <th>{{ $head['day_name'] }}</th>
                                                @endforeach
                                                <th>Workday</th>
                                                <th>Attend</th>
                                                <th>Absence</th>
                                                <th>Late Time</th>
                                                <th>Total Leave</th>
                                                <th>Leave Balance</th>
                                                <th>Overtime</th>
                                            </tr>
                                            <tr>
                                                <th>NO</th>
                                                <th>EMPLOYEE NAME</th>
                                                <th>ENTITY</th>
                                                <th>SBU</th>
                                                <th>JOB POSITION</th>
                                                @foreach ($monthToDate as $head)
                                                    <th>{{ $head['day'] }}</th>
                                                @endforeach
                                                <th></th> 
                                                <th></th> 
                                                <th></th> 
                                                <th></th> 
                                                <th></th> 
                                                <th></th> 
                                                <th></th> 
                                                
                                            </tr>
                                        </thead>
                                        <tbody id='tbody'>


                                            @php
                                                $sl = null;
                                                $totalPresent = 0;
                                                $totalAbsence = 0;
                                                $totalLeave = 0;
                                                $leaveData = [];
                                                $totalCol = 0;
                                            @endphp
                                            @foreach ($results as $key => $value)
                                                <tr>
                                                    <td>{{ ++$sl }}</td>
                                                    <td style="text-align: left;">
                                                        <div class="d-flex align-items-center">
                                                            <img src="{{ $value[0]['image'] }}" alt="Employee Profile Picture" class="rounded-circle" width="32px" height="32px">
                                                            <div class="p-1">
                                                                <b style="color: #7367F0;background-color: transparent;">{{ $key }}</b><br>
                                                                {{ $value[0]['nip'] }}
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $value[0]['entity'] }}</td>
                                                    <td>{{ $value[0]['designation_name'] }}</td>
                                                    <td>{{ $value[0]['position'] }}</td>
                                                    @foreach ($value as $v)
                                                        @php
                                                            if ($sl == 1) {
                                                                $totalCol++;
                                                            }
                                                            if ($v['attendance_status'] == 'notWorkday') {
                                                                $total_day--;
                                                                echo "<td><span><b>-</b></span></td>";
                                                            } elseif ($v['attendance_status'] == 'present') {
                                                                $totalPresent++;
                                                                echo "<td><span class='present' title='Present'>P</span></td>";
                                                            } elseif ($v['attendance_status'] == 'absence') {
                                                                $totalAbsence++;
                                                                echo "<td><span class='absence' title='Absence'>A</span></td>";
                                                            } elseif ($v['attendance_status'] == 'leave') {
                                                                $totalLeave++;
                                                                echo "<td><span class='leave' title='Leave'>L</span></td>";
                                                            } else {
                                                                echo '<td></td>';
                                                            }
                                                        @endphp
                                                    @endforeach
                                                    @php
                                                        $hours = floor($value[0]['late_duration'] / 60);
                                                        $min = $value[0]['late_duration'] - $hours * 60;
                                                        $value[0]['late_duration'] = $hours . ' H ' . $min . ' M';
                                                    @endphp
                                                    <td><span class="bolt">{{ $total_day . ' Days' }}</span></td>
                                                    <td><span class="bolt">{{ $totalPresent }}</span></td>
                                                    <td><span class="bolt">{{ $totalAbsence }}</span></td>
                                                    <td><span
                                                            class="bolt">{{ $value[0]['late_duration'] }}</span>
                                                    </td>
                                                    <td><span
                                                            class="bolt">{{ $totalLeave }}</span>
                                                    </td>
                                                    <td><span
                                                            class="bolt">{{ $value[0]['leave_balance'] }}</span>
                                                    </td>
                                                    <td><span
                                                            class="bolt">{{ $value[0]['overtime_duration'] / 60 . ' H' }}</span>
                                                    </td>
                                                    @php
                                                        $totalPresent = 0;
                                                        $totalAbsence = 0;
                                                        $totalLeave = 0;
                                                        $total_day = $workday;
                                                        
                                                    @endphp
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>


                <div id="empty" class="card">
                    <div class="card-body text-center">
                        <h5>No matching records found</h5>
                    </div>
                </div>
            </div>

        </div>


    </div>

    <style>
        .second-th>th {
            background-color: white !important;
            /* position: fixed; */
        }
    </style>
@endsection

@section('page_js')
    <script src="{{ url('app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
    <script src="{{ url('app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@latest/dist/plugins/monthSelect/index.js"></script>
@endsection

@section('page_script')
    <script type="text/javascript">
        let entity = 0;
        let sbu = 0;

        $('#tableId').dataTable({
            processing: true,
            dom: `<"d-flex align-items-end mx-0 row"
    <"#branch.form-group col-lg-3 p-0 mt-1 mb-50">
    <"#department.form-group col-lg-2 px-lg-1 p-0 mt-1 mb-50">
    <"#date.form-group col-lg-2 mt-1 px-lg-1 p-0 mb-50">
    <".col-lg-4 px-lg-1 p-0"f>
    <".col-lg-1 px-lg-1 text-right mt-1"
    <"#button.btn btn-md btn-outline-primary">
    >>`,
            "lengthChange": false,
            "scrollX": 'true',
            "scrollY": 200,
            paging: false,
            ordering: false,
        });

        $("#button").html('Export');
        $("#button").attr('style', 'margin-bottom: 7px');
        $("#button").click(() => exportOverall());

        const exportOverall = () => {
            date = $('#month-date').val();
            entity = $('#branch_id').val();
            sbu = $('#department_id').val();
            (entity === null) ? entity = 0: entity;
            // (date === null) ? date = 0 : date;
            (sbu === null) ? sbu = 0: sbu;

            fetch(`/download-overall-attendance?branch_id=${entity}&department_id=${sbu}&date=${date}`, {
                    method: 'GET'
                })
                .then(response => response.blob())
                .then(data => {
                    console.log('Success:', data);
                    const url = window.URL.createObjectURL(data);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = "overall_attendance.xlsx";
                    document.body.appendChild(
                        a); // we need to append the element to the dom -> otherwise it will not work in firefox
                    a.click();
                    a.remove(); //afterwards we remove the element again  
                })
                .catch((error) => {
                    console.error('Error:', error);
                });
        };


        $("#branch").append(
            `<select class="form-control" name="branch_id" id="branch_id" onchange="selectFunction()">
      <option selected disabled value="">Select Entity</option>
    </select>`
        );
        $("#branch").css('margin-bottom', '0.5rem !important');

        $("#department").append(
            `<select class="form-control" name="department_id" id="department_id" onchange="selectFunction()" disabled><option selected disabled value="">Select SBU</option></select>`
        );
        $("#department").css('margin-bottom', '0.5rem !important');

        if (status.length > 0) {
            $("#code").val(status);
        }

        $("#date").append(
            `<input type="text" name="date" aria-controls="datatables-ajax" id="month-date" value="{{ request('date') }}" class="form-control" placeholder="Select Date" />`
        );
        $("#date").css('margin-bottom', '0.5rem !important');

        $('#empty').hide()

        flatpickr("#month-date", {
            defaultDate: "<?php echo $month; ?>",
            plugins: [
                new monthSelectPlugin({
                    shorthand: true, //defaults to false
                    dateFormat: "Y-m", //defaults to "F Y"
                    altFormat: "F Y", //defaults to "F Y"
                })
            ],
            onChange: function(selectedDates, dateStr) {
                entity = $('#branch_id').val();
                sbu = $('#department_id').val();
                (entity === null) ? entity = 0: entity;
                (sbu === null) ? sbu = 0: sbu;

                getOverall(entity, sbu, dateStr);
            },
        });
        
        const branchId = document.querySelector('#branch_id');
        if (branchId) {
            fetch(`/branch/select`)
                .then(response => response.json())
                .then(response => {
                    response.data.map(data => {
                        console.log(data);
                        const opt = document.createElement('option');
                        opt.value = data.id;
                        opt.innerHTML = data.text;
                        branchId.appendChild(opt);
                    });
                });

            $(document).ready(() => {
                $('#branch_id').select2();
            });
        }

        let branch = '';

        const selectFunction = () => {
            let branchId = document.querySelector('#branch_id');
            let department = document.querySelector('#department_id');
            date = $('#month-date').val();
            entity = $('#branch_id').val();
            sbu = $('#department_id').val();
            (entity === null) ? entity = 0: entity;
            (sbu === null) ? sbu = 0: sbu;
            if (entity != 0) {
                fetch(`/departments/select/${entity}`)
                    .then(response => response.json())
                    .then(response => {
                        department.removeAttribute('disabled');

                        if (sbu == 0 || branch != entity) {
                            if (department.length > 0) {
                                for (let i = 0; i < department.length; i++) {
                                    $('#department_id')
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
                            console.log(response);
                            if (response.data.length > 0) {
                                response.data.map(data => {
                                    const opt = document.createElement('option');
                                    opt.value = data.id;
                                    opt.innerHTML = data.text;
                                    department.appendChild(opt);
                                });
                            }

                            $(document).ready(() => {
                                $('#branch_id').select2();
                                $('#department_id').select2();
                            });

                            branch = entity;
                        }
                    });
            }

            getOverall(entity, sbu, date);
        };

        const getOverall = (entity, sbu, date) => {
            fetch(`/get-overall-attendance?branch_id=${entity}&department_id=${sbu}&date=${date}`)
                .then(response => response.json())
                .then(response => {
                    updateTable(response);
                });
        };

        function updateTable(response) {
            $('#tbody').html('');
            $('#thead').html('');
            let headRow = ''
            let dataRow = ''
            let number = 0;
            if (response['dataFormat'].length == 0) {
                $('#empty').show()
            } else {
                $('#empty').hide()
            }
            Object.keys(response['dataFormat']).forEach(key => {
                // isi table
                let tr = document.createElement("tr")
                dataRow =
                    '<td>' + (++number) + '</td>' +
                    // '<td>' + key + '</td>' +
                    `<td style="text-align: left;">
                        <div class="d-flex align-items-center">
                            <img src="`+response['dataFormat'][key][0]['image']+`" alt="Employee Profile Picture" class="rounded-circle" width="32px" height="32px">
                            <div class="p-1">
                                <b>`+key+`</b><br>
                                `+response['dataFormat'][key][0]['nip']+`
                            </div>
                        </div>
                    </td>`+
                    '<td>' + response['dataFormat'][key][0]['entity'] + '</td>' +
                    '<td>' + response['dataFormat'][key][0]['designation_name'] + '</td>' +
                    '<td>' + response['dataFormat'][key][0]['position'] + '</td>'
                window.totalPresent = 0
                window.totalAbsence = 0
                window.totalLeave = 0
                console.log(response['workday'])
                window.totalDay = response['workday']
                response['dataFormat'][key].forEach(element => {

                    if (element['attendance_status'] == 'notWorkday') {
                        window.totalDay--;
                        dataRow +=
                            "<td><span><b>-</b></span></td>"
                    } else if (element['attendance_status'] == 'present') {
                        window.totalPresent++;
                        dataRow +=
                            "<td><span class='present' title='Present'>P</span></td>"
                    } else if (element['attendance_status'] == 'absence') {
                        window.totalAbsence++;
                        dataRow +=
                            "<td><span class='absence' title='Absence'>A</span></td>"
                    } else if (element['attendance_status'] == 'leave') {
                        window.totalLeave++;
                        dataRow +=
                            "<td><span class='leave' title='Leave'>L</span></td>"
                    } else {
                        dataRow += '<td></td>'
                    }
                });

                // <span class="bolt">{{ $workday . ' Days' }}</span>
                dataRow += '<td><span class="bolt">' + window.totalDay +
                    ' Days</span></td>'
                dataRow += '<td><span class="bolt">' + window.totalPresent + '</span></td>'
                dataRow += '<td><span class="bolt">' + window.totalAbsence + '</span></td>'
                let hours = Math.floor(response['dataFormat'][key][0]['late_duration'] /
                    60);
                let min = response['dataFormat'][key][0]['late_duration'] - (hours * 60);
                let lateDuration = hours + ' H ' + min + ' M'
                dataRow += '<td><span class="bolt">' + lateDuration + '</span></td>'
                dataRow += '<td><span class="bolt">' + response['dataFormat'][key][0]['leave_balance'] +
                    '</span></td>'
                dataRow += '<td><span class="bolt">' + (response['dataFormat'][key][0][
                    'overtime_duration'
                ] / 60) + ' H' + '</span></td>'

                tr.innerHTML = dataRow; // isi tabelnya


                $('#tbody').append(tr);
            });
            //head Table
            let trHead = document.createElement("tr")
            window.total_col = 0;
            headRow =
                '<th>#</th>' +
                '<th>Tahun</th>' +
                '<th>Bulan</th>'

            response['monthToDate'].forEach(element => {
                window.total_col++
            });
            window.total_col += 8;
            headRow += '<th colspan="' + window.total_col + '" class="totalCol"></th>'



            trHead.innerHTML = headRow;
            $('#thead').append(trHead);
            let yearMonth = response['month'].split('-');
            let year = yearMonth[0]
            trHead = document.createElement("tr")
            trHead.classList.add('second-tr');
            headRow = ''
            headRow += '<th>#</th>'
            headRow += '<th>' + year + '</th>'
            headRow += '<th>' + response['monthName'] + '</th>'
            headRow += '<th></th>'
            headRow += '<th></th>'

            response['monthToDate'].forEach(element => {
                headRow += '<th>' + element['day_name'] + '</th>'
            });

            headRow += '<th>WORKDAY</th>'
            headRow += '<th>ATTEND</th>'
            headRow += '<th>ABSENCE</th>'
            headRow += '<th>LATETIME</th>'
            headRow += '<th>LEAVE BALANCE</th>'
            headRow += '<th>OVERTIME</th>'

            trHead.innerHTML = headRow;
            $('#thead').append(trHead);

            trHead = document.createElement("tr")
            headRow = ''
            headRow += '<th>NO</th>'
            headRow += '<th>EMPLOYEE NAME</th>'
            headRow += '<th>ENTITY</th>'
            headRow += '<th>SBU</th>'
            headRow += '<th>JOB POSITION</th>'

            response['monthToDate'].forEach(element => {
                headRow += '<th>' + element['day'] + '</th>'
            });

            for (let index = 0; index < 6; index++) {
                headRow += '<th></th>'
            }

            trHead.innerHTML = headRow;

            $('#thead').append(trHead);
        }
    </script>
@endsection
