<?php

namespace App\Controllers;

use App\Models\RegimeCodeModel;
use App\Models\RegimeWalletModel;

class Wallet extends BaseController
{
    private function requireUserId()
    {
        if (! session('is_logged_in')) {
            return redirect()->to('/login')->with('errors', [
                'auth' => 'Connecte-toi pour accéder à ton wallet.',
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

    public function index()
    {
        $userId = $this->requireUserId();
        if (! is_int($userId)) {
            return $userId;
        }

        $walletModel = new RegimeWalletModel();
        $wallet = $walletModel->where('user_id', $userId)->first();

        if ($wallet === null) {
            $walletModel->insert([
                'user_id' => $userId,
                'solde' => 0,
            ]);
            $wallet = $walletModel->where('user_id', $userId)->first();
        }

        return view('wallet/index', [
            'wallet' => $wallet,
        ]);
    }

    public function recharge()
    {
        $userId = $this->requireUserId();
        if (! is_int($userId)) {
            return $userId;
        }

        $code = strtoupper(trim((string) $this->request->getPost('code')));
        if ($code === '') {
            return redirect()->back()->withInput()->with('errors', [
                'code' => 'Le code est requis.',
            ]);
        }

        $walletModel = new RegimeWalletModel();
        $codeModel = new RegimeCodeModel();

        $wallet = $walletModel->where('user_id', $userId)->first();
        if ($wallet === null) {
            $walletModel->insert([
                'user_id' => $userId,
                'solde' => 0,
            ]);
            $wallet = $walletModel->where('user_id', $userId)->first();
        }

        $codeRow = $codeModel->where('code', $code)->first();
        if ($codeRow === null) {
            return redirect()->back()->withInput()->with('errors', [
                'code' => 'Code invalide.',
            ]);
        }

        if ((int) ($codeRow['used'] ?? 0) === 1) {
            return redirect()->back()->withInput()->with('errors', [
                'code' => 'Code déjà utilisé.',
            ]);
        }

        $montant = (float) ($codeRow['montant'] ?? 0);
        if ($montant <= 0) {
            return redirect()->back()->withInput()->with('errors', [
                'code' => 'Montant du code invalide.',
            ]);
        }

        $db = db_connect();
        $db->transStart();

        $walletModel->update($wallet['id'], [
            'solde' => (float) ($wallet['solde'] ?? 0) + $montant,
        ]);

        $codeModel->update($codeRow['id'], [
            'used' => 1,
        ]);

        $db->transComplete();

        if (! $db->transStatus()) {
            return redirect()->back()->withInput()->with('errors', [
                'database' => 'Impossible d\'ajouter le montant pour le moment.',
            ]);
        }

        return redirect()->to('/wallet')->with('success', 'Montant ajouté au wallet.');
    }
}
