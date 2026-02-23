<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

/**
 * TicketsController - Complete support ticket system
 * CI: application/controllers/Tickets.php (171 lines)
 */
class TicketsController extends Controller
{
    /**
     * Check if user is logged in
     */
    protected function checkLogin()
    {
        if (!session('loginId')) {
            return redirect('Homes');
        }
        return null;
    }
    
    /**
     * Display tickets listing
     * CI: lines 16-29
     */
    public function index($status = 0)
    {
        if ($redirect = $this->checkLogin()) return $redirect;
        
        if (!empty($status)) {
            $status = base64_decode($status);
        }
        
        if (!in_array($status, [0, 1])) {
            return redirect('Homes');
        }
        
        $language_name = config('store.language_name', 'english');
        
        $data = [
            'page_title' => $language_name == 'french' ? 'Mes Tickets' : 'My Tickets',
            'status_ticket' => $status,
        ];
        
        return view('tickets.index', $data);
    }
    
    /**
     * Get tickets via AJAX
     * CI: lines 31-45
     */
    public function getTickets($status = 0)
    {
        if ($redirect = $this->checkLogin()) return $redirect;
        
        $loginId = session('loginId');
        
        if (!empty($status)) {
            $status = base64_decode($status);
        }
        
        if (!in_array($status, [0, 1])) {
            return redirect('Homes');
        }
        
        $tickets = DB::table('tickets')
            ->where('user_id', $loginId)
            ->where('status', $status)
            ->orderBy('created', 'desc')
            ->get();
        
        $data = [
            'BASE_URL' => url('/'),
            'lists' => $tickets,
        ];
        
        return view('tickets.get_ticket', $data);
    }
    
    /**
     * Get ticket chat
     * CI: lines 47-91
     */
    public function getChat(Request $request, $ticket_id = null)
    {
        if ($redirect = $this->checkLogin()) return $redirect;
        
        $loginId = session('loginId');
        $loginName = session('loginName');
        
        if (!empty($ticket_id)) {
            if ($request->isMethod('post')) {
                $validator = Validator::make($request->all(), [
                    'message' => 'required',
                    'ticket_id' => 'required',
                ]);
                
                if ($validator->fails()) {
                    return response()->json(['error' => $validator->errors()], 422);
                }
                
                $message = $request->input('message');
                $ticket_id_post = $request->input('ticket_id');
                
                // Update ticket
                DB::table('tickets')->where('id', $ticket_id_post)->update([
                    'updated' => now(),
                ]);
                
                // Save comment
                $comment_id = DB::table('ticket_comments')->insertGetId([
                    'message' => $message,
                    'comment_author' => $loginId,
                    'ticket_id' => $ticket_id_post,
                    'created' => now(),
                    'updated' => now(),
                ]);
                
                $comment = DB::table('ticket_comments')
                    ->join('users', 'ticket_comments.comment_author', '=', 'users.id')
                    ->where('ticket_comments.id', $comment_id)
                    ->select('ticket_comments.*', 'users.name as author_name')
                    ->first();
                
                $data = [
                    'list' => $comment,
                    'BASE_URL' => url('/'),
                    'loginName' => $loginName,
                ];
                
                return view('tickets.get_single_chat', $data);
            } else {
                $ticket_id = base64_decode($ticket_id);
                
                $chats = DB::table('ticket_comments')
                    ->join('users', 'ticket_comments.comment_author', '=', 'users.id')
                    ->where('ticket_comments.ticket_id', $ticket_id)
                    ->select('ticket_comments.*', 'users.name as author_name')
                    ->orderBy('ticket_comments.created', 'asc')
                    ->get();
                
                // Mark as read
                DB::table('ticket_comments')
                    ->where('ticket_id', $ticket_id)
                    ->where('comment_author', '!=', $loginId)
                    ->update(['is_read' => 1]);
                
                $data = [
                    'ticket_id' => $ticket_id,
                    'lists' => $chats,
                    'BASE_URL' => url('/'),
                    'loginName' => $loginName,
                ];
                
                return view('tickets.get_chat', $data);
            }
        } else {
            return redirect('Homes');
        }
    }
    
    /**
     * Get latest chat messages
     * CI: lines 93-108
     */
    public function getLetestChat($ticket_id = null)
    {
        if ($redirect = $this->checkLogin()) return $redirect;
        
        $loginId = session('loginId');
        $loginName = session('loginName');
        
        if (!empty($ticket_id)) {
            $ticket_id = base64_decode($ticket_id);
            
            $chats = DB::table('ticket_comments')
                ->join('users', 'ticket_comments.comment_author', '=', 'users.id')
                ->where('ticket_comments.ticket_id', $ticket_id)
                ->where('ticket_comments.is_read', 0)
                ->where('ticket_comments.comment_author', '!=', $loginId)
                ->select('ticket_comments.*', 'users.name as author_name')
                ->orderBy('ticket_comments.created', 'asc')
                ->get();
            
            // Mark as read
            DB::table('ticket_comments')
                ->where('ticket_id', $ticket_id)
                ->where('comment_author', '!=', $loginId)
                ->update(['is_read' => 1]);
            
            $data = [
                'ticket_id' => $ticket_id,
                'lists' => $chats,
                'BASE_URL' => url('/'),
                'loginName' => $loginName,
            ];
            
            return view('tickets.get_letest_chat', $data);
        } else {
            return redirect('Homes');
        }
    }
    
    /**
     * Create new ticket
     * CI: lines 110-154
     */
    public function createTicket(Request $request)
    {
        if ($redirect = $this->checkLogin()) return $redirect;
        
        $language_name = config('store.language_name', 'english');
        $loginId = session('loginId');
        
        $user = DB::table('users')->where('id', $loginId)->first();
        
        $postData = [];
        $save_success = false;
        
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'email' => 'required|email',
                'subject' => 'required|max:255',
                'contact_no' => 'required|max:20',
                'message' => 'required',
            ]);
            
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput()
                    ->with('message_error', 'Missing information.');
            }
            
            $postData = [
                'name' => $request->name,
                'email' => $request->email,
                'subject' => $request->subject,
                'contact_no' => $request->contact_no,
                'message' => $request->message,
                'user_id' => $loginId,
            ];
            
            $ticket_id = DB::table('tickets')->insertGetId([
                'name' => $postData['name'],
                'email' => $postData['email'],
                'subject' => $postData['subject'],
                'contact_no' => $postData['contact_no'],
                'user_id' => $loginId,
                'status' => 0,
                'created' => now(),
                'updated' => now(),
            ]);
            
            if ($ticket_id) {
                // Save first comment
                DB::table('ticket_comments')->insert([
                    'message' => $postData['message'],
                    'comment_author' => $loginId,
                    'ticket_id' => $ticket_id,
                    'created' => now(),
                    'updated' => now(),
                ]);
                
                $save_success = true;
                
                $message = $language_name == 'french'
                    ? 'Votre ticket a été créé avec succès.'
                    : 'Your ticket created successfully.';
                
                session()->flash('message_success', $message);
            } else {
                $message = $language_name == 'french'
                    ? 'Votre ticket n\'a pas été créé.'
                    : 'Your ticket created unsuccessfully';
                
                session()->flash('message_error', $message);
            }
        }
        
        $data = [
            'page_title' => $language_name == 'french' ? 'Créer un Ticket' : 'Create Ticket',
            'postData' => $postData,
            'save_success' => $save_success,
        ];
        
        return view('tickets.create_ticket', $data);
    }
    
    /**
     * Delete ticket
     * CI: lines 156-169
     */
    public function deleteTicket($id = null)
    {
        if ($redirect = $this->checkLogin()) return $redirect;
        
        $language_name = config('store.language_name', 'english');
        $loginId = session('loginId');
        
        if (!empty($id)) {
            $id = base64_decode($id);
            
            // Delete ticket comments
            DB::table('ticket_comments')->where('ticket_id', $id)->delete();
            
            // Delete ticket
            $deleted = DB::table('tickets')
                ->where('id', $id)
                ->where('user_id', $loginId)
                ->delete();
            
            if ($deleted) {
                $message = $language_name == 'french'
                    ? 'Ticket supprimé avec succès'
                    : 'Ticket deleted successfully';
                
                return redirect('Tickets')->with('message_success', $message);
            } else {
                $message = $language_name == 'french'
                    ? 'Échec de la suppression du ticket'
                    : 'Ticket deleted unsuccessfully';
                
                return redirect('Tickets')->with('message_error', $message);
            }
        }
        
        return redirect('Tickets');
    }
}
