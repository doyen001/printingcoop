<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = DB::table('faqs')->orderBy('show_order')->paginate(20);
        
        return view('admin.faq.index', [
            'page_title' => 'FAQs',
            'faqs' => $faqs,
        ]);
    }
    
    public function addEdit(Request $request, $id = null)
    {
        $faq = $id ? DB::table('faqs')->where('id', $id)->first() : null;
        
        if ($request->isMethod('post')) {
            $rules = [
                'question' => 'required',
                'question_french' => 'required',
                'answer' => 'required',
                'answer_french' => 'required',
            ];
            
            $validator = Validator::make($request->all(), $rules);
            
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            
            $data = [
                'question' => $request->question,
                'question_french' => $request->question_french,
                'answer' => $request->answer,
                'answer_french' => $request->answer_french,
                'show_order' => $request->show_order ?? 0,
                'status' => $request->status ?? 1,
                'updated' => now(),
            ];
            
            if ($id) {
                DB::table('faqs')->where('id', $id)->update($data);
                $message = 'FAQ updated successfully';
            } else {
                $data['created'] = now();
                DB::table('faqs')->insert($data);
                $message = 'FAQ created successfully';
            }
            
            return redirect('admin/Faq')->with('message_success', $message);
        }
        
        return view('admin.faq.add_edit', [
            'page_title' => $id ? 'Edit FAQ' : 'Add FAQ',
            'faq' => $faq,
        ]);
    }
    
    public function delete($id)
    {
        DB::table('faqs')->where('id', $id)->delete();
        
        return redirect('admin/Faq')->with('message_success', 'FAQ deleted successfully');
    }
    
    public function activeInactive($id, $status)
    {
        DB::table('faqs')->where('id', $id)->update(['status' => $status, 'updated' => now()]);
        
        $message = $status == 1 ? 'FAQ activated successfully' : 'FAQ deactivated successfully';
        
        return redirect('admin/Faq')->with('message_success', $message);
    }
}
