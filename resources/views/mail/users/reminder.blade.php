<x-mail::message>
# Salam sejahtera {{ $notifiable->name }}

Ini adalah peringatan mesra yang memerlukan tindakan anda untuk memuat naik fail-fail yang diperlukan.

<x-mail::table>
| | |
| ------------- |:-------------:|
| Projek: | {{ $project->id }}: {{ $project->project_name }} |
| Jenis Fail Diperlukan: | {{ $project->types->implode('name', ', ') }} |
| Tarikh Akhir: | {{ $project->tarikh_akhir}} |
</x-mail::table>

Kerjasama dari pihak tuan/puan amat kami hargai dan kami dahulukan dengan ucapan ribuan 
terima kasih.

Sekian,<br>
{{ config('app.name') }}
</x-mail::message>
