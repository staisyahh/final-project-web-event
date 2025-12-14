<?php

namespace App\Livewire\Forms\Admin;

use App\Models\Speaker;
use Livewire\Form;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;

class SpeakerForm extends Form
{
    use WithFileUploads;

    public ?Speaker $speaker = null;

    #[Validate('required|string|min:3|max:255')]
    public string $name = '';

    #[Validate('nullable|string|max:255')]
    public ?string $title = '';

    #[Validate('nullable|string')]
    public ?string $bio = '';

    // Will be instance of UploadedFile.
    #[Validate('nullable|image|max:2048')] // 2MB Max
    public $avatar = null;

    public function setSpeaker(Speaker $speaker)
    {
        $this->speaker = $speaker;
        $this->name = $speaker->name;
        $this->title = $speaker->title;
        $this->bio = $speaker->bio;
        // Note: We don't populate $this->avatar here because it's for upload.
        // The existing avatar URL will be accessed directly from the speaker model in the view.
    }

    public function resetForm()
    {
        $this->reset();
    }
}
