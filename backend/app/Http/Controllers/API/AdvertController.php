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
        $order = "title";
        $sort = "asc";

        $orderVariants = [
            'title',
            'public_date'
        ];
        $sortVariants = [
            'asc',
            'desc'
        ];
        if($request->input('order') && in_array($request->input('order'), $orderVariants)
            && $request->input('sort') && in_array($request->input('sort'), $sortVariants)
        ){
            $order = $request->input('order');
            $sort = $request->input('sort');
        }

        $list = Advert::with('filestorages')->orderBy($order, $sort)->paginate(10);

        return response()->json([
                "success" => true,
                "status" => 200,
                "data" => $list
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     * @param CreateAdvertRequest $request
     * @return \Illuminate\Http\JsonResponse
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
            "message" => __('advert.success'),
            "data" => $advert
        ]);


    }


    /**
     * Display the advert.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $advert = Advert::with("filestorages")->find($id);
        if ($advert) {
            if ($advert->user->id === auth('sanctum')->user()->id) {
                return response()->json([
                    "status" => 200,
                    "success" => true,
                    "message" =>  __('advert.owner'),
                    "data" => $advert
                ]);
            } else {
                return response()->json([
                    "status" => 400,
                    "success" => false,
                    "message" =>  __('advert.not_owner'),

                ]);
            }
        }
        return response()->json([
            "status" => 404,
            "success" => false,
            "message" => __('advert.not_found'),

        ]);
    }


    /**
     * Update the specified advert in storage.
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
            "message" => __('advert.update'),
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
        $advert = Advert::find($id);
        if ($advert){
            if ($advert->user->id === auth('sanctum')->user()->id) {
                $advert->delete();
                return response()->json([
                    "status" => 200,
                    "success" => false,
                    "message" =>  __('advert.success'),
                    "data" => []
                ]);
            }

            return response()->json([
                "status" => 200,
                "success" => false,
                "message" => __('advert.del_error'),
                "data" => []
            ]);
        }
    }
}
