<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use App\Profile;
use App\UserProfile;
use App\SelectOption;
use App\User;
use App\APIError;


class UserController extends Controller
{
    public function getUserInfo($id) {
        $user = User::whereId($id)->first();
        if(!$user){
            $response = new APIError;
            $response->setStatus("404");
            $response->setCode("USER_NOT_FOUND");
            $response->setMessage("The user with id $id was not found");
            return response()->json($response, 404);
        }
        $user_infos = UserProfile::whereUserId($id)->with('profile')->get();
        foreach ($user_infos as $user_info) {
            if($user_info->profile->type == 'file')
                $user[$user_info->profile->slug] = url($user_info->value);
            else
                $user[$user_info->profile->slug] = $user_info->value;
        }

        // The empty user field must be present in response, with null value
        $profiles = Profile::all();
        foreach ($profiles as $profile) {
            if ( ! isset($user[$profile->slug]) ) {
                $user[$profile->slug] = null;
            }
        }

        return response()->json($user);
    }


    /**
     * @author Armel Nya
     */
    public function create(Request $request) {
        $profiles = Profile::get();
        $rules = [
            'login' => ['required', 'alpha_num', 'unique:App\User'],
            'password' => ['required'],
        ];
        // La boucle de validation
        foreach ($profiles as $profile) {
            $rule = [];
            if ($profile->is_required) {
                $rule[] = 'required';
            } else {
                $rule[] = 'nullable';
            }

            if ($profile->is_unique) {
                $rule[] = function ($attribute, $value, $fail) use ($profile) {
                    $count = UserProfile::where('profile_id', $profile->id)->where('value', $value)->count();
                    if ($count > 0) {
                        $fail($attribute . ' must be unique');
                    }
                };
            }

            if ($profile->min) {
                $rule[] = 'min:' . $profile->min;
            }

            if ($profile->max) {
                $rule[] = 'max:' . $profile->max;
            }

            if (strtolower($profile->type) == 'select') {
                $options = SelectOption::where('profile_id', $profile->id)->pluck('key');
                $rule[] = Rule::in($options);
            }

            if (strtolower($profile->type) == 'email') {
                $rule[] = 'email';
            }

            if (strtolower($profile->type) == 'file') {
                $rule[] = 'file';
            }

            if (strtolower($profile->type) == 'number') {
                $rule[] = 'numeric';
            }

            if (strtolower($profile->type) == 'date') {
                $rule[] = 'date';
            }

            if (strtolower($profile->type) == 'url') {
                $rule[] = 'url';
            }

            $rules[ $profile->slug ] = $rule;
        }

        $this->validate($request->all(), $rules);
        // si la validation est ok on cree le user
        $user = User::create([
            'login' => $request->login,
            'password' => bcrypt($request->password)
        ]);

        // Insertion loop
        foreach ($profiles as $profile) {
            $value = null;
            if ($request->has($profile->slug)) {
                if (strtolower($profile->type) == 'file') {
                    if ($file = $request->file($profile->slug)) {
                        $extension = $file->getClientOriginalExtension();
                        $relativeDestination = "uploads/users";
                        $destinationPath = public_path($relativeDestination);
                        $safeName = Str::slug($user->login) . time() . '.' . $extension;
                        $file->move($destinationPath, $safeName);
                        $value = "$relativeDestination/$safeName";
                    }
                } else {
                    $value = $request[ $profile->slug ];
                }

                if ($value) {
                    UserProfile::create([
                        'user_id' => $user->id,
                        'profile_id' => $profile->id,
                        'value' => $value
                    ]);
                }
            }
            $user[ $profile->slug ] = $value;
        }

        return response()->json($user);
    }

    public function update(Request $request, $id) {
        $user = User::find($id);
        if($user == null){
            $unauthorized = new APIError;
            $unauthorized->setStatus("404");
            $unauthorized->setCode("USER_NOT_FOUND");
            $unauthorized->setMessage("No user found with id $id");
                return response()->json($unauthorized, 404);
        }
        $profiles = Profile::get();
        $rules = [
            'login' => ['required', 'alpha_num', Rule::unique('users')->ignore($id,'id')],
        ];
        // boucle de validation
        foreach ($profiles as $profile) {
            $rule = [];
            if ($profile->is_required) {
                $rule[] = 'required';
            } else {
                $rule[] = 'nullable';
            }

            if ($profile->is_unique) {
                $rule[] = function ($attribute, $value, $fail) use ($profile, $user) {
                    $count = UserProfile::where('profile_id', $profile->id)
                                ->where('user_id', '<>', $user->id)
                                ->where('value', $value)
                                ->count();
                    if ($count > 0) {
                        $fail($attribute . ' must be unique');
                    }
                };
            }

            if ($profile->min) {
                $rule[] = 'min:' . $profile->min;
            }

            if ($profile->max) {
                $rule[] = 'max:' . $profile->max;
            }

            if (strtolower($profile->type) == 'select') {
                $options = SelectOption::where('profile_id', $profile->id)->pluck('key');
                $rule[] = Rule::in($options);
            }

            if (strtolower($profile->type) == 'email') {
                $rule[] = 'email';
            }

            if (strtolower($profile->type) == 'file') {
                $rule[] = 'file';
            }

            if (strtolower($profile->type) == 'number') {
                $rule[] = 'numeric';
            }

            if (strtolower($profile->type) == 'date') {
                $rule[] = 'date';
            }

            if (strtolower($profile->type) == 'url') {
                $rule[] = 'url';
            }

            $rules[ $profile->slug ] = $rule;
        }

        $this->validate($request->all(), $rules);

        // Insertion or update
        foreach ($profiles as $profile) {
            $userProfile = UserProfile::where('user_id', $user->id)->where('profile_id', $profile->id)->first();
            $value = (null != $userProfile) ? $userProfile->value : null;
            if ($request->has($profile->slug)) {
                if (strtolower($profile->type) == 'file') {
                    if ($file = $request->file($profile->slug)) {
                        $extension = $file->getClientOriginalExtension();
                        $relativeDestination = "uploads/users";
                        $destinationPath = public_path($relativeDestination);
                        $safeName = Str::slug($user->login) . time() . '.' . $extension;
                        $file->move($destinationPath, $safeName);
                        $value = "$relativeDestination/$safeName";
                    }
                } else {
                    $value = $request[ $profile->slug ];
                }

                if ($value) {
                    if ($userProfile) {
                        $userProfile->value = $value;
                        $userProfile->save();
                    } else {
                        UserProfile::create([
                            'user_id' => $user->id,
                            'profile_id' => $profile->id,
                            'value' => $value
                        ]);
                    }
                }
            } else {
                if ($userProfile) {
                    $userProfile->delete();
                }
                $value = null;
            }
            $user[ $profile->slug ] = $value;
        }

        return response()->json($user);
    }
}
