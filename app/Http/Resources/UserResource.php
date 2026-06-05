<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = [
            'id'               => $this->id,
            'name'             => $this->name,
            'email'            => $this->email,
            'photo'            => $this->photo,
            'profile_photo_url' => $this->profile_photo_url,
            'roles'            => $this->getRoleNames(),
            'created_at'       => $this->created_at,
        ];

        $profile = $this->profile();

        if ($profile) {
            $data['profile'] = match (true) {
                $this->hasRole('guru') => [
                    'nip'            => $profile->nip,
                    'specialization' => $profile->specialization,
                ],
                $this->hasRole('siswa') => [
                    'nis'          => $profile->nis,
                    'classroom_id' => $profile->classroom_id,
                    'guardian_id'  => $profile->guardian_id,
                    'classroom'    => $profile->classroom ? [
                        'grade'         => $profile->classroom->grade,
                        'group_number'  => $profile->classroom->group_number,
                        'academic_year' => $profile->classroom->academic_year,
                        'major_name'    => $profile->classroom->major?->major_name,
                    ] : null,
                ],
                $this->hasRole('guardian') => [
                    'phone_number' => $profile->phone_number,
                    'address'      => $profile->address,
                ],
                default => [],
            };
        }

        return $data;
    }
}
