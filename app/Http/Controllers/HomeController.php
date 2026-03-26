<?php

namespace App\Http\Controllers;

use App\Models\ExtensionListDocument;
use App\Models\Tab;
use App\Models\Card;
use App\Models\UserFavorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // Carregar abas com cards
        $tabs = Tab::with(['cards' => function($query) {
            $query->with(['category', 'dataCenter'])->orderBy('name', 'asc');
        }])->orderBy('order', 'asc')->get();

        // Carregar favoritos do usuário logado
        $favoriteCards = collect();
        $favoriteCardIds = [];
        
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            $favoriteCards = $user->favoriteCards()->with(['category', 'dataCenter', 'tab'])->orderBy('name', 'asc')->get();
            $favoriteCardIds = $user->favoriteCards()->pluck('cards.id')->toArray();
        } elseif (Auth::guard('system')->check()) {
            $systemUser = Auth::guard('system')->user();
            $favoriteCards = $systemUser->favoriteCards()->with(['category', 'dataCenter', 'tab'])->orderBy('name', 'asc')->get();
            $favoriteCardIds = $systemUser->favoriteCards()->pluck('cards.id')->toArray();
        }

        // Criar aba virtual de favoritos se houver favoritos
        $favoritesTab = null;
        if ($favoriteCards->isNotEmpty()) {
            $favoritesTab = (object) [
                'id' => 'favorites',
                'name' => 'Favoritos',
                'description' => 'Seus sistemas favoritos',
                'color' => '#F59E0B', // Cor dourada para favoritos
                'order' => -1, // Aparecer primeiro
                'cards' => $favoriteCards
            ];
        }

        $extensionListSvgUrl = null;
        if (Auth::guard('web')->check() || Auth::guard('system')->check()) {
            if (ExtensionListDocument::current()) {
                $extensionListSvgUrl = route('extension-list.document');
            }
        }

        return view('home', compact('tabs', 'favoritesTab', 'favoriteCardIds', 'extensionListSvgUrl'));
    }
} 