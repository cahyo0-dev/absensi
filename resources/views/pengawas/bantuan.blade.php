@extends('layouts.app')

@section('title', 'Bantuan & Panduan')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-4xl mx-auto px-4">
            <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-200 bg-white">
                    <h5 class="text-lg font-semibold text-gray-800 flex items-center">
                        <i class="fas fa-question-circle text-gray-600 mr-2"></i> Bantuan & Panduan
                    </h5>
                </div>

                <!-- Body -->
                <div class="p-6">
                    <!-- Section Header -->
                    <div class="mb-6">
                        <h6 class="font-medium flex items-center text-gray-700 mb-2">
                            <i class="fas fa-info-circle text-blue-500 mr-2"></i> Panduan Penggunaan Sistem BMKG
                        </h6>
                        <p class="text-gray-500 text-sm">Berikut adalah panduan untuk pengawas</p>
                    </div>

                    <!-- FAQ Accordion -->
                    <div class="space-y-3" id="faqAccordion">
                        @foreach ($faqs as $index => $faq)
                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                <button
                                    class="w-full px-4 py-3 text-left flex justify-between items-center bg-white hover:bg-gray-50 transition-colors duration-200 faq-toggle"
                                    data-target="faq-{{ $index }}">
                                    <span class="font-medium text-gray-800 text-sm">{{ $faq['question'] }}</span>
                                    <svg class="w-4 h-4 text-gray-500 transform transition-transform duration-200 faq-arrow"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div id="faq-{{ $index }}" class="hidden border-t border-gray-200 bg-gray-50">
                                    <div class="p-4">
                                        <p class="text-gray-600 text-sm leading-relaxed">{{ $faq['answer'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Help Section -->
                    <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-100">
                        <h6 class="font-medium flex items-center text-gray-700 mb-2">
                            <i class="fas fa-life-ring text-blue-500 mr-2"></i> Butuh Bantuan Teknis?
                        </h6>
                        <p class="text-gray-600 text-sm mb-0">Silakan hubungi administrator sistem untuk bantuan lebih
                            lanjut.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const faqToggles = document.querySelectorAll('.faq-toggle');

            faqToggles.forEach(toggle => {
                toggle.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const content = document.getElementById(targetId);
                    const arrow = this.querySelector('.faq-arrow');

                    // Toggle konten
                    content.classList.toggle('hidden');

                    // Rotate panah
                    arrow.classList.toggle('rotate-180');
                });
            });
        });
    </script>

    <style>
        .faq-arrow {
            transition: transform 0.2s ease-in-out;
        }

        .rotate-180 {
            transform: rotate(180deg);
        }
    </style>
@endsection
