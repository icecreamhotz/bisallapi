<?php

namespace App\Http\Controllers;

use DB;
use App\Worktime;
use App\Employee;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Firebase\JWT\ExpiredException;
use Image;

class WorktimeController extends Controller
{

    public function create(Request $request)
    {
        $credentials = JWT::decode($request->get('token'), env('JWT_SECRET'), ['HS256']);
        $owner = Employee::select('id')->where('emp_code', $credentials->sub)->first();
        $id = Employee::select('id')->where('emp_code', $request->emp_code)->first();

        $data = array('work_startdate' => $request->work_startdate, 'work_enddate' => $request->work_enddate, 'add_by' => $owner->id, 
        'check_by' => $id->id);
        
        Worktime::insert($data);

        return response()->json(Worktime::all(), 201);
    }

    public function get()
    {
        $worktimes = Worktime::select('worktimes.work_id AS id', 'worktimes.work_startdate AS start', 'worktimes.work_enddate AS end', 
        DB::raw('CONCAT(employees.emp_code, \' \', employees.emp_name, \' \', employees.emp_lastname) AS title'))
        ->join('employees', 'employees.id', '=', 'worktimes.check_by')->get();

        return response()->json($worktimes, 201);
    }
    
    public function getById(Request $request)
    {
        $credentials = JWT::decode($request->get('token'), env('JWT_SECRET'), ['HS256']);
        $owner = Employee::select('id')->where('emp_code', $credentials->sub)->first();

        $worktimes = Worktime::where(['check_by' => $owner->id])->get();

        return response()->json($worktimes, 201);
    }

    public function update($id, Request $request)
    {
        $credentials = JWT::decode($request->get('token'), env('JWT_SECRET'), ['HS256']);
        $owner = Employee::select('id')->where('emp_code', $credentials->sub)->first();

        $worktimes = Worktime::where('work_id', $id)->update(['work_startdate' => $request->work_startdate,
        'work_enddate' => $request->work_enddate, 'add_by' => $owner->id]);

        return response()->json($worktimes, 200);
    }

    public function delete($id)
    {
        Worktime::findOrFail($id)->delete();
        return response()->json('Delete Successfully', 200);
    }

    public function checkin(Request $request) {
        // save image to folder
        $imgname = json_decode($request->imgname, true);
        $i = 0;
        if($files = $request->avatar) {
            foreach($files as $file) {  
                $image_resize = Image::make($file);
                $image_resize->resize(300, 300);
                $image_resize->save(storage_path('public/'.$imgname[$i].'.jpg'));
                $i++;
            }
        }

        $credentials = JWT::decode($request->get('token'), env('JWT_SECRET'), ['HS256']);
        $owner = Employee::select('id')->where('emp_code', $credentials->sub)->first();

        $worktimes = Worktime::where(['check_by' => $owner->id, 'work_startdate' => $request->datenow])
        ->update(['time_in' => $request->time_in, 'work_img' => ($request->avatar ? $request->imgname : null),
        'in_details' => $request->in_details]);
        
        return response()->json(Worktime::where(['check_by' => $owner->id, 'work_startdate' => $request->datenow])->firstOrFail(), 200);
    }

    public function checkout(Request $request) {
        // save image to folder
        $imgname = json_decode($request->imgname, true);
        $i = 0;
        if($files = $request->avatar) {
            foreach($files as $file) {  
                $image_resize = Image::make($file);
                $image_resize->resize(300, 300);
                $image_resize->save(storage_path('public/'.$imgname[$i].'.jpg'));
                $i++;
            }
        }

        $credentials = JWT::decode($request->get('token'), env('JWT_SECRET'), ['HS256']);
        $owner = Employee::select('id')->where('emp_code', $credentials->sub)->first();

        $worktimes = Worktime::where(['check_by' => $owner->id, 'work_startdate' => $request->datenow])
        ->update(['time_out' => $request->time_out, 'out_img' => ($request->avatar ? $request->imgname : null),
        'out_details' => $request->out_details]);
        
        return response()->json(Worktime::where(['check_by' => $owner->id, 'work_startdate' => $request->datenow])->firstOrFail(), 200);
    }

    public function confirmCheckin() {
        $worktimes = DB::select('select wt.*, 
        ea.emp_scode as empc_scode, 
        ea.emp_code as empc_code, 
        ea.emp_name as empc_name, 
        ea.emp_lastname as empc_lastname, 
        eb.emp_scode as empa_scode, 
        eb.emp_code as empa_code, 
        eb.emp_name as empa_name, 
        eb.emp_lastname as empa_lastname
        FROM worktimes as wt 
        left join employees as ea on wt.check_by = ea.id
        left join employees as eb on wt.add_by = eb.id
        WHERE wt.time_in is not null and wt.status = 0');
        return response()->json($worktimes, 200);
    }

    public function searchConfirmCheckin(Request $request) {
        $worktimes = DB::select('select wt.*, 
        ea.emp_scode as empc_scode, 
        ea.emp_code as empc_code, 
        ea.emp_name as empc_name, 
        ea.emp_lastname as empc_lastname, 
        eb.emp_scode as empa_scode, 
        eb.emp_code as empa_code, 
        eb.emp_name as empa_name, 
        eb.emp_lastname as empa_lastname
        FROM worktimes as wt 
        left join employees as ea on wt.check_by = ea.id
        left join employees as eb on wt.add_by = eb.id
        WHERE wt.time_in is not null and wt.status = 0 and wt.work_startdate between \''. $request->start . '\' and \'' . $request->end . '\'');
        return response()->json($worktimes, 200);
    }

    public function confirmCheckout() {
        $worktimes = DB::select('select wt.*, 
        ea.emp_scode as empc_scode, 
        ea.emp_code as empc_code, 
        ea.emp_name as empc_name, 
        ea.emp_lastname as empc_lastname, 
        eb.emp_scode as empa_scode, 
        eb.emp_code as empa_code, 
        eb.emp_name as empa_name, 
        eb.emp_lastname as empa_lastname
        FROM worktimes as wt 
        left join employees as ea on wt.check_by = ea.id
        left join employees as eb on wt.add_by = eb.id
        WHERE wt.time_in is not null and wt.status = 1');
        return response()->json($worktimes, 200);
    }

    public function searchConfirmCheckout(Request $request) {
        $worktimes = DB::select('select wt.*, 
        ea.emp_scode as empc_scode, 
        ea.emp_code as empc_code, 
        ea.emp_name as empc_name, 
        ea.emp_lastname as empc_lastname, 
        eb.emp_scode as empa_scode, 
        eb.emp_code as empa_code, 
        eb.emp_name as empa_name, 
        eb.emp_lastname as empa_lastname
        FROM worktimes as wt 
        left join employees as ea on wt.check_by = ea.id
        left join employees as eb on wt.add_by = eb.id
        WHERE wt.time_in is not null and wt.status = 1 and wt.work_startdate between \''. $request->start . '\' and \'' . $request->end . '\'');
        return response()->json($worktimes, 200);
    }

    public function saveCheckin($workid, Request $request) {
        $worktimes = Worktime::where(['work_id' => $workid])->update([
            'status' => 1
        ]);
        $worktimesConfirm = $this->confirmCheckin();
        return $worktimesConfirm;
    }

    public function get_avatar($name) {
        $avatar_path = storage_path('public') . '/' . $name . '.jpg';
        if (file_exists($avatar_path)) {
          $file = file_get_contents($avatar_path);
          return response($file, 200)->header('Content-Type', 'image/jpeg');
        }

        return response()->json([
            "message" => "cannot get images"
        ]);
    }
}