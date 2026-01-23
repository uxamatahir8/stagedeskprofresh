@extends('emails.layout')

@section('content')
    <h2 style="color: #333; margin-bottom: 20px;">Artist Share Request</h2>

    @php
        $ownerCompany = $sharedArtist->ownerCompany;
        $sharedCompany = $sharedArtist->sharedWithCompany;
        $artist = $sharedArtist->artist;
    @endphp

    <p>Hello {{ $sharedCompany->user->name }},</p>

    <p><strong>{{ $ownerCompany->company_name }}</strong> would like to share one of their artists with your company.</p>

    <div class="info-box">
        <strong>🎭 Artist Share Request</strong><br>
        An artist is available for collaboration between companies.
    </div>

    <h3 style="color: #333; margin: 25px 0 15px 0;">Artist Information</h3>

    <table class="details-table">
        <tr>
            <th>Artist Name</th>
            <td><strong>{{ $artist->user->name }}</strong></td>
        </tr>
        <tr>
            <th>Specialization</th>
            <td>{{ $artist->specialization ?? 'N/A' }}</td>
        </tr>
        @if($artist->experience_years)
        <tr>
            <th>Experience</th>
            <td>{{ $artist->experience_years }} years</td>
        </tr>
        @endif
        @if($artist->bio)
        <tr>
            <th>Bio</th>
            <td>{{ $artist->bio }}</td>
        </tr>
        @endif
    </table>

    <h3 style="color: #333; margin: 25px 0 15px 0;">Sharing Company</h3>

    <table class="details-table">
        <tr>
            <th>Company Name</th>
            <td><strong>{{ $ownerCompany->company_name }}</strong></td>
        </tr>
        <tr>
            <th>Contact Person</th>
            <td>{{ $ownerCompany->user->name }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ $ownerCompany->user->email }}</td>
        </tr>
    </table>

    @if($sharedArtist->notes)
    <div style="margin: 20px 0;">
        <strong>Notes from Sharing Company:</strong>
        <p style="margin: 10px 0; padding: 15px; background-color: #f8f9fa; border-radius: 5px;">
            {{ $sharedArtist->notes }}
        </p>
    </div>
    @endif

    <div class="success-box">
        <strong>✓ Benefits of Artist Sharing</strong>
        <ul style="margin: 10px 0;">
            <li>Access to additional talent for your events</li>
            <li>Expand your service offerings</li>
            <li>Collaborate with other companies</li>
            <li>No additional hiring costs</li>
        </ul>
    </div>

    <div class="warning-box">
        <strong>⚠️ Action Required:</strong><br>
        Please log in to your dashboard to review and respond to this artist sharing request.
    </div>

    <div style="text-align: center; margin: 25px 0;">
        <a href="{{ config('app.url') }}/company-admin/artist-sharing" class="button">Review Request</a>
    </div>

    <p>You can accept or reject this request from your Artist Sharing dashboard.</p>

    <p>Best regards,<br>The {{ config('app.name') }} Team</p>
@endsection
