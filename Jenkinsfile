pipeline {
    agent any

    environment {
        APP_NAME    = 'isi-burger'
        DOCKER_IMAGE = 'isi-burger:latest'
        GIT_BRANCH  = 'NDIAYE_AmadouSedikh_burger'
    }

    stages {

        stage('Pull du code') {
            steps {
                echo 'Récupération du code depuis GitHub...'
                git branch: "${GIT_BRANCH}",
                    url: 'https://github.com/sadikh03/Examen_LARAVEL_NDIAYE_Amadou_Sedikh_l3gl_2025_2026.git'
            }
        }

        stage('Installation des dépendances Laravel') {
            steps {
                echo 'Installation Composer...'
                sh 'composer install --no-dev --optimize-autoloader --no-interaction'

                echo 'Installation NPM...'
                sh 'npm ci && npm run build'

                echo 'Configuration .env...'
                sh '''
                    cp .env.example .env
                    php artisan key:generate
                '''
            }
        }

        stage('Migrations') {
            steps {
                echo 'Exécution des migrations...'
                sh 'php artisan migrate --force'
            }
        }

        stage('Build image Docker') {
            steps {
                echo 'Construction de l\'image Docker...'
                sh "docker build -t ${DOCKER_IMAGE} ."
            }
        }

        stage('Déploiement') {
            steps {
                echo 'Arrêt des anciens conteneurs...'
                sh '''
                    docker-compose down || true
                    docker-compose up -d
                '''
            }
        }
    }

    post {
        success {
            echo 'Pipeline terminé avec succès !'
        }
        failure {
            echo 'Echec du pipeline. Vérifie les logs.'
        }
    }
}

