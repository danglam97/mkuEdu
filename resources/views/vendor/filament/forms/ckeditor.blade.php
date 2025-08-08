@php
    $name = $getName();
    $uploadUrl = $getUploadUrl();
    $browseUrl = $getBrowseUrl();
    $placeholder = $getPlaceholder();
@endphp

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <x-filament::input.wrapper :valid="$errors->count() === 0">
        <div wire:ignore>
            {{-- CKEditor & CKFinder --}}
            <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
            <script src="{{ asset('ckeditor/ckfinder/ckfinder.js') }}"></script>
            <script>
                function createCKEditor() {
                    if (CKEDITOR.instances['ckeditor-{{ $name }}']) {
                        CKEDITOR.instances['ckeditor-{{ $name }}'].destroy(true);
                    }

                    CKEDITOR.replace('ckeditor-{{ $name }}', {
                        language: 'vi',
                        height: 500,
                        removePlugins: 'base64image',
                        filebrowserUploadUrl: '{{ $uploadUrl }}',
                        filebrowserUploadMethod: 'form',
                        filebrowserBrowseUrl: '{{ $browseUrl }}',
                        on: {
                            change: function (evt) {
                                Livewire.dispatch('contentUpdated', {
                                    content: evt.editor.getData(),
                                    editor: 'ckeditor-{{ $name }}'
                                });
                            }
                        }
                    });
                }
            </script>

            <div
                x-data="{
                    state: $wire.$entangle('{{ $getStatePath() }}'),
                    init() {
                        // Khởi tạo với giá trị từ Livewire
                        createCKEditor(this.state);

                        // Khi nội dung CKEditor thay đổi -> cập nhật Livewire state
                        Livewire.on('contentUpdated', (payload) => {
                            this.state = payload.content;
                        });
                    }
                }"
            >
                <textarea
                    id="ckeditor-{{ $name }}"
                    name="{{ $name }}"
                    x-model="state"
                    placeholder="{{ $placeholder }}"
                ></textarea>
            </div>
        </div>
    </x-filament::input.wrapper>
</x-dynamic-component>
