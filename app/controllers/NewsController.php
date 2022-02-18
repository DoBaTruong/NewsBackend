<?php
namespace app\controllers;

use app\helpers\ImageHelper;
use app\helpers\StringHelper;
use app\repostories\NewsRepostory;
use libs\Request;
use libs\Response;

class NewsController extends Controller
{
    public function getBySlug($slug)
    {
        $news = NewsRepostory::getBySlug($slug);

        NewsRepostory::updateNewsViews($news['id'], ++$news['viewer']);

        $relateds = NewsRepostory::getRelated($news['category_id']);

        Response::json(200,[
            'code' => 200,
            'news' => $news,
            'relateds' => $relateds
        ]);
    }

    public function getAdminBySlug($slug)
    {
        $news = NewsRepostory::getBySlug($slug);

        Response::json(200,[
            'code' => 200,
            'news' => $news
        ]);
    }

    public function get($page, $limit)
    {
        $companies = NewsRepostory::get($page, $limit);
        $total = NewsRepostory::total();
        Response::json(200, [
            'code' => 200,
            'news' => $companies,
            'total' => $total
        ]);
    }

    public function create()
    {
        $title = Request::get('title');
        $slug = StringHelper::slug($title);
        $abstract = Request::get('abstract');
        $photo = Request::get('photo');
        $content = Request::get('content');
        $authors = Request::get('authors');
        $featured = Request::get('featured');
        $category_id = Request::get('category_id');
        $time = Request::get('published_at');
        settype($time, 'integer');
        $published_at = date('Y-m-d H:i:s', $time);
        settype($category_id, 'integer');
        settype($featured, 'integer');
        $photo = htmlspecialchars(ImageHelper::save(Request::get('photo'), 'img/news', $slug)); 
        $news = [
            'title' => $title,
            'slug' => $slug,
            'abstract' => $abstract,
            'photo' => $photo,
            'category_id' => $category_id,
            'featured' => $featured,
            'authors' => $authors,
            'published_at' => $published_at
        ];

        $detail = [
            'content' => $content
        ];

        $result = NewsRepostory::create($news, $detail);

        Response::json(200, [
            'code' => 200,
            'message' => 'Danh mục đã tạo thành công.',
            'result' => $result
        ]);
    }
    
    public function update($id)
    {
        $data = $detail = [];
        $title = Request::get('title');
        if($title) {
            $data['title'] = $title;
            $slug = StringHelper::slug($title);
            $data['slug'] = $slug;
        }
        $abstract = Request::get('abstract');
        if($abstract) {
            $data['abstract'] = $abstract;
        }
        $photo = Request::get('photo');
        if($photo && strpos($photo, 'base64') !== false) {
            $photo = htmlspecialchars(ImageHelper::save($photo, 'img/news', $slug)); 
            $data['photo'] = $photo;
        }
        $content = Request::get('content');
        if($content) {
            $detail['content'] = $detail;
        }
        $authors = Request::get('authors');
        if($authors) {
            $data['authors'] = $authors;
        }
        $featured = Request::get('featured');
        if($featured) {
            $data['featured'] = $featured;
            settype($featured, 'integer');
        }
        $category_id = Request::get('category_id');
        if($category_id) {
            $data['category_id'] = $category_id;
            settype($category_id, 'integer');
        }
        $time = Request::get('published_at');
        if($time) {
            settype($time, 'integer');
            $published_at = date('Y-m-d H:i:s', $time);
            $data['published_at'] = $published_at;
        }

        NewsRepostory::update((int) $id, $data, $detail);

        $data['id'] = $id;

        Response::json(200, [
            'code' => 200,
            'result' => $data
        ]);
    }

    public function delete()
    {
        $data = Request::get('id');

        NewsRepostory::delete($data);

        Response::json(200,[
            'code' => 200,
            'message' => 'Đã xóa danh mục !'
        ]);
    }

    public function checkTitle()
    {
        $title = Request::get('title');
        $slug = StringHelper::slug($title);
        $type = Request::get('type');

        if ($type === 'update') {
            $id = Request::get('id');
        } else {
            $id = 0;
        }

        $status = NewsRepostory::checkTitle($slug, $id) ? false : true;

        Response::json(200, [
            'code' => 200,
            'hasTitle' => $status
        ]);
    }
}
