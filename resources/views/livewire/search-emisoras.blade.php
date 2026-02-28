<div class="w-full max-w-2xl mx-auto">
  <form wire:submit="search">
    <label class="sr-only" for="search">Buscar emisoras</label>
    <div class="flex bg-white rounded-full overflow-hidden border border-surface-300 group hover:border-primary/30 transition-all duration-300 shadow-sm">
      <input id="search" wire:model.live.debounce.300ms="query" type="text" placeholder="Buscar emisoras por nombre, género o ciudad..."
        class="flex-1 px-6 py-3 xl:py-4 bg-transparent text-gray-800 placeholder-gray-400 focus:outline-none" />
      <button type="submit" class="px-6 py-3 xl:py-4 bg-primary text-white hover:bg-primary-700 transition-all duration-300 focus:outline-none">
        <i class="fas fa-search"></i>
      </button>
    </div>
    <div class="text-xs text-gray-400 text-center mt-2">
      Presiona Enter para buscar
    </div>
  </form>
</div>
