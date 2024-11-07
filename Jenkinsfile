pipeline {
    agent any

    environment {
        // Variables de entorno necesarias para SonarQube
        SONARQUBE_SERVER = 'SonarQube'  // Nombre del servidor SonarQube configurado en Jenkins
        SONAR_HOST_URL = 'http://10.30.212.47:9000'  // URL del servidor SonarQube
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
        stage('Deploy to Web Server') {
            steps {
                // Usar credenciales SSH para conectarse al servidor web
                sshagent(['webserver_ssh_credentials_id']) {
                    sh '''
                        ssh up2g1@10.30.212.47 'cd /var/www/html/Proyecto2-Web && git clone https://github.com/DavidF2803/Proyecto2.git || (cd /home/up2g1 && git pull)'
                    '''
                }
            }
        }
        stage('ZAP Analysis') {
            steps {
                script {
                    // Ejecutar ZAP dentro de un contenedor Docker sin usar zap-cli
                    docker.image('zaproxy/zap-stable').inside('--network host') {
                        sh '''
                            # Iniciar ZAP en modo demonio
                            zap.sh -daemon -host 10.30.212.47 -port 8090 -config api.disablekey=true &
                            # Esperar a que ZAP esté listo
                            timeout=120
                            while ! curl -s http://10.30.212.47:8090; do
                                sleep 5
                                timeout=$((timeout - 5))
                                if [ $timeout -le 0 ]; then
                                    echo "ZAP no se inició a tiempo"
                                    exit 1
                                fi
                            done
                            # Ejecutar el escaneo completo con zap-full-scan.py
                            zap-full-scan.py -t http://10.30.212.47/Pipeline_P2 -r zap_report.html -I
                            # Apagar ZAP
                            zap.sh -cmd -shutdown
                        '''
                    }
                }
                // Publicar el reporte de ZAP
                publishHTML(target: [
                    reportDir: "${env.WORKSPACE}",
                    reportFiles: 'zap_report.html',
                    reportName: 'Reporte ZAP'
                ])
            }
        }
    }
}
