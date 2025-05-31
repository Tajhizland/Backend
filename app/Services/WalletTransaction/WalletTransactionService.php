<?php

namespace App\Services\WalletTransaction;

use App\Enums\WalletTransactionStatus;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\WalletTransaction\WalletTransactionRepositoryInterface;
use App\Services\Payment\Gateways\Strategy\GatewayStrategyServicesInterface;

class WalletTransactionService implements WalletTransactionServiceInterface
{
    private $gatewayService;

    public function __construct
    (
        private WalletTransactionRepositoryInterface $walletTransactionRepositoryInterface,
        private UserRepositoryInterface              $userRepositoryInterface,
        private GatewayStrategyServicesInterface     $gatewayStrategyServices,

    )
    {
        $this->gatewayService = $this->gatewayStrategyServices->strategy();
    }

    public function chargeRequest($userId, $amount)
    {
        $walletTransaction = $this->walletTransactionRepositoryInterface->create([
            'user_id' => $userId,
            'amount' => $amount,
            'status' => WalletTransactionStatus::Unpaid->value,
        ]);
        return $this->gatewayService->chargeRequest($amount * 10, $walletTransaction->id);
    }

    public function verifyCharge($request)
    {
        $request = $this->gatewayService->callbackParams($request);
        $this->gatewayService->verify($request->trackId);
        $wallet = $this->walletTransactionRepositoryInterface->findOrFail($request->orderId);
        $this->walletTransactionRepositoryInterface->update($wallet, ["status" => WalletTransactionStatus::Paid->value, "track_id" => $request->trackId]);
        $user = $this->userRepositoryInterface->findOrFail($wallet->user_id);
        return $this->userRepositoryInterface->update($user, ["wallet" => $wallet->amount + $user->wallet]);
    }

    public function dataTable()
    {
        return $this->walletTransactionRepositoryInterface->dataTable();
    }
}
