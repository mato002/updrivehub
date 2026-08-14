<div>
    <label class="form-label">Full Name</label>
    <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" class="form-input" required>
</div>
<div>
    <label class="form-label">Email</label>
    <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" class="form-input" required>
</div>
<div>
    <label class="form-label">Password {{ isset($user) ? '(leave blank to keep current)' : '' }}</label>
    <input type="password" name="password" class="form-input" {{ isset($user) ? '' : 'required' }}>
</div>
<div>
    <label class="form-label">Role</label>
    <select name="role" class="form-input" required>
        @foreach($roles as $key => $label)
            <option value="{{ $key }}" @selected(old('role', $user->role ?? 'recruiter') === $key)>{{ $label }}</option>
        @endforeach
    </select>
</div>
<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
    <div>
        <label class="form-label">Admin Access</label>
        <select name="is_admin" class="form-input" required>
            <option value="1" @selected(old('is_admin', $user->is_admin ?? true))>Yes</option>
            <option value="0" @selected(! old('is_admin', $user->is_admin ?? true))>No</option>
        </select>
    </div>
    <div>
        <label class="form-label">Active</label>
        <select name="is_active" class="form-input" required>
            <option value="1" @selected(old('is_active', $user->is_active ?? true))>Active</option>
            <option value="0" @selected(! old('is_active', $user->is_active ?? true))>Inactive</option>
        </select>
    </div>
</div>
