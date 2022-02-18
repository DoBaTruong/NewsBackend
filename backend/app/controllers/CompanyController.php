<?php
namespace app\controllers;

use app\helpers\ImageHelper;
use app\helpers\StringHelper;
use app\repostories\CompanyRepostory;
use libs\Request;
use libs\Response;

class CompanyController extends Controller
{
    public function get($page, $limit)
    {
        $companies = CompanyRepostory::get($page, $limit);
        $total = CompanyRepostory::total();
        Response::json(200, [
            'code' => 200,
            'companies' => $companies,
            'total' => $total
        ]);
    }

    public function create()
    {
        $name = Request::get('name');
        $slug = StringHelper::slug($name);
        $descript = Request::get('descript');
        $address = Request::get('address');
        $contact = Request::get('contact');
        $logo = htmlspecialchars(ImageHelper::save(Request::get('logo'), 'img/companies', $slug)); 
        $data = [
            'name' => $name,
            'slug' => $slug,
            'descript' => $descript,
            'logo' => $logo,
            'contact' => $contact,
            'address' => $address
        ];

        $result = CompanyRepostory::create($data);

        Response::json(200, [
            'code' => 200,
            'message' => 'Công ty đã thêm thành công.',
            'result' => $result
        ]);
    }

    public function update($id)
    {
        $data = [];

        $name = Request::get('name');
        if($name) {
            $slug = StringHelper::slug($name);
            $data['name'] = $name;
            $data['slug'] = $slug;
        }
        
        $descript = Request::get('descript');
        if($name) {
            $slug = StringHelper::slug($name);
            $data['name'] = $name;
            $data['slug'] = $slug;
        }
        $address = Request::get('address');
        if($address) {
            $data['address'] = $address;
        }
        $contact = Request::get('contact');
        if($contact) {
            $data['contact'] = $contact;
        }
        $logo = Request::get('logo');

        if($logo) {
            $path = ImageHelper::save($logo, 'img/companies', $slug);
            $data['logo'] = $path;
        }

        CompanyRepostory::update((int) $id, $data);

        $data['id'] = $id;

        Response::json(200, [
            'code' => 200,
            'result' => $data
        ]);
    }
    
    public function delete()
    {
        $data = Request::get('id');

       CompanyRepostory::delete($data);

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
        if (empty($type)) {
            $id = 0;
        } else {
            $id = Request::get('id');
        }

        $status = CompanyRepostory::checkName($slug, $id) ? false : true;

        Response::json(200, [
            'code' => 200,
            'hasName' => $status
        ]);
    }
    
}
