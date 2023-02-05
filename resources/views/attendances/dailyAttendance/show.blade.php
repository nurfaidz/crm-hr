@extends('partials.template')
@section('main')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-body">
                <section class="horizontal-wizard">
                    <div class="bs-stepper horizontal-wizard-example">
                        <div class="bs-stepper-content">
                            <h3 class="m-0">Details Attendance</h3>
                        </div>
                        <div class="bs-stepper-header m-auto">
                            @if ($attendance->image_manual_attendance)
                                <div class="step">
                                    <button type="button"
                                        class="step-trigger first-st done-step-trigger medical-step-trigger">
                                        <span class="bs-stepper-box first-bsb done-bs-stepper-box">
                                            <span class="bs-stepper-number first-bsn done-bs-stepper-number">
                                                <i class="fa-solid fa-check"></i>
                                            </span>
                                        </span>
                                        <span class="bs-stepper-label">
                                            <span class="bs-stepper-title first-bst done-bs-stepper-title">New
                                                Application</span>
                                        </span>
                                    </button>
                                </div>
                                <span class="first-middle-line active-middle-line"></span>
                                <div class="step">
                                    <button type="button"
                                        class="step-trigger first-st {{ $approve1 }}-step-trigger medical-step-trigger">
                                        <span class="bs-stepper-box first-bsb {{ $approve1 }}-bs-stepper-box">
                                            <span class="bs-stepper-number first-bsn {{ $approve1 }}-bs-stepper-number">
                                                @if ($approve1 == 'done')
                                                    <i class="fa-solid fa-check"></i>
                                                @endif
                                                @if ($approve1 == 'danger')
                                                    <i class="fa-solid fa-close"></i>
                                                @endif
                                                @if ($approve1 == 'active' || $approve1 == 'disable')
                                                    2
                                                @endif
                                            </span>
                                        </span>
                                        <span class="bs-stepper-label">
                                            <span
                                                class="bs-stepper-title first-bst {{ $approve1 }}-bs-stepper-title">Higher-Up
                                                Approval</span>
                                        </span>
                                    </button>
                                </div>
                            @endif
                        </div>

                        <div class="bs-stepper-content">
                            <div class="mb-2">
                                <p class="font-weight-bolder">Attendance Information</p>
                                <p class="mb-50">Date : {{ $attendance->date }}</p>
                                <p class="mb-50">Day : {{ date('l', strtotime($attendance->date)) }}</p>
                                <p class="mb-50">Entity : {{ $branch->branch_name }}</p>
                                <p class="mb-50">SBU : {{ $department->department_name }}</p>
                                <p>Job Position : {{ $jobPosition->job_position }}</p>
                            </div>
                            <div class="card leave-details-statistics">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-3 text-center" style="border-right: 2px solid #D9D9D9;">
                                            <div class="text-left d-inline-block">
                                                <p>Check In Time</p>
                                                <h4 class="text-primary">
                                                    {{ $attendance->check_in ? substr($attendance->check_in, 11, 5) : '-' }}
                                                    WIB</h4>
                                            </div>
                                        </div>
                                        <div class="col-3 text-center" style="border-right: 2px solid #D9D9D9;">
                                            <div class="text-left d-inline-block">
                                                <p>Check Out Time</p>
                                                <h4 class="text-primary">
                                                    {{ $attendance->check_out ? substr($attendance->check_out, 11, 5) : '-' }}
                                                    WIB</h4>
                                            </div>
                                        </div>
                                        <div class="col-3 text-center" style="border-right: 2px solid #D9D9D9;">
                                            <div class="text-left d-inline-block">
                                                <p>Late Time</p>
                                                <h4 class="text-primary">
                                                    {{ $attendance->late_duration > 0 ? floor($attendance->late_duration / 60) . ' H ' . $attendance->late_duration % 60 . ' M' : '-' }}
                                                </h4>
                                            </div>
                                        </div>
                                        <div class="col-3 text-center">
                                            <div class="text-left d-inline-block">
                                                <p>Work Time</p>
                                                <h4 class="text-primary">
                                                    {{ $attendance->working_hour !== null ? (int) date('h', floor(strtotime($attendance->working_hour))) . ' H ' . (int) date('i', strtotime($attendance->working_hour)) . ' M' : '-' }}
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if (!$attendance->image_manual_attendance)
                                <div class="mt-2">
                                    <p class="font-weight-bolder">Check In :
                                        {{ $attendance->image ? 'Mobile App' : 'Web' }}
                                    </p>
                                    <p>Evidence : {{ $attendance->image ? '' : '-' }}</p>
                                    @if ($attendance->image)
                                        <div class="detail-files-container">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="insert-drive-file">
                                                        <svg width="20" height="18" viewBox="0 0 20 18"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M12.12 2L13.95 4H18V16H2V4H6.05L7.88 2H12.12ZM13 0H7L5.17 2H2C0.9 2 0 2.9 0 4V16C0 17.1 0.9 18 2 18H18C19.1 18 20 17.1 20 16V4C20 2.9 19.1 2 18 2H14.83L13 0ZM10 7C11.65 7 13 8.35 13 10C13 11.65 11.65 13 10 13C8.35 13 7 11.65 7 10C7 8.35 8.35 7 10 7ZM10 5C7.24 5 5 7.24 5 10C5 12.76 7.24 15 10 15C12.76 15 15 12.76 15 10C15 7.24 12.76 5 10 5Z"
                                                                fill="#4B4B4B" />
                                                        </svg>
                                                    </span>
                                                </div>
                                                <input type="text" name="detail-files" id="detail-files"
                                                    class="form-control border-left-0 border-right-0 detail-files"
                                                    placeholder="{{ $attendance->image }}" disabled="disabled">
                                                <div class="input-group-append">
                                                    <a href="{{ asset('uploads/' . $attendance->image) }}"
                                                        class="input-group-text" download>
                                                        <img src="{{ url('./img/icons/file-download.svg') }}"
                                                            alt="Insert Drive File"></a>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                            <div id="leave-details">
                                <div class="row">
                                    @if ($attendance->note)
                                        <div class="col-6" id="leave-details-notes">
                                            <p>Notes</p>
                                            <textarea name="notes" id="notes" cols="30" rows="5" class="form-control bg-white" placeholder=""
                                                disabled>{{ $attendance->note }}</textarea>
                                        </div>
                                        <div class="col-6" id="leave-details-notes">
                                        </div>
                                    @endif
                                    @if ($attendance->note_check_in)
                                        <div class="col-6" id="leave-details-notes">
                                            <p>Notes Check In</p>
                                            <textarea name="notes" id="notes" cols="30" rows="5" class="form-control bg-white" placeholder=""
                                                disabled>{{ $attendance->note_check_in }}</textarea>
                                        </div>
                                        <div class="col-6" id="leave-details-notes">
                                        </div>
                                    @endif
                                    @if ($attendance->note_check_out)
                                        <div class="col-6" id="leave-details-notes">
                                            <p>Notes Check Out</p>
                                            <textarea name="notes" id="notes" cols="30" rows="5" class="form-control bg-white" placeholder=""
                                                disabled>{{ $attendance->note_check_out }}</textarea>
                                        </div>
                                        <div class="col-6" id="leave-details-notes">
                                        </div>
                                    @endif
                                    <div class="custom-input-image-container"></div>
                                    @if ($attendance->image_manual_attendance)
                                        <div class="col col-6" id="leave-details-files">
                                            <p>Files</p>
                                            <div class="detail-files-container">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text bg-white"
                                                            id="insert-drive-file"><img
                                                                src="{{ url('./img/icons/insert-drive-file.svg') }}"
                                                                alt="Insert Drive File"></span>
                                                    </div>
                                                    @php
                                                        $image = explode('/', $attendance->image_manual_attendance);
                                                    @endphp
                                                    <input type="text" name="detail-files" id="detail-files"
                                                        class="form-control bg-white border-left-0 border-right-0 detail-files"
                                                        placeholder="{{ $image[5] }}" disabled="disabled">
                                                    <div class="input-group-append">
                                                        <a href="{{ $attendance->image_manual_attendance }}"
                                                            class="input-group-text bg-white" download><img
                                                                src="{{ url('./img/icons/file-download.svg') }}"
                                                                alt="Insert Drive File"></a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="text-right pr-1 leave-details-buttons">
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection

@section('page_js')
    <script src="{{ url('app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
    <script src="{{ url('app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
@endsection

@section('page_script')
    {{-- <script>
        $(document).on('click', '#evidence-subsitute', function() {
            $('#evidence').click();
        });

        function addFile(evidence) {
            $('.custom-input-image-container').html('');
            let files = document.getElementById('evidence').files;

            for (let i = 0; i < files.length; i++) {
                $('.custom-input-image-container').append(
                    `<div class="form-group"><div class="input-group"><div class="input-group-prepend"><span class="input-group-text bg-white"><img src="{{ url('img/icons/file-copy.svg') }}" alt="file-copy"></span></div><input type="text" class="form-control bg-white custom-input-image-name" placeholder="${files[i].name}" disabled="disabled"><div class="input-group-append"><span class="input-group-text bg-white custom-input-image-size">${(files[i].size / Math.pow(1024, 1)).toString().split('.')[0]} Kb</span></div></div></div>`
                );
            }
        }

        $(document).ready(function() {
            function findBaseName(url) {
                return url.substring(url.lastIndexOf('/') + 1);
            }

            let images = {!! json_encode(a, JSON_HEX_TAG) !!};
            let isMultiple = images.includes('|');

            if (isMultiple) {
                let files = images.split('|');
                let array = [];

                files.forEach(element => {
                    array.push(findBaseName(element));
                });

                for (let i = 0; i < array.length; i++) {
                    $('#leave-details-files .detail-files-container').append(
                        '<div class="input-group"><div class="input-group-prepend"><span class="input-group-text bg-white" id="insert-drive-file"><img src="{{ url('./img/icons/insert-drive-file.svg') }}" alt="Insert Drive File"></span></div><input type="text" name="detail-files" id="detail-files" class="form-control bg-white border-left-0 border-right-0 detail-files" disabled="disabled"><div class="input-group-append"><a href="{{ url('medical-reimbursement/download') }}/' +
                        array[i] +
                        '" class="input-group-text bg-white"><img src="{{ url('./img/icons/file-download.svg') }}" alt="Insert Drive File"></a></div></div>'
                    );
                }

                $('#leave-details-files .detail-files').each(function(j) {
                    $(this).attr('placeholder', array[j]);
                });
            } else {
                let file = findBaseName(images);

                if (file != '') {
                    $('#leave-details-files .detail-files-container').append(
                        '<div class="input-group"><div class="input-group-prepend"><span class="input-group-text bg-white" id="insert-drive-file"><img src="{{ url('./img/icons/insert-drive-file.svg') }}" alt="Insert Drive File"></span></div><input type="text" name="detail-files" id="detail-files" class="form-control bg-white border-left-0 border-right-0 detail-files" disabled="disabled"><div class="input-group-append"><a href="{{ url('medical-reimbursement/download') }}/' +
                        file +
                        '" class="input-group-text bg-white"><img src="{{ url('./img/icons/file-download.svg') }}" alt="Insert Drive File"></a></div></div>'
                    );

                    $('#leave-details-files .detail-files').attr('placeholder', file);
                } else {
                    $('#leave-details-files .detail-files-container').append(
                        '<div class="input-group"><div class="input-group-prepend"><span class="input-group-text bg-white" id="insert-drive-file"><img src="{{ url('./img/icons/insert-drive-file.svg') }}" alt="Insert Drive File"></span></div><input type="text" placeholder="No file available" name="detail-files" id="detail-files" class="form-control bg-white border-left-0 border-right-0 detail-files" disabled><div class="input-group-append"><button class="input-group-text bg-white" disabled><img src="{{ url('./img/icons/file-download.svg') }}" alt="Insert Drive File"></button></div></div>'
                    );
                }
            }
        });
    </script> --}}
@endsection
