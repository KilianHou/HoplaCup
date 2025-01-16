#!/bin/bash

set -e

cd "$(dirname "$0")"

echo "Arrêt des services WordPress et suppression des conteneurs associés..."
# Arrête le service WordPress et sa base de données
docker compose stop wordpress db

echo "Suppression des conteneurs WordPress et de la base de données..."
docker compose rm -f wordpress db

echo "Nettoyage des volumes de la base de données..."
# Supprime spécifiquement le volume de la base de données
docker volume rm $(docker volume ls -q | grep db_data) || true

echo "Reconstruction et démarrage des services..."

docker compose up --build -d

cd hoplacup/wp-content/plugins/hoplacup_v2
mkdir uploads
cd ..
sudo chmod 777 -R hoplacup_v2
echo "Création du dossier upload"

echo "Terminé."
