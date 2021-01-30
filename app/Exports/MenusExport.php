<?php

namespace App\Exports;

use App\Models\Menu;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MenusExport implements FromCollection, ShouldAutoSize, WithHeadings
{
    public $take;

    public function __construct(int $take)
    {
        $this->take = $take;
    }

    public function headings(): array
    {
        return [
            'id',
            'parent_id',
            'name',
            'icon',
            'path',
            'has_sub_menus',
            'created_at',
            'updated_at',
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Menu::select('id', 'parent_id', 'name', 'icon', 'path', 'has_sub_menus', 'created_at', 'updated_at')
                        ->limit($this->take)
                        ->get();
    }
}
