<?php

namespace Tests\Feature\Transaction;

use App\Exceptions\UserBalanceNotEnoughException;
use App\Models\User;
use App\Models\Wallet;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Vinkla\Hashids\Facades\Hashids;


class DomainRsolverMock {
    static int $counter = 0;
    public function resolver()
    {
         self::$counter++;
    }
}
class TransferMoneyTest extends TestCase
{


    public function test_expected_retry()
    {
        $this->bind(DomainResolver::Class, DomainResolverMock::class);
        $this->post('/test');

        $this->expected(DomainRsolverMock::$counter, 5);
    }
    /**
     * A basic feature test example.
     */
    public function test_transfer_successfully_money_between_two_users(): void
    {
        $this->withoutExceptionHandling();
        $userSource = User::factory()->has(Wallet::factory(1)->state(['balance' => 100000]))->create();
        $userDest = User::factory()->has(Wallet::factory(1)->state(['balance' => 0]))->create();

        $transferAmount = 20000;

        $this->actingAs($userSource);

        $response = $this->postJson(route('wallets.transfer-money'), [
            'amount' => $transferAmount,
            'to' => Hashids::connection('user-id')->encode($userDest->id),
        ]);

        $response
            ->assertOk()
            ->assertJson([
                'status' => 'ok',
                'fee' => config('transaction.fee'),
            ]);

        $userSourceBalanceExpected = 100000 - $transferAmount - config('transaction.fee'); //79500
        $userDestBalanceExpected = 0 + $transferAmount;

        $this->assertEquals($userSourceBalanceExpected, $userSource->wallet->balance);
        $this->assertEquals($userDestBalanceExpected, $userDest->wallet->balance);
    }

    public function test_transfer_money_with_price_less_than_1000_amount(): void
    {
        $userSource = User::factory()->has(Wallet::factory(1)->state(['balance' => 100000]))->create();
        $userDest = User::factory()->has(Wallet::factory(1)->state(['balance' => 0]))->create();

        $transferAmount = 900;

        $this->actingAs($userSource);

        $response = $this->postJson(route('wallets.transfer-money'), [
            'amount' => $transferAmount,
            'to' => Hashids::connection('user-id')->encode($userDest->id),
        ]);

        $response
            ->assertUnprocessable()
            ->assertInvalid(['amount']);

        $userSourceBalanceExpected = 100000;
        $userDestBalanceExpected = 0;

        $userSource->refresh();
        $userDest->refresh();
        $this->assertEquals($userSourceBalanceExpected, $userSource->wallet->balance);
        $this->assertEquals($userDestBalanceExpected, $userDest->wallet->balance);
    }

    public function test_transfer_money_with_wrong_receiver_id()
    {
        $userSource = User::factory()->has(Wallet::factory(1)->state(['balance' => 100000]))->create();
        $userDest = User::factory()->has(Wallet::factory(1)->state(['balance' => 0]))->create();

        $transferAmount = 900;

        $this->actingAs($userSource);

        $response = $this->postJson(route('wallets.transfer-money'), [
            'amount' => $transferAmount,
            'to' => 'test',
        ]);

        $response
            ->assertUnprocessable()
            ->assertInvalid(['to', 'amount']);

        $userSourceBalanceExpected = 100000;
        $userDestBalanceExpected = 0;

        $this->assertEquals($userSourceBalanceExpected, $userSource->wallet->balance);
        $this->assertEquals($userDestBalanceExpected, $userDest->wallet->balance);
    }

    public function test_transfer_money_on_wallet_not_enough_money()
    {
        $userSource = User::factory()->has(Wallet::factory(1)->state(['balance' => 1000]))->create();
        $userDest = User::factory()->has(Wallet::factory(1)->state(['balance' => 0]))->create();

        $transferAmount = 2000;

        $this->actingAs($userSource);

        $response = $this->postJson(route('wallets.transfer-money'), [
            'amount' => $transferAmount,
            'to' => Hashids::connection('user-id')->encode($userDest->id),
        ]);

        $response
            ->assertBadRequest()
            ->assertJson([
                'error' => trans('exceptions.'.UserBalanceNotEnoughException::class)
            ]);

        $this->assertEquals(1000, $userSource->wallet->balance);
        $this->assertEquals(0, $userDest->wallet->balance);
    }
}
