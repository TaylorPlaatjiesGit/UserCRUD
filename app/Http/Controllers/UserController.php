<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Jobs\SendUserNotification;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    /**
     * Function to store the user
     *
     * @param StoreUserRequest $request
     * @return RedirectResponse
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $user = User::create($data);
        
        $user->interests()->sync($data['interests']);

        SendUserNotification::dispatch($user->id, "You have been added successfully!");

        return redirect()
            ->back()
            ->with('success', 'User stored successfully.');
    }

    /**
     * Function to update the user
     *
     * @param StoreUserRequest $request
     * @param User $user
     * @return RedirectResponse
     */
    public function update(StoreUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        $user->update($data);
        
        $user->interests()->sync($data['interests']);

        return redirect()
            ->back()
            ->with('success', 'User updated successfully.');
    }

    /**
     * Function to delete the user
     *
     * @param User $user
     * @return RedirectResponse
     */
    public function delete(User $user): RedirectResponse
    {
        $user->interests()->detach();
        $user->delete();

        return redirect()
            ->back()
            ->with('success', 'User deleted successfully.');
    }
}