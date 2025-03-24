<div class="col-xl-3 col-md-6 mb-3">
    <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-3 text-center">
                        <i class="{{ $icon }} text-primary"></i>
                        {{ $title }}
                    </div>
                    <div class="d-flex flex-column flex-md-row justify-content-center ">
                        @foreach($data as $item)
                            <div class="data-item text-center mx-2">
                                <div class="h6 mb-1 font-weight-bold text-gray-800 {{ $item['color'] ?? '' }}">
                                    {{ $item['label'] }}
                                </div>
                                <div class="h6 mb-1 font-weight-bold text-gray-800 {{ $item['color'] ?? '' }}">
                                    {{ $item['suffix'] ?? '' }} {{ $item['value'] }} 
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
