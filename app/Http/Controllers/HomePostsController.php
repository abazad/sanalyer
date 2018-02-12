<?php

namespace App\Http\Controllers;

use function App\checkImage;
use App\User;
use function App\YoutubeID;
use Carbon\Carbon;
use Radkod\Posts\Models\Category;
use Radkod\Posts\Models\Posts;
use Illuminate\Routing\Controller as Controller;
use SEO;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;

class HomePostsController extends Controller
{

    public function showPost($slug)
    {
        $date = date('Y-m-d H:i:s');
        $data = explode("-", $slug);
        $id = $data[count($data) - 1];
        $posts = Posts::where('id', $id)
            ->where('updated_at', '<=', $date)
            ->where('type', 0)
            ->first();
        if ($posts == null){
            alert()->error('Aradıgınız içerik bulunmamakta.');
            return redirect(route('home'));
        }
        $postDescEx = explode('----------------------', $posts->content);
        $postDesc = $postDescEx[0];
        $postContent = $postDescEx[1];
        $category = Category::where('id', $posts->category)->first();
        $prev = Posts::where('updated_at', '<', $posts->updated_at)
            ->where('updated_at', '<=', $date)
            ->where('type', 0)
            ->orderBy('updated_at', 'DESC')
            ->first();
        $prevDescEx = explode('----------------------', $prev->content);
        $prevDesc = $prevDescEx[0];
        $prevContent = $prevDescEx[1];
        $postTag = explode(',', $posts->tag);
        SEO::setTitle($posts->title);
        SEO::setDescription($postDesc);
        SEO::setCanonical(route('show_post', str_slug($posts->title) . '-' . $posts->id));
        SEO::metatags()->addKeyword($postTag);
        SEO::opengraph()->setTitle($posts->title)
            ->setDescription($postDesc)
            ->setUrl(route('show_post', str_slug($posts->title) . '-' . $posts->id))
            ->addImages([checkImage($posts->image)])
            ->setType('article')
            ->setArticle([
                'published_time' => $posts->created_at->toW3CString(),
                'modified_time' => $posts->updated_at->toW3CString(),
                'author' => $posts->user->firstname . ' ' . $posts->user->lastname,
                'section' => $posts->category()->first()->title,
                'tag' => $postTag
            ]);

        return view('frontend.detail.posts', compact('posts', 'prev', 'postDesc', 'postContent', 'prevDesc', 'prevContent', 'category'));
    }

    public function showPostAMP($slug)
    {
        $date = date('Y-m-d H:i:s');
        $data = explode("-", $slug);
        $id = $data[count($data) - 1];
        $posts = Posts::where('id', $id)
            ->where('updated_at', '<=', $date)
            ->where('type', 0)
            ->first();
        if ($posts == null){
            alert()->error('Aradıgınız içerik bulunmamakta.');
            return redirect(route('home'));
        }
        $postDescEx = explode('----------------------', $posts->content);
        $postDesc = $postDescEx[0];
        $postContent = $postDescEx[1];
        $category = Category::where('id', $posts->category)->first();
        $prev = Posts::where('updated_at', '<', $posts->updated_at)
            ->where('updated_at', '<=', $date)
            ->where('type', 0)
            ->orderBy('updated_at', 'DESC')
            ->first();
        $prevDescEx = explode('----------------------', $prev->content);
        $prevDesc = $prevDescEx[0];
        $prevContent = $prevDescEx[1];
        $postTag = explode(',', $posts->tag);
        SEO::setTitle($posts->title);
        SEO::setDescription($postDesc);
        SEO::setCanonical(route('show_post', str_slug($posts->title) . '-' . $posts->id));
        SEO::metatags()->addKeyword($postTag);
        SEO::opengraph()->setTitle($posts->title)
            ->setDescription($postDesc)
            ->setUrl(route('show_post', str_slug($posts->title) . '-' . $posts->id))
            ->addImages([checkImage($posts->image)])
            ->setType('article')
            ->setArticle([
                'published_time' => $posts->created_at->toW3CString(),
                'modified_time' => $posts->updated_at->toW3CString(),
                'author' => $posts->user->firstname . ' ' . $posts->user->lastname,
                'section' => $posts->category()->first()->title,
                'tag' => $postTag
            ]);

        return view('frontend.detail.posts_amp', compact('posts', 'prev', 'postDesc', 'postContent', 'prevDesc', 'prevContent', 'category'));
    }

    public function showVideo($slug)
    {
        $date = date('Y-m-d H:i:s');
        $data = explode("-", $slug);
        $id = $data[count($data) - 1];
        $posts = Posts::where('id', $id)
            ->where('updated_at', '<=', $date)
            ->where('type', 1)
            ->first();
        if ($posts == null){
            alert()->error('Aradıgınız içerik bulunmamakta.');
            return redirect(route('home'));
        }
        $postDescEx = explode('----------------------', $posts->content);
        $postDesc = $postDescEx[0];
        $postContent = $postDescEx[1];
        $prev = Posts::where('updated_at', '<', $posts->updated_at)
            ->where('updated_at', '<=', $date)
            ->where('type', 1)
            ->orderBy('updated_at', 'DESC')
            ->take(9)
            ->get();
        $postTag = explode(',', $posts->tag);
        SEO::setTitle($posts->title);
        SEO::setDescription($postDesc);
        SEO::setCanonical(route('show_video', str_slug($posts->title) . '-' . $posts->id));
        SEO::metatags()->addKeyword($postTag);
        SEO::opengraph()->setTitle($posts->title)
            ->setDescription($postDesc)
            ->setUrl(route('show_video', str_slug($posts->title) . '-' . $posts->id))
            ->addImages([checkImage($posts->image)])
            ->setType('video')
            ->setArticle([
                'published_time' => $posts->created_at->toW3CString(),
                'modified_time' => $posts->updated_at->toW3CString(),
                'author' => $posts->user->firstname . ' ' . $posts->user->lastname,
                'section' => $posts->category()->first()->title,
                'tag' => $postTag
            ])
            ->addVideo('http://www.youtube.com/embed/' . YoutubeID($posts->video), [
                'secure_url' => 'https://www.youtube.com/embed/' . YoutubeID($posts->video),
                'type' => 'text/html',
                'width' => 640,
                'height' => 360
            ]);
        return view('frontend.detail.video', compact('posts', 'prev', 'postDesc', 'postContent'));
    }

    public function showCategory($slug)
    {
        $date = date('Y-m-d H:i:s');
        $data = explode("-", $slug);
        $id = $data[count($data) - 1];
        $category = Category::find($id);
        if ($category == null){
            alert()->error('Aradıgınız kategori bulunmamakta.');
            return redirect(route('home'));
        }
        $posts = Posts::where('updated_at', '<=', $date)
            ->where('status', 1)
            ->where('location', '!=', 5)
            ->orderBy('updated_at', 'DESC')
            ->where('category', $id)
            ->paginate(10);
        SEO::setTitle($category->title);
        SEO::setDescription($category->content);
        SEO::setCanonical(route("news"));
        SEO::opengraph()->setTitle($category->title)
            ->setDescription($category->content)
            ->setUrl(route('show_category',str_slug($category-> title).'-'.$category->id));
        return view('frontend.detail.category', compact('posts', 'category'));
    }

    public function newPost(){
        $category = Category::get();
        $type = 0;
        return view('frontend.new_post',compact('category','type'));
    }

    public function newPostPost(Request $request){
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'image' => 'image|mimes:jpeg,png,jpg|max:2048',
            'title' => 'required|min:6',
            'short_desc' => 'required',
            'content_full' => 'required',
            'category' => 'required',
            'location' => 'required',
            'date' => 'required'
        ]);
        $contents = $request->short_desc."----------------------".$request->content_full;
        if($user->rank == 1 || $user->rank == 2){
            $status = 1;
            $responseText = 'İçerik Başarıyla Oluşturuldu.';
        }else{
            $status = 0;
            $responseText = 'İçerik Onaya Sunuldu.';
        }
        $post = new Posts;
        if ($validator->passes()) {
            if($request->file('image')){
                $imageName = str_slug($request->title) .'.' . request()->image->getClientOriginalExtension();
                request()->image->move(public_path('resimler'), $imageName);
                $post->image = $imageName;
            }else{
                $post->image = 'yok.png';
            }
            $date = Carbon::createFromFormat('d-m-Y H:i:s', $request->date)->toDateTimeString();
            $post->title = $request->title;
            $post->content = $contents;
            $post->author = $user->id;
            $post->status = $status;
            $post->type = 0;
            $post->category = $request->category;
            $post->location = $request->location;
            $post->tag = $request->tag;
            $post->created_at = $date;
            $post->updated_at = $date;
            $post->save();
            alert()->success($responseText);
            return redirect('home');
        }
        return redirect()->back()->withErrors($validator)->withInput();
    }

    public function newVideo(){
        $category = Category::get();
        $type = 1;
        return view('frontend.new_post',compact('category','type'));
    }

    public function newVideoPost(Request $request){
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'image' => 'image|mimes:jpeg,png,jpg|max:2048',
            'title' => 'required|min:6',
            'short_desc' => 'required',
            'content_full' => 'required',
            'category' => 'required',
            'video' => 'required',
            'location' => 'required',
            'date' => 'required'
        ]);
        $contents = $request->short_desc."----------------------".$request->content_full;
        if($user->rank == 1 || $user->rank == 2){
            $status = 1;
            $responseText = 'İçerik Başarıyla Oluşturuldu.';
        }else{
            $status = 0;
            $responseText = 'İçerik Onaya Sunuldu.';
        }
        $post = new Posts;
        if ($validator->passes()) {
            if($request->file('image')){
                $imageName = str_slug($request->title) .'.' . request()->image->getClientOriginalExtension();
                request()->image->move(public_path('resimler'), $imageName);
                $post->image = $imageName;
            }else{
                $post->image = 'yok.png';
            }
            $date = Carbon::createFromFormat('d-m-Y H:i:s', $request->date)->toDateTimeString();
            $post->title = $request->title;
            $post->content = $contents;
            $post->author = $user->id;
            $post->status = $status;
            $post->type = 1;
            $post->category = $request->category;
            $post->video = $request->video;
            $post->location = $request->location;
            $post->tag = $request->tag;
            $post->created_at = $date;
            $post->updated_at = $date;
            $post->save();
            alert()->success($responseText);
            return redirect('home');
        }
        return redirect()->back()->withErrors($validator)->withInput();
    }

    public function threads(){

    }

}