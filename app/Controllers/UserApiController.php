<?php

namespace App\Controllers;


use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;
use \Firebase\JWT\JWT;
use \Firebase\JWT\KEY;

class UserApiController extends BaseController
{
    use ResponseTrait;
    public function create()
    {
        $users = new UserModel();
        $data = [
            'name' => $this->request->getVar('name'),
            'email' => $this->request->getVar('email'),
            'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
        ];
        //
        // return $this->respond($data);
                // for email existance
        $is_email = $users->where('email', $this->request->getVar('email'))->first();
        if ($is_email) {
            return $this->respondCreated([
                'status' => 0,
                'message' => 'Email already exist'
            ]);
        } else {
            $result=$users->save($data);
            if($result){
                $status = [
                    'status' => 1,
                    'message' => 'User Create Successfully'
                ];
                return $this->respondCreated($status);
            }else{
                $status = [
                    'status' => 0,
                    'message' => 'User not create successfully',
                ];
                return $this->respondCreated($status);
            }
        }
    }

    public function login()
    {
        $users = new UserModel();
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');
        $is_email = $users->where('email', $email)->first();
        if ($is_email) {
            $verify_password = password_verify($password, $is_email['password']);
            if ($verify_password) {
                $key = "tawatchai";
                $payload = [
                    "iss" => "localhost",
                    "aud" => "localhost",
                    // we can also use exprire time in seconds
                    "data" => [
                        'user_id' => $is_email['id'],
                        'name' => $is_email['name'],
                        'email' => $is_email['email']
                    ]
                ];
                $jwt = JWT::encode($payload, $key, 'HS256');
                return $this->respondCreated([
                    'status' => 1,
                    'jwt_token' => $jwt,
                    'message' => 'User Login Successfully',
                ]);
            } else {
                return $this->respondCreated([
                    'status' => 0,
                    'message' => 'Invalid Email and Password',
                ]);
            }
        }else {
            return $this->respondCreated([
                'status' => 0,
                'message' => 'Email is not found',
            ]);
        }
    }

    public function readUser()
    {

        // Bearer
        $key = getenv('TOKEN_SECRET');
        $header = $this->request->getServer('HTTP_AUTHORIZATION');
        if(!$header) return $this->failUnauthorized('Token Required');
        $jwt = explode(' ', $header)[1];

        // $headers = $this->request->headers();
        // array_walk($headers, function(&$value, $key) {
        //     $value = $value->getValue();
        // });
        // print_r($headers);
        $request = service('request');
        $key = "tawatchai";
        $headers = $this->request->header('Authorization');
        $jwt = $headers->getValue();
        // $jwt = $this->request->getServer('HTTP_AUTHORIZATION');
        
        $userData = JWT::decode($jwt, new KEY($key, 'HS256'));
        $users = $userData->data;
        return $this->respond([
            'status' => 1,
            'users' =>   $users
        ]);
    }
}
