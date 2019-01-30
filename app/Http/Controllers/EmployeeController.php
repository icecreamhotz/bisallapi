<?php

namespace App\Http\Controllers;

use App\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{

    public function showAllEmployees()
    {
        return response()->json(Employee::all());
    }

    public function showAllSeller()
    {
        return response()->json(Employee::where(['pos_id' => '11'])->select(['emp_code AS title', 'emp_name', 'emp_lastname'])->get());
    }

    public function showOneAuthor($id)
    {
        return response()->json(Author::find($id));
    }

    public function create(Request $request)
    {

        for($i = 31; $i <= 32; $i++) {
            $code = 'BIS'.($i < 10 ? '0'.$i : $i);
            $data = array('emp_code' => $code, 'emp_name' => 'DummyName', 'emp_lastname' => 'DummyLastname'
            , 'emp_tel' => '0896586922', 'emp_passport' => '1529900905895', 'emp_address' => 'Chaing Mai');
            Employee::insert($data);
        }

        return response()->json(Employee::all(), 201);
    }

    public function update($id, Request $request)
    {
        $author = Author::findOrFail($id);
        $author->update($request->all());

        return response()->json($author, 200);
    }

    public function updateAuthentication($id, Request $request)
    {
        $employee = Employee::where('emp_code', $id)->update(['emp_password' => $request->emp_password]);
        return response()->json(Employee::all(), 200);
    }

    public function delete($id)
    {
        Author::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }
}