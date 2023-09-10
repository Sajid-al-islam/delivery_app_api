<?php

namespace App\Http\Controllers;

use App\Models\Percel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PercelController extends Controller
{
    public function all()
    {
        if (request()->has('paginate')) {

            $paginate = (int) request()->paginate;
        }else {
            $paginate = 10;
        }
        // $orderBy = request()->orderBy;
        // $orderByType = request()->orderByType;
        $query = Percel::where('status', 1);

        if (request()->has('search_key')) {
            $key = request()->search_key;
            $query->where(function ($q) use ($key) {
                return $q->where('id', $key)
                    ->orWhere('type', $key)
                    ->orWhere('offer_price', $key)
                    ->orWhere('offer_price', 'LIKE', '%' . $key . '%')
                    ->orWhere('type', 'LIKE', '%' . $key . '%');
            });
        }

        $users = $query->paginate($paginate);
        return response()->json($users);
    }

    public function show($id)
    {
        $data = Percel::where('id',$id)->first();
        if(!$data){
            return response()->json([
                'err_message' => 'not found',
                'errors' => ['role'=>['data not found']],
            ], 422);
        }
        return response()->json($data,200);
    }

    public function store()
    {
        $validator = Validator::make(request()->all(), [
            'driver_id' => ['required'],
            'type' => ['required'],
            'offer_price' => ['required'],
            'percel_status' => ['required'],
            'percel_payment_status' => ['required'],
            'delivery_point' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'err_message' => 'validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = new Percel();
        $data->driver_id = request()->driver_id;
        $data->type = request()->type;
        $data->offer_price = request()->offer_price;
        $data->offer_price = request()->offer_price;
        $data->category_id = request()->category_id;
        $data->percel_status = request()->percel_status;
        $data->percel_payment_status = request()->percel_payment_status;
        $data->delivery_point = request()->delivery_point;
        $data->delivery_date = Carbon::parse(request()->delivery_point)->toDateTimeString();
        $data->status = 1;
        $data->save();

        return response()->json([
            "message" => "percel created successfully!"
        ], 200);
    }

    

    public function update()
    {
        $data = Percel::find(request()->id);
        if(!$data){
            return response()->json([
                'err_message' => 'validation error',
                'errors' => ['name'=>['user_role not found by given id '.(request()->id?request()->id:'null')]],
            ], 422);
        }

        $validator = Validator::make(request()->all(), [
            'name' => ['required'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'err_message' => 'validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data->name = request()->name;
        $data->status = 1;
        $data->created_by = auth()->user()->id;
        $data->save();

        return response()->json($data, 200);
    }

    public function soft_delete()
    {
        $validator = Validator::make(request()->all(), [
            'id' => ['required','exists:percel_categories,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'err_message' => 'validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = Percel::find(request()->id);
        $data->status = 0;
        $data->save();

        return response()->json([
            'result' => 'deactivated',
        ], 200);
    }

    public function destroy()
    {
        $validator = Validator::make(request()->all(), [
            'id' => ['required','exists:percel_categories,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'err_message' => 'validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = Percel::where(request()->id)->delete();

        return response()->json([
            'result' => 'deleted',
        ], 200);
    }

    public function restore()
    {
        $validator = Validator::make(request()->all(), [
            'id' => ['required','exists:percel_categories,id'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'err_message' => 'validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = Percel::find(request()->id);
        $data->status = 1;
        $data->save();

        return response()->json([
            'result' => 'activated',
        ], 200);
    }
}
