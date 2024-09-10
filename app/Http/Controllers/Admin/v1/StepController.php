<?php

namespace App\Http\Controllers\Admin\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\StepResourceCollection;
use App\Models\Step;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class StepController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
//        $supervisors = Supervisor::where('role_id', '=', 3)->paginate(10);
        $supervisors = DB::table('steps')
            ->orderBy('id', 'desc')
            ->paginate(10);
        return response()->json(
            new StepResourceCollection($supervisors)
            , 200);
    }
    public function update(Request $request, $id)
    {
        $step =Step::query()->where('id', '=', $id)->get();
//        return $step;
        if ($step->count() == 0 ) {
            return response()->json([
                'message' => 'رکورد مورد نظر یافت نشد'
            ]);
        } else {
            $this->validate($request, [
                'execution_time' => 'required|integer|between:1,99'
            ]);
                try {
                    $step->first()->update([
                        'execution_time' => $request->input('execution_time'),
                    ]);
                    return response()->json([
                        'data' => [
                            'message' => 'رکورد مورد نظر با موفقیت ویرایش شد'
                        ],
                    ], 201);
                } catch (\Exception $e) {
                    return response()->json([
                        'data' => [
                            'message' => 'خطا در ویرایش اطلاعات'
                        ],
                    ], 400);
                }
        }
    }
}
