<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Requests\Api\V1\NewAdvertRequest;

use App\Http\Controllers\Controller;
use App\Models\Advert;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class AdvertController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
      $list = Advert::all();

        return response()->json([
            "status" => true,
            "data"  => $list
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'description' => 'required|max:1032',
            'publication_date' => 'required',
        ]);
        // Дописать обработку только трех фото
        if($validate->fails()){
            return response()->json([
                "status"    => 404,
                "success"   => false,
                "message"   => $validate->errors()
            ]);
        }


        // Дописать сохранение фото в др таблицу
        $advert = new Advert();
        $advert->title = $request->title;
        $advert->description = $request->description;
        $advert->user_id = auth('sanctum')->user()->id;
        $advert->create_date = Carbon::now();
        $advert->save();

        return response()->json([
            "status"    => 200,
            "success"   => true,
            "message"   => "Объявление добавлено"
        ]);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        dd($id);

        $advert = Advert::where('ID', $id)->get();
        if ($advert && $advert->user_id == auth('sanctum')->user()->id) {

           // дописать вывод фото
            return response()->json([
                "status" => 200,
                "success" => true,
                "message" => "Ваше объявление",
                "data" => $advert
            ]);
        }
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
