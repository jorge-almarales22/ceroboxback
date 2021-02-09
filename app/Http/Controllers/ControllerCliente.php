<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ControllerCliente extends Controller
{

	public function index()
	{
		$clientes = Cliente::get();
		return $clientes;
		//devolvemos todos los clientes
	}
    public function store(Request $request)
    {
    	$credentials = $request->only('servicio','nombre', 'cedula', 'correo', 'imagen', 'telefono', 'observaciones');   

        $validator = Validator::make($credentials, [
        	'servicio' => 'required',
            'nombre' => 'required',
            'cedula' => 'required',
            'correo' => 'required',
            'imagen' => 'required',
            'telefono' => 'required',
            'observaciones' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Los campos no son correctos',
                'error' => $validator->errors()
            ],422);
        }
        //validamos el request para verificar que los campos que se van a guardar son los correctos
        $exploded = explode(',', $request->imagen);
        $decoded = base64_decode($exploded[1]); //la imagen vienen en base64
        if(str_contains($exploded[0], 'jpg')){ //le hacemos un exploded y verificamos si es una imagen
            $extension = 'jpg';
        }else{
            $extension = 'png';
        }
        $filename = Str::random().'.'.$extension;
        $path = public_path().'/'.$filename; // esta será la direccion donde se guardará las imagenes, en la carpeta pública con el nombre de la imagen que en este caso la coloco aleatoria.
        file_put_contents($path, $decoded); //acá guardamos en carpeta publica con la imagen
        $cliente = new Cliente();
        $cliente->servicio_id = $request->servicio;
        $cliente->nombre = $request->nombre;
        $cliente->cedula = $request->cedula;
        $cliente->correo = $request->correo;
        $cliente->imagen = $filename;       
        $cliente->telefono = $request->telefono;
        $cliente->observaciones = $request->observaciones;
        $cliente->save(); //guardamos el cliente
        return $cliente;
    }

    public function update(Request $request, $id)
    {
    	$credentials = $request->only('servicio','nombre', 'cedula', 'correo', 'imagen', 'telefono', 'observaciones');   

        $validator = Validator::make($credentials, [
        	'servicio' => 'required',
            'nombre' => 'required',
            'cedula' => 'required',
            'correo' => 'required',
            'imagen' => 'required',
            'telefono' => 'required',
            'observaciones' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Los campos no son correctos',
                'error' => $validator->errors()
            ],422);
        }
        $exploded = explode(',', $request->imagen);
        $decoded = base64_decode($exploded[1]);
        if(str_contains($exploded[0], 'jpg')){
            $extension = 'jpg';
        }else{
            $extension = 'png';
        }
        $filename = Str::random().'.'.$extension;
        $path = public_path().'/'.$filename;
        file_put_contents($path, $decoded);
        $cliente = Cliente::find($id);
        // unlink(public_path().'/'.$cliente->imagen); esta linea de codigo sirve para cuando estemos en produccion no hayan dos imagenes del mismo cliente y asi ahorrar espacio en el servidor.
        $cliente->servicio_id = $request->servicio;
        $cliente->nombre = $request->nombre;
        $cliente->cedula = $request->cedula;
        $cliente->correo = $request->correo;
        $cliente->imagen = $filename;       
        $cliente->telefono = $request->telefono;
        $cliente->observaciones = $request->observaciones;
        $cliente->save();
        return $cliente;
    }
    public function destroy($id){
    	$cliente = Cliente::find($id);
    	// unlink(public_path().'/'.$cliente->imagen); esta linea de codigo sirve para cuando estemos en produccion no hayan dos imagenes del mismo cliente y asi ahorrar espacio en el servidor.
    	$cliente->delete();
    }
}
