<?php


namespace App\Services;


use App\Models\Advert;
use App\Models\Filestorage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use function Symfony\Component\Console\Style\success;

class AdvertService
{
    public static function getList($data)
    {
        $orderVariants = [
            'title',
            'public_date'
        ];
        $sortVariants = [
            'asc',
            'desc'
        ];
        if (isset($data['order']) && (in_array($data['order'], $orderVariants))
            && isset($data['sort']) && (in_array($data['sort'], $sortVariants))
        ) {
            $order = $data['order'];
            $sort = $data['sort'];
        } else {
            $order = $orderVariants['0'];
            $sort = $sortVariants['0'];
        }

        $list = Advert::all();

        return $list;
    }


    public static function createAdvert($data)
    {
        $arPhoto = [];
        if (isset($data['files'])) {
            foreach ($data['files'] as $file) {
                $path = Storage::putFile('images', $file);
                $arPhoto[] = $path;
            }
        }

        $advert = new Advert();
        $advert->title = $data['title'];
        $advert->description = $data['description'];
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

    }

    public function showAdvertByOwner($id)
    {
        $advert = Advert::with("filestorages")->find($id);
        if ($advert) {
            if ($advert->user->id === auth('sanctum')->user()->id) {
                return [
                    'status' => 200,
                    'success' => true,
                    'data' => $advert
                ];
            } else {
                return [
                    "status" => 400,
                    "success" => false,
                    "message" => __('advert.not_owner'),
                ];
            }
        }

        return [
            "status" => 404,
            "success" => false,
            "message" => __('advert.not_found'),

        ];
    }

    public static function updateAdvert($id, $data)
    {
        $advert = Advert::find($id);
        if ($advert) {
            if ($advert->user->id === auth('sanctum')->user()->id) {
                $advert->update($data);
            }
            return $advert;
        }

    }

    public static function deleteAdvert($id)
    {
        $advert = Advert::find($id);
        if ($advert) {
            if ($advert->user->id === auth('sanctum')->user()->id) {
                $advert->delete();
                return [
                    "status" => 200,
                    "success" => false,
                    "message" => __('advert.delete'),
                ];
            }

            return [
                "status" => 200,
                "success" => false,
                "message" => __('advert.del_error'),
            ];
        }
    }

}
