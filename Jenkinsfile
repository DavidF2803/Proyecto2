pipeline {
    agent any

    environment {
        // Variables de entorno necesarias para SonarQube
        SONARQUBE_SERVER = 'SonarQube'  // Nombre del servidor SonarQube configurado en Jenkins
        SONAR_HOST_URL = 'http://10.30.212.24:9000'  // URL del servidor SonarQube
        PATH = "/opt/sonar-scanner-6.2.1.4610-linux-x64/bin:${env.PATH}"  // Ruta del sonar-scanner en tu sistema
    }

    stages {
        // 1. Etapa de Checkout para clonar el código desde el repositorio
        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        // 3. Etapa de Análisis de SonarQube
        stage('SonarQube Analysis') {
            steps {
                withSonarQubeEnv(SONARQUBE_SERVER) {  // Usamos el nombre correcto del servidor
                    // Ejecutar el scanner de SonarQube
                    sh """
                        sonar-scanner \
                        -Dsonar.projectKey=Pipeline_P2 \
                        -Dsonar.sources=. \
                        -Dsonar.php.version=8.0
                    """
                }
            }
        }

        // 5. Etapa de Espera para el Quality Gate de SonarQube
          stage('Quality Gate') {
            steps {
                // Esperar el resultado del Quality Gate
                timeout(time: 1, unit: 'HOURS') {
                    waitForQualityGate abortPipeline: true
                }
            }
          }
        }
    }
