<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

trait ExcelTrait
{
    /**
     * Export file.
     *
     * @param   Request  $request
     * @param   mixed   $export
     * @param   string   $title
     * @param   array    $allowedFormats
     *
     * @return  \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportFile(Request $request, $export, string $title, array $allowedFormats)
    {
        // Validate Input.
        $request->validate([
            'take' => 'required|numeric|gte:10',
            'format' => 'required|string|in:' . implode(',', $allowedFormats)
        ]);

        // Generate file name.
        $date = Carbon::now()->format('d_m_Y');
        $format = '.' . $request->format;
        $fileName = $title . '_' . $date . $format;

        return Excel::download($export, $fileName);
    }

    /**
     * Import file data.
     *
     * @param   Request  $request
     * @param   mixed   $import
     * @param   array    $allowedFormats
     *
     * @return  JsonResponse
     */
    public function importFile(Request $request, $import, array $allowedFormats)
    {
        // Validate Input.
        $request->validate([
            'file' => 'required|file|mimes:' . implode(',', $allowedFormats)
        ]);

        // Import the data.
        Excel::import($import, $request->file('file'));

        return response()->json([
            'ok' => true,
            'message' => 'Success to import files data.'
        ], 201);
    }
}
