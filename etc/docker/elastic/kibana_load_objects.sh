#!/bin/sh
set -e

until curl -s "$KIBANA_HOST/api/status" > /dev/null; do
  echo "Esperando a que Kibana esté listo..."
  sleep 5
done

sleep 15

if [ -f /scripts/saved_objects/kibana.ndjson ]; then
  echo "Importando objetos guardados..."

  max_attempts=5
  attempt=1
  while [ $attempt -le $max_attempts ]; do
    response=$(curl -s -o /dev/null -w "%{http_code}" -X POST "$KIBANA_HOST/api/saved_objects/_import?overwrite=true" \
      -H 'kbn-xsrf: true' \
      --form file=@/scripts/saved_objects/kibana.ndjson)

    if [ "$response" = "200" ] || [ "$response" = "409" ]; then
      echo "Importación completada con código $response."
      break
    else
      echo "Error en la importación (código $response). Reintentando..."
      sleep 5
    fi
    attempt=$((attempt+1))
  done

else
  echo "No se encontraron objetos guardados para importar."
fi
