<?php

namespace Tech5s\LoginSocial\Controllers;

use Tech5s\LoginSocial\Helpers\MediaHelper;
use Tech5s\LoginSocial\Models\User;
use Illuminate\Auth\Events\Registered;
use Laravel\Socialite\Facades\Socialite;
use Tech5s\LoginSocial\Helpers\LoginSocialHelper;
use Tech5s\LoginSocial\Models\MediaTableDetail;

class LoginController extends Controller
{
    public function redirectToSocial($social)
    {
        LoginSocialHelper::URLPrevious(true);
        $provider = Socialite::with($social);
        return $provider->redirect();
    }

    public function handleProviderCallback($social)
    {
        $request = request();
        $infos = [
            'name' => null,
            'picture' => null,
            'email' => null,
            'email_verified' => null,
            'locale' => null,
            'id' => null,
            'verified_email' => null,
        ];
        try {
            if ($request->has('credential')) {
                $results = json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', explode('.', $request->input('credential'))[1]))), true);
            } else {
                $results = Socialite::driver($social)->user();
            }
        } catch (\Exception $ex) {
            if (request()->ajax()) {
                return response(
                    [
                        'code' => 100, 'message' => 'Bạn đã từ chối ủy quyền cho Google xác thực', 'redirect' => route('register'),
                    ]
                );
            } else {
                return LoginSocialHelper::redirectTo(url('/'), 100, 'Bạn đã từ chối ủy quyền cho Google xác thực');
            }
        }
        if ($social == 'facebook') {
            if (!isset($results->user['email'])) {
                return LoginSocialHelper::redirectTo(url('/'), 100, "Không thể đăng nhập, Không thể xác thực tài khoản email từ tài khoản facebook của bạn");
            }
            $infos['name'] = $results->user['name'] ?? '';
            $infos['picture'] = $results->getAvatar();
            $infos['email'] = $results->user['email'] ?? '';
            $infos['id'] = $results->user['id'];
        }

        if ($social == 'google') {
            if ($request->has('credential')) {
                $infos['name'] = $results['name'];
                $infos['email'] = $results['email'];
                $infos['picture'] = $results['picture'];
                $infos['verified_email'] = $results['email_verified'];
                $infos['id'] = $results['sub'];
            } else {
                $infos['name'] = $results->user['name'];
                $infos['picture'] = $results->user['picture'];
                $infos['email'] = $results->user['email'];
                $infos['email_verified'] = $results->user['email_verified'];
                $infos['locale'] = $results->user['locale'];
                $infos['id'] = $results->user['id'];
                $infos['verified_email'] = $results->user['verified_email'];
            }
        }

        $user = User::where('email', $infos['email'])->first();
        if ($user == null) {
            $user = new User;
            $user->name = $infos['name'];
            if ($infos['picture'] != null) {
                $image = MediaHelper::insertFileFromUrl(now()->format('Y/m') . '/tai-khoan', $infos['picture']);
                $user->avatar = json_encode($image, JSON_UNESCAPED_SLASHES);
            }
            $user->email = $infos['email'];
            $user->banned = 0;
            $user->act = 1;
            // $code = \Str::random(6);
            // $user->token = Hash::make($code);
            $user->save();
            if ($user->img != null) {
                MediaTableDetail::insertData($user->getTable(), $user->id, $image->id);
            }
            // event('sendmail.static', [[
            //     'title' => 'Tạo tài khoàn thành công và mã xác nhận kích hoạt tài khoản',
            //     'data' => [
            //         'link' => url('kich-hoat-tai-khoan') . "?token=$code&email=$user->email",
            //         'user' => $user,
            //     ],
            //     'email' => $user->email,
            //     'type' => 'user_create',
            // ]]);
            event(new Registered($user));
            auth()->login($user, true);
            // session()->put('EMAIL_CURRENT_REGISTER', $user->email);
            // session()->put('REGISTER_SOCIAL_NOW', '1');
        } else {
            if ($user->banned == 1) {
                return LoginSocialHelper::redirectTo(url('/'), 100, 'Tài khoản của bạn đã bị khóa');
            }
            if ($user->act == 0) {
                return LoginSocialHelper::redirectTo(url('/'), 100, 'Tài khoản của bạn chưa được kích hoạt vui lòng liên hệ với quản trị viên');
            }
            auth()->login($user, true);
        }

        if ($request->ajax()) {
            return response(['code' => 200, 'message' => 'Đăng nhập thành công', 'redirect' => LoginSocialHelper::URLPrevious(false)]);
        } else {
            return LoginSocialHelper::redirectTo(LoginSocialHelper::URLPrevious(false), 200, 'Đăng nhập thành công');
        }
    }
}
