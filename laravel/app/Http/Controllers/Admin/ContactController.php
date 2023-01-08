<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Helpers\Permission;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $userAuth = new Permission($request->user()->group, 'admin');
        $this->authorize('auth-check', $userAuth->authorize('admin-contacts-index'));

        $breadcrumb = [
            __('Dashboard') => route('admin.index'),
            __('Messages') => '',
        ];

        return view('admin.contacts.index', compact('breadcrumb'));
    }

    public function indexData(Request $request)
    {
        $userAuth = new Permission($request->user()->group, 'admin');
        $this->authorize('auth-check', $userAuth->authorize('admin-contacts-index'));

        $queryLimit = $request->query('limit', 10);
        $queryOffset = $request->query('offset', 0);
        $querySort = $request->query('sort', 'id');
        $queryOrder = $request->query('order', 'desc');
        $querySearch = $request->query('search');
        $queryStatus = $request->query('status', 0);

        $contactsCount = Contact::count();

        $contacts = Contact::when($querySearch, function ($query) use ($querySearch) {
            $query->where('subject', 'like', '%' . $querySearch . '%');
        })
        ->when($queryStatus != '0', function ($query) use ($queryStatus) {
            $query->where('status', $queryStatus);
        });
        $contactsCountFiltered = $contacts->count();

        $contacts = $contacts->orderBy($querySort, $queryOrder)
            ->skip($queryOffset)
            ->take($queryLimit)
            ->get();
        
        return [
            'total' => $contactsCountFiltered,
            'totalNotFiltered' => $contactsCount,
            'rows' => $contacts->map(function ($contact) {
                return [
                    'id' => $contact->id,
                    'name' => $contact->name,
                    'email' => $contact->email,
                    'subject' => $contact->subject,
                    'status' => $contact->status == 'UNREAD' ? '<span class="badge badge-danger">' . __('Unread') . '</span>' : '<span class="badge badge-success">' . __('Read') . '</span>',
                    'created_at' => Carbon::parse($contact->created_at)->format('Y-m-d H:i:s'),
                    'menu' => view('admin.contacts._menu', ['contact' => $contact])->render(),
                ];
            }),
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Contact $contact)
    {
        $userAuth = new Permission($request->user()->group, 'admin');
        $this->authorize('auth-check', $userAuth->authorize('admin-contacts-index'));

        $breadcrumb = [
            __('Dashboard') => route('admin.index'),
            __('Messages') => route('admin.contacts.index'),
            $contact->subject => '',
        ];

        $createdAt = Carbon::parse($contact->created_at)->format('Y-m-d H:i:s');

        return view('admin.contacts.show', compact('contact', 'breadcrumb', 'createdAt'));
    }

    public function toggleStatus(Request $request, Contact $contact)
    {
        $userAuth = new Permission($request->user()->group, 'admin');
        $this->authorize('auth-check', $userAuth->authorize('admin-contacts-index'));

        if ($contact->status == 'READ') {
            $contact->update(['status' => 'UNREAD']);
        }
        else {
            $contact->update(['status' => 'READ']);
        }

        return redirect()->back()->with('success', __('Message has been updated!'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function edit(Contact $contact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contact $contact)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect()->back()->with('success', __('Message has been deleted!'));
    }
}
