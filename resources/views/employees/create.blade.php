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
                            <h3>Add Employee</h3>
                        </div>
                        <div class="bs-stepper-header">
                            <div class="step" data-target="#account-details">
                                <button type="button" class="step-trigger first-st active-step-trigger">
                                    <span class="bs-stepper-box first-bsb active-bs-stepper-box">
                                        <span class="bs-stepper-number first-bsn active-bs-stepper-number">1</span>
                                    </span>
                                    <span class="bs-stepper-label">
                                        <span class="bs-stepper-title first-bst active-bs-stepper-title">Personal
                                            Information</span>
                                    </span>
                                </button>
                            </div>
                            <span class="first-middle-line active-middle-line"></span>
                            <div class="step" data-target="#personal-info">
                                <button type="button" class="step-trigger second-st">
                                    <span class="bs-stepper-box second-bsb">
                                        <span class="bs-stepper-number second-bsn">2</span>
                                    </span>
                                    <span class="bs-stepper-label">
                                        <span class="bs-stepper-title second-bst">Employment Data</span>
                                    </span>
                                </button>
                            </div>
                            <span class="second-middle-line"></span>
                            <div class="step" data-target="#social-links">
                                <button type="button" class="step-trigger third-st">
                                    <span class="bs-stepper-box third-bsb">
                                        <span class="bs-stepper-number third-bsn">3</span>
                                    </span>
                                    <span class="bs-stepper-label">
                                        <span class="bs-stepper-title third-bst">Payroll</span>
                                    </span>
                                </button>
                            </div>
                        </div>
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="bs-stepper-content">
                            <div id="account-details" class="content">
                                <form id="form_data" class="form-data-validate first-page" novalidate>
                                    @csrf
                                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                                    <div class="row">
                                        <div class="form-group form-password-toggle col-md-3">
                                            <label class="form-label" for="first_name">Full Name<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="first_name" id="first_name" class="form-control"
                                                placeholder="First name" onkeyup="saveValue(this)" required />
                                            <div class="invalid-feedback first_name_error"></div>
                                        </div>
                                        <div class="form-group form-password-toggle col-md-3">
                                            <label class="form-label" for="last_name"></label>
                                            <input type="text" name="last_name" id="last_name" class="form-control"
                                                placeholder="Last name" onkeyup="saveValue(this)" required />
                                            <div class="invalid-feedback last_name_error"></div>
                                        </div>
                                        <div class="form-group form-password-toggle col-md-6">
                                            <label class="form-label" for="gender">Gender<span
                                                    class="text-danger">*</span></label>
                                            <select name="gender" id="gender" class="form-control"
                                                onchange="saveValue(this)" required>
                                                <option hidden disabled selected value>Select Gender</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
                                            <div class="invalid-feedback gender_error"></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label class="form-label" for="email">Email<span
                                                    class="text-danger">*</span></label>
                                            <input type="email" name="email" id="email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                placeholder="Input your email address" onkeyup="saveValue(this)" required />
                                            <div class="invalid-feedback email_error"></div>
                                            <label for="email" id="email-message"></label>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="form-label" for="phone">Mobile Phone<span
                                                    class="text-danger">*</span></label>
                                            <input type="number" name="phone" id="phone" class="form-control"
                                                placeholder="Input your mobile phone number" minlength="10"
                                                maxlength="13" onkeyup="saveValue(this)" required />
                                            <div class="invalid-feedback phone_error"></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group form-password-toggle col-md-6">
                                            <label class="form-label" for="password">Password<span
                                                    class="text-danger">*</span></label>
                                            <input type="password" name="password" id="password" class="form-control"
                                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                                onkeyup="saveValue(this); passwordStrengthChecker(this.value)" />
                                            <label for="password" id="password-message"></label>
                                        </div>
                                        <div class="form-group form-password-toggle col-md-6">
                                            <label class="form-label" for="confirm-password">Confirm Password<span
                                                    class="text-danger">*</span></label>
                                            <input type="password" name="confirm-password" id="confirm-password"
                                                class="form-control"
                                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                                onkeyup="saveValue(this)" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label class="form-label" for="place_of_birth">Place of Birth<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="place_of_birth" id="place_of_birth"
                                                class="form-control" placeholder="Input your birth place"
                                                onkeyup="saveValue(this)" required />
                                            <div class="invalid-feedback place_of_birth_error"></div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="form-label" for="marital_status_id">Marital Status<span
                                                    class="text-danger">*</span></label>
                                            <select name="marital_status_id" id="marital_status_id" class="form-control"
                                                onchange="saveValue(this)" required>
                                                <option hidden disabled selected value>Select Marital Status</option>
                                                @foreach ($maritalStatuses as $maritalStatus)
                                                    <option value="{{ $maritalStatus->marital_status_id }}">
                                                        {{ $maritalStatus->marital_status }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback marital_status_id_error"></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label class="form-label" for="date_of_birth">Date of Birth<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="date_of_birth" id="date_of_birth"
                                                class="form-control bg-white" placeholder="Select your birth date"
                                                onchange="saveValue(this)" required />
                                            <div class="invalid-feedback date_of_birth_error"></div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="form-label" for="age">Age</label>
                                            <input type="text" name="age" id="age"
                                                class="form-control bg-white" placeholder="0" onchange="saveValue(this)"
                                                required disabled />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label class="form-label" for="religion_id">Religion<span
                                                    class="text-danger">*</span></label>
                                            <select name="religion_id" id="religion_id" class="form-control"
                                                onchange="saveValue(this)" required>
                                                <option hidden disabled selected value>Select Religion</option>
                                                @foreach ($religions as $religion)
                                                    <option value="{{ $religion->religion_id }}">
                                                        {{ $religion->religion }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback religion_id_error"></div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="form-label" for="blood_type">Blood Type<span
                                                    class="text-danger">*</span></label>
                                            <select name="blood_type" id="blood_type" class="form-control"
                                                onchange="saveValue(this)" required>
                                                <option hidden disabled selected value>Select Blood Type</option>
                                                <option value="A">A</option>
                                                <option value="B">B</option>
                                                <option value="AB">AB</option>
                                                <option value="O">O</option>
                                            </select>
                                            <div class="invalid-feedback blood_type_error"></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label class="form-label" for="nik">NIK<span
                                                    class="text-danger">*</span></label>
                                            <input type="number" name="nik" id="nik" class="form-control"
                                                placeholder="Input your NIK" minlength="16" maxlength="16"
                                                onkeyup="saveValue(this)" required />
                                            <div class="invalid-feedback nik_error"></div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="form-label" for="passport">Passport Number</label>
                                            <input type="number" name="passport" id="passport" class="form-control"
                                                placeholder="Input your passport number" minlength="7" maxlength="7"
                                                onkeyup="saveValue(this)" />
                                            <div class="invalid-feedback passport_error"></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label class="form-label" for="address">Address<span
                                                    class="text-danger">*</span></label>
                                            <textarea name="address" id="address" cols="30" rows="5" class="form-control"
                                                placeholder="Input your address" style="resize: none" onkeyup="saveValue(this)" required></textarea>
                                            <div class="invalid-feedback address_error"></div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="form-label" for="postal_code">Postal Code<span
                                                    class="text-danger">*</span></label>
                                            <input type="number" name="postal_code" id="postal_code"
                                                class="form-control" placeholder="0" minlength="5" maxlength="5"
                                                onkeyup="saveValue(this)" required />
                                            <div class="invalid-feedback postal_code_error"></div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="content-header">
                                        <div class="content-header">
                                            <h5 class="mb-0">Social Media</h5>
                                            <small class="text-muted">Employee social media information</small>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label class="form-label" for="facebook_link">Facebook</label>
                                                <input type="text" name="facebook_link" id="facebook_link"
                                                    class="form-control" placeholder="Input your Facebook link"
                                                    onkeyup="saveValue(this)" />
                                                <div class="invalid-feedback facebook_link_error"></div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="form-label" for="instagram_link">Instagram</label>
                                                <input type="text" name="instagram_link" id="instagram_link"
                                                    class="form-control" placeholder="Input your Instagram link"
                                                    onkeyup="saveValue(this)" />
                                                <div class="invalid-feedback instagram_link_error"></div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label class="form-label" for="linkedin_link">LinkedIn</label>
                                                <input type="text" name="linkedin_link" id="linkedin_link"
                                                    class="form-control" placeholder="Input your LinkedIn link"
                                                    onkeyup="saveValue(this)" />
                                                <div class="invalid-feedback linkedin_link_error"></div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div>
                                    <a href="{{ url('employees') }}" class="btn btn-prev">
                                        <span class="align-middle d-sm-inline-block d-none">Cancel</span>
                                    </a>
                                    <button class="btn btn-primary btn-next first-to-second">
                                        <span class="align-middle d-sm-inline-block d-none">Next</span>
                                    </button>
                                </div>
                            </div>
                            <div id="personal-info" class="content">
                                <form id="form_data" class="form-data-validate second-page" novalidate>
                                    @csrf
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label class="form-label" for="company_id">Holding Company<span
                                                    class="text-danger">*</span></label>
                                            <select name="company_id" id="company_id"
                                                class="select2-data-ajax custom-select"
                                                onchange="selectCompany(); saveValue(this)" required>
                                                <option hidden disabled selected value>Select Holding Company</option>
                                                @foreach ($companies as $company)
                                                    <option value="{{ $company->company_id }}">
                                                        {{ $company->company_name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback company_id_error"></div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="form-label" for="branch_id">Entity<span
                                                    class="text-danger">*</span></label>
                                            <select name="branch_id" id="branch_id" class="form-control"
                                                onchange="selectBranch(); saveValue(this);" required disabled>
                                                <option selected disabled value="">Select Entity</option>
                                            </select>
                                            <div class="invalid-feedback branch_id_error"></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label class="form-label" for="department_id">SBU<span
                                                    class="text-danger">*</span></label>
                                            <select name="department_id" id="department_id" class="form-control"
                                                onchange="saveValue(this);" required disabled>
                                                <option selected disabled value="">Select SBU</option>
                                            </select>
                                            <div class="invalid-feedback department_id_error"></div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="form-label" for="employment_status_id">Employment Status<span
                                                    class="text-danger">*</span></label>
                                            <select name="employment_status_id" id="employment_status_id"
                                                class="form-control" onchange="saveValue(this)" required>
                                                <option hidden disabled selected value>Select Employment Status</option>
                                                @foreach ($employmentStatuses as $employmentStatus)
                                                    <option value="{{ $employmentStatus->employment_status_id }}">
                                                        {{ $employmentStatus->employment_status }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback employment_status_id_error"></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label class="form-label" for="role_id">Roles<span
                                                    class="text-danger">*</span></label>
                                            <select name="role_id" id="role_id" class="form-control"
                                                onchange="selectRole(); saveValue(this)" required>
                                                <option hidden disabled selected value>Select Roles</option>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback role_id_error"></div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="form-label" for="job_class_id">Job Level<span
                                                    class="text-danger">*</span></label>
                                            <select name="job_class_id" id="job_class_id" class="form-control"
                                                onchange="selectJobClass(); saveValue(this)" required disabled>
                                                <option hidden disabled selected value>Select Job Level</option>
                                            </select>
                                            <div class="invalid-feedback job_class_id_error"></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label class="form-label" for="job_position_id">Job Position<span
                                                    class="text-danger">*</span></label>
                                            <select name="job_position_id" id="job_position_id" class="form-control"
                                                onchange="saveValue(this)" required disabled>
                                                <option hidden disabled selected value>Select Job Position</option>
                                            </select>
                                            <div class="invalid-feedback job_position_id_error"></div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="form-label" for="work_shift_id">Work Shift<span
                                                    class="text-danger">*</span></label>
                                            <select name="work_shift_id" id="work_shift_id" class="form-control"
                                                onchange="saveValue(this)" required disabled>
                                                <option hidden disabled selected value>Select Work Shift</option>
                                            </select>
                                            <div class="invalid-feedback work_shift_id_error"></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label class="form-label" for="date_of_joining">Join Date<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="date_of_joining" id="date_of_joining"
                                                class="form-control bg-white" placeholder="Select your date of joining"
                                                onchange="saveValue(this)" required />
                                            <div class="invalid-feedback date_of_joining_error"></div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="form-label" for="status">Status<span
                                                    class="text-danger">*</span></label>
                                            <select name="status" id="status" class="form-control"
                                                onchange="saveValue(this)" required>
                                                <option hidden disabled selected value>Select Status</option>
                                                <option value="1">Active</option>
                                                <option value="0">Not Active</option>
                                            </select>
                                            <div class="invalid-feedback status_error"></div>
                                        </div>
                                    </div>
                                </form>
                                <div>
                                    <button class="btn btn-prev second-to-first">
                                        <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                    </button>
                                    <button class="btn btn-primary btn-next second-to-third">
                                        <span class="align-middle d-sm-inline-block d-none">Next</span>
                                    </button>
                                </div>
                            </div>
                            <div id="social-links" class="content">
                                <form id="form_data" class="form-data-validate third-page" novalidate>
                                    @csrf
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label class="form-label" for="basic_salary">Basic Salary<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="basic_salary" id="basic_salary"
                                                class="form-control" placeholder="Rp0" onkeyup="saveValue(this)"
                                                required />
                                            <div class="invalid-feedback basic_salary_error"></div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="form-label" for="salary_type_id">Salary Type<span
                                                    class="text-danger">*</span></label>
                                            <select name="salary_type_id" id="salary_type_id" class="form-control"
                                                onchange="saveValue(this)" required>
                                                <option hidden disabled selected value>Select Salary Type</option>
                                                @foreach ($salaryTypes as $salaryType)
                                                    <option value="{{ $salaryType->salary_type_id }}">
                                                        {{ $salaryType->salary_type }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback salary_type_id_error"></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label class="form-label" for="bank_id">Bank Name<span
                                                    class="text-danger">*</span></label>
                                            <select name="bank_id" id="bank_id" class="form-control"
                                                onchange="saveValue(this)" required>
                                                <option hidden disabled selected value>Select Salary Type</option>
                                                @foreach ($banks as $bank)
                                                    <option value="{{ $bank->bank_id }}">{{ $bank->bank_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback bank_id_error"></div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="form-label" for="npwp">NPWP<span
                                                    class="text-danger">*</span></label>
                                            <input type="number" name="npwp" id="npwp" class="form-control"
                                                placeholder="0" minlength="15" maxlength="17" onkeyup="saveValue(this)"
                                                required />
                                            <div class="invalid-feedback npwp_error"></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label class="form-label" for="bank_account_number">Account Number<span
                                                    class="text-danger">*</span></label>
                                            <input type="number" name="bank_account_number" id="bank_account_number"
                                                class="form-control" placeholder="Input your bank account number"
                                                minlength="10" maxlength="16" onkeyup="saveValue(this)" required />
                                            <div class="invalid-feedback bank_account_number_error"></div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label class="form-label" for="bank_account_holder">Account Holder Name<span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="bank_account_holder" id="bank_account_holder"
                                                class="form-control" placeholder="Input your bank account holder name"
                                                onkeyup="saveValue(this)" required />
                                            <div class="invalid-feedback bank_account_holder_error"></div>
                                        </div>
                                    </div>
                                </form>
                                <div>
                                    <button class="btn btn-prev third-to-second">
                                        <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                    </button>
                                    <button type="submit" id="submit" class="btn btn-primary">Submit</button>
                                </div>
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
    <script>
        $(document).ready(() => {
            $('#gender').select2();
            $('#marital_status_id').select2();
            $('#religion_id').select2();
            $('#blood_type').select2();
            $('#company_id').select2();
            $('#branch_id').select2();
            $('#department_id').select2();
            $('#employment_status_id').select2();
            $('#role_id').select2();
            $('#job_class_id').select2();
            $('#job_position_id').select2();
            $('#work_shift_id').select2();
            $('#status').select2();
            $('#salary_type_id').select2();
            $('#bank_id').select2();
        });

        const firstSteps = ['.first-st', '.first-bsb', '.first-bsn', '.first-bst'];
        const secondSteps = ['.second-st', '.second-bsb', '.second-bsn', '.second-bst'];
        const thirdSteps = ['.third-st', '.third-bsb', '.third-bsn', '.third-bst'];
        const activeSteps = ['active-step-trigger', 'active-bs-stepper-box', 'active-bs-stepper-number',
            'active-bs-stepper-title'
        ];
        const doneSteps = ['done-step-trigger', 'done-bs-stepper-box', 'done-bs-stepper-number', 'done-bs-stepper-title'];

        $('.first-to-second').click(() => {
            if ($('.first-page').valid()) {
                $('.first-bsn').html('<i class="fa-solid fa-check"></i>');

                for (let i = 0; i < 4; i++) {
                    $(firstSteps[i]).removeClass(activeSteps[i]);
                    $(firstSteps[i]).addClass(doneSteps[i]);
                    $(secondSteps[i]).addClass(activeSteps[i]);
                }
            }
        });

        $('.second-to-first').click(() => {
            $('.first-bsn').html('1');

            for (let i = 0; i < 4; i++) {
                $(secondSteps[i]).removeClass(activeSteps[i]);
                $(firstSteps[i]).removeClass(doneSteps[i]);
                $(firstSteps[i]).addClass(activeSteps[i]);
            }
        });

        $('.second-to-third').click(() => {
            if ($('.second-page').valid()) {
                $('.second-bsn').html('<i class="fa-solid fa-check"></i>');
                $('.second-middle-line').addClass('active-middle-line');

                for (let i = 0; i < 4; i++) {
                    $(secondSteps[i]).removeClass(activeSteps[i]);
                    $(secondSteps[i]).addClass(doneSteps[i]);
                    $(thirdSteps[i]).addClass(activeSteps[i]);
                }
            }
        });

        $('.third-to-second').click(() => {
            $('.second-bsn').html('2');
            $('.second-middle-line').removeClass('active-middle-line');

            for (let i = 0; i < 4; i++) {
                $(thirdSteps[i]).removeClass(activeSteps[i]);
                $(secondSteps[i]).removeClass(doneSteps[i]);
                $(secondSteps[i]).addClass(activeSteps[i]);
            }
        });

        function passwordStrengthChecker(password) {
            if (password.length == 0) {
                $('#password-message').html('');
            }

            let message = '';

            if (password.length < 8 || !(new RegExp('[$@$!%*#?&]').test(password)) || !(new RegExp('[A-Z]').test(
                    password)) || !(new RegExp('[0-9]').test(password))) {
                message =
                    'The password must be at least 8 characters and contain at least 1 uppercase character, 1 special character, and 1 number.';
            }

            $('#password-message').html(message);
            $('#password-message').attr('style', 'color: red');
        }

        function getBranches(e) {
            $('#branch_id').empty();
            $('#branch_id').append('<option hidden disabled selected value>Select Entity</option>');

            $.ajax({
                url: "{{ url('employees/create/branch') }}/" + e.value,
                method: 'GET',
                dataType: 'json',
                success: data => {
                    data.branches.forEach(branch => {
                        $('#branch_id').append(
                            `<option value="${branch.branch_id}">${branch.branch_name}</option>`
                        );
                    });
                }
            });
        }

        function getDepartments(e) {
            $('#department_id').empty();
            $('#department_id').append('<option hidden disabled selected value>Select SBU</option>');

            $.ajax({
                url: "{{ url('employees/create/department') }}/" + e.value,
                method: 'GET',
                dataType: 'json',
                success: data => {
                    data.departments.forEach(department => {
                        $('#department_id').append(
                            `<option value="${department.department_id}">${department.department_name}</option>`
                        );
                    });
                }
            });
        }


        $('#basic_salary').keydown(function(e) {
            let ascii = e.which ? e.which : e.keyCode;

            if (ascii > 31 && (ascii < 48 || ascii > 57)) {
                return false;
            }
        });

        $('#basic_salary').keyup(function() {
            $('#basic_salary')[0].value = rupiahFormat(this.value, 'Rp');
        });

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

        $(document).ready(function() {
            let dateOfBirth = flatpickr("#date_of_birth", {
                altInput: true,
                altFormat: "d/m/Y",
                onClose: function(selectedDates, dateStr, instance) {
                    let date_of_birth = $('#date_of_birth')[0].value;

                    if (date_of_birth) {
                        function getAge(dateString) {
                            let today = new Date();
                            let birthDate = new Date(dateString);
                            let age = today.getFullYear() - birthDate.getFullYear();
                            let month = today.getMonth() - birthDate.getMonth();

                            if (month < 0 || (month === 0 && today.getDate() < birthDate.getDate())) {
                                age--;
                            }

                            return age;
                        }

                        if (getAge(date_of_birth) < 18) {
                            alert('You must be at least 18 years old.');
                            dateOfBirth.clear();
                        } else {
                            let newAge = $('#age')[0];
                            newAge.value = getAge(date_of_birth);
                            newAge.dispatchEvent(new Event('change'));
                        }
                    }
                }
            });

            flatpickr("#date_of_joining", {
                altInput: true,
                altFormat: "d/m/Y"
            });

            $('#submit').click((e) => {
                e.preventDefault();

                if ($('.third-page').valid()) {
                    $.ajax({
                        url: "{{ url('employees') }}",
                        method: 'POST',
                        data: {
                            _token: $('#token').val(),
                            role_id: $('#role_id').val(),
                            first_name: $('#first_name').val(),
                            last_name: $('#last_name').val(),
                            email: $('#email').val(),
                            password: $('#password').val(),
                            place_of_birth: $('#place_of_birth').val(),
                            date_of_birth: $('#date_of_birth').val(),
                            gender: $('#gender').val(),
                            blood_type: $('#blood_type').val(),
                            nik: $('#nik').val(),
                            religion_id: $('#religion_id').val(),
                            marital_status_id: $('#marital_status_id').val(),
                            address: $('#address').val(),
                            postal_code: $('#postal_code').val(),
                            phone: $('#phone').val(),
                            passport: $('#passport').val(),
                            facebook_link: $('#facebook_link').val(),
                            instagram_link: $('#instagram_link').val(),
                            twitter_link: $('#twitter_link').val(),
                            linkedin_link: $('#linkedin_link').val(),
                            github_link: $('#github_link').val(),
                            company_id: $('#company_id').val(),
                            branch_id: $('#branch_id').val(),
                            department_id: $('#department_id').val(),
                            job_class_id: $('#job_class_id').val(),
                            work_shift_id: $('#work_shift_id').val(),
                            job_position_id: $('#job_position_id').val(),
                            employment_status_id: $('#employment_status_id').val(),
                            date_of_joining: $('#date_of_joining').val(),
                            status: $('#status').val(),
                            basic_salary: $('#basic_salary').val(),
                            salary_type_id: $('#salary_type_id').val(),
                            bank_id: $('#bank_id').val(),
                            bank_account_number: $('#bank_account_number').val(),
                            bank_account_holder: $('#bank_account_holder').val(),
                            npwp: $('#npwp').val()
                        },
                        success: (result) => {
                            if (result.error) {
                                // $.each(response.error, function(prefix, val) {
                                //     $('div.' + prefix + '_error').text(val[0]);
                                // });
                                console.log(result.message);
                                Swal.fire({
                                    type: "error",
                                    title: 'Oops...',
                                    text: result.message,
                                    confirmButtonClass: 'btn btn-primary',
                                })

                                let fields = ["first_name", "last_name", "email",
                                    "password", "place_of_birth", "date_of_birth", "gender",
                                    "blood_type", "nik", "religion_id", "marital_status_id",
                                    "address",
                                    "postal_code", "phone", "passport", "facebook_link",
                                    "instagram_link", "twitter_link", "linkedin_link",
                                    "github_link", "company_id", "branch_id",
                                    "department_id", "job_class_id", "work_shift_id",
                                    "job_position_id", "work_shift_id",
                                    "employment_status_id", "date_of_joining", "status",
                                    "basic_salary", "salary_type_id", "bank_id",
                                    "bank_account_number", "bank_account_holder", "npwp"
                                ]

                                for (let i = 0; i < fields.length; i++) {
                                    let error = (response.error.hasOwnProperty(fields[i])) ?
                                        true : false
                                    if (!error) {
                                        $("#" + fields[i]).removeClass("is-invalid");
                                        $("#" + fields[i]).removeClass("invalid-more");
                                    } else {
                                        $("#" + fields[i]).removeClass('was-validated');
                                        $("#" + fields[i]).addClass("is-invalid");
                                        $("#" + fields[i]).addClass("invalid-more");
                                    }
                                }
                            } else {
                                Swal.fire({
                                    type: 'success',
                                    title: 'Success!',
                                    text: result.message,
                                    confirmButtonClass: 'btn btn-success',
                                    timer: 3000
                                }).then(() => window.location =
                                    "{{ url('employees') }}");
                            }
                        }
                    });
                }
            });
        });

        const pageAccessedByReload = (
            (window.performance.navigation && window.performance.navigation.type === 1) ||
            window.performance
            .getEntriesByType('navigation')
            .map((nav) => nav.type)
            .includes('reload')
        );

        if (pageAccessedByReload) {
            $('#marital_status_id')[0].value = getSavedValue('marital_status_id');
            $('#first_name')[0].value = getSavedValue('first_name');
            $('#last_name')[0].value = getSavedValue('last_name');
            $('#gender')[0].value = getSavedValue('gender');
            $('#email')[0].value = getSavedValue('email');
            $('#phone')[0].value = getSavedValue('phone');
            $('#password')[0].value = getSavedValue('password');
            $('#confirm-password')[0].value = getSavedValue('confirm-password');
            $('#religion_id')[0].value = getSavedValue('religion_id');
            $('#blood_type')[0].value = getSavedValue('blood_type');
            $('#place_of_birth')[0].value = getSavedValue('place_of_birth');
            $('#date_of_birth')[0].value = getSavedValue('date_of_birth');
            $('#age')[0].value = getSavedValue('age');
            $('#nik')[0].value = getSavedValue('nik');
            $('#passport')[0].value = getSavedValue('passport');
            $('#address')[0].value = getSavedValue('address');
            $('#postal_code')[0].value = getSavedValue('postal_code');
            $('#facebook_link')[0].value = getSavedValue('facebook_link');
            $('#instagram_link')[0].value = getSavedValue('instagram_link');
            $('#linkedin_link')[0].value = getSavedValue('linkedin_link');
            // $('#company_id')[0].value = getSavedValue('company_id');
            // $('#department_id')[0].value = getSavedValue('department_id');
            // $('#branch_id')[0].value = getSavedValue('branch_id');
            $('#employment_status_id')[0].value = getSavedValue('employment_status_id');
            // $('#role_id')[0].value = getSavedValue('role_id');
            // $('#job_class_id')[0].value = getSavedValue('job_class_id');
            // $('#job_position_id')[0].value = getSavedValue('job_position_id');
            // $('#work_shift_id')[0].value = getSavedValue('work_shift_id');
            $('#date_of_joining')[0].value = getSavedValue('date_of_joining');
            $('#status')[0].value = getSavedValue('status');
            $('#basic_salary')[0].value = getSavedValue('basic_salary');
            $('#salary_type_id')[0].value = getSavedValue('salary_type_id');
            $('#bank_id')[0].value = getSavedValue('bank_id');
            $('#npwp')[0].value = getSavedValue('npwp');
            $('#bank_account_number')[0].value = getSavedValue('bank_account_number');
            $('#bank_account_holder')[0].value = getSavedValue('bank_account_holder');

            function saveValue(e) {
                const id = e.id;
                const val = e.value;

                sessionStorage.setItem(id, val);
            }

            function getSavedValue(v) {
                if (!sessionStorage.getItem(v)) {
                    return '';
                }

                return sessionStorage.getItem(v);
            }
        } else {
            sessionStorage.clear();

            $('#role_id')[0].value = getSavedValue('role_id');
            $('#marital_status_id')[0].value = getSavedValue('marital_status_id');
            $('#first_name')[0].value = getSavedValue('first_name');
            $('#last_name')[0].value = getSavedValue('last_name');
            $('#gender')[0].value = getSavedValue('gender');
            $('#email')[0].value = getSavedValue('email');
            $('#phone')[0].value = getSavedValue('phone');
            $('#password')[0].value = getSavedValue('password');
            $('#confirm-password')[0].value = getSavedValue('confirm-password');
            $('#religion_id')[0].value = getSavedValue('religion_id');
            $('#blood_type')[0].value = getSavedValue('blood_type');
            $('#place_of_birth')[0].value = getSavedValue('place_of_birth');
            $('#date_of_birth')[0].value = getSavedValue('date_of_birth');
            $('#age')[0].value = getSavedValue('age');
            $('#nik')[0].value = getSavedValue('nik');
            $('#passport')[0].value = getSavedValue('passport');
            $('#address')[0].value = getSavedValue('address');
            $('#postal_code')[0].value = getSavedValue('postal_code');
            $('#facebook_link')[0].value = getSavedValue('facebook_link');
            $('#instagram_link')[0].value = getSavedValue('instagram_link');
            $('#twitter_link')[0].value = getSavedValue('twitter_link');
            $('#linkedin_link')[0].value = getSavedValue('linkedin_link');
            $('#github_link')[0].value = getSavedValue('github_link');
            $('#company_id')[0].value = getSavedValue('company_id');
            $('#department_id')[0].value = getSavedValue('department_id');
            $('#branch_id')[0].value = getSavedValue('branch_id');
            $('#employment_status_id')[0].value = getSavedValue('employment_status_id');
            $('#job_class_id')[0].value = getSavedValue('job_class_id');
            $('#job_position_id')[0].value = getSavedValue('job_position_id');
            $('#work_shift_id')[0].value = getSavedValue('work_shift_id');
            $('#date_of_joining')[0].value = getSavedValue('date_of_joining');
            $('#status')[0].value = getSavedValue('status');
            $('#basic_salary')[0].value = getSavedValue('basic_salary');
            $('#salary_type_id')[0].value = getSavedValue('salary_type_id');
            $('#bank_id')[0].value = getSavedValue('bank_id');
            $('#npwp')[0].value = getSavedValue('npwp');
            $('#bank_account_number')[0].value = getSavedValue('bank_account_number');
            $('#bank_account_holder')[0].value = getSavedValue('bank_account_holder');

            function saveValue(e) {
                const id = e.id;
                const val = e.value;

                sessionStorage.setItem(id, val);
            }

            function getSavedValue(v) {
                if (!sessionStorage.getItem(v)) {
                    return '';
                }

                return sessionStorage.getItem(v);
            }
        }
    </script>
    <script>
        let branch = '';
        let dep = '';

        const selectCompany = () => {
            const companyId = document.querySelector('#company_id');
            const branchId = document.querySelector('#branch_id');
            const departmentId = document.querySelector('#department_id');
            const workShiftId = document.querySelector('#work_shift_id');
            if (companyId.value != '') {
                fetch(`/employees/create?company=${companyId.value}`)
                    .then(response => response.json())
                    .then(response => {
                        branchId.removeAttribute('disabled');
                        workShiftId.removeAttribute('disabled');

                        if (branchId.length > 0) {
                            for (let i = 0; i < branchId.length; i++) {
                                $('#branch_id')
                                    .find('option')
                                    .remove();
                            }
                        }

                        if (workShiftId.length > 0) {
                            for (let i = 0; i < workShiftId.length; i++) {
                                $('#work_shift_id')
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

                        const opt2 = document.createElement('option');
                        opt2.value = '';
                        opt2.selected = 'selected';
                        opt2.disabled = 'disabled';
                        opt2.innerHTML = 'Select Work Shift';
                        workShiftId.appendChild(opt2);

                        if (response.branches.length > 0) {
                            response.branches.forEach((item, index) => {
                                const opt = document.createElement('option');
                                opt.value = item.branch_id;
                                opt.innerHTML = item.branch_name;
                                branchId.appendChild(opt);
                            });
                        }

                        const opt3 = document.createElement('option');
                        opt3.value = '';
                        opt3.selected = 'selected';
                        opt3.disabled = 'disabled';
                        opt3.innerHTML = 'Select Job Position';
                        departmentId.appendChild(opt3);
                        departmentId.setAttribute('disabled', 'disabled');

                        if (response.workshifts.length > 0) {
                            response.workshifts.forEach((item, index) => {
                                const opt = document.createElement('option');
                                opt.value = item.work_shift_id;
                                opt.innerHTML = item.shift_name;
                                workShiftId.appendChild(opt);
                            });
                        }
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
            const role = document.querySelector('#role_id');
            const jobClassId = document.querySelector('#job_class_id');
            const jobPositionId = document.querySelector('#job_position_id');
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
