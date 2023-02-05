@extends('partials.template')
@section('main')
    <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-body">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Detail Employee</h4>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile"
                                    aria-controls="profile" role="tab" aria-selected="true">Profile</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="education-tab" data-toggle="tab" href="#education"
                                    aria-controls="education" role="tab" aria-selected="false">Education &
                                    Experience</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="emergency-tab" data-toggle="tab" href="#emergency"
                                    aria-controls="emergency" role="tab" aria-selected="false">Emergency Contact</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="profiles-tab" data-toggle="tab" href="#profiles"
                                    aria-controls="profiles" role="tab" aria-selected="false">Profile All Employees</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="skill-tab" data-toggle="tab" href="#skill" aria-controls="skill"
                                    role="tab" aria-selected="false">Skills & Licenses </a>
                            </li>
                        </ul>
                        <hr>
                        <div class="tab-content">
                            {{-- TAB Profile --}}
                            <div class="tab-pane active" id="profile" aria-labelledby="profile-tab" role="tabpanel">
                                <div class="row mt-2">
                                    <div class="col-12 col-md-6">
                                        <!-- Basic Information -->
                                        <div class="card" id="basic-information">
                                            <div class="card-body border rounded" style="rounded">
                                                <div
                                                    class="d-flex justify-content-between align-items-center border-bottom pb-1">
                                                    <h5 class="card-title m-0">Basic Information</h5>
                                                    <button type="submit" title="Edit" data-toggle="modal"
                                                        data-target="#modal-profile" id="edit-profile"
                                                        data-id="{{ $employee->employee_id }}"
                                                        class="edit btn btn-icon float-right" id="edit"><svg
                                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                            class="feather feather-edit-2">
                                                            <path
                                                                d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
                                                            </path>
                                                        </svg></button>
                                                </div>

                                                <div class="d-flex flex-column align-items-center mt-2">
                                                    <img class="rounded-circle" src="{{ $profilePic }}" alt="avatar"
                                                        height="120" width="120">
                                                    <p class="mt-1 font-weight-bolder">
                                                        {{ $employee->first_name . ' ' . $employee->last_name }}</p>

                                                    <div class="d-flex">
                                                        <a target="_blank" type="button" class="btn btn-light border"
                                                            href="https://wa.me/{{ $employee->phone }}">Whatsapp</a>
                                                        <a target="_blank" type="button" class="btn btn-light border ml-1"
                                                            href="mailto:{{ $user->email }}">Email</a>
                                                    </div>

                                                    <div class="col-12 mt-3">
                                                        <div class="row">
                                                            <!-- Gender -->
                                                            <div class="col-4">
                                                                <p class="font-weight-bolder">
                                                                    Gender
                                                                </p>
                                                            </div>
                                                            <div class="col-8">
                                                                <p>
                                                                    {{ $employee->gender }}
                                                                </p>
                                                            </div>
                                                            <!-- End Gender -->

                                                            <div class="col-12 mt-2">
                                                                <p class="font-weight-bolder">Social Media : </p>
                                                            </div>

                                                            <div class="col-12 px-2">
                                                                <div class="row">
                                                                    <!-- Instagram -->
                                                                    <div class="col-4">
                                                                        <p class="font-weight-bolder">
                                                                            Instagram
                                                                        </p>
                                                                    </div>
                                                                    <div class="col-8">
                                                                        @if (isset($employee->instagram_link))
                                                                            <a target="#blank"
                                                                                href="https://www.instagram.com/{{ $employee->instagram_link }}">{{ $employee->instagram_link }}</a>
                                                                        @else
                                                                            <p>-</p>
                                                                        @endif

                                                                    </div>
                                                                    <!-- End Instagram -->

                                                                    <!-- LinkedIn -->
                                                                    <div class="col-4">
                                                                        <p class="font-weight-bolder">
                                                                            LinkedIn
                                                                        </p>
                                                                    </div>
                                                                    <div class="col-8">
                                                                        @if (isset($employee->linkedin_link))
                                                                            <a target="#blank"
                                                                                href="https://linkedin.com/{{ $employee->linkedin_link }}">{{ $employee->linkedin_link }}</a>
                                                                        @else
                                                                            <p>-</p>
                                                                        @endif
                                                                    </div>
                                                                    <!-- End LinkedIn -->

                                                                    <!-- Facebook -->
                                                                    <div class="col-4">
                                                                        <p class="font-weight-bolder">
                                                                            Facebook
                                                                        </p>
                                                                    </div>
                                                                    <div class="col-8">
                                                                        @if (isset($employee->facebook_link))
                                                                            <a target="#blank"
                                                                                href="https://www.facebook.com/{{ $employee->facebook_link }}">{{ $employee->facebook_link }}</a>
                                                                        @else
                                                                            <p>-</p>
                                                                        @endif
                                                                    </div>
                                                                    <!-- End Facebook -->
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Basic Information -->

                                        <!-- Vaccine Certificate -->
                                        <div class="card" id="card-vaccines">
                                            <div class="card-body border rounded pb-0 mb-0" style="rounded">
                                                <div
                                                    class="d-flex justify-content-between align-items-center border-bottom pb-1 mb-1">
                                                    <h5 class="card-title m-0">Vaccine Certificate</h5>
                                                    <button type="submit" title="Edit" data-toggle="modal"
                                                        data-target="#modal-vaccine" data-id=""
                                                        class="edit btn btn-icon float-right" id="edit-vaccine"><svg
                                                            xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" class="feather feather-edit-2">
                                                            <path
                                                                d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
                                                            </path>
                                                        </svg></button>
                                                </div>
                                                <div class="row">
                                                    @if ($vaccine)
                                                        <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                                                            <div class="card border">
                                                                <button type="button"
                                                                    class="btn btn-outline-none m-0 p-0"
                                                                    data-toggle="modal" data-target="#image-1"><img
                                                                        class="card-img-top img-fluid"
                                                                        src="{{ $vaccine->vaccine_1 ? asset('uploads/' . $vaccine->vaccine_1) : asset('img/default.png') }}"
                                                                        alt="Vaccine 1"></button>
                                                                <div class="card-body p-1">
                                                                    <p class="card-text">Vaccine 1</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                                                            <div class="card border">
                                                                <button type="button"
                                                                    class="btn btn-outline-none m-0 p-0"
                                                                    data-toggle="modal" data-target="#image-2"><img
                                                                        class="card-img-top img-fluid"
                                                                        src="{{ $vaccine->vaccine_2 ? asset('uploads/' . $vaccine->vaccine_2) : asset('img/default.png') }}"
                                                                        alt="Vaccine 2"></button>
                                                                <div class="card-body p-1">
                                                                    <p class="card-text">Vaccine 2</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                                                            <div class="card border">
                                                                <button type="button"
                                                                    class="btn btn-outline-none m-0 p-0"
                                                                    data-toggle="modal" data-target="#image-3"><img
                                                                        class="card-img-top img-fluid"
                                                                        src="{{ $vaccine->vaccine_3 ? asset('uploads/' . $vaccine->vaccine_3) : asset('img/default.png') }}"
                                                                        alt="Vaccine 3"></button>
                                                                <div class="card-body p-1">
                                                                    <p class="card-text">Vaccine 3</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal" id="image-1" tabindex="-1" role="dialog"
                                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-xl">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title" id="modal_title">Vaccine
                                                                            1</h4>
                                                                        <button type="button" class="close m-0"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <img class="card-img-top img-fluid"
                                                                            src="{{ $vaccine->vaccine_1 ? asset('uploads/' . $vaccine->vaccine_1) : asset('img/default.png') }}"
                                                                            alt="Vaccine 1">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal" id="image-2" tabindex="-1" role="dialog"
                                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-xl">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title" id="modal_title">Vaccine
                                                                            2</h4>
                                                                        <button type="button" class="close m-0"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <img class="card-img-top img-fluid"
                                                                            src="{{ $vaccine->vaccine_2 ? asset('uploads/' . $vaccine->vaccine_2) : asset('img/default.png') }}"
                                                                            alt="Vaccine 2">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal" id="image-3" tabindex="-1" role="dialog"
                                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-xl">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title" id="modal_title">Vaccine
                                                                            3</h4>
                                                                        <button type="button" class="close m-0"
                                                                            data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <img class="card-img-top img-fluid"
                                                                            src="{{ $vaccine->vaccine_3 ? asset('uploads/' . $vaccine->vaccine_3) : asset('img/default.png') }}"
                                                                            alt="Vaccine 3">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <!-- End Vaccine Certificate -->
                                        </div>
                                        <!-- End Vaccine Certificate -->
                                    </div>

                                    {{-- TAB Additional Information --}}
                                    <div class="col-12 col-md-6">
                                        <div class="card" id="card-additional">
                                            <div class="card-body border rounded" style="rounded">
                                                <div
                                                    class="d-flex justify-content-between align-items-center border-bottom pb-1">
                                                    <h5 class="card-title m-0">Additional Information</h5>
                                                    <button type="button" title="Edit" data-toggle="modal"
                                                        data-target="#modal-additional-information"
                                                        class="edit btn btn-icon float-right"
                                                        id="edit-additional-information"><svg
                                                            xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" class="feather feather-edit-2">
                                                            <path
                                                                d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
                                                            </path>
                                                        </svg></button>
                                                </div>
                                                <div class="d-flex flex-column align-items-center mt-2">
                                                    <div class="col-12 mt-1">
                                                        <div class="row">
                                                            <!-- Religion -->
                                                            <div class="col-5">
                                                                <p class="font-weight-bolder">
                                                                    Religion
                                                                </p>
                                                            </div>
                                                            <div class="col-7">
                                                                <p>
                                                                    {{ $profile->religion }}
                                                                </p>
                                                            </div>
                                                            <!-- Religion -->

                                                            <!-- Birth Of Place -->
                                                            <div class="col-5">
                                                                <p class="font-weight-bolder">
                                                                    Birth Of Place
                                                                </p>
                                                            </div>
                                                            <div class="col-7">
                                                                <p>
                                                                    {{ $employee->place_of_birth }}
                                                                </p>
                                                            </div>
                                                            <!-- End Birth Of Place -->

                                                            <!-- Date of Birth -->
                                                            <div class="col-5">
                                                                <p class="font-weight-bolder">
                                                                    Date of Birth
                                                                </p>
                                                            </div>
                                                            <div class="col-7">
                                                                <p>
                                                                    {{ date('j F Y', strtotime($employee->date_of_birth)) }}
                                                                </p>
                                                            </div>
                                                            <!-- End Date of Birth -->

                                                            <!-- Blood Type -->
                                                            <div class="col-5">
                                                                <p class="font-weight-bolder">
                                                                    Blood Type
                                                                </p>
                                                            </div>
                                                            <div class="col-7">
                                                                <p>
                                                                    {{ $employee->blood_type }}
                                                                </p>
                                                            </div>
                                                            <!-- End Blood Type -->

                                                            <!-- NIK -->
                                                            <div class="col-5">
                                                                <p class="font-weight-bolder">
                                                                    NIK
                                                                </p>
                                                            </div>
                                                            <div class="col-7">
                                                                <p>
                                                                    {{ $employee->nik }}
                                                                </p>
                                                            </div>
                                                            <!-- End NIK -->

                                                            <!-- Passport -->
                                                            <div class="col-5">
                                                                <p class="font-weight-bolder">
                                                                    No Passport
                                                                </p>
                                                            </div>
                                                            <div class="col-7">
                                                                <p>
                                                                    {{ $employee->passport }}
                                                                </p>
                                                            </div>
                                                            <!-- End Passport -->

                                                            <!-- Address -->
                                                            <div class="col-5">
                                                                <p class="font-weight-bolder">
                                                                    Address
                                                                </p>
                                                            </div>
                                                            <div class="col-7">
                                                                <p>
                                                                    {{ $employee->address }}
                                                                </p>
                                                            </div>
                                                            <!-- End Passport -->

                                                            <!-- Nationality -->
                                                            <div class="col-5">
                                                                <p class="font-weight-bolder">
                                                                    Nationality
                                                                </p>
                                                            </div>
                                                            <div class="col-7">
                                                                <p>
                                                                    {{ $employee->nationality }}
                                                                </p>
                                                            </div>
                                                            <!-- End Nationality -->

                                                            <!-- Marital Status -->
                                                            <div class="col-5">
                                                                <p class="font-weight-bolder">
                                                                    Marital Status
                                                                </p>
                                                            </div>
                                                            <div class="col-7">
                                                                <p>
                                                                    {{ $profile->marital_status }}
                                                                </p>
                                                            </div>
                                                            <!-- End Marital Status -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card" id="employee-information-card">
                                            <div class="card-body border rounded" style="rounded">
                                                <!-- Bagian Atas -->
                                                <div
                                                    class="d-flex justify-content-between align-items-center border-bottom pb-1">
                                                    <h5 class="card-title m-0">Employee Information</h5>
                                                    <button type="button" title="Edit" data-toggle="modal"
                                                        data-target="#modal-employee-information" id="edit-information"
                                                        data-id="{{ $employee->id }}"
                                                        class="edit btn btn-icon float-right"><svg
                                                            xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" class="feather feather-edit-2">
                                                            <path
                                                                d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z">
                                                            </path>
                                                        </svg></button>

                                                </div>

                                                <!-- Bagian Content -->
                                                <div class="d-flex flex-column align-items-center mt-2">
                                                    <div class="col-12 mt-1">
                                                        <div class="row">
                                                            <!-- Holdings -->
                                                            <div class="col-5">
                                                                <p class="font-weight-bolder">
                                                                    Holdings
                                                                </p>
                                                            </div>
                                                            <div class="col-7">
                                                                <p>
                                                                    {{ $profile->company_name }}
                                                                </p>
                                                            </div>
                                                            <!-- End Holdings -->

                                                            <!-- Employee ID -->
                                                            <div class="col-5">
                                                                <p class="font-weight-bolder">
                                                                    Employee ID
                                                                </p>
                                                            </div>
                                                            <div class="col-7">
                                                                <p>
                                                                    {{ $profile->nip }}
                                                                </p>
                                                            </div>
                                                            <!-- End Employee ID -->

                                                            <!-- Job Level-->
                                                            <div class="col-5">
                                                                <p class="font-weight-bolder">
                                                                    Job Level
                                                                </p>
                                                            </div>
                                                            <div class="col-7">
                                                                <p>
                                                                    {{ $profile->job_class }}
                                                                </p>
                                                            </div>
                                                            <!-- End Job Level -->

                                                            <!-- Job Position -->
                                                            <div class="col-5">
                                                                <p class="font-weight-bolder">
                                                                    Job Position
                                                                </p>
                                                            </div>
                                                            <div class="col-7">
                                                                <p>
                                                                    {{ $profile->job_position }}
                                                                </p>
                                                            </div>
                                                            <!-- End Job Position -->

                                                            <!-- Employeement Status -->
                                                            <div class="col-5">
                                                                <p class="font-weight-bolder">
                                                                    Employeement Status
                                                                </p>
                                                            </div>
                                                            <div class="col-7">
                                                                <p>
                                                                    {{ $profile->employment_status }}
                                                                </p>
                                                            </div>
                                                            <!-- End Employeement Status -->

                                                            <!-- Entity -->
                                                            <div class="col-5">
                                                                <p class="font-weight-bolder">
                                                                    Entity
                                                                </p>
                                                            </div>
                                                            <div class="col-7">
                                                                <p>
                                                                    {{ $profile->branch_name }}
                                                                </p>
                                                            </div>
                                                            <!-- End Entity -->

                                                            <!-- SBU -->
                                                            <div class="col-5">
                                                                <p class="font-weight-bolder">
                                                                    SBU
                                                                </p>
                                                            </div>
                                                            <div class="col-7">
                                                                <p>
                                                                    {{ $profile->department_name }}
                                                                </p>
                                                            </div>
                                                            <!-- End Entity -->


                                                            <!-- Join Date -->
                                                            <div class="col-5">
                                                                <p class="font-weight-bolder">
                                                                    Join Date
                                                                </p>
                                                            </div>
                                                            <div class="col-7">
                                                                <p>
                                                                    {{ date('j F Y', strtotime($profile->date_of_joining)) }}
                                                                </p>
                                                            </div>
                                                            <!-- End Join Date-->

                                                            <!-- App Status / Role -->
                                                            <div class="col-5">
                                                                <p class="font-weight-bolder">
                                                                    App Status / Role
                                                                </p>
                                                            </div>
                                                            <div class="col-7">
                                                                <p>
                                                                    {{ $user->getRoleNames()[0] }}
                                                                </p>
                                                            </div>
                                                            <!-- End App Status / Role-->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- TAB Education & Experience --}}
                            <div class="tab-pane" id="education" aria-labelledby="education-tab" role="tabpanel">
                                <section id="ajax-datatable">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="row mb-2">
                                                <div class="col-md-9 col-12 my-auto">
                                                    <h3 class="m-0">Education</h3>
                                                </div>
                                                <div class="col-md-3 col-12 text-right my-auto">
                                                    <div class="btn-group">
                                                        <button type="button" data-toggle="modal"
                                                            data-target="#modal-education" id="add-education"
                                                            class="btn btn-primary">
                                                            Add Education</button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card rounded-lg">
                                                <div class="card-datatable table-rounded table-responsive">
                                                    <table class="table table-borderless" id="table-edu">
                                                        <thead>
                                                            <tr>
                                                                <th>No.</th>
                                                                <th>Institution</th>
                                                                <th>Degree</th>
                                                                <th>Majors</th>
                                                                <th>Entery Level</th>
                                                                <th>Graduation Year</th>
                                                                <th>GPA</th>
                                                                <th class="text-center">Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if (count($educations) < 1)
                                                                <tr>
                                                                    <td colspan="8" class="text-center">No data
                                                                        available in table</td>
                                                                </tr>
                                                            @endif
                                                            @foreach ($educations as $education)
                                                                <tr>
                                                                    <td>{{ $loop->iteration }}</td>
                                                                    <td>{{ $education->institution }}</td>
                                                                    <td>{{ $education->degree }}</td>
                                                                    <td>{{ $education->major }}</td>
                                                                    <td>{{ $education->entry_level }}</td>
                                                                    <td>{{ $education->graduation_year }}</td>
                                                                    <td>{{ $education->gpa }}</td>
                                                                    <td class="text-center">
                                                                        <button type="button" title="Edit"
                                                                            data-toggle="modal"
                                                                            data-target="#modal-education"
                                                                            data-id="{{ $education->id }}"
                                                                            class="edit btn btn-icon btn-success "
                                                                            id="edit"><svg
                                                                                xmlns="http://www.w3.org/2000/svg"
                                                                                width="24" height="24"
                                                                                viewBox="0 0 24 24" fill="none"
                                                                                stroke="currentColor" stroke-width="2"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                class="feather feather-edit">
                                                                                <path
                                                                                    d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7">
                                                                                </path>
                                                                                <path
                                                                                    d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z">
                                                                                </path>
                                                                            </svg></button>
                                                                        <form id="form_delete_education"
                                                                            style="display:inline" class=""
                                                                            action="" method="post" title="Delete">
                                                                            @csrf
                                                                            <button type="submit"
                                                                                style="border:none; background:transparent"
                                                                                class="btn btn-icon btn-danger "
                                                                                onclick="sweetConfirm({{ $education->id }})">
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                    width="24" height="24"
                                                                                    viewBox="0 0 24 24" fill="none"
                                                                                    stroke="currentColor" stroke-width="2"
                                                                                    stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    class="feather feather-trash-2">
                                                                                    <polyline points="3 6 5 6 21 6">
                                                                                    </polyline>
                                                                                    <path
                                                                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                                                    </path>
                                                                                    <line x1="10" y1="11"
                                                                                        x2="10" y2="17">
                                                                                    </line>
                                                                                    <line x1="14" y1="11"
                                                                                        x2="14" y2="17">
                                                                                    </line>
                                                                                </svg>
                                                                            </button>
                                                                        </form>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                                <section id="ajax-datatable">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="row mb-2">
                                                <div class="col-md-9 col-12 my-auto">
                                                    <h3 class="m-0">Experience</h3>
                                                </div>
                                                <div class="col-md-3 col-12 text-right my-auto">
                                                    <div class="btn-group">
                                                        <button type="button" data-toggle="modal"
                                                            data-target="#modal-exp" id="add-experience"
                                                            class="btn btn-primary">
                                                            Add Experience</button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card">
                                                <div class="card-datatable table-rounded table-responsive">
                                                    <table class="table table-borderless" id="table-exp">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Corporate</th>
                                                                <th>Position</th>
                                                                <th>Years of Experience</th>
                                                                <th>Job Description</th>
                                                                <th class="text-center">Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if (count($experiences) < 1)
                                                                <tr>
                                                                    <td colspan="6" class="text-center">No data
                                                                        available in table</td>
                                                                </tr>
                                                            @endif
                                                            @foreach ($experiences as $experience)
                                                                <tr>
                                                                    <td>{{ $loop->iteration }}</td>
                                                                    <td>{{ $experience->corporate }}</td>
                                                                    <td>{{ $experience->position }}</td>
                                                                    <td>{{ $experience->years }} Tahun</td>
                                                                    <td>{{ $experience->description }}</td>
                                                                    <td class="text-center">
                                                                        <button type="button" title="Edit"
                                                                            data-toggle="modal" data-target="#modal-exp"
                                                                            data-id="{{ $experience->id }}"
                                                                            class="edit btn btn-icon btn-success "
                                                                            id="edit-experience"><svg
                                                                                xmlns="http://www.w3.org/2000/svg"
                                                                                width="24" height="24"
                                                                                viewBox="0 0 24 24" fill="none"
                                                                                stroke="currentColor" stroke-width="2"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                class="feather feather-edit">
                                                                                <path
                                                                                    d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7">
                                                                                </path>
                                                                                <path
                                                                                    d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z">
                                                                                </path>
                                                                            </svg></button>
                                                                        <form id="form_delete_experience"
                                                                            style="display:inline" class=""
                                                                            action="" method="post" title="Delete">
                                                                            @csrf
                                                                            <button type="submit"
                                                                                style="border:none; background:transparent"
                                                                                class="btn btn-icon btn-danger "
                                                                                onclick="sweetExperienceConfirm({{ $experience->id }})">
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                    width="24" height="24"
                                                                                    viewBox="0 0 24 24" fill="none"
                                                                                    stroke="currentColor" stroke-width="2"
                                                                                    stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    class="feather feather-trash-2">
                                                                                    <polyline points="3 6 5 6 21 6">
                                                                                    </polyline>
                                                                                    <path
                                                                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                                                    </path>
                                                                                    <line x1="10" y1="11"
                                                                                        x2="10" y2="17">
                                                                                    </line>
                                                                                    <line x1="14" y1="11"
                                                                                        x2="14" y2="17">
                                                                                    </line>
                                                                                </svg>
                                                                            </button>
                                                                        </form>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>

                            {{-- TAB Emergency Contact --}}
                            <div class="tab-pane" id="emergency" aria-labelledby="emergency-tab" role="tabpanel">
                                <section id="ajax-datatable">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="row mb-2">
                                                <div class="col-md-9 col-12 my-auto">
                                                    <h3 class="m-0">Emergency Contact</h3>
                                                </div>
                                                <div class="col-md-3 col-12 text-right my-auto">
                                                    <div class="btn-group">
                                                        <button type="button" data-toggle="modal"
                                                            data-target="#modal-contact" id="add-contact"
                                                            class="btn btn-primary">
                                                            Add Contact</button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card">
                                                <div class="card-datatable table-rounded table-responsive">
                                                    <table class="table" id="table-contact">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Name</th>
                                                                <th>Relationship</th>
                                                                <th>Mobile Phone</th>
                                                                <th class="text-center">Actions</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if (count($emergencyContacts) < 1)
                                                                <tr>
                                                                    <td colspan="5" class="text-center">No data
                                                                        available in table</td>
                                                                </tr>
                                                            @endif
                                                            @foreach ($emergencyContacts as $emergencyContact)
                                                                <tr>
                                                                    <td>{{ $loop->iteration }}</td>
                                                                    <td>{{ $emergencyContact->name }}</td>
                                                                    <td>{{ $emergencyContact->connection }}</td>
                                                                    <td>{{ $emergencyContact->contact }}</td>
                                                                    <td class="text-center">
                                                                        <button type="button" title="Edit"
                                                                            data-toggle="modal"
                                                                            data-target="#modal-contact"
                                                                            data-id="{{ $emergencyContact->id }}"
                                                                            class="edit btn btn-icon btn-success "
                                                                            id="edit-contact"><svg
                                                                                xmlns="http://www.w3.org/2000/svg"
                                                                                width="24" height="24"
                                                                                viewBox="0 0 24 24" fill="none"
                                                                                stroke="currentColor" stroke-width="2"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                class="feather feather-edit">
                                                                                <path
                                                                                    d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7">
                                                                                </path>
                                                                                <path
                                                                                    d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z">
                                                                                </path>
                                                                            </svg></button>
                                                                        <form id="form_delete_contact"
                                                                            style="display:inline" class=""
                                                                            action="" method="post" title="Delete">
                                                                            @csrf
                                                                            <button type="submit"
                                                                                style="border:none; background:transparent"
                                                                                class="btn btn-icon btn-danger "
                                                                                onclick="sweetContactConfirm({{ $emergencyContact->id }})">
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                    width="24" height="24"
                                                                                    viewBox="0 0 24 24" fill="none"
                                                                                    stroke="currentColor" stroke-width="2"
                                                                                    stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    class="feather feather-trash-2">
                                                                                    <polyline points="3 6 5 6 21 6">
                                                                                    </polyline>
                                                                                    <path
                                                                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                                                    </path>
                                                                                    <line x1="10" y1="11"
                                                                                        x2="10" y2="17">
                                                                                    </line>
                                                                                    <line x1="14" y1="11"
                                                                                        x2="14" y2="17">
                                                                                    </line>
                                                                                </svg>
                                                                            </button>
                                                                        </form>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>

                            {{-- TAB Profil All Employees --}}
                            <div class="tab-pane" id="profiles" aria-labelledby="profiles-tab" role="tabpanel">
                                <section id="ajax-datatable">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="col-md-9 col-12 my-auto">
                                                <h3 class="m-0">
                                                    SBU :
                                                    <span>
                                                        {{ $department->department_name }}
                                                    </span>
                                                </h3>
                                            </div>
                                            <div class="card">
                                                <div class="card-datatable table-responsive table-rounded">
                                                    <table class="table table-borderless" id="ajax-profile">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Employee Name</th>
                                                                <th>Position</th>
                                                                <th>Mobile Phone</th>
                                                                <th>Email</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>

                            {{-- Skills & Licenses --}}
                            <div class="tab-pane" id="skill" aria-labelledby="skill-tab" role="tabpanel">
                                <section id="ajax-datatable">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="row mb-2">
                                                <div class="col-md-9 col-12 my-auto">
                                                    <h3 class="m-0">Skills & Licenses</h3>
                                                </div>
                                                <div class="col-md-3 col-12 text-right my-auto">
                                                    <div class="btn-group">
                                                        <button type="button" id="add-skill" class="btn btn-primary"
                                                            data-toggle="modal" data-target="#modal-skill">
                                                            Add Skills & License</button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card">
                                                <div class="card-datatable table-responsive table-rounded">
                                                    <table class="table table-borderless" id="table-skill">
                                                        <thead>
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Name</th>
                                                                <th>Issuing Organization</th>
                                                                <th>issue date</th>
                                                                <th>Expiration Date</th>
                                                                <th>Skills</th>
                                                                <th>Credentials</th>
                                                                <th class="text-center">Actions</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if (count($skills) < 1)
                                                                <tr>
                                                                    <td colspan="8" class="text-center">No data
                                                                        available in table</td>
                                                                </tr>
                                                            @endif
                                                            @foreach ($skills as $skill)
                                                                <tr>
                                                                    <td>{{ $loop->iteration }}</td>
                                                                    <td>{{ $skill->skill_name }}</td>
                                                                    <td>{{ $skill->issued_by }}</td>
                                                                    <td>{{ date('F Y', strtotime($skill->issued_date)) }}
                                                                    </td>
                                                                    <td>
                                                                        @if ($skill->exp_date !== null)
                                                                            {{ date('F Y', strtotime($skill->exp_date)) }}
                                                                        @endif
                                                                    </td>
                                                                    <td>{{ $skill->tags }}</td>
                                                                    <td>{{ $skill->credentials }}</td>
                                                                    {{-- <td>
                                                                        <a href="#"
                                                                            class="badge badge-pill badge-light-primary">show</a>

                                                                    </td> --}}
                                                                    <td class="text-center"><button type="button"
                                                                            title="Edit" data-toggle="modal"
                                                                            data-target="#modal-skill"
                                                                            data-id="{{ $skill->id }}"
                                                                            class="edit btn btn-icon btn-success mx-auto"
                                                                            id="edit-skill"><svg
                                                                                xmlns="http://www.w3.org/2000/svg"
                                                                                width="24" height="24"
                                                                                viewBox="0 0 24 24" fill="none"
                                                                                stroke="currentColor" stroke-width="2"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                class="feather feather-edit">
                                                                                <path
                                                                                    d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7">
                                                                                </path>
                                                                                <path
                                                                                    d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z">
                                                                                </path>
                                                                            </svg></button>
                                                                        <form id="form_delete_skill"
                                                                            style="display:inline" class=""
                                                                            action="" method="post" title="Delete">
                                                                            @csrf
                                                                            <button type="submit"
                                                                                style="border:none; background:transparent"
                                                                                class="btn btn-icon btn-danger "
                                                                                onclick="sweetSkillConfirm({{ $skill->id }})">
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                    width="24" height="24"
                                                                                    viewBox="0 0 24 24" fill="none"
                                                                                    stroke="currentColor" stroke-width="2"
                                                                                    stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    class="feather feather-trash-2">
                                                                                    <polyline points="3 6 5 6 21 6">
                                                                                    </polyline>
                                                                                    <path
                                                                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                                                    </path>
                                                                                    <line x1="10" y1="11"
                                                                                        x2="10" y2="17">
                                                                                    </line>
                                                                                    <line x1="14" y1="11"
                                                                                        x2="14" y2="17">
                                                                                    </line>
                                                                                </svg>
                                                                            </button>
                                                                        </form>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
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
                </div>
            </div>
        </div>
    </div>

    {{-- modal add emergency --}}
    <div class="modal fade text-left" id="modal-contact" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-title">Add Contact</h4>
                    <button type="button" class="close m-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form_contact" action="" class="form-data-validate" novalidate method="POST">
                    @csrf
                    <input type="hidden" name="contact_id" id="contact_id" value="">
                    <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}">
                    <div class="modal-body">
                        <label>Name<span class="red-asterisk">*</span> </label>
                        <div class="form-group">
                            <input type="text" placeholder="E.g. Andy Lawson" name="name" id="name" required
                                class="form-control" />
                            <div class="invalid-feedback name_error">Please enter the name.</div>
                        </div>
                        <label>Relationship<span class="red-asterisk">*</span> </label>
                        <div class="form-group">
                            <select class="form-control" name="connection" id="connection" required>
                                <option selected disabled value="">Select Relationship
                                </option>
                                <option value="Wife">Wife</option>
                                <option value="Husband">Husband</option>
                                <option value="Son">Son</option>
                                <option value="Daughter">Daughter</option>
                                <option value="Mother">Mother</option>
                                <option value="Father">Father</option>
                                <option value="Brother">Brother</option>
                                <option value="Sister">Sister</option>
                                <option value="Cousin">Cousin</option>
                                <option value="Uncle">Uncle</option>
                                <option value="Aunt">Aunt</option>
                                <option value="Close Friend">Close Friend</option>
                            </select>
                            <div class="invalid-feedback connection_error">Please enter the relationship.</div>
                        </div>
                        <label>Mobile Phone<span class="red-asterisk">*</span></label>
                        <div class="form-group">
                            <input type="number" placeholder="E.g. 081234567891" name="contact" id="contact" required
                                class="form-control" />
                            <div class="invalid-feedback contact_error">Please enter the contact.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" aria-label="Close"
                            class="btn btn-outline-primary">Cancel</button>
                        <button type="submit" id="submitContact" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- modal add education --}}
    <div class="modal fade text-left" id="modal-education" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header my-auto">
                    <h4 class="modal-title" id="modal-title">Education</h4>
                    <button type="button" class="close m-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form_education" action="" class="form-data-validate" novalidate method="POST">
                    @csrf
                    <input type="hidden" name="education_id" id="education_id" value="">
                    <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}">
                    <div class="modal-body">
                        <label>Institution<span class="red-asterisk">*</span> </label>
                        <div class="form-group">
                            <input type="text" placeholder="E.g. Lawson University" name="institution"
                                id="institution" required class="form-control" />
                            <div class="invalid-feedback institution_error">Please enter institution.</div>
                        </div>
                        <label>Level Education<span class="red-asterisk">*</span> </label>
                        <div class="form-group">
                            <select class="form-control" name="degree" id="degree">
                                <option selected disabled value="">Select Level Education
                                </option>
                                <option value="Early Childhood Education">Early Childhood Education</option>
                                <option value="Primary Education">Primary Education</option>
                                <option value="Lower Secondary Education">Lower Secondary Education</option>
                                <option value="Upper Secondary Education">Upper Secondary Education</option>
                                <option value="Postsecondary Non-Tertiary Education">Postsecondary Non-Tertiary Education
                                </option>
                                <option value="Short-Cycle Tertiary Education">Short-Cycle Tertiary Education</option>
                                <option value="Bachelor’s or Equivalent Level">Bachelor’s or Equivalent Level
                                </option>
                                <option value="Master’s or Equivalent Level">Master’s or Equivalent Level</option>
                                <option value="Doctor or Equivalent Level">Doctor or Equivalent Level</option>
                            </select>
                            <div class="invalid-feedback degree_error">Please enter level education.</div>
                        </div>
                        <label>Major<span class="red-asterisk">*</span> </label>
                        <div class="form-group">
                            <input type="text" placeholder="E.g. Civil Engineering" name="major" id="major"
                                required class="form-control" />
                            <div class="invalid-feedback major_error">Please enter a major.</div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-6">
                                <label>Entry Level<span class="red-asterisk">*</span></label>
                                <input type="number" name="entry_level" id="entry_level" class="form-control"
                                    min="1900" max="2100" maxlength="4" placeholder="Year" required />
                                <div class="invalid-feedback entry_level_error">Please enter the entry level.</div>
                            </div>
                            <div class="col-6">
                                <label>Graduation<span class="red-asterisk">*</span></label>
                                <input type="number" name="graduation_year" id="graduation_year" min="1900"
                                    max="2100" maxlength="4" required class="form-control" placeholder="Year" />
                                <div class="invalid-feedback graduation_year_error">Please enter a graduation year.</div>
                            </div>
                        </div>
                        <label>GPA<span class="red-asterisk">*</span></label>
                        <div class="form-group">
                            <input type="number" placeholder="3.87" step="0.01" name="gpa" id="gpa"
                                required class="form-control" />
                            <div class="invalid-feedback gpa_error">Please enter a grade point average.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" aria-label="Close"
                            class="btn btn-outline-primary">Cancel</button>
                        <button type="submit" id="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- modal add experience --}}
    <div class="modal fade text-left" id="modal-exp" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-title">Experience</h4>
                    <button type="button" class="close m-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form_experience" action="" class="form-data-validate" novalidate method="POST">
                    @csrf
                    <input type="hidden" name="experience_id" id="experience_id" value="">
                    <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}">
                    <div class="modal-body">
                        <label>Corporate<span class="red-asterisk">*</span></label>
                        <div class="form-group">
                            <input type="text" placeholder="E.g. PT Cendani" name="corporate" id="corporate" required
                                class="form-control" />
                            <div class="invalid-feedback corporate_error">Please enter the corporate.</div>
                        </div>
                        <label>Position<span class="red-asterisk">*</span></label>
                        <div class="form-group">
                            <input type="text" placeholder="E.g. Staff Operation" name="position" id="position"
                                required class="form-control" />
                            <div class="invalid-feedback position_error">Please enter the position.</div>
                        </div>
                        <label>Years of service<span class="red-asterisk">*</span></label>
                        <div class="form-group">
                            <input type="number" placeholder="E.g. 2" name="years" id="years" max="100"
                                required class="form-control" />
                            <div class="invalid-feedback years_error">Please enter a year.</div>
                        </div>
                        <label>Job Description<span class="red-asterisk">*</span></label>
                        <div class="form-group">
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                            <div class="invalid-feedback branch_name_error">Please enter the job description.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" aria-label="Close"
                            class="btn btn-outline-primary">Cancel</button>
                        <button type="submit" id="submitExperience" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- modal add skilll --}}
    <div class="modal fade text-left" id="modal-skill" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-title-skill">Add Skills & Licenses</h4>
                    <button type="button" class="close m-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form_skill" class="form-data-validate" novalidate>
                    @csrf
                    <input type="hidden" name="id" id="skill_id" value="">
                    <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}">
                    <div class="modal-body">

                        <label>Name<span class="red-asterisk">*</span></label>
                        <div class="form-group">
                            <input type="text" placeholder="E.g. Microsoft Certified Network Associate Security"
                                name="skill_name" id="skill_name" required class="form-control" />
                            <div class="invalid-feedback skill_name_error">Please enter appropriate value.</div>
                        </div>
                        <label>Issuing Organization<span class="red-asterisk">*</span></label>
                        <div class="form-group">
                            <input type="text" placeholder="E.g. Microsoft" name="issued_by" id="issued_by" required
                                class="form-control" />
                            <div class="invalid-feedback issued_by_error">Please enter appropriate value.</div>
                        </div>
                        <label>Issue Date<span class="red-asterisk">*</span></label>
                        <div class="form-group">
                            <input type="date" placeholder="Month & Year" name="issued_date" id="issued_date"
                                required class="form-control" />
                            <div class="invalid-feedback issued_date_error">Please enter appropriate value.</div>
                            <!-- <div class="valid-feedback">Looks good!</div> -->
                            {{-- <div class="invalid-feedback">Please select date.</div> --}}
                        </div>

                        <label>Expiration Date</label>
                        <div class="form-group">
                            <input type="date" placeholder="Month & Year" name="exp_date" id="exp_date"
                                class="form-control" />
                            <!-- <div class="valid-feedback">Looks good!</div> -->
                            <div class="invalid-feedback">Please select date.</div>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="hidden" id='not_null' name="is_null" value="0">
                                <input type="checkbox" class="custom-control-input" id="is_null" name="is_null"
                                    value="1">
                                <label class="custom-control-label" for="is_null">This credential does not
                                    expire</label>
                            </div>
                        </div>
                        <label>Credential URL<span class="red-asterisk">*</span></label>
                        <div class="form-group">
                            <input type="text" name="credentials" id="credentials" class="form-control" />
                        </div>
                        <label>Skills<span class="red-asterisk">*</span></label>
                        <div class="form-group">
                            <input type="text" name="tags" id="tags" required class="form-control" />
                            <div class="invalid-feedback tags_error">Please enter appropriate value.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-primary">Cancel</button>
                        <button type="button" id="submitSkill" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- modal profile --}}
    <div class="modal fade text-left" id="modal-profile" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">Basic Information</h4>
                    <button type="button" class="close m-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form_edit_profile" action="" class="form-data-validate" novalidate method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="employee_id" id="employee_id"
                        value="{{ $employee->employee_id }}">
                    <div class="modal-body">
                        <div class="row mt-2 mb-2 d-flex">
                            <div class="col-2 ">
                                <img class="rounded" src="{{ $profilePic }}" alt="avatar" height="120"
                                    id="img-preview" width="120">
                            </div>
                            <div class="col-10 d-flex justify-content-end flex-column">
                                <div>
                                    <label for="image" class="custom-file-upload">Upload</label>
                                    <input class="form-control image-profile" type="file" id="image"
                                        name="image">
                                    <button class="btn btn-outline-primary" id="btnImageProfile"
                                        type="submit">Reset</button><br>
                                </div>
                                <small>alowed file types: png, jpg, jpeg.</small>
                                <div class="text-danger hidden image_error">Please upload an image.</div>
                            </div>
                        </div>
                        <label>Full Name </label>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-6">
                                    <input type="text" placeholder="Elfathan" name="first_name" id="first_name"
                                        value="{{ $employee->first_name }}" required class="form-control" />
                                    <div class="invalid-feedback first_name_error">Please enter the first name.</div>
                                </div>
                                <div class="col-6">
                                    <input type="text" placeholder="Mubarok" name="last_name" id="last_name"
                                        value="{{ $employee->last_name }}" required class="form-control" />
                                    <div class="invalid-feedback last_name_error">Please enter the last name.</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-6">
                                    <label>Email </label>
                                    <input type="text" placeholder="Exm: PT persadaInput Job Level Name"
                                        name="email" id="email" value="{{ $user->email }}" required
                                        class="form-control" />
                                    <div class="invalid-feedback email_error">Please enter the email.</div>
                                </div>
                                <div class="col-6">
                                    <label>Mobile Phone </label>
                                    <input type="number" placeholder="082466382645" name="phone" id="phone"
                                        value="{{ $employee->phone }}" required class="form-control" />
                                    <div class="invalid-feedback phone_error">Please enter a phone number.</div>
                                </div>
                            </div>
                        </div>
                        <label>Media Social </label><br>
                        <label>Instagram </label>
                        <div class="form-group">
                            <input type="text" placeholder="Elfathan Mubarok" name="instagram_link"
                                value="{{ $employee->instagram_link }}" id="instagram_link" class="form-control" />
                            <div class="invalid-feedback instagram_link_error">Please enter an instagram link.
                            </div>
                        </div>
                        <label>LinkedIn</label>
                        <div class="form-group">
                            <input type="text" placeholder="Elfathan Mubarok" name="linkedin_link"
                                value="{{ $employee->linkedin_link }}" id="linkedin_link" class="form-control" />
                            <div class="invalid-feedback linkedin_link_error">Please enter a linkedin link.</div>
                        </div>
                        <label>Facebook</label>
                        <div class="form-group">
                            <input type="text" placeholder="Elfathan Mubarok" name="facebook_link"
                                value="{{ $employee->facebook_link }}" id="facebook_link" class="form-control" />
                            <div class="invalid-feedback facebook_link_error">Please enter a facebook link.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" aria-label="Close"
                            class="btn btn-outline-primary">Cancel</button>
                        <button type="submit" id="btnEditProfile" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- modal additional --}}
    <div class="modal fade text-left" id="modal-additional-information" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">Additional Information</h4>
                    <button type="button" class="close m-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form_additional" action="" class="form-data-validate" novalidate method="POST">
                    @csrf
                    <input type="hidden" name="employee_id" id="employee_id"
                        value="{{ $profile->employee_id }}">
                    <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}">
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-6">
                                    <label>Residential Identity Card</label>
                                    <input type="number" placeholder="0224446668246682" name="nik"
                                        id="nik" value="{{ $profile->nik }}" required class="form-control" />
                                    <div class="invalid-feedback nik_error">Please enter the residential identity card.
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label>Passport Number</label>
                                    <input type="number" placeholder="081288893938" name="passport" id="passport"
                                        value="{{ $profile->passport }}" required class="form-control" />
                                    <div class="invalid-feedback passport_error">Please enter the passport number.</div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-6">
                                    <label>Place of Birth</label>
                                    <input type="text" placeholder="Bandung" name="place_of_birth"
                                        id="place_of_birth" value="{{ $profile->place_of_birth }}" required
                                        class="form-control" />
                                    <div class="invalid-feedback place_of_birth_error">Please enter the place of birth.
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label>Date of Birth* </label>
                                    <input type="date" placeholder="Exm: PT persada" name="date_of_birth"
                                        id="date_of_birth" value="{{ $profile->date_of_birth }}" required
                                        class="form-control" />
                                    <div class="invalid-feedback date_of_birth_error">Please enter the date of birth.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-6">
                                    <label>Blood Type</label>
                                    <select name="blood_type" id="blood_type" class="form-control" required>
                                        <option hidden disabled value>Select a Blood Type</option>
                                        <option value="A" {{ $profile->blood_type === 'A' ? 'selected' : '' }}>A
                                        </option>
                                        <option value="B" {{ $profile->blood_type === 'B' ? 'selected' : '' }}>B
                                        </option>
                                        <option value="AB" {{ $profile->blood_type === 'AB' ? 'selected' : '' }}>
                                            AB</option>
                                        <option value="O" {{ $profile->blood_type === 'O' ? 'selected' : '' }}>O
                                        </option>
                                    </select>
                                    <div class="invalid-feedback blood_type_error">Please select a blood type.</div>
                                </div>
                                <div class="col-6">
                                    <label>Religion</label>
                                    <select class="form-control" id="religions" name="religion_id">
                                        @foreach ($religions as $religion)
                                            <option id="religion_id" value="{{ $religion->religion_id }}"
                                                {{ $profile->religion_id == $religion->religion_id ? 'selected' : '' }}>
                                                {{ $religion->religion }}
                                            </option>
                                        @endforeach
                                        <div class="invalid-feedback religions_error">Please select a religion.</div>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-6">
                                    <label>Nationality</label>
                                    <input type="text" placeholder="Warga Negara Indonesia (WNI)"
                                        name="nationality" value="{{ $profile->nationality }}" id="nationality"
                                        required class="form-control" />
                                    <div class="invalid-feedback nationality_error">Please enter the nationality.</div>
                                </div>
                                <div class="col-6">
                                    <label>Relationship* </label>
                                    <div class="form-group">
                                        <select class="form-control" id="marital" name="marital_status_id">
                                            @foreach ($marital_statuses as $maritalStatus)
                                                <option id="marital_status_id"
                                                    value="{{ $maritalStatus->marital_status_id }}"
                                                    {{ $profile->marital_status_id == $maritalStatus->marital_status_id ? 'selected' : '' }}>
                                                    {{ $maritalStatus->marital_status }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="invalid-feedback marital_error">Please select a marital status.</div>
                                </div>
                            </div>
                        </div>
                        <label>Address</label>
                        <div class="form-group">
                            <textarea class="form-control" placeholder="Jl. Amlapura Shaleh No.20, Jakarta Selatan" id="address"
                                name="address" rows="3" placeholder="">{{ $profile->address }}</textarea>
                            <div class="invalid-feedback address_error">Please enter an address.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-outline-primary">Cancel</button>
                        <button type="submit" id="submitAdditional" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- modal employee information --}}
    <div class="modal fade text-left" id="modal-employee-information" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal_title">Employee Information</h4>
                    <button type="button" class="close m-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form_edit_information" action="" class="form-data-validate" novalidate
                    method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-6 col-12 mb-1">
                                    <input type="hidden" value="{{ $employee->employee_id }}" id="employee_id"
                                        name="employee_id">
                                    <input type="hidden" name="user_id" id="user_id"
                                        value="{{ $user->id }}">
                                    <label>Holdings</label>
                                    <select class="form-control" id="company_id" name="company_id"
                                        onchange="selectCompany()" required>
                                        <option disabled value="">Select Holding
                                        </option>
                                        @foreach ($holdings as $holding)
                                            <option value="{{ $holding->company_id }}"
                                                {{ $employee->company_id == $holding->company_id ? 'selected' : '' }}>
                                                {{ $holding->company_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback company_id_error">Please enter the holding.</div>
                                </div>
                                <div class="col-lg-6 col-12 mb-1">
                                    <label>Entity</label>
                                    <select class="form-control" id="branch_id" name="branch_id"
                                        onchange="selectBranch()" disabled>
                                        <option disabled value="">Select Entity
                                        </option>
                                    </select>
                                    <div class="invalid-feedback branch_id_error">Please enter the entity.</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-12 mb-1">
                                    <label>SBU</label>
                                    <select class="form-control" id="department_id" name="department_id" disabled>
                                        <option disabled value="">Select SBU
                                        </option>
                                    </select>
                                    <div class="invalid-feedback department_id_error">Please enter the entity.</div>
                                </div>
                                <div class="col-lg-6 col-12 mb-1">
                                    <label>Role</label>
                                    <select class="form-control" id="role" name="role"
                                        onchange="selectRole()" required>
                                        <option disabled value="">Select Role
                                        </option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}"
                                                {{ $user->roles->first()->id == $role->id ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback role_error">Please enter the role.</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-12 mb-1">
                                    <label>Job Level</label>
                                    <select class="form-control" id="job_class_id" name="job_class_id" disabled
                                        onchange="selectJobClass()" required>
                                        <option disabled value="">Select Job Level
                                        </option>
                                    </select>
                                    <div class="invalid-feedback job_class_id_error">Please enter the Job Level.</div>
                                </div>
                                <div class="col-lg-6 col-12 mb-1">
                                    <label>Job Position</label>
                                    <select class="form-control" id="job_position_id" name="job_position_id" disabled
                                        required>
                                        <option disabled value="">Select Job Position
                                        </option>
                                    </select>
                                    <div class="invalid-feedback job_position_id_error">Please enter the job position.
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-12 mb-1">
                                    <label>Employment Status </label>
                                    <select class="form-control" id="employment_status_id"
                                        name="employment_status_id">
                                        <option disabled value="">Select Employment Status
                                        </option>
                                        @foreach ($employmentStatuses as $employmentStatus)
                                            <option value="{{ $employmentStatus->employment_status_id }}"
                                                {{ $employee->employment_status_id == $employmentStatus->employment_status_id ? 'selected' : '' }}>
                                                {{ $employmentStatus->employment_status }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback employment_status_id_error">Please enter the employment
                                        status.</div>
                                </div>
                                <div class="col-lg-6 col-12 mb-1">
                                    <label>Join Date</label>
                                    <input type="date" name="date_of_joining" id="date_of_joining" required
                                        class="form-control" value="{{ $employee->date_of_joining }}" />
                                    <div class="invalid-feedback date_of_joining_error">Please enter a join date.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" aria-label="Close"
                            class="btn btn-outline-primary">Cancel</button>
                        <button type="submit" id="btnEditInformation" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- modal Vaccine --}}
    <div class="modal fade text-left" id="modal-vaccine" tabindex="-1" role="dialog"
        aria-labelledby="myModalLabel33" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal-title">Vaccine Certificate</h4>
                    <button type="button" class="close m-0" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form_edit_vaccine" action="" class="form-data-validate" novalidate method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}">
                    <div class="modal-body py-1">
                        <div class="form-group">
                            <div class="d-flex justify-content-between input-group rounded py-1 px-1"
                                style="background-color: #F5F5F5">
                                <div class="my-auto">
                                    <svg width="20" height="23" viewBox="0 0 20 23" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M14.5 0.5H2.5C1.4 0.5 0.5 1.4 0.5 2.5V16.5H2.5V2.5H14.5V0.5ZM13.5 4.5H6.5C5.4 4.5 4.51 5.4 4.51 6.5L4.5 20.5C4.5 21.6 5.39 22.5 6.49 22.5H17.5C18.6 22.5 19.5 21.6 19.5 20.5V10.5L13.5 4.5ZM6.5 20.5V6.5H12.5V11.5H17.5V20.5H6.5Z"
                                            fill="#7367F0" />
                                    </svg>
                                </div>
                                <span class="mx-1 my-auto" style="font-size: 14px"> <b>First Vaccine</b></span>
                                <span class="mx-1 my-auto" id="placeholder_vaccine_1" style="font-size: 14px">
                                    @if ($vaccine)
                                        {{ substr($vaccine->vaccine_1, 0, 30) }}
                                        {{ strlen($vaccine->vaccine_1) > 30 ? '...' : '' }}
                                    @endif
                                </span>
                                <input type="file" name="vaccine_1" id="vaccine_1" class="form-control"
                                    style="visibility: hidden" />
                                <button type="button" id="upload-vaccine-1"
                                    class="btn btn-sm btn-outline-secondary">Upload</button>
                            </div>
                            <div class="text-danger hidden vaccine_1_error"></div>
                        </div>
                        <div class="form-group">
                            <div class="d-flex justify-content-between input-group rounded py-1 px-1"
                                style="background-color: #F5F5F5">
                                <div class="my-auto">
                                    <svg width="20" height="23" viewBox="0 0 20 23" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M14.5 0.5H2.5C1.4 0.5 0.5 1.4 0.5 2.5V16.5H2.5V2.5H14.5V0.5ZM13.5 4.5H6.5C5.4 4.5 4.51 5.4 4.51 6.5L4.5 20.5C4.5 21.6 5.39 22.5 6.49 22.5H17.5C18.6 22.5 19.5 21.6 19.5 20.5V10.5L13.5 4.5ZM6.5 20.5V6.5H12.5V11.5H17.5V20.5H6.5Z"
                                            fill="#7367F0" />
                                    </svg>
                                </div>
                                <span class="mx-1 my-auto" style="font-size: 14px"> <b>Second Vaccine</b></span>
                                <span class="mx-1 my-auto" id="placeholder_vaccine_2" style="font-size: 14px">
                                    @if ($vaccine)
                                        {{ substr($vaccine->vaccine_2, 0, 30) }}
                                        {{ strlen($vaccine->vaccine_2) > 30 ? '...' : '' }}
                                    @endif
                                </span>
                                <input type="file" name="vaccine_2" id="vaccine_2" class="form-control"
                                    style="visibility: hidden" />
                                <button type="button" id="upload-vaccine-2"
                                    class="btn btn-sm btn-outline-secondary">Upload</button>
                            </div>
                            <div class="text-danger hidden vaccine_2_error"></div>
                        </div>
                        <div class="form-group">
                            <div class="d-flex justify-content-between input-group rounded py-1 px-1"
                                style="background-color: #F5F5F5">
                                <div class="my-auto">
                                    <svg width="20" height="23" viewBox="0 0 20 23" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M14.5 0.5H2.5C1.4 0.5 0.5 1.4 0.5 2.5V16.5H2.5V2.5H14.5V0.5ZM13.5 4.5H6.5C5.4 4.5 4.51 5.4 4.51 6.5L4.5 20.5C4.5 21.6 5.39 22.5 6.49 22.5H17.5C18.6 22.5 19.5 21.6 19.5 20.5V10.5L13.5 4.5ZM6.5 20.5V6.5H12.5V11.5H17.5V20.5H6.5Z"
                                            fill="#7367F0" />
                                    </svg>
                                </div>
                                <span class="mx-1 my-auto" style="font-size: 14px"> <b>Third Vaccine</b></span>
                                <span class="mx-1 my-auto" id="placeholder_vaccine_3" style="font-size: 14px">
                                    @if ($vaccine)
                                        {{ substr($vaccine->vaccine_3, 0, 30) }}
                                        {{ strlen($vaccine->vaccine_3) > 30 ? '...' : '' }}
                                    @endif
                                </span>
                                <input type="file" name="vaccine_3" id="vaccine_3" class="form-control"
                                    style="visibility: hidden" />
                                <button type="button" id="upload-vaccine-3"
                                    class="btn btn-sm btn-outline-secondary">Upload</button>
                            </div>
                            <div class="text-danger hidden vaccine_3_error"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" data-dismiss="modal" class="btn btn-outline-primary">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="btnEditVaccine">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@section('page_js')
    <script src="{{ url('app-assets/vendors/js/forms/validation/jquery.validate.min.js') }}"></script>
    <script src="{{ url('app-assets/vendors/js/extensions/sweetalert2.all.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@latest/dist/plugins/monthSelect/index.js"></script>
@endsection

@section('page_script')
    <script>
        $(document).ready(function() {
            $('#image').change(function() {
                let reader = new FileReader();
                reader.onload = (e) => {
                    $('#img-preview').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            });

            flatpickr("#date", {
                plugins: [
                    new monthSelectPlugin({
                        shorthand: true, //defaults to false
                        dateFormat: "Y-m", //defaults to "F Y"
                        altFormat: "F Y", //defaults to "F Y"
                        theme: "light" // defaults to "light"
                    })
                ]
            });

            flatpickr("#issued_date", {
                plugins: [
                    new monthSelectPlugin({
                        shorthand: true, //defaults to false
                        dateFormat: "Y-m", //defaults to "F Y"
                        altFormat: "F Y", //defaults to "F Y"
                        theme: "light" // defaults to "light"
                    })
                ]
            });

            flatpickr("#exp_date", {
                plugins: [
                    new monthSelectPlugin({
                        shorthand: true, //defaults to false
                        dateFormat: "Y-m", //defaults to "F Y"
                        altFormat: "F Y", //defaults to "F Y"
                        theme: "light" // defaults to "light"
                    })
                ]
            });

            profil();

            $('a[data-toggle="tab"]').on('shown.bs.tab', (e) => {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust()
                    .responsive.recalc();
            });
        });

        $('#upload-vaccine-1').click(function() {
            $('#vaccine_1').click();
        });

        $("#vaccine_1").on("change", function() {
            let file = this.files[0];
            let fileName = file.name;
            let fileSize = file.size;
            $('#placeholder_vaccine_1').html(`${fileName.substring(0, 30)} ${(fileName.length > 30 ? '...' : '')}`);
            CustomFileHandlingFunction(file);
        });

        $('#upload-vaccine-2').click(function() {
            $('#vaccine_2').click();
        });

        $("#vaccine_2").on("change", function() {
            let file = this.files[0];
            let fileName = file.name;
            let fileSize = file.size;
            $('#placeholder_vaccine_2').html(`${fileName.substring(0, 30)} ${(fileName.length > 30 ? '...' : '')}`);
            CustomFileHandlingFunction(file);
        });

        $('#upload-vaccine-3').click(function() {
            $('#vaccine_3').click();
        });

        $("#vaccine_3").on("change", function() {
            let file = this.files[0];
            let fileName = file.name;
            let fileSize = file.size;
            $('#placeholder_vaccine_3').html(`${fileName.substring(0, 30)} ${(fileName.length > 30 ? '...' : '')}`);
            CustomFileHandlingFunction(file);
        });

        $(document).on('click', '#edit-vaccine', function(event) {
            $('#modal-title').text('Update Vaccine Certificate');

            $('#vaccine_1').removeClass(['is-invalid', 'invalid-more']);
            $('#vaccine_2').removeClass(['is-invalid', 'invalid-more']);
            $('#vaccine_3').removeClass(['is-invalid', 'invalid-more']);

            editVaccine();
        })

        const editVaccine = () => {
            Array.prototype.filter.call($('#form_edit_vaccine'), function(form) {
                $('#btnEditVaccine').unbind().on('click', function(event) {
                    if (form.checkValidity() === false) {
                        form.classList.add('invalid');
                    }

                    form.classList.add('was-validated');
                    event.preventDefault();

                    const formEditData = document.querySelector('#form_edit_vaccine');
                    if (formEditData) {
                        const request = new FormData(formEditData);
                        const formData = new FormData();
                        formData.append('_token', request.get('_token'));
                        formData.append('user_id', request.get('user_id'));
                        formData.append('vaccine_1', request.get('vaccine_1'));
                        formData.append('vaccine_2', request.get('vaccine_2'));
                        formData.append('vaccine_3', request.get('vaccine_3'));

                        const id = $('#employee_id').val();

                        fetch(`/employees/store-vaccine`, {
                                method: 'POST',
                                headers: {
                                    // 'Content-Type': 'multipart/form-data',
                                },
                                body: formData,
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.error) {
                                    if (data.message) {
                                        throw data.message;
                                    }

                                    $.each(data.error, (prefix, val) => {
                                        $(`.${prefix}_error`).removeClass('hidden');
                                        $(`.${prefix}_error`).text(val[0]);
                                    });

                                } else {
                                    $(`.vaccine_1_error`).addClass('hidden');
                                    $(`.vaccine_2_error`).addClass('hidden');
                                    $(`.vaccine_3_error`).addClass('hidden');
                                    $("#card-vaccines").load(window.location
                                        .href +
                                        " #card-vaccines");
                                    Swal.fire({
                                        type: 'success',
                                        title: 'Success!',
                                        text: data.message,
                                        confirmButtonClass: 'btn btn-success'
                                    });

                                    $('#modal-vaccine').modal('hide');
                                }
                            })
                            .catch((error) => {
                                console.error('Error:', error);
                                Swal.fire({
                                    type: "error",
                                    title: 'Oops...',
                                    text: `${error}`,
                                    confirmButtonClass: 'btn btn-danger',
                                });
                            });
                    }
                });
            });
        };

        $(document).on('click', '#edit-profile', async function(event) {
            $('#modal-profile').modal('show');
            const btnEdit = $('#submitProfile').attr('id', 'btnImageProfile');
            const id = $(this).data('id');
            const employee_id = document.querySelector('#employee_id');
            const image = document.querySelector('#image');

            EditProfileImage();
        });

        const EditProfileImage = () => {
            Array.prototype.filter.call($('#form_edit_profile'), function(form) {
                $('#btnImageProfile').unbind().on('click', function(event) {
                    if (form.checkValidity() === false) {
                        form.classList.add('invalid');
                    }

                    form.classList.add('was-validated');
                    event.preventDefault();

                    const formEditData = document.querySelector('#form_edit_profile');
                    if (formEditData) {
                        const file = document.querySelector('input[type="file"]');
                        const request = new FormData(formEditData);
                        const formData = new FormData();
                        formData.append('_token', request.get('_token'));
                        formData.append('image', request.get('image'));
                        formData.append('image', request.get('image'));

                        const id = $('#employee_id').val();

                        fetch(`/profile-img-reset/${id}?_method=PUT`, {
                                method: 'POST',
                                headers: {
                                    // 'Content-Type': 'multipart/form-data',
                                },
                                body: formData,
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.error) {
                                    if (data.message) {
                                        throw data.message;
                                    }

                                    $.each(data.error, (prefix, val) => {
                                        $(`#${prefix}`).addClass('is-invalid');
                                        $(`#${prefix}`).addClass('invalid-more');
                                        $(`.${prefix}_error`).text(val[0]);
                                    });

                                } else {
                                    $("#basic-information").load(window.location
                                        .href +
                                        " #basic-information");
                                    $("#nav-user").load(window.location
                                        .href + " #nav-user");
                                    Swal.fire({
                                        type: 'success',
                                        title: 'Success!',
                                        text: data.message,
                                        confirmButtonClass: 'btn btn-success'
                                    });

                                    $('#modal-profile').modal('hide');
                                }
                            })
                            .catch((error) => {
                                console.error('Error:', error);
                                Swal.fire({
                                    type: "error",
                                    title: 'Oops...',
                                    text: `${error}`,
                                    confirmButtonClass: 'btn btn-danger',
                                });
                            });
                    }
                });
            });
        };

        $(document).on('click', '#add-education', function(event) {
            $('#modal-title').text('Add an Education');
            const idForm = $('form#form_edit_education').attr('id', 'form_education');
            const education = document.querySelector('#education_id');
            const institution = document.querySelector('#institution');
            const levelEdu = document.querySelector('#degree');
            const major = document.querySelector('#major');
            const entry = document.querySelector('#entry_level');
            const graduation = document.querySelector('#graduation_year');
            const gpa = document.querySelector('#gpa');
            const btnSubmit = $('#btnEdit').attr('id', 'submit');

            education.value = '';
            institution.value = '';
            levelEdu.value = '';
            major.value = '';
            entry.value = '';
            graduation.value = '';
            gpa.value = '';

            $('#institution').removeClass(['is-invalid', 'invalid-more']);
            $('#degree').removeClass(['is-invalid', 'invalid-more']);
            $('#major').removeClass(['is-invalid', 'invalid-more']);
            $('#entry_level').removeClass(['is-invalid', 'invalid-more']);
            $('#graduation_year').removeClass(['is-invalid', 'invalid-more']);
            $('#gpa').removeClass(['is-invalid', 'invalid-more']);

            $(document).ready(() => {
                $('#degree').select2();
            });

            submit();
        });

        $(document).on('click', '#add-experience', function(event) {
            $('#modal-title').text('Add an Experience');
            const idForm = $('form#form_edit_experience').attr('id', 'form_experience');
            const experience = document.querySelector('#experience_id');
            const corporate = document.querySelector('#corporate');
            const position = document.querySelector('#position');
            const years = document.querySelector('#years');
            const description = document.querySelector('#description');
            const btnSubmit = $('#btnEditExperience').attr('id', 'submitExperience');

            experience.value = '';
            corporate.value = '';
            position.value = '';
            years.value = '';
            description.value = '';

            $('#corporate').removeClass(['is-invalid', 'invalid-more']);
            $('#position').removeClass(['is-invalid', 'invalid-more']);
            $('#years').removeClass(['is-invalid', 'invalid-more']);
            $('#description').removeClass(['is-invalid', 'invalid-more']);

            submitExperience();
        });

        $(document).on('click', '#add-skill', function(event) {

            // let reset_form = $('#form_data')[0];

            // $("#form_data").trigger("reset");
            // $(reset_form).removeClass('was-validated');

            $('#modal-title-skill').text('Add Skills & Licenses');
            const idForm = $('form#form_edit_skill').attr('id', 'form_skill');
            const skill = document.querySelector('#skill_id');
            const skill_name = document.querySelector('#skill_name');
            const issued_by = document.querySelector('#issued_by');
            const issued_date = document.querySelector('#issued_date');
            const exp_date = document.querySelector('#exp_date');
            const credentials = document.querySelector('#credentials');
            const is_null = document.querySelector('#is_null');
            const not_null = document.querySelector('#not_null');
            const tags = document.querySelector('#tags');
            const btnSubmit = $('#btnEditSkill').attr('id', 'submitSkill');

            skill.value = '';
            skill_name.value = '';
            issued_by.value = '';
            issued_date.value = '';
            exp_date.value = '';
            credentials.value = '';

            is_null.onchange = () => {
                if (is_null.checked) {
                    exp_date.disabled = true;
                    not_null.disabled = true;
                    is_null.value = 1;
                } else {
                    exp_date.disabled = false;
                    not_null.disabled = false;
                    not_null.value = 0;
                }
            }

            tags.value = '';

            $('#skill_name').removeClass(['is-invalid', 'invalid-more']);
            $('#issued_by').removeClass(['is-invalid', 'invalid-more']);
            $('#issued_date').removeClass(['is-invalid', 'invalid-more']);
            $('#exp_date').removeClass(['is-invalid', 'invalid-more']);
            $('#credentials').removeClass(['is-invalid', 'invalid-more']);
            $('#is_null').removeClass(['is-invalid', 'invalid-more']);
            $('#is_null').prop('checked', false);
            $('#exp_date').prop('disabled', false);
            $('#tags').removeClass(['is-invalid', 'invalid-more']);

            submitSkill();
        });

        $(document).on('click', '#add-contact', function(event) {
            $('#modal-title').text('Add an Emergency Contact');
            const idForm = $('form#form_edit_contact').attr('id', 'form_contact');
            const contactId = document.querySelector('#contact_id');
            const name = document.querySelector('#name');
            const connection = document.querySelector('#connection');
            const contact = document.querySelector('#contact');
            const btnSubmit = $('#btnEditContact').attr('id', 'submitContact');

            contactId.value = '';
            name.value = '';
            connection.value = '';
            contact.value = '';

            $(document).ready(() => {
                $('#connection').select2();
            });

            $('#name').removeClass(['is-invalid', 'invalid-more']);
            $('#connection').removeClass(['is-invalid', 'invalid-more']);
            $('#contact').removeClass(['is-invalid', 'invalid-more']);

            submitContact();
        });

        $(document).on('click', '#edit-information', async function(event) {
            $('#modal-employee-information').modal('show');
            $(document).ready(() => {
                $('#company_id').select2();
                $('#branch_id').select2();
                $('#department_id').select2();
                $('#job_class_id').select2();
                $('#job_position_id').select2();
                $('#employment_status_id').select2();
                $('#role').select2();
                $('#date_of_joining').flatpickr({
                    altInput: true,
                    dateFormat: "Y-m-d",
                    defaultDate: $('#date_of_joining').val(),
                    maxDate: new Date().toISOString().slice(0, 10),
                });
            });

            const companyId = document.querySelector('#company_id');
            const branchId = document.querySelector('#branch_id');
            const departmentId = document.querySelector('#department_id');
            const role = document.querySelector('#role');
            const jobClassId = document.querySelector('#job_class_id');
            const jobPositionId = document.querySelector('#job_position_id');
            if (companyId.value != '') {
                fetch(`/profile?company=${companyId.value}`)
                    .then(response => response.json())
                    .then(response => {
                        branchId.removeAttribute('disabled');

                        if (branchId.length > 0) {
                            for (let i = 0; i < branchId.length; i++) {
                                $('#branch_id')
                                    .find('option')
                                    .remove();
                            }
                        }

                        const opt = document.createElement('option');
                        opt.value = '';
                        opt.disabled = 'disabled';
                        opt.innerHTML = 'Select Entity';
                        branchId.appendChild(opt);
                        if (response.branches.length > 0) {
                            response.branches.forEach((item, index) => {
                                const opt = document.createElement('option');
                                opt.value = item.branch_id;
                                if (response.branch == item.branch_id) {
                                    opt.selected = 'selected';
                                }
                                opt.innerHTML = item.branch_name;
                                branchId.appendChild(opt);
                            });
                        }

                        if (response.branch > 0) {
                            fetch(`/profile?branch=${response.branch}`)
                                .then(response => response.json())
                                .then(response => {
                                    departmentId.removeAttribute('disabled');
                                    console.log(response);

                                    if (departmentId.length > 0) {
                                        for (let i = 0; i < departmentId.length; i++) {
                                            $('#department_id')
                                                .find('option')
                                                .remove();
                                        }
                                    }

                                    const opt = document.createElement('option');
                                    opt.value = '';
                                    opt.disabled = 'disabled';
                                    opt.innerHTML = 'Select SBU';
                                    departmentId.appendChild(opt);
                                    if (response.departments.length > 0) {
                                        response.departments.forEach((item, index) => {
                                            const opt = document.createElement('option');
                                            opt.value = item.department_id;
                                            if (response.department == item.department_id) {
                                                opt.selected = 'selected';
                                            }
                                            opt.innerHTML = item.department_name;
                                            departmentId.appendChild(opt);
                                        });
                                    }
                                });
                        }
                    });
            }

            if (role.value != '') {
                fetch(`/profile?role=${role.value}`)
                    .then(response => response.json())
                    .then(response => {
                        jobClassId.removeAttribute('disabled');

                        if (jobClassId.length > 0) {
                            for (let i = 0; i < jobClassId.length; i++) {
                                $('#job_class_id')
                                    .find('option')
                                    .remove();
                            }
                        }

                        const opt = document.createElement('option');
                        opt.value = '';
                        opt.disabled = 'disabled';
                        opt.innerHTML = 'Select Job Level';
                        jobClassId.appendChild(opt);
                        if (response.jobClasses.length > 0) {
                            response.jobClasses.forEach((item, index) => {
                                const opt = document.createElement('option');
                                opt.value = item.job_class_id;
                                if (response.jobClass == item.job_class_id) {
                                    opt.selected = 'selected';
                                }
                                opt.innerHTML = item.job_class;
                                jobClassId.appendChild(opt);
                            });
                        }

                        if (response.jobClass > 0) {
                            fetch(`/profile?jobclass=${response.jobClass}`)
                                .then(response => response.json())
                                .then(response => {
                                    jobPositionId.removeAttribute('disabled');
                                    console.log(response);

                                    if (jobPositionId.length > 0) {
                                        for (let i = 0; i < jobPositionId.length; i++) {
                                            $('#job_position_id')
                                                .find('option')
                                                .remove();
                                        }
                                    }

                                    const opt = document.createElement('option');
                                    opt.value = '';
                                    opt.disabled = 'disabled';
                                    opt.innerHTML = 'Select Job Position';
                                    jobPositionId.appendChild(opt);
                                    if (response.jobPositions.length > 0) {
                                        response.jobPositions.forEach((item, index) => {
                                            const opt = document.createElement('option');
                                            opt.value = item.job_position_id;
                                            if (response.jobPosition == item.job_position_id) {
                                                opt.selected = 'selected';
                                            }
                                            opt.innerHTML = item.job_position;
                                            jobPositionId.appendChild(opt);
                                        });
                                    }
                                });
                        }
                    });
            }

            $(document).ready(() => {
                $('#company_id').select2();
                $('#branch_id').select2();
                $('#department_id').select2();
                $('#job_class_id').select2();
                $('#job_position_id').select2();
                $('#employment_status_id').select2();
                $('#role').select2();
                $('#date_of_joining').flatpickr({
                    allowInput: true,
                    dateFormat: "Y-m-d",
                    defaultDate: $('#date_of_joining').val(),
                    maxDate: new Date().toISOString().slice(0,
                        10),
                });
            });

            submitEditInformation();
        });

        $(document).on('click', '#edit', async function(event) {
            $('#modal-education').modal('show');
            const btnEdit = $('#submit').attr('id', 'btnEdit');
            const id = $(this).data('id');
            console.log(id)
            const education = document.querySelector('#education_id');
            const institution = document.querySelector('#institution');
            const levelEdu = document.querySelector('#degree');
            const major = document.querySelector('#major');
            const entry = document.querySelector('#entry_level');
            const graduation = document.querySelector('#graduation_year');
            const gpa = document.querySelector('#gpa');
            $('#modal-title').text('Edit an Education');
            const idForm = $('form#form_education').attr('id', 'form_edit_education');
            $('#institution').removeClass(['is-invalid', 'invalid-more']);
            $('#degree').removeClass(['is-invalid', 'invalid-more']);
            $('#major').removeClass(['is-invalid', 'invalid-more']);
            $('#entry_level').removeClass(['is-invalid', 'invalid-more']);
            $('#graduation_year').removeClass(['is-invalid', 'invalid-more']);
            $('#gpa').removeClass(['is-invalid', 'invalid-more']);

            await fetch(`/employee-educations/${id}/edit`)
                .then(response => response.json())
                .then(response => {
                    education.value = id;
                    institution.value = response.institution;
                    levelEdu.value = response.degree;
                    major.value = response.major;
                    entry.value = response.entry_level;
                    graduation.value = response.graduation_year;
                    gpa.value = response.gpa;

                    $(document).ready(() => {
                        $('#degree').select2();
                    });
                });

            submitEdit();
        });

        $(document).on('click', '#edit-experience', async function(event) {
            $('#modal-exp').modal('show');
            const btnEdit = $('#submitExperience').attr('id', 'btnEditExperience');
            const id = $(this).data('id');
            const experience = document.querySelector('#experience_id');
            const corporate = document.querySelector('#corporate');
            const position = document.querySelector('#position');
            const years = document.querySelector('#years');
            const description = document.querySelector('#description');
            $('#modal-title').text('Edit an Experience');
            const idForm = $('form#form_experience').attr('id', 'form_edit_experience');
            $('#corporate').removeClass(['is-invalid', 'invalid-more']);
            $('#position').removeClass(['is-invalid', 'invalid-more']);
            $('#years').removeClass(['is-invalid', 'invalid-more']);
            $('#description').removeClass(['is-invalid', 'invalid-more']);

            await fetch(`/employee-experiences/${id}/edit`)
                .then(response => response.json())
                .then(response => {
                    experience.value = id;
                    corporate.value = response.corporate;
                    position.value = response.position;
                    years.value = response.years;
                    description.value = response.description;
                });

            submitEditExperience();
        });

        $(document).on('click', '#edit-skill', async function(event) {
            $('#modal-skill').modal('show');
            const btnEdit = $('#submitSkill').attr('id', 'btnEditSkill');
            const id = $(this).data('id');
            const skill = document.querySelector('#skill_id');
            const skill_name = document.querySelector('#skill_name');
            const issued_by = document.querySelector('#issued_by');
            const issued_date = document.querySelector('#issued_date');
            const exp_date = document.querySelector('#exp_date');
            const is_null = document.querySelector('#is_null');
            const not_null = document.querySelector('#not_null');
            const credentials = document.querySelector('#credentials');
            const tags = document.querySelector('#tags');

            is_null.onchange = () => {
                if (is_null.checked) {
                    exp_date.disabled = true;
                    not_null.disabled = true;
                    is_null.value = 1;
                } else {
                    exp_date.disabled = false;
                    not_null.disabled = false;
                    not_null.value = 0;
                }
            }


            $('#modal-title-skill').text('Edit Skills & Licenses');
            const idForm = $('form#form_skill').attr('id', 'form_edit_skill');
            $('#skill_name').removeClass(['is-invalid', 'invalid-more']);
            $('#issued_by').removeClass(['is-invalid', 'invalid-more']);
            $('#issued_date').removeClass(['is-invalid', 'invalid-more']);
            $('#exp_date').removeClass(['is-invalid', 'invalid-more']);
            $('#credentials').removeClass(['is-invalid', 'invalid-more']);
            $('#tags').removeClass(['is-invalid', 'invalid-more']);

            await fetch(`/employee-skills/${id}/edit`)
                .then(response => response.json())
                .then(response => {
                    skill.value = id;
                    skill_name.value = response.skill_name;
                    issued_by.value = response.issued_by;
                    issued_date.value = response.issued_date;
                    exp_date.value = response.exp_date;
                    credentials.value = response.credentials;
                    if (response.is_null === 1) {
                        is_null.checked = true;
                        exp_date.disabled = true;
                    } else {
                        is_null.checked = false;
                        exp_date.disabled = false;
                    }
                    tags.value = response.tags;
                });

            submitEditSkill();
        });

        $(document).on('click', '#edit-contact', async function(event) {
            $('#modal-contact').modal('show');
            const btnEdit = $('#submitContact').attr('id', 'btnEditContact');
            const id = $(this).data('id');
            const contactId = document.querySelector('#contact_id');
            const name = document.querySelector('#name');
            const connection = document.querySelector('#connection');
            const contact = document.querySelector('#contact');
            $('#modal-title').text('Edit an Emergency Contact');
            const idForm = $('form#form_contact').attr('id', 'form_edit_contact');
            $('#name').removeClass(['is-invalid', 'invalid-more']);
            $('#connection').removeClass(['is-invalid', 'invalid-more']);
            $('#contact').removeClass(['is-invalid', 'invalid-more']);

            await fetch(`/employee-emergency-contacts/${id}/edit`)
                .then(response => response.json())
                .then(response => {
                    contactId.value = id;
                    name.value = response.name;
                    connection.value = response.connection;
                    contact.value = response.contact;
                });

            submitEditContact();
        });

        $(document).on('click', '#edit-additional-information', async function(event) {
            // console.log(11111)
            const reset_form = $('#form_data')[0];
            const reset_form_edit = $('#form_edit_data')[0];
            $(reset_form).removeClass('was-validated');
            $(reset_form_edit).removeClass('was-validated');
            let uniqueField = ["att_date", "att_check_in", "att_check_out", "attachment"]
            for (let i = 0; i < uniqueField.length; i++) {
                $("#" + uniqueField[i]).removeClass('was-validated');
                $("#" + uniqueField[i]).removeClass("is-invalid");
                $("#" + uniqueField[i]).removeClass("invalid-more");
            }
            $('#modal-additional-information').modal('show');
            const btnEdit = $('#submitAdditional').attr('id', 'submitAdditional');
            const id = $(this).data('employee_id');
            $('#modal-title').text('Additional Information');
            const idForm = $('form#form_additional').attr('id', 'form_additional');
            $('#nik').removeClass(['is-invalid', 'invalid-more']);
            $('#passport').removeClass(['is-invalid', 'invalid-more']);
            $('#place_of_birth').removeClass(['is-invalid', 'invalid-more']);
            $('#date_of_birth').removeClass(['is-invalid', 'invalid-more']);
            $('#blood_type').removeClass(['is-invalid', 'invalid-more']);
            $('#religion_id').removeClass(['is-invalid', 'invalid-more']);
            $('#nationality').removeClass(['is-invalid', 'invalid-more']);
            $('#marital_status_id').removeClass(['is-invalid', 'invalid-more']);
            $('#address').removeClass(['is-invalid', 'invalid-more']);
            $('#marital').removeClass(['is-invalid', 'invalid-more']);
            $('#religions').removeClass(['is-invalid', 'invalid-more']);
            $(document).ready(() => {
                $('#date_of_birth').flatpickr({
                    allowInput: true,
                    dateFormat: "Y-m-d",
                    defaultDate: $('#date_of_birth').val(),
                    maxDate: new Date().toISOString().slice(0,
                        10),
                });
            });

            submitEditAdditional();
        });

        const submitEditInformation = () => {
            Array.prototype.filter.call($('#form_edit_information'), function(form) {
                $('#btnEditInformation').unbind().on('click', function(event) {
                    if (form.checkValidity() === false) {
                        form.classList.add('invalid');
                    }
                    form.classList.add('was-validated');
                    event.preventDefault();

                    const formEditData = document.querySelector('#form_edit_information');
                    if (formEditData) {
                        const request = new FormData(formEditData);

                        const data = {
                            _token: request.get('_token'),
                            user_id: request.get('user_id'),
                            company_id: request.get('company_id'),
                            branch_id: request.get('branch_id'),
                            department_id: request.get('department_id'),
                            job_class_id: request.get('job_class_id'),
                            job_position_id: request.get('job_position_id'),
                            employment_status_id: request.get('employment_status_id'),
                            date_of_joining: request.get('date_of_joining'),
                            role: request.get('role'),
                        };

                        const id = $('#employee_id').val();

                        fetch(`/employee-informations/${id}`, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify(data),
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.error) {
                                    if (data.message) {
                                        throw data.message;
                                    }

                                    $.each(data.error, (prefix, val) => {
                                        $(`#${prefix}`).addClass('is-invalid');
                                        $(`#${prefix}`).addClass('invalid-more');
                                        $(`.${prefix}_error`).text(val[0]);
                                    });

                                } else {
                                    setTimeout(() => {
                                        $("#employee-information-card").load(window.location
                                            .href +
                                            " #employee-information-card");
                                        $("#nav-user").load(window.location
                                            .href + " #nav-user");
                                    }, 0);

                                    Swal.fire({
                                        type: 'success',
                                        title: 'Success!',
                                        text: data.message,
                                        confirmButtonClass: 'btn btn-success'
                                    });

                                    $('#modal-employee-information').modal('hide');
                                }
                            })
                            .catch((error) => {
                                console.error('Error:', error);
                                Swal.fire({
                                    type: "error",
                                    title: 'Oops...',
                                    text: `${error}`,
                                    confirmButtonClass: 'btn btn-success',
                                });
                            });
                    }
                });
            });
        };

        $(document).on('click', '#edit-profile', async function(event) {
            $('#modal-profile').modal('show');
            const btnEdit = $('#submitProfile').attr('id', 'btnEditProfile');
            const employeeId = document.querySelector('#employee_id');
            const first_name = document.querySelector('#first_name');
            const last_name = document.querySelector('#last_name');
            const email = document.querySelector('#email');
            const phone = document.querySelector('#phone');
            const image = document.querySelector('#image')
            const instagram_link = document.querySelector('#instagram_link');
            const linkedin_link = document.querySelector('#linkedin_link');
            const facebook_link = document.querySelector('#facebook_link');
            $('#modal-title').text('Edit an Basic Information');
            const idForm = $('form#form_edit_profile').attr('employee_id', 'form_edit_profile');
            $('#first_name').removeClass(['is-invalid', 'invalid-more']);
            $('#last_name').removeClass(['is-invalid', 'invalid-more']);
            $('#email').removeClass(['is-invalid', 'invalid-more']);
            $('#phone').removeClass(['is-invalid', 'invalid-more']);
            $('#instagram_link').removeClass(['is-invalid', 'invalid-more']);
            $('#linkedin_link').removeClass(['is-invalid', 'invalid-more']);
            $('#facebook_link').removeClass(['is-invalid', 'invalid-more']);

            submitEditProfile();
        });

        const submitEditProfile = () => {
            Array.prototype.filter.call($('#form_edit_profile'), function(form) {
                $('#btnEditProfile').unbind().on('click', function(event) {
                    if (form.checkValidity() === false) {
                        form.classList.add('invalid');
                    }

                    form.classList.add('was-validated');
                    event.preventDefault();

                    const formEditData = document.querySelector('#form_edit_profile');
                    if (formEditData) {
                        const file = document.querySelector('input[type="file"]');
                        const request = new FormData(formEditData);
                        const formData = new FormData();
                        formData.append('_token', request.get('_token'));
                        formData.append('image', request.get('image'));
                        formData.append('first_name', request.get('first_name'));
                        formData.append('last_name', request.get('last_name'));
                        formData.append('email', request.get('email'));
                        formData.append('phone', request.get('phone'));
                        formData.append('image', request.get('image'));
                        formData.append('instagram_link', request.get('instagram_link'));
                        formData.append('linkedin_link', request.get('linkedin_link'));
                        formData.append('facebook_link', request.get('facebook_link'));

                        const id = $('#employee_id').val();

                        fetch(`/profile/${id}?_method=PUT`, {
                                method: 'POST',
                                headers: {
                                    // 'Content-Type': 'multipart/form-data',
                                },
                                body: formData,
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.error) {
                                    if (data.message) {
                                        throw data.message;
                                    }

                                    if (data.error.image) {
                                        $(`.image_error`).removeClass('hidden');
                                    }

                                    $.each(data.error, (prefix, val) => {
                                        $(`#${prefix}`).addClass('is-invalid');
                                        $(`#${prefix}`).addClass('invalid-more');
                                        $(`.${prefix}_error`).text(val[0]);
                                    });

                                } else {
                                    $(`.image_error`).addClass('hidden');
                                    $("#basic-information").load(window.location
                                        .href +
                                        " #basic-information");
                                    $("#nav-user").load(window.location
                                        .href + " #nav-user");
                                    Swal.fire({
                                        type: 'success',
                                        title: 'Success!',
                                        text: data.message,
                                        confirmButtonClass: 'btn btn-success'
                                    });

                                    $('#modal-profile').modal('hide');
                                }
                            })
                            .catch((error) => {
                                console.error('Error:', error);
                                Swal.fire({
                                    type: "error",
                                    title: 'Oops...',
                                    text: `${error}`,
                                    confirmButtonClass: 'btn btn-danger',
                                });
                            });
                    }
                });
            });
        };

        const submitEdit = () => {
            Array.prototype.filter.call($('#form_edit_education'), function(form) {
                $('#btnEdit').unbind().on('click', function(event) {
                    if (form.checkValidity() === false) {
                        form.classList.add('invalid');
                    }

                    form.classList.add('was-validated');
                    event.preventDefault();

                    const formEditData = document.querySelector('#form_edit_education');
                    if (formEditData) {
                        const request = new FormData(formEditData);

                        const data = {
                            _token: request.get('_token'),
                            user_id: request.get('user_id'),
                            institution: request.get('institution'),
                            degree: request.get('degree'),
                            major: request.get('major'),
                            entry_level: request.get('entry_level'),
                            graduation_year: request.get('graduation_year'),
                            gpa: request.get('gpa'),
                        };

                        const id = $('#education_id').val();

                        fetch(`/employee-educations/${id}`, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify(data),
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.error) {
                                    if (data.message) {
                                        throw data.message;
                                    }

                                    $.each(data.error, (prefix, val) => {
                                        $(`#${prefix}`).addClass('is-invalid');
                                        $(`#${prefix}`).addClass('invalid-more');
                                        $(`.${prefix}_error`).text(val[0]);
                                    });

                                } else {
                                    setTimeout(() => {
                                        $("#table-edu").load(window.location.href +
                                            " #table-edu");
                                    }, 0);

                                    Swal.fire({
                                        type: 'success',
                                        title: 'Success!',
                                        text: data.message,
                                        confirmButtonClass: 'btn btn-success'
                                    });

                                    $('#modal-education').modal('hide');
                                }
                            })
                            .catch((error) => {
                                console.error('Error:', error);
                                Swal.fire({
                                    type: "error",
                                    title: 'Oops...',
                                    text: `${error}`,
                                    confirmButtonClass: 'btn btn-success',
                                });
                            });
                    } else {
                        submit();
                    }
                });
            });
        };

        const submitEditExperience = () => {
            Array.prototype.filter.call($('#form_edit_experience'), function(form) {
                $('#btnEditExperience').unbind().on('click', function(event) {
                    if (form.checkValidity() === false) {
                        form.classList.add('invalid');
                    }

                    form.classList.add('was-validated');
                    event.preventDefault();

                    const formEditData = document.querySelector('#form_edit_experience');
                    if (formEditData) {
                        const request = new FormData(formEditData);

                        const data = {
                            _token: request.get('_token'),
                            user_id: request.get('user_id'),
                            corporate: request.get('corporate'),
                            position: request.get('position'),
                            years: request.get('years'),
                            description: request.get('description'),
                        };

                        const id = $('#experience_id').val();

                        fetch(`/employee-experiences/${id}`, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify(data),
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.error) {
                                    if (data.message) {
                                        throw data.message;
                                    }

                                    $.each(data.error, (prefix, val) => {
                                        $(`#${prefix}`).addClass('is-invalid');
                                        $(`#${prefix}`).addClass('invalid-more');
                                        $(`.${prefix}_error`).text(val[0]);
                                    });

                                } else {
                                    setTimeout(() => {
                                        $("#table-exp").load(window.location.href +
                                            " #table-exp");
                                    }, 0);

                                    Swal.fire({
                                        type: 'success',
                                        title: 'Success!',
                                        text: data.message,
                                        confirmButtonClass: 'btn btn-success'
                                    });

                                    $('#modal-exp').modal('hide');
                                }
                            }).catch((error) => {
                                console.error('Error:', error);
                                Swal.fire({
                                    type: "error",
                                    title: 'Oops...',
                                    text: `${error}`,
                                    confirmButtonClass: 'btn btn-success',
                                });
                            });
                    } else {
                        submitExperience();
                    }
                });
            });
        };

        const submitEditSkill = () => {
            Array.prototype.filter.call($('#form_edit_skill'), function(form) {
                $('#btnEditSkill').unbind().on('click', function(event) {
                    if (form.checkValidity() === false) {
                        form.classList.add('invalid');
                    }

                    form.classList.add('was-validated');
                    event.preventDefault();

                    const formEditData = document.querySelector('#form_edit_skill');
                    if (formEditData) {
                        const request = new FormData(formEditData);

                        const data = {
                            _token: request.get('_token'),
                            user_id: request.get('user_id'),
                            skill_name: request.get('skill_name'),
                            issued_by: request.get('issued_by'),
                            issued_date: request.get('issued_date'),
                            exp_date: request.get('exp_date'),
                            is_null: request.get('is_null'),
                            credentials: request.get('credentials'),
                            tags: request.get('tags'),
                        };

                        const id = $('#skill_id').val();

                        fetch(`/employee-skills/${id}`, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify(data),
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.error) {
                                    if (data.message) {
                                        throw data.message;
                                    }

                                    $.each(data.error, (prefix, val) => {
                                        $(`#${prefix}`).addClass('is-invalid');
                                        $(`#${prefix}`).addClass('invalid-more');
                                        $(`.${prefix}_error`).text(val[0]);
                                    });

                                } else {
                                    setTimeout(() => {
                                        $("#table-skill").load(window.location.href +
                                            " #table-skill");
                                    }, 0);

                                    Swal.fire({
                                        type: 'success',
                                        title: 'Success!',
                                        text: data.message,
                                        confirmButtonClass: 'btn btn-success'
                                    });

                                    $('#modal-skill').modal('hide');
                                }
                            }).catch((error) => {
                                console.error('Error:', error);
                                Swal.fire({
                                    type: "error",
                                    title: 'Oops...',
                                    text: `${error}`,
                                    confirmButtonClass: 'btn btn-success',
                                });
                            });
                    } else {
                        submitSkill();
                    }
                });
            });
        };

        const submitEditContact = () => {
            Array.prototype.filter.call($('#form_edit_contact'), function(form) {
                $('#btnEditContact').unbind().on('click', function(event) {
                    if (form.checkValidity() === false) {
                        form.classList.add('invalid');
                    }

                    form.classList.add('was-validated');
                    event.preventDefault();

                    const formEditData = document.querySelector('#form_edit_contact');
                    if (formEditData) {
                        const request = new FormData(formEditData);

                        const data = {
                            _token: request.get('_token'),
                            user_id: request.get('user_id'),
                            name: request.get('name'),
                            connection: request.get('connection'),
                            contact: request.get('contact'),
                        };

                        const id = $('#contact_id').val();

                        fetch(`/employee-emergency-contacts/${id}`, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify(data),
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.error) {
                                    if (data.message) {
                                        throw data.message;
                                    }

                                    $.each(data.error, (prefix, val) => {
                                        $(`#${prefix}`).addClass('is-invalid');
                                        $(`#${prefix}`).addClass('invalid-more');
                                        $(`.${prefix}_error`).text(val[0]);
                                    });

                                } else {
                                    setTimeout(() => {
                                        $("#table-contact").load(window.location.href +
                                            " #table-contact");
                                    }, 0);

                                    Swal.fire({
                                        type: 'success',
                                        title: 'Success!',
                                        text: data.message,
                                        confirmButtonClass: 'btn btn-success'
                                    });

                                    $('#modal-contact').modal('hide');
                                }
                            }).catch((error) => {
                                console.error('Error:', error);
                                Swal.fire({
                                    type: "error",
                                    title: 'Oops...',
                                    text: `${error}`,
                                    confirmButtonClass: 'btn btn-success',
                                });
                            });
                    } else {
                        submitContact();
                    }
                });
            });
        };

        const submitEditAdditional = () => {
            Array.prototype.filter.call($('#form_additional'), function(form) {
                $('#submitAdditional').unbind().on('click', function(event) {
                    if (form.checkValidity() === false) {
                        form.classList.add('invalid');
                    }

                    form.classList.add('was-validated');
                    event.preventDefault();

                    const formEditData = document.querySelector('#form_additional');
                    if (formEditData) {
                        const request = new FormData(formEditData);

                        const data = {
                            _token: request.get('_token'),
                            user_id: request.get('user_id'),
                            employee_id: request.get('employee_id'),
                            nik: request.get('nik'),
                            passport: request.get('passport'),
                            place_of_birth: request.get('place_of_birth'),
                            date_of_birth: request.get('date_of_birth'),
                            blood_type: request.get('blood_type'),
                            religion_id: request.get('religion_id'),
                            nationality: request.get('nationality'),
                            marital_status_id: request.get('marital_status_id'),
                            address: request.get('address'),
                        };
                        console.log(data);

                        const id = $('#employee_id').val();

                        fetch(`/employee-additional-information/${id}`, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify(data),
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.error) {
                                    if (data.message) {
                                        throw data.message;
                                    }

                                    $.each(data.error, (prefix, val) => {
                                        $(`#${prefix}`).addClass('is-invalid');
                                        $(`#${prefix}`).addClass('invalid-more');
                                        $(`.${prefix}_error`).text(val[0]);
                                    });

                                } else {
                                    setTimeout(() => {
                                        $("#card-additional").load(window.location.href +
                                            " #card-additional");
                                    }, 0);

                                    Swal.fire({
                                        type: 'success',
                                        title: 'Success!',
                                        text: data.message,
                                        confirmButtonClass: 'btn btn-success'
                                    });

                                    $('#modal-additional-information').modal('hide');
                                }
                            }).catch((error) => {
                                console.error('Error:', error);
                                Swal.fire({
                                    type: "error",
                                    title: 'Oops...',
                                    text: `${error}`,
                                    confirmButtonClass: 'btn btn-success',
                                });
                            });
                    } else {
                        submitAdditional();
                    }
                });
            });
        };

        const submit = () => {
            Array.prototype.filter.call($('#form_education'), function(form) {
                $('#submit').unbind().on('click', function(event) {
                    if (form.checkValidity() === false) {
                        form.classList.add('invalid');
                    }

                    form.classList.add('was-validated');
                    event.preventDefault();

                    const formData = document.querySelector('#form_education');
                    if (formData) {
                        const request = new FormData(formData);

                        const data = {
                            _token: request.get('_token'),
                            user_id: request.get('user_id'),
                            institution: request.get('institution'),
                            degree: request.get('degree'),
                            major: request.get('major'),
                            entry_level: request.get('entry_level'),
                            graduation_year: request.get('graduation_year'),
                            gpa: request.get('gpa'),
                        };

                        fetch('/employee-educations', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify(data),
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.error) {
                                    if (data.message) {
                                        throw data.message;
                                    }

                                    $.each(data.error, (prefix, val) => {
                                        $(`#${prefix}`).addClass('is-invalid');
                                        $(`#${prefix}`).addClass('invalid-more');
                                        $(`.${prefix}_error`).text(val[0]);
                                    });

                                } else {
                                    setTimeout(() => {
                                        $("#table-edu").load(window.location.href +
                                            " #table-edu");
                                    }, 0);

                                    Swal.fire({
                                        type: 'success',
                                        title: 'Success!',
                                        text: data.message,
                                        confirmButtonClass: 'btn btn-success'
                                    });

                                    $('#modal-education').modal('hide');
                                }
                            }).catch((error) => {
                                console.error('Error:', error);
                                Swal.fire({
                                    type: "error",
                                    title: 'Oops...',
                                    text: `${error}`,
                                    confirmButtonClass: 'btn btn-success',
                                });
                            });
                    } else {
                        submitEdit();
                    }
                });
            });
        };

        const submitExperience = () => {
            Array.prototype.filter.call($('#form_experience'), function(form) {
                $('#submitExperience').unbind().on('click', function(event) {
                    if (form.checkValidity() === false) {
                        form.classList.add('invalid');
                    }

                    form.classList.add('was-validated');
                    event.preventDefault();

                    const formData = document.querySelector('#form_experience');
                    if (formData) {
                        const request = new FormData(formData);

                        const data = {
                            _token: request.get('_token'),
                            user_id: request.get('user_id'),
                            corporate: request.get('corporate'),
                            position: request.get('position'),
                            years: request.get('years'),
                            description: request.get('description'),
                        };

                        fetch('/employee-experiences', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify(data),
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.error) {
                                    if (data.message) {
                                        throw data.message;
                                    }

                                    $.each(data.error, (prefix, val) => {
                                        $(`#${prefix}`).addClass('is-invalid');
                                        $(`#${prefix}`).addClass('invalid-more');
                                        $(`.${prefix}_error`).text(val[0]);
                                    });

                                } else {
                                    setTimeout(() => {
                                        $("#table-exp").load(window.location.href +
                                            " #table-exp");
                                    }, 0);

                                    Swal.fire({
                                        type: 'success',
                                        title: 'Success!',
                                        text: data.message,
                                        confirmButtonClass: 'btn btn-success'
                                    });

                                    $('#modal-exp').modal('hide');
                                }
                            }).catch((error) => {
                                console.error('Error:', error);
                                Swal.fire({
                                    type: "error",
                                    title: 'Oops...',
                                    text: `${error}`,
                                    confirmButtonClass: 'btn btn-success',
                                });
                            });
                    } else {
                        submitEditExperience();
                    }
                });
            });
        };

        const submitSkill = () => {
            Array.prototype.filter.call($('#form_skill'), function(form) {
                $('#submitSkill').unbind().on('click', function(event) {
                    if (form.checkValidity() === false) {
                        form.classList.add('invalid');
                    }

                    form.classList.add('was-validated');
                    event.preventDefault();

                    const formData = document.querySelector('#form_skill');
                    if (formData) {
                        const request = new FormData(formData);

                        const data = {
                            _token: request.get('_token'),
                            user_id: request.get('user_id'),
                            skill_name: request.get('skill_name'),
                            issued_by: request.get('issued_by'),
                            issued_date: request.get('issued_date'),
                            exp_date: request.get('exp_date'),
                            credentials: request.get('credentials'),
                            is_null: request.get('is_null'),
                            tags: request.get('tags'),
                        };

                        fetch('/employee-skills', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify(data),
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.error) {
                                    if (data.message) {
                                        throw data.message;
                                    }

                                    $.each(data.error, (prefix, val) => {
                                        $(`#${prefix}`).addClass('is-invalid');
                                        $(`#${prefix}`).addClass('invalid-more');
                                        $(`.${prefix}_error`).text(val[0]);
                                    });

                                    // const error = data.error.work_shift_name ? true : false

                                    // if (error) {
                                    //     $('#work_shift_name').removeClass('was-validated');
                                    //     $('#work_shift_name').addClass('is-invalid');
                                    //     $('#work_shift_name').addClass('invalid-more');
                                    // } else {
                                    //     $('#work_shift_name').removeClass('is-invalid');
                                    //     $('#work_shift_name').removeClass('invalid-more');
                                    // }
                                } else {
                                    setTimeout(() => {
                                        $("#table-skill").load(window.location.href +
                                            " #table-skill");
                                    }, 0);

                                    Swal.fire({
                                        type: 'success',
                                        title: 'Success!',
                                        text: data.message,
                                        confirmButtonClass: 'btn btn-success'
                                    });

                                    $('#modal-skill').modal('hide');
                                }
                            }).catch((error) => {
                                console.error('Error:', error);
                                Swal.fire({
                                    type: "error",
                                    title: 'Oops...',
                                    text: `${error}`,
                                    confirmButtonClass: 'btn btn-success',
                                });
                            });
                    } else {
                        submitEditSkill();
                    }
                });
            });
        };

        const submitContact = () => {
            Array.prototype.filter.call($('#form_contact'), function(form) {
                $('#submitContact').unbind().on('click', function(event) {
                    if (form.checkValidity() === false) {
                        form.classList.add('invalid');
                    }

                    form.classList.add('was-validated');
                    event.preventDefault();

                    const formData = document.querySelector('#form_contact');
                    if (formData) {
                        const request = new FormData(formData);

                        const data = {
                            _token: request.get('_token'),
                            user_id: request.get('user_id'),
                            name: request.get('name'),
                            connection: request.get('connection'),
                            contact: request.get('contact'),
                        };

                        fetch('/employee-emergency-contacts', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify(data),
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.error) {
                                    if (data.message) {
                                        throw data.message;
                                    }

                                    $.each(data.error, (prefix, val) => {
                                        $(`#${prefix}`).addClass('is-invalid');
                                        $(`#${prefix}`).addClass('invalid-more');
                                        $(`.${prefix}_error`).text(val[0]);
                                    });

                                } else {
                                    setTimeout(() => {
                                        $("#table-contact").load(window.location.href +
                                            " #table-contact");
                                    }, 0);

                                    Swal.fire({
                                        type: 'success',
                                        title: 'Success!',
                                        text: data.message,
                                        confirmButtonClass: 'btn btn-success'
                                    });

                                    $('#modal-contact').modal('hide');
                                }
                            }).catch((error) => {
                                console.error('Error:', error);
                                Swal.fire({
                                    type: "error",
                                    title: 'Oops...',
                                    text: `${error}`,
                                    confirmButtonClass: 'btn btn-success',
                                });
                            });
                    } else {
                        submitEditContact();
                    }
                });
            });
        };

        const sweetConfirm = (id) => {
            event.preventDefault(); // prevent form submit
            const form = event.target.form; // storing the form
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
                    const request = new FormData(document.getElementById('form_delete_education'));
                    const data = {
                        _token: request.get('_token'),
                    };

                    fetch(`/employee-educations/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify(data),
                        })
                        .then(response => response.json())
                        .then(data => {
                            setTimeout(function() {
                                $("#table-edu").load(window.location.href +
                                    " #table-edu");
                            }, 0);

                            Swal.fire({
                                type: 'success',
                                title: 'Success!',
                                text: data.message,
                                confirmButtonClass: 'btn btn-success',
                            });

                            $('#modal-education').modal('hide');
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
        };

        const sweetExperienceConfirm = (id) => {
            event.preventDefault(); // prevent form submit
            const form = event.target.form; // storing the form
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
                    const request = new FormData(document.getElementById('form_delete_experience'));
                    const data = {
                        _token: request.get('_token'),
                    };

                    fetch(`/employee-experiences/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify(data),
                        })
                        .then(response => response.json())
                        .then(data => {
                            setTimeout(function() {
                                $("#table-exp").load(window.location.href +
                                    " #table-exp");
                            }, 0);

                            Swal.fire({
                                type: 'success',
                                title: 'Success!',
                                text: data.message,
                                confirmButtonClass: 'btn btn-success',
                            });

                            $('#modal-exp').modal('hide');
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
        };

        const sweetSkillConfirm = (id) => {
            event.preventDefault(); // prevent form submit
            const form = event.target.form; // storing the form
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
                    const request = new FormData(document.getElementById('form_delete_skill'));
                    const data = {
                        _token: request.get('_token'),
                    };

                    fetch(`/employee-skills/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify(data),
                        })
                        .then(response => response.json())
                        .then(data => {
                            setTimeout(function() {
                                $("#table-skill").load(window.location.href +
                                    " #table-skill");
                            }, 0);

                            Swal.fire({
                                type: 'success',
                                title: 'Success!',
                                text: data.message,
                                confirmButtonClass: 'btn btn-success',
                            });

                            $('#modal-skill').modal('hide');
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
        };

        const sweetContactConfirm = (id) => {
            event.preventDefault(); // prevent form submit
            const form = event.target.form; // storing the form
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
                    const request = new FormData(document.getElementById('form_delete_contact'));
                    const data = {
                        _token: request.get('_token'),
                    };

                    fetch(`/employee-emergency-contacts/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify(data),
                        })
                        .then(response => response.json())
                        .then(data => {
                            setTimeout(function() {
                                $("#table-contact").load(window.location.href +
                                    " #table-contact");
                            }, 0);

                            Swal.fire({
                                type: 'success',
                                title: 'Success!',
                                text: data.message,
                                confirmButtonClass: 'btn btn-success',
                            });

                            $('#modal-contact').modal('hide');
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
        };

        const profil = () => {
            const ajaxProfile = $("#ajax-profile");
            console.log(ajaxProfile.length);
            if (ajaxProfile.length) {
                const dt_ajax = ajaxProfile.dataTable({
                    processing: true,
                    dom: '<"d-flex justify-content-between align-items-end mx-0 row"<"col"l><"col-md-auto"f><"col col-lg-2 p-0.">>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    ajax: `/profile-all-list?user_id={{ $user->id }}`,
                    ordering: false,
                    scrollCollapse: true,
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
                                return ` <b> ${data.first_name} ${data.last_name} </b>
                                </br> <span>135346378894</span>`
                            },
                            name: 'Employee Name'
                        },
                        {
                            data: 'job_position',
                            name: 'Job Position'
                        },
                        {
                            data: 'phone',
                            name: 'Phone'
                        },
                        {
                            data: (data) => ` ${data.email} `,
                            name: 'Email'
                        },
                    ],
                });
            }
        };

        let branch = '';
        let dep = '';

        const selectCompany = () => {
            const companyId = document.querySelector('#company_id');
            const branchId = document.querySelector('#branch_id');
            const departmentId = document.querySelector('#department_id');
            if (companyId.value != '') {
                fetch(`/profile?company=${companyId.value}`)
                    .then(response => response.json())
                    .then(response => {
                        branchId.removeAttribute('disabled');
                        console.log(response);

                        if (branchId.length > 0) {
                            for (let i = 0; i < branchId.length; i++) {
                                $('#branch_id')
                                    .find('option')
                                    .remove();
                            }
                        }

                        const opt = document.createElement('option');
                        opt.value = '';
                        opt.selected = 'selected';
                        opt.disabled = 'disabled';
                        opt.innerHTML = 'Select Entity';
                        branchId.appendChild(opt);
                        if (response.branches.length > 0) {
                            response.branches.forEach((item, index) => {
                                const opt = document.createElement('option');
                                opt.value = item.branch_id;
                                opt.innerHTML = item.branch_name;
                                branchId.appendChild(opt);
                            });
                        }

                        const opt2 = document.createElement('option');
                        opt2.value = '';
                        opt2.selected = 'selected';
                        opt2.disabled = 'disabled';
                        opt2.innerHTML = 'Select Job Position';
                        departmentId.appendChild(opt2);
                        departmentId.setAttribute('disabled', 'disabled');
                    });
            }
        };

        const selectBranch = () => {
            const branchId = document.querySelector('#branch_id');
            const departmentId = document.querySelector('#department_id');
            if (branchId.value != '') {
                fetch(`/profile?branch=${branchId.value}`)
                    .then(response => response.json())
                    .then(response => {
                        departmentId.removeAttribute('disabled');
                        console.log(response);

                        if (departmentId.length > 0) {
                            for (let i = 0; i < departmentId.length; i++) {
                                $('#department_id')
                                    .find('option')
                                    .remove();
                            }
                        }

                        const opt = document.createElement('option');
                        opt.value = '';
                        opt.disabled = 'disabled';
                        opt.selected = 'selected';
                        opt.innerHTML = 'Select Entity';
                        departmentId.appendChild(opt);
                        if (response.departments.length > 0) {
                            response.departments.forEach((item, index) => {
                                const opt = document.createElement('option');
                                opt.value = item.department_id;
                                opt.innerHTML = item.department_name;
                                departmentId.appendChild(opt);
                            });
                        }
                    });
            }
        };

        const selectRole = () => {
            const role = document.querySelector('#role');
            const jobClassId = document.querySelector('#job_class_id');
            const jobPositionId = document.querySelector('#job_position_id');
            if (role.value != '') {
                fetch(`/profile?role=${role.value}`)
                    .then(response => response.json())
                    .then(response => {
                        jobClassId.removeAttribute('disabled');
                        console.log(response);

                        if (jobClassId.length > 0) {
                            for (let i = 0; i < jobClassId.length; i++) {
                                $('#job_class_id')
                                    .find('option')
                                    .remove();
                            }
                        }

                        const opt = document.createElement('option');
                        opt.value = '';
                        opt.disabled = 'disabled';
                        opt.selected = 'selected';
                        opt.innerHTML = 'Select Job Level';
                        jobClassId.appendChild(opt);
                        if (response.jobClasses.length > 0) {
                            response.jobClasses.forEach((item, index) => {
                                const opt = document.createElement('option');
                                opt.value = item.job_class_id;
                                opt.innerHTML = item.job_class;
                                jobClassId.appendChild(opt);
                            });
                        }

                        const opt2 = document.createElement('option');
                        opt2.value = '';
                        opt2.selected = 'selected';
                        opt2.disabled = 'disabled';
                        opt2.innerHTML = 'Select Job Position';
                        jobPositionId.appendChild(opt2);
                        jobPositionId.setAttribute('disabled', 'disabled');
                    });
            }
        };

        const selectJobClass = () => {
            const jobClassId = document.querySelector('#job_class_id');
            const jobPositionId = document.querySelector('#job_position_id');
            if (jobClassId.value != '') {
                fetch(`/profile?jobclass=${jobClassId.value}`)
                    .then(response => response.json())
                    .then(response => {
                        jobPositionId.removeAttribute('disabled');
                        console.log(response);

                        if (jobPositionId.length > 0) {
                            for (let i = 0; i < jobPositionId.length; i++) {
                                $('#job_position_id')
                                    .find('option')
                                    .remove();
                            }
                        }

                        const opt = document.createElement('option');
                        opt.value = '';
                        opt.disabled = 'disabled';
                        opt.selected = 'selected';
                        opt.innerHTML = 'Select Job Position';
                        jobPositionId.appendChild(opt);
                        if (response.jobPositions.length > 0) {
                            response.jobPositions.forEach((item, index) => {
                                const opt = document.createElement('option');
                                opt.value = item.job_position_id;
                                opt.innerHTML = item.job_position;
                                jobPositionId.appendChild(opt);
                            });
                        }
                    });
            }
        };
    </script>
@endsection

@endsection
