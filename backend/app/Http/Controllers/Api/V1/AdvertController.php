<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\ApiResponseHelpers;
use App\Http\Requests\Api\V1\GetAdvertsRequest;
use App\Services\AdvertService;
use Illuminate\Http\Request;
use App\Http\Requests\Api\V1\CreateAdvertRequest;
use App\Http\Requests\Api\V1\UpdateAdvertRequest;

use App\Http\Controllers\Controller;
use App\Http\Controllers\API\V1\FileController;
use App\Models\Advert;
use App\Models\File;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdvertController extends Controller
{

    use ApiResponseHelpers;

    /**
     * Show list with adverts
     * @param GetAdvertsRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(GetAdvertsRequest $request, AdvertService $advert)
    {
        return $advert->getList($request->validated());

    }

    /**
     * Create new advert by auth user
     * @param CreateAdvertRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateAdvertRequest $request, AdvertService $advert)
    {

        return $advert->createAdvert($request->validated());

    }


    /**
     * Show owner advert
     * @param $id - advert id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id, AdvertService $advert)
    {
        return $advert->showAdvertByOwner($id);
    }


    /**
     * Owner updated the specified advert
     * @param UpdateAdvertRequest $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateAdvertRequest $request, $id, AdvertService $advertService)
    {

        return $advertService->updateAdvert($id, $request->validated());

    }


    /**
     * Remove advert by owner.
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id, AdvertService $service)
    {
        return $service->deleteAdvert($id);
    }
}
