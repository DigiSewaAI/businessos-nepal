<?php
namespace App\Exports;

use App\Models\AI\Conversation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ConversationExport implements FromCollection, WithHeadings, WithMapping
{
    protected $userId;

    public function __construct($userId = null)
    {
        $this->userId = $userId;
    }

    public function collection()
    {
        $query = Conversation::with('messages')
            ->where('organization_id', auth()->user()->organization_id);
            
        if ($this->userId) {
            $query->where('user_id', $this->userId);
        }
        
        return $query->get();
    }

    public function headings(): array
    {
        return ['Conversation ID', 'User', 'Title', 'Total Messages', 'Created At'];
    }

    public function map($conversation): array
    {
        return [
            $conversation->id,
            $conversation->user->name ?? 'N/A',
            $conversation->title,
            $conversation->messages->count(),
            $conversation->created_at->format('Y-m-d H:i:s'),
        ];
    }
}