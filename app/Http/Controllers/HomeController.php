<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\ExtensionListDocument;
use App\Models\Tab;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        ['groupId' => $groupId, 'ignoreGroupRestrictions' => $ignoreGroupRestrictions] = $this->resolveHomeViewerVisibility();

        $tabs = Tab::with([
            'cards' => function ($query) {
                $query->with(['category', 'dataCenter', 'userGroups'])->orderBy('name', 'asc');
            },
        ])->orderBy('order', 'asc')->get();

        $tabs->each(function (Tab $tab) use ($groupId, $ignoreGroupRestrictions) {
            $visible = $tab->cards
                ->filter(fn (Card $card) => $card->isVisibleToUserGroup($groupId, $ignoreGroupRestrictions))
                ->values();
            $tab->setRelation('cards', $visible);
        });

        $tabs = $tabs->filter(fn (Tab $tab) => $tab->cards->isNotEmpty())->values();

        $favoriteCards = collect();
        $favoriteCardIds = [];

        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            $favoriteCards = $user->favoriteCards()
                ->with(['category', 'dataCenter', 'tab', 'userGroups'])
                ->orderBy('name', 'asc')
                ->get()
                ->filter(fn (Card $card) => $card->isVisibleToUserGroup($groupId, $ignoreGroupRestrictions))
                ->values();
            $favoriteCardIds = $favoriteCards->pluck('id')->all();
        } elseif (Auth::guard('system')->check()) {
            $systemUser = Auth::guard('system')->user();
            $systemUser->loadMissing('user');
            $favoriteCards = $systemUser->favoriteCards()
                ->with(['category', 'dataCenter', 'tab', 'userGroups'])
                ->orderBy('name', 'asc')
                ->get()
                ->filter(fn (Card $card) => $card->isVisibleToUserGroup($groupId, $ignoreGroupRestrictions))
                ->values();
            $favoriteCardIds = $favoriteCards->pluck('id')->all();
        }

        $favoritesTab = null;
        if ($favoriteCards->isNotEmpty()) {
            $favoritesTab = (object) [
                'id' => 'favorites',
                'name' => 'Favoritos',
                'description' => 'Seus sistemas favoritos',
                'color' => '#F59E0B',
                'order' => -1,
                'cards' => $favoriteCards,
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

    /**
     * @return array{groupId: ?int, ignoreGroupRestrictions: bool}
     */
    private function resolveHomeViewerVisibility(): array
    {
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            $user->loadMissing('userGroup');

            return [
                'groupId' => $user->user_group_id,
                'ignoreGroupRestrictions' => ($user->userGroup?->full_access ?? false) || $user->hasFullAccess(),
            ];
        }

        if (Auth::guard('system')->check()) {
            $systemUser = Auth::guard('system')->user();
            $systemUser->loadMissing('user.userGroup');
            $linked = $systemUser->user;
            if (! $linked) {
                return ['groupId' => null, 'ignoreGroupRestrictions' => false];
            }

            return [
                'groupId' => $linked->user_group_id,
                'ignoreGroupRestrictions' => ($linked->userGroup?->full_access ?? false) || $linked->hasFullAccess(),
            ];
        }

        return ['groupId' => null, 'ignoreGroupRestrictions' => false];
    }
}
