<?php

namespace App\Models;

use App\Classes\WhitebitPrivate;
use App\Classes\WhitebitPublic;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'referral_code',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function getWallets($type='all')
    {
        if ($type === 'crypto')
        {
            return UserWallet::whereNotNull('address')->where('user_id', '=', $this->id)->get();
        }
        else if ($type == 'fiat')
        {
            return UserWallet::whereNull('address')->where('user_id', '=', $this->id)->get();
        }
        else
        {
            return UserWallet::where('user_id', '=', $this->id)->get();
        }
    }

    public function generateWallets($wallets=['BTC', 'USDT', 'ETH', 'MATIC', 'DOGE', 'USD', 'EUR']) {
        $assets = WhitebitPublic::getAssets();

        foreach ($wallets as $value)
        {
            $asset = $assets[$value];
            $data[$value] = $asset;

            $data = [
                'ticker' => $value,
                'user_id' => $this->id,
                'name' => $asset['name'],
                'amount' => 0
            ];

            if (array_key_exists('networks', $asset))
            {
                if (array_key_exists('default', $asset['networks']))
                {
                    $data['network'] = $asset['networks']['default'];
                }
                else
                {
                    if (array_key_exists('deposits', $asset['networks']) && count($asset['networks']['deposits']) > 0)
                    {
                        $data['network'] = $asset['networks']['deposits'][0];
                    }
                }
            }
            else if (array_key_exists('providers', $asset))
            {
                if (array_key_exists('deposits', $asset['providers']))
                {
                    $data['provider'] = json_encode($asset['providers']['deposits']);
                }
            }

            if (array_key_exists('network', $data))
            {
                $data['address'] = WhitebitPrivate::createNewAddress($value, $data['network'])['account']['address'] ?? null;
            }

            UserWallet::create($data);
        }

        return true;
    }
}
