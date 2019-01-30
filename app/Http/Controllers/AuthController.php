<?php
namespace App\Http\Controllers;
use App\Employee;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Firebase\JWT\ExpiredException;
use Laravel\Lumen\Routing\Controller as BaseController;

class AuthController extends BaseController 
{
    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    private $request;
    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(Request $request) {
        $this->request = $request;
    }
    /**
     * Create a new token.
     * 
     * @param  \App\User   $user
     * @return string
     */
    protected function jwt(Employee $employee) {
        $payload = [
            'iss' => "lumen-jwt", // Issuer of the token
            'sub' => $employee->emp_code, // Subject of the token
            'iat' => time(), // Time when JWT was issued. 
            'exp' => time() + 60*60 // Expiration time
        ];

        return JWT::encode($payload, env('JWT_SECRET'));
    } 

    public function authenticate(Employee $employee) {
        // Find the user by email
        $employee = Employee::where(['emp_code' => $this->request->input('emp_code'), 'emp_password' => $this->request->input('emp_password')])->first();
        if (!$employee) {
            // You wil probably have some sort of helpers or whatever
            // to make sure that you have the same response format for
            // differents kind of responses. But let's return the 
            // below respose for now.
            return response()->json([
                'error' => 'employee does not exist.'
            ], 200);
        } else {
            return response()->json([
                'token' => $this->jwt($employee)
            ], 200);
        }
        // Bad Request response
        return response()->json([
            'error' => 'employee or password is wrong.'
        ], 400);
    }

    public function employee(Request $request) {
        $credentials = JWT::decode($request->get('token'), env('JWT_SECRET'), ['HS256']);
        $employee = Employee::where(['emp_code' => $credentials->sub])->firstOrFail();
        return response()->json($employee, 200);
    }

    public function editemployee(Request $request) {
        $credentials = JWT::decode($request->get('token'), env('JWT_SECRET'), ['HS256']);
        $employee = Employee::where(['emp_code' => $credentials->sub])->update(['emp_name' => $request->emp_name, 
        'emp_lastname' => $request->emp_lastname, 'emp_tel' => $request->emp_tel, 'emp_address' => $request->emp_address
        , 'emp_passport' => $request->emp_passport, 'emp_scode' => $request->emp_scode]);
        return response()->json(Employee::where(['emp_code' => $credentials->sub])->firstOrFail(), 200);
    }
}