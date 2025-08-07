<?php

namespace App\Forms\Components;

use Closure;
use Filament\Forms\Components\Field;

class CKEditor extends Field
{
    protected string $view = 'vendor.filament.forms.ckeditor';
    protected string | Closure $content = '';

    protected string $name = 'ckeditor';

    protected int $minLength = 0;

    protected string $placeholder = 'Type or paste your content here...';
    protected string | Closure | null $uploadUrl = null;
    protected string | Closure | null $browseUrl = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dehydrated(true);

        // ✅ Mặc định tự động
        $this->uploadUrl(fn () => route('ckeditor.upload'));
        $this->browseUrl(fn () => url('/ckeditor/ckfinder/ckfinder.html'));
    }

    public function placeholder(string | Closure $placeholder): static
    {
        $this->placeholder = $placeholder;
        return $this;
    }

    public function uploadUrl(string | Closure | null $url): static
    {
        $this->uploadUrl = $url;
        return $this;
    }

    public function browseUrl(string | Closure | null $url): static
    {
        $this->browseUrl = $url;
        return $this;
    }

    public function getPlaceholder(): string
    {
        return $this->evaluate($this->placeholder);
    }

    public function getUploadUrl(): ?string
    {
        return $this->evaluate($this->uploadUrl);
    }

    public function getBrowseUrl(): ?string
    {
        return $this->evaluate($this->browseUrl);
    }

}
