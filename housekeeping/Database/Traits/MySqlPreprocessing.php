<?php

namespace Housekeeping\Database\Traits;

trait MySqlPreprocessing
{
    private string $columns_str;
    private string $bind_str;
    private string $update_bind_str;

    public function __construct()
    {
        // Prepare a list of columns
        $columns = array_map(function ($val) {
            return '`' . $val . '`';
        }, $this->columns);
        $this->columns_str = implode(', ', $columns);


        // Prepare a list of bind params
        $bind_params = array_map(function ($val) {
            return ':' . $val;
        }, $this->columns);
        $this->bind_str = implode(', ', $bind_params);

        // Prepare a list of update binds
        $update_params = array_map(function ($val) {
            return '`' . $val . '` = ' . ':' . $val;
        }, $this->columns);
        $this->update_bind_str = implode(',', $update_params);
    }
}
