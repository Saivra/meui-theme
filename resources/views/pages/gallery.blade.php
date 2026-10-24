<x-filament-panels::page>
    <div class="meui-studio" x-data="{ density: 'comfortable' }" :data-preview-density="density">
        <div class="meui-studio-toolbar">
            <div class="meui-studio-signature"><span class="meui-monogram" aria-hidden="true">m.</span><span>MeUI <span class="meui-muted">/ Filament v5</span></span><x-filament::badge color="gray">Preview</x-filament::badge></div>
            <div class="meui-studio-controls">
                <x-filament-panels::theme-switcher />
                <x-filament::dropdown placement="bottom-end">
                    <x-slot name="trigger"><x-filament::button color="gray" outlined icon="heroicon-o-adjustments-horizontal">Density</x-filament::button></x-slot>
                    <x-filament::dropdown.list>
                        <x-filament::dropdown.list.item x-on:click="density = 'comfortable'; close()">Comfortable</x-filament::dropdown.list.item>
                        <x-filament::dropdown.list.item x-on:click="density = 'compact'; close()">Compact</x-filament::dropdown.list.item>
                    </x-filament::dropdown.list>
                </x-filament::dropdown>
            </div>
        </div>
        <nav class="meui-studio-tabs" aria-label="Studio screens">
            @foreach (['table' => 'Data workspace', 'form' => 'Forms', 'record' => 'Record detail', 'auth' => 'Authentication', 'banners' => 'Banners', 'foundations' => 'Foundations'] as $key => $label)
                <button type="button" wire:click="selectScreen('{{ $key }}')" wire:loading.attr="disabled" @class(['meui-studio-tab', 'meui-selected' => $screen === $key]) aria-current="{{ $screen === $key ? 'page' : 'false' }}">{{ $label }}</button>
            @endforeach
        </nav>
        <div class="meui-studio-surface" wire:loading.class="meui-pending" wire:target="selectScreen">
            @if ($screen === 'table')
                <x-meui::page-intro eyebrow="The workspace" title="A place for every team." description="A focused view of your workspaces, their people, and what needs your attention.">
                    <x-slot name="actions">{{ $this->exampleDialogAction }}</x-slot>
                </x-meui::page-intro>
                <div class="meui-summary-strip">
                    <div><span>Total workspaces</span><strong>6</strong><small>Across your organization</small></div>
                    <div><span>Active</span><strong>3</strong><small>Ready for the day's work</small></div>
                    <div><span>In review</span><strong>2</strong><small>Waiting for the next step</small></div>
                    <div><span>Team members</span><strong>98</strong><small>People working together</small></div>
                </div>
                {{ $this->table }}
            @elseif ($screen === 'form')
                <x-meui::page-intro eyebrow="Workspace settings" title="Make room for good work." description="Clear labels, useful guidance, and just enough structure to keep you moving." />
                <div class="meui-form-layout">
                    <aside class="meui-form-guide"><span class="meui-step-number">01</span><h3>A few useful details</h3><p>Keep the essentials up to date so your team always knows where to start.</p><div class="meui-guide-note"><x-filament::icon icon="heroicon-o-information-circle" /><span>This is a working preview. Nothing is written to your application.</span></div></aside>
                    <form wire:submit="saveExample" class="meui-form-stack">
                        {{ $this->form }}
                        <div class="meui-save-bar">
                            <span role="status">{{ $saved ? 'Example validated. Your changes stay in this preview.' : 'Changes are kept in this preview only.' }}</span>
                            <div><x-filament::button color="gray" outlined wire:click="resetExample">Reset</x-filament::button><x-filament::button type="submit" wire:loading.attr="disabled" wire:target="saveExample">Save changes</x-filament::button></div>
                        </div>
                    </form>
                </div>
            @elseif ($screen === 'record')
                @php
                    $example = $this->selectedExample();
                @endphp
                <x-meui::page-intro :eyebrow="'Workspace / '.$example['reference']" :title="$recordName" description="Everything your team needs to keep the work connected.">
                    <x-slot name="actions"><x-filament::button color="gray" outlined icon="heroicon-o-pencil-square" wire:click="selectScreen('form')">Edit example</x-filament::button>{{ $this->exampleDialogAction }}</x-slot>
                </x-meui::page-intro>
                <div class="meui-record-layout">
                    <div class="meui-form-stack">{{ $this->record }}
                        <x-meui::frame title="Recent activity" description="A little context for the next person.">
                            <ol class="meui-timeline">
                                @foreach ([['ML', 'Mara Lind', 'updated the workspace details', 'Today, 10:42 AM'], ['IC', 'Idris Chen', 'joined the workspace', 'Yesterday, 3:18 PM'], ['ML', 'Mara Lind', 'created this workspace', 'October 14, 2026']] as [$initials, $name, $event, $date])
                                    <li><span class="meui-avatar" aria-hidden="true">{{ $initials }}</span><div><p><strong>{{ $name }}</strong> {{ $event }}</p><time>{{ $date }}</time></div></li>
                                @endforeach
                            </ol>
                        </x-meui::frame>
                    </div>
                    <x-meui::frame title="People" description="A team with a shared view.">
                        <div class="meui-people-list"><div><span class="meui-avatar" aria-hidden="true"><x-filament::icon icon="heroicon-o-user" /></span><div><strong>{{ $example['owner'] }}</strong><p>Workspace owner</p></div></div>@foreach ([['IC', 'Idris Chen', 'Administrator'], ['ER', 'Elena Rossi', 'Member']] as [$initials, $name, $role])@if ($name !== $example['owner'])<div><span class="meui-avatar">{{ $initials }}</span><div><strong>{{ $name }}</strong><p>{{ $role }}</p></div></div>@endif @endforeach</div>
                        <x-slot name="footer"><span class="meui-muted">Example people · no invitations are sent</span></x-slot>
                    </x-meui::frame>
                </div>
            @elseif ($screen === 'auth')
                <x-meui::page-intro eyebrow="First impressions" title="A warm welcome. A clear next step." description="The same visual language follows people through sign-in, verification, recovery, and MFA." />
                <div class="meui-auth-preview">
                    <aside><span class="meui-monogram">m.</span><h3>Good work starts<br />with a clear view.</h3><p>One thoughtfully organized workspace.</p><span class="meui-eyebrow">MeUI / Authentication</span></aside>
                    <div class="meui-auth-preview-content"><x-filament::icon icon="heroicon-o-finger-print" class="meui-auth-preview-icon" /><h3>Welcome back</h3><p>Sign in to continue to your workspace.</p><div class="meui-auth-anatomy"><span>Email address</span><div>you@example.test</div><span>Account verification</span><div class="meui-auth-placeholder" aria-hidden="true">••••••••••••</div></div><x-filament::button tag="a" :href="filament()->getLoginUrl()" icon="heroicon-o-arrow-right" icon-position="after">Open this panel's sign-in screen</x-filament::button><small>Preview anatomy. The real screen uses Filament's authentication flow.</small></div>
                </div>
            @elseif ($screen === 'banners')
                <x-meui::page-intro eyebrow="Clear communication" title="The right message, in the right place." description="Global notices live above the workspace. Contextual guidance uses the same visual language beside the work." />
                <div class="meui-banner-gallery">
                    @foreach (['announcement' => ['A new way to stay connected', 'Share useful product updates without interrupting the work.'], 'info' => ['A little context for your team', 'Give people the information they need to make a confident next move.'], 'success' => ['Your changes are ready', 'Acknowledge the completed step and make the next one clear.'], 'warning' => ['A review needs your attention', 'Explain what needs attention and when to act.'], 'danger' => ['Action is required to continue', 'Keep critical messages visible and give people a direct route to resolve them.']] as $tone => [$heading, $description])
                        <x-meui::banner :tone="$tone" :heading="$heading" :description="$description">
                            <x-slot name="actions"><x-filament::button color="gray" outlined wire:click="previewBanner('{{ $tone }}')">Preview above app</x-filament::button></x-slot>
                        </x-meui::banner>
                    @endforeach
                </div>
            @else
                <x-meui::page-intro eyebrow="The details" title="A shared visual language." description="Purposeful color, comfortable controls, and feedback that tells people what happens next." />
                <div class="meui-foundation-grid">
                    <x-meui::frame title="Actions" description="A clear hierarchy for decisions."><div class="meui-component-row"><x-filament::button>Primary action</x-filament::button><x-filament::button color="gray" outlined>Secondary</x-filament::button><x-filament::button disabled>Unavailable</x-filament::button>{{ $this->exampleDialogAction }}</div></x-meui::frame>
                    <x-meui::frame title="Status" description="Meaning stays legible in both themes."><div class="meui-component-row"><x-filament::badge color="success">Active</x-filament::badge><x-filament::badge color="warning">In review</x-filament::badge><x-filament::badge color="danger">Needs attention</x-filament::badge><x-filament::badge color="info">In progress</x-filament::badge><x-filament::badge color="gray">Archived</x-filament::badge></div></x-meui::frame>
                    <x-meui::frame title="Empty state"><x-meui::empty-state title="A clean slate" description="Your team's work will appear here. Start with one useful thing." icon="heroicon-o-square-3-stack-3d"><x-filament::button color="gray" outlined wire:click="selectScreen('form')">Explore a form</x-filament::button></x-meui::empty-state></x-meui::frame>
                    <x-meui::frame title="Loading state"><div class="meui-skeletons" role="status"><span class="fi-sr-only">Example loading state</span><div></div><div></div><div></div><div></div></div><p class="meui-muted">Content keeps its place while the next view arrives.</p></x-meui::frame>
                </div>
            @endif
        </div>
        <footer class="meui-studio-footer"><span>MeUI by Saivra</span><span>Native Filament components. A new perspective.</span></footer>
    </div>
    {{-- The table owns modals on its tab; other tabs also need Filament's guarded modal renderer. --}}
    <x-filament-actions::modals />
</x-filament-panels::page>
