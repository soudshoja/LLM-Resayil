<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiSettingsController extends Controller
{
    private array $settingKeys = [
        'OLLAMA_GPU_URL',
        'OLLAMA_CLOUD_URL',
        'CLOUD_API_KEY',
        'MYFATOORAH_API_KEY',
        'MYFATOORAH_BASE_URL',
        'MYFATOORAH_CALLBACK_URL',
        'WHATSAPP_API_URL',
        'WHATSAPP_API_KEY',
        'ADMIN_PHONE',
        'REDIS_HOST',
        'REDIS_PORT',
        'REDIS_PASSWORD',
    ];

    public function index()
    {
        $settings = [];
        foreach ($this->settingKeys as $key) {
            $settings[$key] = env($key, '');
        }

        return view('admin.api-settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $envPath = base_path('.env');

        if (!file_exists($envPath)) {
            return back()->with('error', '.env file not found.');
        }

        $envContent = file_get_contents($envPath);

        foreach ($this->settingKeys as $key) {
            if (!$request->has($key)) {
                continue;
            }

            $value = $request->input($key, '');
            $escaped = preg_match('/\s/', $value) ? '"' . $value . '"' : $value;

            if (preg_match("/^{$key}=/m", $envContent)) {
                $envContent = preg_replace(
                    "/^{$key}=.*/m",
                    "{$key}={$escaped}",
                    $envContent
                );
            } else {
                $envContent .= "\n{$key}={$escaped}";
            }
        }

        file_put_contents($envPath, $envContent);

        return back()->with('success', 'API settings saved successfully.');
    }
}
