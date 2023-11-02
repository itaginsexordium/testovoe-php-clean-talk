<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Inet;


class Networks extends Model
{
    use HasFactory;

    protected $table = 'networks';
    protected $fillable  = [
        'network_address',
        'netmask',
        'country_code_id',
    ];

    protected $casts = [
        'network_address' => 'string',
    ];

    // SELECT *
    // FROM networks
    // WHERE network_address = INET_ATON('ВАШ_IPv4_АДРЕС') & (0xFFFFFFFF << (32 - netmask))
    // ORDER BY netmask DESC
    // LIMIT 1;
    public static function findIpV4($ip)
    {
        return self::whereRaw("network_address = INET_ATON(?) & (0xFFFFFFFF << (32 - netmask))", [$ip])
            ->join('countries', 'networks.country_code_id', '=', 'countries.geoname_id')
            ->orderBy('netmask', 'DESC')
            ->limit(1)
            ->first();
    }

    // SELECT *
    // FROM networks
    // WHERE network_address = INET6_ATON('ВАШ_IPv6_АДРЕС') & (0xFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFF << (128 - netmask))
    // ORDER BY netmask DESC
    // LIMIT 1;
    public static function findIpv6($ip)
    {
        return self::whereRaw("network_address = INET6_ATON(?) & (0xFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFFF << (128 - netmask))", [$ip])
        ->join('countries', 'networks.country_code_id', '=', 'countries.geoname_id')    
        ->orderBy('netmask', 'DESC')
            ->limit(1)
            ->first();
    }

    public function getGenIpFromBlob()
    {
        return inet_ntop($this->network_address);
    }


    public function country()
    {
        return $this->belongsTo(Country::class, 'country_code_id', 'geoname_id');
    }

    public function setNetworkAddressAttribute($value)
    {
        $this->attributes['network_address'] = inet_pton($value);
    }

    public function getNetworkAddressAttribute($value)
    {
        return inet_ntop($value);
    }


    public function getCountryNameAttribute($value)
    {
        return $this->country->name ?? 'Unknown';
    }
}
