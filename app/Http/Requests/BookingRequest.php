<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\EventType;

class BookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $eventType = EventType::find($this->event_type_id);
        $isWedding = $eventType && str_contains(strtolower($eventType->event_type), 'wedding');

        return [
            'event_date'       => 'required|date|after:today',
            'user_id'          => 'required|exists:users,id',
            'event_type_id'    => 'required|exists:event_types,id',
            'name'             => 'required|string|max:255',
            'surname'          => 'required|string|max:255',
            'date_of_birth'    => 'required|date|before:' . now()->subYears(18)->format('Y-m-d'),
            'phone'            => 'required|string|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:20',
            'email'            => 'required|email:rfc,dns|max:255',
            'address'          => 'required|string|max:500',
            'start_time'       => 'nullable|date_format:H:i',
            'end_time'         => 'nullable|date_format:H:i|after:start_time',
            'partner_name'     => $isWedding ? 'required|string|max:255' : 'nullable|string|max:255',
            'wedding_date'     => $isWedding ? 'required|date|after:today' : 'nullable|date',
            'wedding_time'     => $isWedding ? 'required|string' : 'nullable|string',
            'wedding_location' => $isWedding ? 'required|string|max:500' : 'nullable|string|max:500',
            'dos'              => 'nullable|array',
            'dos.*'            => 'string|max:500',
            'donts'            => 'nullable|array',
            'donts.*'          => 'string|max:500',
            'playlist_spotify' => 'nullable|url|max:500',
            'additional_notes' => 'nullable|string|max:2000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'event_date.after' => 'The event date must be in the future.',
            'date_of_birth.before' => 'You must be at least 18 years old.',
            'phone.regex' => 'The phone number format is invalid.',
            'email.email' => 'Please provide a valid email address.',
            'end_time.after' => 'The end time must be after the start time.',
            'playlist_spotify.url' => 'Please provide a valid Spotify playlist URL.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Convert dos and donts strings to arrays if needed
        if ($this->has('dos') && is_string($this->dos)) {
            $this->merge([
                'dos' => array_filter(array_map('trim', explode("\n", $this->dos)))
            ]);
        }

        if ($this->has('donts') && is_string($this->donts)) {
            $this->merge([
                'donts' => array_filter(array_map('trim', explode("\n", $this->donts)))
            ]);
        }
    }
}
