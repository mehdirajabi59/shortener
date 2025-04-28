<?php

namespace App\Http\Controllers\API\Wallet;

use App\DTO\TransferDetailRequestDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\TransferMoney\TransferRequest;
use App\Services\TransferMoney\TransferMoneyServiceInterface;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class TransferMoneyController extends Controller
{
    public function __construct(private readonly TransferMoneyServiceInterface $transferMoneyService) {}

    public function test()
    {
        //customer id
        // domain name

        try {
            $result = null;
            retry(5, function() use(&$result){
                $service  = resolve(DoaminResolver::class);
                $result = $service->resolve("domain", "customer_id");
            }, 1000);

            Doamin::query()->create([
                'domain_id' => $result['domain_id']
            ]);
        }catch (\Throwable $exception){
            report($exception);
            return response([
                'message' => 'failed'
            ]);
        }

        return response([
            'message' => 'ok'
        ]);
    }
    public function __invoke(TransferRequest $request)
    {
        $validatedData = $request->validated();
        // Try to acquire the lock for 10 seconds - Prevent Race Condition
        $lock = Cache::lock('transfer-money-lock-'.Auth::id(), 10);
        if (! $lock->get()) {
            return response([
                'status' => 'error',
                'message' => __('error.race-condition'),
            ], Response::HTTP_TOO_MANY_REQUESTS);

        }

        try {
            $responseDTO = $this->transferMoneyService->transfer(
                resolve(TransferDetailRequestDTO::class)
                    ->setUserSourceId(Auth::id())
                    ->setUserDestId($validatedData['to'])
                    ->setAmount($validatedData['amount'])
            );

            return response([
                'status' => 'ok',
                'fee' => $responseDTO->getFee(),
            ]);

        } finally {
            // Release the lock after processing
            $lock->release();
        }
    }
}
