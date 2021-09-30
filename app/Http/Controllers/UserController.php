<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('profile');
    }

    /**
     * Display the specified user.
     *
     * @param User $user
     * @return Response
     */
    public function show(User $user)
    {
        return view('profile.show', ['user' => $user]);
    }

    /**
     * Show the form for editing users.
     *
     * @param User $user
     * @return Response
     */
    public function edit(User $user)
    {
        return view('profile.edit', ['user' => $user]);
    }

    /**
     * Update the specified user in storage.
     *
     * @param ProfileUpdateRequest $request
     * @param User $user
     * @return Response
     */
    public function update(ProfileUpdateRequest $request, User $user)
    {
        $data = $request->validated();
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        if ($user->update($data)) {
            return redirect(route('users.show', ['user' => $user]))
                ->with([
                    'success' => 'Succesfully updated your personal information'
                ]);
        } else {
            return redirect(route('users.edit', ['user' => $user]))
                ->with([
                    'error' => 'Something went wrong when attempting to change your personal information'
                ]);
        }
    }

    /**
     * Remove the specified user from storage.
     *
     * @param User $user
     * @return Response
     */
    public function destroy(User $user)
    {
        if ($user->delete()) {
            return redirect(route('login'))->with('success', 'Your account has been deleted.');
        }
        return redirect(route('profile.show', ['user' => $user]))
            ->with('error', 'Something went wrong when deleting your account. Please c.');

    }
}
