#!/bin/sh
set -e

echo "Ejecutando elastic_create_index.sh..."
/bin/sh /scripts/elastic_create_index.sh

echo "Ejecutando kibana_load_objects.sh..."
/bin/sh /scripts/kibana_load_objects.sh

echo "Entrypoint completado, iniciando servicio..."
exec "$@"
