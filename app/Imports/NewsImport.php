<?php

namespace App\Imports;

use App\Models\News;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class NewsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new News([
            'user_id' => $row['user_id'],
            'banner_image' => $row['banner_image'],
            'title' => $row['title'],
            'summary' => $row['summary'],
            'content' => $row['content'],
            'views' => $row['views'],
            'is_headline' => $row['is_headline'],
            'status' => $row['status'],
        ]);
    }
}
