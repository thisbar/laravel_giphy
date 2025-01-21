#!/bin/bash

set -e

until curl -s http://elasticsearch:9200 > /dev/null; do
  echo "Esperando a que Elasticsearch esté listo..."
  sleep 5
done

curl -X PUT "http://elasticsearch:9200/_index_template/audit_logs_template" -H 'Content-Type: application/json' -d'
{
  "index_patterns": ["audit_logs*"],
  "data_stream": {},
  "template": {
    "settings": {
      "number_of_shards": 1,
      "number_of_replicas": 1
    },
    "mappings": {
      "properties": {
        "user_id": { "type": "keyword" },
        "service": { "type": "text" },
        "request_body": { "type": "object" },
        "http_status_code": { "type": "integer" },
        "response_body": { "type": "object" },
        "ip_address": { "type": "ip" },
        "@timestamp": { "type": "date" }
      }
    }
  }
}'

echo "Template de índice creado."

curl -X POST "http://elasticsearch:9200/audit_logs/_doc" -H 'Content-Type: application/json' -d'
{
  "@timestamp": "'$(date -u +"%Y-%m-%dT%H:%M:%SZ")'",
  "user_id": "example-user",
  "service": "example-service",
  "request_body": {"example": "data"},
  "http_status_code": 200,
  "response_body": {"example": "response"},
  "ip_address": "127.0.0.1"
}'
echo "Data stream audit_logs creado y documento inicial indexado."
