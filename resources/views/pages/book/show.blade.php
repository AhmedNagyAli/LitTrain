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
                        {{-- Download --}}
                        @if($book->file)
                        <a href="{{ asset('storage/'.$book->file) }}" download
                           class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition flex items-center">
                            Download PDF
                        </a>
                        @endif

                        {{-- Read --}}
                        <button onclick="toggleReading()"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition">
                            Read Online
                        </button>

                        {{-- Voice --}}
                        <button class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg font-medium transition">
                            Publish Voice Record
                        </button>

                        {{-- Train --}}
                        <button id="trainBtn" onclick="startTraining({{ $book->id }}, '{{ $book->language->code }}')"
                                class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-2 rounded-lg font-medium transition">
                            Train Writing Skills
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Reading + Training Section --}}
        @if($book->file)
        <div id="readingSection" class="mt-8 bg-white rounded-lg shadow-lg p-6 hidden">
            <h2 id="sectionTitle" class="text-2xl font-bold mb-4">Reading: {{ $book->title }}</h2>

            <div id="readingContent">
                <iframe src="{{ route('book.view', $book->id) }}" class="w-full h-[800px] border-0"></iframe>
            </div>

            <div id="trainingContent" class="hidden">
                {{-- Options --}}
                <div class="flex gap-4 mb-4">
                    <label>Start Page:
                        <input id="startPage" type="number" value="1" min="1" class="border px-2 py-1 rounded w-20">
                    </label>
                    <label>Pages:
                        <input id="numPages" type="number" value="1" min="1" class="border px-2 py-1 rounded w-20">
                    </label>
                </div>

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

function toggleReading() {
    document.getElementById('readingSection').classList.toggle('hidden');
    document.getElementById('trainingContent').classList.add('hidden');
    document.getElementById('readingContent').classList.remove('hidden');
    document.getElementById('sectionTitle').innerText = "Reading: {{ $book->title }}";
}

function startTraining(bookId, langCode) {
    if (langCode !== 'en') {
        Swal.fire('Not Allowed', 'Training works only for English books.', 'warning');
        return;
    }
    if (!isAuth) {
        Swal.fire({
            title: 'Login Required',
            text: 'You must login first to train your writing skills.',
            icon: 'warning',
            confirmButtonText: 'Go to Login'
        }).then(() => window.location.href = "{{ route('login') }}");
        return;
    }

    const section = document.getElementById('readingSection');
    section.classList.remove('hidden');
    document.getElementById('readingContent').classList.add('hidden');
    document.getElementById('trainingContent').classList.remove('hidden');
    document.getElementById('sectionTitle').innerText = "Train Your Writing Skills";

    const startPage = document.getElementById('startPage').value;
    const numPages = document.getElementById('numPages').value;

    fetch(`${trainUrl}?start=${startPage}&pages=${numPages}`, { headers: { 'Accept': 'application/json' } })
        .then(res => res.json())
        .then(data => {
            if (!data.text) { Swal.fire('Error','No text returned','error'); return; }
            const words = data.text.trim().split(/\s+/);
            const trainingText = document.getElementById('trainingText');
            trainingText.innerHTML = words.map((w, i) =>
                `<span id="word-${i}" class="word ${i===0?'current':''}" data-word="${escapeHtml(w)}">${escapeHtml(w)}</span>`
            ).join(' ');

            let currentIndex=0, buffer='', startedAt=null, typedCorrect=0, totalWords=words.length;

            function normalize(s){return (s||'').replace(/[.,!?;:()"'“”«»\[\]{}—–…<>\/\\-]/g,'').toLowerCase();}

            function handler(e){
                if (e.ctrlKey||e.metaKey||e.altKey) return;
                if(!startedAt) startedAt=Date.now();
                if(e.key===' '){
                    e.preventDefault();
                    const el=document.getElementById('word-'+currentIndex);
                    if(!el) return;
                    if(normalize(buffer)===normalize(el.dataset.word)){ el.classList.add('correct'); typedCorrect++; }
                    else el.classList.add('wrong');
                    el.classList.remove('current');
                    buffer=''; currentIndex++;
                    if(currentIndex<totalWords) document.getElementById('word-'+currentIndex)?.classList.add('current');
                    else { document.removeEventListener('keydown',handler); document.getElementById('finishTraining').classList.remove('hidden'); }
                    updateStats();
                } else if(e.key==='Backspace'){ buffer=buffer.slice(0,-1); }
                else if(e.key.length===1){ buffer+=e.key; }
            }
            function updateStats(){
                const time=(Date.now()-startedAt)/1000;
                document.getElementById('typed-count').textContent=typedCorrect;
                document.getElementById('speed').textContent=(typedCorrect/Math.max(1,time)).toFixed(2);
                document.getElementById('accuracy').textContent=(currentIndex?typedCorrect/currentIndex*100:0).toFixed(1);
            }
            document.removeEventListener('keydown',handler); document.addEventListener('keydown',handler);

            document.getElementById('finishTraining').onclick=function(){
                const endedAt=Date.now(); const duration=Math.round((endedAt-startedAt)/1000);
                const accuracy=(typedCorrect/totalWords*100).toFixed(2);
                const rank=(typedCorrect/duration).toFixed(2);
                fetch(saveUrl,{
                    method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken},body:JSON.stringify({
                        duration,accuracy,rank,words_trained:totalWords,started_at:new Date(startedAt).toISOString(),ended_at:new Date(endedAt).toISOString()
                    })
                }).then(r=>r.json()).then(()=>{Swal.fire('Saved','Training session recorded!','success').then(()=>location.reload());});
            };
        });
}

function escapeHtml(text){return text.replace(/[&<>"'`=\/]/g,s=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;','/':'&#x2F;','`':'&#x60;','=':'&#x3D;'}[s]));}
</script>
@endpush
