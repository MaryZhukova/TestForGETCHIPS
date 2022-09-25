<?php


namespace App\Services;


use App\Helpers\ApiResponseHelpers;
use App\Models\Advert;
use App\Models\File;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use function Symfony\Component\Console\Style\success;

class AdvertService
{
    use ApiResponseHelpers;

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

        return Advert::with('file')->orderBy($order, $sort)->paginate(10);
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
                $advertPhoto = new File();
                $advertPhoto->photo = $path;
                $advertPhoto->advert_id = $advert->id;
                $advertPhoto->save();
            }
        }

    }

    public function showAdvertByOwner($id)
    {
        if(!$advert = Advert::with("files")->find($id)){
            return $this->jsonError(__('advert.not_found'));
        }

        if ($advert->user->id !== auth('sanctum')->user()->id) {
           return $this->jsonErrorAuth(__('advert.not_owner'));
        }

        return $this->jsonSuccess($advert);
    }

    public function updateAdvert($id, $data)
    {
        if (!$advert = Advert::find($id)) {
            return $this->jsonError(__('advert.not_found'));
        }

        if ($advert->user->id !== auth('sanctum')->user()->id) {
            return $this->jsonError(__('advert.not_owner'));
        }

        return $this->jsonSuccess($advert->update($data));
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
                "success" => true,
                "message" => __('advert.del_error'),
            ];
        }
    }

}
