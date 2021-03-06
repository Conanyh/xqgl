<?php

namespace App\Http\Controllers\Admin;

use App\Models\ProgramUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProgramUsersController extends Controller
{
    public function index(ProgramUser $programUser)
    {
        $programUsers = $programUser->paginate(15);
        return view('admin.programUser.index', compact('programUsers'));
    }

    public function store(Request $request)
    {
        //
    }

    public function show(ProgramUser $programUser)
    {
        $matters = $programUser->matters()->paginate();
        return view('admin.programUser.show', compact('programUser', 'matters'));
    }

    public function destroy(ProgramUser $programUser)
    {
        $programUser->delete();

        return response()->json(['status' => 1, 'msg' => '删除成功']);
    }
}
