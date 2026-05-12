<?php

namespace App\Traits;

use App\Settings\AppSettings;

trait ApiResponse
{
    protected function success($data = null, string $message = 'Berhasil', int $status = 200)
    {
        return response()->json([
            'status'  => true,
            'message' => $message,
            'data'    => $data,
            'meta'    => $this->buildMeta(),
        ], $status);
    }

    protected function error(string $message = 'Terjadi kesalahan', int $status = 400, $errors = null)
    {
        $response = [
            'status'  => false,
            'message' => $message,
            'data'    => null,
            'meta'    => $this->buildMeta(),
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $status);
    }

    private function buildMeta(): array
    {
        $settings = app(AppSettings::class);

        return [
            'app_name'    => $settings->app_name,
            'app_version' => $settings->app_version,
        ];
    }
}
