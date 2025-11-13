@extends('layouts.admin')

@section('title', 'Bantuan & Panduan')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6 border-b">
                    <h5 class="text-xl font-semibold mb-2 flex items-center">
                        <i class="fas fa-question-circle text-blue-500 mr-2"></i>
                        Bantuan & Panduan
                    </h5>
                </div>

                <div class="p-6">
                    <div class="mb-6">
                        <h6 class="text-lg font-medium flex items-center mb-2">
                            <i class="fas fa-info-circle text-primary mr-2"></i>
                            Pertanyaan Umum
                        </h6>
                        <p class="text-gray-600">Berikut adalah panduan penggunaan sistem BMKG</p>
                    </div>

                    <!-- FAQ dengan JavaScript Vanilla -->
                    <div class="space-y-4" id="faqContainer">
                        @foreach ($faqs as $index => $faq)
                            <div class="border border-gray-200 rounded-lg faq-item">
                                <button
                                    class="w-full p-4 text-left flex justify-between items-center hover:bg-gray-50 transition-colors faq-button"
                                    data-target="faq-{{ $index }}">
                                    <span class="font-medium text-gray-800">{{ $faq['question'] }}</span>
                                    <svg class="w-5 h-5 text-gray-500 transform transition-transform duration-200 faq-arrow"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div id="faq-{{ $index }}" class="hidden border-t border-gray-200 faq-content">
                                    <div class="p-4 bg-gray-50">
                                        <p class="text-gray-600 whitespace-pre-line">{{ $faq['answer'] }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                        <h6 class="font-medium flex items-center mb-2">
                            <i class="fas fa-life-ring text-blue-500 mr-2"></i>
                            Butuh Bantuan Lebih Lanjut?
                        </h6>
                        <p class="text-gray-700">Hubungi administrator sistem di kantor Anda untuk bantuan teknis.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const faqButtons = document.querySelectorAll('.faq-button');

            faqButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const content = document.getElementById(targetId);
                    const arrow = this.querySelector('.faq-arrow');

                    // Toggle content
                    content.classList.toggle('hidden');

                    // Rotate arrow
                    arrow.classList.toggle('rotate-180');

                    // Optional: Close other open items
                    // document.querySelectorAll('.faq-content').forEach(item => {
                    //     if (item.id !== targetId && !item.classList.contains('hidden')) {
                    //         item.classList.add('hidden');
                    //         item.previousElementSibling.querySelector('.faq-arrow').classList.remove('rotate-180');
                    //     }
                    // });
                });
            });
        });
    </script>
@endsection
