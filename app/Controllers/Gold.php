<?php

namespace App\Controllers;

use App\Libraries\GoldService;

class Gold extends BaseController
{
    private function requireUserId()
    {
        if (! session('is_logged_in')) {
            return redirect()->to('/login')->with('errors', [
                'auth' => 'Connecte-toi pour accéder à cette page.',
            ]);
        }

        $userId = (int) session('user_id');
        if ($userId <= 0) {
            return redirect()->to('/login')->with('errors', [
                'auth' => 'Session invalide. Merci de te reconnecter.',
            ]);
        }

        return $userId;
    }

    public function activate()
    {
        $userId = $this->requireUserId();
        if (! is_int($userId)) {
            return $userId;
        }

        $service = new GoldService();
        $result = $service->activate($userId);

        if (! ($result['ok'] ?? false)) {
            $code = (string) ($result['code'] ?? 'error');
            $message = (string) ($result['message'] ?? 'Erreur lors de l\'activation Gold.');

            if ($code === 'insufficient_funds') {
                return redirect()->to('/wallet')->with('errors', [
                    'gold' => $message,
                ]);
            }

            return redirect()->back()->with('errors', [
                'gold' => $message,
            ]);
        }

        return redirect()->to('/profil')->with('success', (string) ($result['message'] ?? 'Gold activé.'));
    }
}
