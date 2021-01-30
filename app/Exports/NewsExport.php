<?php

namespace App\Exports;

use App\Models\News;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class NewsExport implements FromCollection, ShouldAutoSize, WithHeadings
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
            'user_id',
            'banner_image',
            'title',
            'summary',
            'views',
            'is_headline',
            'status',
            'created_at',
            'updated_at',
            'content',
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return News::select(
            'id',
            'user_id',
            'banner_image',
            'title',
            'summary',
            'views',
            'is_headline',
            'status',
            'created_at',
            'updated_at',
            'content',
        )
        ->limit($this->take)
        ->get();
    }
}
