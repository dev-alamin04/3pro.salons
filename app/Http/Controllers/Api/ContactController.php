<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Contact\StoreContactRequest;
use App\Mail\ContactSupportMail;
use App\Models\ContactSubmission;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    use ApiResponse;

    public function store(StoreContactRequest $request)
    {
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('contact-attachments', 'public');
        }

        $validatedData = $request->validated();

        $validatedData['attachment'] = $attachmentPath;
        $validatedData['is_read']    = false;

        ContactSubmission::create($validatedData);
        Mail::to($request->email)->send(new ContactSupportMail($request->name));

        return $this->success(
            [],
            'Got it. Your message is on its way to our team — we typically respond within 1–2 business days. Keep an eye on your email.',
            200
        );
    }
}
