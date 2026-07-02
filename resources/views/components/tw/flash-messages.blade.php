@if ($errors->any())
    <x-tw.alert type="danger" title="Please fix the following errors">
        <ul class="list-disc space-y-1 pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </x-tw.alert>
@endif

@if (session('success'))
    <x-tw.alert type="success">{{ session('success') }}</x-tw.alert>
@endif

@if (session('error'))
    <x-tw.alert type="danger">{{ session('error') }}</x-tw.alert>
@endif

@if (session('warning'))
    <x-tw.alert type="warning">{{ session('warning') }}</x-tw.alert>
@endif

@if (session('info'))
    <x-tw.alert type="info">{{ session('info') }}</x-tw.alert>
@endif
