<?php
namespace app\controllers;

use app\repostories\SearchRepostory;
use libs\Response;

class SearchController extends Controller
{
    public function getNews($slug, $keyword, $page, $limit)
    {
        $results = [];

        if($slug == 'all') {
            $results = SearchRepostory::getNewsByKeyword($keyword, $page, $limit);
        } else {
            $results = SearchRepostory::getNewsByCategory($slug, $page, $limit);
        }
        
        Response::json(200, [
            'code' => 200,
            'news' => $results['news'],
            'total' => $results['total'],
        ]);
    }
}
