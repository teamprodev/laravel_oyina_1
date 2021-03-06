<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Generation;
use App\Models\Admin\Image;
use App\Models\Admin\Video;
use Illuminate\Http\Request;
use App\Models\Admin\Post;
use App\Models\Admin\ArticleCategory;
use App\Models\Admin\Category;
use App\Models\Admin\Talk;
use App\Models\Admin\Document;
use App\Models\Admin\Useful;
use App\Models\Admin\Article;
use App\Models\Admin\Publication;
use App\Models\Admin\Symbol;
use App\Models\Admin\Announcement;
use App\Models\Admin\PublicationCategory;
use App\Models\Admin\FunnyCategory;
use App\Models\Admin\Funny;
use App\Models\Admin\Audio;
use App\Models\Admin\DocumentCategory;
use Exception;
use App\Models\Comment;
use App\Models\Admin\Audiobook;

class MainPageController extends Controller
{


    // Post routes -------------------------------------------------------------------------------------------
    public function news() {
        $categories = Category::query()->get();
        return view('site.news.news', compact('categories'));
    }

    public function singlePost(Post $post)
    {
        $post->views = $post->views + 1;
        $post->save();
        return view('site.news.single-news', ['post' => $post]);
    }

    public function category($category_slug)
    {
        $category = Category::query()
            ->orderBy('id', 'desc')
            ->where('slug', '=', $category_slug)->first();
        $post = Post::query()
            ->orderBy('id', 'desc')
            ->where('lang', '=', app()->getLocale())
            ->where('category_id', '=', $category->id)->paginate(20);

        return view('site.news.category-news', ['post' => $post, 'category' => $category]);

    }

    public function postType($id)
    {
        if ($id == 1) {
            $post = Post::query()
                ->where('lang', '=', app()->getLocale())
                ->where('type', '=', 'option2')->paginate(20);
        } elseif ($id == 2){
            $post = Post::query()
                ->where('lang', '=', app()->getLocale())
                ->where('type', '=', 'option3')->paginate(20);
        } elseif ($id == 3){
            $post = Post::query()
                ->where('lang', '=', app()->getLocale())
                ->orderBy('id', 'desc')->paginate(20);
        } elseif ($id == 4){
            $post = Post::query()
                ->where('lang', '=', app()->getLocale())
                ->orderBy('views', 'desc')->paginate(20);
        }

        return view('site.news.type-news', ['post' => $post, 'type' => $id]);

    }




    // Article routes
    public function articles()
    {
        $categories = ArticleCategory::query()->get();
        return view('site.articles.articles', compact('categories'));
    }

    public function article($id)
    {
        $comments = Comment::where('type', 'articles')->where('relation_id', $id)->where('status', 1)->get();
        $article = Article::query()->find($id);
        return view('site.articles.article')->with(compact('article'))->with(compact('comments'));
    }

    public function articleCategory($category_slug)
    {
        $category = ArticleCategory::query()
            ->where('slug', '=', $category_slug)->first();
        $articles = Article::query()->orderBy('id', 'desc')
            ->where('lang', '=', app()->getLocale())
            ->where('category_id', '=', $category->id)->paginate(20);

        return view('site.articles.category-article', compact('category', 'articles'));
    }


    //Document routes
    public function documents()
    {
        $categories = DocumentCategory::query()->get();
        return view('site.documents.documents', compact('categories'));
    }

    public function documentCategory($category_slug)
    {
        $category = DocumentCategory::query()
            ->where('slug', '=', $category_slug)->first();
        $documents = Document::query()->orderBy('id', 'desc')
            ->where('category_id', '=', $category->id)->paginate(15);
        return view('site.documents.document-category', compact('category', 'documents'));
    }



    // Interview routes
    public function interviews()
    {
        $interviews = Talk::query()->orderBy('id', 'desc')->paginate(20);
        return view('site.interviews.interviews', compact('interviews'));
    }

    public function interview($id)
    {
        $comments = Comment::where('type', 'interviews')->where('relation_id', $id)->where('status', 1)->get();
        $talk = Talk::query()->find($id);
        return view('site.interviews.interview', ['content' => $talk, 'comments' => $comments]);
    }




    // Generation routes
    public function generations()
    {
        $generations = Generation::query()
            ->orderBy('id', 'desc')
            ->simplePaginate(20);
        return view('site.generations.generations', compact('generations'));
    }

    public function generation($id)
    {
        $comments = Comment::where('type', 'generations')->where('relation_id', $id)->where('status', 1)->get();
        $generation = Generation::query()->find($id);

        return view('site.generations.generation')->with(compact('generation'))->with(compact('comments'));

    }




    // Books routes
    public function libary()
    {
        $categories = PublicationCategory::query()->get();
        $audiobooks = Audiobook::query()
            ->where('lang', '=', app()->getLocale())
            ->orderBy('id', 'desc')->limit(10)->get();
        return view('site.books.index', compact('categories', 'audiobooks'));
    }

    public function singleBook($id)
    {
        $comments = Comment::where('type', 'books')->where('relation_id', $id)->where('status', 1)->get();
        $book = Publication::query()->find($id);
        return view('site.books.singlebook')->with(compact('book'))->with(compact('comments'));
    }

    public function bookCategory($category_slug)
    {
        $category = PublicationCategory::query()
            ->where('slug', '=', $category_slug)->first();
        $books = Publication::query()->orderBy('id', 'desc')
            ->where('category_id', '=', $category->id)->paginate(20);

        return view('site.books.bookscategory', compact('category', 'books'));
    }


    public function audiobooks()
    {
        $books = Audiobook::query()->orderBy('id', 'desc')->paginate(15);
        return view('site.books.audiobooks', compact('books'));
    }

    public function audiobook($id)
    {
        $book = Audiobook::query()->find($id);
        $otherbooks = Audiobook::query()->orderBy('id', 'desc')->limit(5)->get();
        return view('site.books.audiobook', compact('book', 'otherbooks'));
    }


    // Multimedia routes
    public function multimedia() {
        $photos = Image::query()
            ->where('lang', '=', app()->getLocale())
            ->orderBy('id', 'desc')->limit(4)->get();
        $videos = Video::query()
            ->where('lang', '=', app()->getLocale())
            ->orderBy('id', 'desc')->limit(4)->get();
        $audios = Audio::query()
            ->where('lang', '=', app()->getLocale())
            ->orderBy('id', 'desc')->limit(6)->get();
        return view('site.multimedia.index', compact('photos', 'videos', 'audios'));
    }

    public function photo(){
        $photos = Image::query()
            ->where('lang', '=', app()->getLocale())
            ->orderBy('id', 'desc')->paginate(12);
        return view('site.multimedia.allphoto', compact('photos'));
    }

    public function video(){
        $videos = Video::query()
            ->where('lang', '=', app()->getLocale())
            ->orderBy('id', 'desc')->paginate(12);
        return view('site.multimedia.allvideo', compact('videos'));
    }

    public function audio(){
        $audios = Audio::query()
            ->where('lang', '=', app()->getLocale())
            ->orderBy('id', 'desc')->paginate(9);
        return view('site.multimedia.allaudio', compact('audios'));
    }



    // Announcement Routes
    public function announcements() {
        $announcements = Announcement::query()->orderBy('id', 'desc')
            ->simplePaginate(10);

        return view('site.announcement.index', compact('announcements'));
    }

    public function announcement($id) {
        $comments = Comment::where('type', 'announcements')->where('relation_id', $id)->where('status', 1)->get();
        $announcement = Announcement::query()->find($id);

        return view('site.announcement.singleannouncement')->with(compact('announcement'))->with(compact('comments'));
    }


//    Usefullink Routes
    public function usefullinkall()
    {
        $usefullinkall = Useful::query()->orderBy('id', 'desc')->get();
        return view('site.usefullinks.usefullink', compact('usefullinkall'));
    }



//    Symbols Routes
    public function symbols()
    {
        $symbols = Symbol::query()->orderBy('id', 'desc')->get();
        return view('site.symbols.symbols', compact('symbols'));
    }

    public function symbol($id)
    {
        $symbol = Symbol::query()->find($id);
        return view('site.symbols.symbol', compact('symbol'));
    }



    // Teahause Routes
    public function teahauses()
    {
        $categories = FunnyCategory::query()->get();
        return view('site.teahause.teahauses', compact('categories'));
    }

    public function teahause($id)
    {
        $article = Funny::query()->find($id);
        return view('site.teahause.teahause', compact('article'));
    }

    public function teahauseCategory($category_slug)
    {
        $category = FunnyCategory::query()
            ->where('slug', '=', $category_slug)->first();
        $articles = Funny::query()->orderBy('id', 'desc')
            ->where('category_id', '=', $category->id)->paginate(20);

        return view('site.teahause.category-teahause', compact('category', 'articles'));
    }

}



