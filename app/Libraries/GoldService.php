<?php

namespace App\Libraries;

use App\Models\RegimeUtilisateurModel;
use App\Models\RegimeWalletModel;

class GoldService
{
    /**
     * Active Gold pour un utilisateur si possible.
     *
     * @return array{ok:bool,code?:string,message?:string}
     */
    public function activate(int $userId): array
    {
        if ($userId <= 0) {
            return [
                'ok' => false,
                'code' => 'invalid_user',
                'message' => 'Utilisateur invalide.',
            ];
        }

        $details = GoldOption::details();
        $price = (float) ($details['price'] ?? 0);

        if ($price <= 0) {
            return [
                'ok' => false,
                'code' => 'invalid_price',
                'message' => 'Prix Gold invalide.',
            ];
        }

        $users = new RegimeUtilisateurModel();
        $wallets = new RegimeWalletModel();
        $db = db_connect();

        $db->transStart();

        $user = $users->find($userId);
        if ($user === null) {
            $db->transComplete();
            return [
                'ok' => false,
                'code' => 'user_not_found',
                'message' => 'Utilisateur introuvable.',
            ];
        }

        if (isset($user['is_gold']) && (int) $user['is_gold'] === 1) {
            $db->transComplete();
            return [
                'ok' => true,
                'code' => 'already_gold',
                'message' => 'Gold est déjà actif.',
            ];
        }

        $wallet = $wallets->where('user_id', $userId)->first();
        if ($wallet === null) {
            $wallets->insert([
                'user_id' => $userId,
                'solde' => 0,
            ]);
            $wallet = $wallets->where('user_id', $userId)->first();
        }

        $walletId = (int) ($wallet['id'] ?? 0);
        if ($walletId <= 0) {
            $db->transComplete();
            return [
                'ok' => false,
                'code' => 'wallet_error',
                'message' => 'Wallet introuvable.',
            ];
        }

        // Débit atomique: ne débite que si solde >= price.
        $db->table('regime_wallet')
            ->set('solde', 'solde - ' . (float) $price, false)
            ->where('id', $walletId)
            ->where('solde >=', $price)
            ->update();

        if ($db->affectedRows() <= 0) {
            $db->transComplete();
            return [
                'ok' => false,
                'code' => 'insufficient_funds',
                'message' => 'Solde insuffisant pour activer Gold.',
            ];
        }

        $users->update($userId, [
            'is_gold' => 1,
        ]);

        $db->transComplete();

        if (! $db->transStatus()) {
            return [
                'ok' => false,
                'code' => 'database',
                'message' => 'Impossible d\'activer Gold pour le moment.',
            ];
        }

        return [
            'ok' => true,
            'code' => 'activated',
            'message' => 'Gold activé avec succès.',
        ];
    }
}
