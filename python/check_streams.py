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
        response = requests.get(url, headers=headers, timeout=timeout_seconds, stream=True, allow_redirects=True)
        
        status_code = response.status_code
        content_type = response.headers.get('Content-Type', '').lower()

        if status_code == 200:
            # Verificar Content-Type común para streams de audio
            # Puedes expandir esta lista si es necesario
            audio_content_types = [
                'audio/mpeg', 'audio/aac', 'audio/ogg', 'audio/aacp', 
                'application/octet-stream', 'audio/x-mpegurl', 'audio/x-scpls'
            ]
            
            is_audio_type = any(ct in content_type for ct in audio_content_types)

            if not is_audio_type:
                 # Si no es un tipo de audio conocido, podría ser una página HTML o algo más
                 # Podríamos ser más estrictos aquí si queremos, pero por ahora lo dejamos pasar si lee datos
                 pass # No retornamos inactivo aún, esperaremos a intentar leer

            try:
                # Intentar leer una pequeña porción del stream (ej. 1KB)
                # Esto ayuda a confirmar que no es solo una cabecera 200 OK vacía
                # o una página de error que devuelve 200.
                first_chunk = next(response.iter_content(chunk_size=1024, decode_unicode=False), None)
                
                if first_chunk:
                    # Si pudimos leer algo, consideramos el stream activo
                    return True, f"Stream activo (código: {status_code}, tipo: {content_type}, datos leídos)"
                else:
                    # No se pudo leer ningún dato, aunque el código fue 200
                    return False, f"Stream parece inactivo (código: {status_code}, tipo: {content_type}, no se pudieron leer datos)"
            except requests.exceptions.ChunkedEncodingError as ce:
                # A veces, si el stream se cierra abruptamente mientras se lee el chunk
                return False, f"Error leyendo stream (ChunkedEncodingError): {str(ce)}"
            except Exception as e_read:
                # Otro error al intentar leer el stream
                return False, f"Error al intentar leer datos del stream: {str(e_read)}"
        else:
            return False, f"Stream inactivo (código: {status_code}, tipo: {content_type})"
            
    except requests.exceptions.Timeout:
        return False, f"Timeout después de {timeout_seconds} segundos"
    except requests.exceptions.RequestException as e:
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
