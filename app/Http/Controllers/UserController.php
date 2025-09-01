<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $user = User::create($data);
        
        $user->interests()->sync($data['interests']);

        return redirect()
            ->back()
            ->with('success', 'User stored successfully.');
    }

    public function update(StoreUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        $user->update($data);
        
        $user->interests()->sync($data['interests']);

        return redirect()
            ->back()
            ->with('success', 'User updated successfully.');
    }

    public function delete(User $user): RedirectResponse
    {
        $user->interests()->detach();
        $user->delete();

        return redirect()
            ->back()
            ->with('success', 'User deleted successfully.');
    }
}