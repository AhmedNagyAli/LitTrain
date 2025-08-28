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
                           class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition flex items-center">
                            Download PDF
                        </a>
                        @endif

                        <button onclick="toggleReading()"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">
                            Read Online
                        </button>

                        <button id="trainBtn" onclick="startTraining({{ $book->id }}, '{{ $book->language->code }}')"
                                class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-2 rounded-lg font-medium transition">
                            Train Writing Skills
                        </button>
                    </div>
                </div>
            </div>
        </div>

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

                {{-- Always visible end session --}}
                <button id="finishTraining" class="mt-6 bg-green-600 text-white px-4 py-2 rounded-lg">
                    End Training
                </button>

                {{-- Load More --}}
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const trainUrl = @json(route('books.train', $book->id));
const saveUrl  = @json(route('books.training_sessions.store', $book->id));
const csrfToken = @json(csrf_token());
const isAuth = @json(auth()->check());

let currentPage = 1;
let words = [];
let currentIndex = 0;
let buffer = '';
let startedAt = null;
let typedCorrect = 0;

function toggleReading() {
    document.getElementById('readingSection').classList.toggle('hidden');
}

function startTraining(bookId, langCode) {
    if (langCode !== 'en') {
        Swal.fire('Not Allowed', 'Training works only for English books.', 'warning');
        return;
    }
    if (!isAuth) {
        Swal.fire({ title: 'Login Required', text: 'You must login first.', icon: 'warning' })
            .then(() => window.location.href = "{{ route('login') }}");
        return;
    }

    document.getElementById('readingSection').classList.remove('hidden');
    loadPages(5); // load first 5 pages
}

function loadPages(count) {
    fetch(`${trainUrl}?start=${currentPage}&pages=${count}`, { headers: { 'Accept': 'application/json' } })
        .then(res => res.json())
        .then(data => {
            if (!data.text) return;

            const container = document.getElementById('trainingPages');
            const pageBlock = document.createElement('div');
            pageBlock.className = "page-block";

            // split into words and wrap spans
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

            currentPage += count;
            document.getElementById('loadMore').classList.remove('hidden');
        });
}


function startFromHere(index) {
    currentIndex = index;
    buffer = '';
    startedAt = Date.now(); // start immediately
    typedCorrect = 0;

    // reset stats visually
    document.getElementById('typed-count').textContent = 0;
    document.getElementById('speed').textContent = 0;
    document.getElementById('accuracy').textContent = 0;

    // clear old highlights
    document.querySelectorAll('.word').forEach(w => w.classList.remove('current','correct','wrong'));

    highlightCurrent();

    // enable typing handler
    document.removeEventListener('keydown', handler); // avoid duplicate binding
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

document.getElementById('finishTraining').onclick=function(){
    Swal.fire('Session Ended','Your training session has ended.','success');
};

document.getElementById('loadMore').onclick=function(){
    loadPages(5);
};

function normalize(s){return (s||'').replace(/[.,!?;:()"'“”«»\[\]{}—–…<>\/\\-]/g,'').toLowerCase();}
</script>
@endpush
