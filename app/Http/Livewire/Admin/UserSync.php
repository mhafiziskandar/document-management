<?php

namespace App\Http\Livewire\Admin;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Livewire\Component;
use App\Notifications\UserSync as NotificationsUserSync;

class UserSync extends Component
{
    public $isVisible = true;

    public function render()
    {
        return view('livewire.admin.user-sync');
    }

    public function request()
    {
        $login_response = Http::post('https://myprofil.iyres.gov.my/api/login', [
            'ic_no' => 'myprofil@iyres.gov.my',
            'password' => 'PP@ssw0rd2022',
        ]);

        $check = Http::withHeaders([
            'Authorization' => "Bearer " . $login_response->json()['token'],
        ])->post('https://myprofil.iyres.gov.my/api/warga-iyres', [
            'limit' => 1,
            'offset' => 0
        ]);

        $total = $check->json()['total'];

        $limit = 100;

        for ($offset = 0; $offset <= $total; $offset += 100) {
            $response = Http::withHeaders([
                'Authorization' => "Bearer " . $login_response->json()['token'],
            ])->post('https://myprofil.iyres.gov.my/api/warga-iyres', [
                'limit' => $limit,
                'offset' => $offset
            ]);

            if (isset($response->json()['data'])) {
                foreach ($response->json()['data'] as $data) {

                    $ic_ebelia[] = $data['ic_no'];

                    $checkingStatus = User::where('user_id', $data['id'])
                        // ->whereIn('status', [User::REJECT, User::APPROVED])
                        ->first();

                    if (!$checkingStatus) {
                        $user_id_array = ['user_id' => $data['id']];

                        $user_data_array = [
                            'name' => $data['name'],
                            'email' => $data['email'],
                            'ic_no' => $data['ic_no'],
                            'profile_image' => $data['profile_picture'],
                            'status' => User::PENDING,
                            'sync' => Carbon::now()
                        ];

                        $user = User::updateOrCreate($user_id_array, $user_data_array);

                        if ($user->wasRecentlyCreated) {
                            $admins = User::role('admin')->get();

                            Notification::send($admins, new NotificationsUserSync());
                        }

                        $user->syncRoles(['member']);
                    } else {
                        $checkingStatus->update([
                            'name' => $data['name'],
                            'email' => $data['email'],
                            'ic_no' => $data['ic_no'],
                            'profile_image' => $data['profile_picture']
                        ]);
                    }
                }
            }
        }

        $icno = User::role('member')->pluck('ic_no')->toArray();

        // foreach ($icno as $value) {
        //     if (!in_array($value, $ic_ebelia)) {

        //         User::where('ic_no', $value)->update(['status' => User::DELETE]);
        //     }
        // }

        $this->isVisible = true;

        $this->emit('updateTable');
    }
}
