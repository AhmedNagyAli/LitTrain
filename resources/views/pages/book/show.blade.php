@extends('layouts.app')

@section('title', $book->title . ' | LiTrain')
@section('meta_description', substr(strip_tags($book->description), 0, 160) . '...')

@push('styles')
<style>
    .rtl-content { direction: rtl; text-align: right; }
    .word { padding: 2px; white-space: pre-wrap; }
    .current { background-color: #fde68a; } /* yellow */
    .correct { background-color: #bbf7d0; } /* green */
    .wrong { background-color: #fecaca; }   /* red */
    /* small helper to make long text wrap nicely */
    #trainingText { word-break: break-word; }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        {{-- Breadcrumb --}}
        <nav class="mb-6 text-sm">
            <a href="{{ route('home.index') }}" class="text-blue-600 hover:text-blue-800">Home</a>
            <span class="mx-2">/</span>
            <span class="text-gray-500">{{ $book->title }}</span>
        </nav>

        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="md:flex">
                {{-- Book Cover --}}
                <div class="md:w-1/3 p-6">
                    <img src="{{ asset('storage/'.$book->cover) }}"
                        alt="{{ $book->title }}"
                        class="w-full rounded-lg shadow-md"
                        onerror="this.src='{{ asset('images/placeholder-book.jpg') }}'">
                </div>

                {{-- Book Details --}}
                <div class="md:w-2/3 p-6 @if($book->language === 'arabic') rtl-content @endif">
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $book->title }}</h1>
                    <p class="text-lg text-gray-600 mb-4">
                        by <span class="font-semibold">{{ $book->author->name ?? 'Unknown Author' }}</span>
                    </p>

                    <div class="flex flex-wrap items-center gap-2 mb-4">
                        @foreach($book->categories as $category)
                            <div class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                                {{ $category->category }}
                            </div>
                        @endforeach
                        <span class="text-gray-500">{{ $book->published_year ?? 'Unknown year' }}</span>
                    </div>

                    <div class="mb-6">
                        <h2 class="text-xl font-semibold mb-2">Description</h2>
                        <p class="text-gray-700 leading-relaxed">
                            {{ $book->description ?? 'No description available.' }}
                        </p>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex flex-wrap gap-4 @if($book->language === 'arabic') rtl-button @endif">
                        {{-- PDF Download Button --}}
                        @if($book->file)
                        <a href="{{ asset('storage/'.$book->file) }}" download
                           class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            {{ $book->language === 'arabic' ? 'تحميل PDF' : 'Download PDF' }}
                        </a>
                        @endif

                        {{-- Toggle Reading Button --}}
                        <button onclick="toggleReading()"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">
                            {{ $book->language === 'arabic' ? 'قراءة الكتاب' : 'Read Online' }}
                        </button>

                        {{-- Publish Voice Record Button --}}
                        <button class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg font-medium transition">
                            {{ $book->language === 'arabic' ? 'تسجيل صوتي' : 'Publish Voice Record' }}
                        </button>

                        {{-- Train Writing Skills Button --}}
                        <button id="trainBtn" onclick="startTraining({{ $book->id }})"
                            class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-2 rounded-lg font-medium transition">
                            {{ $book->language === 'arabic' ? 'تدريب الكتابة' : 'Train Writing Skills' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Reading Section --}}
        @if($book->file)
        <div id="readingSection" class="mt-8 bg-white rounded-lg shadow-lg p-6 hidden">
            <h2 id="sectionTitle" class="text-2xl font-bold mb-4">Reading: {{ $book->title }}</h2>
            <div id="readingContent">
                <iframe src="{{ route('book.view', $book->id) }}" class="w-full h-[800px] border-0"></iframe>
            </div>

            {{-- Training Section --}}
            <div id="trainingContent" class="hidden">
                <div id="trainingText" class="mb-6 text-lg leading-relaxed"></div>

                <div class="mt-4 space-y-1">
                    <p><strong>Words Typed:</strong> <span id="typed-count">0</span></p>
                    <p><strong>Speed:</strong> <span id="speed">0</span> words/sec</p>
                    <p><strong>Accuracy:</strong> <span id="accuracy">0</span>%</p>
                </div>

                <button id="finishTraining" class="mt-6 bg-green-600 text-white px-4 py-2 rounded-lg hidden">
                    Finish Training
                </button>
            </div>
        </div>
        @endif

        {{-- Related Books --}}
        @if($relatedBooks && $relatedBooks->count() > 0)
        <div class="mt-12">
            <h2 class="text-2xl font-bold mb-6">Related Books</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($relatedBooks as $relatedBook)
                    @component('components.book-card', ['book' => $relatedBook]) @endcomponent
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Login Modal --}}
<div id="loginModal" class="hidden fixed inset-0 flex items-center justify-center bg-black/50">
    <div class="bg-white rounded-lg p-6 shadow-lg w-96 text-center">
        <h3 class="text-xl font-bold mb-4">Login Required</h3>
        <p class="mb-4">You must login first to train your writing skills.</p>
        <a href="{{ route('login') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Go to Login</a>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Backend endpoints generated by Laravel route() — ensures correct URL
    const trainUrl = @json(route('books.train', $book->id));
    const saveUrl  = @json(route('books.training_sessions.store', $book->id));
    const csrfToken = @json(csrf_token());

    function toggleReading() {
        document.getElementById('readingSection').classList.toggle('hidden');
        document.getElementById('trainingContent').classList.add('hidden');
        document.getElementById('readingContent').classList.remove('hidden');
        document.getElementById('sectionTitle').innerText = "Reading: {{ $book->title }}";
    }

    function startTraining(bookId) {
        @if(!auth()->check())
            document.getElementById('loginModal').classList.remove('hidden');
            return;
        @endif

        const section = document.getElementById('readingSection');
        section.classList.remove('hidden');
        document.getElementById('readingContent').classList.add('hidden');
        document.getElementById('trainingContent').classList.remove('hidden');
        document.getElementById('sectionTitle').innerText = "Train Your Writing Skills";

        // Fetch first-page text from backend
        fetch(trainUrl, { headers: { 'Accept': 'application/json' } })
            .then(async res => {
                if (!res.ok) {
                    const txt = await res.text();
                    console.error('train fetch failed', res.status, txt);
                    alert('Failed to load book text from server (status: ' + res.status + '). Check console.');
                    return Promise.reject('fetch-error');
                }
                return res.json();
            })
            .then(data => {
                if (!data || !data.text) {
                    alert('Server returned empty text. Check backend parsing.');
                    console.error('empty text response', data);
                    return;
                }

                // Normalize and split words for display & comparison
                // Keep original display (so punctuation shows), but normalize for matching.
                const rawText = data.text.replace(/\s+/g, ' ').trim();
                // Split on spaces — you can adapt to split by sentences/pages
                const displayWords = rawText.split(' ');

                // build HTML spans
                const trainingText = document.getElementById('trainingText');
                trainingText.innerHTML = displayWords.map((w, i) =>
                    `<span id="word-${i}" class="word ${i === 0 ? 'current' : ''}" data-word="${escapeHtml(w)}">${escapeHtml(w)}</span>`
                ).join(' ');

                // helper to normalize words (remove punctuation & lower-case)
                function normalize(s) {
                    if (!s) return '';
                    // remove common punctuation (including Arabic punctuation)
                    return s.replace(/[.,!?؛،:;()"'“”«»\[\]{}—–…<>\/\\-—–…]/g, '')
                            .replace(/\s+/g, '')
                            .toLowerCase();
                }

                let currentIndex = 0;
                let buffer = '';
                let startedAt = null;
                let typedCorrect = 0;
                const totalWords = displayWords.length;

                // avoid multiple listeners if user clicks train multiple times
                const handler = (e) => {
                    // ignore modifier keys, arrows, etc.
                    if (e.ctrlKey || e.metaKey || e.altKey) return;

                    if (!startedAt) startedAt = Date.now();

                    if (e.key === ' ') {
                        e.preventDefault();
                        const displayed = document.getElementById('word-' + currentIndex);
                        if (!displayed) return;

                        const expectedNormalized = normalize(displayed.dataset.word);
                        const typedNormalized = normalize(buffer);

                        displayed.classList.remove('current');
                        if (typedNormalized !== '' && typedNormalized === expectedNormalized) {
                            displayed.classList.add('correct');
                            typedCorrect++;
                        } else {
                            displayed.classList.add('wrong');
                        }

                        // advance
                        currentIndex++;
                        buffer = '';

                        if (currentIndex < totalWords) {
                            const next = document.getElementById('word-' + currentIndex);
                            if (next) next.classList.add('current');
                        } else {
                            // finished
                            document.removeEventListener('keydown', handler);
                            document.getElementById('finishTraining').classList.remove('hidden');
                        }

                        updateStats();
                    } else if (e.key === 'Backspace') {
                        // remove last char from buffer
                        buffer = buffer.slice(0, -1);
                    } else if (e.key.length === 1) {
                        // printable character
                        buffer += e.key;
                    }
                };

                // update UI stats
                function updateStats() {
                    const timeElapsed = startedAt ? (Date.now() - startedAt) / 1000 : 1;
                    document.getElementById('typed-count').textContent = typedCorrect;
                    document.getElementById('speed').textContent = (typedCorrect / Math.max(1, timeElapsed)).toFixed(2);
                    const accuracy = currentIndex > 0 ? (typedCorrect / currentIndex) * 100 : 0;
                    document.getElementById('accuracy').textContent = accuracy.toFixed(1);
                }

                // attach once
                document.removeEventListener('keydown', handler);
                document.addEventListener('keydown', handler);

                // Finish button - save session
                document.getElementById('finishTraining').onclick = function() {
                    const endedAt = Date.now();
                    const duration = Math.max(1, Math.round((endedAt - startedAt) / 1000));
                    const accuracy = totalWords > 0 ? (typedCorrect / totalWords * 100) : 0;
                    const rank = (typedCorrect / duration).toFixed(2);

                    fetch(saveUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            duration,
                            accuracy,
                            rank,
                            words_trained: totalWords,
                            started_at: new Date(startedAt).toISOString(),
                            ended_at: new Date(endedAt).toISOString()
                        })
                    })
                    .then(async r => {
                        if (!r.ok) {
                            const txt = await r.text();
                            console.error('save failed', r.status, txt);
                            alert('Failed to save session. Check console.');
                            return Promise.reject('save-error');
                        }
                        return r.json();
                    })
                    .then(resp => {
                        alert('Training session saved! Rank: ' + rank + ' WPS');
                        location.reload();
                    })
                    .catch(err => {
                        console.error(err);
                    });
                };
            })
            .catch(err => {
                if (err !== 'fetch-error') console.error('Unexpected error', err);
            });
    }

    // small helper for HTML escaping to avoid XSS if text contains < or >
    function escapeHtml(text) {
        if (!text) return '';
        return text.replace(/[&<>"'`=\/]/g, function (s) {
          return ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;',
            '/': '&#x2F;',
            '`': '&#x60;',
            '=': '&#x3D;'
          })[s];
        });
    }
</script>
@endpush
