@extends('layouts.app')

@section('title', $book->title . ' | LiTrain')
@section('meta_description', substr(strip_tags($book->description), 0, 160) . '...')

@push('styles')
<style>
    .rtl-content { direction: rtl; text-align: right; }
    .word { padding: 2px; white-space: pre-wrap; }
    .current { background-color: #fde68a; }
    .correct { background-color: #bbf7d0; }
    .wrong { background-color: #fecaca; }
    #trainingText { word-break: break-word; }
    .page-block { border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 1rem; margin-bottom: 1.5rem; }

    /* 📖 PDF Viewer */
    #pdf-viewer {
        width: 100%;
        height: auto;
        max-height: 85vh;
        overflow-y: auto;
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 10px;
    }
    canvas {
        display: block;
        margin: 0 auto 20px auto;
        border: 1px solid #ccc;
        border-radius: 6px;
    }
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
                <div class="md:w-2/3 p-6 @if($book->language->code === 'ar') rtl-content @endif">
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $book->title }}</h1>
                    @if($book->records->count())
    <span id="audibleMark" class="bg-orange-800 text-white px-2 py-1 rounded font-semibold text-sm">
        Audible
    </span>
@endif

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

                    {{-- Description --}}
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold mb-2">Description</h2>
                        <p class="text-gray-700 leading-relaxed">
                            {{ $book->description ?? 'No description available.' }}
                        </p>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex flex-wrap gap-4">
                        @if($book->file)
                        <a href="{{ asset('storage/'.$book->file) }}" download
                           class="bg-indigo-900 hover:bg-indigo-600 text-white px-6 py-2 rounded-lg font-medium transition flex items-center">
                            Download PDF
                        </a>
                        @endif

                        <button onclick="openPdf()"
                                class="bg-violet-700 hover:bg-violet-600 text-white px-6 py-2 rounded-lg font-medium transition">
                            Read Online
                        </button>

                        <button id="trainBtn" onclick="startTraining({{ $book->id }}, '{{ $book->language->code }}')"
                                class="bg-purple-700 hover:bg-purple-600 text-white px-6 py-2 rounded-lg font-medium transition">
                            Train Writing Skills
                        </button>
                    </div>
                    {{-- 🎧 Records List + Player --}}
                    @if($book->records->count())
                        <div class="mt-8">
                            <h2 class="text-lg font-semibold mb-2">Available Audio Records</h2>
                            <ul class="space-y-2">
                                @foreach($book->records as $record)
                                    <li>
                                        <button
                                            class="w-full text-left px-4 py-2 rounded bg-gray-100 hover:bg-orange-100 transition"
                                            onclick="playRecord('{{ asset('storage/'.$record->record_file) }}', '{{ $record->user->name ?? 'Unknown User' }}')">
                                            🎤 {{ $record->user->name ?? 'Unknown User' }}
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div id="audioPlayerWrapper" class="fixed bottom-0 left-0 w-full bg-white dark:bg-slate-800 shadow-lg border-t border-slate-200 dark:border-slate-600 z-50">
                            <div class="max-w-7xl mx-auto px-4 py-3 flex items-center space-x-4">
                                {{-- Book Cover --}}
                                <img src="{{ asset('storage/'.$book->cover) }}" alt="{{ $book->title }}" class="w-16 h-16 rounded-lg object-cover" onerror="this.src='{{ asset('images/placeholder-book.jpg') }}'">

                                {{-- Book Info --}}
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-orange-500 font-semibold truncate">Audible</p>
                                    <h3 id="recordTitle" class="text-md font-semibold text-slate-800 dark:text-slate-50 truncate">{{ $book->title }}</h3>
                                    <p id="recordUser" class="text-sm text-slate-500 dark:text-slate-400 truncate"></p>
                                </div>

                                {{-- Audio Controls --}}
                                <audio id="bookAudio" class="w-1/2" controls preload="metadata">
                                    <source id="audioSource" src="" type="audio/mpeg">
                                    Your browser does not support the audio element.
                                </audio>
                            </div>
                        </div>
                    @endif

                    {{-- 🎤 Record Button --}}
<div class="mt-6">
    @auth
        <a href="{{ route('books.records.create', $book->id) }}"
           class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-2 rounded-lg font-medium transition">
            🎤 Record Audio for this Book
        </a>
    @else
        <a href="{{ route('login') }}"
           class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-2 rounded-lg font-medium transition">
            🎤 Record Audio for this Book
        </a>
    @endauth
</div>

                </div>
            </div>
        </div>
        {{-- 🔹 Suggested Books --}}
@if($relatedBooks->count())
<div class="mt-12">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">You may also like</h2>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($relatedBooks as $related)
            <div class="bg-white rounded-lg shadow hover:shadow-lg transition overflow-hidden">
                <a href="{{ route('books.show', $related->id) }}">
                    <img src="{{ asset('storage/'.$related->cover) }}"
                         alt="{{ $related->title }}"
                         class="w-full h-48 object-cover"
                         onerror="this.src='{{ asset('images/placeholder-book.jpg') }}'">
                </a>
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-800 truncate">
                        <a href="{{ route('books.show', $related->id) }}" class="hover:text-indigo-600">
                            {{ $related->title }}
                        </a>
                    </h3>
                    <p class="text-sm text-gray-500">
                        {{ $related->author->name ?? 'Unknown Author' }}
                    </p>
                    <div class="flex flex-wrap gap-1 mt-2">
                        @foreach($related->categories->take(2) as $cat)
                            <span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-1 rounded-full">
                                {{ $cat->category }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif


        {{-- 📖 PDF Viewer Section --}}
        @if($book->file)
        <div id="pdfSection" class="mt-8 bg-white rounded-lg shadow-lg p-6 hidden">
            <h2 class="text-2xl font-bold mb-4">{{ $book->title ?? 'Read online' }}</h2>
            <div id="pdf-viewer"></div>
            <div class="text-center mt-4">
                <button id="load-more"
                        class="px-4 py-2 bg-gray-700 text-white rounded-lg shadow hover:bg-gray-900">
                    Load More Pages
                </button>
            </div>
        </div>
        @endif

        {{-- Training Section --}}
        @if($book->file)
        <div id="readingSection" class="mt-8 bg-white rounded-lg shadow-lg p-6 hidden">
            <h2 id="sectionTitle" class="text-2xl font-bold mb-4">Train Writing Skills</h2>

            <div id="trainingContent">
                <div id="trainingPages"></div>

                {{-- Stats --}}
                <div class="mt-4 space-y-1">
                    <p><strong>Words Typed:</strong> <span id="typed-count">0</span></p>
                    <p><strong>Speed:</strong> <span id="speed">0</span> words/sec</p>
                    <p><strong>Accuracy:</strong> <span id="accuracy">0</span>%</p>
                </div>

                <button id="finishTraining" class="mt-6 bg-green-600 text-white px-4 py-2 rounded-lg">
                    End Training
                </button>

                <button id="loadMore" class="mt-6 bg-gray-600 text-white px-4 py-2 rounded-lg hidden">
                    Load More Pages
                </button>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
{{-- ✅ PDF.js --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if($book->records->count())
 <script>
        const audio = document.getElementById('bookAudio');
        const toggleBtn = document.getElementById('toggleAudio');
        const playIcon = document.getElementById('playIcon');

        toggleBtn.addEventListener('click', () => {
            if(audio.paused){
                audio.play();
                playIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>`; // Pause icon
            } else {
                audio.pause();
                playIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-6.518-3.76A1 1 0 007 8.293v7.414a1 1 0 001.234.97l6.518-1.887a1 1 0 000-1.878z"/>`; // Play icon
            }
        });
        function playRecord(src, userName) {
    const audio = document.getElementById('bookAudio');
    const source = document.getElementById('audioSource');
    source.src = src;
    audio.load();
    audio.play();

    document.getElementById('recordUser').textContent = "Narrated by: " + userName;
}
    </script>

@endif
<script>
const trainUrl = @json(route('books.train', $book->id));
const saveUrl  = @json(route('books.training_sessions.store', $book->id));
const csrfToken = @json(csrf_token());
const isAuth = @json(auth()->check());

/* ------------------- 📖 PDF.js Viewer ------------------- */
let pdfDoc = null;
let currentPage = 0;
const scale = 1.2;

function openPdf() {
    document.getElementById("pdfSection").classList.toggle("hidden");
    if (!pdfDoc) {
        const url = "{{ asset('storage/'.$book->file) }}";
        pdfjsLib.getDocument(url).promise.then(function(pdfDoc_) {
            pdfDoc = pdfDoc_;
            loadNextPages(3);
        });
    }
}

async function loadNextPages(n) {
    const pdfViewer = document.getElementById("pdf-viewer");
    for (let i = 0; i < n; i++) {
        if (currentPage >= pdfDoc.numPages) {
            document.getElementById("load-more").disabled = true;
            document.getElementById("load-more").innerText = "No more pages";
            break;
        }
        currentPage++;
        const page = await pdfDoc.getPage(currentPage);
        const viewport = page.getViewport({ scale });
        const canvas = document.createElement("canvas");
        const context = canvas.getContext("2d");
        canvas.height = viewport.height;
        canvas.width = viewport.width;
        await page.render({ canvasContext: context, viewport: viewport }).promise;
        pdfViewer.appendChild(canvas);
    }
}
document.getElementById("load-more")?.addEventListener("click", () => loadNextPages(3));

/* ------------------- ✍️ Training ------------------- */
let currentPageTrain = 1;
let words = [];
let currentIndex = 0;
let buffer = '';
let startedAt = null;
let typedCorrect = 0;

function startTraining(bookId, langCode) {
    if (langCode !== 'en') {
        Swal.fire('Not Allowed', 'Training works only for English books for now.', 'warning');
        return;
    }
    if (!isAuth) {
        Swal.fire({ title: 'Login Required', text: 'You must login first.', icon: 'warning' })
            .then(() => window.location.href = "{{ route('login') }}");
        return;
    }

    document.getElementById('readingSection').classList.remove('hidden');
    loadPages(5);
}

function loadPages(count) {
    fetch(`${trainUrl}?start=${currentPageTrain}&pages=${count}`, { headers: { 'Accept': 'application/json' } })
        .then(res => res.json())
        .then(data => {
            if (!data.text) return;
            const container = document.getElementById('trainingPages');
            const pageBlock = document.createElement('div');
            pageBlock.className = "page-block";
            const splitWords = data.text.trim().split(/\s+/).map(w => `<span class="word">${w}</span>`).join(' ');
            pageBlock.innerHTML = `
                <div class="mb-4">
                    <button class="bg-blue-500 hover:bg-blue-600 text-white rounded px-3 py-1 text-sm"
                            onclick="startFromHere(${words.length})">
                        Start From This Page
                    </button>
                </div>
                <div>${splitWords}</div>
            `;
            container.appendChild(pageBlock);
            const newWords = data.text.trim().split(/\s+/);
            words = words.concat(newWords);
            currentPageTrain += count;
            document.getElementById('loadMore').classList.remove('hidden');
        });
}

function startFromHere(index) {
    currentIndex = index;
    buffer = '';
    startedAt = Date.now();
    typedCorrect = 0;
    document.getElementById('typed-count').textContent = 0;
    document.getElementById('speed').textContent = 0;
    document.getElementById('accuracy').textContent = 0;
    document.querySelectorAll('.word').forEach(w => w.classList.remove('current','correct','wrong'));
    highlightCurrent();
    document.removeEventListener('keydown', handler);
    document.addEventListener('keydown', handler);
    Swal.fire('Training Started', 'Begin typing to practice from this page.', 'success');
}

function handler(e) {
    if (e.ctrlKey||e.metaKey||e.altKey) return;
    if(!startedAt) startedAt=Date.now();
    if(e.key===' '){
        e.preventDefault();
        const expected = words[currentIndex] || '';
        const currentWordEl = document.querySelectorAll('.word')[currentIndex];
        if(normalize(buffer)===normalize(expected)){
            typedCorrect++;
            currentWordEl.classList.add('correct');
        } else {
            currentWordEl.classList.add('wrong');
        }
        currentWordEl.classList.remove('current');
        currentIndex++;
        buffer='';
        highlightCurrent();
        updateStats();
    } else if(e.key==='Backspace'){ buffer=buffer.slice(0,-1); }
    else if(e.key.length===1){ buffer+=e.key; }
}

function highlightCurrent(){
    const wordEls = document.querySelectorAll('.word');
    wordEls.forEach(w => w.classList.remove('current'));
    if(wordEls[currentIndex]) wordEls[currentIndex].classList.add('current');
}

function updateStats(){
    const time=(Date.now()-startedAt)/1000;
    document.getElementById('typed-count').textContent=typedCorrect;
    document.getElementById('speed').textContent=(typedCorrect/Math.max(1,time)).toFixed(2);
    document.getElementById('accuracy').textContent=(currentIndex?typedCorrect/currentIndex*100:0).toFixed(1);
}

/* ------------------- ✅ End Training -> Save Session ------------------- */
document.getElementById('finishTraining').onclick=function(){
    const endedAt = Date.now();
    const duration = Math.floor((endedAt - startedAt)/1000);
    const accuracy = currentIndex ? (typedCorrect/currentIndex*100).toFixed(1) : 0;
    const rank = accuracy >= 90 ? 'Expert' : accuracy >= 70 ? 'Intermediate' : 'Beginner';

    Swal.fire('Session Ended','Your training session has ended.','success');

    if(!isAuth) return;

    fetch(saveUrl, {
        method: "POST",
        headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
        body: JSON.stringify({
            duration: duration,
            accuracy: accuracy,
            rank: rank,
            words_trained: currentIndex,
            started_at: new Date(startedAt).toISOString(),
            ended_at: new Date(endedAt).toISOString()
        })
    })
    .then(response => response.json()) // ✅ This now works because we return JSON
.then(data => {
    if (data.success) {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: data.message,
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    } else {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: "❌ Failed: " + data.message,
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    }
})
.catch(error => {
    console.error("❌ Error saving session:", error);
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'error',
        title: "Error saving session",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
});
};

document.getElementById('loadMore').onclick=function(){ loadPages(5); };

function normalize(s){return (s||'').replace(/[.,!?;:()"'“”«»\[\]{}—–…<>\/\\-]/g,'').toLowerCase();}
</script>
@endpush
