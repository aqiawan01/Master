<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;
use App\Classes\Helper;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Frontend\Customer\GetProfile as GetUserProfile;
use Hash;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, LogsActivity, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['added_by', 'updated_by', 'name', 'email', 'otp', 'device_type', 'device_token', 'verified_by', 'social_provider', 'social_token', 'social_id', 'password'];

    protected static $logAttributes = ['added_by', 'updated_by', 'name', 'email', 'otp', 'device_type', 'device_token', 'verified_by', 'social_provider', 'social_token', 'social_id'];
    protected static $logName = 'User';
    protected static $logOnlyDirty = true;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Customer Section Start Created By MYTECH MAESTRO
    public static function verifyOtp($request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'nullable|email',
            'otp' => 'required|numeric|digits:4'
        ]);

        if ($validator->fails()) {
            return $validator;
        }

        $user = User::where('email', $request->email)->first();

        if($user) {
            if($request->otp == $user->otp) {
                if ($user->verified_by == 'email' && $user->email_verified_at == '' || $request->email) {
                    $user->email_verified_at = date('Y-m-d H:i:s');
                    $user->otp = null;
                    if ($request->email) {
                        $user->email = $request->email;
                    }
                    $user->save();

                    if (!Auth::guard('frontend')->loginUsingId($user->id)) 
                    {
                        return 'Something wen\'t wrong';
                    }

                    if (Auth::guard('frontend')->user()) 
                    {
                        $user = Auth::guard('frontend')->user();
                    }

                    $tokenResult = $user->createToken('Personal Access Token');

                    $token = $tokenResult->token;

                    if ($request->remember_me)
                    {
                        $token->expires_at = Carbon::now()->addWeeks(1);
                    }

                    $token->save();

                    $device_type = $request->has('device_type') ? $request->device_type : '';
                    $device_token = $request->has('device_token') ? $request->device_token : '';

                    if ($device_token && $device_type) 
                    {
                        $user->device_type   = $device_type;
                        $user->device_token  = $device_token;

                        $user->save();
                    }

                    $user->token = 'Bearer ' . $tokenResult->accessToken;
                    // $user->roles = $user->roles ?? [];
                    
                    return $user;
                    
                } else {
                    return ['error' => 'User is already verified'];
                }
            } else {
                return 'Please enter valid otp';
            }
        } else {
            return 'User is invalid';
        }
    }

    public function resendOtp($request)
    {
        $record = $this::whereNotNull('otp');
        
        $record = $this::query();
        
        if ($request->email)
        {
            $record->where('email', $request->email);
        }

        $record = $record->first();

        if (!$record) 
        {
            return 'Invalid User';
        }

        if($record->verified_by == 'email') {
            $data = [
                'email' => $record->email,
                'name' => $record->name,
                'subject' => 'Resend Account verification code',
            ];

            Helper::sendEmail('accountVerification', ['data' => $record], $data);
        }

        return $record;
    }

    public function login($request)
    {
        if($request->has('email')) {
            $validationRules['email'] = 'required|string|email';
        }
        $validationRules['password'] = 'required|string|min:6|max:16';
        $validationRules['device_type'] = 'in:android,ios';
        $validationRules['device_token'] = 'string|max:255';

        $validator = Validator::make($request->all(), $validationRules);

        if($validator->fails()) {
            return $validator;
        }

        $attempt_by_email = $user = User::where('email', $request->email)->first();

        if($attempt_by_email) {
            $credentials = ['email' => $request->email, 'password' => $request->password];
        }

        if(!$user) {
            return "Invalid Credentials";
        }

        if(!Auth::guard('frontend')->attempt($credentials))
            return 'Invalid Credentials';

        if($attempt_by_email && Auth::guard('frontend')->user()->email_verified_at == '') {

            $user->otp = mt_rand(1000, 9999);
            $user->save();

            $data = [
                'email' => $user->email,
                'name' => $user->name,
                'subject' => 'Account verification code',
            ];

            Helper::sendEmail('accountVerification', ['data' => $user], $data);

            return ['error' => 'User is not verified', 'user' => $user];

        }

        $user = Auth::guard('frontend')->user();

        $tokenResult = $user->createToken('Personal Access Token');

        $token = $tokenResult->token;

        if($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);

        $token->save();

        $device_type = $request->has('device_type') ? $request->device_type : '';
        $device_token = $request->has('device_token') ? $request->device_token : '';

        if($device_token && $device_type) {

            $user->device_type  = $device_type;
            $user->device_token = $device_token;

            $user->save();
            try {

            } catch(\Exception $eex) {

            }
        }

        $user->token = 'Bearer ' . $tokenResult->accessToken;
        // $user->roles = $user->roles ?? [];
        return $user;
    }

    public function signup($request)
    {
        $validationRules['name'] = 'required|string|min:3|max:55';
        $validationRules['password'] = 'required|string|min:6|max:16|confirmed';
        $validationRules['verified_by'] = 'required|in:email';
        $validationRules['email'] = 'required|string|email|min:5|max:155|unique:users';

        $validator = Validator::make($request->all(), $validationRules);

        if ($validator->fails()) {
            return $validator;
        }

        if($request->verified_by == 'email') {
            $type = 'email';
            $token = mt_rand(1000, 9999);
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'verified_by' => $type,
            'otp' => $token,
            $this->assignRole(8)
        ];

        $this->fill($data);
        $this->save();

        if($this->verified_by == 'email') {
            $data = [
                'email' => $this->email,
                'name' => $this->name,
                'subject' => 'Account verification code',
            ];

            Helper::sendEmail('accountVerification', ['data' => $this], $data);
        }

        return $this;
        
    }

    public function forgetPassword($request)
    {
        $record = $this::where('email', $request->email)->first();

        $requestFor = 'email';

        if (!$record) 
        {
            return 'Email Not Found!';
        }

        $record->otp = mt_rand(1111, 9999);
        $record->verified_by = $requestFor;
        $record->save();

        if ($requestFor = 'email') 
        {
            $data = [
                'email' => $record->email,
                'name' => $record->name,
                'subject' => 'Account recovery code',
            ];

            Helper::sendEmail('accountVerification', ['data' => $record], $data);
        }

        return $record;
    }

    public function verifyForgetCode($request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'nullable|email',
            'otp' => 'required|numeric|digits:4'
        ]);

        if ($validator->fails()) {
            return $validator;
        }

        $record = $this::where('email', $request->email)->first();

        if (!$record) 
        {
            return 'Invalid user';
        }

        if ($record->otp == null) 
        {
            return ['error' => 'User is already verified'];
        }

        if ($record->otp != $request->otp) 
        {
            return 'Please enter valid otp';
        }

        $record->otp = null;
        $record->save();

        if ($record->verified_by = 'email') 
        {
            $data = [
                'email' => $record->email,
                'name' => $record->name,
                'subject' => 'Account recovery code',
            ];

            Helper::sendEmail('accountVerification', ['data' => $record], $data);
        } 

        return $record;
    }

    public function changePassword($request, $id)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => 'required|string|min:6|max:16|confirmed'
        ]);

        if ($validator->fails()) 
        {
            return $validator;
        }

        $record = $this::find($id);
        if (Hash::check($request->old_password, $record->password)) {
            $record->password = bcrypt($request->password);
            $record->save();
        } else {
            return 'Current password doesn,t match';
        }

        return $record;
    }

    public function resetPassword($request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:16|confirmed'
        ]);

        if ($validator->fails()) 
        {
            return $validator;
        }

        $record = $this::query();

        if($request->email)
        {
            $record->where('email', $request->email);
        }

        $record = $record->first();
        
        if (!$record) 
        {
            return 'Invalid user';
        }

        $record->password = bcrypt($request->password);
        $record->save();

        return $record;
    }

    public function updateProfile($request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|between:3,55',
            'email' => 'nullable|email',
            'phone' => 'nullable|numeric|digits_between:9,14',
        ]);

        if ($validator->fails()) {
            return $validator;
        }

        $checkUser = $this::where('id', $id)->first();

        $currentName = $checkUser->name;
        $currentEmail = $checkUser->email;
        $currentPhone = $checkUser->phone;

        $record = $this::find($id);
        $record->name = $request->name ? $request->name : $currentName;
        $record->email = $request->email ? $request->email : $currentEmail;
        $record->phone = $request->phone ? $request->phone : $currentPhone;
        $record->save();

        return $record;
    }

    public function getProfile($request, $id)
    {
        $record = $this->find($id);

        if (!$record) {
            return 'Unauthorized';
        }

        return (new GetUserProfile($record))->resolve();
    }

    public function signOut($request)
    {
        try 
        {
            $user = $request->user();
            $user->device_token = null;
            $user->device_type = null;
            $user->save();
            $request->user()->token()->revoke();
        } 
        catch (\Exception $exception) 
        {
            if ($exception instanceof \Illuminate\Auth\AuthenticationException)
            {
                return 'The session is already logged out';
            }
        }

        return $user;
    }
    // Customer Section End Created By MYTECH MAESTRO
}
