<?php

namespace Saivra\MeUi\Support;

use Filament\Facades\Filament;
use Illuminate\Support\Facades\DB;

final class PreferenceStore
{
    public function key(): string
    {
        $user = Filament::auth()->user();
        abort_unless($user !== null, 403);

        return hash('sha256', json_encode([
            Filament::getId(),
            Filament::getAuthGuard(),
            $user::class,
            (string) $user->getAuthIdentifier(),
        ], JSON_THROW_ON_ERROR));
    }

    /** @return array<string, string> */
    public function read(): array
    {
        $cacheKey = 'meui.preferences.'.$this->key();

        if (request()->attributes->has($cacheKey)) {
            return request()->attributes->get($cacheKey);
        }

        $json = DB::table('meui_preferences')->where('key', $this->key())->value('preferences');
        $values = is_string($json) ? json_decode($json, true) : [];
        $values = is_array($values) ? ThemeOptions::sanitize($values) : [];
        request()->attributes->set($cacheKey, $values);

        return $values;
    }

    /** @param array<string, string> $values */
    public function write(array $values): void
    {
        $key = $this->key();

        if ($values === []) {
            DB::table('meui_preferences')->where('key', $key)->delete();
        } else {
            DB::table('meui_preferences')->upsert([
                'key' => $key,
                'preferences' => json_encode($values, JSON_THROW_ON_ERROR),
                'created_at' => now(),
                'updated_at' => now(),
            ], ['key'], ['preferences', 'updated_at']);
        }

        request()->attributes->set('meui.preferences.'.$key, $values);
    }
}
