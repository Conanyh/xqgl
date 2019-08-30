<?php

namespace App\Http\Controllers\Admin;

use App\Models\Coordinate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CoordinatesController extends Controller
{
    public function index(Coordinate $coordinate)
    {
        $coordinates = Coordinate::all();
        return view('admin.coordinates.index', compact('coordinates'));
    }

    public function create(Coordinate $coordinate)
    {
        return view('admin.coordinates.create_and_edit', compact('coordinate'));
    }

    public function store(Request $request, Coordinate $coordinate)
    {
//        $this->rules($request);
        $data = $request->all();
        $count = count($data['lng']);
        $zbArray = [];
        for ($i = 0; $i< $count; $i++ ) {
            $zb = $data['lng'][$i] .';'. $data['lat'][$i];
            array_push($zbArray, $zb);
        }
        $zbString = implode($zbArray, ',');
        $coordinates = [
            'number' => $data['number'],
            'coordinates' => $zbString
        ];

        $coordinate->fill($coordinates);
        $coordinate->save();

        return redirect()->route('admin.coordinates.index');

    }

    public function destroy(Coordinate $coordinate)
    {
        $coordinate->delete();

        return response()->json(['status' => '1', 'msg' => '删除成功']);
    }

    public function rules(Request $request)
    {
        $this->validate($request, [
            'number' => 'required|numeric|unique:coordinates',
        ], [
            'number.required' => '请输入编号',
            'number.numeric' => '编号只能是数字',
            'number.unique' => '编号已存在，请重新输入',
        ]);
    }
}
