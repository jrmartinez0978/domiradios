{{--
  FAQ Schema Component - SEO 2025 Critical para Featured Snippets

  Uso:
  <x-faq-schema :faqs="[
      ['question' => '�C�mo escuchar esta radio?', 'answer' => 'Haz clic en el bot�n...'],
      ['question' => '�Es gratis?', 'answer' => 'S�, completamente gratis...']
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
<div class="space-y-4" itemscope itemtype="https://schema.org/FAQPage">
    @foreach($faqs as $index => $faq)
    <div class="border-b border-glass-border pb-4 last:border-0" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
        <h3 class="text-base font-semibold text-gray-200 mb-2" itemprop="name">
            {{ $faq['question'] }}
        </h3>
        <div class="text-dark-300 text-sm leading-relaxed" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
            <div itemprop="text">
                {!! nl2br(e($faq['answer'])) !!}
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- FAQ Schema JSON-LD --}}
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "FAQPage",
  "mainEntity": [
    @foreach($faqs as $index => $faq)
    {
      "@@type": "Question",
      "name": "{{ $faq['question'] }}",
      "acceptedAnswer": {
        "@@type": "Answer",
        "text": "{{ strip_tags($faq['answer']) }}"
      }
    }{{ $index < count($faqs) - 1 ? ',' : '' }}
    @endforeach
  ]
}
</script>
@endif
