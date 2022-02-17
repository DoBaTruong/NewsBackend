<?php
namespace app\controllers;

use app\repostories\SearchRepostory;
use libs\Response;

class SearchController extends Controller
{
    public function getNews($category, $keyword, $page, $limit)
    {
        // if($category = 0)
        // $results = SearchRepostory::getNews($category, $keyword, $page, $limit);
        $results = [];

        if($category == 0) {
            $results = SearchRepostory::getNewsByKeyword($keyword, $page, $limit);
        } 
        
        Response::json(200, [
            'code' => 200,
            'news' => $results['news'],
            'total' => $results['total']
        ]);
    }
}
