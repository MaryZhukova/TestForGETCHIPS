<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Requests\Api\NewAdvertRequest;
use App\Http\Requests\UpdateAdvertRequest;

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
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort = $request->input('sort');
        $order = $request->input('order');

        $list = Advert::orderBy($order, $sort)->paginate(5);

        return response()->json([
            "status" => true,
            "data" =>[
                "records" => $list,
                'pagination' => [
                'total_items' => $list->total(),
                'count_pages' => $list->count(),
                'per_page' => $list->perPage(),
                'current_page' => $list->currentPage(),
                ]
            ]
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
        if ($validate->fails()) {
            return response()->json([
                "status" => 404,
                "success" => false,
                "message" => $validate->errors()
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
            "status" => 200,
            "success" => true,
            "message" => "Объявление добавлено"
        ]);
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $advert = Advert::find($id);
        if ($advert) {
            if($advert->user->id === auth('sanctum')->user()->id){
                // дописать вывод фото
                return response()->json([
                    "status" => 200,
                    "success" => true,
                    "message" => "Ваше объявление",
                    "data" => $advert
                ]);
            }else{
                return response()->json([
                    "status" => 200,
                    "success" => false,
                    "message" => "Не ваше объявление",
                    "data" => []
                ]);
            }
        }
    }


    /**
     * Update the specified resource in storage.
     * @param UpdateAdvertRequest $request
     * @param App\Models\Advert $advert
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAdvertRequest $request,  $id)
    {

        $advert = Advert::find($id);
        if ($advert) {
            if ($advert->user->id === auth('sanctum')->user()->id) {
                $advert->update($request->validated());
            }
        }

        return response()->json([
            "status" => 200,
            "success" => false,
            "message" => "Обновлено",
            "data" => $advert
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
