<?php

namespace App\Exports;

use App\Models\thread;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReportedExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Thread::where('is_reported', true)
            ->whereNull('deleted_at')
            ->get();
    }

    public function headings(): array
    {
        return ['ID', 'Title', 'Content', 'User ID', 'Is Reported', 'Report Reason', 'Created At', 'Updated At'];
    }

    public function map($thread): array
    {
        return [
            $thread->id,
            $thread->title,
            $thread->content,
            $thread->user_id,
            $thread->is_reported,
            $thread->report_reason,
            $thread->created_at,
            $thread->updated_at,
        ];
    }
}
