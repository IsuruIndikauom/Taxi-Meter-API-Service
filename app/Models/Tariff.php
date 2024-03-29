<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Tariff extends Model {
    use HasFactory;
    protected $guarded = [];

    public function createTariff ( $data ) {
        if ( $data->status == 1 ) {
            DB::table( 'tariffs' )->update( [ 'status' => 0 ] );
            $tarrif = $this->create( $data->all() ) ;
        } else {
            $tarrif = $this->create( $data->all() ) ;
        }
        return $tarrif ;
    }

    public function updateTariff( $data ) {
        if ( $data->status == 1 ) {
            DB::table( 'tariffs' )->where( 'id', '!=', $this->id )->update( [ 'status' => 0 ] );
            $tarrif = $this->update( $data->all() ) ;
        } else {
            $data->offsetUnset( 'status' );
            $tarrif = $this->update( $data->all() ) ;
        }
        return $this ;
    }
}
