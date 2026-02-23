<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrintersController extends Controller
{
    /**
     * Display printers list based on type
     * CI: Printers->index($type)
     */
    public function index($type = 'printers')
    {
        if ($type == 'printers') {
            $page_title = 'Printer Brands';
            $lists = DB::table('printers')
                ->orderBy('shortOrder', 'asc')
                ->orderBy('name', 'asc')
                ->get();
        } else if ($type == 'printer_series') {
            $page_title = 'Printer Series';
            $lists = DB::table('printer_series')
                ->select('printer_series.*', 'printers.name as brand_name')
                ->leftJoin('printers', 'printers.id', '=', 'printer_series.printer_brand_id')
                ->orderBy('printer_series.name', 'asc')
                ->get();
        } else if ($type == 'printermodels') {
            $page_title = 'Printer models';
            $lists = DB::table('printermodels')
                ->select('printermodels.*', 'printers.name as brand_name')
                ->leftJoin('printers', 'printers.id', '=', 'printermodels.printer_brand_id')
                ->orderBy('printermodels.name', 'asc')
                ->get();
        } else {
            $page_title = 'Printer Brands';
            $lists = DB::table('printers')
                ->orderBy('shortOrder', 'asc')
                ->orderBy('name', 'asc')
                ->get();
        }

        $sub_page_title = 'Add New ' . ($type == 'printers' ? 'Printer Brand' : ($type == 'printer_series' ? 'Printer Series' : 'Printer Model'));

        return view('admin.printers.index', [
            'page_title' => $page_title,
            'sub_page_title' => $sub_page_title,
            'lists' => $lists,
            'type' => $type,
        ]);
    }
    
    /**
     * Add/Edit printer based on type
     * CI: Printers->addEdit($id, $type)
     */
    public function addEdit($id = null, $type = 'printers')
    {
        if ($type == 'printers') {
            $page_title = 'Add New Printer Brands';
            if (!empty($id)) {
                $page_title = 'Edit Printer Brands';
            }
        } else if ($type == 'printer_series') {
            $page_title = 'Add New Printer Series';
            if (!empty($id)) {
                $page_title = 'Edit Printer Series';
            }
        } else if ($type == 'printermodels') {
            $page_title = 'Add New Printer Model';
            if (!empty($id)) {
                $page_title = 'Edit Printer Model';
            }
        }

        $postData = [];
        if ($id) {
            if ($type == 'printers') {
                $postData = DB::table('printers')->where('id', $id)->first();
            } else if ($type == 'printer_series') {
                $postData = DB::table('printer_series')->where('id', $id)->first();
            } else if ($type == 'printermodels') {
                $postData = DB::table('printermodels')->where('id', $id)->first();
            }
        }

        // Get printer brands for dropdown
        $printerBrandLists = DB::table('printers')
            ->where('status', 1)
            ->orderBy('shortOrder', 'asc')
            ->orderBy('name', 'asc')
            ->get();

        $printerSeriesLists = [];
        if ($type == 'printermodels' && !empty($postData->printer_brand_id)) {
            $printerSeriesLists = DB::table('printer_series')
                ->where('printer_brand_id', $postData->printer_brand_id)
                ->where('status', 1)
                ->orderBy('name', 'asc')
                ->get();
        }

        if (request()->isMethod('post')) {
            $rules = $this->getValidationRules($type);
            
            $validator = \Illuminate\Support\Facades\Validator::make(request()->all(), $rules);
            
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $data = [
                'name' => request()->input('name'),
                'name_french' => request()->input('name_french'),
                'status' => 1,
            ];

            if ($type == 'printer_series' || $type == 'printermodels') {
                $data['printer_brand_id'] = request()->input('printer_brand_id');
            }

            if ($type == 'printermodels') {
                $data['printer_series_id'] = request()->input('printer_series_id');
            }

            if ($id) {
                // Update
                if ($type == 'printers') {
                    DB::table('printers')->where('id', $id)->update($data);
                } else if ($type == 'printer_series') {
                    DB::table('printer_series')->where('id', $id)->update($data);
                } else if ($type == 'printermodels') {
                    DB::table('printermodels')->where('id', $id)->update($data);
                }
                $message = $page_title . ' Successfully.';
            } else {
                // Insert
                if ($type == 'printers') {
                    $data['shortOrder'] = DB::table('printers')->max('shortOrder') + 1;
                    DB::table('printers')->insert($data);
                } else if ($type == 'printer_series') {
                    DB::table('printer_series')->insert($data);
                } else if ($type == 'printermodels') {
                    DB::table('printermodels')->insert($data);
                }
                $message = $page_title . ' Successfully.';
            }
            
            return redirect('admin/Printers/index/' . $type)->with('message_success', $message);
        }

        return view('admin.printers.add_edit', [
            'page_title' => $page_title,
            'postData' => $postData,
            'type' => $type,
            'printerBrandLists' => $printerBrandLists,
            'printerSeriesLists' => $printerSeriesLists,
        ]);
    }
    
    /**
     * Toggle printer status
     * CI: Printers->activeInactive($id, $status, $type)
     */
    public function activeInactive($id = null, $status = null, $type = null)
    {
        if (!empty($id) && ($status == 1 || $status == 0)) {
            $data = ['status' => $status];
            
            if ($type == 'printers') {
                $page_title = $status == 1 ? 'Printer Brands Active' : 'Printer Brands Inactive';
                DB::table('printers')->where('id', $id)->update($data);
            } else if ($type == 'printer_series') {
                $page_title = $status == 1 ? 'Printer Series Active' : 'Printer Series Inactive';
                DB::table('printer_series')->where('id', $id)->update($data);
            } else if ($type == 'printermodels') {
                $page_title = $status == 1 ? 'Printer Model Active' : 'Printer Model Inactive';
                DB::table('printermodels')->where('id', $id)->update($data);
            }
            
            $message = $page_title . ' Successfully.';
        } else {
            $message = 'Missing information.';
        }

        return redirect('admin/Printers/index/' . $type)->with('message_success', $message);
    }
    
    /**
     * Delete printer
     * CI: Printers->deletePrinter($id, $type)
     */
    public function deletePrinter($id = null, $type = null)
    {
        if ($type == 'printers') {
            $page_title = 'Printer Brands Delete';
            $table = 'printers';
        } else if ($type == 'printer_series') {
            $page_title = 'Printer Series Delete';
            $table = 'printer_series';
        } else if ($type == 'printermodels') {
            $page_title = 'Printer Model Delete';
            $table = 'printermodels';
        }
        
        if (!empty($id)) {
            if (DB::table($table)->where('id', $id)->delete()) {
                $message = $page_title . ' Successfully.';
            } else {
                $message = $page_title . ' Unsuccessfully.';
            }
        } else {
            $message = 'Missing information.';
        }
        
        return redirect('admin/Printers/index/' . $type)->with('message_success', $message);
    }
    
    /**
     * Get validation rules based on type
     */
    private function getValidationRules($type)
    {
        $rules = [
            'name' => 'required|max:250',
            'name_french' => 'required|max:250',
        ];
        
        if ($type == 'printer_series' || $type == 'printermodels') {
            $rules['printer_brand_id'] = 'required';
        }
        
        if ($type == 'printermodels') {
            $rules['printer_series_id'] = 'required';
        }
        
        return $rules;
    }
}
