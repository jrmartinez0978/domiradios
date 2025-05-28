#!/usr/bin/env python3
import sys
import json
import requests

def check_stream(url):
    """
    Verifica si una URL de stream está activa.
    Intenta leer una pequeña porción del stream y verifica el Content-Type.
    Devuelve: (bool, str) - (está_activo, mensaje)
    """
    if not url or not url.startswith(('http://', 'https://')):
        return False, "URL inválida"

    headers = {
        'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
        'Accept': '*/*' # Aceptar cualquier tipo de contenido
    }
    timeout_seconds = 10 # Timeout un poco más generoso

    try:
        # Intentar con GET y stream=True para poder leer el contenido por partes
        print(f"DEBUG PYTHON: Intentando HEAD request a {url} con timeout {timeout_seconds / 2}", flush=True)
        head_response = requests.head(url, headers=headers, timeout=timeout_seconds / 2, allow_redirects=True)
        print(f"DEBUG PYTHON: HEAD Status: {head_response.status_code}, Content-Type: {head_response.headers.get('Content-Type')}", flush=True)
        if head_response.status_code == 200:
            content_type = head_response.headers.get('Content-Type', '').lower()
            print(f"DEBUG PYTHON: HEAD Content-Type procesado: {content_type}", flush=True)

            # Verificar Content-Type común para streams de audio
            # Puedes expandir esta lista si es necesario
            audio_content_types = [
                'audio/mpeg', 'audio/aac', 'audio/ogg', 'audio/aacp', 
                'application/octet-stream', 'audio/x-mpegurl', 'audio/x-scpls'
            ]
            
            print(f"DEBUG PYTHON: Verificando HEAD Content-Type contra {audio_content_types}", flush=True)
            is_audio_type = any(audio_type in content_type for audio_type in audio_content_types)

            if not is_audio_type:
                print(f"DEBUG PYTHON: GET Content-Type '{content_type}' no es de audio. Stream INACTIVO.", flush=True)
                return False, f"Stream content type {content_type} not audio"

            try:
                # Intentar leer una pequeña porción del stream (ej. 1KB)
                # Esto ayuda a confirmar que no es solo una cabecera 200 OK vacía
                # o una página de error que devuelve 200.
                response = requests.get(url, headers=headers, timeout=timeout_seconds, stream=True, allow_redirects=True)
                print(f"DEBUG PYTHON: GET Status: {response.status_code}, Content-Type: {response.headers.get('Content-Type')}", flush=True)
                read_chunk_size = 1024
                print(f"DEBUG PYTHON: GET Content-Type OK. Intentando leer chunk de {read_chunk_size} bytes.", flush=True)
                chunk = next(response.iter_content(chunk_size=read_chunk_size, decode_unicode=False), None)
                if chunk:
                    print(f"DEBUG PYTHON: Chunk leído exitosamente ({len(chunk)} bytes). Stream ACTIVO.", flush=True)
                    return True, f"Stream activo (código: {response.status_code}, tipo: {content_type}, datos leídos)"
                else:
                    print(f"DEBUG PYTHON: No se pudo leer chunk. Stream INACTIVO.", flush=True)
                    return False, f"Stream parece inactivo (código: {response.status_code}, tipo: {content_type}, no se pudieron leer datos)"
            except requests.exceptions.ChunkedEncodingError as ce:
                # A veces, si el stream se cierra abruptamente mientras se lee el chunk
                return False, f"Error leyendo stream (ChunkedEncodingError): {str(ce)}"
            except Exception as e_read:
                # Otro error al intentar leer el stream
                return False, f"Error al intentar leer datos del stream: {str(e_read)}"
        else:
            return False, f"Stream inactivo (código: {head_response.status_code}, tipo: {content_type})"
            
    except requests.exceptions.Timeout as e:
        print(f"DEBUG PYTHON: Timeout exception: {str(e)}", flush=True)
        return False, f"Timeout después de {timeout_seconds} segundos"
    except requests.exceptions.RequestException as e:
        print(f"DEBUG PYTHON: RequestException: {str(e)}", flush=True)
        return False, f"Error de conexión: {str(e)}"
    except Exception as e_global:
        return False, f"Error inesperado al verificar: {str(e_global)}"

# Uso del script como programa independiente
if __name__ == "__main__":
    if len(sys.argv) != 2:
        print(json.dumps({"success": False, "is_active": False, "message": "Se requiere una URL como argumento"}))
        sys.exit(1)
        
    target_url = sys.argv[1]
    active_status, status_message = check_stream(target_url)
    
    result = {
        "success": True, # Indica que el script se ejecutó, no necesariamente que el stream esté activo
        "is_active": active_status,
        "message": status_message,
        "url": target_url
    }
    
    print(json.dumps(result))
