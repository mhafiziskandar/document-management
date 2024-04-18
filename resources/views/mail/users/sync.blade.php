<x-mail::message>
# Salam sejahtera {{ $notifiable->name }},

Pengguna baru telah ditemui dari Hub Profil & memerlukan pengesahan. 
Sila login ke Hub Data dan klik ke bahagian menu Pengguna dan klik pada Pengguna Baru

@component('mail::button', ['url' => route('admin.users.index')])
Hub Data
@endcomponent

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
