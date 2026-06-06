<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SupportTicketController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'topic' => ['required', 'string', 'max:120'],
            'order_reference' => ['nullable', 'string', 'max:120'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $ticket = SupportTicket::create([
            'user_id' => $request->user()->id,
            'reference' => 'TKT-' . now()->format('ymdHis') . '-' . $request->user()->id,
            'topic' => $data['topic'],
            'order_reference' => $data['order_reference'] ?? null,
            'message' => $data['message'],
            'status' => 'Open',
        ]);

        return response()->json([
            'ticket' => [
                'id' => $ticket->id,
                'ref' => $ticket->reference,
                'topic' => $ticket->topic,
                'order' => $ticket->order_reference,
                'message' => $ticket->message,
                'status' => $ticket->status,
                'createdAt' => $ticket->created_at?->toISOString(),
            ],
        ], 201);
    }
}
