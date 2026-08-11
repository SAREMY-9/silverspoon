<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    /**
     * Ensure the authenticated user is an administrator.
     */
    protected function ensureAdmin(Request $request): void
    {
        abort_unless(
            $request->user() &&
            $request->user()->role === 'admin',
            403,
            'You are not authorized to manage users.'
        );
    }

    /**
     * User list.
     */
    public function index(Request $request): View
    {
        $this->ensureAdmin($request);

        $query = User::query()
            ->withCount([
                'subscriptions',
                'payments',
                'mealRedemptions',
            ]);

        /*
         * Search
         */
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        /*
         * Role filter
         */
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        /*
         * Status filter
         */
        if ($request->filled('status')) {
            $query->where(
                'is_active',
                $request->status === 'active'
            );
        }

        /*
         * Ordering
         */
        $users = $query
            ->orderByDesc('created_at')
            ->paginate(25)
            ->withQueryString();

        /*
         * Dashboard counts
         */
        $totalUsers = User::count();

        $activeUsers = User::where(
            'is_active',
            true
        )->count();

        $inactiveUsers = User::where(
            'is_active',
            false
        )->count();

        $adminCount = User::where(
            'role',
            'admin'
        )->count();

        return view('admin.users.index', compact(
            'users',
            'totalUsers',
            'activeUsers',
            'inactiveUsers',
            'adminCount'
        ));
    }

    /**
     * Show create user form.
     */
    public function create(Request $request): View
    {
        $this->ensureAdmin($request);

        return view('admin.users.create');
    }

    /**
     * Create a user from admin panel.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->ensureAdmin($request);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'role' => [
                'required',
                Rule::in([
                    'customer',
                    'staff',
                    'admin',
                ]),
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],
        ]);

        $validated['is_active'] =
            $request->boolean('is_active');

        User::create($validated);

        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                'User created successfully.'
            );
    }

    /**
     * Show user details.
     */
    public function show(
        Request $request,
        User $user
    ): View {
        $this->ensureAdmin($request);

        $user->load([
            'subscriptions' => function ($query) {
                $query
                    ->with('mealPlan')
                    ->latest();
            },

            'payments' => function ($query) {
                $query
                    ->with('subscription')
                    ->latest();
            },

            'mealRedemptions' => function ($query) {
                $query
                    ->with('meal')
                    ->latest('redeemed_at')
                    ->limit(20);
            },
        ]);

        $entitlements = \App\Models\MealEntitlement::query()
            ->whereHas(
                'subscription',
                function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                }
            )
            ->with([
                'subscription.mealPlan',
                'meal',
                'redemption',
            ])
            ->latest()
            ->paginate(30)
            ->withQueryString();

        return view(
            'admin.users.show',
            compact(
                'user',
                'entitlements'
            )
        );
    }

    /**
     * Show edit form.
     */
    public function edit(
        Request $request,
        User $user
    ): View {
        $this->ensureAdmin($request);

        return view(
            'admin.users.edit',
            compact('user')
        );
    }

    /**
     * Update user.
     */
    public function update(
        Request $request,
        User $user
    ): RedirectResponse {
        $this->ensureAdmin($request);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($user->id),
            ],

            'phone' => [
                'nullable',
                'string',
                'max:30',
            ],

            'role' => [
                'required',
                Rule::in([
                    'customer',
                    'staff',
                    'admin',
                ]),
            ],
        ]);

        /*
         * Prevent an administrator from removing their
         * own administrator privileges.
         */
        if (
            $user->id === $request->user()->id &&
            $validated['role'] !== 'admin'
        ) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'You cannot remove your own administrator privileges.'
                );
        }

        /*
         * Prevent removing the final administrator.
         */
        if (
            $user->role === 'admin' &&
            $validated['role'] !== 'admin'
        ) {
            $adminCount = User::where(
                'role',
                'admin'
            )->count();

            if ($adminCount <= 1) {
                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'The system must have at least one administrator.'
                    );
            }
        }

        $user->update($validated);

        return redirect()
            ->route(
                'admin.users.show',
                $user
            )
            ->with(
                'success',
                'User updated successfully.'
            );
    }

    /**
     * Activate/deactivate user.
     */
    public function toggle(
        Request $request,
        User $user
    ): RedirectResponse {
        $this->ensureAdmin($request);

        /*
         * Prevent self-deactivation.
         */
        if ($user->id === $request->user()->id) {
            return back()->with(
                'error',
                'You cannot deactivate your own account.'
            );
        }

        /*
         * Do not allow the last admin to be deactivated.
         */
        if (
            $user->role === 'admin' &&
            $user->is_active
        ) {
            $activeAdmins = User::query()
                ->where('role', 'admin')
                ->where('is_active', true)
                ->count();

            if ($activeAdmins <= 1) {
                return back()->with(
                    'error',
                    'The system must have at least one active administrator.'
                );
            }
        }

        $user->update([
            'is_active' => !$user->is_active,
        ]);

        return back()->with(
            'success',
            $user->is_active
                ? 'User activated successfully.'
                : 'User deactivated successfully.'
        );
    }

    /**
     * Reset user password.
     */
    public function resetPassword(
        Request $request,
        User $user
    ): RedirectResponse {
        $this->ensureAdmin($request);

        $validated = $request->validate([
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        $user->update([
            'password' => Hash::make(
                $validated['password']
            ),
        ]);

        return back()->with(
            'success',
            'Password reset successfully.'
        );
    }

    /**
     * Delete user.
     */
    public function destroy(
        Request $request,
        User $user
    ): RedirectResponse {
        $this->ensureAdmin($request);

        /*
         * Never allow an administrator to delete themselves.
         */
        if ($user->id === $request->user()->id) {
            return back()->with(
                'error',
                'You cannot delete your own account.'
            );
        }

        /*
         * Prevent deleting the last administrator.
         */
        if ($user->role === 'admin') {
            $adminCount = User::where(
                'role',
                'admin'
            )->count();

            if ($adminCount <= 1) {
                return back()->with(
                    'error',
                    'The last administrator cannot be deleted.'
                );
            }
        }

        /*
         * Do not destroy users with financial or subscription
         * history. This is important for auditability.
         */
        if ($user->subscriptions()->exists()) {
            return back()->with(
                'error',
                'This user has subscription history and cannot be deleted. Deactivate the account instead.'
            );
        }

        if ($user->payments()->exists()) {
            return back()->with(
                'error',
                'This user has payment history and cannot be deleted. Deactivate the account instead.'
            );
        }

        if ($user->mealRedemptions()->exists()) {
            return back()->with(
                'error',
                'This user has meal redemption history and cannot be deleted. Deactivate the account instead.'
            );
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                'User deleted successfully.'
            );
    }
}