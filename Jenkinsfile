pipeline {
    agent any

    stages {
        stage('Start Containers') {
            steps {
                sh 'docker compose up -d'
            }
        }

        stage('Install Dependencies') {
            steps {
                sh 'docker compose exec -T php composer install'
            }
        }

        stage('Unit Tests') {
            steps {
                sh 'docker compose exec -T php vendor/bin/phpunit'
            }
        }
    }

    post {
        always {
            sh 'docker compose down'
        }
    }
}