<form method="GET" action="{{ route('admin.applications.index') }}" id="{{ $formId ?? 'applications-filter-form' }}" class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
    <div class="xl:col-span-2">
        <label class="form-label">Search</label>
        <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Name, email, phone, reference..." class="form-input">
    </div>
    <div>
        <label class="form-label">Status</label>
        <select name="status" class="form-input">
            <option value="">All statuses</option>
            @foreach($statuses as $key => $meta)
                <option value="{{ $key }}" @selected(($filters['status'] ?? '') === $key)>{{ $meta['label'] }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="form-label">County</label>
        <select name="county" class="form-input">
            <option value="">All counties</option>
            @foreach($counties as $county)
                <option value="{{ $county }}" @selected(($filters['county'] ?? '') === $county)>{{ $county }}</option>
            @endforeach
        </select>
    </div>
    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="form-label">From</label>
            <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" class="form-input">
        </div>
        <div>
            <label class="form-label">To</label>
            <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" class="form-input">
        </div>
    </div>
    <div class="flex flex-wrap items-end gap-2 md:col-span-2 xl:col-span-5">
        <button type="submit" class="btn-primary w-full sm:w-auto">
            <i class="fa-solid fa-filter mr-1.5"></i> Apply Filters
        </button>
        <a href="{{ route('admin.applications.index') }}" class="btn-secondary w-full sm:w-auto">Clear</a>
        @permission('applications.export')
            <a href="{{ route('admin.applications.export', $filters) }}" class="btn-secondary w-full sm:w-auto">
                <i class="fa-solid fa-file-csv mr-1.5"></i> Export CSV
            </a>
        @endpermission
    </div>
</form>
