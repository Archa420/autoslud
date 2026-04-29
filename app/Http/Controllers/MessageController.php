<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $allMessages = Message::query()
            ->with(['ad', 'sender', 'receiver'])
            ->where(function ($query) use ($request) {
                $query->where('sender_id', $request->user()->id)
                    ->orWhere('receiver_id', $request->user()->id);
            })
            ->latest()
            ->get();

        $messages = $allMessages
            ->unique(function ($message) use ($request) {
                $otherUserId = $message->sender_id === $request->user()->id
                    ? $message->receiver_id
                    : $message->sender_id;

                return $message->ad_id . '-' . $otherUserId;
            })
            ->values();

        return view('messages.index', compact('messages'));
    }

    public function show(Request $request, Message $message)
    {
        if ($message->sender_id !== $request->user()->id && $message->receiver_id !== $request->user()->id) {
            abort(403);
        }

        $otherUserId = $message->sender_id === $request->user()->id
            ? $message->receiver_id
            : $message->sender_id;

        $conversation = Message::query()
            ->with(['ad', 'sender', 'receiver'])
            ->where('ad_id', $message->ad_id)
            ->where(function ($query) use ($request, $otherUserId) {
                $query->where(function ($q) use ($request, $otherUserId) {
                    $q->where('sender_id', $request->user()->id)
                        ->where('receiver_id', $otherUserId);
                })->orWhere(function ($q) use ($request, $otherUserId) {
                    $q->where('sender_id', $otherUserId)
                        ->where('receiver_id', $request->user()->id);
                });
            })
            ->oldest()
            ->get();

        return view('messages.show', [
            'message' => $message,
            'conversation' => $conversation,
            'otherUserId' => $otherUserId,
        ]);
    }

    public function store(Request $request, Ad $ad)
    {
        if (!$request->user()) {
            abort(403);
        }

        if ($request->user()->id === $ad->user_id) {
            return back()->with('error', 'Tu nevari nosūtīt ziņu pats sev.');
        }

        $data = $request->validate([
            'content' => ['required', 'string', 'max:2000'],
        ], [
            'content.required' => 'Ziņojuma teksts ir obligāts.',
            'content.max' => 'Ziņojums nedrīkst pārsniegt 2000 rakstzīmes.',
        ]);

        $message = Message::create([
            'ad_id' => $ad->id,
            'sender_id' => $request->user()->id,
            'receiver_id' => $ad->user_id,
            'content' => $data['content'],
        ]);

        return redirect()
            ->route('messages.show', $message)
            ->with('success', 'Ziņojums nosūtīts!');
    }

    public function reply(Request $request, Message $message)
    {
        if ($message->sender_id !== $request->user()->id && $message->receiver_id !== $request->user()->id) {
            abort(403);
        }

        $data = $request->validate([
            'content' => ['required', 'string', 'max:2000'],
        ], [
            'content.required' => 'Atbildes teksts ir obligāts.',
            'content.max' => 'Atbilde nedrīkst pārsniegt 2000 rakstzīmes.',
        ]);

        $receiverId = $message->sender_id === $request->user()->id
            ? $message->receiver_id
            : $message->sender_id;

        Message::create([
            'ad_id' => $message->ad_id,
            'sender_id' => $request->user()->id,
            'receiver_id' => $receiverId,
            'content' => $data['content'],
        ]);

        return redirect()
            ->route('messages.show', $message)
            ->with('success', 'Atbilde nosūtīta!');
    }
}