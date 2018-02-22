<?php

namespace App\Http\Controllers\Tasks;

use App\Task;
use Illuminate\Http\Request;
use App\User;
use App\Profile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function getUser (){
        $user = User::findOrFail(Auth::id());
        $profile = Profile::where('user_id',Auth::id())->first();
        $role = DB::table('role_user')
            ->leftJoin('roles','role_user.role_id','=','roles.id')
            ->where('role_user.user_id',Auth::id())
            ->first();
        $data = [
            'user'=>$user,
            'profile'=>$profile,
            'role'=>$role
        ];
        return $data;
    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $data = $this->getUser();
        $newtask = new Task();
        $tasks = Task::orderBy('created_at','desc')
            ->paginate(10);
        return view('tasks.index',compact('data','newtask','tasks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validate($request,[
            'name'=>'required|unique:tasks',
            'description'=>'required',
            'start_date'=>'required',
            'end_date'=>'required'
        ]);

        $task = new Task();
        $task->name = $request->input('name');
        $task->description = $request->input('description');
        $task->start_date = $request->input('start_date');
        $task->end_date = $request->input('end_date');
        $task->created_by = Auth::id();
        $task->save();

        return redirect()->back()->with('message','Task created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
