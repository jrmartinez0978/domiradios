{{-- Sidebar Overlay (mobile) --}}
<div
    x-show="sidebarOpen"
    x-cloak
    @click="sidebarOpen = false"
    class="fixed inset-0 z-30 bg-black/30 backdrop-blur-sm lg:hidden"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
></div>

{{-- Sidebar Panel --}}
<aside
    class="fixed top-16 left-0 z-30 w-64 h-[calc(100vh-4rem)] bg-white border-r border-surface-300 overflow-y-auto transition-transform duration-300 shadow-sm"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
>
    <nav class="p-4 space-y-6">
        {{-- Main --}}
        <div>
            <h3 class="px-3 mb-2 text-xs font-semibold uppercase tracking-wider text-gray-400">Principal</h3>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                              {{ request()->routeIs('admin.dashboard') ? 'bg-primary-50 text-primary border border-primary/20' : 'text-gray-600 hover:text-primary hover:bg-primary-50' }}">
                        <i class="fas fa-tachometer-alt w-5 text-center"></i>
                        <span>Panel</span>
                    </a>
                </li>
            </ul>
        </div>

        {{-- Content --}}
        <div>
            <h3 class="px-3 mb-2 text-xs font-semibold uppercase tracking-wider text-gray-400">Contenido</h3>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('admin.radios.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                              {{ request()->routeIs('admin.radios.*') ? 'bg-primary-50 text-primary border border-primary/20' : 'text-gray-600 hover:text-primary hover:bg-primary-50' }}">
                        <i class="fas fa-broadcast-tower w-5 text-center"></i>
                        <span>Radios</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.genres.index', ['type' => 'genre']) }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                              {{ request()->routeIs('admin.genres.*') && request('type', 'genre') === 'genre' ? 'bg-primary-50 text-primary border border-primary/20' : 'text-gray-600 hover:text-primary hover:bg-primary-50' }}">
                        <i class="fas fa-music w-5 text-center"></i>
                        <span>Géneros</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.genres.index', ['type' => 'city']) }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                              {{ request()->routeIs('admin.genres.*') && request('type') === 'city' ? 'bg-primary-50 text-primary border border-primary/20' : 'text-gray-600 hover:text-primary hover:bg-primary-50' }}">
                        <i class="fas fa-city w-5 text-center"></i>
                        <span>Ciudades</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.blog-posts.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                              {{ request()->routeIs('admin.blog-posts.*') ? 'bg-primary-50 text-primary border border-primary/20' : 'text-gray-600 hover:text-primary hover:bg-primary-50' }}">
                        <i class="fas fa-newspaper w-5 text-center"></i>
                        <span>Blog</span>
                    </a>
                </li>
            </ul>
        </div>

        {{-- System --}}
        <div>
            <h3 class="px-3 mb-2 text-xs font-semibold uppercase tracking-wider text-gray-400">Sistema</h3>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('admin.users.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                              {{ request()->routeIs('admin.users.*') ? 'bg-primary-50 text-primary border border-primary/20' : 'text-gray-600 hover:text-primary hover:bg-primary-50' }}">
                        <i class="fas fa-users w-5 text-center"></i>
                        <span>Usuarios</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.themes.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                              {{ request()->routeIs('admin.themes.*') ? 'bg-primary-50 text-primary border border-primary/20' : 'text-gray-600 hover:text-primary hover:bg-primary-50' }}">
                        <i class="fas fa-palette w-5 text-center"></i>
                        <span>Temas</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.settings.edit') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                              {{ request()->routeIs('admin.settings.*') ? 'bg-primary-50 text-primary border border-primary/20' : 'text-gray-600 hover:text-primary hover:bg-primary-50' }}">
                        <i class="fas fa-cog w-5 text-center"></i>
                        <span>Ajustes</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.configs.edit') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                              {{ request()->routeIs('admin.configs.*') ? 'bg-primary-50 text-primary border border-primary/20' : 'text-gray-600 hover:text-primary hover:bg-primary-50' }}">
                        <i class="fas fa-sliders-h w-5 text-center"></i>
                        <span>Configuracion</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</aside>
