<div class="w-full max-w-2xl mx-auto">
  <form wire:submit="search">
    <label class="sr-only" for="search">Buscar emisoras</label>
    <div class="flex bg-glass-white-10 backdrop-blur-xl rounded-full overflow-hidden border border-glass-border-light group hover:border-accent-red/30 transition-all duration-300">
      <input id="search" wire:model.live="query" type="text" placeholder="Buscar emisoras por nombre, género o ciudad..."
        class="flex-1 px-6 py-3 xl:py-4 bg-transparent text-gray-100 placeholder-dark-400 focus:outline-none" />
      <button type="submit" class="px-6 py-3 xl:py-4 bg-gradient-to-r from-accent-red to-red-600 text-white hover:opacity-90 transition-all duration-300 focus:outline-none">
        <i class="fas fa-search"></i>
      </button>
    </div>
    <div class="text-xs text-dark-500 text-center mt-2">
      Presiona Enter para buscar
    </div>
  </form>
</div>
