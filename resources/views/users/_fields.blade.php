<!-- Generic Fields -->
<x-form-input name="name" label="Full Name" :value="$user->name" />
<x-form-input name="email" label="Email Address" type="email" :value="$user->email" />

<!-- Conditional Field: Only show Roles to Admins -->
@if(auth()->user()->isAdmin() && $user->id !== auth()->id())
    <x-form-select
        name="role"
        label="System Role"
        :options="$roles"
        :selected="$user->role"
    />
@endif

<p class="text-xs text-gray-500 mt-4 mb-2">Leave password blank to keep current password.</p>
<x-form-input name="password" label="New Password" type="password" />
<x-form-input name="password_confirmation" label="Confirm New Password" type="password" />
