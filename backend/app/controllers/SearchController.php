<?php
namespace app\controllers;

use app\repostories\SearchRepostory;
use libs\Response;

class SearchController extends Controller
{
    public function getNews($slug, $keyword, $page, $limit)
    {
        $results = [];

        if($slug !== 'news-hot' && $slug !== 'news-comment' && $slug !== 'news-read') {
            if($slug == 'all') {
                $results = SearchRepostory::getNewsByKeyword($keyword, $page, $limit);
            } else {
                $results = SearchRepostory::getNewsByCategory($slug, $page, $limit);
            }
        } else {
            if($slug === 'news-hot') {
                $results = SearchRepostory::getHotNews($page, $limit);
            } 

            if($slug === 'news-comment') {
                $results = SearchRepostory::getCommentNews($page, $limit);
            }

            if($slug === 'news-read') {
                $results = SearchRepostory::getReadNews($page, $limit);
            }
        }

        Response::json(200, [
            'code' => 200,
            'news' => $results['news'],
            'total' => $results['total'],
        ]);
    }
}
