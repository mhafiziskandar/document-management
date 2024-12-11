<x-mail::message>
# Salam sejahtera {{ $notifiable->name }}

Terdapat fail baru telah dimuat naik & memerlukan pengesahan.

<x-mail::table>
| | |
| ------------- |:-------------:|
| Projek: | [{{ $folder->bil }}: {{ $folder->project_name }}] |
| Fail: | [{{ is_null($file->filename) ? $file?->url : $file->filename }} |
</x-mail::table>

@component('mail::button', ['url' => route('admin.projects.show', $folder)])
Pergi ke web
@endcomponent

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
