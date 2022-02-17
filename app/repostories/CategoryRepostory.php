<?php
namespace app\repostories;

use app\exceptions\InternalServerException;
use app\helpers\ImageHelper;
use app\models\CategoryModel;
use libs\Response;

class CategoryRepostory
{
    public static function getAll()
    {
        $model = new CategoryModel();
        try {
            $result = $model->all();
            return $result;
        } catch (\Exception $ex) {
            $excep = new InternalServerException();
            Response::json(200,[
                'code' => $excep->getCode(),
                'message' => $excep->getMessage()
            ]);
            exit();
        }
    }

    public static function create($data)
    {
        $model = new CategoryModel();
        try {
            $category = $model->create($data);
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

    public static function update($id, $data)
    {
        $model = new CategoryModel();
        try {
            $category = $model->update($id, $data);
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

    public static function delete($data)
    {
        $cateModel = new CategoryModel();

        try {
            if(is_array($data) && count($data) > 1) {
                $cateModel->whereIn('id', $data);
                $categories = $cateModel->get();
            } else {
                if(is_array($data)) {
                    $id = $data[0];
                } else {
                    $id = $data;
                }
                $categories = [$cateModel->getById($id)];
            }
            foreach($categories as $item) {
                ImageHelper::remove($item['photo']);
            }   
            $status = $cateModel->delete($data);
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

    public static function checkName($slug, $id = 0)
    {
        $model = new CategoryModel();
        $model->where(['slug', $slug]);
        $model->where(['id', '!=', $id]);
        return $model->first() ?? false;
    }
}