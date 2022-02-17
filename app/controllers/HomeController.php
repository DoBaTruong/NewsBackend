<?php 
namespace app\controllers;

use app\repostories\HomeRepostory;
use libs\Response;

class HomeController extends Controller 
{
    public function getSlide()
    {
        $slides = HomeRepostory::getSlide();

        Response::json(200, [
            'code' => 200,
            'slide' => $slides
        ]);
    }

    public function getNewsReadALot()
    {
        $reads = HomeRepostory::getNewsReadALot();

        Response::json(200, [
            'code' => 200,
            'reads' => $reads
        ]);
    }

    public function getCompany()
    {
        $companies = HomeRepostory::getCompany();

        Response::json(200, [
            'code' => 200,
            'companies' => $companies
        ]);
    }

    public function getNews()
    {
        $news = HomeRepostory::getNews();

        Response::json(200, [
            'code' => 200,
            'news' => $news
        ]);
    }

    public function getNewsComment()
    {
        $newscomment = HomeRepostory::getNewsComment();

        Response::json(200, [
            'code' => 200,
            'newscomment' => $newscomment
        ]);
    }
}