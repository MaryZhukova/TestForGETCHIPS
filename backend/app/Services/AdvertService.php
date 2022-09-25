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

    public function getList($data)
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

        return Advert::with('file')
            ->orderBy($order, $sort)
            ->paginate(10);
    }


    public function addPhoto($arFiles, $advert_id)
    {
        foreach ($arFiles as $file) {
            $path = Storage::putFile('images', $file);
            $advertPhoto = new File();
            $advertPhoto->photo = $path;
            $advertPhoto->advert_id = $advert_id;
            $advertPhoto->save();
        }
    }


    public function createAdvert($data)
    {
        $advert = new Advert();
        $advert->title = $data['title'];
        $advert->description = $data['description'];
        $advert->user_id = auth('sanctum')->user()->id;
        $advert->public_date = Carbon::now();
        $advert->save();

        if (isset($data['files'])) {
            self::addPhoto($data['files'], $advert->id);
        }

        return $this->jsonSuccess($advert);
    }

    public function showAdvertByOwner($id)
    {
        if (!$advert = Advert::with("file")->find($id)) {
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


    public function deleteAdvert($id)
    {
        if (!$advert = Advert::find($id)) {
            return $this->jsonError(__('advert.not_found'));
        }
        if ($advert->user->id !== auth('sanctum')->user()->id) {
            return $this->jsonError(__('advert.not_owner'));
        }
        return $this->jsonSuccess($advert->delete());
    }

}
