@props(['variant' => 'dark', 'placeholder' => 'Cari produk AI...'])

@php
    $isDark = $variant === 'dark';
    $inputClass = $isDark
        ? 'w-full min-w-0 text-sm md:text-base border border-white/10 bg-surface text-white placeholder-gray-500 rounded-l-full pl-4 pr-2 md:px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary/50'
        : 'w-full min-w-0 border border-gray-200 rounded-l-full px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/40';
    $btnClass = $isDark
        ? 'bg-primary hover:bg-primary-dark text-dark font-semibold px-3 md:px-5 rounded-r-full shrink-0'
        : 'bg-primary text-white px-4 rounded-r-full';
@endphp

<form action="{{ route('products.index') }}" method="GET" autocomplete="off" data-search-box
    data-suggest-url="{{ route('search.suggest') }}" {{ $attributes->merge(['class' => 'relative']) }}>
    <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ $placeholder }}"
        class="{{ $inputClass }}" role="combobox" aria-autocomplete="list" aria-expanded="false">
    <button class="{{ $btnClass }}" aria-label="Cari">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
            stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
        </svg>
    </button>
    <div data-dropdown
        class="hidden fixed left-3 right-3 top-[68px] md:absolute md:left-0 md:right-0 md:top-full md:mt-2 bg-white text-gray-800 rounded-2xl shadow-2xl border border-gray-100 overflow-hidden z-50 max-h-[70vh] overflow-y-auto text-sm">
    </div>
</form>

@once
    @push('scripts')
        @verbatim
            <script>
                (function() {
                    const KEY = 'satuai_recent_searches';
                    const esc = s => String(s ?? '').replace(/[&<>"']/g, c => ({
                        '&': '&amp;',
                        '<': '&lt;',
                        '>': '&gt;',
                        '"': '&quot;',
                        "'": '&#39;'
                    } [c]));
                    const reEsc = s => s.replace(/[.*+?^$()|[\]\\]/g, '\\$&').replace(/[{}]/g, '\\$&');
                    const hl = (text, q) => {
                        const t = esc(text);
                        if (!q) return t;
                        try {
                            return t.replace(new RegExp('(' + reEsc(esc(q)) + ')', 'ig'),
                                '<mark class="bg-primary/25 text-inherit rounded px-0.5">$1</mark>');
                        } catch (e) {
                            return t;
                        }
                    };
                    const recents = {
                        get() {
                            try {
                                return JSON.parse(localStorage.getItem(KEY)) || [];
                            } catch (e) {
                                return [];
                            }
                        },
                        add(v) {
                            v = (v || '').trim();
                            if (v.length < 2) return;
                            const list = [v, ...this.get().filter(x => x.toLowerCase() !== v.toLowerCase())].slice(0, 5);
                            try {
                                localStorage.setItem(KEY, JSON.stringify(list));
                            } catch (e) {}
                        },
                        clear() {
                            try {
                                localStorage.removeItem(KEY);
                            } catch (e) {}
                        },
                    };

                    const title = t =>
                        `<p class="px-4 pt-3 pb-1 text-[11px] font-semibold uppercase tracking-wide text-gray-400">${t}</p>`;
                    const searchIcon =
                        '<svg class="h-4 w-4 text-gray-300 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/></svg>';

                    function init(form) {
                        const input = form.querySelector('input[name=q]');
                        const box = form.querySelector('[data-dropdown]');
                        const suggestUrl = form.dataset.suggestUrl;
                        const base = form.getAttribute('action');
                        const cache = {};
                        let timer, ctrl, active = -1;

                        const link = q => base + '?q=' + encodeURIComponent(q);
                        const items = () => [...box.querySelectorAll('[data-item]')];
                        const open = () => {
                            box.classList.remove('hidden');
                            input.setAttribute('aria-expanded', 'true');
                        };
                        const close = () => {
                            box.classList.add('hidden');
                            input.setAttribute('aria-expanded', 'false');
                            active = -1;
                        };
                        const setActive = i => {
                            const it = items();
                            it.forEach(e => e.classList.remove('bg-gray-100'));
                            if (!it.length) {
                                active = -1;
                                return;
                            }
                            active = (i + it.length) % it.length;
                            it[active].classList.add('bg-gray-100');
                            it[active].scrollIntoView({
                                block: 'nearest'
                            });
                        };

                        const chips = (list) => '<div class="flex flex-wrap gap-2 px-4 pb-3 pt-1">' + list.map(k =>
                            `<a data-item data-save="${esc(k)}" href="${link(k)}" class="px-3 py-1.5 rounded-full bg-gray-100 hover:bg-primary/15 hover:text-primary text-xs transition">${esc(k)}</a>`
                        ).join('') + '</div>';

                        const catRows = (cats) => cats.map(c =>
                            `<a data-item href="${esc(c.url)}" class="flex items-center gap-3 px-4 py-2 hover:bg-gray-50"><span class="text-gray-300">▤</span><span class="flex-1">${esc(c.name)}</span><span class="text-xs text-gray-400">Kategori</span></a>`
                        ).join('');

                        function render(d, q) {
                            let h = '';
                            if (!q) {
                                const r = recents.get();
                                if (r.length) {
                                    h += `<div class="flex items-center justify-between pr-4">${title('Terakhir dicari')}<button type="button" data-clear class="text-[11px] text-gray-400 hover:text-red-500 pt-3">Hapus</button></div>` + chips(r);
                                }
                                if (d.popular?.length) h += title('Pencarian populer') + chips(d.popular);
                                if (d.categories?.length) h += title('Jelajahi kategori') + catRows(d.categories);
                                box.innerHTML = h + '<div class="pb-2"></div>';
                                return;
                            }
                            if (d.keywords?.length) {
                                h += title('Rekomendasi kata kunci') + d.keywords.map(k =>
                                    `<a data-item data-save="${esc(k)}" href="${link(k)}" class="flex items-center gap-3 px-4 py-2 hover:bg-gray-50">${searchIcon}<span>${hl(k, q)}</span></a>`
                                ).join('');
                            }
                            if (d.products?.length) {
                                h += title('Produk') + d.products.map(p =>
                                    `<a data-item data-save="${esc(q)}" href="${esc(p.url)}" class="flex items-center gap-3 px-4 py-2 hover:bg-gray-50"><img src="${esc(p.image)}" alt="" class="h-9 w-9 rounded-lg bg-gray-100 object-cover shrink-0"><span class="flex-1 min-w-0"><span class="block truncate font-medium">${hl(p.name, q)}</span><span class="block text-xs text-gray-400 truncate">${esc(p.category)}</span></span><span class="text-xs font-semibold text-primary whitespace-nowrap">${esc(p.price)}</span></a>`
                                ).join('');
                            }
                            if (d.categories?.length) h += title('Kategori') + catRows(d.categories);
                            if (!d.keywords?.length && !d.products?.length && !d.categories?.length) {
                                h += `<p class="px-4 pt-4 pb-1 text-gray-500">Tidak ada hasil untuk “${esc(q)}”.</p><p class="px-4 pb-1 text-xs text-gray-400">Coba kata kunci ini:</p>` + chips(d.popular || []);
                            } else {
                                h += `<a data-item data-save="${esc(q)}" href="${link(q)}" class="block px-4 py-3 border-t border-gray-100 text-primary font-medium hover:bg-gray-50">Lihat semua hasil untuk “${esc(q)}” →</a>`;
                            }
                            box.innerHTML = h;
                        }

                        async function load() {
                            let q = input.value.trim();
                            if (q.length < 2) q = '';
                            active = -1;
                            try {
                                if (!cache[q]) {
                                    if (ctrl) ctrl.abort();
                                    ctrl = new AbortController();
                                    const res = await fetch(suggestUrl + '?q=' + encodeURIComponent(q), {
                                        headers: {
                                            'Accept': 'application/json'
                                        },
                                        signal: ctrl.signal
                                    });
                                    if (!res.ok) return;
                                    cache[q] = await res.json();
                                }
                                if (q !== (input.value.trim().length < 2 ? '' : input.value.trim())) return;
                                render(cache[q], q);
                                open();
                            } catch (e) {}
                        }

                        input.addEventListener('input', () => {
                            clearTimeout(timer);
                            timer = setTimeout(load, 180);
                        });
                        input.addEventListener('focus', load);
                        input.addEventListener('keydown', e => {
                            if (e.key === 'ArrowDown') {
                                e.preventDefault();
                                box.classList.contains('hidden') ? load() : setActive(active + 1);
                            } else if (e.key === 'ArrowUp') {
                                e.preventDefault();
                                setActive(active - 1);
                            } else if (e.key === 'Escape') {
                                close();
                            } else if (e.key === 'Enter' && active >= 0) {
                                e.preventDefault();
                                items()[active].click();
                            }
                        });
                        box.addEventListener('mousedown', e => e.preventDefault());
                        box.addEventListener('click', e => {
                            if (e.target.closest('[data-clear]')) {
                                recents.clear();
                                load();
                                return;
                            }
                            const s = e.target.closest('[data-save]');
                            if (s) recents.add(s.dataset.save);
                        });
                        form.addEventListener('submit', () => recents.add(input.value));
                        document.addEventListener('click', e => {
                            if (!form.contains(e.target)) close();
                        });
                    }

                    document.addEventListener('DOMContentLoaded', () => {
                        document.querySelectorAll('[data-search-box]').forEach(init);
                    });
                })();
            </script>
        @endverbatim
    @endpush
@endonce