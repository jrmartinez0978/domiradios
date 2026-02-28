<div class="w-full max-w-2xl mt-10">
  <form wire:submit="search">
    <label class="sr-only" for="search">Buscar emisoras</label>
    <div class="flex bg-white rounded-full overflow-hidden shadow-xl group hover:shadow-2xl transition-all duration-300">
      <input id="search" wire:model.live="query" type="text" placeholder="Buscar emisoras por nombre, género o ciudad..." 
        class="flex-1 px-6 py-3 xl:py-4 text-slate-700 focus:outline-none" />
      <button type="submit" class="px-6 py-3 xl:py-4 bg-gradient-to-r from-brand-blue to-brand-red text-white hover:opacity-90 transition-all duration-300 focus:outline-none">
        <i class="fas fa-search"></i>
      </button>
    </div>
    <div class="text-xs text-white text-center mt-2 opacity-75">
      Presiona Enter para buscar
    </div>
  </form>
</div>
