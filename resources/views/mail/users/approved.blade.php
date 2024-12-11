<x-mail::message>
# Salam sejahtera {{ $notifiable->name }},

Kami di Hub Data mengalukan anda sebagai ahli terbaru kami. Anda boleh login di <a href="{{ route('login') }}">{{ route('login')}}</a>
untuk mendapatkan maklumat projek-projek yang telah ditugaskan kepada anda. Setiap projek anda perlu memuat naik
fail-fail yang diperlukan mengikut tarikh akhir yang telah ditetapkan.

Kerjasama dari pihak tuan/puan amat kami hargai dan kami dahulukan dengan ucapan ribuan terima kasih.

Sekian,<br>
{{ config('app.name') }}
</x-mail::message>
