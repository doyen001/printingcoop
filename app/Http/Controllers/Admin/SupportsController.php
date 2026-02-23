<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupportsController extends Controller
{
    /**
     * Display supports list
     * CI: Supports->index()
     */
    public function index()
    {
        // Get support queries from contact_us table
        $supportQueries = DB::table('contact_us')
            ->orderBy('created', 'desc')
            ->get();
        
        // Get stores for display
        $stores = DB::table('stores')
            ->where('status', 1)
            ->orderBy('name')
            ->get()
            ->keyBy('id');
        
        return view('admin.supports.index', [
            'page_title' => 'Supports',
            'supportQueries' => $supportQueries,
            'stores' => $stores,
        ]);
    }
    
    /**
     * View support query details
     * CI: Supports->view()
     */
    public function view($id = null)
    {
        if (empty($id)) {
            return redirect('admin/Supports');
        }
        
        // Get specific support query
        $data = DB::table('contact_us')
            ->where('id', $id)
            ->first();
        
        if (!$data) {
            return redirect('admin/Supports')->with('message_error', 'Query not found');
        }
        
        // Get stores for display
        $stores = DB::table('stores')
            ->where('status', 1)
            ->orderBy('name')
            ->get()
            ->keyBy('id');
        
        return view('admin.supports.view', [
            'page_title' => 'Query Details',
            'data' => $data,
            'stores' => $stores,
        ]);
    }
    
    /**
     * Delete support query
     * CI: Supports->delete()
     */
    public function delete($id = null)
    {
        if (!empty($id)) {
            $page_title = 'Query';
            
            if (DB::table('contact_us')->where('id', $id)->delete()) {
                return redirect('admin/Supports')->with('message_success', $page_title . ' deleted successfully.');
            } else {
                return redirect('admin/Supports')->with('message_error', $page_title . ' deletion failed.');
            }
        } else {
            return redirect('admin/Supports')->with('message_error', 'Missing information.');
        }
    }
}
