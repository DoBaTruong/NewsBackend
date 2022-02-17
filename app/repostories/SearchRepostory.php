<?php
namespace app\repostories;

use app\exceptions\InternalServerException;
use app\models\CategoryModel;
use app\models\NewsModel;
use libs\Response;

class SearchRepostory
{
    public static function getNews($category, $keyword, $page, $limit)
    {
        $model = new NewsModel();
        try {
            $from = $page * $limit - $limit;
            $date = date('Y-m-d H:i:s', time());
            if(!empty($category)) {
                $cate = "category_id = $category";
            } else {
                $cate = "";
            }
            $arr_keyword = explode(' ', $keyword);
            $newkey = "%".implode("%", $arr_keyword)."%";
            // if(!empty($keyword)) {
                $model->where(['title', 'LIKE', $newkey]);
            // } else {
                // $model->where(['title', 'LIKE', $newkey]);
            // }

            // $sql = "SELECT * FROM news WHERE 'title' LIKE '$newkey''";
            $news = $model->get();
            return $keyword;
        } catch (\Exception $ex) {
            $excep = new InternalServerException();
            Response::json(200,[
                'code' => $excep->getCode(),
                'message' => $excep->getMessage()
            ]);
            exit();
        }
    }

    public static function getNewsByKeyword($keyword, $page, $limit)
    {
        $model = new NewsModel();
        try {
            $from = $page * $limit - $limit;
            $date = date('Y-m-d H:i:s', time());
            $arr_keyword = explode(' ', $keyword);
            $newkey = "%".implode("%", $arr_keyword)."%";
            $model->where(['title', 'LIKE', $newkey]);
            $model->where(['published_at', '<=', $date]);
            $model->orWhere(['abstract', 'LIKE', $newkey]);
            $model->order(['published_at', 'DESC']);
            $model->limit([$from, $limit]);
            $news = $model->get();
            $total = $model->total();
            return [
                'total' => $total,
                'news' => $news,
            ];
        } catch (\Exception $ex) {
            $excep = new InternalServerException();
            Response::json(200,[
                'code' => $excep->getCode(),
                'message' => $excep->getMessage()
            ]);
            exit();
        }
    }

    public static function getNewsByCategory($slug, $page, $limit)
    {
        $model = new NewsModel();
        $cateModel = new CategoryModel();

        try {
            $date = date('Y-m-d H:i:s', time());
            $from = $page * $limit - $limit;
            $categores = $cateModel->all();
            $cateModel->where(['slug', $slug]);
            $category = $cateModel->first();
            $result = [$category['id']];
            recursive($categores, $category['id'], $result);
            if(count($result)) {
                $stringId = $result[0];
                $model->where(['category_id', $stringId]);
            } else {
                $stringId = implode(', ', $result);
                $model->where(['category_id', ' IN ', $stringId]);
            }
            $model->where(['published_at', '<=', $date]);
            $total = $model->total();
            $model->limit([$from, $limit]);
            $model->order(['published_at', 'DESC']);
            $news = $model->get();

            return [
                'total' => $total,
                'news' => $news,
            ];
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
