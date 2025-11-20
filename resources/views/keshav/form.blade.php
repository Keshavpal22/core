@extends('layouts.main')
@section('title', 'Form Components')
@section('content')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-edit bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('Components') }}</h5>
                            <span>{{ __('lorem ipsum dolor sit amet, consectetur adipisicing elit') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('dashboard') }}"><i class="ik ik-home"></i></a>
                            </li>
                            <li class="breadcrumb-item"><a href="#">{{ __('Forms') }}</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ __('Components') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        {{-- ==================== DEFAULT FORM (unchanged) ==================== --}}
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header"><h3>{{ __('Default form') }}</h3></div>
                    <div class="card-body">
                        <form class="forms-sample">
                            <div class="col-sm-4 form-group">
                                <label for="exampleInputUsername1">{{ __('Username') }}</label>
                                <input type="text" class="form-control" id="exampleInputUsername1" placeholder="Username">
                            </div>
                            <div class="col-sm-4 form-group">
                                <label for="exampleInputEmail1">{{ __('Email address') }}</label>
                                <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Email">
                            </div>
                            <div class="col-sm-4 form-group">
                                <label for="exampleInputPassword1">{{ __('Password') }}</label>
                                <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                            </div>
                            <div class="col-sm-4 form-group">
                                <label for="exampleInputConfirmPassword1">{{ __('Confirm Password') }}</label>
                                <input type="password" class="form-control" id="exampleInputConfirmPassword1" placeholder="Password">
                            </div>
                            <div class="col-sm-4 form-group">
                                <label class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input">
                                    <span class="custom-control-label">&nbsp;{{ __('Remember me') }}</span>
                                </label>
                            </div>
                            <button type="submit" class="btn btn-primary mr-2">{{ __('Submit') }}</button>
                            <button class="btn btn-light">{{ __('Cancel') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- ==================== NEW USER FORM (80% width + centered) ==================== --}}
        <div class="row justify-content-center">
            <div>
                <div class="card">
                    <div class="card-header"><h3>{{ __('New User Form') }}</h3></div>
                    <div class="card-body">

                        {{-- ==== FORM START ==== --}}
                        <form method="POST" action="{{ route('newuser.store') }}" class="forms-sample">
                            @csrf

                            {{-- Global validation errors --}}
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>{{ __('Please fix the following errors:') }}</strong>
                                    <ul class="mb-0 mt-2">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            {{-- Success message --}}
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            {{-- ==== SINGLE ROW – all fields side-by-side ==== --}}
                            <div class="row">

                                {{-- First Name --}}
                                <div class="col-sm-4 form-group">
                                    <label for="exampleInputFirstname1">{{ __('First Name') }}</label>
                                    <input type="text" name="first_name"
                                           class="form-control @error('first_name') is-invalid @enderror"
                                           id="exampleInputFirstname1"
                                           value="{{ old('first_name') }}"
                                           placeholder="Enter Your First Name" required>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Last Name --}}
                                <div class="col-sm-4 form-group">
                                    <label for="exampleInputLastname1">{{ __('Last Name') }}</label>
                                    <input type="text" name="last_name"
                                           class="form-control @error('last_name') is-invalid @enderror"
                                           id="exampleInputLastname1"
                                           value="{{ old('last_name') }}"
                                           placeholder="Enter Your Last Name" required>
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Email --}}
                                <div class="col-sm-4 form-group">
                                    <label for="exampleInputEmail1">{{ __('Email address') }}</label>
                                    <input type="email" name="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           id="exampleInputEmail1"
                                           value="{{ old('email') }}"
                                           placeholder="Enter Your Email Address" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Gender --}}
                                <div class="col-sm-2 form-group">
                                    <label for="exampleSelectGender1">{{ __('Gender') }}</label>
                                    <select class="form-control @error('gender') is-invalid @enderror"
                                            id="exampleSelectGender1" name="gender" required>
                                        <option value="" disabled {{ old('gender') ? '' : 'selected' }}>{{ __('Select') }}</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>{{ __('Male') }}</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>{{ __('Female') }}</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Phone --}}
                                <div class="col-sm-4 form-group">
                                    <label for="exampleInputPhone1">{{ __('Phone Number') }}</label>
                                    <input type="text" name="phone"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           id="exampleInputPhone1"
                                           value="{{ old('phone') }}"
                                           placeholder="10-digit mobile" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Address --}}
                                <div class="col-sm-6 form-group">
                                    <label for="exampleInputAddress1">{{ __('Address') }}</label>
                                    <input type="text" name="address"
                                           class="form-control @error('address') is-invalid @enderror"
                                           id="exampleInputAddress1"
                                           value="{{ old('address') }}"
                                           placeholder="Enter Your Address" required>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Occupation --}}
                                <div class="col-sm-4 form-group">
                                    <label for="exampleInputOccupationField1">{{ __('Occupation Field') }}</label>
                                    <select class="form-control @error('occupation_field') is-invalid @enderror"
                                            id="exampleInputOccupationField1" name="occupation_field" required>
                                        <option value="" disabled {{ old('occupation_field') ? '' : 'selected' }}>{{ __('Select') }}</option>
                                        <option value="engineering"   {{ old('occupation_field') == 'engineering'   ? 'selected' : '' }}>{{ __('Engineering') }}</option>
                                        <option value="doctor"        {{ old('occupation_field') == 'doctor'        ? 'selected' : '' }}>{{ __('Doctor') }}</option>
                                        <option value="teacher"       {{ old('occupation_field') == 'teacher'       ? 'selected' : '' }}>{{ __('Teacher') }}</option>
                                        <option value="business"      {{ old('occupation_field') == 'business'      ? 'selected' : '' }}>{{ __('Business') }}</option>
                                        <option value="other"         {{ old('occupation_field') == 'other'         ? 'selected' : '' }}>{{ __('Other') }}</option>
                                    </select>
                                    @error('occupation_field')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Experience --}}
                                <div class="col-sm-5 form-group">
                                    <label for="exampleInputExperience1">{{ __('Experience') }}</label>
                                    <input type="number" name="experience"
                                           class="form-control @error('experience') is-invalid @enderror"
                                           id="exampleInputExperience1"
                                           value="{{ old('experience') }}"
                                           min="0" max="60" step="1" placeholder="0"
                                           oninput="let v = this.value.trim(); this.value = (v === '' || isNaN(v) || v < 0) ? 0 : Math.abs(v);"
                                           required>
                                    <small class="text-muted">{{ __('Years') }}</small>
                                    @error('experience')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Mode of Transfer --}}
                                <div class="col-sm-3 form-group">
                                    <label for="exampleInputModeOfTransfer1">{{ __('Mode of Transfer') }}</label>
                                    <select class="form-control @error('mode_of_transfer') is-invalid @enderror"
                                            id="exampleInputModeOfTransfer1" name="mode_of_transfer" required>
                                        <option value="" disabled {{ old('mode_of_transfer') ? '' : 'selected' }}>{{ __('Select') }}</option>
                                        <option value="car"      {{ old('mode_of_transfer') == 'car'      ? 'selected' : '' }}>{{ __('Car') }}</option>
                                        <option value="bike"     {{ old('mode_of_transfer') == 'bike'     ? 'selected' : '' }}>{{ __('Bike') }}</option>
                                        <option value="bus"      {{ old('mode_of_transfer') == 'bus'      ? 'selected' : '' }}>{{ __('Bus') }}</option>
                                        <option value="metro"    {{ old('mode_of_transfer') == 'metro'    ? 'selected' : '' }}>{{ __('Metro') }}</option>
                                        <option value="cycle"    {{ old('mode_of_transfer') == 'cycle'    ? 'selected' : '' }}>{{ __('Cycle') }}</option>
                                        <option value="walking"  {{ old('mode_of_transfer') == 'walking'  ? 'selected' : '' }}>{{ __('Walking') }}</option>
                                    </select>
                                    @error('mode_of_transfer')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>

                            {{-- ==== SUBMIT / CANCEL ==== --}}
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary mr-2">{{ __('Submit') }}</button>
                                <button type="button" class="btn btn-light">{{ __('Cancel') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Second User Form --}}
        <div class="row justify-content-center">
            <div>
                <div class="card">
                    <div class="card-header"><h3>{{ __('New Details Form') }}</h3></div>
                    <div class="card-body">

                        {{-- ==== FORM START ==== --}}
                        <form method="POST" action="{{ route('newuser.store') }}" class="forms-sample">
                            @csrf

                            {{-- Global validation errors --}}
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>{{ __('Please fix the following errors:') }}</strong>
                                    <ul class="mb-0 mt-2">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            {{-- Success message --}}
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            {{-- ==== SINGLE ROW – all fields side-by-side ==== --}}
                            <div class="row">

                                {{-- First Name --}}
                                <div class="col-sm-4 form-group">
                                    <label for="exampleInputFirstname1">{{ __('First Name') }}</label>
                                    <input type="text" name="first_name"
                                           class="form-control @error('first_name') is-invalid @enderror"
                                           id="exampleInputFirstname1"
                                           value="{{ old('first_name') }}"
                                           placeholder="Enter Your First Name" required>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Last Name --}}
                                <div class="col-sm-4 form-group">
                                    <label for="exampleInputLastname1">{{ __('Last Name') }}</label>
                                    <input type="text" name="last_name"
                                           class="form-control @error('last_name') is-invalid @enderror"
                                           id="exampleInputLastname1"
                                           value="{{ old('last_name') }}"
                                           placeholder="Enter Your Last Name" required>
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Email --}}
                                <div class="col-sm-4 form-group">
                                    <label for="exampleInputEmail1">{{ __('Email address') }}</label>
                                    <input type="email" name="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           id="exampleInputEmail1"
                                           value="{{ old('email') }}"
                                           placeholder="Enter Your Email Address" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Gender --}}
                                <div class="col-sm-2 form-group">
                                    <label for="exampleSelectGender1">{{ __('Gender') }}</label>
                                    <select class="form-control @error('gender') is-invalid @enderror"
                                            id="exampleSelectGender1" name="gender" required>
                                        <option value="" disabled {{ old('gender') ? '' : 'selected' }}>{{ __('Select') }}</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>{{ __('Male') }}</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>{{ __('Female') }}</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Phone --}}
                                <div class="col-sm-4 form-group">
                                    <label for="exampleInputPhone1">{{ __('Phone Number') }}</label>
                                    <input type="text" name="phone"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           id="exampleInputPhone1"
                                           value="{{ old('phone') }}"
                                           placeholder="10-digit mobile" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Address --}}
                                <div class="col-sm-6 form-group">
                                    <label for="exampleInputAddress1">{{ __('Address') }}</label>
                                    <input type="text" name="address"
                                           class="form-control @error('address') is-invalid @enderror"
                                           id="exampleInputAddress1"
                                           value="{{ old('address') }}"
                                           placeholder="Enter Your Address" required>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Occupation --}}
                                <div class="col-sm-4 form-group">
                                    <label for="exampleInputOccupationField1">{{ __('Occupation Field') }}</label>
                                    <select class="form-control @error('occupation_field') is-invalid @enderror"
                                            id="exampleInputOccupationField1" name="occupation_field" required>
                                        <option value="" disabled {{ old('occupation_field') ? '' : 'selected' }}>{{ __('Select') }}</option>
                                        <option value="engineering"   {{ old('occupation_field') == 'engineering'   ? 'selected' : '' }}>{{ __('Engineering') }}</option>
                                        <option value="doctor"        {{ old('occupation_field') == 'doctor'        ? 'selected' : '' }}>{{ __('Doctor') }}</option>
                                        <option value="teacher"       {{ old('occupation_field') == 'teacher'       ? 'selected' : '' }}>{{ __('Teacher') }}</option>
                                        <option value="business"      {{ old('occupation_field') == 'business'      ? 'selected' : '' }}>{{ __('Business') }}</option>
                                        <option value="other"         {{ old('occupation_field') == 'other'         ? 'selected' : '' }}>{{ __('Other') }}</option>
                                    </select>
                                    @error('occupation_field')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Experience --}}
                                <div class="col-sm-5 form-group">
                                    <label for="exampleInputExperience1">{{ __('Experience') }}</label>
                                    <input type="number" name="experience"
                                           class="form-control @error('experience') is-invalid @enderror"
                                           id="exampleInputExperience1"
                                           value="{{ old('experience') }}"
                                           min="0" max="60" step="1" placeholder="0"
                                           oninput="let v = this.value.trim(); this.value = (v === '' || isNaN(v) || v < 0) ? 0 : Math.abs(v);"
                                           required>
                                    <small class="text-muted">{{ __('Years') }}</small>
                                    @error('experience')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Mode of Transfer --}}
                                <div class="col-sm-3 form-group">
                                    <label for="exampleInputModeOfTransfer1">{{ __('Mode of Transfer') }}</label>
                                    <select class="form-control @error('mode_of_transfer') is-invalid @enderror"
                                            id="exampleInputModeOfTransfer1" name="mode_of_transfer" required>
                                        <option value="" disabled {{ old('mode_of_transfer') ? '' : 'selected' }}>{{ __('Select') }}</option>
                                        <option value="car"      {{ old('mode_of_transfer') == 'car'      ? 'selected' : '' }}>{{ __('Car') }}</option>
                                        <option value="bike"     {{ old('mode_of_transfer') == 'bike'     ? 'selected' : '' }}>{{ __('Bike') }}</option>
                                        <option value="bus"      {{ old('mode_of_transfer') == 'bus'      ? 'selected' : '' }}>{{ __('Bus') }}</option>
                                        <option value="metro"    {{ old('mode_of_transfer') == 'metro'    ? 'selected' : '' }}>{{ __('Metro') }}</option>
                                        <option value="cycle"    {{ old('mode_of_transfer') == 'cycle'    ? 'selected' : '' }}>{{ __('Cycle') }}</option>
                                        <option value="walking"  {{ old('mode_of_transfer') == 'walking'  ? 'selected' : '' }}>{{ __('Walking') }}</option>
                                    </select>
                                    @error('mode_of_transfer')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>

                            {{-- ==== SUBMIT / CANCEL ==== --}}
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary mr-2">{{ __('Submit') }}</button>
                                <button type="button" class="btn btn-light">{{ __('Cancel') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @push('script')
        <script src="{{ asset('js/form-components.js') }}"></script>
    @endpush
@endsection
