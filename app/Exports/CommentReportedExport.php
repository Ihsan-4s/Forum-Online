<?php

namespace App\Exports;

use App\Models\comment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;


class CommentReportedExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Comment::where('is_reported', true)
            ->whereNull('deleted_at')
            ->get();
    }

    public function headings(): array
    {
        return ['ID', 'Content', 'Thread ID', 'User ID', 'Is Reported', 'Report Reason', 'Created At', 'Updated At'];
    }

    public function map($comment): array
    {
        return [
            $comment->id,
            $comment->content,
            $comment->thread_id,
            $comment->user_id,
            $comment->is_reported,
            $comment->report_reason,
            $comment->created_at,
            $comment->updated_at,
        ];
    }
}
