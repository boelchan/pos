<?php

namespace App\DataTables;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Rappasoft\LaravelAuthenticationLog\Models\AuthenticationLog as ModelsAuthenticationLog;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class AuthenticationLogDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param  mixed  $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->editColumn('user_agent', function ($query) {
                return browser_agent($query->user_agent);
            })
            ->editColumn('login_at', function ($query) {
                $login_at = Carbon::parse($query->login_at);

                return ($query->login_at) ? $login_at->diffForHumans().' | '.$login_at->format('d/m/Y H:i:s') : '-';
            })
            ->editColumn('login_successful', function ($query) {
                return $query->login_successful == true ? 'Berhasil' : 'Gagal';
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param  \App\Models\AuthenticationLog  $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ModelsAuthenticationLog $model)
    {
        return $model->where('authenticatable_id', (request()->segment(2) ?? Auth::id()));
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('authenticationlog-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('<"d-flex justify-content-between p-2 pt-3" row <"col-lg-6 d-flex"f> <"col-lg-6 d-flex justify-content-end px-2"B> >t <"d-flex justify-content-between m-2 row" <"col-md-6 d-flex justify-content-center justify-content-md-start"li> <"col-md-6 px-0"p> >')
            ->stateSave(false)
            ->orderBy(3, 'desc')
            ->buttons(
                Button::make('reset')
            );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::computed('No')->data('DT_RowIndex'),
            Column::make('ip_address'),
            Column::make('user_agent')->title('browser'),
            Column::make('login_at'),
            Column::make('login_successful')->title('Status'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'AuthenticationLog_'.date('YmdHis');
    }
}
