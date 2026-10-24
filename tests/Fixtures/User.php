<?php

namespace Saivra\MeUi\Tests\Fixtures;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser
{
    protected $guarded = [];

    protected function casts(): array
    {
        return ['can_preview' => 'boolean'];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }
}
