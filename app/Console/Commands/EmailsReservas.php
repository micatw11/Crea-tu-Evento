<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservaProveedor as MailReserva;
use Carbon\Carbon;
use App\Reserva;

class EmailsReservas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:reservas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia un email de recordatorio a proveedores que posean reservas esta semana';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $to = Carbon::now()->addDays(29); 
        $from = Carbon::now();
        $reservas = Reserva::join('publicaciones', 'publicaciones.id', '=', 'reservas.publicacion_id')
            ->join('proveedores', 'publicaciones.proveedor_id', '=', 'proveedores.id')
            ->where('reservas.estado', 'confirmado')
            ->whereBetween('fecha', array($from->toDateString(),$to->toDateString()))
            ->with('publicacion.proveedor')->orderBy('proveedores.id', 'ASC')->get();
        $count = 0;
        $proveedor_id = null;
        foreach ($reservas as $reserva) {
            if($proveedor_id != $reserva->proveedor->id){
                Mail::to($reserva->publicacion->proveedor->email)->queue(new MailReserva($count));
                $count = 0;
                $proveedor_id = $reserva->publicacion->proveedor->id;
                $count++;
            }
            else
            {
                $count++;
            }
        }
        $this->info('Los email de recordatorio de reservas han sido enviados correctamente!');
    }
}
