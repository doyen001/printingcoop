<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BlogsController extends Controller
{
    /**
     * Display blogs listing
     * CI: Blogs->index() lines 12-19
     */
    public function index()
    {
        $language_name = config('store.language_name', 'english');
        $main_store_id = config('store.main_store_id', 1);
        
        $page_title = $language_name == 'french' ? 'Blogs' : 'Blogs';
        
        // Get blogs list
        $blogs = $this->getBlogsFrontEndList(0, 0, 0, 'blogs.created', 'desc', 0, 0, $main_store_id);
        
        return view('blogs.index', compact('page_title', 'blogs', 'language_name'));
    }
    
    /**
     * Display blogs by category
     * CI: Blogs->category() lines 27-71
     */
    public function category($category_id = null)
    {
        $language_name = config('store.language_name', 'english');
        $main_store_id = config('store.main_store_id', 1);
        
        // Get category by slug or show all
        $blog_category = $this->getBlogsCategorySlug($category_id);
        
        if (!$category_id) {
            $blog_category = [
                'page_title' => 'Printing Coop Blog Posts Category - printing.coop',
                'meta_description_content' => 'Explore a wealth of insights in the Printing Coop blog posts category. Stay informed about the latest trends and expert tips in the printing industry.',
                'meta_keywords_content' => 'Printing Coop Blog Posts Category'
            ];
        }
        
        if ($category_id && empty($blog_category)) {
            abort(404);
        }
        
        $category_id_filter = $blog_category['id'] ?? null;
        $category_name = $blog_category ? ($blog_category['category_name'] ?? 'All Categories') : 'All Categories';
        
        $page_title = $language_name == 'french' ? 'Blog de la catégorie' : 'Category Blog';
        
        $blogs = $this->getBlogsFrontEndList($category_id_filter, 0, 0, 'blogs.created', 'desc', 0, 0, $main_store_id);
        $sideBarData = $this->getSideBarData($main_store_id);
        
        return view('blogs.category', array_merge(
            compact('page_title', 'blogs', 'language_name', 'blog_category', 'category_name'), 
            $sideBarData
        ));
    }
    
    /**
     * Search blogs
     * CI: Blogs->search() lines 32-40
     */
    public function search(Request $request)
    {
        $search = $request->input('search', '');
        $language_name = config('store.language_name', 'english');
        $main_store_id = config('store.main_store_id', 1);
        
        $page_title = $language_name == 'french' ? 'Rechercher dans le blog' : 'Search Blog';
        
        $blogs = $this->getBlogsFrontEndList(null, null, $search, 'blogs.title', 'asc', 0, 0, $main_store_id);
        $sideBarData = $this->getSideBarData($main_store_id);
        
        return view('blogs.category', array_merge(compact('page_title', 'blogs', 'language_name'), $sideBarData));
    }
    
    /**
     * Display single blog view
     * CI: Blogs->singleview() lines 82-104
     */
    public function singleview($slug = null)
    {
        $language_name = config('store.language_name', 'english');
        $main_store_id = config('store.main_store_id', 1);
        
        // Get blog by slug instead of ID
        $blog = $this->getBlogsFrontEndBySlug($slug);
        
        if (empty($blog)) {
            return redirect('/');
        }
        
        $category_id = $blog['category_id'];
        $releted_blog = $this->getBlogsFrontEndList($category_id, null, null, 'blogs.created', 'desc', 0, 5, $main_store_id);
        
        $page_title = $language_name == 'french' ? $blog['title_french'] : $blog['title'];
        $sideBarData = $this->getSideBarData($main_store_id, 2, $blog);
        
        return view('blogs.single_view', array_merge(compact('page_title', 'blog', 'releted_blog', 'language_name'), $sideBarData));
    }
    
    /**
     * Get sidebar data for blogs
     * CI: Blogs->sideBarData() lines 59-70
     */
    protected function getSideBarData($main_store_id, $fl = 1, $blog = null)
    {
        $language_name = config('store.language_name', 'english');
        
        $latestblogs = $this->getBlogsFrontEndList(null, null, null, 'blogs.created', 'desc', 0, 10, $main_store_id);
        $popularblogs = $this->getBlogsFrontEndList(null, 1, null, 'blogs.created', 'desc', 0, 10, $main_store_id);
        $category = $this->getBlogsCategoryList(1, $main_store_id);
        
        $data = compact('latestblogs', 'popularblogs', 'category');
        
        if ($fl == 2 && $blog) {
            $data['page_title'] = $language_name == 'french' ? $blog['title_french'] : $blog['title'];
        }
        
        return $data;
    }
    
    /**
     * Get blogs list for frontend
     * Based on CI Blog_Model->getBlogsFrontEndList()
     */
    protected function getBlogsFrontEndList($category_id = null, $popular = null, $search = null, $order_by = 'blogs.created', $type = 'desc', $start = 0, $limit = 0, $main_store_id = 1)
    {
        $query = DB::table('blogs')
            ->leftJoin('blog_category', 'blogs.category_id', '=', 'blog_category.id')
            ->select('blogs.*', 'blog_category.category_name', 'blog_category.category_name_french', 'blog_category.blog_category_slug')
            ->where('blogs.status', 1)
            ->whereRaw("FIND_IN_SET(?, blogs.store_id)", [$main_store_id]);
        
        if ($category_id) {
            $query->where('blogs.category_id', $category_id);
        }
        
        if ($popular) {
            $query->where('blogs.populer', 1);
        }
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('blogs.title', 'like', '%' . $search . '%')
                  ->orWhere('blogs.title_french', 'like', '%' . $search . '%')
                  ->orWhere('blogs.content', 'like', '%' . $search . '%')
                  ->orWhere('blogs.content_french', 'like', '%' . $search . '%');
            });
        }
        
        $query->orderBy($order_by, $type);
        
        if ($limit > 0) {
            $query->limit($limit);
        }
        
        if ($start > 0) {
            $query->offset($start);
        }
        
        return $query->get()->map(function($item) {
            return (array) $item;
        })->toArray();
    }
    
    /**
     * Get blog by ID for frontend
     * Based on CI Blog_Model->getBlogsFrontEndById()
     */
    protected function getBlogsFrontEndById($id)
    {
        $blog = DB::table('blogs')
            ->leftJoin('blog_category', 'blogs.category_id', '=', 'blog_category.id')
            ->select('blogs.*', 'blog_category.category_name', 'blog_category.category_name_french', 'blog_category.blog_category_slug')
            ->where('blogs.id', $id)
            ->where('blogs.status', 1)
            ->first();
        
        return $blog ? (array) $blog : [];
    }
    
    /**
     * Get blog categories list
     * Based on CI Blog_Model->getBlogsCategoryList()
     */
    protected function getBlogsCategoryList($status = null, $main_store_id = 1)
    {
        $query = DB::table('blog_category')
            ->whereRaw("FIND_IN_SET(?, store_id)", [$main_store_id]);
        
        if ($status !== null) {
            $query->where('status', $status);
        }
        
        return $query->get()->map(function($item) {
            return (array) $item;
        })->toArray();
    }
    
    /**
     * Get blog by slug for frontend
     * Based on CI Blog_Model->getBlogsFrontEndBySlug()
     */
    protected function getBlogsFrontEndBySlug($slug)
    {
        $blog = DB::table('blogs')
            ->leftJoin('blog_category', 'blogs.category_id', '=', 'blog_category.id')
            ->select('blogs.*', 'blog_category.category_name', 'blog_category.category_name_french', 'blog_category.blog_category_slug')
            ->where('blogs.blog_slug', $slug)
            ->where('blogs.status', 1)
            ->first();
        
        return $blog ? (array) $blog : [];
    }
    
    /**
     * Get blog category by slug
     * Based on CI Blog_Model->getBlogsCategorySlug()
     */
    protected function getBlogsCategorySlug($slug)
    {
        if (!$slug) {
            return null;
        }
        
        $category = DB::table('blog_category')
            ->where('blog_category_slug', $slug)
            ->where('status', 1)
            ->first();
        
        return $category ? (array) $category : [];
    }
}
