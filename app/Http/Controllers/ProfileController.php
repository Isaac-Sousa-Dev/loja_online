<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Partner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $userId = $request->user()->id;
        $imageProfile = '/storage/'.$request->user()->image_profile;
        $partner = Partner::where('user_id', $userId)->first();

        return view('profile.edit', [
            'user' => $request->user(),
            'partner' => $partner,
            'imageProfile' => $imageProfile
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
    
        $partner = Partner::where('user_id', $request->user()->id)->first();

        if ($request->hasFile('image-profile')) {
            $profileImage = $request->file('image-profile');
            $profileImagePath = $profileImage->store('profile_images', 'public');

            // Se o usuário já tiver uma imagem de perfil, delete a imagem antiga
            if ($request->user()->profile_image) {
                Storage::disk('public')->delete($request->user()->image_profile);
            }

            $request->user()->image_profile = $profileImagePath;
        }

        if ($partner !== null) {
            $partner->update([
                'initial_message' => $request->description,
            ]);
        }

        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
