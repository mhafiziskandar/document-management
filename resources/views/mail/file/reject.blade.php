<x-mail::message>
# Salam sejahtera {{ $notifiable->name }},

Fail untuk projek {{ $folder->project_name }} telah ditolak oleh pihak admin Hub Data.

Terima kasih atas kerjasama yang telah diberikan.

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
