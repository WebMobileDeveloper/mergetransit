<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\DB;

class BlogController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $this->create_distance_table();
        $tag = $request->query('tag');
        
        $data['white_header'] = true;
        $data['tags']= DB::table('blogs')->select('tag')->distinct()->get()->toArray();
        array_unshift($data['tags'], (object) array("tag"=>"all"));
        // dd($data['tags']);
        // exit();
        $data['curr_tag']= isset($tag) ? $tag : 'all';

        if ($data['curr_tag'] == 'all') {
            $data['blogs'] = DB::table('blogs')->paginate(3);
        } else {
            $data['blogs'] = DB::table('blogs')->where('tag', $data['curr_tag'])->paginate(3);
        }
        return view('blog', $data);
    }
    public function detail($id)
    {
        $data['white_header'] = true;
        $data['blog'] = DB::table('blogs')->where('id', $id)->first();
        $data['contents'] = DB::table('blog_contents')->where('blog_id', $id) ->orderBy('order')->get();
        $data['related_blog'] = DB::table('blogs')->where([['tag', $data['blog']->tag],['id','<>', $id]])->inRandomOrder()->first();
        //   dd($data);
        return view('blog_detail', $data);    
    }    
}
