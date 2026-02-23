<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Neighbor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class NeighborController extends Controller
{
    public $class_name = 'Neighbor';

    public function __construct()
    {
        // Laravel doesn't need parent::__construct() call in this context
        // $this->data array is not used in Laravel - we pass data directly to views
    }

    /**
     * Display neighbors list
     * CI: Neighbor->index()
     */
    public function index($neighbor_id = 0, $order = 'desc')
    {
        try {
            // Handle POST request for order change
            if (request()->isMethod('post')) {
                $order = request('order', 'desc');
                return redirect("admin/Neighbor/index/$neighbor_id/$order");
            }

            $data['page_title'] = 'Neighbor';

            // Pagination configuration
            $perPage = 20;
            $totalRows = Neighbor::getNeighborsCount($neighbor_id);
            $currentPage = request('page', 1);
            $offset = ($currentPage - 1) * $perPage;

            $list = Neighbor::getNeighbors($neighbor_id, $perPage, $offset, $order);
            
            $data['list'] = $list;
            $data['order'] = $order;
            $data['neighbor_id'] = $neighbor_id;
            $data['total_rows'] = $totalRows;
            $data['per_page'] = $perPage;
            $data['current_page'] = $currentPage;

            return view('admin.neighbor.index', $data);
            
        } catch (Exception $e) {
            Log::error('Error in NeighborController@index: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('message_error', 'Error loading neighbors: ' . $e->getMessage());
        }
    }

    /**
     * Add/Edit neighbor
     * CI: Neighbor->edit()
     */
    public function edit($neighbor_id = 0, $attribute_id = 0, $attribute_item_id = 0, $order = 'asc')
    {
        try {
            // Handle POST request
            if (request()->isMethod('post')) {
                return $this->saveNeighbor(request(), $neighbor_id);
            }

            $page_title = 'Add New Neighbor';
            if (!empty($neighbor_id)) {
                $page_title = 'Edit Neighbor';
            }

            $postData = [];
            if ($neighbor_id) {
                $postData = Neighbor::getNeighbors($neighbor_id, 1, 0, 'desc');
                $postData = $postData[0] ?? [];
            }

            $data = [
                'page_title' => $page_title,
                'neighbor_id' => $neighbor_id,
                'attribute_id' => $attribute_id,
                'attribute_item_id' => $attribute_item_id,
                'order' => $order,
                'postData' => $postData
            ];

            return view('admin.neighbor.edit', $data);
            
        } catch (Exception $e) {
            Log::error('Error in NeighborController@edit: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('message_error', 'Error loading neighbor form: ' . $e->getMessage());
        }
    }

    /**
     * Save neighbor data
     * CI: Neighbor->edit() POST handling
     */
    protected function saveNeighbor(Request $request, $neighbor_id = null)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'url' => 'required|string|max:255',
            ], [
                'name.required' => 'Enter the neighbor name',
                'url.required' => 'Enter the neighbor url',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $data = [
                'name' => $request->input('name'),
                'url' => $request->input('url'),
            ];

            if ($neighbor_id) {
                $data['id'] = $neighbor_id;
            }

            $result = Neighbor::saveNeighbor($data);

            if ($result > 0) {
                return redirect()->route('neighbor.index')
                    ->with('message_success', 'Neighbor Successfully.');
            } else {
                return redirect()->back()
                    ->with('message_error', 'Neighbor Unsuccessfully.')
                    ->withInput();
            }
            
        } catch (Exception $e) {
            Log::error('Error in NeighborController@saveNeighbor: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('message_error', 'Error saving neighbor: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete neighbor
     * CI: Neighbor->delete()
     */
    public function delete($id = null)
    {
        try {
            if (empty($id)) {
                return redirect()->route('neighbor.index')
                    ->with('message_error', 'Missing information.');
            }

            $result = Neighbor::deleteNeighbor($id);
            
            if ($result) {
                return redirect()->route('neighbor.index')
                    ->with('message_success', 'Neighbor Delete Successfully.');
            } else {
                return redirect()->route('neighbor.index')
                    ->with('message_error', 'Neighbor Delete Unsuccessfully.');
            }
            
        } catch (Exception $e) {
            Log::error('Error in NeighborController@delete: ' . $e->getMessage());
            
            return redirect()->route('neighbor.index')
                ->with('message_error', 'Error deleting neighbor: ' . $e->getMessage());
        }
    }

    /**
     * Delete all selected neighbors (CI project style)
     */
    public function deleteAll(Request $request)
    {
        try {
            $neighborIds = $request->input('neighbor_ids', []);
            
            if (empty($neighborIds)) {
                return redirect()->route('neighbor.index')
                    ->with('message_error', 'No neighbors selected for deletion.');
            }

            $result = Neighbor::deleteMultipleNeighbors($neighborIds);
            
            if ($result) {
                return redirect()->route('neighbor.index')
                    ->with('message_success', 'Selected neighbors deleted successfully.');
            } else {
                return redirect()->route('neighbor.index')
                    ->with('message_error', 'Failed to delete selected neighbors.');
            }
            
        } catch (Exception $e) {
            Log::error('Error in NeighborController@deleteAll: ' . $e->getMessage());
            
            return redirect()->route('neighbor.index')
                ->with('message_error', 'Error deleting neighbors: ' . $e->getMessage());
        }
    }

    /**
     * Search neighbors (AJAX)
     * CI equivalent: Custom search functionality
     */
    public function search(Request $request)
    {
        try {
            $searchTerm = $request->input('search', '');
            
            if (empty($searchTerm) || strlen($searchTerm) < 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Search term must be at least 2 characters'
                ]);
            }

            $neighbors = Neighbor::searchNeighbors($searchTerm);
            
            return response()->json([
                'success' => true,
                'data' => $neighbors,
                'count' => count($neighbors)
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error in NeighborController@search: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Search failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
