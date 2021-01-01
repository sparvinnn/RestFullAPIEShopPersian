<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator  =   Validator::make($request->all(), [
            'name' => 'required',
            'county' => 'required',
            'city' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json(["status" => "failed", "validation_errors" => $validator->errors()]);
        }

        $inputs = $request->all();

        $branch   =   Branch::create($inputs);

        if(!is_null($branch)) {
            return response()->json(["status" => "success", "message" => "Success! registration completed", "data" => $branch]);
        }
        else {
            return response()->json(["status" => "failed", "message" => "Registration failed!"]);
        }
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
        $branch = Branch::find($id);
        if(!is_null($branch)) {
            DB::beginTransaction();
            try{
                if($request->name) $branch->name = $request->name;
                if($request->phones) $branch->phones = $request->phones;
                if($request->county) $branch->county = $request->county;
                if($request->city) $branch->city = $request->city;
                if($request->address) $branch->address = $request->address;
                if($request->postal_code) $branch->postal_code = $request->postal_code;
                if($request->fax) $branch->fax = $request->fax;
                if($request->status) $branch->status = $request->status;
                $branch->save();
                DB::commit();
                return response()->json(["status" => "success", "data" => $branch]);
            }catch (Exception $exception){
                DB::rollBack();
                return response()->json(["status" => "failed", "message" => $exception]);
            }
        }
        else {
            DB::rollBack();
            return response()->json(["status" => "failed", "message" => "Whoops! no branch found"]);
        }
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

    public function search(Request $request){
        $id = $request->id;
        $name = $request->name;
        $phones = $request->phones;
        $county = $request->county;
        $city = $request->city;
        $address = $request->address;
        $postal_code = $request->postal_code;
        $fax = $request->fax;
        $status = $request->status;

        try{
            $list = Branch::
                when($status, function ($q, $status) {
                    return $q->where('status', $status);
                })
                ->when($id, function ($q, $id) {
                    return $q->where('id', $id);
                })
                ->when($name, function ($q, $name) {
                    return $q->where('name', $name);
                })
                ->when($phones, function ($q, $phones) {
                    return $q->where('phones', $phones);
                })
                ->when($address, function ($q, $address) {
                    return $q->where('address', $address);
                })
                ->when($postal_code, function ($q, $postal_code) {
                    return $q->where('postal_code', $postal_code);
                })
                ->when($fax, function ($q, $fax) {
                    return $q->where('fax', $fax);
                })
                ->when($county, function ($q, $county) {
                    return $q->where('county', $county);
                })
                ->when($city, function ($q, $city) {
                    return $q->where('city', $city);
                })
                ->orderBy('created_at')
                ->get([
                    'id',
                    'name',
                    'phones',
                    'county',
                    'city',
                    'address',
                    'postal_code',
                    'fax',
                    'status'
                ]);

            $response = [
                'status' => true,
                'msg' => 'list successfully get.',
                'data' => $list
            ];
            return response()->json($response);

        }catch(Exception $e){
            return response($e, 202);
        }
    }

}
