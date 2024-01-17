<?php

namespace Modules\Landingpage\app\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Landingpage\app\Models\News;
use Modules\Landingpage\app\Resources\NewsResource;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $news = News::with("images")->where("state","public")->where("type","news")->get()->take(2);
        $activity = News::with("images")->where("state","public")->where("type","activity")->get()->take(2);
        $response = [
            'success' => true,
            'message' => "list new public",
            'activity'    => NewsResource::collection( $activity),
            'news' => NewsResource::collection( $news )
        ];
        return response()->json($response, 200);

    }
    public function listNews()
    {
        $news = News::with("images")->where("state","public")->where("type","news")->paginate(12);
        $response = [
            'success' => true,
            'message' => "list new public",
            'data'    => $news
        ];
        return response()->json($response, 200);
    }
    public function listActivity()
    {
        $news = News::with("images")->where("state","public")->where("type","activity")->paginate(12);
        $response = [
            'success' => true,
            'message' => "list activity public",
            'data'    => $news
        ];
        return response()->json($response, 200);
    }
    public function NewsDetail($id)
    {
        $news = News::with("images")->findOrFail($id);
        $response = [
            'success' => true,
            'message' => "news detail",
            'data'    => $news
        ];
        return response()->json($response, 200);
    }

    

}
