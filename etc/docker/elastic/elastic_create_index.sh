#!/bin/bash

set -e

until curl -s http://elasticsearch:9200 > /dev/null; do
  echo "Esperando a que Elasticsearch esté listo..."
  sleep 5
done

# Crear/actualizar el template 'audit_logs_template' para el data stream 'audit_logs'
curl -X PUT "http://elasticsearch:9200/_index_template/audit_logs_template" \
     -H 'Content-Type: application/json' \
     -d '
{
  "index_patterns": ["audit_logs*"],
  "data_stream": {},
  "template": {
    "settings": {
      "number_of_shards": 1,
      "number_of_replicas": 1
    },
    "mappings": {
      "dynamic": true,
        "dynamic_templates": [
          {
            "all_strings_with_keyword": {
              "match_mapping_type": "string",
              "mapping": {
                "type": "text",
                "fields": {
                  "keyword": {
                    "type": "keyword"
                  }
                }
              }
            }
          }
        ],
      "properties": {
        "@timestamp": {
          "type": "date"
        },
        "http_status_code": {
          "type": "integer"
        },
        "ip_address": {
          "type": "ip"
        },
        "user_id": {
          "type": "keyword"
        },
        "service": {
          "type": "text",
          "fields": {
            "keyword": {
              "type": "keyword"
            }
          }
        },
        "request_body": {
          "type": "object"
        },
        "response_body": {
          "type": "object"
        }
      }
    }
  }
}
'

echo "Template de índice audit_logs_template creado/actualizado."