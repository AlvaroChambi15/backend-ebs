<?php

namespace App\Http\Controllers;

use App\Models\Reserve;
use App\Models\Horario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReserveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return response()->json(["requestEnviado" => $request]);

        /* $solicitudes = Reserve::orderBy('created_at', 'desc')->paginate($request->rows);
        return response()->json($solicitudes, 200); */
    }


    public function getSolicitudes(Request $request)
    {
        // return $request;
        $filterState = $request->searchEstado ? $request->searchEstado : null;
        $filterSearch = $request->searchSoli ? $request->searchSoli : null;

        // $request->searchEstado ? 'verdadero' : 'falso';

        /* if ($filterName) {
            return response()->json(["respuesta" => "Existe el dato de nameGlobal", "dato" => $filterName], 200);
        } else {
            return response()->json(["respuesta" => "NO existe el dato de nameGlobal", "dato" => $filterName], 200);
        } */

        // $searchEstado

        // $solicitudes = Reserve::orderBy('created_at', 'desc')->paginate($request->row);
        // $solicitudes = Horario::with('reserva')->where('estado', 'SOLICITUD')->orWhere('estado', 'POR CONFIRMAR')->orWhere('estado', 'CANCELADO')->orderBy('created_at', 'desc')->paginate($request->rowPage);

        /*         $solicitudes = Horario::with("reserva")
            ->orwhere('estado', 'SOLICITUD')
            ->orWhere('estado', 'POR CONFIRMAR')
            ->orWhere('estado', 'CANCELADO')
            ->orderBy('created_at', 'desc')
            ->state($filterState)
            ->paginate($request->rowPage); */


        $queryBilderSoli = Reserve::with("horarios")
            ->select(["*", DB::raw("CONCAT(nombres,' ',apellidos)  AS fullname")])

            ->orwhereRelation('horarios', 'estado', 'LIKE', 'SOLICITUD')
            ->orwhereRelation('horarios', 'estado', 'LIKE', 'POR CONFIRMAR')
            ->orwhereRelation('horarios', 'estado', 'LIKE', 'CANCELADO')
            ->fullname($filterSearch)
            ->state($filterState)
            ->orderBy('created_at', 'desc');


        $solicitudes = $queryBilderSoli->paginate($request->rowPage);

        /*               $solicitudes = Reserve::with("horarios")
            ->select(["*", DB::raw("CONCAT(nombres,' ',apellidos)  AS fullname")])
            // ->orwhere('estado', 'SOLICITUD')
            ->orwhereRelation('horarios', 'estado', 'LIKE', 'SOLICITUD')
            // ->orWhere('estado', 'POR CONFIRMAR')
            ->orwhereRelation('horarios', 'estado', 'LIKE', 'POR CONFIRMAR')
            // ->orWhere('estado', 'CANCELADO')
            ->orwhereRelation('horarios', 'estado', 'LIKE', 'CANCELADO')
            ->state($filterState)
            ->orderBy('created_at', 'desc')
            // ->fullname($filterName)
            ->paginate($request->rowPage);

 */

        /* $solicitudes = Horario::with(['reserva' => function ($q) {
            $q->select([
                "*",
                DB::raw("CONCAT(nombres,' ',apellidos)  AS fullname")
            ]);
        }])
            ->orwhere('estado', 'SOLICITUD')
            ->orWhere('estado', 'POR CONFIRMAR')
            ->orWhere('estado', 'CANCELADO')
            ->orderBy('created_at', 'desc')
            ->fullname($filterName)
            // ->whereRelation('reserva', 'nombres', 'LIKE', "%$filterFullName%")
            ->state($filterState)
            ->paginate($request->rowPage); */


        // $solicitudes = Reserve::with('horarios')->where('horario.estado', 'SOLICITUD')->orWhere('horario.estado', 'POR CONFIRMAR')->orWhere('horario.estado', 'CANCELADO')->orderBy('created_at', 'desc')->paginate($request->row);
        // $solicitudes = Reserve::with('horarios')->where('horarios.estado', 'SOLICITUD')->orderBy('created_at', 'desc')->paginate($request->row);

        // $solicitudes = Reserve::orderBy('created_at', 'desc')->paginate($request->row);
        return response()->json($solicitudes, 200);
        // return response()->json(["datos" => $solicitudes, "datosEnviados" => $request], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }


    /* * Guardando una soicitud de pacientes. */

    public function storeSolicitud(Request $request)
    {
        // return $request;

        $request->validate([
            "nombres" => "required|min:2|max:60",
            "apellidos" => "required|min:2|max:60",
            "email" => "max:80",
            "celular" => "required|digits:8"
        ]);

        DB::begintransaction();
        try {

            /* CONVIRTIENDO EL ESPACIO "tratamiento" en un array */
            $tratArray = (array) $request->tratamiento;
            /* OBTEMOS EL PRIMER ELEMENTO DEL ARRAY tratamiento */
            $fisrtNameTrat = reset($tratArray);
            /* CONVIRTIENDO EL PRIMER ELEMENTO DE "tratameinto" en un JSON VALIDO */
            $nameTrat = json_encode($fisrtNameTrat);

            // ================================================

            $solicitud = new Reserve;

            $solicitud->nombres = $request->nombres;
            $solicitud->apellidos = $request->apellidos;
            $solicitud->edad = $request->edad;
            $solicitud->email = $request->email;
            $solicitud->celular = $request->celular;
            $solicitud->contacto_llamada = $request->contactTelefono;
            $solicitud->contacto_whatsapp = $request->contactWsp;
            $solicitud->contacto_correo = $request->contactEmail;

            $solicitud->especialidad = $nameTrat;
            $solicitud->fecha_soli = $request->fechaSoli;
            $solicitud->hora_soli = $request->horaSoli;

            $solicitud->save();

            //Creando su Horario

            $horario = new Horario;

            $horario->fecha = $request->fechaSoli;
            $horario->hora = $request->horaSoli;
            $horario->reserve_id = $solicitud->id;
            $horario->estado = "SOLICITUD";

            $horario->save();

            DB::commit();
            return response()->json(["mensaje" => "solicitud-almacenada"], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["mensaje" => "Algo ocurrio al enviar la solicitud", "error" => $e], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Reserve  $reserve
     * @return \Illuminate\Http\Response
     */
    public function show(Reserve $reserve)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reserve  $reserve
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reserve $reserve)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Reserve  $reserve
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reserve $reserve)
    {
        //
    }
}