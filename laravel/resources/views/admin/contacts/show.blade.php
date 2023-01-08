<x-admin.layouts.app :breadcrumb="$breadcrumb">
    <x-slot:pageTitle>{{ __('Message') }}</x-slot>

    <div class="row">
        <div class="col-12 col-lg-6">

            @if (session('success'))
                <x-admin.components.alert>
                    {{ session('success') }}
                </x-admin.components.alert>
            @endif

            <div class="card card-primary card-outline">
                <div class="card-body p-0">
                    <div class="mailbox-read-info">
                        <h2 class="h5">{{ $contact->subject }}</h2>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="h6 w-50">{{ $contact->name }} ({{ $contact->email }})</div>
                            <div class="mailbox-read-time">{{ $createdAt }}</div>
                        </div>
                    </div>
                    <div class="mailbox-read-message">
                        {!! nl2br($contact->message, false) !!}
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-end">
                        <form action="{{ route('admin.contacts.toggle-status', ['contact' => $contact]) }}" method="POST">
                            @csrf
                            @method('PUT')

                            @if ($contact->status == 'UNREAD')
                                <button type="submit" class="btn btn-outline-primary">{{ __('Mark as Read') }}</button>
                            @else
                                <button type="submit" class="btn btn-outline-primary">{{ __('Mark as Unread') }}</button>
                            @endif
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-admin.layouts.app>