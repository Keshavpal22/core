<div class="row">
    <div class="col-sm-4 form-group">
        <label>First Name</label>
        <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror"
               value="{{ old('first_name', $user->first_name ?? '') }}" required>
        @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-sm-4 form-group">
        <label>Last Name</label>
        <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror"
               value="{{ old('last_name', $user->last_name ?? '') }}" required>
        @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-sm-4 form-group">
        <label>Email</label>
        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
               value="{{ old('email', $user->email ?? '') }}" required>
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-sm-2 form-group">
        <label>Gender</label>
        <select name="gender" class="form-control @error('gender') is-invalid @enderror" required>
            <option value="male" {{ old('gender', $user->gender ?? '') == 1 ? 'selected' : '' }}>Male</option>
            <option value="female" {{ old('gender', $user->gender ?? '') == 0 ? 'selected' : '' }}>Female</option>
        </select>
        @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-sm-4 form-group">
        <label>Phone</label>
        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
               value="{{ old('phone', $user->phone ?? '') }}" required>
        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-sm-6 form-group">
        <label>Address</label>
        <input type="text" name="address" class="form-control @error('address') is-invalid @enderror"
               value="{{ old('address', $user->address ?? '') }}" required>
        @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-sm-4 form-group">
        <label>Occupation</label>
        <select name="occupation_field" class="form-control @error('occupation_field') is-invalid @enderror" required>
            @foreach(['engineering','doctor','teacher','business','other'] as $occ)
                <option value="{{ $occ }}" {{ old('occupation_field', $user->occupation_field ?? '') == $occ ? 'selected' : '' }}>
                    {{ ucfirst($occ) }}
                </option>
            @endforeach
        </select>
        @error('occupation_field') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-sm-5 form-group">
        <label>Experience (Years)</label>
        <input type="number" name="experience" class="form-control @error('experience') is-invalid @enderror"
               value="{{ old('experience', $user->experience ?? 0) }}" min="0" max="60" required
               oninput="let v=this.value.trim();this.value=(v===''||isNaN(v)||v<0)?0:Math.abs(v);">
        @error('experience') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="col-sm-3 form-group">
        <label>Mode of Transfer</label>
        <select name="mode_of_transfer" class="form-control @error('mode_of_transfer') is-invalid @enderror" required>
            @foreach(['car','bike','bus','metro','cycle','walking'] as $mode)
                <option value="{{ $mode }}" {{ old('mode_of_transfer', $user->mode_of_transfer ?? '') == $mode ? 'selected' : '' }}>
                    {{ ucfirst($mode) }}
                </option>
            @endforeach
        </select>
        @error('mode_of_transfer') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>
