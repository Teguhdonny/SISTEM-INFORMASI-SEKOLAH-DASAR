<div x-data="schoolChatbot()" class="fixed bottom-5 right-5 z-50">
    <button type="button" @click="open = !open" class="flex h-14 w-14 items-center justify-center rounded-full bg-blue-700 text-white shadow-lg transition hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-200" :aria-expanded="open.toString()" aria-label="Buka chatbot sekolah">
        <i class="fas" :class="open ? 'fa-xmark text-xl' : 'fa-comments text-xl'"></i>
    </button>

    <section x-cloak x-show="open" x-transition.origin.bottom.right class="absolute bottom-16 right-0 flex h-[min(34rem,calc(100vh-7rem))] w-[min(22rem,calc(100vw-2rem))] flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-2xl" aria-label="Chatbot Sekolah">
        <header class="flex items-center gap-3 bg-blue-800 px-4 py-4 text-white">
            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-white/15"><i class="fas fa-robot"></i></div>
            <div>
                <h2 class="text-sm font-extrabold">Asisten Sekolah</h2>
                <p class="text-[11px] text-blue-100">Tanya informasi SDN Pojok 2</p>
            </div>
        </header>

        <div class="flex-1 space-y-3 overflow-y-auto bg-gray-50 p-3" x-ref="messages" aria-live="polite">
            <template x-for="(item, index) in messages" :key="index">
                <div class="flex" :class="item.role === 'user' ? 'justify-end' : 'justify-start'">
                    <p class="max-w-[88%] whitespace-pre-line rounded-2xl px-3 py-2 text-xs leading-relaxed" :class="item.role === 'user' ? 'rounded-br-md bg-blue-700 text-white' : 'rounded-bl-md border border-gray-200 bg-white text-gray-700'" x-text="item.text"></p>
                </div>
            </template>
            <div x-show="loading" class="flex justify-start">
                <p class="rounded-2xl rounded-bl-md border border-gray-200 bg-white px-3 py-2 text-xs text-gray-500">Sedang mencari jawaban...</p>
            </div>
        </div>

        <div class="border-t border-gray-100 bg-white p-3">
            <div class="mb-2 flex gap-2 overflow-x-auto pb-1">
                <template x-for="suggestion in suggestions" :key="suggestion">
                    <button type="button" @click="message = suggestion; send()" class="shrink-0 rounded-full border border-blue-100 bg-blue-50 px-3 py-1.5 text-[11px] font-semibold text-blue-700 hover:bg-blue-100" x-text="suggestion"></button>
                </template>
            </div>
            <form @submit.prevent="send" class="flex items-end gap-2">
                <label for="school-chatbot-message" class="sr-only">Pertanyaan</label>
                <textarea id="school-chatbot-message" x-model="message" @keydown.enter.exact.prevent="send" rows="1" maxlength="1000" placeholder="Tulis pertanyaan..." class="max-h-24 min-h-10 flex-1 resize-none rounded-xl border border-gray-200 px-3 py-2.5 text-xs outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100"></textarea>
                <button type="submit" :disabled="loading || !message.trim()" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-600 text-white transition hover:bg-red-700 disabled:cursor-not-allowed disabled:opacity-50" aria-label="Kirim pertanyaan">
                    <i class="fas fa-paper-plane text-xs"></i>
                </button>
            </form>
        </div>
    </section>
</div>

<script>
    function schoolChatbot() {
        return {
            open: false,
            loading: false,
            message: '',
            suggestions: ['Syarat registrasi daftar ulang?', 'Kapan jadwal daftar ulang?', 'Alamat sekolah?'],
            messages: [{ role: 'assistant', text: 'Halo! Saya Asisten Sekolah. Ada yang bisa saya bantu?' }],
            async send() {
                const question = this.message.trim();
                if (!question || this.loading) {
                    return;
                }

                const history = this.messages.slice(1).slice(-8).map((item) => ({
                    role: item.role,
                    content: item.text,
                }));
                this.messages.push({ role: 'user', text: question });
                this.message = '';
                this.loading = true;
                this.$nextTick(() => { this.$refs.messages.scrollTop = this.$refs.messages.scrollHeight; });

                try {
                    const response = await fetch('{{ route('chatbot.message') }}', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({ message: question, history }),
                    });
                    const data = await response.json();
                    if (!response.ok) {
                        throw new Error(data.message || 'Permintaan tidak dapat diproses.');
                    }

                    this.messages.push({ role: 'assistant', text: data.message });
                } catch (error) {
                    this.messages.push({ role: 'assistant', text: error.message || 'Terjadi kesalahan. Silakan coba lagi.' });
                } finally {
                    this.loading = false;
                    this.$nextTick(() => { this.$refs.messages.scrollTop = this.$refs.messages.scrollHeight; });
                }
            },
        };
    }
</script>
