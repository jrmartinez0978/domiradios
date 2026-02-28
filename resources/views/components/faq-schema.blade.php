{{--
  FAQ Schema Component - SEO 2025 Critical para Featured Snippets

  Uso:
  <x-faq-schema :faqs="[
      ['question' => '¿Cómo escuchar esta radio?', 'answer' => 'Haz clic en el botón...'],
      ['question' => '¿Es gratis?', 'answer' => 'Sí, completamente gratis...']
  ]" />

  Beneficios:
  - Featured Snippets en Google
  - Google Assistant responses
  - Voice Search optimization
  - Rich results en SERP
--}}

@props(['faqs' => []])

@if(count($faqs) > 0)
{{-- FAQ Visual --}}
<div class="bg-white rounded-lg shadow-sm p-6 mb-6">
    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
        <svg class="w-6 h-6 mr-2 text-brand-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Preguntas Frecuentes
    </h2>

    <div class="space-y-4" itemscope itemtype="https://schema.org/FAQPage">
        @foreach($faqs as $index => $faq)
        <div class="border-b border-gray-200 pb-4 last:border-0" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
            <h3 class="text-lg font-semibold text-gray-900 mb-2" itemprop="name">
                {{ $faq['question'] }}
            </h3>
            <div class="text-gray-700 leading-relaxed" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
                <div itemprop="text">
                    {!! nl2br(e($faq['answer'])) !!}
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- FAQ Schema JSON-LD --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    @foreach($faqs as $index => $faq)
    {
      "@type": "Question",
      "name": "{{ $faq['question'] }}",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "{{ strip_tags($faq['answer']) }}"
      }
    }{{ $index < count($faqs) - 1 ? ',' : '' }}
    @endforeach
  ]
}
</script>
@endif
