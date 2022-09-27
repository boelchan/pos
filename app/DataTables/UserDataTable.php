<?php

namespace App\DataTables;

use App\Models\User;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class UserDataTable extends DataTable
{
    public function dataTable($query)
    {
        $dataTable = datatables()->eloquent($query)->addIndexColumn();

        if (! request()->search['value']) {
            $dataTable->filter(function ($query) {
                if (request()->email) {
                    $query->where('email', 'like', '%'.request()->email.'%');
                }
                if (request()->name) {
                    $query->where('name', 'like', '%'.request()->name.'%');
                }
                if (request()->role) {
                    $query->where('role_id', request()->role);
                }
                if (request()->status) {
                    if (request()->status == 'aktif') {
                        $query->whereNull('banned_at');
                    }
                    if (request()->status == 'tidakAktif') {
                        $query->whereNotNull('banned_at');
                    }
                }
            });
        }

        return $dataTable->editColumn('created_at', function ($query) {
            return $query->created_at;
        })
            ->editColumn('role', function ($query) {
                return $query->roles->first()->name;
            })
            ->editColumn('email_verified_at', function ($query) {
                return $query->email_verified_at;
            })
            ->editColumn('banned_at', function ($query) {
                return $query->banned_at ? '<span class="badge bg-pink">Tidak Aktif</span>' : '<span class="badge bg-green">Aktif</span>';
            })
            ->editColumn('action', function ($query) {
                return view('components.button.show', ['action' => route('user.show', [$query->id, 'uuid' => $query->uuid])]).
                    view('components.button.edit', ['action' => route('user.edit', [$query->id, 'uuid' => $query->uuid])]).
                    view('components.button.destroy', ['action' => route('user.destroy', [$query->id, 'uuid' => $query->uuid]), 'label' => $query->name, 'target' => 'user-table']);
            })
            ->rawColumns(['banned_at', 'action']);
    }

    public function query(User $model)
    {
        return $model->select('users.*')->leftJoin('model_has_roles', 'model_id', '=', 'users.id');
    }

    public function html()
    {
        return $this->builder()
            ->setTableId('user-table')
            ->columns($this->getColumns())
            ->orderBy(2, 'desc')
            ->ajax([
                'data' => 'function(d) { 
                    d.email = $("#email").val(); 
                    d.name = $("#name").val();
                    d.role = $("#role").val();
                    d.status = $("#status").val();
                }',
            ])
            ->drawCallback("function( settings ) { $(document).find('[data-toggle=\"tooltip\"]').tooltip(); }")
            ->buttons('create')
            ->dom('<"d-flex justify-content-between p-2 pt-3" row <"col-lg-6 d-flex"f> <"col-lg-6 d-flex justify-content-end px-2"B> >t <"d-flex justify-content-between m-2 row" <"col-md-6 d-flex justify-content-center justify-content-md-start"li> <"col-md-6 px-0"p> >');
    }

    protected function getColumns()
    {
        return [
            Column::computed('id')->title('no')->data('DT_RowIndex'),
            Column::make('name'),
            Column::make('email'),
            Column::computed('role'),
            Column::make('created_at')->title('Tanggal Daftar'),
            Column::make('email_verified_at')->title('Tanggal verifikasi'),
            Column::computed('banned_at')->title('Status'),
            Column::computed('action')->addClass('text-center'),
        ];
    }
}
