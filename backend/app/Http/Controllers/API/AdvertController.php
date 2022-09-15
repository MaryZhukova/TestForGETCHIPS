<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Requests\CreateAdvertRequest;
use App\Http\Requests\UpdateAdvertRequest;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\FilestorageController;
use App\Models\Advert;
use App\Models\Filestorage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class AdvertController extends Controller
{

    /**
     * Show list of adverts
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $list = Advert::orderBy($request->input('order'), $request->input('sort'))->paginate(5);

        return response()->json([
                "success" => true,
                "status" => 200,
                "data" => $list
                /*[
                "records" => $list,
                'pagination' => [
                'total_items' => $list->total(),
                'count_pages' => $list->count(),
                'per_page' => $list->perPage(),
                'current_page' => $list->currentPage(),
                ]*/
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     * @param CreateAdvertRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateAdvertRequest $request)
    {
        $arPhoto = [];
        if ($request->hasfile('files')) {
            foreach ($request->file('files') as $file) {
                $path = Storage::putFile('images', $file);
                $arPhoto[] = $path;
            }
        }


        $advert = new Advert();
        $advert->title = $request->title;
        $advert->description = $request->description;
        $advert->user_id = auth('sanctum')->user()->id;
        $advert->public_date = Carbon::now();
        $advert->save();

        if (!empty($arPhoto)) {
            foreach ($arPhoto as $path) {
                $advertPhoto = new Filestorage();
                $advertPhoto->photo = $path;
                $advertPhoto->advert_id = $advert->id;
                $advertPhoto->save();
            }
        }

        return response()->json([
            "status" => 200,
            "success" => true,
            "message" => __('advert.success')
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
            if ($advert->user->id === auth('sanctum')->user()->id) {
                // дописать вывод фото
                return response()->json([
                    "status" => 200,
                    "success" => true,
                    "message" => "Ваше объявление",
                    "data" => $advert
                ]);
            } else {
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
    public function update(UpdateAdvertRequest $request, $id)
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
    public function delete($id)
    {
        $advert = Advert::findOrFail($id);
        if ($advert)
            $advert->delete();
        else
            return response()->json([
                "status" => 200,
                "success" => false,
                "message" => "Erorr",
                "data" => []
            ]);

        return response()->json([
            "status" => 200,
            "success" => false,
            "message" => "Success delete",
            "data" => []
        ]);
    }


}
