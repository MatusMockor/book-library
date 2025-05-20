<x-bootstrap-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-4 text-dark">
            {{ __('Authors Dashboard (Bootstrap)') }}
        </h2>
    </x-slot>

    <div class="container py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <bootstrap-author-list></bootstrap-author-list>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-bootstrap-layout>
