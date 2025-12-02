<?php

namespace App\Services\Admin;

use App\Models\Speaker;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class SpeakerService
{
    public function save(array $data, ?int $id = null): Speaker
    {
        $avatar = $data['avatar'] ?? null;
        unset($data['avatar']); // Remove avatar from main data array

        if ($avatar && $avatar instanceof TemporaryUploadedFile) {
            // If updating and a new avatar is uploaded, delete the old one
            if ($id) {
                $oldSpeaker = Speaker::find($id);
                if ($oldSpeaker && $oldSpeaker->avatar_url) {
                    Storage::disk('public')->delete($oldSpeaker->avatar_url);
                }
            }
            // Store the new avatar and update the path in data
            $data['avatar_url'] = $avatar->store('speakers', 'public');
        }

        $speakerData = [
            'name' => $data['name'],
            'title' => $data['title'],
            'bio' => $data['bio'],
        ];

        // Only update avatar_url if it's part of the data (a new one was uploaded)
        if(isset($data['avatar_url'])){
            $speakerData['avatar_url'] = $data['avatar_url'];
        }

        $speaker = Speaker::updateOrCreate(
            ['id' => $id],
            $speakerData
        );

        return $speaker;
    }

    public function delete(int $id): void
    {
        $speaker = Speaker::findOrFail($id);

        // Delete avatar from storage if it exists
        if ($speaker->avatar_url) {
            Storage::disk('public')->delete($speaker->avatar_url);
        }

        $speaker->delete();
    }
}
