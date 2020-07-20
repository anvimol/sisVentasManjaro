<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Ingreso;
use App\DetalleIngreso;
use App\User;
use App\Notifications\NotifiAdmin;

class IngresoController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if ($buscar=='') {
            $ingresos = Ingreso::join('personas','ingresos.proveedor_id','=','personas.id')
            ->join('users','ingresos.usuario_id','=','users.id')
            ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante',
            'ingresos.num_comprobante','ingresos.fecha_hora','ingresos.impuesto','ingresos.total',
            'ingresos.estado','personas.nombre','users.usuario')
            ->orderBy('ingresos.id','desc')->paginate(5);
        }
        else {
            $ingresos = Ingreso::join('personas','ingresos.proveedor_id','=','personas.id')
            ->join('users','ingresos.usuario_id','=','users.id')
            ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante',
            'ingresos.num_comprobante','ingresos.fecha_hora','ingresos.impuesto','ingresos.total',
            'ingresos.estado','personas.nombre','users.usuario')
            ->where('ingresos.' . $criterio, 'like', '%' . $buscar . '%')
            ->orderBy('ingresos.id','desc')
            ->paginate(5);
        }

        return [
            'pagination' => [
                'total'        => $ingresos->total(),
                'current_page' => $ingresos->currentPage(),
                'per_page'     => $ingresos->perPage(),
                'last_page'    => $ingresos->lastPage(),
                'from'         => $ingresos->firstItem(),
                'to'           => $ingresos->lastItem(),
            ],
            'ingresos' => $ingresos
        ];
    }

    public function obtenerCabecera(Request $request) {
        if (!$request->ajax()) return redirect('/');
        $id = $request->id;

        $ingreso = Ingreso::join('personas','ingresos.proveedor_id','=','personas.id')
        ->join('users','ingresos.usuario_id','=','users.id')
        ->select('ingresos.id','ingresos.tipo_comprobante','ingresos.serie_comprobante',
        'ingresos.num_comprobante','ingresos.fecha_hora','ingresos.impuesto','ingresos.total',
        'ingresos.estado','personas.nombre','users.usuario')
        ->where('ingresos.id','=',$id)
        ->orderBy('ingresos.id','desc')->take(1)->get();

        return [
            'ingreso' => $ingreso
        ];
    }

    public function obtenerDetalles(Request $request) {
        //if (!$request->ajax()) return redirect('/');
        $id = $request->id;

        $detalles = DetalleIngreso::join('articulos','detalle_ingresos.articulo_id','=','articulos.id')
        ->select('detalle_ingresos.cantidad','detalle_ingresos.precio','articulos.nombre as articulo')
        ->where('detalle_ingresos.ingreso_id','=',$id)
        ->orderBy('detalle_ingresos.id','desc')->get();

        return ['detalles' => $detalles]; 
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        try {
            DB::beginTransaction();
            $mytime = Carbon::now('Europe/Madrid');

            $ingreso = new Ingreso();
            $ingreso->proveedor_id = $request->proveedor_id;
            $ingreso->usuario_id = \Auth::user()->id; // Usuario autenticado
            $ingreso->tipo_comprobante = $request->tipo_comprobante;
            $ingreso->serie_comprobante = $request->serie_comprobante;
            $ingreso->num_comprobante = $request->num_comprobante;
            $ingreso->fecha_hora = $mytime->toDateString();
            $ingreso->impuesto = $request->impuesto;
            $ingreso->total = $request->total;
            $ingreso->estado = 'Registrado';
            $ingreso->save();

            $detalles = $request->data; // Array de detalles
            // Recorro todos los elementos
            foreach ($detalles as $key => $det) {
                $detalle = new DetalleIngreso();
                $detalle->ingreso_id = $ingreso->id;
                $detalle->articulo_id = $det['articulo_id'];
                $detalle->cantidad = $det['cantidad'];
                $detalle->precio = $det['precio'];
                $detalle->save();
            }
            /* $fechaActual= date('Y-m-d');
            $numVentas = DB::table('ventas')->whereDate('created_at', $fechaActual)->count(); 
            $numIngresos = DB::table('ingresos')->whereDate('created_at',$fechaActual)->count(); 

            $arregloDatos = [ 
            'ventas' => [ 
                        'numero' => $numVentas, 
                        'msj' => 'Ventas' 
                    ], 
            'ingresos' => [ 
                        'numero' => $numIngresos, 
                        'msj' => 'Ingresos' 
                    ] 
            ];                
            $allUsers = User::all();

            foreach ($allUsers as $notificar) { 
                User::findOrFail($notificar->id)->notify(new NotifyAdmin($arregloDatos)); 
            }   */

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
        
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $ingreso = Ingreso::findOrFail($request->id);
        $ingreso->estado = 'Anulado';
        $ingreso->save(); 
    }
}
