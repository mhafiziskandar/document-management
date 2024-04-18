<x-mail::message>
# Salam sejahtera {{ $notifiable->name }},

Terdapat projek yang memerlukan tindakan daripada anda. Anda perlu memuat naik fail-fail yang diperlukan sebelum tarikh
akhir yang telah ditetapkan.

<x-mail::table>
| | |
| ------------- |:-------------:|
| Projek: | {{ $folder->project_name}} |
| Jenis Fail Diperlukan: | {{ $folder->types->implode('name', ', ') }} |
| Tarikh Akhir: | {{ $folder->tarikh_akhir}} |
</x-mail::table>

Kerjasama dari pihak tuan/puan amat kami hargai dan kami dahulukan dengan ucapan ribuan terima kasih.

Sekian,<br>
{{ config('app.name') }}
</x-mail::message>
