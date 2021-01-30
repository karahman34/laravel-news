<?php

namespace App\Imports;

use App\Models\Menu;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MenusImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Menu([
            'parent_id' => $row['parent_id'],
            'name' => $row['name'],
            'icon' => $row['icon'],
            'path' => $row['path'],
            'has_sub_menus' => $row['has_sub_menus'],
        ]);
    }
}
