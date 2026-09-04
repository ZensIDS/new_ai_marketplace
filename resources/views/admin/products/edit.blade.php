@extends('layouts.admin')
@section('title', 'Edit Produk')

@section('content')
<div class="card card-stat p-4" style="max-width:750px">
    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="category_id" class="form-select" required>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Nama Produk</label>
            <input type="text" name="name" value="{{ old('name', $product->name) }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Tags</label>

            <div class="tag-picker" id="product-tag-picker">
                <div class="tag-chips" id="tag-chips"></div>

                <input
                    type="text"
                    id="tag-input"
                    class="form-control border-0 shadow-none"
                    placeholder="Ketik untuk mencari tag..."
                    autocomplete="off"
                >

                <input
                    type="hidden"
                    name="tags_input"
                    id="tags-input-hidden"
                    value="{{ old('tags_input', $product->tags->pluck('name')->implode(', ')) }}"
                >

                <div class="tag-suggestions" id="tag-suggestions"></div>
            </div>

            <small class="text-muted">
                Bisa memilih banyak tag. Ketik nama produk/deskripsi untuk mendapatkan rekomendasi.
            </small>
        </div>

        <style>
            .tag-picker {
                position: relative;
                border: 1px solid #dee2e6;
                border-radius: .375rem;
                background: #fff;
                padding: .35rem .5rem;
            }

            .tag-picker:focus-within {
                border-color: #86b7fe;
                box-shadow: 0 0 0 .25rem rgba(13, 110, 253, .15);
            }

            .tag-chips {
                display: flex;
                flex-wrap: wrap;
                gap: .4rem;
            }

            .tag-chip {
                display: inline-flex;
                align-items: center;
                gap: .35rem;
                background: #f3f4f6;
                border: 1px solid #dee2e6;
                border-radius: 999px;
                padding: .3rem .55rem;
                font-size: .875rem;
            }

            .tag-chip button {
                border: 0;
                background: transparent;
                padding: 0;
                line-height: 1;
                color: #6c757d;
                cursor: pointer;
            }

            #tag-input {
                width: 100%;
                min-height: 38px;
                padding-left: 0;
            }

            .tag-suggestions {
                display: none;
                position: absolute;
                z-index: 1050;
                left: 0;
                right: 0;
                top: 100%;
                margin-top: 4px;
                background: #fff;
                border: 1px solid #dee2e6;
                border-radius: .375rem;
                box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .12);
                max-height: 280px;
                overflow-y: auto;
            }

            .tag-suggestion {
                display: flex;
                justify-content: space-between;
                align-items: center;
                width: 100%;
                border: 0;
                background: #fff;
                padding: .65rem .75rem;
                text-align: left;
                cursor: pointer;
            }

            .tag-suggestion:hover {
                background: #f8f9fa;
            }

            .tag-suggestion small {
                color: #6c757d;
                margin-left: 1rem;
            }

            .tag-reason {
                font-size: .72rem;
            }
        </style><div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="description" rows="3" class="form-control">{{ old('description', $product->description) }}</textarea>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Harga Dasar (Rp)</label>
                <input type="number" name="price" value="{{ old('price', $product->price) }}" class="form-control" required min="0">
                <small class="text-muted">Dipakai jika produk tidak punya varian.</small>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Stok Dasar</label>
                <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" class="form-control" required min="0">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Gambar Produk</label>
            @if($product->image)
                <img src="{{ asset('storage/'.$product->image) }}" width="70" class="d-block mb-2 rounded">
            @endif
            <input type="file" name="image" class="form-control">
        </div>
        <div class="form-check mb-4">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ $product->is_active ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Tampilkan di frontend (aktif)</label>
        </div>

        <!-- VARIAN PRODUK -->
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <label class="form-label mb-0 fw-bold">Varian Produk (opsional)</label>
                <button type="button" class="btn btn-sm btn-outline-dark" onclick="addVariantRow()">
                    <i class="bi bi-plus-lg"></i> Tambah Varian
                </button>
            </div>
            <small class="text-muted d-block mb-2">Menyimpan ulang seluruh varian setiap kali disimpan.</small>
            <div id="variant-rows">
                @foreach($product->variants as $variant)
                    <div class="row g-2 align-items-center mb-2 variant-row">
                        <div class="col-md-5">
                            <input type="text" name="variants[{{ $loop->index }}][name]" value="{{ $variant->name }}" class="form-control form-control-sm" placeholder="Nama varian">
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="variants[{{ $loop->index }}][price]" value="{{ $variant->price }}" class="form-control form-control-sm" placeholder="Harga" min="0">
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="variants[{{ $loop->index }}][stock]" value="{{ $variant->stock }}" class="form-control form-control-sm" placeholder="Stok" min="0">
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.variant-row').remove()"><i class="bi bi-trash"></i></button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <button class="btn text-white" style="background:#C9A227;color:#0B0B0C">Update</button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-light">Batal</a>
    </form>
</div>

<template id="variant-row-template">
    <div class="row g-2 align-items-center mb-2 variant-row">
        <div class="col-md-5">
            <input type="text" name="variants[__i__][name]" class="form-control form-control-sm" placeholder="Nama varian (mis. 1 Bulan)">
        </div>
        <div class="col-md-3">
            <input type="number" name="variants[__i__][price]" class="form-control form-control-sm" placeholder="Harga" min="0">
        </div>
        <div class="col-md-3">
            <input type="number" name="variants[__i__][stock]" class="form-control form-control-sm" placeholder="Stok" min="0">
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.variant-row').remove()"><i class="bi bi-trash"></i></button>
        </div>
    </div>
</template>

@push('scripts')
<script>
(function () {
    'use strict';
    const availableTags = {!! json_encode(
        $tags->map(function ($tag) {
            return [
                'id' => $tag->id,
                'name' => $tag->name,
                'keywords' => $tag->related_keywords_array,
            ];
        })->values()->all(),
        JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT
    ) !!};

    const tagPicker = document.getElementById('product-tag-picker');
    const tagInput = document.getElementById('tag-input');
    const hiddenTags = document.getElementById('tags-input-hidden');
    const tagChips = document.getElementById('tag-chips');
    const tagSuggestions = document.getElementById('tag-suggestions');
    const productNameInput = document.querySelector('[name="name"]');
    const descriptionInput = document.querySelector('[name="description"]');

    if (!tagPicker || !tagInput || !hiddenTags || !tagChips || !tagSuggestions) {
        return;
    }

    let selectedTags = [];

    function normalizeTag(value) {
        return String(value || '').trim().replace(/\s+/g, ' ');
    }

    function tagKey(value) {
        return normalizeTag(value).toLowerCase();
    }

    function syncHiddenTags() {
        hiddenTags.value = selectedTags.join(', ');
    }

    function escapeHtml(value) {
        const div = document.createElement('div');
        div.textContent = String(value ?? '');
        return div.innerHTML;
    }

    function renderTagChips() {
        tagChips.innerHTML = '';

        selectedTags.forEach(function (tag, index) {
            const chip = document.createElement('span');
            chip.className = 'tag-chip';

            const text = document.createElement('span');
            text.textContent = tag;

            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.setAttribute('aria-label', 'Hapus ' + tag);
            removeButton.innerHTML = '&times;';
            removeButton.addEventListener('click', function () {
                removeProductTag(index);
            });

            chip.appendChild(text);
            chip.appendChild(removeButton);
            tagChips.appendChild(chip);
        });

        syncHiddenTags();
    }

    function addProductTag(value) {
        const tag = normalizeTag(value);

        if (!tag) {
            return;
        }

        if (!selectedTags.some(function (item) {
            return tagKey(item) === tagKey(tag);
        })) {
            selectedTags.push(tag);
        }

        tagInput.value = '';
        renderTagChips();
        renderTagSuggestions();
        tagInput.focus();
    }

    function removeProductTag(index) {
        selectedTags.splice(index, 1);
        renderTagChips();
        renderTagSuggestions();
        tagInput.focus();
    }

    window.removeProductTag = removeProductTag;

    function getProductContext() {
        const name = productNameInput ? productNameInput.value : '';
        const description = descriptionInput ? descriptionInput.value : '';

        return normalizeTag(name + ' ' + description).toLowerCase();
    }

    function renderTagSuggestions() {
        const typed = normalizeTag(tagInput.value).toLowerCase();
        const context = getProductContext();

        const selected = new Set(
            selectedTags.map(function (tag) {
                return tagKey(tag);
            })
        );

        const selectedObjects = availableTags.filter(function (tag) {
            return selected.has(tagKey(tag.name));
        });

        const selectedKeywords = selectedObjects
            .flatMap(function (tag) {
                return Array.isArray(tag.keywords) ? tag.keywords : [];
            })
            .map(function (keyword) {
                return String(keyword).toLowerCase().trim();
            })
            .filter(Boolean);

        const ranked = availableTags
            .filter(function (tag) {
                return !selected.has(tagKey(tag.name));
            })
            .map(function (tag) {
                const name = String(tag.name).toLowerCase();
                const keywords = (Array.isArray(tag.keywords) ? tag.keywords : [])
                    .map(function (keyword) {
                        return String(keyword).toLowerCase().trim();
                    })
                    .filter(Boolean);

                let score = 0;
                let reason = 'Tag tersedia';

                // Prioritas pencarian berdasarkan apa yang sedang diketik.
                if (typed) {
                    if (name === typed) {
                        score += 100;
                    } else if (name.startsWith(typed)) {
                        score += 70;
                    } else if (name.includes(typed)) {
                        score += 40;
                    }

                    if (keywords.some(function (keyword) {
                        return keyword.includes(typed);
                    })) {
                        score += 35;
                    }
                }

                // Rekomendasi berdasarkan nama/deskripsi produk.
                if (context) {
                    if (context.includes(name)) {
                        score += 50;
                        reason = 'Cocok dengan produk';
                    }

                    if (keywords.some(function (keyword) {
                        return keyword.length > 1 && context.includes(keyword);
                    })) {
                        score += 30;
                        reason = 'Relevan dengan keyword';
                    }
                }

                // Setelah satu tag dipilih, gunakan related_keywords-nya
                // untuk mencari tag lain yang berhubungan.
                if (selectedKeywords.length) {
                    const related = keywords.some(function (keyword) {
                        return selectedKeywords.some(function (selectedKeyword) {
                            return keyword === selectedKeyword ||
                                keyword.includes(selectedKeyword) ||
                                selectedKeyword.includes(keyword);
                        });
                    });

                    if (related) {
                        score += 60;
                        reason = 'Terkait dengan tag yang dipilih';
                    }
                }

                // Saat input kosong, rekomendasi harus selalu tampil, baik
                // pada Create maupun Edit. Tag yang sudah dipilih tetap disaring.
                if (!typed) {
                    score = Math.max(score, 1);
                }

                return {
                    tag: tag,
                    score: score,
                    reason: reason
                };
            })
            .filter(function (item) {
                return item.score > 0;
            })
            .sort(function (a, b) {
                return b.score - a.score ||
                    String(a.tag.name).localeCompare(String(b.tag.name));
            });

        // Jika user sedang mengetik tetapi tidak ada kecocokan, jangan
        // biarkan panel rekomendasi kosong. Tampilkan tag lain yang belum
        // dipilih sebagai fallback.
        if (!ranked.length && typed) {
            availableTags
                .filter(function (tag) {
                    return !selected.has(tagKey(tag.name));
                })
                .slice(0, 8)
                .forEach(function (tag) {
                    ranked.push({
                        tag: tag,
                        score: 1,
                        reason: 'Tag tersedia'
                    });
                });
        }

        const visibleRanked = ranked.slice(0, 8);

        tagSuggestions.innerHTML = '';

        if (!ranked.length) {
            tagSuggestions.style.display = 'none';
            return;
        }

        visibleRanked.forEach(function (item) {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'tag-suggestion';

            const name = document.createElement('span');
            name.textContent = '+ ' + item.tag.name;

            const reason = document.createElement('small');
            reason.className = 'tag-reason';
            reason.textContent = item.reason;

            button.appendChild(name);
            button.appendChild(reason);

            // mousedown dipakai supaya klik tidak kalah oleh blur input.
            button.addEventListener('mousedown', function (event) {
                event.preventDefault();
                addProductTag(item.tag.name);
            });

            tagSuggestions.appendChild(button);
        });

        tagSuggestions.style.display = 'block';
    }

    function initializeProductTags() {
        /*
         * Backend menyimpan tags_input sebagai:
         * AI, Audio, Music
         *
         * Di sini kita ubah kembali menjadi array chip.
         */
        const initialValue = hiddenTags.value || '';

        selectedTags = initialValue
            .split(/[\n,]+/)
            .map(normalizeTag)
            .filter(Boolean)
            .filter(function (tag, index, array) {
                return array.findIndex(function (item) {
                    return tagKey(item) === tagKey(tag);
                }) === index;
            });

        renderTagChips();
        renderTagSuggestions();
    }

    tagInput.addEventListener('input', renderTagSuggestions);
    tagInput.addEventListener('focus', renderTagSuggestions);

    if (productNameInput) {
        productNameInput.addEventListener('input', renderTagSuggestions);
    }

    if (descriptionInput) {
        descriptionInput.addEventListener('input', renderTagSuggestions);
    }

    tagInput.addEventListener('keydown', function (event) {
        if (event.key === 'Enter' || event.key === ',') {
            event.preventDefault();

            if (tagInput.value.trim()) {
                addProductTag(tagInput.value);
            }
        }

        // Backspace ketika input kosong menghapus chip terakhir.
        if (event.key === 'Backspace' && !tagInput.value && selectedTags.length) {
            selectedTags.pop();
            renderTagChips();
            renderTagSuggestions();
        }

        if (event.key === 'Escape') {
            tagSuggestions.style.display = 'none';
        }
    });

    document.addEventListener('click', function (event) {
        if (!tagPicker.contains(event.target)) {
            tagSuggestions.style.display = 'none';
        }
    });

    initializeProductTags();
})();
</script>

<script>
    let variantIndex = {{ $product->variants->count() }};
    function addVariantRow() {
        const tpl = document.getElementById('variant-row-template').innerHTML.replaceAll('__i__', variantIndex);
        document.getElementById('variant-rows').insertAdjacentHTML('beforeend', tpl);
        variantIndex++;
    }
</script>
@endpush
@endsection