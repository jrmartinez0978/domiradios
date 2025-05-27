#!/usr/bin/env python3
import sys
import json
import requests

def check_stream(url):
    """
    Verifica simplemente si una URL responde con código 200 OK
    Devuelve: (bool, str) - (está_activo, mensaje)
    """
    try:
        # Verificar si la URL es válida
        if not url or not url.startswith(('http://', 'https://')):
            return False, "URL inválida"
            
        # Realizar la verificación HTTP
        headers = {
            'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
        }
        
        # Intentar primero con HEAD
        try:
            response = requests.head(url, headers=headers, timeout=8, allow_redirects=True)
            
            # Si la respuesta es 200 OK, consideramos el stream como activo
            if response.status_code == 200:
                return True, f"Stream activo (código: 200 OK)"
                
            # Si HEAD no devuelve 200, intentamos con GET
            response = requests.get(url, headers=headers, timeout=8, stream=True, allow_redirects=True)
            
            # Si la respuesta GET es 200 OK, consideramos el stream como activo
            if response.status_code == 200:
                return True, f"Stream activo (código: 200 OK)"
            else:
                return False, f"Stream inactivo (código: {response.status_code})"
                
        except requests.RequestException as e:
            return False, f"Error de conexión: {str(e)}"
            
    except Exception as e:
        return False, f"Error al verificar: {str(e)}"

# Uso del script como programa independiente
if __name__ == "__main__":
    if len(sys.argv) != 2:
        print(json.dumps({"success": False, "error": "Se requiere una URL como argumento"}))
        sys.exit(1)
        
    url = sys.argv[1]
    is_active, message = check_stream(url)
    
    result = {
        "success": True,
        "is_active": is_active,
        "message": message,
        "url": url
    }
    
    print(json.dumps(result))
