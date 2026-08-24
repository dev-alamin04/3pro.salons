<?php

namespace App\Http\Controllers\Web\Backend\Contact;

use App\Http\Controllers\Controller;
use App\Mail\AdminReplyMail;
use App\Models\ContactSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\Facades\DataTables;

class ContactSubmissionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = ContactSubmission::latest();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('job_title', fn($row) => $row->job_title ?? '—')
                ->addColumn('salon_name', fn($row) => $row->salon_name ?? '—')
                ->addColumn('message', fn($row) => strlen($row->message) > 50
                    ? substr($row->message, 0, 50) . '...'
                    : $row->message)
                ->addColumn('status', fn($row) =>
                    '<span class="badge bg-' . ($row->is_read ? 'success' : 'warning text-dark') . '">'
                    . ($row->is_read ? 'Read' : 'Unread') . '</span>')
                ->addColumn('action', function ($row) {
                    $showUrl  = route('admin.contacts.show', $row->id);
                    $markRead = "markRead({$row->id})";
                    $delete   = "showDeleteConfirm({$row->id})";
                    $readBtn  = $row->is_read ? 'disabled title="Already read"' : '';

                    return "
                        <div class='text-center'>
                            <div class='btn-group btn-group-sm' role='group'>
                                <a href='{$showUrl}' class='btn btn-info btn-sm' title='View'>
                                    <i class='fas fa-eye'></i>
                                </a>
                                <a onclick='{$markRead}' class='btn btn-success btn-sm' {$readBtn} title='Mark as read'>
                                    <i class='fas fa-check'></i>
                                </a>
                                <a onclick='{$delete}' class='btn btn-danger btn-sm' title='Delete'>
                                    <i class='fas fa-trash'></i>
                                </a>
                            </div>
                        </div>
                    ";
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('backend.layouts.contact.index');
    }

    public function show(ContactSubmission $contact)
    {
        if (!$contact->is_read) {
            $contact->update(['is_read' => true]);
        }

        return view('backend.layouts.contact.show', compact('contact'));
    }

    public function update(Request $request, ContactSubmission $contact)
    {
        if ($contact->is_read) {
            return response()->json(['status' => 'error', 'message' => 'Already marked as read']);
        }

        $contact->update(['is_read' => true]);
        return response()->json(['status' => 'success', 'message' => 'Marked as read']);
    }

    public function destroy(ContactSubmission $contact)
    {
        $contact->delete();
        return response()->json(['status' => 'success', 'message' => 'Contact submission deleted successfully']);
    }

    public function reply(Request $request, ContactSubmission $contact)
    {
        $request->validate(['reply_message' => 'required|string|min:5|max:5000']);

        Mail::to($contact->email)
            ->send(new AdminReplyMail($contact->name, $request->reply_message));

        // Mark as read automatically when admin replies
        $contact->update(['is_read' => true]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Reply sent successfully to ' . $contact->email,
        ]);
    }
}
