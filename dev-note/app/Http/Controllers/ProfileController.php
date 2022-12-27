<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

use App\Models\Profile;

class ProfileController extends Controller
{
    public $icon_path = 'public/assets/img/icon-img';

    /**
     * 新規登録後に表示するプロフィール作成画面（初回のみ表示）
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function init(Request $request)
    {
        // $result = Profile::find($request->user()->id);
        // if ($result) {
        //     // 既にプロフィールが登録されている場合はprofileにリダイレクト
        //     return redirect()->route('profile.edit');
        // }

        return view('profile.init', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Display the user's profile form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function edit(Request $request)
    {
        $profile = Profile::find($request->user());
        return view('profile.edit', [
            'user' => $request->user(),
            'profile' => $profile[0],
        ]);
    }

    /**
     * Update the user's profile information.
     *
     * @param  \App\Http\Requests\ProfileUpdateRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfileUpdateRequest $request)
    {
        $validated_data = $request->validated();

        $request->user()->fill($validated_data);

        $icon_path = $this->icon_path . '/' . $request->user()->id;

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $profile_data = array();
        if (isset($validated_data['icon-img'])) {
            Storage::deleteDirectory($icon_path);
            $filename = $validated_data['icon-img']->store($icon_path);
            $profile_data['top_img'] = str_replace('public/', '', $filename);
        }

        $profile_data['description'] = $validated_data['description'];

        $request->user()->save();
        Profile::updateOrCreate(
            ['user_id' => $request->user()->id,],
            $profile_data,
        );

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
