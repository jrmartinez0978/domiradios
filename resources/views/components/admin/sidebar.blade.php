{{-- Sidebar Overlay (mobile) --}}
<div
    x-show="sidebarOpen"
    x-cloak
    @click="sidebarOpen = false"
    class="fixed inset-0 z-30 bg-black/60 backdrop-blur-sm lg:hidden"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
></div>

{{-- Sidebar Panel --}}
<aside
    class="fixed top-16 left-0 z-30 w-64 h-[calc(100vh-4rem)] bg-dark-900/95 backdrop-blur-xl border-r border-glass-border overflow-y-auto transition-transform duration-300"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
>
    <nav class="p-4 space-y-6">
        {{-- Main --}}
        <div>
            <h3 class="px-3 mb-2 text-xs font-semibold uppercase tracking-wider text-dark-400">Principal</h3>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                              {{ request()->routeIs('admin.dashboard') ? 'bg-accent-red/20 text-accent-red border border-accent-red/30' : 'text-dark-300 hover:text-white hover:bg-glass-white-10' }}">
                        <i class="fas fa-tachometer-alt w-5 text-center"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
            </ul>
        </div>

        {{-- Content --}}
        <div>
            <h3 class="px-3 mb-2 text-xs font-semibold uppercase tracking-wider text-dark-400">Contenido</h3>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('admin.radios.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                              {{ request()->routeIs('admin.radios.*') ? 'bg-accent-red/20 text-accent-red border border-accent-red/30' : 'text-dark-300 hover:text-white hover:bg-glass-white-10' }}">
                        <i class="fas fa-broadcast-tower w-5 text-center"></i>
                        <span>Radios</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.genres.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                              {{ request()->routeIs('admin.genres.*') ? 'bg-accent-red/20 text-accent-red border border-accent-red/30' : 'text-dark-300 hover:text-white hover:bg-glass-white-10' }}">
                        <i class="fas fa-map-marker-alt w-5 text-center"></i>
                        <span>Generos</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.blog-posts.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                              {{ request()->routeIs('admin.blog-posts.*') ? 'bg-accent-red/20 text-accent-red border border-accent-red/30' : 'text-dark-300 hover:text-white hover:bg-glass-white-10' }}">
                        <i class="fas fa-newspaper w-5 text-center"></i>
                        <span>Blog Posts</span>
                    </a>
                </li>
            </ul>
        </div>

        {{-- System --}}
        <div>
            <h3 class="px-3 mb-2 text-xs font-semibold uppercase tracking-wider text-dark-400">Sistema</h3>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('admin.users.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                              {{ request()->routeIs('admin.users.*') ? 'bg-accent-red/20 text-accent-red border border-accent-red/30' : 'text-dark-300 hover:text-white hover:bg-glass-white-10' }}">
                        <i class="fas fa-users w-5 text-center"></i>
                        <span>Usuarios</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.themes.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                              {{ request()->routeIs('admin.themes.*') ? 'bg-accent-red/20 text-accent-red border border-accent-red/30' : 'text-dark-300 hover:text-white hover:bg-glass-white-10' }}">
                        <i class="fas fa-palette w-5 text-center"></i>
                        <span>Temas</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.settings.edit') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                              {{ request()->routeIs('admin.settings.*') ? 'bg-accent-red/20 text-accent-red border border-accent-red/30' : 'text-dark-300 hover:text-white hover:bg-glass-white-10' }}">
                        <i class="fas fa-cog w-5 text-center"></i>
                        <span>Settings</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.configs.edit') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                              {{ request()->routeIs('admin.configs.*') ? 'bg-accent-red/20 text-accent-red border border-accent-red/30' : 'text-dark-300 hover:text-white hover:bg-glass-white-10' }}">
                        <i class="fas fa-sliders-h w-5 text-center"></i>
                        <span>Config</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</aside>
