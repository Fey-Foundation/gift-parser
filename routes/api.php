<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Gift;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/get-gift-graph', function (Illuminate\Http\Request $request) {
    $userId = $request->query('userId');
    $depth = $request->query('depth', 1);

    if (!$userId) {
        return response()->json(['error' => 'Parameter "userId" is required.'], 400);
    }

    $users = [];
    $edges = [];
    $edgeSet = [];

    $traverse = function ($id, $currentDepth) use (&$users, &$edges, &$edgeSet, &$traverse, $depth) {
        if ($currentDepth > $depth) return;

        $user = \App\Models\TgUser::find($id);
        if (!$user) return;

        if (!collect($users)->contains('id', $user->id)) {
            $users[] = ['id' => $user->id, 'label' => $user->name];
        }

        $giftsSent = Gift::where('sender_user_id', $id)->get();
        foreach ($giftsSent as $gift) {
            $edgeKey = $gift->sender_user_id . '-' . $gift->recipient_user_id;
            if (!in_array($edgeKey, $edgeSet)) {
                $edges[] = [
                    'source' => $gift->sender_user_id,
                    'target' => $gift->recipient_user_id,
                    'type' => 'sent',
                    'gift_id' => $gift->id
                ];
                $edgeSet[] = $edgeKey;
                $traverse($gift->recipient_user_id, $currentDepth + 1);
            }
        }

        // Получаем подарки, которые получены
        $giftsReceived = Gift::where('recipient_user_id', $id)->get();
        foreach ($giftsReceived as $gift) {
            $edgeKey = $gift->sender_user_id . '-' . $gift->recipient_user_id;
            if (!in_array($edgeKey, $edgeSet)) {
                $edges[] = [
                    'source' => $gift->sender_user_id,
                    'target' => $gift->recipient_user_id,
                    'type' => 'received',
                    'gift_id' => $gift->id
                ];
                $edgeSet[] = $edgeKey;
                $traverse($gift->sender_user_id, $currentDepth + 1);
            }
        }
    };

    $traverse($userId, 0);




    foreach ($edges as $k=> $edge) {
        $b=false;
        foreach ($users as $user) {
            if ($user['id'] == $edge['target']){
                $b=true;
            }
        }
        if (!$b) {
            unset($edges[$k]);
        }
    }

    foreach ($edges as $k=> $edge) {
        $b=false;
        foreach ($users as $user) {
            if ($user['id'] == $edge['source']){
                $b=true;
            }
        }
        if (!$b) {
            unset($edges[$k]);
        }
    }


    return response()->json([
        'nodes' => array_values($users),
        'edges' => array_values($edges),
    ]);
});


