<?php
namespace app\repostories;

use app\exceptions\InternalServerException;
use app\helpers\ImageHelper;
use app\models\CompanyModel;
use libs\Response;

class CompanyRepostory
{
    public static function get($page, $limit)
    {
        $model = new CompanyModel();
        $from = $limit * $page - $limit;
        $model->limit([$from, $limit]);
        try {
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

    public static function create($data)
    {
        $model = new CompanyModel();
        try {
            $company = $model->create($data);
            return $company;
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
        $model = new CompanyModel();
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

    public static function delete($data)
    {
        $model = new CompanyModel();

        try {
            if(is_array($data) && count($data) > 1) {
                $model->whereIn('id', $data);
                $companies = $model->get();
            } else {
                if(is_array($data)) {
                    $id = $data[0];
                } else {
                    $id = $data;
                }
                $companies = [$model->getById($id)];
            }
            foreach($companies as $item) {
                ImageHelper::remove($item['logo']);
            }   
            $status = $model->delete($data);
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

    public static function update($id, $data)
    {
        $model = new CompanyModel();
        try {
            $company = $model->update($id, $data);
            return $company;
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
        $model = new CompanyModel();
        $model->where(['slug', $slug]);
        $model->where(['id', '!=', $id]);
        return $model->first() ?? false;
    }
}
