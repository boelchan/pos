<div class="collapse mb-2" id="collapse-datatable-filter">
    <div class="card border-primary">
        <div class="card-header py-2">
            <h3 class="card-title">Pencarian</h3>
        </div>
        <div class="card-body filter-datatable-form d-flex flex-sm-row py-2">
            <div class="row col-12">
                {!! $slot !!}
            </div>
        </div>
        <div class="card-footer d-flex justify-content-center p-1">
            <button class="btn btn-pill me-1" type="button" 
                data-bs-toggle="collapse"
                data-bs-target="#collapse-datatable-filter" aria-expanded="false"
                aria-controls="collapse-datatable-filter">
                Tutup
            </button>
            {!! Form::button('Cari', ['class' => 'submit-filter btn btn-primary btn-pill me-1', 'data-target' => $target, 'value'=> 'submit']) !!}
            {!! Form::button('Reset', ['class' => 'submit-filter btn btn-warning btn-pill', 'data-target' => $target, 'value'=> 'reset']) !!}
        </div>
    </div>
</div>