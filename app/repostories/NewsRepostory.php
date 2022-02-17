<?php
namespace app\repostories;

use app\exceptions\InternalServerException;
use app\helpers\ImageHelper;
use app\models\DetailNewsModel;
use app\models\NewsModel;
use libs\Response;

class NewsRepostory
{
    public static function get($page, $limit)
    {
        $model = new NewsModel();
        $from = $limit * $page - $limit;
        $model->limit([$from, $limit]);
        try {
            $news = $model->get();
            return $news;
        } catch (\Exception $ex) {
            $excep = new InternalServerException();
            Response::json(200,[
                'code' => $excep->getCode(),
                'message' => $excep->getMessage()
            ]);
            exit();
        }
    }

    public static function total()
    {
        $model = new NewsModel();
        try {
            $total = $model->total();
            return $total;
        } catch (\Exception $ex) {
            $excep = new InternalServerException();
            Response::json(200,[
                'code' => $excep->getCode(),
                'message' => $excep->getMessage()
            ]);
            exit();
        }
    }

    public static function create($news, $detail)
    {
        $newsModel = new NewsModel();
        $detailModel = new DetailNewsModel();
        try {
            $newsResult = $newsModel->create($news);
            $detail['news_id'] = $newsResult['id'];
            $detailResult = $detailModel->create($detail);
            $newsResult['content'] = $detailResult['content'];
            return $newsResult;
        } catch (\Exception $ex) {
            $excep = new InternalServerException();
            Response::json(200,[
                'code' => $excep->getCode(),
                'message' => $excep->getMessage()
            ]);
            exit();
        }
    }

    public static function delete($data)
    {
        $modelNews = new NewsModel();
        $modelDetail = new DetailNewsModel();

        try {
            if(is_array($data) && count($data) > 1) {
                $modelNews->whereIn('id', $data);
                $news = $modelNews->get();
            } else {
                if(is_array($data)) {
                    $id = $data[0];
                } else {
                    $id = $data;
                }
                $news = [$modelNews->getById($id)];
            }

            foreach($news as $item) {
                ImageHelper::remove($item['photo']);
            } 
            
            $status = $modelDetail->delete($data, 'news_id');
            $status = $modelNews->delete($data);
            return $status;
        } catch (\Exception $ex) {
            $excep = new InternalServerException();
            Response::json(200,[
                'code' => $excep->getCode(),
                'message' => $excep->getMessage()
            ]);
            exit();
        }
    }

    public static function getBySlug($slug)
    {
        $model = new NewsModel();
        $detailModel = new DetailNewsModel();
        $model->where(['slug', $slug]);

        try {
            $category = $model->first();
            $detail = $detailModel->getById($category['id']);
            $category['content'] = $detail['content'];

            return $category;
        } catch (\Exception $ex) {
            $excep = new InternalServerException();
            Response::json(200,[
                'code' => $excep->getCode(),
                'message' => $excep->getMessage()
            ]);
            exit();
        }
    }

    public static function update($id, $data, $detail)
    {
        $model = new NewsModel();
        $modeldetail = new DetailNewsModel();
        try {
            $news = $model->update($id, $data);
            if(count($detail) > 1) {
                $detail = $modeldetail->update($id, $detail);
            }
            return $news;
        } catch (\Exception $ex) {
            $excep = new InternalServerException();
            Response::json(200,[
                'code' => $excep->getCode(),
                'message' => $excep->getMessage()
            ]);
            exit();
        }
    }

    public static function checkTitle($slug, $id = 0)
    {
        $model = new NewsModel();
        $model->where(['slug', $slug]);
        $model->where(['id', '!=', $id]);
        return $model->first() ?? false;
    }
}
