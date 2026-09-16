<?php

namespace App\Http\Controllers\Admin;

use HasinHayder\TyroDashboard\Http\Controllers\UserController as BaseUserController;
use HasinHayder\Tyro\Models\Role;
use HasinHayder\Tyro\Support\PasswordRules;
use HasinHayder\TyroDashboard\Support\DashboardRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends BaseUserController
{
    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        \Illuminate\Support\Facades\Log::info('UserController@store called with data:', $request->all());

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'password' => array_merge(['required', 'confirmed'], PasswordRules::get(['name' => $request->input('name'), 'email' => $request->input('email')])),
            'designation' => ['nullable', 'string', 'in:senior,junior'],
            'roles' => ['array'],
            'roles.*' => ['exists:roles,id'],
        ]);

        $userModel = $this->getUserModel();

        $user = $userModel::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'plain_password' => $validated['password'],
            'designation' => $validated['designation'] ?? null,
        ]);

        if (!empty($validated['roles'])) {
            $user->roles()->sync($validated['roles']);

            $assignedRoleIds = array_map('intval', $validated['roles']);
            $assignedRoles = Role::query()->whereIn('id', $assignedRoleIds)->get(['id', 'slug']);
            $this->auditRoleAssignments($user, $assignedRoles, true);
        }

        return redirect()
            ->route(DashboardRoute::name('users.index'))
            ->with('success', 'User created successfully. Password: ' . $validated['password']);
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, $id)
    {
        \Illuminate\Support\Facades\Log::info('UserController@update called with data:', $request->all());

        $userModel = $this->getUserModel();
        $user = $userModel::findOrFail($id);
        
        $oldName = $user->name;
        $oldEmail = $user->email;
        $oldRoleIds = $user->roles()->pluck('roles.id')->map(fn ($item) => (int) $item)->values()->all();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'username' => ['required', 'string', 'max:255', 'unique:users,username,'.$user->id],
            'password' => array_merge(['nullable', 'confirmed'], PasswordRules::get(['name' => $request->input('name'), 'email' => $request->input('email')])),
            'designation' => ['nullable', 'string', 'in:senior,junior'],
            'roles' => ['array'],
            'roles.*' => ['exists:roles,id'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->username = $validated['username'];
        $user->designation = $validated['designation'] ?? $user->designation;

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
            $user->plain_password = $validated['password'];
        }

        $user->save();

        if ($oldEmail !== $user->email) {
            $this->auditSafely('user.email_changed', $user, ['email' => $oldEmail], ['email' => $user->email]);
        }

        if (isset($validated['roles'])) {
            $user->roles()->sync($validated['roles']);

            $newRoleIds = array_map('intval', $validated['roles']);
            $attachedRoleIds = array_values(array_diff($newRoleIds, $oldRoleIds));
            $detachedRoleIds = array_values(array_diff($oldRoleIds, $newRoleIds));

            if (!empty($attachedRoleIds)) {
                $attachedRoles = Role::query()->whereIn('id', $attachedRoleIds)->get(['id', 'slug']);
                $this->auditRoleAssignments($user, $attachedRoles, true);
            }

            if (!empty($detachedRoleIds)) {
                $detachedRoles = Role::query()->whereIn('id', $detachedRoleIds)->get(['id', 'slug']);
                $this->auditRoleAssignments($user, $detachedRoles, false);
            }
        }

        $message = 'User updated successfully.';
        if (!empty($validated['password'])) {
            $message .= ' New Password: ' . $validated['password'];
        }

        return redirect()
            ->route(DashboardRoute::name('users.index'))
            ->with('success', $message);
    }
}
