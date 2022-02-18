<?php
namespace app\repostories;

use app\exceptions\InternalServerException;
use app\models\CompanyModel;
use app\models\NewsModel;
use libs\Response;

class HomeRepostory 
{
    public static function getSlide()
    {
        try {
            $model = new NewsModel();
            $date = date('Y-m-d H:i:s', time());
            $model->where(['published_at', '<=', $date]);
            $model->where(['featured', 1]);
            $model->limit([0, 5]);
            $model->order(['published_at', 'DESC']);
            $slides = $model->get();
            return $slides;
        } catch (\Exception $ex) {
            $excep = new InternalServerException();
            Response::json(200,[
                'code' => $excep->getCode(),
                'message' => $excep->getMessage()
            ]);
            exit();
        }
    }

    public static function getNewsReadALot()
    {
        $model = new NewsModel();

        try {
            $date = date('Y-m-d H:i:s', time());
            $model->where(['published_at', '<=', $date]);
            $model->where(['viewer', '>=', 0]);
            $model->limit([0, 5]);
            $model->order(['viewer', 'DESC']);
            $reads = $model->get();
            return $reads;
        } catch (\Exception $ex) {
            $excep = new InternalServerException();
            Response::json(200,[
                'code' => $excep->getCode(),
                'message' => $excep->getMessage()
            ]);
            exit();
        }
    }

    public static function getCompany()
    {
        $model = new CompanyModel();

        try {
            $model->limit([0, 6]);
            $companies = $model->get();
            return $companies;
        } catch (\Exception $ex) {
            $excep = new InternalServerException();
            Response::json(200,[
                'code' => $excep->getCode(),
                'message' => $excep->getMessage()
            ]);
            exit();
        }
    }

    public static function getNews()
    {
        $model = new NewsModel();

        try {
            $date = date('Y-m-d H:i:s', time());
            $model->where(['published_at', '<=', $date]);
            $model->order(['published_at', 'DESC']);
            $model->limit([0, 6]);
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

    public static function getNewsComment()
    {
        $model = new NewsModel();

        try {
            $date = date('Y-m-d H:i:s', time());
            $model->where(['published_at', '<=', $date]);
            $model->limit([0, 6]);
            $model->order(['comment', 'DESC']);
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
}
