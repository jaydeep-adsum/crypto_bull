<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Models\Authenticator;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UserController extends AppBaseController
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    private $authenticator;

    public function __construct(UserRepository $userRepository, Authenticator $authenticator){
        $this->userRepository = $userRepository;
        $this->authenticator = $authenticator;
    }

    /**
     * @param Request $request
     * @return JsonResponse|void
     */
    public function signup(Request $request){
        try {
            $validator = Validator::make($request->all(), [
                'full_name' => 'required',
                'user_name' => 'required',
                'email' => 'required|unique:users',
                'password' => 'required',
//                'tether_account' => 'required',
                'secret_question' => 'required',
                'secret_answer' => 'required',
                'term_condition' => 'required',
            ]);

            $error = (object)[];
            if ($validator->fails()) {

                return response()->json(['status' => "false", 'data' => $error, 'message' => implode(', ', $validator->errors()->all())]);
            }
            $input = $request->all();
            $input['password'] = Hash::make($input['password']);
            $user = User::create($input);

            if ($user) {
                $credentials['user_name'] = $user->user_name;
                $credentials['password'] = $user->password;

                if ($user = $this->authenticator->attemptSignUp($credentials)) {
                    $tokenResult = $user->createToken('crypto-bull');
                    $token = $tokenResult->token;
                    $token->save();
                    $success['token'] = 'Bearer ' . $tokenResult->accessToken;
                    $success['expires_at'] = Carbon::parse(
                        $tokenResult->token->expires_at
                    )->toDateTimeString();
                    $success['user'] = $user;

                    return $this->sendResponse(
                        $success, 'You Have Successfully Logged in to crypto bull.'
                    );
                } else {
                    return response()->json(['success' => false, 'data' => $error, 'message' => 'These credentials do not match our records']);
                }
            }
        } catch (Exception $e) {
            return $this->sendError($e);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_name' => 'required',
                'password' => 'required',
            ]);

            $error = (object)[];
            if ($validator->fails()) {

                return response()->json(['status' => "false", 'data' => $error, 'message' => implode(', ', $validator->errors()->all())]);
            }

                $credentials['user_name'] = $request->user_name;
                $credentials['password'] = $request->password;

                if ($user = $this->authenticator->attemptLogin($credentials)) {

                    $tokenResult = $user->createToken('crypto-bull');
                    $token = $tokenResult->token;
                    $token->save();
                    $success['token'] = 'Bearer ' . $tokenResult->accessToken;
                    $success['expires_at'] = Carbon::parse(
                        $tokenResult->token->expires_at
                    )->toDateTimeString();
                    $success['user'] = $user;

                    return $this->sendResponse(
                        $success, 'You Have Successfully Logged in to crypto bull.'
                    );
                } else {
                    return response()->json(['success' => false, 'data' => $error, 'message' => 'These credentials do not match our records']);
                }
        } catch (Exception $e) {
            return $this->sendError($e);
        }
    }

    public function index(){
        if (!Auth::user()) {
            return $this->sendError('Unauthorized');
        }
        $user = Auth::user();
        return $this->sendResponse($user, ('User retrieved successfully'));
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request){
        if (Auth::id()!=$request->user_id) {
            return $this->sendError('Unauthorized');
        }
        $input['tether_account'] = $request->tether_account;
        $user = User::find($request->user_id);
        $user->update($input);

        return $this->sendResponse($user, ('User updated successfully'));
    }
}
