<?php
namespace app\controllers;

use app\helpers\ImageHelper;
use app\helpers\StringHelper;
use app\repostories\CategoryRepostory;
use libs\Request;
use libs\Response;

class CategoryController extends Controller
{
    public function getAll()
    {
        $result = CategoryRepostory::getAll();

        Response::json(200, [
            'code' => 200,
            'categories' => $result
        ]);
    }

    public function create()
    {
        $name = Request::get('name');
        $slug = StringHelper::slug($name);
        $parent_id = Request::get('parent_id');
        settype($parent_id, 'integer');
        $photo = htmlspecialchars(ImageHelper::save(Request::get('photo'), 'img/categories', $slug)); 
        $data = [
            'name' => $name,
            'slug' => $slug,
            'parent_id' => $parent_id,
            'photo' => $photo
        ];
        $result = CategoryRepostory::create($data);

        Response::json(200, [
            'code' => 200,
            'message' => 'Danh mục đã tạo thành công.',
            'result' => $result
        ]);
    }

    public function update($id)
    {
        $name = Request::get('name');
        $slug = StringHelper::slug($name);
        $parentId = Request::get('parent_id');
        settype($parentId, 'integer');
        $photo = Request::get('photo');

        $data = [
            'name' => $name,
            'slug' => $slug,
            'parent_id' => $parentId,
        ];

        if($photo) {
            $path = ImageHelper::save($photo, 'img/categories', $slug);
            $data['photo'] = $path;
        }

        CategoryRepostory::update((int) $id, $data);

        $data['id'] = $id;

        Response::json(200, [
            'code' => 200,
            'result' => $data
        ]);
    }

    public function delete()
    {
        $data = Request::get('id');

        CategoryRepostory::delete($data);

        Response::json(200,[
            'code' => 200,
            'message' => 'Đã xóa danh mục !'
        ]);
    }

    public function checkName()
    {
        $name = Request::get('name');
        $slug = StringHelper::slug($name);
        $type = Request::get('type');
        if ($type === 'update') {
            $id = Request::get('id');
        } else {
            $id = 0;
        }

        $status = CategoryRepostory::checkName($slug, $id) ? false : true;

        Response::json(200, [
            'code' => 200,
            'hasName' => $status
        ]);
    }
}