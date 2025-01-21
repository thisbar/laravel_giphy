#!/bin/sh
set -e

until curl -s "$KIBANA_HOST/api/status" > /dev/null; do
  echo "Esperando a que Kibana esté listo..."
  sleep 5
done

if [ -f /data/saved_objects/kibana.ndjson ]; then
  echo "Importando objetos guardados..."
  curl -X POST "$KIBANA_HOST/api/saved_objects/_import?overwrite=true" \
    -H 'kbn-xsrf: true' \
    --form file=@/data/saved_objects/kibana.ndjson
  echo "Importación completada."
else
  echo "No se encontraron objetos guardados para importar."
fi
