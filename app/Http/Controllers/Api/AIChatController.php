<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Ai\Agents\MenuAssistantAgent;

class AIChatController extends Controller
{
    /**
     * Get the list of past conversations for the current user/session.
     */
    public function getConversations(Request $request)
    {
        $userId = auth()->id();
        $sessionId = session()->getId();

        $query = DB::table('agent_conversations')
            ->orderBy('updated_at', 'desc');

        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }

        return response()->json($query->get(['id', 'title', 'updated_at']));
    }

    /**
     * Get messages for a specific conversation.
     */
    public function getMessages($id, Request $request)
    {
        $userId = auth()->id();
        $sessionId = session()->getId();

        $conversation = DB::table('agent_conversations')
            ->where('id', $id)
            ->first();

        if (!$conversation) {
            return response()->json(['error' => 'Conversation not found'], 404);
        }

        // Verify ownership
        if ($userId) {
            if ($conversation->user_id !== $userId) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
        } else {
            if ($conversation->session_id !== $sessionId) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
        }

        $messages = DB::table('agent_conversation_messages')
            ->where('conversation_id', $id)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    /**
     * Send a new message to the AI.
     */
    public function ask(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:1000',
            'conversation_id' => 'nullable|string',
            'menu_id' => 'nullable|exists:menus,id'
        ]);

        try {
            $agent = new MenuAssistantAgent();
            
            // Set participant (user or session dummy object)
            $participant = auth()->user();
            if (!$participant) {
                // Dummy object for guest
                $participant = (object) ['id' => null, 'session_id' => session()->getId()];
            }

            if ($request->conversation_id) {
                $agent->continue($request->conversation_id, $participant);
            } else {
                $agent->forUser($participant);
                
                // If this is a new conversation and we have a specific menu,
                // we can prepend some context implicitly
                if ($request->menu_id) {
                    $menu = Menu::find($request->menu_id);
                    $context = "Sebagai informasi, saat ini saya sedang melihat menu: {$menu->name}. ";
                    $request->merge(['question' => $context . $request->question]);
                }
            }

            // Generate response using gemini
            $response = $agent->prompt($request->question, provider: 'gemini');

            // Find out the assigned conversation ID 
            $conversationId = $agent->currentConversation();

            // If we are a guest, update the session_id in the database
            if (!auth()->check() && $conversationId) {
                DB::table('agent_conversations')
                    ->where('id', $conversationId)
                    ->whereNull('session_id') // only update if not set
                    ->update(['session_id' => session()->getId()]);
            }

            return response()->json([
                'conversation_id' => $conversationId,
                'answer' => $response->text
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('AI Error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json([
                'error' => 'Gagal terhubung ke AI: ' . $e->getMessage()
            ], 500);
        }
    }
}
